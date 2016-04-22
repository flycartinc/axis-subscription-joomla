<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use JUri;
use JText;

class Currency extends DataController
{
	protected function onBrowse() {
		$model = $this->getModel();
		//$model->updateCurrencies(false);
		
		return parent::onBeforeBrowse();
	}
}