<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use JFactory;

/**
 * Permission helper.
 */
class Permission{
	public static $instance = null;

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
		}

		return self::$instance;
	}

}
