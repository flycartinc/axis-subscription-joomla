<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper\Plugins;
defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Helper\Plugins\Base;
use Flycart\Axisubs\Admin\Model\Mixin\FOF3Utils;

if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}
use JFactory;
use JText;

class App extends Base
{
	use FOF3Utils;
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
	var $_element    = '';
	
	var $_axisversion = '';
	
	function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);		
	}

	public function onAxisubsGetAppView($row){
		if ( !$this->_isMe( $row ) )
		{
			return null;
		}
	}

	/************************************
	 * Note to 3pd:
	 *
	 * DO NOT MODIFY ANYTHING AFTER THIS
	 * TEXT BLOCK UNLESS YOU KNOW WHAT YOU
	 * ARE DOING!!!!!
	 *
	 ************************************/

	/**
	 * Tells extension that this is a App
	 *
	 * @param $element  string      a valid shipping plugin element
	 * @return boolean	true if it is this particular shipping plugin
	 */
	public function onAxisubsGetAppPlugins( $element )
	{
		$success = false;
		if ( $this->_isMe( $element ) )
		{
			$success = true;
		}
		return $success;
	}


	/**
	 * Gets the app namespace for state variables
	 * @return string
	 */
	protected function _getNamespace( )
	{
		$app = JFactory::getApplication( );
		$ns = $app->getName( ) . '::' . 'com.axisubs.app.' . $this->get( '_element' );
	}

	/**
	 * Get the task for the shipping plugin controller
	 */
	public static function getAppTask( )
	{
	 	return JFactory::getApplication()->input->getString( 'appTask', '' );

	}

	/**
	 * Get the id of the current shipping plugin
	 */
	public static function getAppId( )
	{
		return JFactory::getApplication()->input->getInt( 'id', '' );
	}

	/**
	 * Get a variable from the JRequest object
	 * @param unknown_type $name
	 */
	public function getAppVar( $name )
	{
		$var = JFactory::getApplication()->input->getString( $name, '' );
		return $var;
	}

	/**
	 * Prepares the 'view' tmpl layout
	 * when viewing a app
	 *
	 * @return unknown_type
	 */
	function _renderView( $view = 'view', $vars = null )
	{
		if ( $vars == null ) $vars = new JObject( );
		$html = $this->_getLayout( $view, $vars );

		return $html;
	}

	/**
	 * Prepares variables for the app form
	 *
	 * @return unknown_type
	 */
	function _renderForm($data )
	{
		$vars = new JObject( );
		$html = $this->_getLayout( 'form', $vars );

		return $html;
	}

	/**
	 * Gets the appropriate values from the request
	 *
	 * @return unknown_type
	 */
	function _getState( )
	{
		$state = new JObject( );

		foreach ( $state->getProperties( ) as $key => $value )
		{
			$new_value = JRequest::getVar( $key );
			$value_exists = array_key_exists( $key, JRequest::get( 'post' ) );
			if ( $value_exists && !empty( $key ) )
			{
				$state->$key = $new_value;
			}
		}


		return $state;
	}

	public function getVersion() {
		
		if(empty($this->_axisversion)) {
			$db = JFactory::getDbo();
			// Get installed version
			$query = $db->getQuery(true);
			$query->select($db->quoteName('manifest_cache'))->from($db->quoteName('#__extensions'))->where($db->quoteName('element').' = '.$db->quote('com_axisubs'));
			$db->setQuery($query);
			$registry = new JRegistry;
			$registry->loadString($db->loadResult());
			$this->_axisversion = $registry->get('version');
		}
		
		return $this->_axisversion;
	}
	
	function getCurrency($order, $convert=false) {
		$results = array();
		$currency_code = $order->currency_code;
		$currency_value = $order->currency_value;
		
		$results['currency_code'] = $currency_code;
		$results['currency_value'] = $currency_value;
		$results['convert'] = $convert;
	
		return $results;
	}

	function getSubscription($subscription_id = 0){
		if( $subscription_id > 0 ){
			$sub_model = $this->getModel('Subscriptions');
			$sub_model->load( $subscription_id );
			if ($sub_model->axisubs_subscription_id){
				return $sub_model;
			}
		}else {
			return '';
		}
	}
}