<?php
/**
 * @package   App EU Vat - Axis Subscription
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/AppModel.php');
use Flycart\Axisubs\Admin\Helper\AppModel;

class AxisubsModelAppEUVat extends AppModel
{
	public $_element = 'app_eu_vat';

	/**
	 * to get EU Countries
	 * */
	public function getEUCountries() {

		return array(
			'AT' => 'AT', //Austria
			'BE' => 'BE', //Belgium
			'BG' => 'BG', //Bulgaria
			'CY' => 'CY', //Cyprus
			'CZ' => 'CZ', //Czech Republic
			'HR' => 'HR', //Croatia
			'DK' => 'DK', //Denmark
			'EE' => 'EE', //Estonia
			'FI' => 'FI', //Finland
			'FR' => 'FR', //France
			'FX' => 'FR', //France métropolitaine
			'DE' => 'DE', //Germany
			'GR' => 'EL', //Greece
			'HU' => 'HU', //Hungary
			'IE' => 'IE', //Irland
			'IT' => 'IT', //Italy
			'LV' => 'LV', //Latvia
			'LT' => 'LT', //Lithuania
			'LU' => 'LU', //Luxembourg
			'MT' => 'MT', //Malta
			'NL' => 'NL', //Netherlands
			'PL' => 'PL', //Poland
			'PT' => 'PT', //Portugal
			'RO' => 'RO', //Romania
			'SK' => 'SK', //Slovakia
			'SI' => 'SI',  //Slovania
			'ES' => 'ES', //Spain
			'SE' => 'SE', //Sweden
			'GB' => 'GB' //United Kingdom
		);
	}

	/**
	 * Validate Vat Number
	 * */
	public function validateVatNumber($country_code, $number){
		$org_number = $number;
		$number = str_replace($country_code, "", $org_number);
		$status = 0;
		if(!class_exists('SoapClient')) {
			require_once(JPATH_SITE.'/plugins/axisubs/app_eu_vat/library/class.euvat.php');
			$vatValidation = new vatValidation( array('debug' => false));
			if($vatValidation->check($country_code, $number)) {
				$status = 1;
			} else {
				$status = 0;
			}
		} else {
			$response = file_get_contents('http://ec.europa.eu/taxation_customs/vies/viesquer.do?ms=' . $country_code . '&iso=' .$country_code. '&vat=' . $number);
			if (preg_match('/\bvalid VAT number\b/i', $response)) {
				$status = 1;
			}

			if (preg_match('/\binvalid VAT number\b/i', $response)) {
				$status = 0;
			}
		}

		return $status;
	}
}