<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/AppModel.php');
use Flycart\Axisubs\Admin\Helper\AppModel;

class AxisubsModelAppEmailtemplates extends AppModel
{
	public $_element = 'app_emailtemplates';

}
