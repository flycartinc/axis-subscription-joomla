<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use JFactory;
use JText;

/**
 * Permission helper.
 */
class Status{
	public static $instance = null;
	public static $statuses = null;

	public function __construct($properties=null) {
	}

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
			self::$statuses = self::getCoreStatuses();
		}

		return self::$instance;
	}

	function get_text($status){
		if (isset(self::$statuses[$status])){
			return JText::_( 'AXISUBS_STATUS_'.self::$statuses[$status] );
		}else {
			return $status;
		}
	}

	function get_label($status){
		$labels = array(
						'N' => 'warning',
						'T' => 'info', 
						'P' => 'info', 
						'A' => 'success', 
						'F' => 'info',
						'E' => 'danger',
						'C' => 'danger',
						'R' => 'warning',
						'O' => 'info'
						);
		$ret = '';
		if (isset($labels[$status]))
			$ret = $labels[$status];

		return $ret;
	}

	/**
	 * Get readable list of array for forms
	 * */
	public static function getList(){
  		self::$statuses = self::getCoreStatuses();
  		$result = array();
  		foreach(self::$statuses as $key => $value){
  			$result[$key] = JText::_('AXISUBS_STATUS_'.$value);
  		}
  		return $result;
  	}

  	public static function getStatusKeys(){
  		return array_keys( self::getCoreStatuses() );
  	}

  	/**
  	 * Statuses in which a subscription can live 
  	 * */
	public static function getCoreStatuses(){

		/**
		 * List of statuses a subsctiption could have
		 * New 			- N 		-	
		 * Trial 		- T 		-	when subscription is in trial period
		 * Pending 		- P 		-	when subscription is not paid
		 * Active 		- A 		-	Payment_old successfull and state is Active
		 * Future		- F 		- 	When the subscription start date is in future date	
		 * Expired 		- E 		-	Subscription has expired
		 * Cancelled 	- C 		-	Cancelled ( cancelled but Payment not refunded )
		 * Refunded 	- R 		-	Refunded ( cancelled and Payment has been refunded )
		 * Other 		- O 		-	Other	( A reason statement can be specified for understanding the status )
		 * */
		self::$statuses = array(
						'N' => 'New',
						'T' => 'Trial', 
						'P' => 'Pending', 
						'A' => 'Active', 
						'F' => 'Future',
						'E' => 'Expired',
						'C' => 'Cancelled',
						'R' => 'Refunded',
						'O' => 'Other'
						);
		return self::$statuses;				

	}
}
