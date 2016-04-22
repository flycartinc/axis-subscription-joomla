<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model;

// No direct access to this file
defined('_JEXEC') or die;

use FOF30\Model\DataModel;

class Configurations extends DataModel {

	public function &getItemList($overrideLimits = false, $group = '')
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)->select('*')->from('#__axisubs_configurations');
		$db->setQuery($query);
		$items = $db->loadObjectList('config_meta_key');
		return $items; 
	} 

 	public function onBeforeLoadForm(&$name, &$source, &$options) {
		$app = \JFactory::getApplication();
		$data1 = $this->_formData;
		$data = $this->getItemList();
		
		$params = array();
		foreach($data as $namekey=>$singleton) {
			$params[$namekey] = $singleton->config_meta_value;
		}
		$this->_formData = $params;		
	}

}
