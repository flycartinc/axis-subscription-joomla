<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use JUri;
use JText;
use JFactory;
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class Customers extends DataController
{
	function getState(){
		$app = JFactory::getApplication();
		$country = $app->input->get('country','US');
		$list = Select::getZones($country);		
		echo json_encode($list); exit;
	}

	//Get Joomla User Details
	function getUserDetails(){
		$app = JFactory::getApplication();
		$userId = $app->input->get('id');
		$model = $this->getModel();
		$model->load(array('user_id' => $userId));
		if(isset($model->axisubs_customer_id) && $model->axisubs_customer_id){
			$data['exist_already'] = 1;
			$data['id'] = $model->axisubs_customer_id;
		} else {
			$userData = JFactory::getUser($userId);
			$data['first_name'] = $userData->name;
			$data['email'] = $userData->email;
			$data['exist_already'] = 0;
		}
		echo json_encode($data); exit;
	}

	function getSubscriptionsOfCustomer(){
		$curr_helper = Axisubs::currency();
		$status_helper = Axisubs::status();
		$app = JFactory::getApplication();
		$userId = $app->input->get('id');
		$model = $this->getModel('Subscriptions');
		$data = $model->user_id($userId)
			->filter(function($item){
				return ($item->status !='N');
			})
			->get();
		if(count($data)){
			foreach($data as $key => $value){
				echo '<div class="item">';
				echo '<span class="transaction-history-text">'.JText::_('COM_AXISUBS_PLAN_NAME').': </span>'.$value->plan->name.' ';
				echo '<span class="transaction-history-text">'.JText::_('COM_AXISUBS_PLAN_PRICE').': </span>'. $curr_helper->format( $value->total, $value->currency_code).' ';
				echo '<span class="transaction-history-text">'.JText::_('AXISUBS_SUBSCRIPTION_STATUS').': </span>'. $status_helper->get_text( $value->status).' ';
				echo '</div>';
			}
		} else {
			echo JText::_('COM_AXISUBS_NO_DATA');
		}
		$app->close();
	}
}