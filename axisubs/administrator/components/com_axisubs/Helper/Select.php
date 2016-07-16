<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Helper;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JFactory;
use JFolder;
use JHtml;
use JLoader;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Date;
use Carbon\Carbon;

use CommerceGuys\Addressing\Repository\CountryRepository;
use CommerceGuys\Addressing\Repository\SubdivisionRepository;

defined('_JEXEC') or die;

/**
 * A helper class for drop-down selection boxes
 */
abstract class Select
{

	public static function getLang(){
		$app = JFactory::getApplication();
		return $app->getLanguage()->getTag();
	}

	public static function getSubZones($country_code){
		
		/*$subd_repo = new SubdivisionRepository();
		$lang = self::getLang();
		$list = $subd_repo->getList( $country_code );
		return $list;*/
		return self::getZones($country_code);
	}

	public static function getZones( $country_code = '' ){
		if (empty($country_code))
			return array();
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('concat(z.country_code,"-",z.zone_code) zone_code, zone_name') 
			 -> from('#__axisubs_zones z')
		     -> where('country_code='.$db->q($country_code) );
		$db->setQuery($qry);
		$items = $db->loadAssocList('zone_code');
		$list = array();
		foreach ($items as $k => $item) {
			$list[$k] = $item['zone_name'];
		}
		return $list;
	}

