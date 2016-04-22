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
	<div id="j-main-container" class="row">
        <div class="col-lg-12">
	        <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart1'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart2'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart3'); ?>
        </div>
	</div>
</div>