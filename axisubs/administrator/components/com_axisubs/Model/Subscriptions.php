<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Model\Plans;
use FOF30\Model\DataModel;
use JLoader;
use Carbon\Carbon;
use JText;
use JFactory;

use Flycart\Axisubs\Admin\Helper\Tax;
use CommerceGuys\Addressing\Model\Address;

use CommerceGuys\Tax\Repository\TaxTypeRepository;
use CommerceGuys\Tax\Resolver\TaxType\ChainTaxTypeResolver;
use CommerceGuys\Tax\Resolver\TaxType\CanadaTaxTypeResolver;
use CommerceGuys\Tax\Resolver\TaxType\EuTaxTypeResolver;
use CommerceGuys\Tax\Resolver\TaxType\DefaultTaxTypeResolver;
use CommerceGuys\Tax\Resolver\TaxRate\ChainTaxRateResolver;
use CommerceGuys\Tax\Resolver\TaxRate\DefaultTaxRateResolver;
use CommerceGuys\Tax\Resolver\TaxResolver;

use Flycart\Axisubs\Admin\Helper\Taxable;

use CommerceGuys\Tax\Resolver\Context;

/**
 * Model class for Akeeba Subscriptions user data
 *
 * @property  int		$axisubs_user_id
 * @property  int		$user_id
 * @property  int		$isbusiness
 * @property  string	$businessname
 * @property  string	$occupation
 * @property  string	$vatnumber
 * @property  int		$viesregistered
 * @property  string	$taxauthority
 * @property  string	$address1
 * @property  string	$address2
 * @property  string	$city
 * @property  string	$state
 * @property  string	$zip
 * @property  string	$country
 * @property  array		$params
 * @property  string	$notes
 * @property  int		$needs_logout
 *
 * @method  $this  axisubs_user_id()  axisubs_user_id(int $v)
 * @method  $this  user_id()             user_id(int $v)
 * @method  $this  isbusiness()          isbusiness(bool $v)
 * @method  $this  businessname()        businessname(string $v)
 * @method  $this  occupation()          occupation(string $v)
 * @method  $this  vatnumber()           vatnumber(string $v)
 * @method  $this  viesregistered()      viesregistered(bool $v)
 * @method  $this  taxauthority()        taxauthority(string $v)
 * @method  $this  address1()            address1(string $v)
 * @method  $this  address2()            address2(string $v)
 * @method  $this  city()                city(string $v)
 * @method  $this  state()               state(string $v)
 * @method  $this  zip()                 zip(string $v)
 * @method  $this  country()             country(string $v)
 * @method  $this  notes()               notes(string $v)
 * @method  $this  needs_logout()        needs_logout(bool $v)
 * @method  $this  block()               block(bool $v)
 * @method  $this  username()            username(string $v)
 * @method  $this  name()                name(string $v)
 * @method  $this  email()               email(string $v)
 * @method  $this  search()              search(string $v)
 *
 * @property-read  JoomlaUsers		$user
 * @property-read  Subscriptions[]  $subscriptions
 */
class Subscriptions extends DataModel
{
	use Mixin\JsonData, Mixin\Assertions,  Mixin\CarbonHelper, Mixin\FOF3Utils;

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->addBehaviour('RelationFilters');

