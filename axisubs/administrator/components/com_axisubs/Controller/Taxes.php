<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\Controller;
use JUri;
use JText;
use JFactory;

class Taxes extends Controller
{
	//use Mixin\PredefinedTaskList;

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		//$this->predefinedTaskList = ['main'];
		//$this->taskMap= array('main');
	}


	/**
	 * Runs before the main task, used to perform housekeeping function automatically
	 */
	function main()
	{
		
		$view = $this->getView();
		$all_tax_rates = $this->getModel('TaxRates');
		
		$view->taxrates = $all_tax_rates->get();
		$view->display();
	}

	 function updateTax(){
		$app = JFactory::getApplication();
		$data = $app->input->post->getArray();
		//echo "<pre>";print_r($data);exit;
		 $model = $this->getModel('Taxes');
		 $task = $this->getTask();
		 if(isset($data['axisubs_taxrate_id']) && !empty($data['axisubs_taxrate_id'])){
			 $model->load($data['axisubs_taxrate_id']);
		 }
		 unset($data['option']);
		 unset($data['task']);
		 unset($data['view']);
		 unset($data['token']);
		 $model->bind($data);
		 $model->save();
		 if($task=="updateTax"){
			 $app->redirect("index.php?option=com_axisubs&view=Taxes");
		 }
	}

	function deleteTax(){
		$app = JFactory::getApplication();
		$taxrate_id = $app->input->getInt('taxrate_id',0);
		$json = array();
		if(!empty($taxrate_id)){
			$model = $this->getModel('TaxRates');
			$model->delete($taxrate_id);
			$json['success'] = JText::_('AXISUBS_TAXRATE_DELETE_SUCCESS');
		}else{
			$json['error'] = JText::_('AXISUBS_TAXRATE_DELETE_FAILED');
		}
		echo json_encode($json);
		$app->close();
	}
}

