<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/App.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\App;

class plgAxisubsApp_Test extends App
{

	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_test';

    /**
     * Overriding
     *
     * @param $options
     * @return unknown_type
     */
    function onAxisubsGetAppView( $row )
    {

	   	if (!$this->_isMe($row))
    	{
    		return null;
    	}

    	$html = $this->viewList();

    	return $html;
    }

    /**
     * Validates the data submitted based on the suffix provided
     * A controller for this plugin, you could say
     *
     * @param $task
     * @return html
     */
    function viewList()
    {
    	$app = JFactory::getApplication();
    	$option = 'com_axisubs';
    	$ns = $option.'.app';
    	$html = "";
    	JToolBarHelper::title(JText::_('AXISUBS_APP').'-'.JText::_('PLG_AXISUBS_'.strtoupper($this->_element)),'axisubs-logo');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();

	   	$vars = new JObject();
	    $this->includeCustomModel('AppTest');

        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppTest( $container, $config = array('name'=>'AxisubsModelAppTest') );

        $data = $this->params->toArray();
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;

    	$id = $app->input->getInt('id', '0');
    	$vars->id = $id;
    	$html = $this->_getLayout('default', $vars);
    	return $html;
    }

    function onAxisubsPlanAfterFormRender($plan){
        $vars = new JObject();
        $vars->title='second integ';
        $vars->html = 'second integration content';
        return $vars;
    }

}