	/**
	 * Maps the two letter codes to country names (in English)
	 *
	 * @var  array
	 */
	public static $countries = array(
		''   => '----',
		'AD' => 'Andorra',
		'AE' => 'United Arab Emirates',
		'AF' => 'Afghanistan',
		'AG' => 'Antigua and Barbuda',
		'AI' => 'Anguilla',
		'AL' => 'Albania',
		'AM' => 'Armenia',
		'AO' => 'Angola',
		'AQ' => 'Antarctica',
		'AR' => 'Argentina',
		'AS' => 'American Samoa',
		'AT' => 'Austria',
		'AU' => 'Australia',
		'AW' => 'Aruba',
		'AX' => 'Aland Islands',
		'AZ' => 'Azerbaijan',
		'BA' => 'Bosnia and Herzegovina',
		'BB' => 'Barbados',
		'BD' => 'Bangladesh',
		'BE' => 'Belgium',
		'BF' => 'Burkina Faso',
		'BG' => 'Bulgaria',
		'BH' => 'Bahrain',
		'BI' => 'Burundi',
		'BJ' => 'Benin',
		'BL' => 'Saint Barthélemy',
		'BM' => 'Bermuda',
		'BN' => 'Brunei Darussalam',
		'BO' => 'Bolivia, Plurinational State of',
		'BQ' => 'Bonaire, Saint Eustatius and Saba',
		'BR' => 'Brazil',
		'BS' => 'Bahamas',
		'BT' => 'Bhutan',
		'BV' => 'Bouvet Island',
		'BW' => 'Botswana',
		'BY' => 'Belarus',
		'BZ' => 'Belize',
		'CA' => 'Canada',
		'CC' => 'Cocos (Keeling) Islands',
		'CD' => 'Congo, the Democratic Republic of the',
		'CF' => 'Central African Republic',
		'CG' => 'Congo',
		'CH' => 'Switzerland',
		'CI' => 'Cote d\'Ivoire',
		'CK' => 'Cook Islands',
		'CL' => 'Chile',
		'CM' => 'Cameroon',
		'CN' => 'China',
		'CO' => 'Colombia',
		'CR' => 'Costa Rica',
		'CU' => 'Cuba',
		'CV' => 'Cape Verde',
		'CW' => 'Curaçao',
		'CX' => 'Christmas Island',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DE' => 'Germany',
		'DJ' => 'Djibouti',
		'DK' => 'Denmark',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'DZ' => 'Algeria',
		'EC' => 'Ecuador',
		'EE' => 'Estonia',
		'EG' => 'Egypt',
		'EH' => 'Western Sahara',
		'ER' => 'Eritrea',
		'ES' => 'Spain',
		'ET' => 'Ethiopia',
		'FI' => 'Finland',
		'FJ' => 'Fiji',
		'FK' => 'Falkland Islands (Malvinas)',
		'FM' => 'Micronesia, Federated States of',
		'FO' => 'Faroe Islands',
		'FR' => 'France',
		'GA' => 'Gabon',
		'GB' => 'United Kingdom',
		'GD' => 'Grenada',
		'GE' => 'Georgia',
		'GF' => 'French Guiana',
		'GG' => 'Guernsey',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GL' => 'Greenland',
		'GM' => 'Gambia',
		'GN' => 'Guinea',
		'GP' => 'Guadeloupe',
		'GQ' => 'Equatorial Guinea',
		'GR' => 'Greece',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'GT' => 'Guatemala',
		'GU' => 'Guam',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HK' => 'Hong Kong',
		'HM' => 'Heard Island and McDonald Islands',
		'HN' => 'Honduras',
		'HR' => 'Croatia',
		'HT' => 'Haiti',
		'HU' => 'Hungary',
		'ID' => 'Indonesia',
		'IE' => 'Ireland',
		'IL' => 'Israel',
		'IM' => 'Isle of Man',
		'IN' => 'India',
		'IO' => 'British Indian Ocean Territory',
		'IQ' => 'Iraq',
		'IR' => 'Iran, Islamic Republic of',
		'IS' => 'Iceland',
		'IT' => 'Italy',
		'JE' => 'Jersey',
		'JM' => 'Jamaica',
		'JO' => 'Jordan',
		'JP' => 'Japan',
		'KE' => 'Kenya',
		'KG' => 'Kyrgyzstan',
		'KH' => 'Cambodia',
		'KI' => 'Kiribati',
		'KM' => 'Comoros',
		'KN' => 'Saint Kitts and Nevis',
		'KP' => 'Korea, Democratic People\'s Republic of',
		'KR' => 'Korea, Republic of',
		'KW' => 'Kuwait',
		'KY' => 'Cayman Islands',
		'KZ' => 'Kazakhstan',
		'LA' => 'Lao People\'s Democratic Republic',
		'LB' => 'Lebanon',
		'LC' => 'Saint Lucia',
		'LI' => 'Liechtenstein',
		'LK' => 'Sri Lanka',
		'LR' => 'Liberia',
		'LS' => 'Lesotho',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'LV' => 'Latvia',
		'LY' => 'Libyan Arab Jamahiriya',
		'MA' => 'Morocco',
		'MC' => 'Monaco',
		'MD' => 'Moldova, Republic of',
		'ME' => 'Montenegro',
		'MF' => 'Saint Martin (French part)',
		'MG' => 'Madagascar',
		'MH' => 'Marshall Islands',
		'MK' => 'Macedonia, the former Yugoslav Republic of',
		'ML' => 'Mali',
		'MM' => 'Myanmar',
		'MN' => 'Mongolia',
		'MO' => 'Macao',
		'MP' => 'Northern Mariana Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MS' => 'Montserrat',
		'MT' => 'Malta',
		'MU' => 'Mauritius',
		'MV' => 'Maldives',
		'MW' => 'Malawi',
		'MX' => 'Mexico',
		'MY' => 'Malaysia',
		'MZ' => 'Mozambique',
		'NA' => 'Namibia',
		'NC' => 'New Caledonia',
		'NE' => 'Niger',
		'NF' => 'Norfolk Island',
		'NG' => 'Nigeria',
		'NI' => 'Nicaragua',
		'NL' => 'Netherlands',
		'NO' => 'Norway',
		'NP' => 'Nepal',
		'NR' => 'Nauru',
		'NU' => 'Niue',
		'NZ' => 'New Zealand',
		'OM' => 'Oman',
		'PA' => 'Panama',
		'PE' => 'Peru',
		'PF' => 'French Polynesia',
		'PG' => 'Papua New Guinea',
		'PH' => 'Philippines',
		'PK' => 'Pakistan',
		'PL' => 'Poland',
		'PM' => 'Saint Pierre and Miquelon',
		'PN' => 'Pitcairn',
		'PR' => 'Puerto Rico',
		'PS' => 'Palestinian Territory, Occupied',
		'PT' => 'Portugal',
		'PW' => 'Palau',
		'PY' => 'Paraguay',
		'QA' => 'Qatar',
		'RE' => 'Reunion',
		'RO' => 'Romania',
		'RS' => 'Serbia',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'SA' => 'Saudi Arabia',
		'SB' => 'Solomon Islands',
		'SC' => 'Seychelles',
		'SD' => 'Sudan',
		'SE' => 'Sweden',
		'SG' => 'Singapore',
		'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
		'SI' => 'Slovenia',
		'SJ' => 'Svalbard and Jan Mayen',
		'SK' => 'Slovakia',
		'SL' => 'Sierra Leone',
		'SM' => 'San Marino',
		'SN' => 'Senegal',
		'SO' => 'Somalia',
		'SR' => 'Suriname',
		'SS' => 'South Sudan',
		'ST' => 'Sao Tome and Principe',
		'SV' => 'El Salvador',
		'SX' => 'Sint Maarten',
		'SY' => 'Syrian Arab Republic',
		'SZ' => 'Swaziland',
		'TC' => 'Turks and Caicos Islands',
		'TD' => 'Chad',
		'TF' => 'French Southern Territories',
		'TG' => 'Togo',
		'TH' => 'Thailand',
		'TJ' => 'Tajikistan',
		'TK' => 'Tokelau',
		'TL' => 'Timor-Leste',
		'TM' => 'Turkmenistan',
		'TN' => 'Tunisia',
		'TO' => 'Tonga',
		'TR' => 'Turkey',
		'TT' => 'Trinidad and Tobago',
		'TV' => 'Tuvalu',
		'TW' => 'Taiwan',
		'TZ' => 'Tanzania, United Republic of',
		'UA' => 'Ukraine',
		'UG' => 'Uganda',
		'UM' => 'United States Minor Outlying Islands',
		'US' => 'United States',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VA' => 'Holy See (Vatican City State)',
		'VC' => 'Saint Vincent and the Grenadines',
		'VE' => 'Venezuela, Bolivarian Republic of',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'VN' => 'Viet Nam',
		'VU' => 'Vanuatu',
		'WF' => 'Wallis and Futuna',
		'WS' => 'Samoa',
		'YE' => 'Yemen',
		'YT' => 'Mayotte',
		'ZA' => 'South Africa',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);

