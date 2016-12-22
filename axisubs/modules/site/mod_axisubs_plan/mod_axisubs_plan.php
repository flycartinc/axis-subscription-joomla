<?php
/**
 * @package   Axisubs Module - Subscription Management System
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

// Do not run if Axisubs Component is not enabled
JLoader::import('joomla.application.component.helper');
if (!JComponentHelper::isEnabled('com_axisubs'))
{
    return false;
}
require_once JPATH_ADMINISTRATOR.'/components/com_axisubs/vendor/autoload.php';
require_once( dirname(__FILE__).'/helper.php' );

// Load the language files
$jlang = JFactory::getLanguage();
$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, 'en-GB', true);
$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, null, true);

$jlang->load('com_axisubs', JPATH_SITE, 'en-GB', true);
$jlang->load('com_axisubs', JPATH_SITE, $jlang->getDefault(), true);
$jlang->load('com_axisubs', JPATH_SITE, null, true);

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'media/mod_axisubs_plan/css/mod_axisubs_plan.css');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$helper = new modAxisubsPlanHelper();

//For loading selected plan
$planDetails = $helper->getSelectedPlan($params);
require JModuleHelper::getLayoutPath('mod_axisubs_plan', $params->get('layout', 'default'));
