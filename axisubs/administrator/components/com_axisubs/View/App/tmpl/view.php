<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die;
JHtml::_('behavior.modal');
use Flycart\Axisubs\Admin\Helper\Axisubs;
$sidebar = \JHtmlSidebar::render();
$row = $this->item;
?>
<?php if(!empty( $sidebar )): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $sidebar ; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<?php $results = Axisubs::plugin()->eventWithHtml('GetAppView', array($row)); ?>
		<h3><?php echo JText::_($row->name); ?></h3>
		<?php echo $results; ?>
	</div>
