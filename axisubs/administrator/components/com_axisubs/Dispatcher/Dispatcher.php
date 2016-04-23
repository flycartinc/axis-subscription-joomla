<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Dispatcher;
use JUri;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Flycart\Axisubs\Admin\Render\AxisRenderer;

defined('_JEXEC') or die;

use FOF30\Container\Container;

class Dispatcher extends \FOF30\Dispatcher\Dispatcher
{
	/** @var   string  The name of the default view, in case none is specified */
	public $defaultView = 'Dashboard';

	public function onBeforeDispatch()
	{
		
		// include namespaced bootstrap files
		$this->_loadMediaFiles();
		$this->translateOldViewNames();
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
			'emailtemplates' 	=> 'EmailTemplates',
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
		
		$doc = \JFactory::getDocument();
		// admin css
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/font-awesome.min.css');
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/axisubs_admin.css');
		$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/axisubs_bootstrap.min.css');

		// admin js
		$doc->addScript ( JURI::root ( true ) . '/media/com_axisubs/js/bootstrap.js' );
		$doc->addScript ( JURI::root ( true ) . '/media/com_axisubs/js/bootstrap.min.js' );
		$doc->addScript ( JURI::root ( true ) . '/media/com_axisubs/js/jquery.sparkline.min.js' );
		$doc->addScript ( JURI::root ( true ) . '/media/com_axisubs/js/jquery.circliful.min.js' );
	}
}