		$this->hasOne('customer', 'Customers', 'user_id', 'user_id');
		$this->hasOne('plan', 'Plans', 'plan_id', 'axisubs_plan_id');
		$this->hasOne('subscriptioninfo', 'SubscriptionInfos', 'axisubs_subscription_id', 'subscription_id');
		$this->hasOne('transaction', 'Transactions', 'axisubs_subscription_id', 'subscription_id');
		//$this->hasMany('transactions', 'Transactions', 'axisubs_subscription_id', 'subscription_id');

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [ 'plan_quantity', 'status_id', 'email', 'trial_start', 'trial_end', 
							'remaining_billing_cycles', 'po_number', 'started_at', 'activated_at', 'cancel_reason', 
							'affiliate_token', 'created_from_ip', 'has_scheduled_changes', 'due_invoices_count', 
							'due_since','total_dues', 'invoice_notes', 'ip', 'ip_country','skip_trial',
							'recurring_amount', 'tax_percent', 'prediscount_amount', 'params', 'ref_subscription_id'];
	}

	/**
	 * Build the query to fetch data from the database
	 *
	 * @param   boolean $overrideLimits Should I override limits
	 *
	 * @return  \JDatabaseQuery  The database query to use
	 */
	public function buildQuery($overrideLimits = false)
	{
		// Get a "select all" query
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('#__axisubs_subscriptions.*')
			->from($this->getTableName());

		// Run the "before build query" hook and behaviours
		$this->triggerEvent('onBeforeBuildQuery', array(&$query, $overrideLimits));

		// Apply custom WHERE clauses
		if (count($this->whereClauses))
		{
			foreach ($this->whereClauses as $clause)
			{
				$query->where($clause);
			}
		}

		$order = $this->getState('filter_order', null, 'cmd');

		if (!array_key_exists($order, $this->knownFields))
		{
			$order = $this->getIdFieldName();
			$this->setState('filter_order', $order);
		}

		$order = $db->qn($order);

		$dir = strtoupper($this->getState('filter_order_Dir', null, 'cmd'));

		if (!in_array($dir, array('ASC', 'DESC')))
		{
			$dir = 'ASC';
			$this->setState('filter_order_Dir', $dir);
		}

		$query->order($order . ' ' . $dir);

		// Run the "before after query" hook and behaviours
		$this->triggerEvent('onAfterBuildQuery', array(&$query, $overrideLimits));

		return $query;
	}

	/**
	 * Map state variables from their old names to their new names, for a modicum of backwards compatibility
	 *
	 * @param   \JDatabaseQuery  $query
	 */
	protected function onBeforeBuildQuery(\JDatabaseQuery &$query)
	{
		// Set the default ordering by ID, descending
		if (is_null($this->getState('filter_order', null, 'cmd')) && is_null($this->getState('filter_order_Dir', null, 'cmd')))
		{
			$this->setState('filter_order', $this->getIdFieldName());
			$this->setState('filter_order_Dir', 'DESC');
//			$this->setState('filter_order', '`status`');
//			$this->setState('filter_order_Dir', 'ASC');
		}
	}

	/**
	 * Build the SELECT query for returning records. Overridden to apply custom filters.
	 *
	 * @param   \JDatabaseQuery  $query           The query being built
	 * @param   bool             $overrideLimits  Should I be overriding the limit state (limitstart & limit)?
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(\JDatabaseQuery $query, $overrideLimits = false)
	{
		$db = $this->getDbo();

		$tableAlias = $this->getBehaviorParam('tableAlias', null);
		$tableAlias = !empty($tableAlias) ? ($db->qn($tableAlias) . '.') : '';

		/*$query->join('LEFT','#__axisubs_customers as ac ON ac.user_id = #__axisubs_subscriptions.user_id');
		$query->join('LEFT','#__axisubs_plans as ap ON ap.axisubs_plan_id = #__axisubs_subscriptions.plan_id');
*/
		$start_date = $this->getState('filter_start_date', null);
		$end_date = $this->getState('filter_end_date', null);

		$filter_created_date = $this->getState('filter_created_date', 1);
		$filter_trial_date = $this->getState('filter_trial_date', 0);
		$filter_term_date = $this->getState('filter_term_date', 0);

		// clean start date
		if (!empty($start_date)){
			$cstart_date 	 = $this->getDate($start_date);
			$cstart_date->hour = 0;
			$cstart_date->minute = 0;
			$cstart_date->second = 0;
			$start_date = $cstart_date->toDateTimeString();
		}
		if (!empty($end_date)){
			$cend_date 	 = $this->getDate($end_date);
			$cend_date->hour = 23;
			$cend_date->minute = 59;
			$cend_date->second = 59;
			$end_date = $cend_date->toDateTimeString();
		}

		if ( $filter_created_date ) {
			if (!empty($start_date) && $start_date!='0000-00-00 00:00:00' && $start_date!='0000-00-00' ){
				$query->where(
					$tableAlias . $db->qn('#__axisubs_subscriptions.created_on') . ' >= ' .  $db->q($start_date)
				);
			}

			if (!empty($end_date) && $end_date!='0000-00-00 00:00:00' && $start_date!='0000-00-00' ){
				$query->where(
					$tableAlias . $db->qn('#__axisubs_subscriptions.created_on') . ' <= ' . $db->q($end_date)
				);
			}
		}

		$exclude_this = $this->getState('exclude_this', 0);
		if ( $exclude_this > 0 ) {
			$query->where(
				$tableAlias . $db->qn('#__axisubs_subscriptions.axisubs_subscription_id') . ' <> ' . $db->q($exclude_this)
			);
		}

		$statuses = $this->getState('statuses', array() );
		if ( count($statuses) > 0  ) {
			$str_statuses = '';
			foreach ($statuses as $k => $st) {
				$str_statuses .= $db->q($st).',';
			}
			$str_statuses = trim($str_statuses, ',');

			$query->where(
				$tableAlias . $db->qn('#__axisubs_subscriptions.status') . ' IN ( ' . $str_statuses .' ) '
			);
		}
	/*	if ( $filter_trial_date ) {
			if (!empty($start_date) || $start_date!='0000-00-00 00:00:00'){
				$query->where(
					$tableAlias . $db->qn('trial_start') . ' >= ' .  $db->q($start_date)
				);
			}

			if (!empty($end_date) || $end_date!='0000-00-00 00:00:00'){
				$query->where(
					$tableAlias . $db->qn('trial_end') . ' <= ' . $db->q($end_date)
				, 'OR');
			}
		}
		
		$term_start = $this->getState('term_start', null, 'string');
		$term_end = $this->getState('term_end', null, 'string');

		if ( $filter_term_date ) {
			if (!empty($start_date) || $start_date!='0000-00-00 00:00:00'){
				$query->where(
					$tableAlias . $db->qn('current_term_start') . ' >= ' .  $db->q($start_date)
				);
			}

			if (!empty($end_date) || $end_date!='0000-00-00 00:00:00'){
				$query->where(
					$tableAlias . $db->qn('current_term_end') . ' <= ' . $db->q($end_date)
				, 'OR');
			}
		}
		*/

		$this->filterByTermDates( $query );

		$search = $this->getState('filter_search', null, 'string');
		if($search)
		if ((int)$search)
		{
			$query->where( $db->qn('axisubs_subscription_id') . ' = '. $db->q($search));
		} else {
			$this->whereHas('subscriptioninfo', function(\JDatabaseQuery $subQuery) use($search, $db) {
				$subQuery->where('('.$db->qn('billing_first_name') . ' LIKE ' . $db->q('%' . $search . '%').') OR '.
					'('.$db->qn('billing_last_name') . ' LIKE ' . $db->q('%' . $search . '%').')');
			});
		}
		$payment_processor = $this->getState('payment_processor', null, 'string');
		if($payment_processor){
			$this->whereHas('transaction', function(\JDatabaseQuery $subQuery) use($payment_processor, $db) {
				$subQuery->where($db->qn('payment_processor') . ' = ' . $db->q($payment_processor));
			});
		}

		$is_recurring = $this->getState('recurring', 0, 'int');
		if ( $is_recurring > 0 ) {
			$query->where( $db->qn('remaining_billing_cycles') . ' >= 1 ' );
		}

		$price_from = $this->getState('filter_price_from', null, 'int');
		if ( !empty($price_from) ) {
			$query->where(
					'(' .
					'(' . $db->qn('total') . ' >= ' .$db->q($price_from) .' ) OR ' .
					'(' . $db->qn('subtotal') . ' >= ' .$db->q($price_from) .' ) '
					//'(' . $db->qn('subtotal') . ' >= ' .$db->q($price_from) .' ) ' .
					//'(' . $db->qn('tax') . ' >= ' .$db->q($price_from) .' )  '
					. ')'
					);
		}
		
		$price_to   = $this->getState('filter_price_to', null, 'int');
		if ( !empty($price_to) ) {
			$query->where(
					'(' .
					'(' . $db->qn('total') . ' <= ' .$db->q($price_to) .' ) OR ' .
					'(' . $db->qn('subtotal') . ' <= ' .$db->q($price_to) .' ) '
					//'(' . $db->qn('subtotal') . ' <= ' .$db->q($price_to) .' ) OR ' .
					//'(' . $db->qn('tax') . ' <= ' .$db->q($price_to) .' )  '
					. ')'
					);
		}

	}

	/**
	 * Method to filter out the subscriptions based on the expiry dates
	 * */
	protected function filterByTermDates(\JDatabaseQuery $query) {

		$db = JFactory::getDbo();

		$term_start = $this->getState('term_start', null, 'string');
		$term_end 	= $this->getState('term_end', null, 'string');
		$trial_end 	= $this->getState('trial_end', null, 'string');

		if (!empty($term_start)){
			$cstart_date 	 = $this->getDate($term_start);
			$term_start = $cstart_date->toDateTimeString();
		}

		if (!empty($term_end)){
			$cend_date 	 = $this->getDate($term_end);
			$term_end = $cend_date->toDateTimeString();
		}

		if (!empty($trial_end)){
			$cend_date 	 = $this->getDate($trial_end);
			$trial_end = $cend_date->toDateTimeString();
		}

		if ( !empty($term_start) ) {
			if (!empty($term_start) || $term_start!='0000-00-00 00:00:00'){
				$query->where(
					 $db->qn('current_term_start') . ' <= ' .  $db->q($term_start)
				);
			}
		}

		if ( !empty($term_end) ) {
			if (!empty($term_end) || $term_end!='0000-00-00 00:00:00'){
				$query->where(
					 $db->qn('current_term_end') . ' <= ' . $db->q($term_end)
				);
			}
		}

		if ( !empty($trial_end) ) {
			if (!empty($trial_end) || $trial_end!='0000-00-00 00:00:00'){
				$query->where(
					 $db->qn('trial_end') . ' <= ' . $db->q($trial_end)
				);
			}
		}

	}

	/**
	 * Run the onAKUserSaveData event on the plugins before saving a row
	 *
	 * @param   array|\stdClass  $data  Source data
	 *
	 * @return  bool
	 */
	function onBeforeSave(&$data)
	{
		$pluginData = $data;

		if (is_object($data))
		{
			if ($data instanceof DataModel)
			{

				$pluginData = $data->toArray();
			}
			else
			{
				$pluginData = (array) $data;
			}
		}

		$this->container->platform->importPlugin('axisubs');
		 // trigger 'onAKUserSaveData', array(&$pluginData) ;
		//$this->getRelations()->save('customer');
	}

	/**
	 * Before checking
	 * */
	function onAfterBind(&$data){
		$app = JFactory::getApplication();

		// check if plan relation is set, else load the plan record 
		if ( ! (isset($this->plan) && ($this->plan instanceof \Flycart\Axisubs\Admin\Model\Plans || $this->plan instanceof \Flycart\Axisubs\Site\Model\Plans)) ){
			// maybe try to load it ourself
			$plan = $this->getContainer()->factory->model('Plans');

			if ( !empty($this->plan_id) ){
				$plan->load($this->plan_id);
				if ( empty($plan->axisubs_plan_id) ){
					$this->throwError( 'COM_AXISUBS_SUBSCRIPTION_ERR_CANNOT_BIND_PLAN_RELATION' );
					// log this error it's an application level error
				}else {
					$this->plan = clone($plan); // everything is fine bind the relation
				}
			}
		}

		// check if supplied start date is a valid one
		if ( isset($data['start_date']) && !empty($data['start_date']) ){
			$start_date = $this->getDate( $data['start_date'] );
		} else {
			$start_date = $this->getCurrentDate();
		}

		// new subscription then bind subscription dates and calculate rates
		$oldPk = $this->getId();
		
		if ( isset($this->plan) && ($this->plan instanceof \Flycart\Axisubs\Admin\Model\Plans || $this->plan instanceof \Flycart\Axisubs\Site\Model\Plans) && $start_date instanceof Carbon && empty($oldPk) ){
		//if (( isset($this->plan) && $this->plan instanceof Plans && $start_date instanceof Carbon && empty($oldPk)) || ( isset($this->plan) && $this->plan instanceof Plans && $start_date instanceof Carbon && $couponCode != '')){
			$this->start_date = $start_date->toDateTimeString();
	
			$this->created_on = $this->getCurrentDate()->toDateTimeString();

			if ( $app->isAdmin() ){
				//TODO: get the applicable currency from the admin
				$this->currency_code = Axisubs::currency()->getCode();
				$this->currency_value = Axisubs::currency()->getValue();
				$this->language = JFactory::getLanguage()->getTag() ;
			}

			$this->calculateTermDates();
			$this->calculateTotals();
		}
	}

	/**
	 * Validates the subscription row
	 */
	public function check()
	{
		$errors = array();

		// certain things needs to be cheked even before validation
		// 3 essential things - user_id, plan_id and its relation

		// check if the user id or customer details exists
		/*if ( empty($this->user_id) || $this->user_id <= 0 ){
			$errors[] = JText::_('COM_AXISUBS_SUBSCRIPTION_ERR_USER_ID');
		}*/

		$this->assertNotEmpty($this->user_id, 'COM_AXISUBS_SUBSCRIPTION_ERR_USER_ID');

		// check if a plan is selected 
		/*if ( empty($this->plan_id) || $this->plan_id <= 0 ){
			$errors[] = JText::_('COM_AXISUBS_SUBSCRIPTION_ERR_PLAN_ID');
		}*/
		$this->assertNotEmpty($this->plan_id, 'COM_AXISUBS_SUBSCRIPTION_ERR_PLAN_ID');

		// if the plan is allowed for this user
		if ( isset($this->plan) && ! $this->plan->canAccess($this->user_id) ){
			$this->throwError( 'COM_AXISUBS_SUBSCRIPTION_ERR_RESTRICTED_ACCESS' );
			// or throw the standard error -> throw new AccessForbidden;
		}

		//$errors = $this->validate( $data );
		if ( is_array($errors) && count($errors) > 0 ) {
			// return the validation errors
		}

		$this->assertNotEmpty($this->current_term_start, 'COM_AXISUBS_SUBSCRIPTION_ERR_EMPTY_START_DATE');
		$this->assertNotEmpty($this->current_term_end, 'COM_AXISUBS_SUBSCRIPTION_ERR_EMPTY_END_DATE');
		$statuses = Axisubs::status()->getStatusKeys();

		//$this->assertInArray($this->getFieldValue('status_id', ''), $statuses, 'COM_AXISUBS_SUBSCRIPTION_ERR_STATE');
		$this->normaliseEnabled(); 
	}

	/**
	 * Method called subscription saved
	 * */
	function onAfterCreate(){
		if ( $this->user_id <=0 || $this->axisubs_subscription_id <=0 ){
			return ;
		}
		$this->updateSubscriptionInfo();

		Axisubs::plugin()->event( 'SubscriptionCreated', array($this) );
	
	}

	/**
	 * Method called after subscription saved
	 * */
	function onAfterSave(){
		// save the totals 
		//$this->calculateDiscountTotals();
		$this->calculateTotals();
	}

	function updateFreeSubscription($data, $id){
		$subscription_model = $this->getModel('Subscriptions');
		$subscription_model->load( array( 'axisubs_subscription_id' => $id ) );
		try {
			$result = $subscription_model->save( $data );
			//trigger event after update
			Axisubs::plugin()->event('AfterSubscriptionStatusUpdate', array($subscription_model, 'N'));
			return $result;
		} catch (\Exception $e) {
			echo  $e->getMessage();exit;
		}
	}

	/**
	 * Method to update the subscription information
	 * */
	function updateSubscriptionInfo(){

		$customer_model = $this->getModel('Customers');
		$customer_model->load( array( 'user_id' => $this->user_id ) );
		$customer_data = $customer_model->getData();

		$billing_fields = array('first_name','last_name','email','address1','address2',
									'phone','company','city','state','zip','country')	;

		// create a subscription info record
		foreach ($billing_fields as $k => $field) {
			$data['billing_'.$field] = $customer_data[$field] ;
		}
		$data['vat_number'] 		= $customer_data['vat_number'];
		$data['user_id'] 			= $this->user_id;
		$data['subscription_id'] 	= $this->axisubs_subscription_id;

		try {
			$subsinfo_model = $this->getModel('SubscriptionInfos');
			$subsinfo_model->load(array( 'user_id' => $this->user_id ,
										'subscription_id'=>$this->axisubs_subscription_id)) ;
			$subsinfo_model->save( $data );	
		} catch (\Exception $e) {
			$e->getMessage();
		}
		
	}

	/**
	 * Method to calculate the totals
	 * @param string $taxes
	 */

	public function calculateTotals($taxes=true) {
		$this->order_discount = 0;
		$this->subtotal = 0;

		// implement plan quantity at a later stage
		$this->plan_quantity = 1;

		//set the subscription and customer information
		$this->setSubscriptionInformation();

		// calculate plan and add on totals
		$this->calculateSubscriptionTotals();

		// then calculate the tax
		$this->calculateTaxTotals();
		// discount
		$this->calculateDiscountTotals();
		
		// Trigger the fees API where developers can add fees or additional cost to order
		$this->calculateFeeTotals();



		// sum totals
		$total =	$this->subtotal
					+ $this->surcharge
					+ $this->fees
					- $this->discount
					;
		// set object properties
		$this->total      = $total;
		// We fire just a single plugin event here and pass the entire order object
		Axisubs::plugin()->event("CalculateSubscriptionTotals", array( &$this ) );

	}

	/**
	 * Method to calculate the trial, subscription term start and end dates
	 * Happens only once when a new subsctiption is created or when reassigned to a diff plan
	 * */
	function calculateTermDates(){

		// optional - check plan and customer details

		// process:
		// get subscription history / last active / recent subscription
		$subs_model = clone($this);
		$has_active_subscription = false;
		$active_subscription = $subs_model->user_id( $this->user_id )
					->plan_id( $this->plan_id)
					->status( 'A' ) // active and future subscriptions
					->exclude_this( $this->axisubs_subscription_id )
					->get()
					->sortByDesc( 'current_term_end' )
					->first();

		$future_subscription = $subs_model->user_id( $this->user_id )
					->plan_id( $this->plan_id)
					->status( 'F' ) // active and future subscriptions
					->exclude_this( $this->axisubs_subscription_id )
					->get()
					->sortByDesc( 'current_term_end' )
					->first();			

		if ( isset($active_subscription->axisubs_subscription_id) && $active_subscription->axisubs_subscription_id > 0 ) {
			$has_active_subscription = true;
		}
		
		$this->recurring = $this->plan->isRecurring();

		// check only once flag / renewal applicable flag
		// if renewals are not allowed, then throw exception
		if ( $this->plan->only_once == 1 && $has_active_subscription && $this->user_id){
			//$this->throwError( 'COM_AXISUBS_SUBSCRIPTION_ERR_CANNOT_RENEW' );
			\JError::raiseWarning( 100, JText::_('COM_AXISUBS_SUBSCRIPTION_ERR_CANNOT_RENEW') );
		}
		
		// if active subscription is present, then curent subscription is a renewal
		if ( $has_active_subscription ){
			//////////////////RENEWAL///////////////////
			// calculate renewal date based on present active subscription end date
			// for renewal trial start, trial end is 0 , only start and end date calculated
			$this->trial_start 		= $this->getNullDate();
			$this->trial_end 		= $this->getNullDate();

			$this->status 			= 'P'; // a renewal always has a pending subscription status until paid 

			$this->remaining_billing_cycles = $this->plan->getBillingCycle();

			$has_future_subscription = false;
			if ( isset($future_subscription->axisubs_subscription_id) 
					&& $future_subscription->axisubs_subscription_id > 0 ) {
				$has_future_subscription = true;
			}

			if ( $has_future_subscription ) {
				$active_ends_on = $this->getDate( $future_subscription->current_term_end ) ;
				$billing_cycles = $future_subscription->remaining_billing_cycles - 1 ;
				$this->remaining_billing_cycles = ($billing_cycles > 0) ? $billing_cycles : 0;
			}else{
				$active_ends_on = $this->getDate( $active_subscription->current_term_end ) ;
				$billing_cycles = $active_subscription->remaining_billing_cycles - 1 ;
				$this->remaining_billing_cycles = ($billing_cycles > 0) ? $billing_cycles : 0;
			}

			$active_ends_on->addSeconds(1);

			$current_term_start = $active_ends_on->copy();
			$date = $active_ends_on->copy();

			$current_term_end = $date->addDays( $this->plan->getPeriodInDays() ); 

			$this->current_term_start 		= $current_term_start->toDateTimeString();
			$this->current_term_end 		= $current_term_end->toDateTimeString();

		} else{
			// check multiple subscriptions flag
			// calculate based on default plan dates

			$this->status 			= 'N'; // new subscription

			$this->remaining_billing_cycles = $this->plan->getBillingCycle();

			if ( empty($this->start_date) ){
				$this->start_date = $this->getCurrentDate()->toDateTimeString() ;
			}

			// populate the subscription dates based on plan settings for a new subscription
			if ( $this->plan->hasTrial() && $this->skip_trial != 1 ) {
				$this->trial_start 		= $this->plan->calculateTrialStartDate( $this->start_date )->toDateTimeString();
				$this->trial_end 		= $this->plan->calculateTrialEndDate( $this->start_date )->toDateTimeString();
			} else {
				$this->trial_start 		= $this->getNullDate();
				$this->trial_end 		= $this->getNullDate();
				$this->plan->setState('skip_trial',1);
			}
			$this->current_term_start 	= $this->plan->calculateStartDate( $this->start_date )->toDateTimeString();
			$this->current_term_end 	= $this->plan->calculateEndDate( $this->start_date )->toDateTimeString();

		}

	}

	//set the subscription and customer information
	function setSubscriptionInformation(){
		// set the billing address info and customer details

	}

	function getSubscriptionInfo(){
		if ($this->axisubs_subscription_id <= 0 ){
			return null;
		}

		$subs_info = $this->getModel('SubscriptionInfos');
		$subs_info->load( array('subscription_id'=>$this->axisubs_subscription_id) );
		if ( $subs_info->axisubs_subscriptioninfo_id  > 0 ){
			return $subs_info;
		}

		return null;
	}

	/**
	 * Method to get the invoice number of the subscription record
	 * */
	function getInvoiceNumber(){
		$invoice_prefix = Axisubs::config()->get('invoice_prefix','');
		$invoice_number = '';
		$invoice_number = $invoice_prefix.''.$this->axisubs_subscription_id ;
		return $invoice_number;
	}

	function calculateSubscriptionTotals(){
		$this->plan_price	= $this->plan->getPrice() ;
		// check if setup fee is applicable
		$subs_model = $this->getModel('Subscriptions');
		$other_subscriptions_count = $subs_model->user_id( $this->user_id )
			->plan_id( $this->plan_id )
			->exclude_this( $this->axisubs_subscription_id )
			->get()
			->filter(function($item)
					{
						if ( $item->axisubs_subscription_id == $this->axisubs_subscription_id ){
							return false;
						}
						//TODO: actually compute number of paid subscriptions
						if ( in_array($item->status, array('N','P'))  ){
							return false;
						}
					    return true ;
					})
			->count();

		$this->setup_fee = 0 ;

		if ($other_subscriptions_count <= 0 || $this->user_id == 0 ){
			if ( !$this->isRecurring() ) {
				$this->setup_fee	=	$this->plan->getSetupCost();
			}elseif ( $this->remaining_billing_cycles == $this->plan->getBillingCycle() ){
				$this->setup_fee	=	$this->plan->getSetupCost();
			}
		}	

		$this->subtotal += $this->plan_price * $this->plan_quantity;
		
		// recurring total does not include setup fee
		$this->recurring_total = $this->subtotal;

		$this->subtotal += $this->setup_fee;
	}

	function calculateDiscountTotals(){
		$app = JFactory::getApplication();
		$session = $app->getSession();
		$couponCode = $session->get('axisubs_coupon_code');
		$total_price = $this->subtotal;
		if($couponCode != ''){
			if($this->plan_id){
				Axisubs::plugin()->event( 'GetDiscountFromCouponCode', array($couponCode, $this->plan_id, $this->axisubs_subscription_id, $total_price));
				$couponCodeVal = $session->get('axisubs_coupon_code_value');
				if($couponCodeVal!=''){
					//$this->subtotal        	= $total_price - $couponCodeVal;
					$this->subtotal_ex_discount 	= $total_price;
					$this->discount 				= $couponCodeVal;
				} else {
					$session->set('axisubs_coupon_code', '');
					//$this->subtotal        	= $total_price;
					$this->subtotal_ex_discount = $total_price;
					$this->discount = 0;
				}
				if($this->discount > 0) {
					$this->calculateTaxDiscounts();
				} else {
					$this->discount_tax = 0;
				}

				if($this->axisubs_subscription_id){
					Axisubs::plugin()->event( 'ApplyCouponCodeInSubscription', array($couponCode, $this->plan_id, $this->axisubs_subscription_id, $this->discount, $this->discount_tax));
				}
			}
		} else {
			$session->set('axisubs_coupon_code', '');
			$this->subtotal_ex_discount = $total_price;
			$this->discount = 0;
			$this->discount_tax = 0;
		}
	}

	function calculateTaxDiscounts(){

		$config = Axisubs::config();
		// get the tax rates
		$tax_rates      = array();
		$store_tax_rates = array();

		$this->tax_class = 'standard';

		$is_including_tax = $config->get('config_including_tax',0);

		$is_tax_enabled = $config->get('enable_tax',0);
		if ( ! $is_tax_enabled ) {
			return ; // do not perform tax calcualtions
		}

		$line_price = $this->discount;
		if ( ! $this->isTaxable() ) {
			// just return an empty amount

			$line_subtotal 		= $line_price;
			$line_subtotal_tax  = 0;

		}elseif ( $is_including_tax ) {
			// include tax

			// Get base tax rates
			if ( empty( $shop_tax_rates[ $this->tax_class ] ) ) {
				$shop_tax_rates[ $this->tax_class ] = Tax::get_base_tax_rates( $this->tax_class );
			}

			// Get item tax rates
			if ( empty( $tax_rates[ $this->tax_class ] ) ) {
				$tax_rates[ $this->tax_class ] = Tax::get_rates( $this->tax_class );
			}

			$base_tax_rates = $shop_tax_rates[ $this->tax_class ];
			$item_tax_rates = $tax_rates[ $this->tax_class ];

			/**
			 * ADJUST TAX - Calculations when base tax is not equal to the item tax.
			 *
			 * The woocommerce_adjust_non_base_location_prices filter can stop base taxes being taken off when dealing with out of base locations.
			 * e.g. If a product costs 10 including tax, all users will pay 10 regardless of location and taxes.
			 * This feature is experimental @since 2.4.7 and may change in the future. Use at your risk.
			 */
			if ( $item_tax_rates !== $base_tax_rates ) {

				// Work out a new base price without the shop's base tax
				$taxes                 = Tax::calc_tax( $line_price, $base_tax_rates, true, true );

				// Now we have a new item price (excluding TAX)
				$line_subtotal         = $line_price - array_sum( $taxes );

				// Now add modified taxes
				$tax_result            = Tax::calc_tax( $line_subtotal, $item_tax_rates );
				$line_subtotal_tax     = array_sum( $tax_result );

				/**
				 * Regular tax calculation (customer inside base and the tax class is unmodified.
				 */
			} else {

				// Calc tax normally
				$taxes                 = Tax::calc_tax( $line_price , $item_tax_rates, true );
				$line_subtotal_tax     = array_sum( $taxes );
				$line_subtotal         = $line_price - array_sum( $taxes );
			}
		}else{
			// exluding tax
			if ( ! empty( $this->tax_class ) ) {

			}
			$tax_rates[ $this->tax_class ]  = Tax::get_rates( $this->tax_class );
			$item_tax_rates        = $tax_rates[ $this->tax_class ];


			// Base tax for line before discount - we will store this in the order data
			$taxes                 = Tax::calc_tax( $line_price, $item_tax_rates );
			$line_subtotal_tax     = array_sum( $taxes );
			$line_subtotal         = $line_price;
			$this->subtotal        	= $this->subtotal + $line_subtotal_tax;
		}
		// Add to main subtotal

		$this->subtotal_ex_discount_tax 	= $this->subtotal;
		$this->discount_tax 				= $line_subtotal_tax;
	}

	function calculateFeeTotals(){

	}

	function calculateTaxTotals(){

		$config = Axisubs::config();

		/*
		// get the store address
		$address = new Address();
		$store_address = $address
		    ->withCountryCode($config->get('country_id','US') )
		    ->withAdministrativeArea($config->get('zone_id','US-CA') )
		    ->withLocality( $config->get('store_city','') )
		    ->withAddressLine1($config->get('store_address_1','') )
		    ->withPostalCode($config->get('store_zip','') );

		// get the customer address
		$address = new Address();
		$customer_address = $address
		    ->withCountryCode( $this->customer->country )
		    ->withAdministrativeArea( $this->customer->state )
		    ->withLocality( $this->customer->city )
		    ->withAddressLine1( $this->customer->address1 )
		    ->withPostalCode( $this->customer->zip );

		// get the context

		$context = new Context($customer_address, $store_address);

		$taxable = new Taxable();
		// get the resolver
		$taxTypeRepository = new TaxTypeRepository();
		$chainTaxTypeResolver = new ChainTaxTypeResolver();
		$chainTaxTypeResolver->addResolver(new CanadaTaxTypeResolver($taxTypeRepository));
		$chainTaxTypeResolver->addResolver(new EuTaxTypeResolver($taxTypeRepository));
		$chainTaxTypeResolver->addResolver(new DefaultTaxTypeResolver($taxTypeRepository));
		$chainTaxRateResolver = new ChainTaxRateResolver();
		$chainTaxRateResolver->addResolver(new DefaultTaxRateResolver());
		$resolver = new TaxResolver($chainTaxTypeResolver, $chainTaxRateResolver);

        // get the rates and amounts

		$types = $resolver->resolveTypes($taxable, $context);

		$rates = $resolver->resolveRates($taxable, $context);

		$amounts = $resolver->resolveAmounts($taxable, $context);
		// compute the array

		// calculate the tax totals
*/

		// check if tax calculation needed
		$is_tax_enabled = $config->get('enable_tax',0);
		if ( ! $is_tax_enabled ) {
			return ; // do not perform tax calcualtions 
		}

		// For checking tax is applicable or not
		$enableTax = 1;
		Axisubs::plugin()->event( 'CheckTaxIsApplicable', array(&$enableTax, $this));
		if(!$enableTax){
			$this->tax = 0;
			$params = $this->tax_details = array();
			if(isset($params['tax_details'])){
				$params['tax_details'] = array();
				$this->params = $params;
			}
			return ;
		}

		// get the tax rates
		$tax_rates      = array();
		$store_tax_rates = array();

		$this->tax_class = 'standard';

		$is_including_tax = $config->get('config_including_tax',0);

		$line_price = $this->subtotal;
		if ( ! $this->isTaxable() ) {
			// just return an empty amount
		
			$line_subtotal 		= $line_price;
			$line_subtotal_tax  = 0;

		}elseif ( $is_including_tax ) {
			// include tax
			
			// Get base tax rates
			if ( empty( $shop_tax_rates[ $this->tax_class ] ) ) {
				$shop_tax_rates[ $this->tax_class ] = Tax::get_base_tax_rates( $this->tax_class );
			}

			// Get item tax rates
			if ( empty( $tax_rates[ $this->tax_class ] ) ) {
				$tax_rates[ $this->tax_class ] = Tax::get_rates( $this->tax_class );
			}

			$base_tax_rates = $shop_tax_rates[ $this->tax_class ];
			$item_tax_rates = $tax_rates[ $this->tax_class ];

			/**
			 * ADJUST TAX - Calculations when base tax is not equal to the item tax.
			 *
				 * The woocommerce_adjust_non_base_location_prices filter can stop base taxes being taken off when dealing with out of base locations.
				 * e.g. If a product costs 10 including tax, all users will pay 10 regardless of location and taxes.
				 * This feature is experimental @since 2.4.7 and may change in the future. Use at your risk.
				 */
			if ( $item_tax_rates !== $base_tax_rates ) {

				// Work out a new base price without the shop's base tax
				$taxes                 = Tax::calc_tax( $line_price, $base_tax_rates, true, true );

				// Now we have a new item price (excluding TAX)
				$line_subtotal         = $line_price - array_sum( $taxes );

				// Now add modified taxes
				$tax_result            = Tax::calc_tax( $line_subtotal, $item_tax_rates );

				//To display each tax price in front end
				foreach($tax_result as $k => $tax_rates){
					$item_tax_rates[$k]['price'] = $tax_rates;
				}
				$this->tax_details = $item_tax_rates;

				$line_subtotal_tax     = array_sum( $tax_result );
				$line_subtotal         = $line_price - $line_subtotal_tax;

			/**
			 * Regular tax calculation (customer inside base and the tax class is unmodified.
			 */
			} else {

				// Calc tax normally
				$taxes                 = Tax::calc_tax( $line_price , $item_tax_rates, true );

				//To display each tax price in front end
				foreach($taxes as $k => $tax_rates){
					$item_tax_rates[$k]['price'] = $tax_rates;
				}
				$this->tax_details = $item_tax_rates;

				$line_subtotal_tax     = array_sum( $taxes );
				$line_subtotal         = $line_price - array_sum( $taxes );
			}

		}else{
			// exluding tax
			if ( ! empty( $this->tax_class ) ) {
				
			}
			$tax_rates[ $this->tax_class ]  = Tax::get_rates( $this->tax_class );
			$item_tax_rates        = $tax_rates[ $this->tax_class ];

			// Base tax for line before discount - we will store this in the order data
			$taxes                 = Tax::calc_tax( $line_price, $item_tax_rates );

			//To display each tax price in front end
			foreach($taxes as $k => $tax_rates){
				$item_tax_rates[$k]['price'] = $tax_rates;
			}
			$this->tax_details = $item_tax_rates;

			$line_subtotal_tax     = array_sum( $taxes );
			$line_subtotal         = $line_price;
		}

		// Add to main subtotal
		$this->subtotal        	= $line_subtotal + $line_subtotal_tax;
		$this->subtotal_ex_tax 	= $line_subtotal;
		$this->tax 				= $line_subtotal_tax;
		$params = '';
		$params['tax_details'] = $this->tax_details;
		$this->params = json_encode($params);

	}

	/**
	 * Method to check if the subscription is taxable
	 * */
	function isTaxable() {
		return true;
	}

	/**
	 * If the current date is outside the publish_up / publish_down range then disable the subscription. Otherwise make
	 * sure it's enabled if state = C or disabled in any other case.
	 *
	 * @return  void
	 */
	protected function normaliseEnabled()
	{
		$format="Y-m-d H:i:s";

		$now  = Carbon::now();

		$start = Carbon::createFromFormat($format, $this->current_term_start); 
		$end   = Carbon::createFromFormat($format, $this->current_term_end);

		if ($now->lt($start))
		{
			//$this->enabled = 0;
		}
		elseif ( (($now->gt($start))) && (($now->lt($end))) ) //($uNow >= $jUp->toUnix()) && ($uNow < $jDown->toUnix()))
		{
			//$this->enabled = ($this->status == 'A') ? 1 : 0;
		}
		else
		{
			//$this->enabled = 0;
		}
	}

	/**
	 * Map the 'custom' data key to params
	 *
	 * @param   array|mixed $data
	 */
	protected function onBeforeBind(&$data)
	{
		if (!is_array($data))
		{
			return;
		}

		if (array_key_exists('custom', $data))
		{
			$params = json_encode($data['custom']);
			unset($data['custom']);
			$data['params'] = $params;
		}
	}

	protected function getParamsAttribute($value)
	{
		return $this->getAttributeForJson($value);
	}

	protected function setParamsAttribute($value)
	{
		return $this->setAttributeForJson($value);
	}

	/**
	 * Method to bind the subscription with another plan id
	 * */
	function resetSubscriptionPlan( $plan_id )
	{
		// first check if the status is new 
		if ( $this->status == 'N'){
			$this->reset_flag = true ; 
		}

		//TODO: additionally check if the is no transaction records created / Payment tried for this subscription

		$this->plan_id = $plan_id ;

		if ( ! isset($this->plan) && ($this->plan instanceof \Flycart\Axisubs\Admin\Model\Plans || $this->plan instanceof \Flycart\Axisubs\Site\Model\Plans) ){
			// maybe try to load it ourself
			$plan = $this->getContainer()->factory->model('Plans');
			if ( !empty($this->plan_id) ){
				$plan->load($this->plan_id);
				if ( empty($plan->axisubs_plan_id) ){
					$this->throwError( 'COM_AXISUBS_SUBSCRIPTION_ERR_CANNOT_BIND_PLAN_RELATION' );
					// log this error it's an application level error
				}else {
					$this->plan = $plan; // every thing is fine bind the relation
				}				
			}
		}

		$this->start_date = $this->getCurrentDate()->toDateTimeString();
		
		$this->calculateTermDates();

		$this->calculateTotals(); // this will calculate price, gross, discount, tax and new amount

		$this->store( ); //  this will reset the plan id and dates and all other subs values

	}

	/**
	 * Intialize transaction record if not already created
	 * */
	function initTransaction(){
		if ( isset($this->transaction) && isset($this->transaction->subscription_id) ){
			return true;
		} else {
			// create a transaction record here 
			$this->saveTransaction();
		}
	}

	/**
	 * get the transaction object 
	 * */
	function getTransaction(){
		if ( isset($this->transaction) ){
			return $this->transaction;	
		}else{
			$transaction_model = $this->getModel('Transactions');
			$transactions = $transaction_model->user_id( $this->user_id )
							->subscription_id( $this->axisubs_subscription_id )
							->get();
			if ( count($transactions) > 0 ){
				$transaction = $transactions->first();
				return $transaction;
			} else {
				return false;	
			}			
		}
		return false;	
	}

	/**
	 * Create or update a transaction record
	 * */
	function saveTransaction( $transaction_data = array() ){
		$transaction = $this->getTransaction();

		if ( $transaction ){
			if ( count($transaction_data) > 0 ){
				$transaction->save( $transaction_data );	
			}			
		} else {
			if ( count($transaction_data) == 0 ){
				$transaction_data['user_id'] = $this->user_id;
				$transaction_data['subscription_id'] = $this->axisubs_subscription_id;
			}

			if ( isset($transaction_data['transaction_status']) && empty($transaction_data['transaction_status']) ){
				$transaction_data['transaction_status'] = 'intiated';	
			}			

			$transaction_model = $this->getModel('Transactions');
			$transaction = $transaction_model->save( $transaction_data );
		}

		return $transaction;
	}

	/**
	 * isEligibleForSubscription()
	 * isEligibleForPayment()
	 * paymentCompleted()
	 * markActive()
	 * markPending()
	 * markCancelled()
	 * markDeleted()
	 * 
	 * hasTrail()
	 * startTrial()
	 * extendTrial()
	 * skipTrial()
	 * 
	 * isTrial()
	 * isActive()
	 * isExpired()
	 * 
	 * isRecurring()
	 * getRemaingBillingCycles()
	 * getRenewals()
	 * 
	 * // console jobs / system jobs only
	 * selfCheckStatus()
	 * sendReminderEmails()
	 * raiseInvoice()
	 * collectPayment()
	 * charge() // alias for collectPayment - uses Payment factory
	 * 
	 * markTrialEnd()
	 * markExpired()
	 * */


	function createNewSubscription( &$data ){
		// if user id is missing create a new user with the name and email id provided
		$user_id = $this->createNewCustomer( $data );
	}

	/**
	 * Method to check if the subscription is isEligibleForPayment
	 * @param  int 		$user_id 	user_id
	 * @return bool  		 		true if the user has enough access
	 * */
	function isEligibleForSubscription( $user_id ){
		// check if customer and plan is binded
		if ( !( isset($this->plan) ) ){
			return false;
		}

		// plan access 
		if (! $this->plan->canAccess( $user_id ) ){
			return false;
		}

		//if current plan is in trial then renewal is not allowed
		if ($this->status == 'T'){
			return false;	
		}

		// non recurring - only once plan - customer already has an active subscription 
		// then not allowed
		$subs_model = $this->getModel('Subscriptions');

		if ( $this->user_id && $this->plan->only_once == 1 ){
			// check if there is any active subscription
			$active_subscription_count = $subs_model->user_id( $this->user_id )
						->plan_id( $this->plan_id)
						->status('A') // active
						->get()
						->count();
			if ( $active_subscription_count > 0 ) {
				return false;
			}
		}

		// if the plan aready has an trial subscription 
	 	$trial_subscription_count = $subs_model->user_id( $this->user_id )
						->plan_id( $this->plan_id)
						->status('T') 
						->get()
						->count();
		if ( $trial_subscription_count > 0 && $user_id != 0 ){
			return false;
		}

		return true;
	}

	/**
	 * alias for isEligibleForSubscription Method to check if the renewal is allowed
	 * @param  int 		$user_id 	user_id
	 * @return bool  		 		true if the user has enough access
	 * */
	function isRenewalAllowed( $user_id ){
		$access = $this->isEligibleForSubscription( $user_id ) ;
		return $access ;
	}

	/**
	 * Method to check if the subscription is isEligibleForPayment
	 * */
	function isEligibleForPayment(){
		// before collecting Payment we check for certain parameters
		// if subscription / plan parameters has any Payment related restrictions
		return true;
	}

	/**
	 * Mark the Payment as completed
	 * */
	function paymentCompleted( $transaction_data = array() ){
		// save the transaction details
		$transaction = $this->saveTransaction( $transaction_data );
		// TODO: extra check if it is in renewal state ?
		
		$old_subscription_status = $this->status;

		// If there is an active subscription 
		$subs_model = clone($this);
		$active_subscription_count = $subs_model->user_id( $this->user_id )
						->plan_id( $this->plan_id)
						->status('A') // active
						->exclude_this( $this->axisubs_subscription_id )
						->get()
						->count();

		if ($active_subscription_count > 0){
			// a future subscription 
			// regenerate the dates and Payment info and save the record
			$this->calculateTermDates();
			
			// assign the correct start date for a future subscription
			$this->start_date = $this->getCurrentDate();

			$this->markFuture();
			Axisubs::plugin()->event( 'SubscriptionRenewalPaid', array($this, $old_subscription_status) );
		}elseif ( $this->hasTrial() ) {
			// mark subscription as In Trail
			$this->calculateTermDates();
			$this->startTrial();
			Axisubs::plugin()->event( 'SubscriptionTrialPaid', array($this, $old_subscription_status) );
		}else {
			// mark subscription as Active
			$this->calculateTermDates();
			$this->markActive();
			Axisubs::plugin()->event( 'SubscriptionActivePaid', array($this, $old_subscription_status) );
		}

		if ($transaction){
			$transaction->updateState('paid');	
		}

		Axisubs::plugin()->event( 'SubscriptionPaymentSuccess', array($this, $old_subscription_status) );

	}

	/**
	 * Mark the Payment as Failed
	 * */
	function paymentFailed( $transaction_data = array() ){
		// save the transaction details
		$transaction = $this->saveTransaction( $transaction_data );
		if ($transaction){
			$transaction->updateState('failed');	
		}		
		
		$old_subscription_status = $this->status;

		// check if the status is pending else mark as pending
		// TODO: check with a flag and mark as pending
		if ($this->status != 'P'){
			$this->markPending();
		}
		Axisubs::plugin()->event( 'SubscriptionPaymentFailed', array($this, $old_subscription_status) );
	}

	/**
	 * Mark the Payment as Failed
	 * */
	function paymentPending( $transaction_data = array() ){
		$transaction = $this->saveTransaction( $transaction_data );
		if ($transaction){
			$transaction->updateState('pending');	
		}
		$old_subscription_status = $this->status;

		// check if the status is pending else mark as pending
		// TODO: check with a flag and mark as pending
		if ($this->status != 'P'){
			$this->markPending();
		}
		Axisubs::plugin()->event( 'SubscriptionPaymentPending', array($this, $old_subscription_status) );
	}

	function markActive(){
		$old_subscription_status = $this->status;
		// verify the Payment records related to this subscription and update status to active
		$this->updateState('A');
		Axisubs::plugin()->event( 'SubscriptionMarkedActive', array($this, $old_subscription_status) );
	}

	function markPending(){
		$old_subscription_status = $this->status;
		$this->updateState('P');
		Axisubs::plugin()->event( 'SubscriptionMarkedPending', array($this, $old_subscription_status) );
	}

	function markCancelled(){
		$old_subscription_status = $this->status;
		$this->updateState('C');	
		Axisubs::plugin()->event( 'SubscriptionCancelled', array($this, $old_subscription_status) );
	}

	function markDeleted(){
		$old_subscription_status = $this->status;
		//$this->updateState('D');
		// this should delte the subscription or have a delete flag marked yes (soft delete)
		$this->delete();
		Axisubs::plugin()->event( 'SubscriptionDeleted', array($this, $old_subscription_status) );
	}

	function markFuture(){
		$old_subscription_status = $this->status;
		$this->updateState('F');
		Axisubs::plugin()->event( 'SubscriptionMarkedRenewal', array($this, $old_subscription_status) );
	}

	function markExpired(){
		$old_subscription_status = $this->status;
		$this->updateState('E');
		Axisubs::plugin()->event( 'SubscriptionExpired', array($this, $old_subscription_status) );
	}

	/**
	 * Method to check if the subsctiption is entitled to trial period or not
	 * Trila will not be applicable if Plan does not have trial period and if subscription is a renewal
	 * @return bool true if trial is applicable
	 * */
	function hasTrial(){
		if ( $this->plan->hasTrial() && isset($this->trial_start) && !empty($this->trial_start) ){
			if ( $this->trial_start != $this->getNullDate() ) {
				return true;
			}
		}
		return false;
	}

	function startTrial(){
		$old_subscription_status = $this->status;
		// check if plan has trial
		$this->updateState('T');
		// check if plan requires card authourize and customer card is authourized
		Axisubs::plugin()->event( 'SubscriptionTrialStarted', array($this, $old_subscription_status ) );
	}

	function endTrial(){
		$old_subscription_status = $this->status;
		// either move to pending or to active
		// check if plan requires card authourize and customer card is authourized
		$this->updateState('P');

		Axisubs::plugin()->event( 'SubscriptionTrialEnded', array($this , $old_subscription_status ) );
	}

	function extendTrial($no_of_days){
		// extend the trial upto certain number of days or upto a certain date
	}

	function skipTrial(){
		// just mark trial end and mark active or pending based on Payment records
		// if card is authourized then this will bill the customer
	}

	function isActive(){
		// if the plan is in active status
	}

	function isExpired(){
		// if the plan is in expired status
	}

	/**
	 * Method to check if the subscription is recurring
	 * @return bool true if it is a recurring subscription
	 * */
	function isRecurring(){
		if ( $this->remaining_billing_cycles >= 1 ) {
			return true;
		}
		return false;
	}

	function getRenewals(){
		// if the plan is in trial status
	}

	/**
	 * Method to check the status of the subscription and mark itself to trial end / active / expiry
	 * */
	function selfCheckStatus(){

		/**
		 * Process if future subscriptions started
		 * Process if expired subscriptions in confirmed state
		 * Process if trial ended subscriptions in trial state
		 * */

		$current_date = $this->getCurrentDate();

		// future subscriptions started
		if ( $this->status == 'F' ) {
			$current_term_start = $this->getDate( $this->current_term_start );
			if ( $current_term_start->lte($current_date) ) {
				// then move the subscription to active status
				$this->markActive();
			}
		}

		// expired subscriptions in Active state
		if ( $this->status == 'A' ) {
			
			// create the renewal subscription record in pending status
			$this->createRenewalSubscription();

			$term_end_date = $this->getDate( $this->current_term_end );
			if ( $term_end_date->lte( $current_date ) ) {
				$this->markExpired();
			}		
			
		}

		// trial ended
		if ( $this->status == 'T' ) {
			$trial_end_date = $this->getDate( $this->trial_end );
			if ( $trial_end_date->lte( $current_date ) ) {
				$this->markActive(); // move to active state
			}
		}
	}

	/**
	 * Creates or gets the next renewal record for the current subscription supplied
	 * @param 	int 	$subscription 	subscription record or a subscription id
	 * @return 	bool/subscription object	
	 * */
	public function getNextRenewal( $subscription , $transaction_ref_id = '' ){
		
		// check for the subscription records with exiting transaction id 
		if (!empty($transaction_ref_id)) {
			$transaction_model = $this->getModel('Transactions');
			$matched_transaction = $transaction_model->transaction_ref_id($transaction_ref_id)
								->get()
								->first();
		
			if (isset( $matched_transaction->axisubs_transaction_id ) ) {
				$existing_sub = $this->getModel('Subscriptions');
				$existing_sub->load( $matched_transaction->subscription_id );
				return $existing_sub;
			}	
		
		}
		
		$current_sub = '';

		if ( $subscription instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions) {
			$current_sub = $subscription ;
		}else{
			$subscription_id = (int) $subscription ;
			if ($subscription_id > 0) {
				$sub = clone ($this);
				$sub->load( $subscription_id ) ;
				if ( $sub->axisubs_subscription_id > 0 ) {
					$current_sub = $sub;
				}
			}
		}

		// validate if a valid subscription record is available
		if ( !($current_sub instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $current_sub instanceof \Flycart\Axisubs\Site\Model\Subscriptions) ) {
			return false;
		}
		
		if ( empty( $current_sub->ref_subscription_id ) || $current_sub->ref_subscription_id > 0 ) {
			if (empty( $current_sub->transaction->transaction_ref_id )) {
				return $current_sub;
			}
		}

		// first check if there is a pending subscription in this state alredy exists
		$subs_model = $this->getModel('Subscriptions');
		$latest_pending_sub = $subs_model->user_id( $current_sub->user_id )
								->plan_id( $current_sub->plan_id )
								->ref_subscription_id( $current_sub->axisubs_subscription_id )
								->statuses( [ 'P','F'] ) // any subscription in pending or in future state
								->get()
								->sortByDesc( 'current_term_end' )
								->first();

		if ($latest_pending_sub instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $latest_pending_sub instanceof \Flycart\Axisubs\Site\Model\Subscriptions)
		{
			if ( !isset($latest_pending_sub->transaction) || count($latest_pending_sub->transaction) == 0 ){
				// no transaction record or same transaction reference, then this is the subscription to be processed
				return $latest_pending_sub ;
			}
		}
		
		$new_subscription = $current_sub->createRenewalSubscription();

		return $new_subscription;
	}

	/**
	 * Method to actually create a renewal subscription record
	 * */
	function createRenewalSubscription(){
		//
		if ( $this->isRecurring() ) {
			$subs_data = $this->getData();
			$new_subscription_data = [  'plan_id'				=>	$subs_data['plan_id'],
										'user_id'				=>	$subs_data['user_id'],
										'ref_subscription_id' 	=>  $subs_data['axisubs_subscription_id'],
										'plan_quantity'			=>	$subs_data['plan_quantity'] ,
										'currency_code' 		=> 	$subs_data['currency_code'],
										'currency_value' 		=> 	$subs_data['currency_value'],
										'language'				=>  $subs_data['language'] 
										];

			//$subs_model = $this->getModel('Subscriptions');
			//$new_subscription = $subs_model->tmpInstance();
			$new_subscription = clone ($this);

			$new_subscription->axisubs_subscription_id = 0;
			$new_subscription->save( $new_subscription_data );

			$new_subscription->currency_code	=	$subs_data['currency_code'];
			$new_subscription->currency_value	=	$subs_data['currency_value'];
			$new_subscription->language 		=	$subs_data['language'];
			$new_subscription->save();
			$new_subscription->markPending();

			return $new_subscription;
		}
	}

	/**
	 * Method to trigger reminder emails 
	 * // mybe not needed in this place
	 * */
	function sendReminderEmails(){

	}
	
	/**
	 * Method to create / generate invoice for this subscription
	 * */
	function raiseInvoice(){

	}
	
	/**
	 * Method to collect Payment for the subscription
	 * */
	function collectPayment(){

	}

	/**
	 * Alias for collectPayment
	 * */
	function charge(){
		$this->collectPayment();
	}

	/**
	 * Method to update the status of the subscription
	 * This cannot be accessed from the frontend
	 * */
	private function updateState( $new_state_flag ) {
		if (empty ( $this->axisubs_subscription_id ))
			return;
		$app = JFactory::getApplication();
		$old_status = $this->status;
		// update only when the status is new
		if ($new_state_flag !== $old_status) {

			//trigger event before update
			Axisubs::plugin()->event( 'BeforeSubscriptionStatusUpdate', array($this, $new_state_flag) );

			// first update the order
			$this->status = $new_state_flag;
			
			$this->store();

			$this->touch();

			//trigger event after update
			Axisubs::plugin()->event('AfterSubscriptionStatusUpdate', array($this, $new_state_flag));
		}

		$force_notify_customer = false; //
		if($force_notify_customer) {
			 $this->notify_customer();
		}
	}

	function notify_customer(){}

	/**
	 * FOF3 work arounds due to poor handling of exceptions
	 * return an exception message
	 * */
	public function store($updateNulls = false)
	{
		try
		{
			$this->save();
		}
		catch (\Exception $e)
		{
			// set the model error here or throw the exception or remove the exception block itself
			return false;
		}

		return true;
	}

	/**
	 * Method to format the shortcodes with correct price and 
	 * */
	public function getFormatedShortCodes(){
		$status_helper = Axisubs::status();
		$date_helper = Axisubs::date();
		$curr_helper = Axisubs::currency();
		$shortcode_helper = Axisubs::shortcodes();

		$subscription_shortcodes = array();
		$formatted_shortcodes = array();
		$subscription_shortcodes = $shortcode_helper->getShortCodes('subscription');

		foreach ( $subscription_shortcodes as $k => $key ) {
			$key = strtolower($key);
			$formatted_shortcodes[ $key ] = $this->$key ;
		}

		$formatted_shortcodes[ 'status' ] 		= $status_helper->get_text( $formatted_shortcodes[ 'status' ] );
		if($formatted_shortcodes[ 'trial_start' ] != '0000-00-00 00:00:00')
			$formatted_shortcodes[ 'trial_start' ] 	= $date_helper->get_formatted_date ( $formatted_shortcodes[ 'trial_start' ] ) ;
		else
			$formatted_shortcodes[ 'trial_start' ] 	= '-';
		if($formatted_shortcodes[ 'trial_end' ] != '0000-00-00 00:00:00')
			$formatted_shortcodes[ 'trial_end' ] 	= $date_helper->get_formatted_date ( $formatted_shortcodes[ 'trial_end' ] ) ;
		else
			$formatted_shortcodes[ 'trial_end' ] 	= '-';
		if($formatted_shortcodes[ 'current_term_start' ] != '0000-00-00 00:00:00')
			$formatted_shortcodes[ 'current_term_start' ] = $date_helper->get_formatted_date ( $formatted_shortcodes[ 'current_term_start' ] ) ;
		else
			$formatted_shortcodes[ 'current_term_start' ] 	= '-';
		if($formatted_shortcodes[ 'current_term_end' ] != '0000-00-00 00:00:00')
			$formatted_shortcodes[ 'current_term_end' ] = $date_helper->get_formatted_date ( $formatted_shortcodes[ 'current_term_end' ] ) ;
		else
			$formatted_shortcodes[ 'current_term_end' ] 	= '-';
		if($formatted_shortcodes[ 'created_on' ] != '0000-00-00 00:00:00')
			$formatted_shortcodes[ 'created_on' ] 	= $date_helper->get_formatted_date ( $formatted_shortcodes[ 'created_on' ] ) ;
		else
			$formatted_shortcodes[ 'created_on' ] 	= '-';
		$formatted_shortcodes[ 'total' ] 		= $curr_helper->format( $this->total, $this->currency_code);
		$formatted_shortcodes[ 'subtotal' ] 		= $curr_helper->format( $this->subtotal, $this->currency_code);
		$formatted_shortcodes[ 'plan_price' ] 	= $curr_helper->format( $this->plan_price, $this->currency_code);
		$formatted_shortcodes[ 'setup_fee' ] 	= $curr_helper->format( $this->setup_fee, $this->currency_code);
		$formatted_shortcodes[ 'subtotal_ex_tax' ] = $curr_helper->format( $this->subtotal_ex_tax, $this->currency_code);
		$formatted_shortcodes[ 'tax' ] 			= $curr_helper->format( $this->tax, $this->currency_code);
		$formatted_shortcodes[ 'discount' ] 			= $curr_helper->format( $this->discount, $this->currency_code);
		$formatted_shortcodes[ 'discount_tax' ] 			= $curr_helper->format( $this->discount_tax, $this->currency_code);


		$formatted_shortcodes_obj = (object) $formatted_shortcodes;
		return $formatted_shortcodes_obj;
	}
}
