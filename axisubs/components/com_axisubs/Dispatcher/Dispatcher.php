<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Site\Dispatcher;

use Flycart\Axisubs\Admin\Helper\ComponentParams;
use JUri;

require_once(JPATH_ADMINISTRATOR.'/components/com_axisubs/version.php');

defined('_JEXEC') or die;

class Dispatcher extends \FOF30\Dispatcher\Dispatcher
{
	/** @var   string  The name of the default view, in case none is specified */
	public $defaultView = 'Plans';

	public function onBeforeDispatch()
	{
		// Translate view names 
		$this->translateOldViewNames();
		$this->_loadMediaFiles();
		require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
	}

	/**
	 * Translates the view name 
	 */
	protected function translateOldViewNames()
	{
		$map = [
			'plan'    		=> 'Plan',
			'plans'    		=> 'Plans',
			'subscribe' 	=> 'Subscribe',
			'myprofile'		=> 'Profile',
			'profile' 		=> 'Profile',
			'myaccount' 	=> 'Profile',
		];

		$oldViewName = strtolower($this->view);

		if (isset($map[$oldViewName]))
		{
			$this->view = $map[$oldViewName];
		}
	}

	function _loadMediaFiles(){

		// Load common CSS and JavaScript
		\JHtml::_('jquery.framework');
		\JHtml::_('bootstrap.framework');
		$doc = \JFactory::getDocument();
		// admin css
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/font-awesome.min.css');
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/axisubs.css');
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/axisubs_bootstrap.min.css');

		$doc->addScript ( JURI::root ( true ) . '/media/com_axisubs/js/axisubs.js' );
	}
	
}