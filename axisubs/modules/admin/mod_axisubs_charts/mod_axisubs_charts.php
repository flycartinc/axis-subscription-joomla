<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
require_once( dirname(__FILE__).'/helper.php' );

JFactory::getLanguage()->load('com_axisubs', JPATH_ADMINISTRATOR);
$moduleclass_sfx = $params->get('moduleclass_sfx','');
$link_type = $params->get('link_type','link');
$helper = new modAxisubsChartsHelper();

$display_data = $helper->getData();

require( JModuleHelper::getLayoutPath('mod_axisubs_charts', $params->get('layout', 'default')));
