<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\View\App;

use Flycart\Axisubs\Admin\Model\Apps;
use JComponentHelper;
use JFactory;
use JText;
use Flycart\Axisubs\Admin\Helper\Axisubs;
defined('_JEXEC') or die;

class Html extends \FOF30\View\DataView\Html
{	
	public function display($tpl=null){
		$app = JFactory::getApplication();
		$task =$app->input->getString('task');
		$id = $app->input->getInt('id');
		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$model = $container->factory->model('Apps');
		//$model= $this->getModel('Apps');
		$this->item=$model->find($id);
		parent::display($tpl);
	}
}