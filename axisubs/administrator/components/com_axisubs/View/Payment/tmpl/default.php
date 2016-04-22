<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die;
$sidebar = JHtmlSidebar::render();
?>
<div class="axisubs-bs3">
    <?php if(!empty( $sidebar )): ?>
    <div id="j-sidebar-container">
      <?php echo $sidebar ; ?>
    </div>
    <?php endif;?>
    <div id="j-main-container" class="col-md-12 ">
        <h3><?php echo JText::_('COM_AXISUBS_PAYMENTS'); ?></h3>
    </div>
</div>