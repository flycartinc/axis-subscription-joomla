<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class Router
{
	static function getAndPop(&$query, $key, $default = null)
	{
		if(isset($query[$key]))
		{
			$value = $query[$key];
			unset($query[$key]);
			return $value;
		}
		else
		{
			return $default;
		}
	}

	/**
	 * Finds a menu whose query parameters match those in $qoptions
	 * @param array $qoptions The query parameters to look for
	 * @param array $params The menu parameters to look for
	 * @return null|object Null if not found, or the menu item if we did find it
	 */
	static public function findMenu($qoptions = array(), $params = null)
	{
		static $joomla16 = null;

		if(is_null($joomla16)) {
			$joomla16 = version_compare(JVERSION,'1.6.0','ge');
		}

		// Convert $qoptions to an object
		if(empty($qoptions) || !is_array($qoptions)) $qoptions = array();

		$menus =JMenu::getInstance('site');
		$menuitem = $menus->getActive();
		// First check the current menu item (fastest shortcut!)
		if(is_object($menuitem)) {
			if(self::checkMenu($menuitem, $qoptions, $params)) {
				return $menuitem;
			}
		}


		//print_r($menus->getItems(array('language'), $languages) );
		foreach($menus->getMenu() as $item)
		{
			if($joomla16) {
				if(self::checkMenu($item, $qoptions, $params)) return $item;
			} elseif($item->published)
			{
				if(self::checkMenu($item, $qoptions, $params)) return $item;
			}
		}


		return null;
	}

	/**
	 * Checks if a menu item conforms to the query options and parameters specified
	 *
	 * @param object $menu A menu item
	 * @param array $qoptions The query options to look for
	 * @param array $params The menu parameters to look for
	 * @return bool
	 */
	static public function checkMenu($menu, $qoptions, $params = null)
	{
		$query = $menu->query;
		foreach($qoptions as $key => $value)
		{
			if(is_null($value)) continue;
			if(!isset($query[$key])) return false;
			if($query[$key] != $value) return false;
		}

		if(!is_null($params))
		{
			$menus =JMenu::getInstance('site');
			$check =  $menu->params instanceof JRegistry ? $menu->params : $menus->getParams($menu->id);

			foreach($params as $key => $value)
			{
				if(is_null($value)) continue;
				if( $check->get($key) != $value ) return false;
			}
		}

		$lang = JFactory::getLanguage();
		if($lang->getTag() == $menu->language) {
			return true;
		}if($menu->language == '*') {
			return true;
		} else {
			return false;
		}

		return true;
	}

	static public function preconditionSegments($segments)
	{
		$newSegments = array();
		if(!empty($segments)) foreach($segments as $segment)
		{
			if(strstr($segment,':'))
			{
				$segment = str_replace(':','-',$segment);
			}
			if(is_array($segment)) {
				$newSegments[] = implode('-', $segment);
			} else {
				$newSegments[] = $segment;
			}
		}
		return $newSegments;
	}

}
