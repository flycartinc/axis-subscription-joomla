<?php
/**
 * @package   Axisubs - Expiry Control 
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php'))
	require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/Model/Mixin/CarbonHelper.php'))
	require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Model/Mixin/CarbonHelper.php');
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Config.php'))
	require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Config.php');
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Date.php'))
	require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Date.php');
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php'))
	require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');
if(JFile::exists(JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/SetSessionData.php'))
	require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/SetSessionData.php');

use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Model\Subscriptions;
use Flycart\Axisubs\Admin\Model\CliActions;
use Flycart\Axisubs\Admin\Model\Mixin\CarbonHelper;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Config;
use Flycart\Axisubs\Admin\Helper\Date;

class plgSystemAxisexpirycontrol extends JPlugin
{
	/**
	 * Should this plugin be allowed to run? True if FOF can be loaded and the Akeeba Subscriptions component is enabled
	 *
	 * @var  bool
	 */
	private $enabled = true;
	private $cron = false;
	private $_element    = 'axisexpiry_cron';

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

		$jlang->load('plg_system_axisexpirycontrol', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('plg_system_axisexpirycontrol', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('plg_system_axisexpirycontrol', JPATH_ADMINISTRATOR, null, true);
	}

	/**
	 * Called when user login
	 * */
	public function onUserAfterLogin($user, $options = array())
	{
		$setSessionData = Axisubs::setSessionData();
		$setSessionData->updateAddressSessionData();
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

		//For checking cron/normal
		$app = JFactory::getApplication();
		$cron_key = $app->input->get('axiscronkey');
		$plugin_param = $this->params;
		$param_cron_key = $plugin_param->get('cron_key');
		$run_as_cron = $plugin_param->get('run_as_cron', 2);
		if($cron_key != '' && $param_cron_key === $cron_key && $run_as_cron == "1"){
			$this->cron = true;
		} else{
			if($run_as_cron == "1"){
				return;
			}
		}

		// Check if we need to run
		if (!$this->doIHaveToRun())
		{
			return;
		}

		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTION_STARTED');
		$this->addLogForCron($message, 1);

		$this->onAxisubsCronTask('expirationcontrol');

		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTION_END');
		$this->addLogForCron($message);

		if($this->cron){
			echo "\n".JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTED_SUCCESSFULLY')."\n";exit;
		}
	}

	public function onAxisubsCronTask($task, $options = array())
	{
		if (!$this->enabled)
		{
			$message = "Something goes wrong";
			$this->addLogForCron($message);
			return;
		}

		if ($task != 'expirationcontrol')
		{
			return;
		}

		//$record_limit = 10;
		// Process the number of future subscriptions without start date
		$cliModel = Container::getInstance('com_axisubs',array(),'admin')->factory->model('CliActions')->tmpInstance();
		
		$cliModel->expiryControl();
		

		$date = Axisubs::date();
		$current_date = $date->getCarbonDate();

		// 1. Process the expired subscriptions in confirmed state
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$exp_subs = $subsModel
				->status('A')
				->term_end($current_date)
				//->limit( $record_limit )
				->get();

		$exp_subs_count = 0 ;

		if ( count( $exp_subs ) > 0 ) {
			foreach ($exp_subs as $sub) {
				if( isset($sub->plan->plan_type) && $sub->plan->plan_type == 1 ) {
					$sub->selfCheckStatus();
					$exp_subs_count ++ ;
				}
			}
		}
		
		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_PROCESS_CONFIRMED_STATE')."\n";
		$message .= JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTED').": ". $exp_subs_count ;
		$this->addLogForCron($message);
		
		// 2. Process the number of future subscriptions without start date
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$future_subs = $subsModel
				->status('F')
				->term_start( $current_date )
				//->limit( $record_limit )
				->get();

		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_PROCESS_START_STATE')."\n";
		$message .= JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTED').": ".count( $future_subs );
		$this->addLogForCron($message);

		if ( count( $future_subs ) > 0 ) {
			foreach ($future_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}

		// 3. Process the trial ended subscriptions in trial state
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$trial_ended_subs = $subsModel
				->status('T')
				->trial_end( $current_date )
				//->limit( $record_limit )
				->get();

		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_PROCESS_TRIAL_STATE')."\n";
		$message .= JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTED').": ".count( $trial_ended_subs );
		$this->addLogForCron($message);

		if ( count( $trial_ended_subs ) > 0 ) {
			foreach ($trial_ended_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}

		// 4. Process the recurring subscriptions for which the term has ended and next term needs to start
		$subsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();
		$trial_ended_subs = $subsModel
				->status('A')
				->recurring( 1 )
				->term_end($current_date)
				//->limit(10)
				->get();

		$message = JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_PROCESS_TERM_TO_START')."\n";
		$message .= JText::_('PLG_SYSTEM_AXISEXPIRYCONTROL_CRON_EXECUTED').": ".count( $trial_ended_subs );
		$this->addLogForCron($message);

		if ( count( $trial_ended_subs ) > 0 ) {
			foreach ($trial_ended_subs as $sub) {
				$sub->selfCheckStatus();
			}
		}

		// Update the last run info and quit
		$this->setLastRunTimestamp();
	}

	/**
	 * "Do I have to run?" - the age old question. Let it be answered by checking the
	 * last execution timestamp, stored in the component's configuration.
	 */
	private function doIHaveToRun()
	{
		if($this->cron){
			return true;
		}
		$config 	 = Axisubs::config();
		$lastRunUnix = $config->get('axisubs_expirationcontrol_timestamp', 0);
		$nextRunUnix = $lastRunUnix;
		$nextRunUnix += $this->params->get('execute_by_each_hours', 12) * 3600;
		$now = time();
		return ($now >= $nextRunUnix);
	}

	/**
	 * Saves the timestamp of this plugin's last run
	 */
	private function setLastRunTimestamp()
	{
		$lastRun = time();
		$config  = Axisubs::config();
		$conf_timestamp = $config->get('axisubs_expirationcontrol_timestamp', '');

		if ( $conf_timestamp == '' ) {
			$sql = " INSERT INTO `#__axisubs_configurations` (`config_meta_key`, `config_meta_value`, `config_meta_default`) VALUES ('axisubs_expirationcontrol_timestamp', $lastRun, '0'); " ;
		} else {
			$sql = " UPDATE `#__axisubs_configurations` SET `config_meta_value` = '".$lastRun."' WHERE `#__axisubs_configurations`.`config_meta_key` = 'axisubs_expirationcontrol_timestamp'; ";
		}
		$db = JFactory::getDbo();
		$db->setQuery($sql);
		$db->execute();
	}

	//For creating log
	private function addLogForCron($text, $start = 0, $type = 'message')
    {
        if (is_array($text) || is_object($text)) {
            $text = json_encode($text);
        }

		$file = JPATH_ROOT . "/cache/{$this->_element}.txt";
		$date = \JDate::getInstance();

		$f = fopen($file, 'a');
		if($start){
			fwrite($f, "\n" . "----------------------------------------------------------------");
		}
		fwrite($f, "\n\n" . $date->format('Y-m-d H:i:s'));
		fwrite($f, "\n" . $type . ': ' . $text);
		if ($this->cron) {
			echo "\n\n" . $date->format('Y-m-d H:i:s');
			echo "\n" . $type . ': ' . $text;
		}
		fclose($f);
	}
}