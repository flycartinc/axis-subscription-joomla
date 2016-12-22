<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Helper;

use JComponentHelper;
use JFactory;
use JLoader;
use JURI;
use JText;
use JError;
use FOF30\Container\Container;

defined('_JEXEC') or die;

/**
 * A helper class to set session data
 */
class SetSessionData
{
	public static $instance;
	
	public static function getInstance($properties=null) {
	
		if (!self::$instance)
		{
			self::$instance = new self($properties);
		}
	
		return self::$instance;
	}

	/**
	 * Update session data
	 * */
	public function updateAddressSessionData($customer = null){
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$session = $app->getSession();
		if($user->id){
			if($customer == null){
				$customer = Container::getInstance('com_axisubs',array())->factory->model('Customers')->tmpInstance();
				$customer->load( array('user_id' => $user->id) ) ;
			}
			if(!empty($customer)) {
				if(isset($customer->country))
					$session->set('customer_billing_country', $customer->country, 'axisubs');
				if(isset($customer->state))
					$session->set('customer_billing_state', $customer->state, 'axisubs');
				if(isset($customer->zip))
					$session->set('customer_billing_zip', $customer->zip, 'axisubs');
				if(isset($customer->city))
					$session->set('customer_billing_city', $customer->city, 'axisubs');
			}
		}
	}
}
