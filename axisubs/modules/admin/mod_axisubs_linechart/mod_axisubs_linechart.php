<?php
/**
 * @package   Module - Axisubs Line chart
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
require_once( dirname(__FILE__).'/helper.php' );

// Do not run if Axisubs Component is not enabled
JLoader::import('joomla.application.component.helper');
if (!JComponentHelper::isEnabled('com_axisubs'))
{
    return false;
}

JFactory::getLanguage()->load('com_axisubs', JPATH_ADMINISTRATOR);
$moduleclass_sfx = $params->get('moduleclass_sfx','');
$link_type = $params->get('link_type','link');
$helper = new modAxisubsLineChartHelper();

$lastWeekData = $helper->getLastDaysData($params, 7);
$lastMonthData = $helper->getLastDaysData($params, 30);

require( JModuleHelper::getLayoutPath('mod_axisubs_linechart', $params->get('layout', 'default')));
