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
}