	/**
	 * Maps countries to state short codes and names
	 *
	 * @var  array
	 */
	public static $states = array();

	/**
	 * Returns a list of custom field types
	 *
	 * @return  array  type => description
	 */
	public static function getFieldTypes()
	{
		$fieldTypes = array();

		JLoader::import('joomla.filesystem.folder');

		$basepath = JPATH_ADMINISTRATOR . '/components/com_axisubs/CustomField';

		$files = JFolder::files($basepath, '.php');

		foreach ($files as $file)
		{
			if ($file === 'Base.php')
			{
				continue;
			}

			$type      = basename($file, '.php');
			$className = 'Akeeba\\Subscriptions\\Admin\\CustomField\\' . $type;

			if (class_exists($className))
			{
				$fieldTypes[ strtolower($type) ] = JText::_('COM_AKEEBASUBS_CUSTOMFIELDS_FIELD_TYPE_' . strtoupper($type));
			}
		}

		return $fieldTypes;
	}

	/**
	 * Returns a list of all countries except the empty option (no country)
	 *
	 * @return  array
	 */
	public static function getCountriesForHeader()
	{
		static $countries = array();

		if (empty($countries))
		{
			$countries = self::getCountries();
			unset($countries['']);
		}

		return $countries;
	}

	/**
	 * Returns a list of all countries including the empty option (no country)
	 *
	 * @return  array
	 */
	public static function getCountries()
	{	
		/*$country_repo = new CountryRepository();
		$lang = self::getLang();
		$countries = $country_repo->getList($lang);
		*/
	
		return self::$countries;
	}

	/**
	 * Returns a list of all states
	 *
	 * @return  array
	 */
	public static function getStates()
	{
		static $states = array();

		if (empty($states))
		{
			$states = array();

			foreach (self::$states as $country => $s)
			{
				$states = array_merge($states, $s);
			}
		}

		return $states;
	}

	public static function calendar($name,$value,$options=array()){
		$id = isset($options['id']) ? $options['id']: self::clean($name);
		$nullDate = JFactory::getDbo()->getNullDate();
		if($value == $nullDate || empty($value)) {
			$value = $nullDate;
		}
		$format = isset($options['format']) ? $options['format']: '%Y-%m-%d' ; // %H:%M:%S
		return JHtml::_('calendar', $value, $name, $id, $format, $options);
	}

