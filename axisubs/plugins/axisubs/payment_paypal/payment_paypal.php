<?php
/**
 * @subpackage   Axisubs - Paypal Payment plugin
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
        $this->includeCustomModel('PaymentPaypal');

        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelPaymentPaypal( $container, $config = array('name'=>'AxisubsModelPaymentPaypal') );

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

        // prepare the Payment form
        $vars = new JObject();
        $vars->subscription_id = $subcription['axisubs_subscription_id'];
        $currency_values = $this->getCurrency($data);
        $vars->currency_code = $currency_values['currency_code'];
        $vars->orderpayment_amount = $currency->format($subcription['total'], $currency_values['currency_code'], $currency_values['currency_value'], false);
        $vars->orderpayment_type = $this->_element;
        $vars->cart_session_id = JFactory::getSession()->getId();
        $vars->display_name = $this->params->get('display_name', 'PAYMENT_PAYPAL');
        $vars->onbeforepayment_text = $this->params->get('onbeforepayment', '');
        $vars->button_text = $this->params->get('button_text', 'AXISUBS_PLACE_ORDER');
        $vars->image = $this->params->get('display_image', '');
        //sub total
        $products = array();
        
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

        // set Payment plugin variables
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

        //////////////////////////////////////

        $vars->cmd = '_xclick';
        $vars->item_name = $data->plan->name;

        if ( $data->plan->hasTrial() ) {
            $vars->a1 = $currency->format($data->setup_fee, $currency_values['currency_code'], $currency_values['currency_value'], false);;
            //$vars->p1 = $data->plan->getTrialPeriodInDays();
            //$vars->t1 = 'D';
            $vars->p1 = $data->plan->trial_period;
            $vars->t1 = $data->plan->trial_period_unit;
        }

        if ( $data->plan->isRecurring() ) {
            $vars->cmd =  '_xclick-subscriptions' ;
            // send the price without setup fee
            $vars->a3 = $currency->format($data->plan_price, $currency_values['currency_code'], $currency_values['currency_value'], false); 
            // TODO: fix it to recurring_total at of now this includes setup fee as well
            //$vars->p3 = $data->plan->getPeriodInDays();
            //$vars->t3 = 'D';
            $vars->p3 = $data->plan->period;
            $vars->t3 = $data->plan->period_unit;
            $vars->billing_cycles = $data->remaining_billing_cycles;
            $vars->recurring_total = $data->recurring_total;
            $vars->sra = 1 ; // failiure reattempt flag
        }
        $sub_total = $data->total-$data->tax;
         $vars->total = $currency->format($sub_total, $currency_values['currency_code'], $currency_values['currency_value'], false);
        //////////////////////////////////////

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
        $vars->invoice = $data->getInvoiceNumber(); //$subcription['axisubs_subscription_id'];
        $vars->plan = $data->plan;
        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }

    function _process()
    {
        $app = JFactory::getApplication();
        $data = $app->input->post->getArray();

        $params = Axisubs::config();
        $errors = array();
        $custom = $data['custom'];
        //$custom_array = explode('|', $custom);
        $subscription_id  = $custom;

        $data['transaction_details'] = $this->_getFormattedTransactionDetails( $data );
        $this->_log($data['transaction_details']);

        if($subscription_id && $subscription_id > 0){
            $subscription = $this->getSubscription($subscription_id);
            if (! ($subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions || $subscription instanceof \Flycart\Axisubs\Admin\Model\Subscriptions)){
                $errors[] = JText::_('AXISUBS_SUBSCRIPTION_NOT_FOUND');
                $this->_log($errors);
            }
            // prepare some data
            $validate_ipn = $this->params->get('validate_ipn', 1);
            if($validate_ipn) {

                if($subscription && ($subscription->axisubs_subscription_id == $subscription_id) ) {
                    // validate the IPN info

                    $validation_result = $this->_validateIPN($data, $subscription);
                    if (!empty($validation_result))
                    {
                        // ipn Validation failed
                        $data['ipn_validation_results'] = $validation_result;
                        $errors[] = $validation_result;
                        $this->_log($errors);
                    }

                }
            }

            // process the Payment based on its type
            if ( !empty($data['txn_type']) )
            {
                $known_txn_response_types = ['web_accept', 'subscr_signup', 'subscr_payment', 'subscr_eot'];

                $environment = $this->getEnvironment();
                // set Payment plugin variables
                if($environment){
                    $merchant_email = trim($this->_getParam( 'sandbox_merchant_email' ));
                }else{
                    $merchant_email = trim($this->_getParam( 'merchant_email' ));
                }

                // is the recipient correct?
                if (empty ( $data ['receiver_email'] ) || JString::strtolower ( $data ['receiver_email'] ) != JString::strtolower ( trim ( $merchant_email ) )) {
                    $errors [] = JText::_ ( 'J2STORE_PAYPAL_MESSAGE_RECEIVER_INVALID' );
                    $this->_log($errors);
                }

                // for recurring subscription a recurring profile is created and a confirmation is got for signup
                if ($data['txn_type'] == 'subscr_signup') {
                    // just update the transaction record with profile id and mark pending
                        $transaction_data = array(
                            'subscription_id' => $subscription->axisubs_subscription_id,
                            'user_id' => $subscription->user_id,
                            'payment_processor' => $this->_element,
                            'subscription_profile_id' => $data ['subscr_id'],
                            'transaction_currency' => $data['mc_currency']
                        );

                        $subscription->paymentPending($transaction_data);                       
                        return;
                }

                if ($data['txn_type'] == 'subscr_payment') {
                    /**
                     * Check if the current subscription record already has a successful transaction object within that date range 
                     * if a new or second transaction record comes in, expire the active subscription and create a new subscription for this renewal
                     * associate the txn id with newly created record and activate the subscription
                     * */
                    $current_trans_id = $data['txn_id'];
                    // decide the appropriate subscription record to be processed
                    $next_subscription = $subscription->getNextRenewal( $subscription , $current_trans_id);

                    if ( $next_subscription ) {
                        $subscription = $next_subscription ;
                    }

                }

                // Recurring or Non-recurring subscription Payment confirmation
                if ( in_array($data['txn_type'], array('web_accept', 'subscr_payment' ) ) ) {
                    // a subscription Payment has been done
                    if( !empty($subscription->axisubs_subscription_id) ) {                        // check the Payment status
                        if (empty ( $data ['payment_status'] ) || ($data ['payment_status'] != 'Completed' && $data ['payment_status'] != 'Pending')) {
                            $errors [] = JText::sprintf ( 'J2STORE_PAYPAL_MESSAGE_STATUS_INVALID', @$data ['payment_status'] );
                            $this->_log($errors);
                        }

                        //$subscription = $this->getSubscription( $transaction->subscription_id );
                        $currency = Axisubs::currency();
                        $currency_values = $this->getCurrency($subscription);
                        if ( $data['txn_type'] == 'subscr_payment' ) {
                            $gross = $currency->format($subscription->recurring_total, $currency_values['currency_code'], $currency_values['currency_value'], false);    
                        }else{
                            $gross = $currency->format($subscription->total, $currency_values['currency_code'], $currency_values['currency_value'], false);
                        }

                        $mc_gross = floatval($data['mc_gross']);

                        //TODO: check the first time Payment and the setup fee with the gross amount
                        if ($mc_gross > 0)
                        {
                            // A positive value means "Payment". The prices MUST match!
                            // Important: NEVER, EVER compare two floating point values for equality.
                            $isValid = ($gross - $mc_gross) < 0.05;
                            if(!$isValid) {
                                $errors[] = JText::_('PLG_AXISUBS_PAYMENT_PAYPAL_ERROR_PAYMENT_AMOUNT_MISMATCH');
                                $this->_log($errors);
                            }
                        }

                        $transaction_data = array(
                            'subscription_id' => $subscription->axisubs_subscription_id,
                            'user_id' => $subscription->user_id,
                            'payment_processor' => $this->_element,
                            'transaction_ref_id' => $data ['txn_id'],
                            'subscription_profile_id' => $data ['subscr_id'],
                            'transaction_amount' => $mc_gross,
                            'transaction_currency' => $data['mc_currency'],
                            'prepayment' => "",
                            'postpayment' => $data['transaction_details'],
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
                        $this->_log($errors);
                    }
                }

                if (  !in_array($data['txn_type'], $known_txn_response_types )  ) {
                    // other methods not supported right now
                    $errors[] = JText::_( "J2STORE_PAYPAL_ERROR_INVALID_TRANSACTION_TYPE" ).": ".$data['txn_type'];
                    $this->_log($errors);
                }
            }
        }

        if (count($errors) > 0) {
            $sitename = $config = JFactory::getConfig()->get('sitename');
            //send error notification to the administrators
            $subject = JText::sprintf('J2STORE_PAYPAL_EMAIL_PAYMENT_NOT_VALIDATED_SUBJECT', $sitename);

            $receivers = $this->_getAdmins();
            foreach ($receivers as $receiver) {
                //TODO: sending of error emails
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
     * Method to convert the given number of days into paypal duration format
     * */
    private function _toPPDuration($days)
    {
        $ret = (object)array(
            'unit'  => 'D',
            'value' => $days
        );

        // 0-90 => return days
        if ($days < 90)
        {
            return $ret;
        }

        // Translate to weeks, months and years
        $weeks = (int)($days / 7);
        $months = (int)($days / 30);
        $years = (int)($days / 365);

        // Find which one is the closest match
        $deltaW = abs($days - $weeks * 7);
        $deltaM = abs($days - $months * 30);
        $deltaY = abs($days - $years * 365);
        $minDelta = min($deltaW, $deltaM, $deltaY);

        // Counting weeks gives a better approximation
        if ($minDelta == $deltaW)
        {
            $ret->unit = 'W';
            $ret->value = $weeks;

            // Make sure we have 1-52 weeks, otherwise go for a months or years
            if (($ret->value > 0) && ($ret->value <= 52))
            {
                return $ret;
            }
            else
            {
                $minDelta = min($deltaM, $deltaY);
            }
        }

        // Counting months gives a better approximation
        if ($minDelta == $deltaM)
        {
            $ret->unit = 'M';
            $ret->value = $months;

            // Make sure we have 1-24 month, otherwise go for years
            if (($ret->value > 0) && ($ret->value <= 24))
            {
                return $ret;
            }
            else
            {
                $minDelta = min($deltaM, $deltaY);
            }
        }

        // If we're here, we're better off translating to years
        $ret->unit = 'Y';
        $ret->value = $years;

        if ($ret->value < 0)
        {
            // Too short? Make it 1 (should never happen)
            $ret->value = 1;
        }
        elseif ($ret->value > 5)
        {
            // One major pitfall. You can't have renewal periods over 5 years.
            $ret->value = 5;
        }

        return $ret;
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


    /**
     * Simple logger OVERRIDEEN FOR TETS PURPOSES
     *
     * @param string $text
     * @param string $type
     * @return void
     */
    function _log($text, $type = 'message')
    {
        if (is_array($text) || is_object($text)) {
            $text = json_encode($text);
        }
        
        $isLog = $this->params->get('debug',0);
        if ($isLog) {
            $file = JPATH_ROOT . "/cache/{$this->_element}.txt";
            $date = \JDate::getInstance();

            $f = fopen($file, 'a');
            fwrite($f, "\n\n" . $date->format('Y-m-d H:i:s'));
            fwrite($f, "\n" . $type . ': ' . $text);
            fclose($f);
        }
    }

}
/* *

response from paypal site

Once a recurring profile has been successfully created.
---
$data['txn_type'] == 'subscr_signup')
* {
    "invoice": "16",
    "amount3": "5.00",
    "address_status": "confirmed",
    "recur_times": "5",
    "subscr_date": "04:40:00 Jun 16, 2016 PDT",
    "payer_id": "DFJQT572UHLQJ",
    "address_street": "1 Main St",
    "mc_amount3": "5.00",
    "charset": "windows-1252",
    "address_zip": "95131",
    "first_name": "student",
    "reattempt": "1",
    "address_country_code": "US",
    "address_name": "student sasi",
    "notify_version": "3.8",
    "subscr_id": "I-DBXSSA1K96Y1",
    "custom": "16",
    "payer_status": "verified",
    "business": "j2storesasi-facilitator@gmail.com",
    "address_country": "United States",
    "address_city": "San Jose",
    "verify_sign": "APueAIIswPm3q4MHfNgQRsbtcQNkArYiS8CaYLQvAVtydOX1pLPVVFA4",
    "payer_email": "j2storesasi-buyer@gmail.com",
    "last_name": "sasi",
    "address_state": "CA",
    "receiver_email": "j2storesasi-facilitator@gmail.com",
    "recurring": "1",
    "txn_type": "subscr_signup",
    "item_name": "recurrin1",
    "mc_currency": "USD",
    "residence_country": "US",
    "test_ipn": "1",
    "period3": "1 D",
    "ipn_track_id": "4ea1edc8c9fd"
}

notification for a successfull Payment
---

$data['txn_type'] == 'subscr_payment')
{
    "mc_gross": "5.00",
    "invoice": "16",
    "protection_eligibility": "Eligible",
    "address_status": "confirmed",
    "payer_id": "DFJQT572UHLQJ",
    "address_street": "1 Main St",
    "payment_date": "04:40:02 Jun 16, 2016 PDT",
    "payment_status": "Completed",
    "charset": "windows-1252",
    "address_zip": "95131",
    "first_name": "student",
    "mc_fee": "0.45",
    "address_country_code": "US",
    "address_name": "student sasi",
    "notify_version": "3.8",
    "subscr_id": "I-DBXSSA1K96Y1",
    "custom": "16",
    "payer_status": "verified",
    "business": "j2storesasi-facilitator@gmail.com",
    "address_country": "United States",
    "address_city": "San Jose",
    "verify_sign": "A5fnw1THwiXcAXrh.njPTTF-TKJOAcBD6tLlN0TtRoMmMWNCAr6L9pbR",
    "payer_email": "j2storesasi-buyer@gmail.com",
    "txn_id": "3CV71004E8561912L",
    "payment_type": "instant",
    "last_name": "sasi",
    "address_state": "CA",
    "receiver_email": "j2storesasi-facilitator@gmail.com",
    "payment_fee": "0.45",
    "receiver_id": "K495JRLQF3CTA",
    "txn_type": "subscr_payment",
    "item_name": "recurrin1",
    "mc_currency": "USD",
    "residence_country": "US",
    "test_ipn": "1",
    "transaction_subject": "recurrin1",
    "payment_gross": "5.00",
    "ipn_track_id": "4ea1edc8c9fd"
}

$data['txn_type'] == web_accept

{
    "mc_gross": "20.00",
    "invoice": "19",
    "protection_eligibility": "Eligible",
    "address_status": "unconfirmed",
    "payer_id": "6937KEGZGJMVY",
    "tax": "0.00",
    "address_street": "test\r\ntest",
    "payment_date": "08:05:49 Jun 16, 2016 PDT",
    "payment_status": "Completed",
    "charset": "windows-1252",
    "address_zip": "65685",
    "first_name": "count",
    "mc_fee": "0.88",
    "address_country_code": "AU",
    "address_name": "rec test",
    "notify_version": "3.8",
    "custom": "19",
    "payer_status": "verified",
    "business": "j2storesasi-facilitator@gmail.com",
    "address_country": "Australia",
    "address_city": "salem",
    "quantity": "1",
    "verify_sign": "AD.EssxJaCA33vwTdVBvnzP1jLMpAbECxi2gf0Guxibvnyk98ksP1pif",
    "payer_email": "countasasi-buyer2@gmail.com",
    "txn_id": "4DY5495450987035K",
    "payment_type": "instant",
    "last_name": "sas",
    "address_state": "US",
    "receiver_email": "j2storesasi-facilitator@gmail.com",
    "payment_fee": "0.88",
    "receiver_id": "K495JRLQF3CTA",
    "txn_type": "web_accept",
    "item_name": "non recurring",
    "mc_currency": "USD",
    "item_number": "",
    "residence_country": "US",
    "test_ipn": "1",
    "handling_amount": "0.00",
    "transaction_subject": "",
    "payment_gross": "20.00",
    "shipping": "0.00",
    "ipn_track_id": "88addf4d35afa"
}

         **/