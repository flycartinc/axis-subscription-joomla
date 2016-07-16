<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
JLoader::import('joomla.application.component.helper');
require_once( dirname(__FILE__).'/helper.php' );
if (!JComponentHelper::isEnabled('com_axisubs')) {
    return false;
}
JFactory::getLanguage()->load('com_axisubs', JPATH_ADMINISTRATOR);
$moduleclass_sfx = $params->get('moduleclass_sfx','');
$link_type = $params->get('link_type','link');
require( JModuleHelper::getLayoutPath('mod_axisubs_menu', $params->get('layout', 'default')));