	public static function getDateRangeFilterOptions(){

		$date_helper = Axisubs::date() ;

		$date_filter_options	= array();

		$date_filter_option	= array();
		$date_filter_option['key']			=	'nofilter';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_ALLSUBS');
		$date_filter_option['start_date']	=	'';
		$date_filter_option['end_date']		=	'';
		$date_filter_options[] 				= 	$date_filter_option ;

		$start = $date_helper->getCarbonDate();
		$end = $start->copy();
		$date_filter_option	= array();
		$date_filter_option['key']			=	'today';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_TODAY');
		$date_filter_option['start_date']	=	$start->toDateString();
		$date_filter_option['end_date']		=	$end->toDateString();
		$date_filter_options[] 				= 	$date_filter_option ;


		// yesterday
		$start->subDay();
		$date_filter_option	= array();
		$date_filter_option['key']			=	'yesterday';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_YESTERDAY');
		$date_filter_option['start_date']	=	$start->toDateString();
		$date_filter_option['end_date']		=	$start->toDateString();
		$date_filter_options[] 				= 	$date_filter_option ;

		// add 2 more day
		$start->subDays(2);
		$date_filter_option	= array();
		$date_filter_option['key']			=	'last3days';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_LAST3DAYS');
		$date_filter_option['start_date']	=	$start->toDateString();
		$date_filter_option['end_date']		=	$end->toDateString();
		$date_filter_options[] 				= 	$date_filter_option ;
		
		Carbon::setWeekStartsAt(Carbon::SUNDAY);
		Carbon::setWeekEndsAt(Carbon::SATURDAY);

		// this week
		$today = $date_helper->getCarbonDate();
		$date_filter_option	= array();
		$date_filter_option['key']			=	'thisweek';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_THIS_WEEK');
		$date_filter_option['start_date']	=	$today->startOfWeek()->toDateString();
		$date_filter_option['end_date']		=	$today->endOfWeek()->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;

		// last week
		$last_week = $today->copy(); 
		$last_week = $last_week->subDays(7);
		$date_filter_option	= array();
		$date_filter_option['key']			=	'lastweek';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_LAST_WEEK');
		$date_filter_option['start_date']	=	$last_week->startOfWeek()->toDateString();
		$date_filter_option['end_date']		=	$last_week->endOfWeek()->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;

		/*// past 2 weeks
		$date_filter_option	= array();
		$date_filter_option['key']			=	'past2weeks';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_LAST_WEEK');
		$date_filter_option['start_date']	=	$last_week->startOfWeek()->toDateString();
		$date_filter_option['end_date']		=	$today->endOfWeek()->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;*/

		// last 15 days
		$last_15 = $today->copy();
		$last_15->subDays(15);
		$date_filter_option	= array();
		$date_filter_option['key']			=	'last15days';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_LAST15DAYS');
		$date_filter_option['start_date']	=	$last_15->toDateString();
		$date_filter_option['end_date']		=	$today->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;

		// last month
		$last_month = $today->copy();
		$last_month->subMonth();
		$date_filter_option	= array();
		$date_filter_option['key']			=	'lastmonth';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_LAST_MONTH');
		$date_filter_option['start_date']	=	$last_month->startOfMonth()->toDateString();
		$date_filter_option['end_date']		=	$last_month->endOfMonth()->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;

		// this month
		$date_filter_option	= array();
		$date_filter_option['key']			=	'thismonth';
		$date_filter_option['value']		=	JText::_('COM_AXISUBS_DATE_FILTER_OPTION_THIS_MONTH');
		$date_filter_option['start_date']	=	$today->startOfMonth()->toDateString();
		$date_filter_option['end_date']		=	$today->endOfMonth()->toDateString();
		$date_filter_options[] 				=   $date_filter_option ;

		return $date_filter_options;
	}


