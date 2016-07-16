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
use JFactory;

class Apps extends DataController
{

	public function execute($task)
	{

		$app = JFactory::getApplication();
		$appTask = $app->input->getCmd('appTask', '');
		$values = $app->input->getArray($_POST);
		$returnView = $app->input->getCmd('return', '');

		// Check if we are in a report method view. If it is so,
		// Try lo load the report plugin controller (if any)
		if ( $task  == "view" && $appTask != '' )
		{
			$model = $this->getModel('Apps');

			$id = $app->input->getInt('id', '0');

			if(!$id)
				parent::execute($task);

			$model->setId($id);

			// get the data
			// not using getItem here to enable ->checkout (which requires JTable object)
			$model->load( (int) $model->getId() );
			$element = $model->element;

			// The name of the App Controller should be the same of the $_element name,
			// without the tool_ prefix and with the first letter Uppercase, and should
			// be placed into a controller.php file inside the root of the plugin
			// Ex: tool_standard => J2StoreControllerToolStandard in tool_standard/controller.php
			if($returnView != ''){
				$controllerName = str_ireplace(strtolower($returnView).'_', '', $element);
			} else {
				$controllerName = str_ireplace('app_', '', $element);
			}
			$controllerName = ucfirst($controllerName);
			$path = JPATH_SITE.'/plugins/axisubs/';
			$controllerPath = $path.$element.'/'.$element.'/controller.php';
			if (file_exists($controllerPath)) {
				require_once $controllerPath;
			} else {
				$controllerName = '';
			}
			if($returnView != '') {
				$className = 'AxisubsController'.$returnView. $controllerName;
			} else {
				$className = 'AxisubsControllerApp' . $controllerName;
			}
			if ($controllerName != '' && class_exists($className)){

				$container = \FOF30\Container\Container::getInstance('com_axisubs');

				// Create the controller
				$controller   = new $className( $container );

				// Add the view Path
				//$controller->addViewPath($path);
				// Perform the requested task
				$controller->execute( $appTask );
				// Redirect if set by the controller
				$controller->redirect();
			} else{
				parent::execute($task);
			}
		} else{
			parent::execute($task);
			if($returnView != '' && $task  != "view"){
				$app->redirect('index.php?option=com_axisubs&view='.$returnView);
				return;
			}

		}
	}

	function view(){
		$model = $this->getModel();
		$id = $this->input->getInt('id');
		$row = $model->getItem($id);
		$view   = $this->getView('App');
		$view->setModel( 'model', $model );
		$view->row = $row ;
		$view->setLayout( 'view' );
		$view->display();
	}
}