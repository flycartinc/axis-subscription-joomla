<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Controller\DataController;
use FOF30\Inflector\Inflector;
use FOF30\Container\Container;
use JFactory;

class Plan extends DataController
{	

	/**
	 * A delete prompt to warn the users before deletion of a plan
	 * After confirmation a soft delete is performed
	 * */
	public function deleteprompt(){
		$app = JFactory::getApplication();		
		$plan_id = $app->input->get('id',0);
		$plan = $this->getModel();
		$view = $this->getView();
		$plan->load($plan_id);
		$view->plan = $plan;
		
		$view->setLayout('delete');

		$view->display();
	}

	/**
	 * Method to disable the plan so it is not accessed or listed 
	 * Soft delete the plan
	 * */
	function disablePlan(){
		$app = JFactory::getApplication();		
		$plan_id = $app->input->get('plan_id',0);
		$plan = $this->getModel();
		$view = $this->getView();
		$plan->load($plan_id);
		if (isset($plan->axisubs_plan_id) && $plan->axisubs_plan_id >0 ){
			$plan->enabled = -1	; // soft deleted or to trashed status
			$plan->store();
		}
		$app->redirect('index.php?option=com_axisubs&view=Plans',\JText::_('COM_AXISUBS_LBL_PLAN_DELETED'),'warning');
	}

	public function onBeforeRemove(){
		$app = JFactory::getApplication();		
		$plan_id = $app->input->get('id',0);
		$subs_model = $this->getModel('Subscriptions');
		$active_subs_count = $all_subs_count = 0;
		$active_subs_count = $subs_model->plan_id($plan_id)->status('A')->get()->count();
		$all_subs_count = $subs_model->plan_id($plan_id)->get()->count();
		if ($active_subs_count > 0 || $all_subs_count > 0){
			// redirect to list view with an error message
			$app->redirect('index.php?option=com_axisubs&view=Plans',\JText::_('COM_AXISUBS_PLAN_ERROR_CANNOT_DELETE_PLAN'),'error');
			return ;
		}

	}	
}
