<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/AppController.php');
use Flycart\Axisubs\Admin\Helper\AppController;

class AxisubsControllerAppCoupons extends AppController
{
	var $_element   = 'app_coupons';

}
