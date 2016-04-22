<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\View\Subscriptions;
defined('_JEXEC') or die;

use JFactory;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Model\Customers;

class Form extends \FOF30\View\DataView\Form
{	
	/**
	 * Method to find the customer id and set the customer model in the view object 
	 * */
	public function onBeforeAdd( $tpl = null ){
		
		parent::onBeforeAdd( $tpl = null );

		// get the user id from the input and set in the view
		$app = JFactory::getApplication();
		$user_id = $app->input->get('user_id',0);

		if ( isset($this->item->user_id) && !empty($this->item->user_id) ){
			$user_id = $this->item->user_id ;
		}

		$container = \FOF30\Container\Container::getInstance ( 'com_axisubs' );
		$customer_model = $container->factory->model ( 'Customers' );

		//$customer_model = $this->getModel('Customers');
		
		if ( $user_id > 0 ){
			$cust_count = 0;
			$customer_model->limitstart(0);
			$cust_count = $customer_model->user_id($user_id)->get()->count();
			if ( $cust_count > 0 ) {
				$this->customer = $customer_model->user_id($user_id)->get()->first();	
				$this->current_customer = $this->customer;
			}
		}

		if ( !isset($this->customer) ){
			$app->redirect('index.php?option=com_axisubs&view=Customers&flag=choose_customer');
		}

		
	}

	/**
	 * set the customer object for consistancy
	 * */
	public function onBeforeEdit( $tpl = null ){
		parent::onBeforeEdit( $tpl = null );
		$this->customer  =	$this->item->customer; 
		$this->current_customer = $this->customer;
	}

	public function onBeforeBrowse( $tpl = null ){
		parent::onBeforeBrowse($tpl);
	}
}