<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Site\Controller;

defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Controller\Mixin;
use FOF30\Controller\Controller;
use FOF30\Container\Container;
use JFactory;
use JRoute;
use JText;

class Profile extends Controller
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

		$this->predefinedTaskList = ['default','read'];

		$this->cacheableTasks = [];

		// Force the view name, required because I am extending the Levels (plural) controller but I want my view
		// templates to be read from the Level (singular) directory.
		$this->viewName = 'Profile';
	}

	/**
	 * Runs before the default task
	 *
	 * @throws \Exception
	 */
	public function onBeforeDefault(){
		
		$user = JFactory::getUser();
		
		$this->_restrictGuestUsers();

		$view = $this->getView();
		
		// user id get the customer record and the subscription records
		$customer_model = $this->getModel('Customers');
		$customer_model->load( array('user_id'=>$user->id));
		$view->customer = $customer_model ;

		// get the subscription records for that users
		$subscription_model = $this->getModel('subscriptions');
		$view->subscriptions = $subscription_model->user_id($user->id)
									->get()
									->sortBy('plan_id')
									->filter(function($item){
											return ($item->status !='N');
										});
		$view->new_subscriptions = 	$subscription_model->user_id($user->id)
									->get()
									->sortBy('plan_id')
									->filter(function($item){
											return ($item->status =='N');
										});					
		return ;
	}

	/**
	 * Task to display the customer edit form
	 */
	public function editCustomerAddress(){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$this->_restrictGuestUsers();

		$user_id = $user->id;

		$view = $this->getView();
		$view->setLayout('edit_address');

		// get the customer details and set it in the view
		$cust_model = $this->getModel('Customers');
		$cust_model->load( array('user_id'=>$user_id) );
		
		$view->customer = $cust_model;
		$view->user_id = $user_id;
		$view->display();
	}

	/**
	 * Task to display the customer edit form
	 */
	public function saveCustomerAddress(){
		$app = JFactory::getApplication();
		$user_id = JFactory::getUser()->id;
		$view = $this->getView();
		$customer_id = 0;
		$subscription_id = 0 ;
		
		$billing_data  = $app->input->post->get('billing_address', array(), 'array');
		
		$result = array();
		$errors = array();
		$customer_model = $this->getModel('Customers');
		
		$errors = $customer_model->validateAddress( $billing_data );

		if ( count($errors) > 0 ) {
			$result['error'] = $errors ;
			$this->handleResponse($result);
		}

		// no errors then create the customer record
		try {
			$customer_model->load( array('user_id' => $user_id) ) ;
			$customer_model->bind( $billing_data );
			$customer_model->user_id = $user_id ;

			$cust_store_success = $customer_model->store();
		} catch (\Exception $e) {
			$result['error'] = $e->getMessage() ;
			$this->handleResponse($result);
		}

		$result['redirect'] = JRoute::_('index.php?option=com_axisubs&view=Profile') ;
		$this->handleResponse($result);
	}

	/**
	 * Task to view the subscription details
	 * */
	function viewSubscription(){
		$this->_restrictGuestUsers();

		$app = JFactory::getApplication();
		$user_id = \JFactory::getUser()->id;
		$view = $this->getView();

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
							JText::_('AXISUBS_ERROR_INVALID_ACCESS_CANNOT_VIEW'),'error');
			}

			$view->subscription = $subscription ;
			$view->setLayout('view_subscription');
			$view->display();
		}else {
			$app->redirect('index.php?option=com_axisubs&view=profile',
							JText::_('COM_AXISUBS_ERROR_INVALID_SUBSCRIPTION_ID'),'error');
		}
	}

	/**
	 * Method to redirect to login if not logged in
	 * */
	protected function _restrictGuestUsers(){
		// an user should be loged in to access the profile view
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		if ( $user->id <= 0 ) {
			$return_url = JRoute::_('index.php?option=com_axisubs&view=profile');
			$encoded_return_url = base64_encode( $return_url );
			$app->redirect( 'index.php?option=com_users&view=login&return='.$encoded_return_url,
									JText::_('COM_AXISUBS_PROFILE_LOGIN_BEFORE_ACCESS'),'warning' );
			return ;
		}

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