<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Controller\DataController;
use FOF30\Inflector\Inflector;
use FOF30\Container\Container;
use JFactory;
use JText;

class Subscription extends DataController
{

	
	public function __construct(Container $container, array $config = array())
	{
		$this->taskMap = [	'save' 	=> 'save',
							'apply' => 'apply' ];
		parent::__construct($container, $config);
	}

	/**
	 * Task to prompt the user to Payment complete
	 * */
	public function mark_payment_complete(){
		// get the subscription records
		$app = JFactory::getApplication();
		$id = $app->input->get('id',0);

		$subs_model = $this->getModel();
		if ($id <= 0){
			$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions');
			return;
		}

		$subs_model->load( $id );
		// set the layout and view variables
		$view = $this->getView();
		$view->subscription = $subs_model ;
		$view->setLayout('payment_complete');
		$view->display();
	}

	/**
	 * Save the transaction record and mark Payment complete action
	 * */
	public function payment_complete(){
		// get the subscription records
		$app = JFactory::getApplication();
		$id = $app->input->get('subscription_id',0);
		$data = $app->input->getArray ( $_POST );

		$subs_model = $this->getModel();
		if ($id <= 0){
			$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions');
			return;
		}
		
		// validate transaction record details
		$error = [];
		$required = array('payment_processor','transaction_currency','transaction_amount','user_id','subscription_id');
		foreach ($required as $key => $value) {
			if ( !isset($data[$value]) ) {
				$error[$value]=JText::_('AXISUBS_ERROR_'.strtoupper($value).'_REQUIRED');
				continue;
			}
			if (empty($data[$value])) {
				$error[$value]=JText::_('AXISUBS_ERROR_'.strtoupper($value).'_REQUIRED');
			}
		}

		$err_message = '';
		$err_message = implode(',', $error);
		if (count($error) > 0 ){
			$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions&task=mark_payment_complete&id='.$id,
					$err_message, 'error');
			return;
		}
		
