<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
use Flycart\Axisubs\Admin\Helper\Axisubs;

$curr = Axisubs::currency();
$status_helper = Axisubs::status();
?>

<h3> <?php echo JText::_('COM_AXISUBS_PLAN_DELETE_ARE_YOU_SURE'); ?> - <?php echo $this->plan->name; ?> ? </h3>
<div class="alert alert-error">
	<ul>
		<li> <?php echo JText::_('COM_AXISUBS_PLAN_DELETE_WARNING1'); ?></li>
		<li> <?php echo JText::_('COM_AXISUBS_PLAN_DELETE_WARNING2'); ?> </li>
	</ul>
</div>

<form action="index.php" method="POST" >
	<input type="hidden" name="option" value="com_axisubs">
	<input type="hidden" name="view" value="Plan">
	<input type="hidden" name="task" value="disablePlan">
	<input type="hidden" name="plan_id" value="<?php echo $this->plan->axisubs_plan_id; ?>">
	<button class="btn btn-danger" type="submit"><?php echo JText::_('COM_AXISUBS_PLAN_DELETE_ACTION_CONFIRM_DELETE'); ?> </button>
</form>