<?php
/**
 * @package   Axisubs - Test Payment
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/Payment.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\Payment;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class plgAxisubsPayment_Test extends Payment
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element    = 'payment_test';
	var $_isLog      = false;
	var $_axisversion = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		if($this->params->get('debug', 0)) {
			$this->_isLog = true;
		}
	}
	
	function onAxisubsCalculateFees($order) {
	
	}

	function onAxisubsGetAppView( $row )
	{

		if (!$this->_isMe($row))
		{
			return null;
		}

		$html = $this->viewList();

		return $html;
	}

	function viewList()
	{
		$app = JFactory::getApplication();
		$option = 'com_axisubs';
		$ns = $option.'.payment';
		$html = "";
		JToolBarHelper::title(JText::_('AXISUBS_APP').'-'.JText::_('PLG_AXISUBS_'.strtoupper($this->_element)),'axisubs-logo');
		JToolBarHelper::apply('apply');
		JToolBarHelper::save();

		$vars = new JObject();
		$this->includeCustomModel('PaymentTest');

		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$model = new AxisubsModelPaymentTest( $container, $config = array('name'=>'AxisubsModelPaymentTest') );

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
	 * @param $data     array       form post data
	 * @return string   HTML to display
	 */
	//function _prePayment( $data )
	/*function onAxisubsPrePayment( $element, $data )
	{
		// get component params
		$params = Axisubs::config();
		$currency = Axisubs::currency();

		// prepare the Payment form

		$vars = new JObject();

		$html = $this->_getLayout('prepayment', $vars);
		return $html;
	}*/

	/**
	 * Verifies that all the required form fields are completed
	 * if any fail verification, set
	 * $object->error = true
	 * $object->message .= '<li>x item failed verification</li>'
	 *
	 * @param $submitted_values     array   post data
	 * @return unknown_type
	 */
	function _verifyForm($payment_values) {

		$object = new JObject ();
		$object->error = false;
		$object->message = '';
		$user = JFactory::getUser ();

return $object; ///////////////////////////////////////

		//  validation
		$errors = [];
		// check reqiured fields first
		$required = array('cardholder','cardnum','month','year','cardcvv');
		foreach ($required as $key => $value) {
			if ( !isset( $payment_values[$value] ) ){
				$errors[]= JText::_('AXISUBS_FIELD_'.strtoupper($value)).' '. JText::_('AXISUBS_ERROR_REQUIRED');
				continue;
			}
			if (empty($payment_values[$value])) {
				$errors[]= JText::_('AXISUBS_FIELD_'.strtoupper($value)).' '. JText::_('AXISUBS_ERROR_REQUIRED');
			}
		}

		// now validate specific fields
		
		foreach ( $payment_values as $key => $value ) {
			switch ($key) {
				
				case "cardnum" :
					if (! isset ( $payment_values [$key] ) || ! JString::strlen ( $payment_values [$key] )) {
						$object->error = true;
						$errors[]= JText::_ ( "AXISUBS_CARD_ERROR_CARD_NUMBER_INVALID" ) ;
					}
					break;
				case "month" :
					if ( isset ( $payment_values [$key] ) ) {
						// check if number and within 1-12
						if ( ! ( intval( $payment_values [$key] ) >= 1
									&& intval( $payment_values [$key] ) <= 12 ) ){
							$errors[]= JText::_('AXISUBS_CARD_ERROR_INVALID_MONTH_SUPPLIED');
						}
					}
					break;
				case "year" :
					if ( isset ( $payment_values [$key] ) ) {
						$curr_year = (int) date("Y") ;
						if ( ! (  intval( $payment_values [$key] ) >= $curr_year ) ){
							$errors[]= JText::_('AXISUBS_CARD_ERROR_INVALID_YEAR_SUPPLIED');
						}
					}
					break;
				case "cardcvv" :
					if ( isset ( $payment_values [$key] ) ) {
						if ( ! ( intval( $payment_values [$key] ) > 0 ) ) {
							$errors[]= JText::_('AXISUBS_CARD_ERROR_INVALID_CVV_SUPPLIED');
						}
					}
					break;
				default :
					break;
			}
		}

		if ( is_array($errors) && count($errors) > 0 ){
			$object->error = true;
			$object->message = '<ul> <li>'.implode('</li><li>', $errors).'</li></ul>';
		}		
		
		return $object;
	}

	/**
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
        //get subscription data
        $subcription = $data->getData();

        // prepare the Payment form
        $vars = new JObject();
        if ( isset( $subcription['axisubs_subscription_id'] ) ) {
	        $vars->subscription_id = $subcription['axisubs_subscription_id'];
	        $vars->plan_id = $subcription['plan_id'];
	        $vars->user_id = $subcription['user_id'];
        }
        $vars->payment_method = $this->_element;
        $vars->onbeforepayment_text = $this->params->get('onbeforepayment', '');

        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }


    /*function onAxisubsPlanAfterFormRender($plan){
    	$vars = new JObject();
    	$vars->message='message simple';
    	$vars->title='message simple';
    	$vars->html = $this->_getLayout('message', $vars);
        return $vars;
    }*/

	/**
	 * Processes the Payment form
	 * and returns HTML to be displayed to the user
	 * generally with a success/failed message
	 *
	 * @param $data     array       form post data
	 * @return string   HTML to display
	 */
	function _postPayment( $data )
	{

		// first step is to identify the subscription record or the transaction record
        $app = JFactory::getApplication();
        $vars = new JObject();
        $html = '';
        $subscription_id = $app->input->get('subscription_id');
        $subscription = $this->getSubscription( $subscription_id );
        if (! ($subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions || $subscription instanceof \Flycart\Axisubs\Admin\Model\Subscriptions)){
        	$html = $this->params->get ( 'onerrorpayment', '' );
        	return $html; // throw error
        }

        // create a transaction record
        $transaction = $this->getModel('Transactions');
        $transaction_data = [
        	'subscription_id' => $subscription->axisubs_subscription_id,
        	'user_id' => $subscription->user_id,
        	'payment_processor' => $this->_element,
        	'transaction_amount' => $subscription->net_amount,
        	'transaction_currency' => $subscription->currency,
        ];

		$subscription_state = $this->params->get('status', 'A'); // DEFAULT: Active 

		try {
			if ( $subscription_state == 'A' ) {
				$subscription->paymentCompleted();
				$transaction_data ['processor_status'] = $subscription_state ; 
			} else {
				$subscription->markPending();
				$transaction_data ['processor_status'] = 'P' ;
			}
			$transaction->save ( $transaction_data );
		} catch (\Exception $e) {
			$html  = $this->params->get('onerrorpayment', 'error');
			$html .= $e->getMessage();
			return $html;
		}

		$vars->onafterpayment_text = $this->params->get('onafterpayment', '');
		$html = $this->_getLayout('postpayment', $vars);

        return $html;
	}

	/**
	 * Prepares variables for the Payment form
	 *
	 * @return unknown_type
	 */
	function _renderForm( $data )
	{
		$user = JFactory::getUser();
		$vars = new JObject();
		$vars->onselection_text = $this->params->get('onselection', '');
		$html = $this->_getLayout('form', $vars);

		return $html;
	}

	/**
	 *
	 * @return HTML
	 */
	function _process()
	{
		$error = '';
		// if here, all went well
		$error = 'processed';
		return $error;
	}

	/**
	 * Processes the sale Payment
	 *
	 * @param array $data IPN data
	 * @return boolean Did the IPN Validate?
	 * @access protected
	 */
	function _processSale($data, $ipnValidationFailed = '') {
		/*
		 * validate the Payment data
		 */
		$errors = array ();

		return count ( $errors ) ? implode ( "\n", $errors ) : '';
	}
}
