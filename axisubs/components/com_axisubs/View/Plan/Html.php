<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Site\View\Plan;

defined('_JEXEC') or die;

use Flycart\Axisubs\Site\Model\Plans;

class Html extends \FOF30\View\DataView\Html
{
	/**
	 * The record loaded (read, edit, add views)
	 *
	 * @var  Plans
	 */
	protected $item = null;

	/**
	 * Executes before the read task, allows us to push data to the view
	 */
	protected function onBeforeRead()
	{
		parent::onBeforeRead();

		// Force the layout
		$this->layout = 'default';

		// Makes sure SiteGround's SuperCache doesn't cache the subscription page
		\JFactory::getApplication()->setHeader('X-Cache-Control', 'False', true);
	}
}