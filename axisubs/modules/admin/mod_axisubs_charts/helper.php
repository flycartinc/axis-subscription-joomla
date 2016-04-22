<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

use Carbon\Carbon;
Carbon::setWeekStartsAt(Carbon::SUNDAY);
Carbon::setWeekEndsAt(Carbon::SATURDAY);

class modAxisubsChartsHelper {
	
	/**
	 * Method to get the chart data from db to diplay in view 
	 * @return stdClass display data
	 * */
	function getData(){
		$display_data = new \stdClass();

		$display_data->weekly_sales_plan = $this->getWeeklySalesPlan();

		//total revenue in particular week
		$display_data->firstweek_amount=$this->getFirstWeekAmount();
		//total revenue in particular week
		$display_data->secondweek_amount=$this->getSecondWeekAmount();
		//net amount in particular week
		$display_data->weeknet_amount=$this->getWeekNetAmount();
		//net amount in particular month
		$display_data->monthnet_amount=$this->getMonthNetAmount();
		//no of sale  in particular day
		$display_data->day_sale=$this->getDaySale();
		//total sale  revenue
		$display_data->total_sale_amount=$this->getTotalSaleAmount();
		//total pending order
		$display_data->total_pending_order=$this->getPendingOrder();

		//carbon date class to create the date
		$dt = Carbon::create(2016, 3, 9, 12, 0, 0);
		//carbon date function startOfWeek() to calculate start of the week
		//carbon date function endOfWeek() to calculate end of the week
		$display_data->datearray=$this->createDaysforWeek((string)$dt->startOfWeek(),(string)$dt->endOfWeek());
		$display_data->datearrayfills= array_fill_keys($display_data->datearray,'0');
		$display_data->array = $display_data->firstweek_amount;
		//foreach to replace the available date values
		    foreach($display_data->array as $key=>$value)
		    {
		        $display_data->date=$display_data->array[$key]->encoded_date;
		        $display_data->firstweekamount=$display_data->array[$key]->firstweekamount;
		        foreach($display_data->datearrayfills as $k => $v)
		        {  
		            if($display_data->date==$k)
		            {
		           $display_data->datearrayfills[$k]=$display_data->firstweekamount;
		            }
		        }
		    }
		$display_data->firstweekdatearray=array();
		    foreach($display_data->datearrayfills as $key =>$value)
		    {
		        $display_data->firstweekdatearray[]=$value;
		    }

		$dt = Carbon::create(2016, 3, 3, 12, 0, 0);
		$display_data->datearray=$this->createDaysforWeek((string)$dt->startOfWeek(),(string)$dt->endOfWeek());
		$display_data->datearrayfills= array_fill_keys($display_data->datearray,'0');
		$display_data->array =  $display_data->secondweek_amount;
		    foreach($display_data->array as $key=>$value)
		    {
		        $display_data->date=$display_data->array[$key]->encod_date;
		        $display_data->secondweekamount=$display_data->array[$key]->secondweekamount;
		        foreach($display_data->datearrayfills as $k => $v)
		        {  
		            if($display_data->date==$k)
		            {
		           $display_data->datearrayfills[$k]=$display_data->secondweekamount;

		            }
		        }
		    }
		$display_data->secondweekdatearray=array();
		    foreach($display_data->datearrayfills as $key =>$value)
		    {
		    $display_data->secondweekdatearray[]=$value;
		    }


		//data for the weekly income per plan       
		$display_data->amount=array();
		    foreach ($display_data->weekly_sales_plan as $key => $value) 
		    {
		        $display_data->amount[]=$value->amount;
		    }

		//net amount in particular secondweek
		$display_data->weeknetamount=array();
		    foreach ($display_data->weeknet_amount as $key => $value) 
		    {
		        $weeknetamount[]=$value->weeknetamount;
		    }
		$display_data->week_sale_amount = array_map( create_function('$value', 'return (int)$value;'),
		            $weeknetamount);
		//net amount in particular month
		$display_data->monthnetamount=array();
		    foreach ($display_data->monthnet_amount as $key => $value) 
		    {
		        $monthnetamount[]=$value->monthnetamount;
		    }

		$display_data->month_sale_amount = array_map( create_function('$value', 'return (int)$value;'),
		            $monthnetamount);
		//total sale a day
		$display_data->daysale=array();
		    foreach ($display_data->day_sale as $key => $value) 
		    {
		        $daysale[]=$value->daysale;

		    }
		$display_data->today_sale_amount = array_map( create_function('$value', 'return (int)$value;'),
		            $daysale);


		//total sale amount
		$display_data->totalsaleamount=array();
		    foreach ($display_data->total_sale_amount as $key => $value) 
		    {
		        $totalsaleamount[]=$value->totalsaleamount;
		        
		    }
		$display_data->total_sale_amount = array_map( create_function('$value', 'return (int)$value;'),
		            $totalsaleamount);

		//total Pending
		$display_data->totalPending=array();
		    foreach ($display_data->total_pending_order as $key => $value) 
		    {
		        $totalPending[]=$value->totalpending;
		        
		    }
		$display_data->total_pending = array_map( create_function('$value', 'return (int)$value;'),
		            $totalPending);


		return $display_data; 
	}
		/**
	 * Method to create a date range for chart display purpose
	 * @return array list of subscription id in array format
	 * */
	function getWeeklySalesPlan(){
		$dt = Carbon::create(2016, 3, 9, 12, 0, 0);
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('count(`axisubs_subscription_id`) as amount') 
			 -> from('#__axisubs_subscriptions')
			 ->where('created_on >= '. $db->q((string)$dt->startOfWeek()) 
			 			.' AND created_on <= '. $db->q((string)$dt->endOfWeek()))
			 ->group('plan_id');
		$db->setQuery($qry);
		$data = $db->loadObjectList();
		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of a particular week sale amount in array format(first week data used for comparison)
	 * */
	function getFirstWeekAmount(){
		$dt = Carbon::create(2016, 3, 9, 12, 0, 0);
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('sum(`total`) as firstweekamount,DATE_FORMAT(created_on, "%Y-%m-%d") encoded_date') 
			 -> from('#__axisubs_subscriptions')
			 ->where('created_on >= '. $db->q((string)$dt->startOfWeek()) 
			 			.' AND created_on <= '. $db->q((string)$dt->endOfWeek()));
			 $qry .= "GROUP BY CAST(`created_on` AS DATE)";
			 
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of a particular week sale amount in array format(second week data used for comparison)
	 * */
	function getSecondWeekAmount(){
		$dt = Carbon::create(2016, 3, 3, 12, 0, 0);
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('sum(`total`) as secondweekamount,DATE_FORMAT(created_on, "%Y-%m-%d") encod_date') 
			 -> from('#__axisubs_subscriptions')
			 ->where('created_on >= '. $db->q((string)$dt->startOfWeek()) 
			 			 .' AND created_on <= '. $db->q((string)$dt->endOfWeek()));
			 $qry .= "GROUP BY CAST(`created_on` AS DATE)";
			 
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of a particular week sale amount in array format
	 * */
	function getWeekNetAmount(){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('sum(`total`) as weeknetamount') 
			 -> from('#__axisubs_subscriptions')
			 ->where('created_on >= '. $db->q('2016-03-06 00:00:00') 
			 			.' AND created_on <= '. $db->q('2016-03-12 23:59:59'));
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of a particular month sale amount in array format
	 * */
	function getMonthNetAmount(){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('sum(`total`) as monthnetamount') 
			 -> from('#__axisubs_subscriptions')
			 ->where('created_on >= '. $db->q('2016-03-01 00:00:00') 
			 			.' AND created_on <= '. $db->q('2016-03-30 23:59:59'));
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
		/**
	 * Method to create a date range for chart display purpose
	 * @return array list of a particular day sale amount in array format
	 * */
		function getDaySale(){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('count(`axisubs_subscription_id`) as daysale') 
			 -> from('#__axisubs_subscriptions');
			 $qry .= " WHERE `created_on` LIKE '2016-03-03%'";
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of Total sale amount in array format
	 * */
	function getTotalSaleAmount(){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('sum(`total`) as totalsaleamount') 
			 -> from('#__axisubs_subscriptions');
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}
	/**
	 * Method to create a date range for chart display purpose
	 * @return array list of pending subscriptions in array format
	 * */
	function getPendingOrder(){
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('count(`axisubs_subscription_id`) as totalpending') 
			 -> from('#__axisubs_subscriptions')
			 ->where('status = '. $db->q('P'));
		$db->setQuery($qry);
		$data = $db->loadObjectList();

		return $data;
	}

	/**
	 * Method to create a date range for chart display purpose
	 * @param string $strDateFrom start day 
	 * @param string $strDateTo end day 
	 * @return array list of dates within the date range in array format
	 * */
	function createDaysforWeek($strDateFrom,$strDateTo)
	{
	    $aryRange=array();

	    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
	    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	    if ($iDateTo>=$iDateFrom)
	    {
	        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
	        while ($iDateFrom<$iDateTo)
	        {
	            $iDateFrom+=86400; // add 24 hours
	            array_push($aryRange,date('Y-m-d',$iDateFrom));
	        }
	    }
	    return $aryRange;
	}

}
