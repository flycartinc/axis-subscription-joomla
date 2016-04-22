<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use Flycart\Axisubs\Admin\Model\Mixin\CarbonHelper;
use Carbon\Carbon;
use JFactory;
use JText;

/**
 * Permission helper.
 */
class Date{
	use CarbonHelper;

	public static $instance = null;
	public static $store_format = null;

	public function __construct($properties=null) {
	}

	/**
	 * get an instance
	 * @param array $config
	 * @return \Flycart\Axisubs\Admin\Helper\Permission
	 * * */
	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}
		
		self::$store_format = Axisubs::config()->get('datetime_format','Y-m-d');

		return self::$instance;
	}

	/**
	 * Method to format the date 
	 * Assume the supplied date is in UTC and format it to local timezone
	 * 
	 * */
	public function get_formatted_date( $local='', $options=array() ) {
		$format = self::$store_format ;
		$tz = JFactory::getConfig()->get('offset');
		
		//Carbon::setLocale('de');

		$date = $this->getDate( $local );
		$date->setTimeZone($tz);
		
		$result = $date->format( $format );

		if(isset($options['format']) && $options['format']) {
			//format option is set.
			$format = isset($options['format']) ? $options['format'] : 'Y-m-d'; 
			$result = $date->format($format);
		}
		return $result;
	}

	/**
	 * An Alias for get_formated_date
	 * */
	public function format( $local, $options=array() ) {
		$date = $this->get_formatted_date($local,$options);
		return $date;
	}

	public function getCarbonDate( $date ='' ){
		return $this->getDate($date);
	}
}