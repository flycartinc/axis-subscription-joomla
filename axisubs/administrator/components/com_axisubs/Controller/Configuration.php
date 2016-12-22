<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use JFilterInput;

class Configuration extends DataController {

	public function __construct($config) {

		parent::__construct($config);
		$this->registerTask('apply', 'save');
		$this->registerTask('saveNew', 'save');
		$this->registerTask('populatedata','save');
	}

	public function execute($task) {
		if(in_array($task, array('browse', 'read', 'edit', 'add'))) {
			$task = 'add';
		}
		return parent::execute($task);
	}

	/**
	 * Method to cancel(non-PHPdoc)
	 * @see F0FController::cancel()
	 */
	public function cancel(){
		$app = \JFactory::getApplication();
		$url  ='index.php?option=com_axisubs';
		$app->redirect($url);
	}
	
	/**
	 * Method to save data
	 * (non-PHPdoc)
	 * @see F0FController::save()
	 */
	public function save(){

		//security check
		\JSession::checkToken() or die( 'Invalid Token' );

		$app = \JFactory::getApplication();
		$model = $this->getModel('configurations');
		$data = $app->input->post->getArray();
		$task = $this->getTask();
		$token = \JSession::getFormToken();

		unset($data['option']);
		unset($data['task']);
		unset($data['view']);
		unset($data[$token]);

		$db = \JFactory::getDbo();
		$config = Axisubs::config();
		$query = 'REPLACE INTO #__axisubs_configurations (config_meta_key,config_meta_value) VALUES ';

		jimport('joomla.filter.filterinput');
		$filter = JFilterInput::getInstance(null, null, 1, 1);
		$conditions = array();
		foreach ($data as $metakey=>$value) {
			//now clean up the value
			if($metakey == 'store_billing_layout' || $metakey == 'store_shipping_layout' || $metakey == 'store_payment_layout') {
				$value = $app->input->get($metakey, '', 'raw');
				$clean_value = $filter->clean($value, 'html');

			} else{
				$clean_value = $filter->clean($value, 'string');
			}
			$config->set($metakey, $clean_value);
			$conditions[] = '('.$db->q(strip_tags($metakey)).','.$db->q($clean_value).')';
		}

		$query .= implode(',',$conditions);

		try {
			$db->setQuery($query);
			$db->execute();
			$msg = \JText::_('AXISUBS_CHANGES_SAVED');
		}catch (Exception $e) {
			$msg = $e->getMessage();
			$msgType='Warning';
		}

		switch($task){
			case 'apply':
				$url  ='index.php?option=com_axisubs&view=Configuration';
				break;
			case 'save':
				$url  ='index.php?option=com_axisubs&view=Dashboard';
				break;
		}
		$this->setRedirect($url,$msg,$msgType);
	}
	
}


