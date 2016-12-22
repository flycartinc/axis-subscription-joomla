<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Helper;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JFactory;
use JFolder;
use JHtml;
use JLoader;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Date;


defined('_JEXEC') or die;

/**
 * A helper class for Duration
 */
class Duration
{
	public static $instance = null;

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

	//get the Duration in format
	public function getDurationInFormat($period, $unit){
		$text = JText::_('COM_AXISUBS_PLAN_UNIT_DAY');
		switch($unit){
			case 'W':
				$text = JText::_('COM_AXISUBS_PLAN_UNIT_WEEK');
				break;
			case 'M':
				$text = JText::_('COM_AXISUBS_PLAN_UNIT_MONTH');
				break;
			case 'Y':
				$text = JText::_('COM_AXISUBS_PLAN_UNIT_YEAR');
				break;
		}
		$duration = $period.' '.$text;
		return $duration;
	}
}