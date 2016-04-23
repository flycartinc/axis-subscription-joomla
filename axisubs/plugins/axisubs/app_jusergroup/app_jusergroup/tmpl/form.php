<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
?>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label for="params_joomla_addgroups" class="control-label">
				<?php echo JText::_('PLG_AXISUBS_JUSERGROUP_ADDGROUPS_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($vars->plan, 'add') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AXISUBS_JUSERGROUP_ADDGROUPS_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label for="params_joomla_removegroups" class="control-label">
				<?php echo JText::_('PLG_AXISUBS_JUSERGROUP_REMOVEGROUPS_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($vars->plan, 'remove') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AXISUBS_JUSERGROUP_REMOVEGROUPS_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="alert alert-warning">
	<p><?php echo JText::_('PLG_AXISUBS_JUSERGROUP_USAGENOTE'); ?></p>
</div>