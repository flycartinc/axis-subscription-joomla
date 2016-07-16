<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Site\Controller;

defined('_JEXEC') or die;
use Flycart\Axisubs\Admin\Controller\Mixin;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Container\Container;
use FOF30\Controller\Controller;
use JFactory;
use JText;
use JModel;
use JLoader;
use JRoute;
use JURI;

class Subscribe extends Controller
{
	/**
	 * Overridden. Limit the tasks we're allowed to execute.
	 *
	 * @param   Container  $container
	 * @param   array      $config
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);
	
		$this->predefinedTaskList = ['subscribe','renew'];
	
		$this->cacheableTasks = [];
	
		// Force the view name, required because I am extending the Levels (plural) controller but I want my view
		// templates to be read from the Level (singular) directory.
		$this->viewName = 'Subscribe';
	}

	public function execute($task){
		parent::execute($task);
	}

	public function userRefTest(){
		$app = JFactory::getApplication();
		$session = $app->getSession();
	 	$user_id = \JFactory::getUser()->id; 
	 	Axisubs::plugin()->event( 'UserRefresh',array($user_id) );
	}
	/**
	 * Register view required data
	 *  */
	public function onBeforeDefault(){
		$app = JFactory::getApplication();
		$session = $app->getSession();
	 	$user_id = \JFactory::getUser()->id; 
		$subscription_id = 0 ;

		$view = $this->getView();
		$view->setLayout('default');
		$view->user = array() ;
		$plan_id = $this->input->get('id',0);
		$plan_id = $this->input->get('plan_id',$plan_id);
		$plan_slug = $this->input->get('plan','');
		if (!empty($plan_slug) || $plan_id > 0) {
			$plan_model = $this->getModel('Plans');
			$plan_model->load( array('slug'=>$plan_slug) );
			$view->plan = $plan_model->getData();
			$plan_id = $plan_model->axisubs_plan_id ;
		}else {
			// redirect them to a plans browse view or probably get the redirect url from params
			$redirect_url = JRoute::_('index.php?option=com_axisubs&view=Plans');
			$this->setRedirect($redirect_url);
			return ;
		}

		// check if user has enough acess to the plan
		if ( !$plan_model->isEligible($user_id) ){
			$redirect_url = JRoute::_('index.php?option=com_axisubs&view=Plans');
			$this->setRedirect($redirect_url,JText::_('AXISUBS_ERROR_INVALID_ACCESS_CANNOT_SUBSCRIBE'),'error');
			return ;
		}
		// get the customer details and set it in the view
		$cust_model = $this->getModel('Customers');
		$cust_model->load( array('user_id'=>$user_id) );
		
		$view->customer = $cust_model;

		$subscription_id = $this->getSubscriptionId( $plan_model );

		// intialize subscription record
		$subscription = $this->getModel('Subscriptions');
		if ( $subscription_id > 0 ) {
			$subscription->load( $subscription_id );
		} else {
			$subscription->load( 0 );
			$subscription->user_id = $user_id;
			$subscription->plan_id = $plan_id;
			$d = array() ;
			$subscription->onAfterBind($d); // this will calculate totals and dates
		}

		if (!$subscription->isEligibleForSubscription($user_id) ){
			$redirect_url = JRoute::_('index.php?option=com_axisubs&view=Plans');
			$this->setRedirect($redirect_url,JText::_('AXISUBS_ERROR_INVALID_ACCESS_CANNOT_SUBSCRIBE'),'error');
			return ;
		}

		$view->subscription = $subscription ;

		// get the Payment plugins and set it in the view object
		$paymentFactory = Axisubs::payment();
		$paymentFactory->initialize( $subscription_id ) ;
		$enabledPaymentPlugin = $plan_model->payment_plugins;
		$pluginList = $paymentFactory->getPaymentMethods($enabledPaymentPlugin);

		$view->payments = $pluginList;
	}