		try {
			// create a transaction record for subscription
			$transaction_model = $this->getModel('Transactions');
			$transaction_model->save( $data );

			$subs_model->load( $id );
			$subs_model->paymentCompleted();
			
			$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions&task=read&id='.$id,
								'','success');
			return;
		} catch (\Exception $e) {

			$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions&task=mark_payment_complete&id='.$id,
					$e->getMessage(), 'error');
			return;
		}
		
	}

	/**
	 * Task to extend the trial period to specified date
	 * */
	public function startTrial(){
		$subscription = $this->_getSubscription();

		if ($subscription->status == 'T'){
			return ;
		}

		$subs_model =  $this->getModel() ;
		// if a renewal or already active record exists then redirect with a warning
		$trial_subscription_count = $subs_model->user_id( $subscription->user_id )
						->plan_id( $subscription->plan_id)
						->status('T') // active
						->get()
						->count();

		if ( $trial_subscription_count > 0 ) {
			// send a warning that this cannot be moved to trial status
			$this->setRedirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$subscription->axisubs_subscription_id,
						\JText::_('COM_AXISUBS_SUBSCRIPTION_ERR_ALREADY_ACTIVE_TRIAL_SUBS_EXISTS'), 'error');
			return;
		}

		$subscription->skip_trial = 0;
		$subscription->calculateTermDates();
		$subscription->startTrial();
	}

	/**
	 * Task to extend the trial period to specified date
	 * */
	public function extendTrial(){
		$subscription = $this->_getSubscription();
		//get the trial start date / no of days the trial to extend and calculate dates

	}

	/**
	 * Task to mark the subscription to pending state
	 * */
	public function markPending(){
		$subscription = $this->_getSubscription();

		if ($subscription->status == 'P'){
			return ;
		}

		// If the plan is in trial change the trial end date
		if ($subscription->status == 'T'){
			$date_helper = Axisubs::date();
			$subscription->trial_end = $date_helper->getCarbonDate()->toDateTimeString();
			$this->skip_trial = 1;
		}

		// mark as pending
		$subscription->markPending();

		$this->setRedirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$subscription->axisubs_subscription_id,
						\JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS_PENDING_SUCCESS'),'message');
		return;
	}

	/**
	 * Activate the subscription
	 * */
	public function markActive(){
		$subscription = $this->_getSubscription();
		
		if ($subscription->status == 'A'){
			return ;
		}

		$subs_model =  $this->getModel() ;
		// if a renewal or already active record exists then redirect with a warning
		$active_subscription_count = $subs_model->user_id( $subscription->user_id )
						->plan_id( $subscription->plan_id)
						->status('A') // active
						->get()
						->count();

		if ( $active_subscription_count > 0 ) {
			// by default mark as future
			$subscription->calculateTermDates();
			$subscription->markFuture();

			// send a warning that this cannot be moved to active status
			$this->setRedirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$subscription->axisubs_subscription_id,
						\JText::_('COM_AXISUBS_SUBSCRIPTION_ERR_ALREADY_ACTIVE_SUBS_EXISTS'), 'warning');
			return;
		}

		if ( $subscription->hasTrial() ) {
			$subscription->skip_trial = 1;
		}

		// else recalculate date and mark as active		
		$subscription->calculateTermDates();
		$subscription->markActive();

		$this->setRedirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$subscription->axisubs_subscription_id,
						\JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS_ACTIVATE_SUCCESS'),'message');
		return;
	}

	/**
	 * Cancel the subscription
	 * */
	public function markCancel(){
		$subscription = $this->_getSubscription();

		$subscription->markCancelled();

		$this->setRedirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$subscription->axisubs_subscription_id,
						\JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS_CANCEL_SUCCESS'),'message');
		return;
	}

	/**
	 * Delete the subscription
	 * */
	public function markDelete(){
		$subscription = $this->_getSubscription();

		$subscription->markDeleted();

		$this->setRedirect('index.php?option=com_axisubs&view=Subscriptions',
						\JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS_DELETE_SUCCESS'),'message');
		return;
	}
	
	protected function _getSubscription(){
		// get the subscription records
		$app = JFactory::getApplication();
		$id = $app->input->get('id',0);

		$subs_model = $this->getModel();
		if ($id <= 0){
			$app->redirect('index.php?option=com_axisubs&view=Subscriptions',
						\JText::_('COM_AXISUBS_ERR_SUBSCRIPTION_ID_NOT_FOUND'),'error');
			return;
		}

		$subs_model->load( $id );
		if ( $subs_model->axisubs_subscription_id <= 0 ){
			$app->redirect('index.php?option=com_axisubs&view=Subscription&task=read&id='.$id,
						JText::_('COM_AXISUBS_ERR_SUBSCRIPTION_NOT_FOUND'),'error');
			return;	
		}
		$this->setRedirect( 'index.php?option=com_axisubs&view=Subscription&task=read&id='.$subs_model->axisubs_subscription_id );
		return $subs_model;
	}

	public function onBeforeEdit(){

	}

	public function onBeforeApply(){

	}
	public function onBeforeSave(){

	}

	public function onBeforeApplySave($data){
		// perform the complete validation and return to the return url if there are any errors

		$app = JFactory::getApplication();
		
	}
	
	public function publish()
	{
		$this->noop();
	}

	public function unpublish()
	{
		$this->noop();
	}

	public function archive()
	{
		$this->noop();
	}

	public function noop()
	{
		// CSRF prevention
		$this->csrfProtection();

		// Redirect
		if ($customURL = $this->input->getBase64('returnurl', ''))
		{
			$customURL = base64_decode($customURL);
		}

		$url = !empty($customURL) ? $customURL : 'index.php?option=' . $this->container->componentName . '&view=' . $this->container->inflector->pluralize($this->view) . $this->getItemidURLSuffix();

		$this->setRedirect($url);
	}

	protected function onBeforeBrowse()
	{
		$format = $this->input->getCmd('format', 'html');

		// Do not apply list limits on CSV export
		if ($format == 'csv')
		{
			$this->getModel()
				->savestate(false)
				->limit(0)
				->limitstart(0);
		}
	}
}

