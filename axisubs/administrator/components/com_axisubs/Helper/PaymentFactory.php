<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

use JPluginHelper;
use JFactory;
use JText;
use Flycart\Axisubs\Admin\Model\Mixin\FOF3Utils;

class PaymentFactory
{
	use FOF3Utils;

	public static $instance = null;
	public $subscription_id = 0;

	public function __construct($properties=null) {

	}

	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}

		return self::$instance;
	}

	/**
	 * Payment factory initialized using the subscription id
	 * @param 	int 	$subscription_id	 subscription id
	 * */
	public function initialize($subscription_id){

		$this->subscription_id = $subscription_id;
		$subs_model = $this->getModel('Subscriptions');
		$subs_model->load( $subscription_id );
		$this->subscription = $subs_model ;
	}

	/**
	 * Method to list all the payment methods applicable for the current customer / subscription / plan / state
	 * @return 	array	List of payment methods applicable
	 * */
	public function getPaymentMethods(){

		$app = JFactory::getApplication();
		$params = Axisubs::config();
		$subscription = $this->subscription ;

		$payment_plugins = Axisubs::plugin()->getPluginsWithEvent( 'onAxisubsGetPaymentPlugins');

		$default_method = $params->get('default_payment_method', ''); // additionally check the plan's preferences
		$plugins = array();
		$payment_form_div = '' ;
		if ($payment_plugins)
		{
			foreach ($payment_plugins as $plugin)
			{
				$results = Axisubs::plugin()->event("onAxisubsGetPaymentOptions", array( $plugin->element, $subscription ) );
				if (!in_array(false, $results, false))
				{
					$a_plugin = new \stdClass();
					$a_plugin->checked = false;
					if(!empty($default_method) && $default_method == $plugin->element) {
						$a_plugin->checked = true;
					}
					$a_plugin->element = $plugin->element;
					$a_plugin->payment_form = $this->getPaymentForm( $plugin->element, true);

					$plg_params= new \JRegistry;
					$plg_params->loadString($plugin->params);
					$a_plugin->params = $plg_params ;

					$disp_name = $plg_params->get( 'display_name', $plugin->name );
					$a_plugin->name = JText::_($disp_name);			
					$a_plugin->display_name = JText::_($disp_name);
					$a_plugin->display_image = $plg_params->get('display_image','');

					$plugins[] = $a_plugin;
				}

				// filter by customer / plan's preferences or any state or filters in the application
				Axisubs::plugin()->event( 'ListPaymentPlugins', array( &$plugins ) );
			}
		}
		return $plugins;
	}

	/**
	 * Method to get the pre payment forms
	 * */
	function getPrePaymentForm( $payment_method ){
		$app = JFactory::getApplication();
		$results = Axisubs::plugin()->event( "PrePayment", array( $payment_method, $this->subscription ) );
		// Display whatever comes back from Payment Plugin for the onPrePayment
		$html = "";
		for ($i=0; $i<count($results); $i++)
		{
			$html .= $results [$i];
		}

		return $html;
	}

	/**
	 * Method to get the pre payment forms
	 * */
	function getPostPaymentForm( $payment_method ,$values){
		$app = \JFactory::getApplication();
		$results = Axisubs::plugin()->event( "PostPayment", array( $payment_method, $values ) );

		// Display whatever comes back from Payment Plugin for the onPrePayment
		$html = "";
		for ($i=0; $i<count($results); $i++)
		{
			$html .= $results [$i];
		}

		return $html;
	}

	/**
	 * Method to get the payment form
	 * */
	function getPaymentForm($element = '', $plain_format = false) {
		$app = JFactory::getApplication ();
		$values = $app->input->getArray ( $_REQUEST );
		$html = '';
		$text = "";
		$user = JFactory::getUser();
		if (empty ( $element )) {
			$element = $app->input->getString ( 'payment_element' );
		}
		$results = array ();

		JPluginHelper::importPlugin ( 'axisubs' );

		$results = $app->triggerEvent ( "onAxisubsGetPaymentForm", array (
				$element,
				$values
		) );
		for($i = 0; $i < count ( $results ); $i ++) {
			$result = $results [$i];
			$text .= $result;
		}

		$html = $text;
		if ($plain_format) {
			return $html;
		} else {

			// set response array
			$response = array ();
			$response ['msg'] = $html;

			// encode and echo (need to echo to send back to browser)
			echo json_encode ( $response );
			$app->close ();
		}
		// return;
	}

	/**
	 * Method to validate the selected payment method and its form values
	 * */
	//function validatePaymentForm( $payment_plugin, $values ) {
	public function validateSelectPayment( $payment_plugin, $values ) {
		$response = array ();
		$response ['msg'] = '';
		$response ['error'] = '';

		$app = JFactory::getApplication ();
		JPluginHelper::importPlugin ( 'axisubs' );

		// verify the form data
		$results = array ();
		$results = $app->triggerEvent ( "onAxisubsGetPaymentFormVerify", array (
				$payment_plugin,
				$values
		) );

		for($i = 0; $i < count ( $results ); $i ++) {
			$result = $results [$i];
			if (! empty ( $result->error )) {
				$response ['msg'] = $result->message;
				$response ['error'] = '1';
			}
		}

		if ($response ['error']) {
			throw new \Exception ( $response ['msg'] );
			return false;
		} else {
			return true;
		}
		return false;
	}

	/**
	 * Method to create a transaction record based on the supplied transaction data
	 * */
	public function createTransactionRecord( $transaction_data ){
		$transaction = $this->getModel('Transaction');
		if (is_array($transaction_data) && count( $transaction_data ) > 0 ){
			if ( !isset($transaction_data['subscription_id']) || empty($transaction_data['subscription_id']) ){
				$transaction_data['subscription_id'] = $this->subscription->axisubs_subscription_id ; 
			}
			if ( $transaction->save( $transaction_data ) ){ 
				return $transaction ;
			}
		}
		return '';
	}

}