	public function subscribeUser(){
		$app = JFactory::getApplication();
		$session = $app->getSession();
		$customer  = $app->input->post->get('customer', array(), 'array');
		$logger = Axisubs::logger();
		$plugin_helper = Axisubs::plugin();
		$user_id = JFactory::getUser()->id;
		$customer_id = 0;
		$subscription_id = 0 ;
		
		$subscription_id = $this->getSubscriptionId();

		$billing_data  = $app->input->post->get('billing_address', array(), 'array');
		$plan_id = $app->input->get('plan_id',0);

		$result = array();
		$errors = array();
		$customer_errors = array();

		$customer_model = $this->getModel('Customers');
		// if the customer has logged in 
		if( $user_id <= 0 ) {
			$customer_errors = $customer_model->validateAccountDetails($customer);
			// account details are valid 
			if( count($customer_errors) <= 0 ){
				// create an account
				$is_account_created = false;
				try {
					$user_id = $customer_model->createNewCustomer($customer);

					$plugin_helper->event('CustomerSignUp',array($user_id, $customer));

					if (!empty($user_id)){
						$is_account_created = true;
					}
				} catch (Exception $e) {
					// get the error and handle response
					$result['error'] = $e->getMessage();
					$this->handleResponse($result);
				}

				// login the user
				if ( $is_account_created ) {
					if(isset($customer['email']) && isset($customer['password1'])){
						$credentials = array();
						$credentials['username'] = $customer['email'];
						$credentials['password'] = $customer['password1'];
						$success = $app->login($credentials);
						$logger->log('user logged in',$success);
					}
				}
			}
		}
		
		$errors = $customer_model->validateAddress( $billing_data );

		$errors = array_merge( $errors, $customer_errors );

		if ( count($errors) > 0 ) {
			$result['error'] = $errors ;
			$this->handleResponse($result);
		}

		// no errors then create the customer record and set id in session
		try {
			$customer_model->load( array('user_id' => $user_id) ) ;
			$customer_model->bind( $customer );
			$customer_model->bind( $billing_data );
			$customer_model->user_id = $user_id ;

			$session->get('customer_billing_country', $customer_model->country ,'axisubs');
			$session->get('customer_billing_state', $customer_model->state ,'axisubs');
			$session->get('customer_billing_zip', $customer_model->zip ,'axisubs');
			$session->get('customer_billing_city', $customer_model->city ,'axisubs');
			
			$cust_store_success = $customer_model->store();	
			$customer_id = $customer_model->axisubs_customer_id;
			 
		} catch (\Exception $e) {
			$result['error'] = $e->getMessage() ;
			$this->handleResponse($result);
		}

//////////////////////////// customer created
		// get  currency
		$currency_code = Axisubs::currency()->getCode();
		$currency_val = Axisubs::currency()->getValue();
//////////////////////////// create subscription
		if ( $customer_id > 0 && $plan_id > 0) {
			$plan_quantity = 1; // later implement plan quantity
			$subs_model = $this->getModel('Subscriptions');
			$subs_data = ['user_id' 		=> 	$customer_model->user_id, 
						  'plan_id'			=>	$plan_id ,
						  'subscription_id' =>  $subscription_id,
						  'plan_quantity'	=>	$plan_quantity ,
						  'currency_code' 	=> 	$currency_code,
						  'currency_value' 	=> 	$currency_val, 
						  'language'		=>  JFactory::getLanguage()->getTag() ];
			try {
				$subs_model->save( $subs_data );
				$subs_model->updateSubscriptionInfo();

				$subscription_id = $subs_model->axisubs_subscription_id ; 

				$session->set('subscription_id', $subscription_id, 'axisubs');

			} catch (\RuntimeException $e) {
				$result['error'] = $e->getMessage() ;
				$this->handleResponse($result);
			}
		}
//////////////////////////// subscription created 
		if ( $subscription_id <= 0 ){
			$result['error'] =  'Unable to create subscription please retry';
			$this->handleResponse($result);
			return; // some error - subscription not created - return to view and retry
		}
//////////////////////////// prepare Payment plugins

		$paymentFactory = Axisubs::payment() ;
		$paymentFactory->initialize( $subscription_id );

		$payment_plugin = $app->input->get('payment_plugin','');
		
		if( empty($payment_plugin) ){
			$result['error']['axisubs_payment_form_validation'] = JText::_('AXISUBS_PAYMENT_METHOD_MANDATORY');
			$is_valid = false;
			$this->handleResponse($result);
			// log it - did not choose the Payment gateway maybe has some js errors or difficulties
		}

		$payment_values = $app->input->post->get('Payment', array(), 'array');

		// if the Payment fields are empty then send an error message
		// try to get the Payment fields in other way

		//validate the selected Payment
		try {
			$is_valid = $paymentFactory->validateSelectPayment( $payment_plugin, $payment_values );
		} catch (\Exception $e) {
			$result['error']['axisubs_payment_form_validation'] = $e->getMessage();
			$is_valid = false;
		}

		if (!$is_valid){
			$this->handleResponse( $result );
		}

		// set the subscription id, Payment method and all params in session		
		$session->set('payment_method', $payment_plugin, 'axisubs');
		$session->set('payment_values', $payment_values, 'axisubs');

		$result['redirect'] = JRoute::_('index.php?option=com_axisubs&view=Subscribe&task=paySubscription');
		$this->handleResponse( $result ); 
//////////////////////////// everything is fine, just redirect and process the Payment
	}

