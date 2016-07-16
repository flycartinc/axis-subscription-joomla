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
use Carbon\Carbon;
use FOF30\Model\DataModel;
use JLoader;

/**
 * Model class for Axisubs Plan data
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
 * @method  $this  axisubs_plan_id()  	 axisubs_plan_id(int $v)
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
class Plans extends DataModel
{
	use Mixin\JsonData, Mixin\CarbonHelper;

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->addBehaviour('RelationFilters');
		
		$this->aliasFields = ['title'=>'name'];

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [ 'image', 'taxprofile_id', 'ordertext','orderurl',
									'canceltext','cancelurl','only_once','recurring',
									'forever','access', 'fixed_date','payment_plugins',
									'renew_url', 'content_url','params'];
		$this->blacklistFilters(['only_once']);
	}

	protected function preProcessSave($dataObject){
		$app = \JFactory::getApplication();
		$post = $app->input->post->getArray();
		if(!empty($post)){
			if($app->input->get("payment_plugins", '0') != '0')
				$dataObject->payment_plugins = implode(',', $app->input->get("payment_plugins"));
			else
				$dataObject->payment_plugins = '';
		}
		return $dataObject->payment_plugins;
	}
	function onBeforeCreate(&$dataObject){
		$dataObject->payment_plugins = $this->preProcessSave($dataObject);
	}

	function onBeforeUpdate(&$dataObject){
		$dataObject->payment_plugins = $this->preProcessSave($dataObject);

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
		$user      = \JFactory::getUser();

		$query->where('enabled IN (1,0)');

		$access_user_id = $this->getState('access_user_id', null);

		if (!is_null($access_user_id))
		{
			$levels = \JFactory::getUser($access_user_id)->getAuthorisedViewLevels();

			if (!empty($levels))
			{
				$levels = array_map(array($this->getDbo(), 'quote'), $levels);

				$query->where($db->qn('access') . ' IN (' . implode(',', $levels) . ')');
			}
		}

		$subIDs = array();

		$only_once = $this->getState('only_once', null);

		if ($only_once && $user->id)
		{
			/** @var Subscriptions $subscriptionsModel */
			$subscriptionsModel = $this->container->factory
				->model('Subscriptions')->tmpInstance();

			$mySubscriptions = $subscriptionsModel
				->user_id($user->id)
				->paystate('A')
				->get(true);

			if ($mySubscriptions->count())
			{
				foreach ($mySubscriptions as $sub)
				{
					$subIDs[] = $sub->axisubs_plan_id;
				}
			}

			$subIDs = array_unique($subIDs);

		}

		$subIDs = array_filter($subIDs);

		if ($only_once && $user->id)
		{
			if (count($subIDs) )
			{
				$query->where(
					'(' .
						'(' . $db->qn('only_once') . ' = ' . $db->q(0) . ')' .
						' OR ' .
						'(' .
							'(' . $db->qn('only_once') . ' = ' . $db->q(1) . ')'
							. ' AND ' .
							'(' . $db->qn('axisubs_plan_id') . ' NOT IN ' . '(' . implode(',', $subIDs) . ')' . ')'
						. ')' .
					')'
				);
			}
		}

		$search = $this->getState('search', null);

		if ($search)
		{
			$search = '%' . $search . '%';
			$query->where($db->qn('description') . ' LIKE ' . $db->q($search));
		}

		// Filter by IDs
		$ids = $this->getState('id', null);

		if (is_array($ids))
		{
			$temp = '';

			foreach ($ids as $id)
			{
				$id = (int) $id;

				if ($id > 0)
				{
					$temp .= $id . ',';
				}
			}

			if (empty($temp))
			{
				$temp = ' ';
			}

			$ids = substr($temp, 0, - 1);
		}
		elseif (is_string($ids) && (strpos($ids, ',') !== false))
		{
			$ids  = explode(',', $ids);
			$temp = '';

			foreach ($ids as $id)
			{
				$id = (int) $id;

				if ($id > 0)
				{
					$temp .= $id . ',';
				}
			}

			if (empty($temp))
			{
				$temp = ' ';
			}

			$ids = substr($temp, 0, - 1);
		}
		elseif (is_numeric($ids) || is_string($ids))
		{
			$ids = (int) $ids;
		}
		else
		{
			$ids = '';
		}

		if ($ids)
		{
			$query->where($db->qn('axisubs_plan_id') . ' IN (' . $ids . ')');
		}

		$levelgroup = $this->getState('levelgroup', null, 'int');

		if (is_numeric($levelgroup))
		{
			$query->where($db->qn('axisubs_levelgroup_id') . ' = ' . (int) $levelgroup);
		}

		$order = $this->getState('filter_order', 'axisubs_plan_id', 'cmd');

		if (!in_array($order, array_keys($this->getData())))
		{
			$order = 'axisubs_plan_id';
		}

		$dir = $this->getState('filter_order_Dir', 'DESC', 'cmd');
		$query->order($order . ' ' . $dir);

		$search = $this->getState('search', null, 'string');

		if ($search)
		{
			$search = '%' . $search . '%';
			$query->where(
				'(' .
				'(' . $db->qn('name') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('slug') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('description') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('price') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('setup_cost') .
				' LIKE ' . $db->q($search) . ')'
				. ')'
			);
		}
	}

	/**
	 * Map the 'custom' data key to params
	 *
	 * @param   array|mixed $data
	 */
	protected function onBeforeBind(&$data)
	{
		$app = \JFactory::getApplication();
		$input_data = $app->input->getArray($_POST);

		if (!is_array($data))
		{
			return;
		}

		if (array_key_exists('params', $input_data))
		{
			$params = json_encode($data['params']);
			$data['params'] =  $params ;
		}

		if (array_key_exists('custom', $data))
		{
			$params = json_encode($data['custom']);
			unset($data['custom']);
			$data['params'] = $params;
		}
		//print_r($app->input->get('payment_plugins'));exit;
		if(isset($data['payment_plugins']) && $data['payment_plugins'] != ''){
			$data['payment_plugins'] = explode(",", $data['payment_plugins']);
		}

		//echo "<pre>";print_r($data);exit;
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
	 * List of methods to be defined
	 * For any new subscription to be created, all the parameters depend upon the plan's settings and behaviour
	 * hasTrial()
	 * isActive()
	 * isRecurring()
	 * getPeriod()
	 * getTrialPeriod()
	 * hasFixedEndDate()
	 * getFixedEndDate()
	 * calculateStartDate()
	 * calculateEndDate()
	 * calculateTrialStartDate()
	 * calculateTrialEndDate()
	 * canAccess($user_id) // if the user had enough previlages to access or to subscribe to the plan
	 * isEligible($user_id) // alias for canAccess()
	 * canSubscribe($user_id) // alias for canAccess()
	 * getPrice()
	 * getSetupCost()
	 * 
	 * 
	 * */

	function hasTrial(){
		if ( isset($this->trial_period) && $this->trial_period > 0 && $this->getState('skip_trial',0) == 0 ){
			return true;
		} else {
			return false;
		}
	}
	
	function isActive(){
		if ($this->enabled == 1){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Method to check if the plan is a recurring plan
	 * */
	function isRecurring(){
		if ( $this->recurring == 1 ){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Method to get the number of billing cycles
	 * */
	function getBillingCycle(){
		return (int) $this->billing_cycles;
	}


	/**
	 * Calculate the period in days
	 * */
	function getPeriodInDays(){
		if ( isset( $this->period) && !empty( $this->period ) ){
			return $this->period;
		}else {
			return 0;
		}
	}

	/**
	 * Calculate the trial period in days
	 * */
	function getTrialPeriodInDays(){
		if ( isset( $this->trial_period) && !empty( $this->trial_period ) ){
			return $this->trial_period;
		}else {
			return 0;
		}
	}

	function hasFixedEndDate(){
		if ( isset( $this->fixed_end_date) && !empty( $this->fixed_end_date ) ){
			$fixed = $this->getFixedEndDate();
			return true;
			/*if ( $this->isPast($fixed) ){return false;} do we need to check if it is past here ? */
		}
		return false;
	}

	function getFixedEndDate(){
		return $this->getDate($this->fixed_end_date);
	}

	/**
	 * Calculates the end date based on plan period and supplied start date
	 * returns fixed end date if it has one
	 * if start date is not supplied then start date / subscription id not supplied
	 * it is assumed current date is start date
	 * if it has trial period then plan end date is 
	 * @param string $start_date	start date of the subscription
	 * @return Carbon 				end date of the plan
	 * */
	function calculateEndDate( $start_date='' ){

		$start_date = $this->calculateStartDate( $start_date );
		
		// check if plan has a fixed end date and not in past
		if ( $this->hasFixedEndDate() ){
			return $this->getFixedEndDate();
			// later if needed
			if ( $this->isPast() ){
			}else {
				// error it has a fixed date and date is in the past 
				// raise an error or warning based on error handling technique
			}
		}

		// calculate the end date and return
		$date = $start_date->copy();

		$end_date = $date->addDays( $this->getPeriodInDays() ); // add the length of the plan in days and get the end date

		return  $end_date;
	}

	/**
	 * calculates start date
	 * current date if no trial and calculated start date if trial period exists
	 * */
	function calculateStartDate( $start_date = '' ){
		
		$start_date = $this->calculateTrialEndDate( $start_date );

		return $start_date;
	}

	/**
	 * Calculates Trial Start Date
	 * */
	function calculateTrialStartDate( $trial_start_date = '' ){
		if ( empty($trial_start_date) ){
			$trial_start_date = $this->getCurrentDate();
		}else {
			$trial_start_date = $this->getDate($trial_start_date);
		}

		return $trial_start_date;
	}

	/**
	 * Calculates Trial End Date
	 * */
	function calculateTrialEndDate($trial_start_date = ''){
		$trial_start = $this->calculateTrialStartDate($trial_start_date);
		$trial_end = $trial_start->copy();
		if ($this->hasTrial()){
			$trial_end->addDays(  $this->getTrialPeriodInDays() );
		}
		return $trial_end;
	}

	/**
	 * Checks if the supplied user has access to the plan 
	 * */
	function canAccess($user_id){

		//check the permission of the user based on the user access levels and additional conditions if any
		$accessLevels = \JFactory::getUser($user_id)->getAuthorisedViewLevels();
		if (in_array($this->access, $accessLevels))
		{
			return true;
		}
		return false;
	}

	function isEligible($user_id) {	
		$access = $this->canAccess($user_id);
		return $access;
	}

	function canSubscribe($user_id) {	
		$access = $this->canAccess($user_id);
		return $access;
	}

	/**
	 * Supply the currency code and get the price
	 * @param string $currency_code	 currency code
	 * @return float 				 price
	 * */
	function getPrice( $currency_code ='' ){
		
		if ( empty($currency_code) ){
			$currency_code = Axisubs::currency()->getCode();
		}

		// TODO: get the price based on the currency code supplied
		return $this->price;
	}

	/**
	 * Supply the currency code and get the price
	 * @param string $currency_code	 currency code
	 * @return float 				 price
	 * */
	function getSetupCost(){
		return $this->setup_cost;
	}

}