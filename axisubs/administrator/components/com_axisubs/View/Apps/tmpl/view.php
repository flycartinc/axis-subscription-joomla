<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
$row = $this->item;?>
	<?php $results = J2Store::plugin()->eventWithHtml('GetAppView', array($row)); ?>
	<h3><?php echo JText::_($row->name); ?></h3>
	<?php echo $results; ?>