<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Site\Model;
defined('_JEXEC') or die;

use FOF30\Model\DataModel;
use Flycart\Axisubs\Admin\Helper\Axisubs;	

use FOF30\Container\Container;
use FOF30\Utils\Ip;
use JFactory;
use JText;

class Customers extends DataModel
{

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
		$this->fieldsSkipChecks = [ 'first_name', 'last_name', 'email', 'phone', 'address2', 'user_id','company',
									'notes','vat_number','auto_collection','allow_direct_debit','created_from_ip','params'];
	}

	function validateAccountDetails($customer_data){

		//  validation
		$errors = [];
		$user = JFactory::getUser();
		// check reqiured fields first
		$required = array('first_name');
		$reg_required = array('password1','password2','email');
		if ($user->id <= 0) {
			$required = array_merge( $required, $reg_required );
		}
		foreach ($required as $key => $value) {
			if ( !isset( $customer_data[$value] ) ){
				$errors[$value]=JText::_('AXISUBS_ERROR_REQUIRED');
				continue;
			}
			if (empty($customer_data[$value])) {
				$errors[$value]=JText::_('AXISUBS_ERROR_REQUIRED');
			}
		}

		if ($user->id <= 0 ) {
		
			// check password fields match
			if($customer_data['password1'] != $customer_data['password2']){
				$errors['password1']=JText::_('AXISUBS_PASSWORD_MISMATCH');
			}
			
			// check email address
			if (filter_var(trim($customer_data['email']), FILTER_VALIDATE_EMAIL) == false) {
				$errors['email']=JText::_('AXISUBS_ENTER_VALID_EMAIL');
			} 

			// check if email already exists
			$user_helper = Axisubs::user(); 
			if (!isset($errors['email']) && $user_helper->emailExists($customer_data['email']) ) {
				$errors['email']=JText::_('AXISUBS_ERROR_EMAIL_ALREADY_EXISTS');
				// this error - the user could be given an easy option to login / quick reset password
				// any other way of quick authentication without losing the lead
			}

			if ( $user_helper->usernameExists($customer_data['email']) ) {
				$errors['username']=JText::_('AXISUBS_ERROR_ALREADY_REGISTERED');
			}
		}
		$ret = array();
		foreach ($errors as $k=>$err) {
			$ret['customer\\['.$k.'\\]'] = $err ;
		}
		return $ret;
	}

	public function validateAddress($data){
		//  validation
		$errors = [];
		$user = JFactory::getUser();
		// check reqiured fields first
		$required = array('first_name','address1','city','zip','country','state');

		foreach ($required as $key => $value) {
			if (empty($data[$value])) {
				$errors[$value]=JText::_('AXISUBS_ERROR_REQUIRED');
			}
		}

		$ret = array();
		foreach ($errors as $k=>$err) {
			$ret['billing_address\\['.$k.'\\]'] = $err ;
		}
		return $ret;
	}

	/**
	 * Create a new customer record if the user id does not exists
	 * */
	public function createNewCustomer($data){

		$user = JFactory::getUser();
		if ( isset($user->id) && $user->id > 0){
			return $user->id;
		}

		$user_details = array();
		$user_details['name'] = $data['first_name'] .' '.$data['last_name'] ;
		$user_details['email'] = $data['email'] ;

		$user_details['password'] = $data['password1'] ;
		$user_helper = Axisubs::user();
		$msg = '';
		$user = $user_helper->createNewUser($user_details,$msg);	
		if (isset($user->id)){
			return $user->id;
		}else {
			return '';
		}
	}

}