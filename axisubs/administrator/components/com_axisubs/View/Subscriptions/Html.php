<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\View\Subscriptions;
defined('_JEXEC') or die;

use JFactory;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Model\Customers;

class Html extends \FOF30\View\DataView\Html
{	
	public function onBeforeBrowse( $tpl = null ){
		parent::onBeforeBrowse($tpl);
	}
}