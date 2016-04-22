<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use JUri;
use JText;
use JFactory;
use Flycart\Axisubs\Admin\Helper\Select;

class Customers extends DataController
{
	function getState(){
		$app = JFactory::getApplication();
		$country = $app->input->get('country','US');
		$list = Select::getZones($country);		
		echo json_encode($list); exit;
	}
}