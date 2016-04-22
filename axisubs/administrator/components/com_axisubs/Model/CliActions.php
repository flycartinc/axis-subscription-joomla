<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Model\Subscriptions;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Model\Model;

class CliActions extends Model
{	

	/**
	 * Method to update the subscription expiry statuses based on thier dates
	 * */
	function expiryControl(){
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
		}
	}

	/**
	 * Method to trigger notifications that will in turn send emails
	 * */
	function triggerNotifications(){

		// trigger expiry alert notices

	}

}