	/**
	 * Task to load the prePayment layout and redirect the customer to a Payment gateway.
	 *  <<< TODO: This method could be rewritten or merged with above view itself
	 * 	For a better design and conversion rate - we need to remove dependancy from Joomla session variables and 
	 *  Just process the Payment request too in a single screen. With help of Javascript functions. >>>
	 * */
	function paySubscription(){
		// load the subscriptions and perform some prechecks - isEligibleForPayment()
		$app = JFactory::getApplication();
		$session = $app->getSession();
		$view = $this->getView();
		$subscription_id = $this->getSubscriptionId();
		$error = false;

		if ( $subscription_id > 0 ){
			
			$subs_model = $this->getModel('Subscriptions');
			$subs_model->load( $subscription_id ) ;

			if ( ! $subs_model->isEligibleForPayment() ){
				$error = true; // handle error
			}

			// Initialize transaction object
			$subs_model->initTransaction();

			if ( $session->has('payment_method', 'axisubs') ){
		 		$payment_plugin = $session->get('payment_method', '' , 'axisubs');
			}
			if ( empty($payment_plugin) ){
				$payment_plugin = $app->input->get('payment_method','');
			}
			if ( empty($payment_plugin) ){
				$error = true; // redirect back to the first subscribe screen
			}	

			if ( !$error ){
			 	$payment_values = $session->get('payment_values', '' , 'axisubs');

				// trigger prepayment and get the form - intialize with Payment values
				$paymentFactory = Axisubs::payment() ;
				$paymentFactory->initialize( $subscription_id );
				$prePaymentForm = $paymentFactory->getPrePaymentForm( $payment_plugin );
				$view->payment_method = $payment_plugin ;
				$view->prePaymentForm = $prePaymentForm ;
				$error = false;
			}
			
			if (!$error){
				$view->subscription = $subs_model;

				$view->subscription_id = $subs_model->axisubs_subscription_id;
				$view->plan_id = $subs_model->plan_id;
				$view->user_id = JFactory::getUser()->id;

				$plan_model = $this->getModel('Plans');
				$plan_model->load( $subs_model->plan_id );
				$view->plan = $plan_model;
				$view->setLayout('payment');
				$view->display();
			}

		}else{
			$app->redirect('index.php'); //  redirect to previous page
		}
		return;
	}

	function confirmPayment(){
		// handle the post Payment task here

		$app = JFactory::getApplication();
		$session = $app->getSession();
		$view = $this->getView();
		$subscription_id = 0;
		//$subscription_id = $this->getSubscriptionId();
		$values = $app->input->getArray ( $_POST );
		$error = false;

		$payment_plugin = $app->input->get('orderpayment_type','');
		if ( empty($payment_plugin) ){
			// sometimes this is got from Payment gateway, log the error
			$error = true; // redirect back to the first subscribe screen
		}

		if (!$error) {
			// trigger postpayment to update the subscription status
			$paymentFactory = Axisubs::payment() ;
			
			if ( $subscription_id > 0 ) {
				$paymentFactory->initialize( $subscription_id );
			}

			$postPaymentForm = $paymentFactory->getPostPaymentForm( $payment_plugin , $values );
			$view->postPaymentForm = $postPaymentForm;
			
			// after Payment remove subscription id from session
			$session->clear('subscription_id', 'axisubs');
			$session->clear('plan_id', 'axisubs');
			$session->clear('user_id', 'axisubs');

			$error = false;
		}

		if (!$error){
			$view->setLayout('postpayment');
			$view->display();
		}else{
			$app->redirect('index.php'); //  redirect to previous page
		}

		return;
	}

	function paymentCallback(){
		// handle Payment callback
	}

