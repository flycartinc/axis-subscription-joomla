<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Flycart\Axisubs\Admin\Helper\Select;
$events = Select::getTriggersList();
?>
<table class="axisubs_list_items_table">
	<thead>
	<tr>
		<th>
			<b>#</b>
		</th>
		<th>
			<b><?php echo JText::_('ID'); ?></b>
		</th>
		<th>
			<b><?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_EVENT_LABEL'); ?></b>
		</th>
		<th>
			<b><?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_SUBJECT'); ?></b>
		</th>
		<th>
			<b><?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_RECIPIENTS_LABEL'); ?></b>
		</th>
		<th>
			<b><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_STATUS'); ?></b>
		</th>
		<th>
		</th>
	</tr>
	</thead>
	<tbody>
<?php foreach($this->items as $k => $item){
	?>
	<tr>
		<td>
			<input type="checkbox" onclick="Joomla.isChecked(this.checked);" value="<?php echo $item->axisubs_emailtemplate_id; ?>" name="cid[]" id="cb<?php echo $k; ?>">
		</td>
		<td>
			<a href="index.php?option=com_axisubs&view=EmailTemplate&id=<?php echo $item->axisubs_emailtemplate_id; ?>">
				<?php echo $item->axisubs_emailtemplate_id; ?>
			</a>
		</td>
		<td>
			<?php
			if(isset($events[$item->event])){
				echo $events[$item->event];
			} else {
				echo $item->event;
			}
			?>
		</td>
		<td>
			<?php echo $item->emailtemplatecontent->content; ?>
		</td>
		<td>
			<?php echo $item->recipients; ?>
		</td>
		<td>
			<?php
			if($item->enabled){
				?>
				<span class=" axisubs-status label label-success">
							<?php
							echo JText::_('COM_AXISUBS_PLAN_ENABLED');
							?>
						</span>
				<?php
			} else {
				?>
				<span class=" axisubs-status label label-danger">
							<?php
							echo JText::_('COM_AXISUBS_STATUS_DISABLED');
							?>
						</span>
				<?php
			}
			?>
		</td>
		<td>
			<a class="btn btn-info" href="index.php?option=com_axisubs&view=EmailTemplate&id=<?php echo $item->axisubs_emailtemplate_id; ?>">
				<?php echo JText::_('AXISUBS_EDIT'); ?>
			</a>
		</td>
	</tr>
	<?php
} ?>
	</tbody>
</table>