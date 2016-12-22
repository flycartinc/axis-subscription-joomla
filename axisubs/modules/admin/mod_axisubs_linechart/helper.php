<?php
/**
 * @package   Module - Axisubs Line chart
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Carbon\Carbon;
Carbon::setWeekStartsAt(Carbon::SUNDAY);
Carbon::setWeekEndsAt(Carbon::SATURDAY);

class modAxisubsLineChartHelper {
	
	//Get subscription count based on last days
	function getLastDaysData($params, $day = 7){
		$dt = Carbon::create(date('Y'), date('n'), date('j'), date('H'), date('i'), date('s'));
		$dt_time = strtotime($dt);
		$lastWeekStart = strtotime("-".$day." day", $dt_time);
		$startDate = Carbon::create(date('Y', $lastWeekStart), date('n', $lastWeekStart), date('j', $lastWeekStart), 0, 0, 0);
		if($params->get('plans', '0') == '0' || in_array('0', $params->get('plans'))){
			$planSelected = '';
		} else {
			$planSelected = implode(',', $params->get('plans'));
		}

		$plans = $this->getPlans($planSelected);
		$display = new \stdClass();
		$display->startDate = date('Y-m-d', strtotime($startDate));
		$display->endDate = date('Y-m-d', strtotime($dt));
		$display_data = array();
		foreach($plans as $key => $plan){
			$display_data[$key] = new \stdClass();
			$display_data[$key]->plan_name = $plan->name;
			$display_data[$key]->plan_id = $plan->axisubs_plan_id;
			$subscriptions = $this->getSubscriptionsByDate($startDate, $dt, $plan->axisubs_plan_id);
			$display_data[$key]->subscriptions = $subscriptions;
		}
		$display->plans = $display_data;
		return $display;
	}	

	//get Plans
	protected function getPlans($planSelected = ''){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('axisubs_plan_id, name')
			->from('#__axisubs_plans');
		if($planSelected != ''){
			$query->where('axisubs_plan_id IN ('.$planSelected.')');
		}
		$db->setQuery($query);
		$data = $db->loadObjectList();
		return $data;
	}

	//get subscriptions by date and plan
	protected function getSubscriptionsByDate($startDate, $endDate, $planId = 0){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry-> select('count(`sp`.`axisubs_subscription_id`) as count, sp.plan_id, sp.created_on, substr(sp.created_on, 1, 10) as date')
			-> from('#__axisubs_subscriptions as sp')
			->where('sp.created_on >= '. $db->q((string)$startDate).' AND sp.created_on <= '. $db->q((string)$endDate))
			->where('sp.status IN (\'A\', \'F\')')
			->group('substr(sp.created_on, 1, 10)');
		if($planId){
			$qry->where('sp.plan_id = '.$db->q($planId));
		}
		$db->setQuery($qry);
		$data = $db->loadObjectList('date');

		return $data;
	}
}
