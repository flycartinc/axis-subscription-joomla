<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model\Mixin;

defined('_JEXEC') or die;
use Carbon\Carbon;
/**
 * Trait for check() method assertions
 */
trait CarbonHelper
{
	/**
	 * the date will be supplied from sql format
	 * create a valid carbon object based on the supplied date and the format 
	 * @param string $date date in sql format
	 * */
	protected function getDate( $date ='' ){

		// check if the date is aleady an instance of Carbon then return
		if (  is_a( $date, 'Carbon\Carbon') ){
			return $date;
		}

		if ( empty($date) ){
			return $this->getCurrentDate();
		}

		// validate date if it in the below format
		// "Y-m-d H:i:s" --> 2016-03-18 00:00:00
		$format="Y-m-d H:i:s";
		$valid_date = false;
		if(!preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date)){
		    // invalid date format - try to convert the suppied date
			$date = date('Y-m-d H:i:s',strtotime($date));
		}

		if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$date)){
		   // valid format proceed
			$valid_date = true;
		}

		if ($valid_date){
			$start = Carbon::createFromFormat($format, $date);
			//$start = Carbon::parse($date); //also works
		}else {
			// throw error
			$this->throwError('COM_AXISUBS_SUBSCRIPTION_INVALID_DATE_FORMAT');
		}

		return $start;
	}

	/**
	 * Checks if the date is a past date
	 * @param mixed $date a carbon object or a date string
	 * */
	protected function isPast($date=''){

		if ( empty($date) ){
			return false;
		}

		// check if the date is aleady an instance of Carbon then return
		if ( ! is_a( $date, 'Carbon\Carbon') ) {
			$date = $this->getDate($date);
		}

		$now = $this->getCurrentDate();
		if ($now->gt($date)){
			return true;
		}

		return false;
	}

	protected function getCurrentDate(){
		// wrapper for carbon now to manage timezone in future
		return Carbon::now();
		//$tz = JFactory::getConfig()->get('offset');
		//return Carbon::now( $tz );
	}

	protected function getNullDate(){
		return '0000-00-00 00:00:00';
	}
}