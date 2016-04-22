<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/App.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\App;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class plgAxisubsApp_Emailtemplates extends App
{

	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_emailtemplates';

    private $events =  array(   'CustomerSignUp',
                            'SubscriptionCreated',
                            'SubscriptionRenewalAttempted',
                            'SubscriptionRenewalPaid',
                            'SubscriptionTrialPaid',
                            'SubscriptionActivePaid',
                            'SubscriptionPaymentSuccess',
                            'SubscriptionPaymentFailed',
                            'SubscriptionPaymentPending',
                            'SubscriptionMarkedActive',
                            'SubscriptionCancelled',
                            'SubscriptionDeleted',
                            'SubscriptionMarkedRenewal',
                            'SubscriptionExpired',
                            'SubscriptionTrialStarted',
                            'SubscriptionTrialEnded'  );    

    /**
     * Magic function used to handle all the email sending events
     * */
    public function __call($name, $arguments) 
    {
        print_r($name); exit;
        $func_name = str_replace('onAxisubs', '', $name);

        if ( array_key_exists( $func_name, $this->events ) ) {
            $this->_sendNotificationEmails ( $func_name, $arguments );
        }
    }

    /**
     * Overriding
     *
     * @param $options
     * @return unknown_type
     */
    function onAxisubsGetAppView( $row )
    {

	   	if (!$this->_isMe($row))
    	{
    		return null;
    	}

    	$html = $this->viewList();

    	return $html;
    }

    /**
     * Validates the data submitted based on the suffix provided
     * A controller for this plugin, you could say
     *
     * @param $task
     * @return html
     */
    function viewList()
    {
    	$app = JFactory::getApplication();
    	$option = 'com_axisubs';
    	$ns = $option.'.app';
    	$html = "";
    	JToolBarHelper::title(JText::_('AXISUBS_APP').'-'.JText::_('PLG_AXISUBS_'.strtoupper($this->_element)),'axisubs-logo');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();

	   	$vars = new JObject();
	    $this->includeCustomModel('AppEmailtemplates');

        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppEmailtemplates( $container, $config = array('name'=>'AxisubsModelAppEmailtemplates') );

        $data = $this->params->toArray();
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;

    	$id = $app->input->getInt('id', '0');
    	$vars->id = $id;
    	$html = $this->_getLayout('default', $vars);
    	return $html;
    }

    /**
     * Method to handle the list of mail notifications
     * */
    function _sendNotificationEmails( $event_name, $objects = array() ){
        $mail_helper = Axisubs::mail();
        $mail_temps = $mail_helper->sendEmails( $event_name , $objects );
    }

    function onAxisubsCustomerSignUp($user_id, $customer){
        $this->_sendNotificationEmails ( $event = 'CustomerSignUp', $customer );
    }
    
    function onAxisubsSubscriptionRenewalAttempted($subscription){
        $this->_sendNotificationEmails ( $event = 'SubscriptionRenewalAttempted',
                                            array( 'subscription' => $subscription ) );
    }

    ////// Subscription Events

    function onAxisubsSubscriptionCreated( $subscription ){
        $this->_sendNotificationEmails ( $event = 'SubscriptionCreated',
                                            array( 'subscription' => $subscription ) );
    }    

    function onAxisubsSubscriptionRenewalPaid($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionRenewalPaid',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionTrialPaid($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionTrialPaid',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionActivePaid($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionActivePaid',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionPaymentSuccess($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionPaymentSuccess',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionPaymentFailed($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionPaymentFailed',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionPaymentPending($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionPaymentPending',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionMarkedActive($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionMarkedActive',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionMarkedPending($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionMarkedPending',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionCancelled($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionCancelled',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionDeleted($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionDeleted',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionMarkedRenewal($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionMarkedRenewal',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionExpired($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionExpired',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionTrialStarted($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionTrialStarted',
                                            array( 'subscription' => $subscription ) );
    }

    function onAxisubsSubscriptionTrialEnded($subscription, $old_subscription_status){
        $this->_sendNotificationEmails ( $event = 'SubscriptionTrialEnded',
                                            array( 'subscription' => $subscription ) );
    }

}