	/**
	 * Method or task to handle renewals
	 * */
	function renew(){

		$app = JFactory::getApplication();
		$view = $this->getView();
		$error = false;

		$plan_slug = $app->input->get('slug', null, 'string');
		$plan_slug = trim($plan_slug , ' ');
		$plan_id = $app->input->get('plan_id', 0, 'int');
		$user_id = \JFactory::getUser()->id;
		$subscription_id = $app->input->get('subscription_id',0);
		$subs_model = $this->getModel('Subscriptions');
		$plan_model = $this->getModel('Plans');

		if ( $user_id <= 0 ) {
			$return_url = JRoute::_('index.php?option=com_axisubs&view=profile');
			$encoded_return_url = base64_encode( $return_url );
			$this->setRedirect( 'index.php?option=com_users&view=login&return='.$encoded_return_url,
									JText::_('COM_AXISUBS_PROFILE_LOGIN_TO_RENEWAL'),'warning' );
			return ;
		}

		// get recent subscription record
			// get subscription id from input and check if recent
			if ( $subscription_id > 0 ) {
				// check if recent subscription and if renewal allowed for this subscription
				if ( $user_id > 0 ){
					$curr_subs = $subs_model->find( $subscription_id );
					if ($curr_subs->user_id != $user_id){
						// forbid renewal
						$this->setRedirect('index.php?option=com_axisubs&view=profile',
										JText::_('COM_AXISUBS_ERROR_RENEWAL_NOT_ALLOWED'),'error' );
						return;
					}
					$plan_id = $curr_subs->plan_id;
				}
			}
			// If the ID is not set but slug is let's try to find the level by slug
			if (!$plan_id && $plan_slug)
			{
				$plan = $plan_model
					->id(0)
					->slug($plan_slug)
					->firstOrNew();

				$plan_id = $plan->getId();
				$plan_slug = $plan->slug;
			}

			if (!$plan_id)
			{
				// no plan id throw an error message / redirect to profile view
				$this->setRedirect( 'index.php?option=com_axisubs&view=profile');
				return ;
			}

			// now find the recent subscription record for this customer 
			$latest_subscription = $subs_model->user_id( $user_id )
						->plan_id( $plan_id)
						->get()
						->sortByDesc('current_term_end')
						->first();

			// and check if renewal allowed for this subscription
			if ( $latest_subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions ){
				if ( $latest_subscription->isRenewalAllowed( $user_id ) ) {
					
					// mark a renewal attempt and redirect to subscription view with proper slug
					
					Axisubs::plugin()->event( 'SubscriptionRenewalAttempted', array($latest_subscription) );

					$this->setRedirect('index.php?option=com_axisubs&view=subscribe&plan='.$latest_subscription->plan->slug);
					return;
				}else {
					// redirect with an error message
					$this->setRedirect('index.php?option=com_axisubs&view=profile',
										JText::_('COM_AXISUBS_ERROR_RENEWAL_NOT_ALLOWED'),'error' );
					return;
				}
			}else {
				// if no subscription id is found this is a new subscription
				// redirect the user to subscribe view with plan slug
				if ( !empty($plan_slug) ) {
					$this->setRedirect('index.php?option=com_axisubs&view=subscribe&plan='.$plan_slug);
				}
				return;
			}

	}

	/**
	 * Task to show confirm cancel layout to prompt and warn user before cancel action
	 * */
	function confirmCancel(){
		$app = JFactory::getApplication();
		$view = $this->getView();
		$error = false;
		$user_id = \JFactory::getUser()->id;

		$subscription_id = $app->input->get('subscription_id',0);
		if (empty($subscription_id)){
			$subscription_id = $app->input->get('id',0);
		}
		if (empty($subscription_id)){
			$app->redirect('index.php?option=com_axisubs&view=profile',
							JText::_('COM_AXISUBS_ERROR_INVALID_SUBSCRIPTION_ID'),'error');
		}

		$view->setLayout('confirm_cancel');
		$view->display();
	}

