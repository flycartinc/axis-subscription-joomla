<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Database\Installer;
use FOF30\Model\Model;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Plugin;
use JLoader;
use JPluginHelper;

class Payment extends Model
{	
	/**
     * Gets a list of payment plugins and their titles
     *
     * @param   string  $country    Additional filtering based on the country
     *
     * @return  array
     */
	public function getPaymentPlugins($country = '')
	{
		JLoader::import('joomla.plugin.helper');
		JPluginHelper::importPlugin('axisubs');

		$payment_plugins = Axisubs::plugin()->getPluginsWithEvent( 'onAxisubsGetPaymentPlugins');
		return $payment_plugins;
	}
}
