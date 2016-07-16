<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();

define('AXISUBS_VERSION', '0.8');
define('AXISUBS_DATE', '2016-02-26');
define('AXISUBS_VERSIONHASH', md5(AXISUBS_VERSION.AXISUBS_DATE.JFactory::getConfig()->get('secret','')));
