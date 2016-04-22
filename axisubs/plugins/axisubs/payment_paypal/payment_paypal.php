<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/Payment.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Plugins\Payment;

class plgAxisubsPayment_paypal extends Payment
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element    = 'payment_paypal';

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
    }

    /**
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
        //get subscription data
        $subcription = $data->getData();
        //get subscription info data
        $subcription_info = $data->subscriptioninfo->getData();

        // get component params
        $params = Axisubs::config();

        //get currency
        $currency = Axisubs::currency();

        // prepare the payment form
        $vars = new JObject();
        $vars->subscription_id = $subcription['axisubs_subscription_id'];
        $currency_values = $this->getCurrency($data);
        $vars->currency_code = $currency_values['currency_code'];
        $vars->orderpayment_amount = $currency->format($subcription['total'], $currency_values['currency_code'], $currency_values['currency_value'], false);
        $vars->orderpayment_type = $this->_element;
        $vars->cart_session_id = JFactory::getSession()->getId();
        $vars->display_name = $this->params->get('display_name', 'PAYMENT_PAYPAL');
        $vars->onbeforepayment_text = $this->params->get('onbeforepayment', '');
        $vars->button_text = $this->params->get('button_text', 'J2STORE_PLACE_ORDER');
        $vars->image = $this->params->get('display_image', '');
        //sub total
        $products = array();
        $products['name'] = $data->plan->name;
        $products['price'] = $currency->format($data->total, $currency_values['currency_code'], $currency_values['currency_value'], false);
        $products['number'] = $data->plan_id;
        $products['quantity'] = $data->plan_quantity;
        //$products['options'] = array();
        $vars->products = array();
        $vars->products[] = $products;
        // tax cart
        $vars->tax_cart = $currency->format($subcription['tax'], $currency_values['currency_code'], $currency_values['currency_value'], false);
        // discount
        $vars->discount_amount_cart = $currency->format($subcription['discount'], $currency_values['currency_code'], $currency_values['currency_value'], false);

        //get sandbox
        $environment = $this->getEnvironment();

        // set payment plugin variables
        if($environment){
            $vars->merchant_email = trim($this->_getParam( 'sandbox_merchant_email' ));
        }else{
            $vars->merchant_email = trim($this->_getParam( 'merchant_email' ));
        }

        $rootURL = rtrim(JURI::base(),'/');
        $subpathURL = JURI::base(true);
        if(!empty($subpathURL) && ($subpathURL != '/')) {
            $rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
        }

        $vars->post_url = $this->_getPostUrl();
        $vars->return_url = JURI::root()."index.php?option=com_axisubs&view=Subscribe&task=confirmPayment&orderpayment_type=".$this->_element."&paction=display";
        $vars->cancel_url = JURI::root()."index.php?option=com_axisubs&view=Subscribe&task=confirmPayment&orderpayment_type=".$this->_element."&paction=cancel";
        $vars->notify_url = JURI::root()."index.php?option=com_axisubs&view=Subscribe&task=confirmPayment&orderpayment_type=".$this->_element."&paction=process&tmpl=component";

        $vars->first_name   = $subcription_info['billing_first_name'];
        $vars->last_name    = $subcription_info['billing_last_name'];
        $vars->email        = $subcription_info['billing_email'];
        $vars->address_1    = $subcription_info['billing_address1'];
        $vars->address_2    = $subcription_info['billing_address2'];
        $vars->city         = $subcription_info['billing_city'];
        $vars->country      = $subcription_info['billing_state'];
        $vars->region       = $subcription_info['billing_country'];
        $vars->postal_code  = $subcription_info['billing_zip'];
        $vars->invoice = $subcription['axisubs_subscription_id'];
        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }

    function _process()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getArray($_POST);

        $params = Axisubs::config();
        $errors = array();
        $custom = $data['custom'];
        $custom_array = explode('|', $custom);
        $subscription_id  = $custom_array[0];

        $data['transaction_details'] = $this->_getFormattedTransactionDetails( $data );
        $this->_log($data['transaction_details']);
        if($subscription_id && $subscription_id > 0){
            $subscription = $this->getSubscription($subscription_id);
            if (! $subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions){
                $errors [] = JText::_('AXISUBS_SUBSCRIPTION_NOT_FOUND');
            }
            // prepare some data
            $validate_ipn = $this->params->get('validate_ipn', 1);
            if($validate_ipn) {

                if($subscription && !empty($subscription->axisubs_transaction_id) && ($subscription->axisubs_subscription_id == $subscription_id) ) {
                    // validate the IPN info
                    $errors[]= $validation_result = $this->_validateIPN($data, $subscription);
                    if (!empty($validation_result))
                    {
                        // ipn Validation failed
                        $data['ipn_validation_results'] = $validation_result;
                    }

                }
            }

            // process the payment based on its type
            if ( !empty($data['txn_type']) )
            {
                //$payment_error = '';

                if ($data['txn_type'] == 'cart') {
                    // Payment received for multiple items; source is Express Checkout or the PayPal Shopping Cart.
                    //$payment_error = $this->_processSale( $data, $error );
                    //get sandbox
                    $environment = $this->getEnvironment();
                    // set payment plugin variables
                    if($environment){
                        $merchant_email = trim($this->_getParam( 'sandbox_merchant_email' ));
                    }else{
                        $merchant_email = trim($this->_getParam( 'merchant_email' ));
                    }

                    // is the recipient correct?
                    if (empty ( $data ['receiver_email'] ) || JString::strtolower ( $data ['receiver_email'] ) != JString::strtolower ( trim ( $merchant_email ) )) {
                        $errors [] = JText::_ ( 'J2STORE_PAYPAL_MESSAGE_RECEIVER_INVALID' );
                    }


                    if( !empty($subscription->axisubs_subscription_id) && ($subscription->axisubs_subscription_id == $subscription_id) ) {                        // check the payment status
                        if (empty ( $data ['payment_status'] ) || ($data ['payment_status'] != 'Completed' && $data ['payment_status'] != 'Pending')) {
                            $errors [] = JText::sprintf ( 'J2STORE_PAYPAL_MESSAGE_STATUS_INVALID', @$data ['payment_status'] );
                        }

                        //$subscription = $this->getSubscription( $transaction->subscription_id );
                        $currency = Axisubs::currency();
                        $currency_values = $this->getCurrency($subscription);
                        $gross = $currency->format($subscription->total, $currency_values['currency_code'], $currency_values['currency_value'], false);

                        $mc_gross = floatval($data['mc_gross']);

                        if ($mc_gross > 0)
                        {
                            // A positive value means "payment". The prices MUST match!
                            // Important: NEVER, EVER compare two floating point values for equality.
                            $isValid = ($gross - $mc_gross) < 0.05;
                            if(!$isValid) {
                                $errors[] = 'Paid amount does not match the order total';
                            }
                        }

                        $transaction_data = array(
                            'subscription_id' => $subscription->axisubs_subscription_id,
                            'user_id' => $subscription->user_id,
                            'payment_processor' => $this->_element,
                            'transaction_ref_id' => $data ['txn_id'],
                            'transaction_amount' => $mc_gross,
                            'transaction_currency' => $data['mc_currency'],
                            'prepayment' => "",
                            'postpayment' => "",
                            'authorize' => "",
                            'params' => "",
                            'processor_status' =>$data ['payment_status']
                        );

                        if (count ( $errors )) {

                            $subscription->paymentFailed($transaction_data);
                        }elseif (strtoupper($data ['payment_status']) == 'PENDING') {
                            $subscription->paymentPending($transaction_data);
                        }elseif(strtoupper($data ['payment_status']) == 'COMPLETED') {
                            $subscription->paymentCompleted($transaction_data);
                        }
                    }else{
                        $errors[] = JText::_( "J2STORE_PAYPAL_ERROR_INVALID_SUBSCRIPTION_ID" );
                    }
                }
                else {
                    // other methods not supported right now
                    $errors[] = JText::_( "J2STORE_PAYPAL_ERROR_INVALID_TRANSACTION_TYPE" ).": ".$data['txn_type'];
                }
            }
        }

        if (count($errors) > 0) {
            $sitename = $config = JFactory::getConfig()->get('sitename');
            //send error notification to the administrators
            $subject = JText::sprintf('J2STORE_PAYPAL_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);

            $receivers = $this->_getAdmins();
            foreach ($receivers as $receiver) {
                //$body = JText::sprintf('J2STORE_PAYPAL_EMAIL_PAYMENT_FAILED_BODY', $receiver->name, $sitename, JURI::root(), $error, $data['transaction_details']);
                //J2Store::email()->sendErrorEmails($receiver->email, $subject, $body);
            }
            //return $error;
        }
        // if here, all went well
        $error = 'processed';
        return $error;
    }


    /**
     * Validates the IPN data
     *
     * @param array $data
     * @return string Empty string if data is valid and an error message otherwise
     * @access protected
     */
    function _validateIPN( $data, $order)
    {
        $paypal_url = $this->_getPostUrl(true);

        $request = 'cmd=_notify-validate';

        foreach ($data as $key => $value) {
            $request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
        }

        $curl = curl_init($paypal_url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        if (!$response) {
            $this->_log('CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
        }

        $this->_log('IPN Validation REQUEST: ' . $request);
        $this->_log('IPN Validation RESPONSE: ' . $response);

        if ((strcmp($response, 'VERIFIED') == 0 || strcmp($response, 'UNVERIFIED') == 0)) {
            return '';
        }elseif (strcmp ($response, 'INVALID') == 0) {
            return JText::_('J2STORE_PAYPAL_ERROR_IPN_VALIDATION');
        }
        return '';
    }

        /**
     * Gets the value for the Paypal variable
     *
     * @param string $name
     * @return string
     * @access protected
     */
    function _getParam( $name, $default='' )
    {
        $return = $this->params->get($name, $default);

        $sandbox_param = "sandbox_$name";
        $sb_value = $this->params->get($sandbox_param);
        $sandbox = $this->getEnvironment();
        if ($sandbox && !empty($sb_value))
        {
            $return = $this->params->get($sandbox_param, $default);
        }

        return $return;
    }

    /**
     * Gets the Paypal gateway URL
     *
     * @param boolean $full
     * @return string
     * @access protected
     */
    function _getPostUrl($full = true)
    {
        $url = $this->params->get('sandbox') ? 'www.sandbox.paypal.com' : 'www.paypal.com';

        if ($full)
        {
            $url = 'https://' . $url . '/cgi-bin/webscr';
        }

        return $url;
    }


}