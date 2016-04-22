<?php
/**
 * @package   Axisubs - Expiry Control 
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');

require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Model/Mixin/CarbonHelper.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Config.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Date.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Model\Subscriptions;
use Flycart\Axisubs\Admin\Model\Mixin\CarbonHelper;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Date;

class plgSystemAxisexpirycontrol extends JPlugin
{
	/**
	 * Should this plugin be allowed to run? True if FOF can be loaded and the Akeeba Subscriptions component is enabled
	 *
	 * @var  bool
	 */
	private $enabled = true;

	/**
	 * Public constructor. Overridden to load the language strings.
	 */
	public function __construct(& $subject, $config = array())
	{
		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			$this->enabled = false;
		}

		// Do not run if Akeeba Subscriptions is not enabled
		JLoader::import('joomla.application.component.helper');

		if (!JComponentHelper::isEnabled('com_axisubs'))
		{
			$this->enabled = false;
		}

		if (!is_object($config['params']))
		{
			JLoader::import('joomla.registry.registry');
			$config['params'] = new JRegistry($config['params']);
		}

		parent::__construct($subject, $config);

		// Timezone fix; avoids errors printed out by PHP 5.3.3+ (thanks Yannick!)
		if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
		{
			if (function_exists('error_reporting'))
			{
				$oldLevel = error_reporting(0);
			}
			$serverTimezone = @date_default_timezone_get();
			if (empty($serverTimezone) || !is_string($serverTimezone))
			{
				$serverTimezone = 'UTC';
			}
			if (function_exists('error_reporting'))
			{
				error_reporting($oldLevel);
			}
			@date_default_timezone_set($serverTimezone);
		}

		// Load the language files
		$jlang = JFactory::getLanguage();
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, null, true);

		$jlang->load('com_axisubs', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_axisubs', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_axisubs', JPATH_SITE, null, true);
	}

	/**
	 * Called when Joomla! is booting up and checks for expired subscriptions.
	 */
	public function onAfterInitialise()
	{

		if (!$this->enabled)
		{
			return;
		}

		// Check if we need to run
		if (!$this->doIHaveToRun())
		{
			return;
		}

		$this->onAxisubsCronTask('expirationcontrol');
	}

	public function onAxisubsCronTask($task, $options = array())
	{
		if (!$this->enabled)
		{
			return;
		}

		if ($task != 'expirationcontrol')
		{
			return;
		}

		
		// Process the number of future subscriptions without start date
		$cliModel = Container::getInstance('com_axisubs')->factory->model('CliActions')->tmpInstance();
		
		$cliModel->expiryControl();
		
		/*

		$date = Axisubs::date();
		$current_date = $date->getCarbonDate();

		// Process the number of future subscriptions without start date
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$exp_subs = $subsModel
				->status('A')
				->term_end($current_date)
				->limit(10)
				->get();

		if ( count( $exp_subs ) > 0 ) {
			foreach ($exp_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}

		// Process the expired subscriptions in confirmed state
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$future_subs = $subsModel
				->status('F')
				->term_start( $current_date )
				->limit(10)
				->get();

		if ( count( $future_subs ) > 0 ) {
			foreach ($future_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}

		// Process the trial ended subscriptions in trial state
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$trial_ended_subs = $subsModel
				->status('T')
				->trial_end( $current_date )
				->limit(10)
				->get();

		if ( count( $trial_ended_subs ) > 0 ) {
			foreach ($trial_ended_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}*/

		// Update the last run info and quit
		$this->setLastRunTimestamp();
	}

	/**
	 * Fetches the com_axisubs component's parameters as a JRegistry instance
	 *
	 * @return JRegistry The component parameters
	 */
	private function getComponentParameters()
	{
		JLoader::import('joomla.registry.registry');

		$component = JComponentHelper::getComponent('com_axisubs');

		if ($component->params instanceof JRegistry)
		{
			$cparams = $component->params;
		}
		elseif (!empty($component->params))
		{
			$cparams = new JRegistry($component->params);
		}
		else
		{
			$cparams = new JRegistry('{}');
		}

		return $cparams;
	}

	/**
	 * "Do I have to run?" - the age old question. Let it be answered by checking the
	 * last execution timestamp, stored in the component's configuration.
	 */
	private function doIHaveToRun()
	{
		/*$params      = $this->getComponentParameters();
		$lastRunUnix = $params->get('plg_akeebasubs_asexpirationcontrol_timestamp', 0);
		$dateInfo    = getdate($lastRunUnix);
		$nextRunUnix = mktime(0, 0, 0, $dateInfo['mon'], $dateInfo['mday'], $dateInfo['year']);
		$nextRunUnix += 24 * 3600;
		$now = time();

		return ($now >= $nextRunUnix);*/
		return true; ///////////////
	}

	/**
	 * Saves the timestamp of this plugin's last run
	 */
	private function setLastRunTimestamp()
	{
		$lastRun = time();
		$params  = $this->getComponentParameters();
		$params->set('plg_akeebasubs_asexpirationcontrol_timestamp', $lastRun);

		$db   = JFactory::getDBO();
		$data = $params->toString();

		$query = $db->getQuery(true)
		            ->update($db->qn('#__extensions'))
		            ->set($db->qn('params') . ' = ' . $db->q($data))
		            ->where($db->qn('element') . ' = ' . $db->q('com_axisubs'))
		            ->where($db->qn('type') . ' = ' . $db->q('component'));
		$db->setQuery($query);
		$db->execute();
	}
}