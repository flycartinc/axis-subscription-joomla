<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

// no direct access
defined('_JEXEC') or die('Restricted access');

use JURI;
use JHtml;
use JFactory;
use JText;

class Strapper {

	public static function addJS() {

	}

	public static function addCSS() {

	}

	public static function getDefaultTemplate() {

		static $tsets;

		if ( !is_array( $tsets ) )
		{
			$tsets = array( );
		}
		$id = 1;
		if(!isset($tsets[$id])) {
			$db = \JFactory::getDBO();
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home=1";
			$db->setQuery( $query );
			$tsets[$id] = $db->loadResult();
		}
		return $tsets[$id];
	}

	static public function sizeFormat($filesize)
	{
		if($filesize > 1073741824) {
			return number_format($filesize / 1073741824, 2)." Gb";
		} elseif($filesize >= 1048576) {
			return number_format($filesize / 1048576, 2)." Mb";
		} elseif($filesize >= 1024) {
			return number_format($filesize / 1024, 2)." Kb";
		} else {
			return $filesize." bytes";
		}
	}
	
	public static function getTimePickerScript($date_format='', $time_format='', $prefix='axisubs', $isAdmin=false) {

		//initialise the date/time picker
		//if($isAdmin) {
			$document =JFactory::getDocument();
			$document->addScript(JUri::root(true).'/media/com_axisubs/js/jquery-ui-timepicker-addon.js');
			$document->addStyleSheet(JURI::root(true).'/media/com_axisubs/css/jquery-ui-custom.css');
		//}

		if(empty($date_format)) {
			$date_format = 'yy-mm-dd';
		}

		if(empty($time_format)) {
			$time_format = 'HH:mm';
		}

		$element_date = $prefix.'_date';
		$element_time = $prefix.'_time';
		$element_datetime = $prefix.'_datetime';

		//localisation
		$currentText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_CURRENT_TEXT'));
		$closeText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_CLOSE_TEXT'));
		$timeOnlyText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_CHOOSE_TIME'));
		$timeText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_TIME'));
		$hourText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_HOUR'));
		$minuteText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_MINUTE'));
		$secondText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_SECOND'));
		$millisecondText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_MILLISECOND'));
		$timezoneText = addslashes(JText::_('LIENITNOW_TIMEPICKER_JS_TIMEZONE'));

		$localisation ="
		currentText: '$currentText',
		closeText: '$closeText',
		timeOnlyTitle: '$timeOnlyText',
		timeText: '$timeText',
		hourText: '$hourText',
		minuteText: '$minuteText',
		secondText: '$secondText',
		millisecText: '$millisecondText',
		timezoneText: '$timezoneText'
		";

		$timepicker_script ="
					if(typeof(axisubs) == 'undefined') {
					var axisubs = {};
							}

							if(typeof(jQuery) != 'undefined') {
							jQuery.noConflict();
							}

							if(typeof(axisubs.jQuery) == 'undefined') {
							axisubs.jQuery = jQuery.noConflict();
							}

							if(typeof(axisubs.jQuery) != 'undefined') {

							(function($) {
								$(document).ready(function(){
								//date, time, datetime
								if ($.browser.msie && $.browser.version == 6) {
									$('.$element_date, .$element_datetime, .$element_time').bgIframe();
								}

								$('.$element_date').datepicker({dateFormat: '$date_format'});
								$('.$element_datetime').datetimepicker({
											dateFormat: '$date_format',
											timeFormat: '$time_format',
											$localisation
								});

								$('.$element_time').timepicker({timeFormat: '$time_format', $localisation});

							});
							})(axisubs.jQuery);
							} 

							";

	return $timepicker_script;

	}
	
	
	
	
	
}
