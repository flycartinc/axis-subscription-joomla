<?php
/**
 * @package   Axisubs
 * @copyright Copyright (c)2015-2019 Sasi varna kumar / J2Store.org
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\View\Dashboard;

use Flycart\Axisubs\Admin\Model\Dashboard;
use JComponentHelper;
use JFactory;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{
	protected function onBeforeMain($tpl = null)
	{
		/** @var Dashboard $model */
		$model = $this->getModel();
	}
}
