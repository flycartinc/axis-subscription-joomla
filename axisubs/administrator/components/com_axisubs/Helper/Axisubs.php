<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
namespace Flycart\Axisubs\Admin\Helper;

defined ( '_JEXEC' ) or die ();
use Flycart\Axisubs\Admin\Helper\Config;
use Flycart\Axisubs\Admin\Helper\Permission;
use Flycart\Axisubs\Admin\Helper\Mail;
use Flycart\Axisubs\Admin\Helper\User;
use Flycart\Axisubs\Admin\Helper\Logger;
use Flycart\Axisubs\Admin\Helper\Status;
use Flycart\Axisubs\Admin\Helper\Currency;
use Flycart\Axisubs\Admin\Helper\Plugin;
use Flycart\Axisubs\Admin\Helper\PaymentFactory;
use Flycart\Axisubs\Admin\Helper\Date;
use Flycart\Axisubs\Admin\Helper\Duration;
use Flycart\Axisubs\Admin\Helper\SetSessionData;
use Carbon\Carbon;
/**
 * Axisubs helper.
  */
class Axisubs
{
	public static function config($config=array()) {
		return Config::getInstance($config);
	}

	public static function mail() {
		return Mail::getInstance();
	}

	public static function permission() {
		return Permission::getInstance();
	}

	public static function user() {
		return User::getInstance();
	}
	
	public static function date() {
		return Date::getInstance();
		//return new Carbon();
	}

	public static function logger() {
		return Logger::getInstance();
	}

	public static function currency() {
		return Currency::getInstance();
	}

	public static function status() {
		return Status::getInstance();
	}

	public static function plugin() {
		return Plugin::getInstance();
	}

	public static function payment() {
		return PaymentFactory::getInstance();
	}

	public static function shortcodes(){
		return ShortCodes::getInstance();
	}

	public static function duration(){
		return Duration::getInstance();
	}

	public static function setSessionData() {
		return SetSessionData::getInstance();
	}

	public static function isPro() {
		$isPro = defined('AXISUBS_PRO') ? AXISUBS_PRO : 0;
		return $isPro;
	}

	public static function buildHelpLink($url, $content='app') {

		$source = 'axisubs';		
		$utm_query ='?utm_source='.$source.'&utm_medium=component&utm_campaign=inline&utm_content='.$content;
		$domain = 'https://flycart.org';

		$fullurl = $domain.'/'.$url.$utm_query;
		return $fullurl;
	}

}