	public static function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}


	/**
	 * Returns a list of known invoicing extensions supported by plugins
	 *
	 * @return  array  extension => title
	 */
	public static function getInvoiceExtensions()
	{
		static $invoiceExtensions = null;

		if (is_null($invoiceExtensions))
		{
			$source = Container::getInstance('com_axisubs')->factory
				->model('Invoices')->tmpInstance()
				->getExtensions(0);
			$invoiceExtensions = array();

			if (!empty($source))
			{
				foreach ($source as $item)
				{
					$invoiceExtensions[ $item['extension'] ] = $item['title'];
				}
			}
		}

		return $invoiceExtensions;
	}

	/**
	 * Translate a two letter country code into the country name (in English). If the country is unknown the country
	 * code itself is returned.
	 *
	 * @param   string  $cCode  The country code
	 *
	 * @return  string  The name of the country or, of it's not known, the country code itself.
	 */
	public static function decodeCountry($cCode)
	{
		$countries = self::getCountries();
		if (array_key_exists($cCode, $countries))
		{
			return $countries[ $cCode ];
		}
		else
		{
			return $cCode;
		}
	}

	/**
	 * Translate a two letter country code into the country name (in English). If the country is unknown three em-dashes
	 * are returned. This is different to decode country which returns the country code in this case.
	 *
	 * @param   string  $cCode  The country code
	 *
	 * @return  string  The name of the country or, of it's not known, the country code itself.
	 */
	public static function formatCountry($cCode = '')
	{
		$name = self::decodeCountry($cCode);

		if ($name == $cCode)
		{
			$name = '&mdash;';
		}

		return $name;
	}

	/**
	 * Translate the short state code into the full, human-readable state name. If the state is unknown three em-dashes
	 * are returned instead.
	 *
	 * @param   string  $state  The state code
	 *
	 * @return  string  The human readable state name
	 */
	public static function formatState($state)
	{
		$name = '&mdash;';

		foreach (self::$states as $country => $states)
		{
			if (array_key_exists($state, $states))
			{
				$name = $states[ $state ];
			}
		}

		return $name;
	}

	/**
	 * Return a generic drop-down list
	 *
	 * @param   array   $list      An array of objects, arrays, or scalars.
	 * @param   string  $name      The value of the HTML name attribute.
	 * @param   mixed   $attribs   Additional HTML attributes for the <select> tag. This
	 *                             can be an array of attributes, or an array of options. Treated as options
	 *                             if it is the last argument passed. Valid options are:
	 *                             Format options, see {@see JHtml::$formatOptions}.
	 *                             Selection options, see {@see JHtmlSelect::options()}.
	 *                             list.attr, string|array: Additional attributes for the select
	 *                             element.
	 *                             id, string: Value to use as the select element id attribute.
	 *                             Defaults to the same as the name.
	 *                             list.select, string|array: Identifies one or more option elements
	 *                             to be selected, based on the option key values.
	 * @param   mixed   $selected  The key that is selected (accepts an array or a string).
	 * @param   string  $idTag     Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list
	 */
	protected static function genericlist($list, $name, $attribs = null, $selected = null, $idTag = null)
	{
		if (empty($attribs))
		{
			$attribs = null;
		}
		else
		{
			$temp = '';

			foreach ($attribs as $key => $value)
			{
				$temp .= ' ' . $key . '="' . $value . '"';
			}

			$attribs = $temp;
		}

		return JHtml::_('select.genericlist', $list, $name, $attribs, 'value', 'text', $selected, $idTag);
	}

	/**
	 * Generates an HTML radio list.
	 *
	 * @param   array    $list       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the <select> tag
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idTag      Value of the field id or null by default
	 *
	 * @return  string  HTML for the select list
	 */
	protected static function genericradiolist($list, $name, $attribs = null, $selected = null, $idTag = null)
	{
		if (empty($attribs))
		{
			$attribs = null;
		}
		else
		{
			$temp = '';

			foreach ($attribs as $key => $value)
			{
				$temp .= $key . ' = "' . $value . '"';
			}

			$attribs = $temp;
		}

		return JHtml::_('select.radiolist', $list, $name, $attribs, 'value', 'text', $selected, $idTag);
	}

	/**
	 * Generates a yes/no drop-down list.
	 *
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 * @param   string  $selected  The key that is selected
	 *
	 * @return  string  HTML for the list
	 */
	public static function booleanlist($name, $attribs = null, $selected = null, $idTag = null )
	{
		$options = array(
			JHtml::_('select.option', '0', JText::_('JNo')),
			JHtml::_('select.option', '1', JText::_('JYes'))
		);
		$attribs['class'] = 'btn btn-group';
		//return self::genericlist($options, $name, $attribs, $selected, $name);
		return JHtml::_('select.radiolist', $options, $name, $attribs, 'value', 'text', $selected, $idTag);
	}

	/**
	 * Returns a drop-down selection box for countries. Some special attributes:
	 *
	 * show     An array of country codes to display. Takes precedence over hide.
	 * hide     An array of country codes to hide.
	 *
	 * @param   string  $selected  Selected country code
	 * @param   string  $id        Field name and ID
	 * @param   array   $attribs   Field attributes
	 *
	 * @return string
	 */
	public static function countries($selected = null, $id = 'country', $attribs = array())
	{
		// Get the raw list of countries
		$options   = array();
		$countries = self::getCountries();
		asort($countries);

		// Parse show / hide options

		// -- Initialisation
		$show = array();
		$hide = array();

		// -- Parse the show attribute
		if (isset($attribs['show']))
		{
			$show = trim($attribs['show']);

			if (!empty($show))
			{
				$show = explode(',', $show);
			}
			else
			{
				$show = array();
			}

			unset($attribs['show']);
		}

		// -- Parse the hide attribute
		if (isset($attribs['hide']))
		{
			$hide = trim($attribs['hide']);

			if (!empty($hide))
			{
				$hide = explode(',', $hide);
			}
			else
			{
				$hide = array();
			}

			unset($attribs['hide']);
		}

		// -- If $show is not empty, filter the countries
		if (count($show))
		{
			$temp = array();

			foreach ($show as $key)
			{
				if (array_key_exists($key, $countries))
				{
					$temp[ $key ] = $countries[ $key ];
				}
			}

			asort($temp);
			$countries = $temp;
		}

		// -- If $show is empty but $hide is not, filter the countries
		elseif (count($hide))
		{
			$temp = array();

			foreach ($countries as $key => $v)
			{
				if (!in_array($key, $hide))
				{
					$temp[ $key ] = $v;
				}
			}

			asort($temp);
			$countries = $temp;
		}

		foreach ($countries as $code => $name)
		{
			$options[] = JHtml::_('select.option', $code, $name);
		}

		return self::genericlist($options, $id, $attribs, $selected, $id);
	}

	/**
	 * Returns a drop-down box of states grouped by country
	 *
	 * @param   string  $selected  Short code of the already selected state
	 * @param   string  $id        Field name and ID
	 * @param   array   $attribs   Attributes
	 *
	 * @return  string  The HTML of the drop-down list
	 */
	public static function states($selected = null, $id = 'state', $attribs = array())
	{
		$data = array();

		foreach (self::$states as $country => $states)
		{
			$data[$country] = [
				'id' => \JApplicationHelper::stringURLSafe($country),
				'text' => $country,
				'items' => []
			];

			foreach ($states as $code => $name)
			{
				$data[$country]['items'][] = JHtml::_('select.option', $code, $name);
			}
		}

		return JHtml::_('select.groupedlist', $data, $id, [
			'id' =>$id,
			'group.id' => 'id',
			'list.attr' => $attribs,
			'list.select' => $selected
		]);
	}

	/**
	 * Displays a list of the available user groups.
	 *
	 * @param   string   $name      The form field name.
	 * @param   string   $selected  The name of the selected section.
	 * @param   array    $attribs   Additional attributes to add to the select field.
	 *
	 * @return  string   The HTML for the list
	 */
	public static function usergroups($name = 'usergroups', $selected = '', $attribs = array())
	{
		return JHtml::_('access.usergroup', $name, $selected, $attribs, false);
	}

	/**
	 * Generates a Published/Unpublished drop-down list.
	 *
	 * @param   string  $selected  The key that is selected (0 = unpublished / 1 = published)
	 * @param   string  $id        The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function published($selected = null, $id = 'enabled', $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', null, '- ' . JText::_('COM_AKEEBASUBS_COMMON_SELECTSTATE') . ' -');
		$options[] = JHtml::_('select.option', 0, JText::_('JUNPUBLISHED'));
		$options[] = JHtml::_('select.option', 1, JText::_('JPUBLISHED'));

		return self::genericlist($options, $id, $attribs, $selected, $id);
	}

	/**
	 * Generates a drop-down list for the available languages of a multi-language site.
	 *
	 * @param   string  $selected  The key that is selected
	 * @param   string  $id        The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function languages($selected = null, $id = 'language', $attribs = array())
	{
		JLoader::import('joomla.language.helper');
		$languages = \JLanguageHelper::getLanguages('lang_code');
		$options   = array();
		$options[] = JHtml::_('select.option', '*', JText::_('JALL_LANGUAGE'));

		if (!empty($languages))
		{
			foreach ($languages as $key => $lang)
			{
				$options[] = JHtml::_('select.option', $key, $lang->title);
			}
		}

		return self::genericlist($options, $id, $attribs, $selected, $id);
	}

	/**
	 * Generates a drop-down list for the available subscription Payment states.
	 *
	 * @param   string  $selected  The key that is selected
	 * @param   string  $id        The value of the HTML name attribute
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function statuses($selected = null, $id = 'state', $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', '', '- ' . JText::_('AXISUBS_SUBSCRIPTION_STATUS') . ' -');

		$statuses = Status::getList();

		foreach ($statuses as $status => $label )
		{
			$options[] = JHtml::_('select.option', $status, $label );
		}

		return self::genericlist($options, $id, $attribs, $selected, $id);
	}

	/**
	 * Generates a drop-down list for the available coupon types.
	 *
	 * @param   string  $name      The value of the HTML name attribute
	 * @param   string  $selected  The key that is selected
	 * @param   array   $attribs   Additional HTML attributes for the <select> tag
	 *
	 * @return  string  HTML for the list
	 */
	public static function coupontypes($name = 'type', $selected = 'value', $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', '', '- ' . JText::_('COM_AKEEBASUBS_COMMON_SELECT') . ' -');
		$options[] = JHtml::_('select.option', 'value', JText::_('COM_AKEEBASUBS_COUPON_TYPE_VALUE'));
		$options[] = JHtml::_('select.option', 'percent', JText::_('COM_AKEEBASUBS_COUPON_TYPE_PERCENT'));

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}


	/**
	 * Drop down list of invoice extensions
	 *
	 * @param   string  $name      The field's name
	 * @param   string  $selected  Pre-selected value
	 * @param   array   $attribs   Field attributes
	 *
	 * @return  string  The HTML of the drop-down
	 */
	public static function invoiceextensions($name = 'extension', $selected = '', $attribs = array())
	{
		/** @var \Akeeba\Subscriptions\Admin\Model\Invoices $model */
		$model = Container::getInstance('com_axisubs')->factory
			->model('Invoices')->tmpInstance();

		$options = $model->getExtensions(1);
		$option = JHtml::_('select.option', '', '- ' . JText::_('COM_AKEEBASUBS_COMMON_SELECT') . ' -');
		array_unshift($options, $option);

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}

	/**
	 * Drop down list of VIES registration flag
	 *
	 * @param   string  $name      The field's name
	 * @param   int     $selected  Pre-selected value
	 * @param   array   $attribs   Field attributes
	 *
	 * @return  string  The HTML of the drop-down
	 */
	public static function viesregistered($name = 'viesregistered', $selected = 0, $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', '0', JText::_('COM_AKEEBASUBS_SUBSCRIPTIONS_USER_VIESREGISTERED_NO'));
		$options[] = JHtml::_('select.option', '1', JText::_('COM_AKEEBASUBS_SUBSCRIPTIONS_USER_VIESREGISTERED_YES'));
		$options[] = JHtml::_('select.option', '2', JText::_('COM_AKEEBASUBS_SUBSCRIPTIONS_USER_VIESREGISTERED_FORCEYES'));

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}

	/**
	 * Drop down list of Is Business preference for invoice templates
	 *
	 * @param   string  $name      The field's name
	 * @param   int     $selected  Pre-selected value
	 * @param   array   $attribs   Field attributes
	 *
	 * @return  string  The HTML of the drop-down
	 */
	public static function invoicetemplateisbusines($name = 'isbusiness', $selected = - 1, $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', '-1', JText::_('COM_AKEEBASUBS_INVOICETEMPLATES_FIELD_ISBUSINESS_INDIFFERENT'));
		$options[] = JHtml::_('select.option', '0', JText::_('COM_AKEEBASUBS_INVOICETEMPLATES_FIELD_ISBUSINESS_PERSONAL'));
		$options[] = JHtml::_('select.option', '1', JText::_('COM_AKEEBASUBS_INVOICETEMPLATES_FIELD_ISBUSINESS_BUSINESS'));

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}

	/**
	 * Drop down list of CSV delimiter preference
	 *
	 * @param   string  $name      The field's name
	 * @param   int     $selected  Pre-selected value
	 * @param   array   $attribs   Field attributes
	 *
	 * @return  string  The HTML of the drop-down
	 */
	public static function csvdelimiters($name = 'csvdelimiters', $selected = 1, $attribs = array())
	{
		$options   = array();
		$options[] = JHtml::_('select.option', '1', 'abc, def');
		$options[] = JHtml::_('select.option', '2', 'abc; def');
		$options[] = JHtml::_('select.option', '3', '"abc"; "def"');
		$options[] = JHtml::_('select.option', '-99', JText::_('COM_AKEEBASUBS_IMPORT_DELIMITERS_CUSTOM'));

		return self::genericlist($options, $name, $attribs, $selected, $name);
	}

	/**
	 * Method to get the list of email types
	 * The list of triggers for which an email should be sent as key value pairs
	 * @return array list of triggers
	 * */
	public static function getTriggersList(){
		$types = array();

		$types['CustomerSignUp'] 				= JText::_('AXISUBS_EMAIL_TYPE_CUSTOMER_SIGNEDUP');
		$types['SubscriptionCreated']  			= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_CREATED');
		$types['SubscriptionRenewalAttempted'] 	= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_RENEWAL_ATTEMPTED');
		$types['SubscriptionRenewalPaid'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_RENEWAL_PAID');
		$types['SubscriptionTrialPaid'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_TRIAL_PAID');
		$types['SubscriptionActivePaid'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_ACTIVE_PAID');
		$types['SubscriptionPaymentSuccess'] 	= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_PAYMENT_SUCCESSFUL');
		$types['SubscriptionPaymentFailed'] 	= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_PAYMENT_FAILED');
		$types['SubscriptionPaymentPending'] 	= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_PAYMENT_PENDING');
		$types['SubscriptionMarkedActive'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_MARKED_ACTIVE');
		$types['SubscriptionMarkedPending']		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_MARKED_PENDING');
		$types['SubscriptionCancelled'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_CANCELLED');
		$types['SubscriptionDeleted'] 			= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_DELETED');
		$types['SubscriptionMarkedRenewal'] 	= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_MARKED_RENEWAL');
		$types['SubscriptionExpired'] 			= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_EXPIRED');
		$types['SubscriptionTrialStarted'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_TRIAL_STARTED');
		$types['SubscriptionTrialEnded'] 		= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_TRIAL_ENDED');
		$types['BeforeSubscriptionStatusUpdate']= JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_BEFORE_STATUS_UPDATE');
		$types['AfterSubscriptionStatusUpdate'] = JText::_('AXISUBS_EMAIL_TYPE_SUBSCRIPTION_AFTER_STATUS_UPDATE');

		return $types;
	}

	public static function recipientShortCodeOptions(){

		$options = Axisubs::shortcodes()->getRecipientShortCodes();
		return $options;

	}

	/**
	 * Method to display the shortcode list 
	 * */
	public static function recipientShortCodes( $name, $attribs=array() ){
		
		$options = Axisubs::shortcodes()->getRecipientShortCodes();

		$attribs['id'] = $name;
		$attribs['size'] = 40;
		$attribs['multiple'] = 'multiple';
		$attribs['style'] = 'height:120px';

		return self::genericlist( $options, $name, $attribs, '', $name);
	}	


	/**
	 * Method to display the shortcode list 
	 * */
	public static function shortcodes( $name, $attribs=array() ){
		
		$options = Axisubs::shortcodes()->getAllShortCodesOptions();

		$attribs['id'] = $name;
		$attribs['size'] = 40;
		$attribs['multiple'] = 'multiple';
		$attribs['style'] = 'height:600px';

		return self::genericlist( $options, $name, $attribs, '', $name);
	}	

	public static function getAllPaymentMethods()
	{
		/** @var PaymentMethods $pluginsModel */
		$pluginsModel = Container::getInstance('com_axisubs')->factory
			->model('Payment')->tmpInstance();

		$plugins = $pluginsModel->getPaymentPlugins();

		$ret = [];

		foreach ($plugins as $plugin)
		{
			$ret[$plugin->element ] = $plugin->name;
		}
		return $ret;
	}

	/**
	 * Returns the current Akeeba Subscriptions container object
	 *
	 * @return  Container
	 */
	protected static function getContainer()
	{
		static $container = null;

		if (is_null($container))
		{
			$container = Container::getInstance('com_axisubs');
		}

		return $container;
	}
}

// Load the states from the database
if(!function_exists('akeebasubsHelperSelect_init'))
{
	function akeebasubsHelperSelect_init()
	{
		/** @var States $model */
		$model                = Container::getInstance('com_axisubs')->factory->model('Zones')->tmpInstance();
		$rawstates            = $model->enabled(1)->orderByLabels(1)->get(true);

		$states               = array();
		$current_country      = '';
		$current_country_name = 'N/A';
		$current_states       = array('' => 'N/A');

		/** @var States $rawstate */
		foreach ($rawstates as $rawstate)
		{
			// Note: you can't use $rawstate->state, it gets the model state
			$rawstate_state = $rawstate->getFieldValue('axisubs_zone_id', null);

			if ($rawstate->country_code != $current_country)
			{
				if (!empty($current_country_name))
				{
					$states[ $current_country_name ] = $current_states;
					$current_states                  = array();
					$current_country                 = '';
					$current_country_name            = '';
				}

				if (empty($rawstate->country_code) || empty($rawstate_state) || empty($rawstate->zone_name))
				{
					continue;
				}

				$current_country      = $rawstate->country_code;
				if (isset(Select::$countries[ $current_country ])) {
					$current_country_name = Select::$countries[ $current_country ];	
				}				
			}

			$current_states[ $rawstate_state ] = $rawstate->zone_name;
		}

		if (!empty($current_country_name))
		{
			$states[ $current_country_name ] = $current_states;
		}

		Select::$states = $states;
	}

	akeebasubsHelperSelect_init();
}