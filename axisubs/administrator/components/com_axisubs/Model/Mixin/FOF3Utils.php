<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model\Mixin;
defined('_JEXEC') or die;

use FOF30\Container\Container;

/**
 * Trait for fof3 util functions
 */
trait FOF3Utils
{
	public function getModel($model_name){
		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$model = $container->factory->model($model_name);	
		return $model;
	}
}