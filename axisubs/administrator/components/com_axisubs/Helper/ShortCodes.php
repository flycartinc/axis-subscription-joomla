<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use Flycart\Axisubs\Admin\Model\Mixin\CarbonHelper;
use Carbon\Carbon;
use JFactory;
use JText;
use JHTML;
use Flycart\Axisubs\Admin\Helper\Axisubs;
/**
 * ShortTags helper
 */
class ShortCodes{

	public static $instance = null;

	protected $subscription = '';

	protected $subscriptiondata = '';

	protected $customer = '';
	protected $plan = '';
	protected $subscriptioninfo = '';
	protected $transaction = '';


	/**
	 * get an instance
	 * @param array $config
	 * @return \Flycart\Axisubs\Admin\Helper\Permission
	 * * */
	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}
		return self::$instance;
	}

	/**
	 * define list of short codes based on different objects such as customer, subscription, Payment, etc.,
	 * getShortCodes($object_type)
	 * processShortCode($object,$code)
	 * processContent($text, $objects) // processes list of all shorcodes in the text using the object
	 * */

	/**
	 * Method to list different shortcodes based on short code type
	 * @param string $object_type different objects such as customer, subscription, Payment, etc.,
	 * */
	function getShortCodes( $object_type = '' ){

		$shortcodes = array();

		$subscription_shortcodes = array( 'AXISUBS_SUBSCRIPTION_ID', 
											'USER_ID', 
											'PLAN_ID', 
											'PLAN_QUANTITY', 
											'STATUS', 
											'START_DATE', 
											'TRIAL_START',
											'TRIAL_END', 
											'CURRENT_TERM_START', 
											'CURRENT_TERM_END', 
											'CREATED_ON', 
											/*'CREATED_FROM_IP', 
											'IP', 
											'IP_COUNTRY', */
											'CURRENCY_CODE', 
											/*'CURRENCY_VALUE', */
											'TOTAL', 
											'SUBTOTAL', 
											'PLAN_PRICE', 
											'SETUP_FEE', 
											'SUBTOTAL_EX_TAX', 
											'TAX',
											'DISCOUNT',
											'DISCOUNT_TAX',
											/*'SHIPPING', 
											'SHIPPING_TAX',
											'SURCHARGE', 
											'FEES' */ );
		$subscription_info_shortcodes = array(  'SUBSCRIPTION_ID', 
												'USER_ID', 
												'BILLING_FIRST_NAME', 
												'BILLING_LAST_NAME', 
												'BILLING_EMAIL', 
												'BILLING_PHONE', 
												'BILLING_COMPANY', 
												'VAT_NUMBER', 
												'BILLING_ADDRESS1', 
												'BILLING_ADDRESS2', 
												'BILLING_CITY', 
												'BILLING_STATE', 
												'BILLING_ZIP', 
												'BILLING_COUNTRY', 	);

		$customer_shortcodes = array( 		'USER_ID', 
											'FIRST_NAME', 
											'LAST_NAME', 
											'EMAIL', 
											'PHONE', 
											'COMPANY', 
											'VAT_NUMBER', 
											'ADDRESS1', 
											'ADDRESS2', 
											'CITY', 
											'STATE', 
											'ZIP', 
											'COUNTRY' );
		
		$plan_shortcodes = array( 				'AXISUBS_PLAN_ID', 
												'NAME', 
												/*'SLUG', 
												'IMAGE', 
												'DESCRIPTION', */
												'PERIOD', 
												'TRIAL_PERIOD', 
												/*'CHARGE_MODEL', */
												'PRICE', 
												'SETUP_COST', 
												/*'RECURRING', 
												'FOREVER', 
												'ACCESS', 
												'FIXED_DATE', 
												'PAYMENT_PLUGINS', */
												'RENEW_URL', );	

		$shortcodes['subscription'] 	= $subscription_shortcodes;	
		$shortcodes['customer'] 		= $customer_shortcodes;	
		$shortcodes['subscriptioninfo'] = $subscription_info_shortcodes;	
		$shortcodes['plan'] 			= $plan_shortcodes;	

		if ( !empty($object_type) && isset($shortcodes [$object_type]) ){
			return $shortcodes [$object_type];
		}

		return $shortcodes;
	}

	/**
	 * Method to bind the subscription and related objects with the shortcode factory object
	 * @param object $obj object
	 * */
	function bindSubscription($obj){

		// check if a subscription object
		if (! ($obj instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $obj instanceof \Flycart\Axisubs\Site\Model\Subscriptions) ) {
			return '';
		}
		$this->subscription 	= $obj ;
		$this->subscriptiondata = $this->subscription->getFormatedShortCodes();

		$this->customer 		= $obj->customer ;
		$this->plan 			= $obj->plan ;
		$this->subscriptioninfo = $obj->subscriptioninfo ;
		$this->transaction 		= $obj->transaction ;
	}

	/**
	 * Method to bind the customer and related objects with the shortcode factory object
	 * @param object $obj object
	 * */
	function bindCustomer($obj){
		
		// check if a object
		if ( ! is_object($obj) ) {
			return '';
		}

		$this->customer = $obj ;
	}

	/**
	 * Method to bind the plan and related objects with the shortcode factory object
	 * @param object $obj object
	 * */
	function bindPlan( $obj ){
		// check if a object
		if ( ! is_object($obj) ) {
			return '';
		}

		$this->plan = $obj ;	
	}

	/**
	 * Method to get the list of shortcodes to be listed in a dropdown in email templates
	 * */
	function getAllShortCodesOptions(){
		$all_shortcodes = $this->getShortCodes();
		$shortcodes = array();
		$all_options = array();

		foreach ($all_shortcodes as $shortcode_grp => $codes) {
			$all_options[] = JHTML::_('select.optgroup', JText::_('AXISUBS_CODE_GROUP_'.$shortcode_grp ) );
			foreach ($codes as $code) {
				$sc = '['. $shortcode_grp .':'. $code .']' ;
				$sc = strtoupper( $sc ) ;
				$shortcodes[$sc] = JText::_('AXISUBS_CODE_'.$shortcode_grp.'_'.$code);
				$all_options[] = JHTML::_('select.option', $sc, JText::_('AXISUBS_CODE_'.$shortcode_grp.'_'.$code) );
			}
			$all_options[] = JHTML::_('select.optgroup', JText::_('AXISUBS_CODE_GROUP_'.$shortcode_grp ) );
		}

		//return $shortcodes;
		return $all_options;
	}

	/**
	 * Method to process a single shortcode based on the supplied object and code
	 * @param object $object 	subscription, plan or customer object 
	 * @param string $code 		Shortcode with or without prefix
	 * @return string 			the content of the shortcode 
	 * */
	function processShortCode( $code, $object ){
		// seperate the var name if prefixed with OBJECTNAME:VARNAME
		$varname = '';
		$result = '';
		if (!empty($code)){
			$strpos = 0 ;
			$strpos = strpos($code, ':') + 1;
			if ($strpos > 0){
				$objname = strtolower( substr($code, 1, $strpos-2 ) ) ;
			 	$varname = substr($code, $strpos );
			}else {
				$varname = ltrim($varname, '[');
			}
			$varname = strtolower( rtrim($varname, ']') );
		}

		if (!empty($objname) && $objname == 'subscription') {
			$objname = 'subscriptiondata';
		}
		if ( !empty($objname) && isset($this->$objname) && !empty($varname)
									&& isset($this->$objname->$varname) ){
			$result = $this->$objname->$varname;
			if($varname == 'period'){
				$result = $this->getPlanPeriods($this->$objname->period, $this->$objname->period_unit);
			}
			if($varname == 'trial_period'){
				$result = $this->getPlanPeriods($this->$objname->trial_period, $this->$objname->trial_period_unit);
			}
			if($varname == 'country' || $varname == 'billing_country'){
				$result = $this->getCustomerCountry($this->$objname->$varname);
			}
			if($varname == 'state' || $varname == 'billing_state'){
				if($varname == 'state') {
					$result = $this->getCustomerZone($this->$objname->$varname, $this->$objname->country);
				} else {
					$result = $this->getCustomerZone($this->$objname->$varname, $this->$objname->billing_country);
				}
			}
			if($varname == 'renew_url') {
				$result = $this->getRenewalURL($this->$objname->slug);
			}
		} elseif ( !empty($varname) && is_object($object) && isset( $object->$varname ) ) {
			$result = $object->$varname;
			if($varname == 'period'){
				$result = $this->getPlanPeriods($object->period, $object->period_unit);
			}
			if($varname == 'trial_period'){
				$result = $this->getPlanPeriods($object->trial_period, $object->trial_period_unit);
			}
			if($varname == 'state' || $varname == 'billing_state'){
				if($varname == 'state') {
					$result = $this->getCustomerZone($object->$varname, $object->country);
				} else {
					$result = $this->getCustomerZone($object->$varname, $object->billing_country);
				}
			}
			if($varname == 'country' || $varname == 'billing_country'){
				$result = $this->getCustomerCountry($object->$varname);
			}
			if($varname == 'renew_url') {
				$result = $this->getRenewalURL($object->slug);
			}
		}elseif ( is_array($object) && isset( $object [ $varname ] ) ) {
			$result = $object [ $varname ];
			if($varname == 'period'){
				$result = $this->getPlanPeriods($object['period'], $object['period_unit']);
			}
			if($varname == 'trial_period'){
				$result = $this->getPlanPeriods($object['trial_period'], $object['trial_period_unit']);
			}
			if($varname == 'state' || $varname == 'billing_state'){
				if($varname == 'state') {
					$result = $this->getCustomerZone($object [ $varname ], $object [ 'country' ]);
				} else {
					$result = $this->getCustomerZone($object [ $varname ], $object [ 'billing_country' ]);
				}
			}
			if($varname == 'country' || $varname == 'billing_country'){
				$result = $this->getCustomerCountry($object [ $varname ]);
			}
			if($varname == 'renew_url') {
				$result = $this->getRenewalURL($object ['slug']);
			}
		}

//		print_r($varname);exit;
		if ( is_array($result) ) {
			$result = implode(',', $result);
		} elseif ( is_object($result) ){
			$result = '';
		}

		return $result;
	}

	/**
	 * get Plan period in format
	 * */
	protected function getPlanPeriods($period, $period_unit){
		$get_duration = Axisubs::duration();
		return $get_duration->getDurationInFormat($period, $period_unit);
	}

	/**
	 * get customer Zone in format
	 * */
	protected function getCustomerZone($state, $country){
		$stateSelected = Select::getZones($country);
		if(isset($stateSelected[$state])) {
			return $stateSelected[$state];
		} else {
			return $state;
		}
	}

	/**
	 * get customer Zone in format
	 * */
	protected function getCustomerCountry($country){
		return Select::decodeCountry($country);
	}

	/**
	 * get date in format
	 * */
	protected function getRenewalURL($slug)
	{
		$app = JFactory::getApplication();
		$siteApp = $app->getInstance('site');
		$siteRouter = $siteApp->getRouter();
		$newURI = 'index.php?option=com_axisubs&view=subscribe&plan='.$slug;
		$baseURL = \JURI::base();
		$baseURLNew = str_replace('/administrator', '', $baseURL);
		$generatedURL = \JURI::root( false, $siteRouter->build($newURI));
		$newURL = str_replace($baseURL, $baseURLNew, $generatedURL);

		return $newURL;
	}

	/**
	 * Method to Extract the short code from a given text and process all the shortcodes 
	 * @param string	$text 		text to be processed
	 * @param object 	$objects	subscription object
	 * @return strig				processed text after replacing shortcodes
	 * */
	function processContent( $text, $object ){

		//now we have unprocessed fields. remove any other square brackets found.
		preg_match_all("^\[(.*?)\]^",$text,$removeFields, PREG_PATTERN_ORDER);

		if(count($removeFields[1])) {
			foreach($removeFields[1] as $fieldName) {
				//$text= str_replace('['.$fieldName.']', '', $text);
				$field_val = ''; 
				$field_val = $this->processShortCode( '['.$fieldName.']', $object );
				$text = str_replace('['.$fieldName.']', $field_val, $text);
			}
		}

		return $text;
	}

	/**
	 * Method to get the list of recipients shortcode
	 * */
	function getRecipientShortCodes(){
		$recip_shortcodes = array(
								   '[CUSTOMER]' => JText::_('AXISUBS_CODE_GROUP_CUSTOMER'),
								   /*'[ADMINS]'=> JText::_('AXISUBS_CODE_GROUP_ADMIN'), */  );
		// get list of user groups
		
		$usergroups = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level')
			->from($db->quoteName('#__usergroups') . ' AS a')
			->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->group('a.id, a.title, a.lft, a.rgt')
			->order('a.lft ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();

		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			if ( in_array( $options[$i]->text, array('Guest','Registered','Public') ) ) {
				continue;
			}
			$k ='';
			$k = '[USERGROUP:'.$options[$i]->text.']';
			$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;			
			$usergroups[$k] = $options[$i]->text;
		}

		$recip_shortcodes = array_merge($recip_shortcodes, $usergroups);
		return $recip_shortcodes;
	}

	/**
	 * Method to get the list of recipients 
	 * */
	function processRecipients($recipient_list, $objects){

		// trying to load from subscription 
		$subscription ='';
		if ( isset( $objects->subscription ) ) {
			$subscription = $objects->subscription;
		}elseif ( isset($this->subscription) ){
			$subscription = $this->subscription;
		}
		
		if ( !empty($subscription) && isset( $subscription->customer ) 
				&& isset($subscription->customer->email) && !empty($subscription->customer->email)) {
			$customer_email = $subscription->customer->email;
			$recipient_list = str_replace('[CUSTOMER]', $customer_email, $recipient_list );	
		}

		// trying to load from customer array
		if( empty( $customer_email ) && isset($objects->customer) ) {
			if (is_array($objects->customer) && isset($objects->customer['email'])) {
				$customer_email = $objects->customer['email'];
			}elseif ( isset($objects->customer->email) ){
				$customer_email = $objects->customer->email;
			}
		}

		preg_match_all("^\[(.*?)\]^",$recipient_list,$removeFields, PREG_PATTERN_ORDER);
		if(count($removeFields[1])) {
			foreach($removeFields[1] as $fieldName) {

				$user_emails = '';

				if ( substr($fieldName, 0, 9 ) == 'USERGROUP' ) {
					$user_group_name = str_replace('USERGROUP:', '', $fieldName);
					// now get the list of users in the user group name 
					$user_emails = $this->getUsersInUserGroup($user_group_name);
				}

				$recipient_list = str_replace('['.$fieldName.']', $user_emails, $recipient_list);
			}
		}

		// strip unwanted commas
		$recipient_list_arr = explode(',', $recipient_list);
		$recipient_list_arr = array_filter($recipient_list_arr);

		return $recipient_list_arr;
	}

	/**
	 * Method to extract the list of email ids in a user group
	 * */
	private function getUsersInUserGroup( $user_group_name='' ){
		if ( empty($user_group_name) ) {
			return '';
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id')
			->from($db->quoteName('#__usergroups') . ' AS a')
			->where(' title LIKE '. $db->q('%'.$user_group_name.'%') );
		$db->setQuery($query);
		$usergroup_id = $db->loadResult();
		
		if (empty($usergroup_id)) {
			return '';
		}
		
		// now get list of users from a user group
		$query = $db->getQuery(true)
				->select('u.email')
				->from ('#__user_usergroup_map uugm')
				->join('left','#__users u on u.id=uugm.user_id')
				->where('uugm.group_id = '.$db->q($usergroup_id));
		$db->setQuery($query,0,20);
		$user_emails = $db->loadColumn();
		
		$user_emails_csv = implode( ',', $user_emails ) ;
		return $user_emails_csv;
	}

}