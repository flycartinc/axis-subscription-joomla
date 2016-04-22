<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
use JPluginHelper;
use JFactory;

class Plugin
{

	public static $instance = null;

	public function __construct($properties=null) {

	}

	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}

		return self::$instance;
	}

	/**
	 * Only returns plugins that have a specific event
	 *
	 * @param $eventName
	 * @param $folder
	 * @return array of JTable objects
	 */
	public function getPluginsWithEvent( $eventName, $folder='Axisubs' )
	{
		$return = array();
		if ($plugins = $this->getPlugins( $folder ))
		{
			foreach ($plugins as $plugin)
			{
				if ($this->hasEvent( $plugin, $eventName ))
				{
					$return[] = $plugin;
				}
			}
		}
		return $return;
	}

	/**
	 * Returns Array of active Plugins
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public static function getPlugins( $folder='Axisubs' )
	{
		$database = JFactory::getDBO();

		$order_query = " ORDER BY ordering ASC ";
		$folder = strtolower( $folder );

		$query = "
		SELECT
		*
		FROM
		#__extensions
		WHERE  enabled = '1'
		AND
		LOWER(`folder`) = '{$folder}'
		{$order_query}
		";

		$database->setQuery( $query );
		$data = $database->loadObjectList();
		return $data;
	}
	
	/**
	 * Returns an active Plugin
	 * 
	 * @param
	 *        	mixed Boolean
	 * @param
	 *        	mixed Boolean
	 * @return array
	 */
	public static function getPlugin($element, $folder = 'axisubs') {
		if (empty ( $element ))
			return false;
		$row = false;
		$db = JFactory::getDBO ();
		
		$folder = strtolower ( $folder );
		$query = $db->getQuery ( true )->select ( '*' )->from ( '#__extensions' )
						->where ( $db->qn ( 'enabled' ) . ' = ' . $db->q ( 1 ) )
						->where ( $db->qn ( 'folder' ) . ' = ' . $db->q ( $folder ) )
						->where ( $db->qn ( 'element' ) . ' = ' . $db->q ( $element ) );
		
		$db->setQuery ( $query );
		try {
			$row = $db->loadObject ();
		} catch ( Exception $e ) {
			return false;
		}
		return $row;
	}

	/**
	 * Returns HTML
	 * @param mixed Boolean
	 * @param mixed Boolean
	 * @return array
	 */
	public function getPluginsContent( $event, $options, $method='vertical' )
	{
		$text = "";
		jimport('joomla.html.pane');

		if (!$event) {
			return $text;
		}

		$args = array();

		$results = JFactory::getApplication()->triggerEvent( $event, $options );

		if ( !count($results) > 0 ) {
			return $text;
		}

		// grab content
		switch( strtolower($method) ) {
			case "vertical":
				for ($i=0; $i<count($results); $i++) {
					$result = $results[$i];
					$title = $result[1] ? JText::_( $result[1] ) : JText::_( 'Info' );
					$content = $result[0];

					// Vertical
					$text .= '<p>'.$content.'</p>';
				}
				break;
			case "tabs":
				break;
		}

		return $text;
	}

	/**
	 * Checks if a plugin has an event
	 *
	 * @param obj      $element    the plugin JTable object
	 * @param string   $eventName  the name of the event to test for
	 * @return unknown_type
	 */
	public function hasEvent( $element, $eventName )
	{
		$success = false;
		if (!$element || !is_object($element)) {
			return $success;
		}

		if (!$eventName || !is_string($eventName)) {
			return $success;
		}

		// Check if they have a particular event
		$import 	= JPluginHelper::importPlugin( strtolower('Axisubs'), $element->element );

		$result 	= JFactory::getApplication()->triggerEvent( $eventName, array( $element ) );
		if (in_array(true, $result, true))
		{
			$success = true;
		}
		return $success;
	}

	public function enableAxisubsPlugin() {
		$db = JFactory::getDBO();

		$folder = strtolower( 'axisubs');

		$query = $db->getQuery(true)->update('#__extensions')->set('enabled=1')
					->where($db->qn('folder').' = '.$db->q('system'))
					->where($db->qn('element').' = '.$db->q('axisubs'));
		$db->setQuery($query);
		$db->execute();
		return true;
	}

	public function importCatalogPlugins() {
		JPluginHelper::importPlugin('content');
	}

	public function event($event, $args=array(), $prefix='onAxisubs') {
		if(empty($event)) return '';
		$this->importCatalogPlugins();
		JPluginHelper::importPlugin('axisubs');
		$app = JFactory::getApplication();
		$result = $app->triggerEvent($prefix.$event, $args);
		return $result;
	}

	/**
	 * Method to get the html output of an event
	 * @param string $event
	 * @param array $args
	 * @return string
	 */
	public function eventWithHtml($event, $args=array(), $prefix='onAxisubs') {
		if(empty($event)) return '';
		JPluginHelper::importPlugin('axisubs');
		$app = JFactory::getApplication();
		$html = '';
		$results = $app->triggerEvent($prefix.$event, $args);
		foreach($results as $result) {
			$html .= $result;
		}
		return $html;
	}

	public function eventWithArray($event, $args=array(), $prefix='onAxisubs') {
		if(empty($event)) return '';
		JPluginHelper::importPlugin('axisubs');
		$app = JFactory::getApplication();
		$results = $app->triggerEvent($prefix.$event, $args);
		$array = array();
		if(isset($results[0])) {
			$array = $results[0];
		}
		return $array;
	}
}