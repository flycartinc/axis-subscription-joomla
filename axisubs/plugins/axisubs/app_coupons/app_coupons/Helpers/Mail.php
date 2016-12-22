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
 * Mail helper
 */
class Mail{

	public static $instance = null;	

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
		return self::$instance;
	}

	/**
	 * Method to get the mailer object
	 * */
	function getMailer(){
		// get the type of mailer configured from component settings
		$mailer_name = 'Joomla' ;

		if ( $mailer_name == 'Joomla' ){
			$mailer = clone JFactory::getMailer();
			$isHTML = true;
			$mailer->IsHTML($isHTML);
			// Required in order not to get broken characters
			$mailer->CharSet = 'UTF-8';
		}
		//implement more mailers like swiftmailer in future

		return $mailer;
	}
}