	/**
	 * Task to handle the cancellation of an active subscription
	 * */
	function cancelSubscription(){
		$app = JFactory::getApplication();
		$view = $this->getView();
		$error = false;
		$user_id = \JFactory::getUser()->id;

		$subscription_id = $app->input->get('subscription_id',0);
		if (empty($subscription_id)){
			$subscription_id = $app->input->get('id',0);
		}
		if (empty($subscription_id)){
			$app->redirect('index.php?option=com_axisubs&view=profile',
							JText::_('COM_AXISUBS_ERROR_INVALID_SUBSCRIPTION_ID'),'error');
		}

		// perform the cancellation of subscription
		$subscription = $this->getModel('Subscriptions');
		$subscription->load( array( $subscription_id ) );

		if ( $subscription->axisubs_subscription_id > 0 ){
			// check the access of the subscription
			if ( $user_id != $subscription->user_id ){
				$app->redirect('index.php?option=com_axisubs&view=profile',
							JText::_('AXISUBS_ERROR_INVALID_ACCESS_CANNOT_CANCEL'),'error');
			}

			$subscription->markCancelled();

			$app->redirect('index.php?option=com_axisubs&view=profile',
							JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS_CANCEL_SUCCESS'),'message');
		}

		$app->redirect('index.php?option=com_axisubs&view=profile');
	}

	function getPaymentForm(){
		$paymentFactory = Axisubs::payment() ;
		$resp = $paymentFactory->getPaymentForm();
		$this->handleResponse( $resp );
	}

	function getSubscriptionId( $plan = '' ){
		// check if the subscription present in the session is for that plan
		// else recreate or re-bind the same subscription record

		// from the URL get the plan id and user id
		// load the subscription from the db 
		// if there is an obselete subscription id in the session bind with this plan and regenerate the subscription

		$app = JFactory::getApplication();
		$session = $app->getSession();
		$plan_slug = $app->input->get('plan','');
		$plan_slug = trim($plan_slug , ' ');
		$plan_id = $app->input->get('plan_id',0);
		$user_id = \JFactory::getUser()->id;
		$subscription_id = $app->input->get('subscription_id',0);
		$new_status = 'N' ; 
		$subs_model = $this->getModel('Subscriptions');

		if ( empty($plan) ){
			$plan_model = $this->getModel('Plans');
			if ( !empty( $plan_slug ) ) {
				$plan_model->load( array('slug'=>$plan_slug) );	
			}elseif ($plan_id > 0) {
				$plan_model->load( $plan_id );	
			}
			$plan_id = $plan_model->axisubs_plan_id;
		}else {
			$plan_id = $plan->axisubs_plan_id;
		}

		if ( $plan_id == 0 ){
			// then redirect to select a plans page
		}

		if ($subscription_id > 0 ) { 
			$subscription_id = $subscription_id; 
		}elseif ( $session->has('subscription_id', 'axisubs') && $session->get('subscription_id', 0 , 'axisubs') > 0 ){ 
			$subscription_id = $session->get('subscription_id', 0 , 'axisubs') ; 
		}

		if ( $subscription_id > 0 )	{
			$subs_model->load( $subscription_id ); 
			// check if the subscription belongs to the current plan otherwise reset 
			if( $subs_model->plan_id != $plan_id && $plan_id > 0 ) {
				$subs_model->resetSubscriptionPlan($plan_id);
			}
			$subscription_id = $subs_model->axisubs_subscription_id;
		}
		/*elseif ($user_id > 0 ){
			$subs_model->load( array( 'plan_id'=> $plan_id, 'user_id'=> $user_id ) );
			$subscription_id = $subs_model->axisubs_subscription_id;
		}*/

		return $subscription_id;
	}

	function handleResponse($result){
		$app = JFactory::getApplication();
		$is_ajax = $app->input->get('ajax', '' );

		$returnURL = $app->input->get ( 'returnURL' );
		$data = $app->input->getArray ( $_POST );
		if ( !empty( $is_ajax ) ) {
			echo json_encode($result); $app->close();
		} else{

			if ( isset( $result['error'] ) ) {
				// app enqueue and redirect the error
				$keys = implode( array_keys($result['error'])) ;
				$app->enqueueMessage(JText::_('FREEDOWNLOAD_ERROR_INVALID_FIELDS').$keys,'error');
				if ( !empty($returnURL) ) {
					$app->redirect( base64_decode( $returnURL ) );
				}else{
					$app->redirect('index.php');
				}
			}

			if ( isset( $result['redirect'] ) ) {
				$app->redirect($result['redirect']);
			}

			if ( isset( $result['success'] ) ) {
				//$app->redirect($result['success']);
				// handle success on a post
			}
		}
	}

}