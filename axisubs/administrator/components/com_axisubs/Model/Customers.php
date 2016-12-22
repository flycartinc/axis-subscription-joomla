<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JLoader;
use JFactory;
use JText;
use JError;

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
class Customers extends DataModel
{
	use Mixin\JsonData;

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->addBehaviour('RelationFilters');

		$this->hasOne('user', 'JoomlaUsers', 'user_id', 'id');
		$this->hasMany('subscriptions', 'Subscriptions', 'user_id', 'user_id');
		$this->with(['user']);

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [ 'first_name', 'last_name', 'email', 'phone', 'address2', 'user_id',
									'vat_number','auto_collection','allow_direct_debit','created_from_ip','params', 'notes'];
	}

	/**
	 * Create a new customer record if the user id does not exists
	 * */
	public function createNewCustomer($data){

			$user_details = array();
			$user_details['name'] = $data['first_name'] .' '.$data['last_name'] ;
			$user_details['email'] = $data['email'] ;

			$password = ''; // random generate a password
			$user_details['password'] = $data['password'] ;
			$user_helper = Axisubs::user();
			$user = $user_helper->createNewUser($user_details);
			return $user->id;
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

		$app = JFactory::getApplication();

		$errors = $this->validate($data);
		foreach ($errors as $k => $error) {
			JError::raiseWarning('Registration fail', $error);
		}
		if (count($errors) > 0 ) {
			throw new \RuntimeException('Cannot save Customer data');
		}

		if ( ( isset($data['new_or_existing_customer']) && $data['new_or_existing_customer'] == 'new') 
				|| ( $data['user_id'] <= 0 ) ) {
			$data['user_id'] = $this->createNewCustomer($data);
		}

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

		if (in_array(false, $jResponse))
		{
			throw new \RuntimeException('Cannot save user data');
		}
	}

	public function validate($data){
		//  validation
		$error = [];
		$user = JFactory::getUser();
		// check reqiured fields first
		$required = array('address1','city','zip','country');
		$reg_required = array('first_name','last_name','email');
		if ($data['user_id'] <= 0) {
			$required = array_merge($required, $reg_required);
		}
		foreach ($required as $key => $value) {
			if (empty($data[$value])) {
				$error[$value]=JText::_('AXISUBS_ERROR_'.strtoupper($value).'_REQUIRED');
			}
		}

		if ($user->id <= 0 ) {

			// check password fields match
			if($data['password'] != $data['password2']){
				$error['password']=JText::_('AXISUBS_PASSWORD_MISMATCH');
			}
			if($data['email'] != $data['email2']){
				$error['email']=JText::_('AXISUBS_EMAIL_MISMATCH');
			}
			
			// check email address
			if (filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL) == false) {
				$error['email']=JText::_('AXISUBS_ENTER_VALID_EMAIL');
			} 

			// check if email already exists
			$user_helper = Axisubs::user();
			if (!isset($error['email']) && $user_helper->emailExists($data['email']) ) {
				$error['email']=JText::_('AXISUBS_ERROR_EMAIL_ALREADY_EXISTS');	
			}

			if ( $user_helper->usernameExists($data['username']) ) {
				$error['username']=JText::_('AXISUBS_ERROR_CARD_ALREADY_REGISTERED');	
			}
		}

		return $error;
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

		$username = $this->getState('username', null, 'string');

		if ($username)
		{
			$this->whereHas('user', function(\JDatabaseQuery $subQuery) use($username, $db) {
				$subQuery->where($db->qn('username') . ' LIKE ' . $db->q('%' . $username . '%'));
			});
		}

		$name = $this->getState('name', null, 'string');

		if ($name)
		{
			$this->whereHas('user', function(\JDatabaseQuery $subQuery) use($name, $db) {
				$subQuery->where($db->qn('name') . ' LIKE ' . $db->q('%' . $name . '%'));
			});
		}

		$email = $this->getState('email', null, 'string');

		if ($email)
		{
			$this->whereHas('user', function(\JDatabaseQuery $subQuery) use($email, $db) {
				$subQuery->where($db->qn('email') . ' LIKE ' . $db->q('%' . $email . '%'));
			});
		}

		$block = $this->getState('block', null, 'int');

		if (!is_null($block))
		{
			$this->whereHas('user', function(\JDatabaseQuery $subQuery) use($block, $db) {
				$subQuery->where($db->qn('block') . ' = ' . $db->q($block));
			});
		}

		$search = $this->getState('search', null, 'string');

		if ($search)
		{
			$search = '%' . $search . '%';
			$query->where(
				'(' .
				'(' . $db->qn('company') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('vat_number') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('address1') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('address2') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('city') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('state') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('zip') .
				' LIKE ' . $db->q($search) . ')'
				. ')'
			);
		}
	}

	/**
	 * Returns the merged data from the Akeeba Subscriptions' user parameters, the Joomla! user data and the Joomla!
	 * user profile data.
	 *
	 * @param   int  $user_id  The user ID to load, null to use the alredy loaded user
	 *
	 * @return  object
	 */
	public function getMergedData($user_id = null)
	{
		if (is_null($user_id))
		{
			$user_id = $this->getState('user_id', $this->user_id);
		}

		$this->find(['user_id' => $user_id]);

		// Get a legacy data set from the user parameters
		$userRow = $this->user;

		if (empty($this->user_id) || !is_object($userRow))
		{
			/** @var JoomlaUsers $userRow */
			$userRow = $this->container->factory->model('JoomlaUsers')->tmpInstance();
			$userRow->find($user_id);
		}

		// Decode user parameters
		$params = $userRow->params;

		if (!($userRow->params instanceof \JRegistry))
		{
			JLoader::import('joomla.registry.registry');
			$params = new \JRegistry($userRow->params);
		}

		$company = $params->get('company', '');

		$nativeData = array(
			'company'   => $params->get('company', ''),
			'vat_number'      => $params->get('vat_number', ''),
			'address1'       => $params->get('address', ''),
			'address2'       => $params->get('address2', ''),
			'city'           => $params->get('city', ''),
			'state'          => $params->get('state', ''),
			'zip'            => $params->get('zip', ''),
			'country'        => $params->get('country', ''),
			'params'         => array()
		);

		$userData = $userRow->toArray();
		$myData = $nativeData;

		foreach (array('name', 'username', 'email') as $key)
		{
			$myData[$key] = $userData[$key];
		}

		$myData['email2'] = $userData['email'];

		unset($userData);

		if (($user_id > 0) && ($this->user_id == $user_id))
		{
			$myData = array_merge($myData, $this->toArray());

			if (is_string($myData['params']))
			{
				$myData['params'] = json_decode($myData['params'], true);

				if (is_null($myData['params']))
				{
					$myData['params'] = array();
				}
			}
		}
		else
		{
		/*	$taxParameters = $this->container->factory->model('TaxHelper')->tmpInstance()->getTaxDefiningParameters($myData);

			$taxData = array(
				'city'       => $taxParameters['city'],
				'state'      => $taxParameters['state'],
				'country'    => $taxParameters['country'],
				'params'     => array()
			);

			$myData = array_merge($myData, $taxData);*/
		}

		// Finally, merge data coming from the plugins. Note that the
		// plugins only run when a new subscription is in progress, not
		// every time the user data loads.
		$this->container->platform->importPlugin('axisubs');

		// 'onAKUserGetData', array((object)$myData) ;

		if (!isset($myData['params']))
		{
			$myData['params'] = array();
		}

		$myData['params'] = (object)$myData['params'];

		return (object)$myData;
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
}