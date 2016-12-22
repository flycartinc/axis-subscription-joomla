<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;
defined ( '_JEXEC' ) or die ();

use \JObject;
use \stdClass;
use \JFactory;

class Logger extends JObject {
	public static $instance = null;	
	var $_data;

	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}

		return self::$instance;
	}
	
	/**
	 * method to log the message
	 * @param  string $event   
	 * @param  string $message data or the message to be logged
	 * @return none
	 */
	public function log($event='',$message=''){		
		
		if (empty($event)) {
			return;
		}

		$app = JFactory::getApplication();
		if (is_array($message) || is_object($message)) {
			$message = json_encode($message);
		}else{
			$message = json_encode( array('message' => $message ) );
		}
		
		$message_obj = json_decode($message);
		$message_obj->option = $app->input->get('option');
		$message_obj->view = $app->input->get('view');
		$message_obj->task = $app->input->get('task');
		if (!isset($message_obj->user_id )) {
			$message_obj->user_id = JFactory::getUser()->id;
		}		
		$message_obj->is_admin = (int)$app->isAdmin();

		$log_data = new stdClass();
		$log_data->log_time = \JDate::getInstance()->toSql();
		$log_data->event = $event ;
		$log_data->message = json_encode($message_obj) ;		
		
		$db = JFactory::getDbo();
		$res = $db->insertObject('#__axisubs_logs', $log_data);
		return;
	}

}
