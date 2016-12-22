<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die;
use Flycart\Axisubs\Admin\Helper\Axisubs;
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
            <div class="col-lg-9 col-md-9 col-sm-12">
                <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart1', 'html5'); ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="panel-body-dashboard">
                    <h3><?php echo JText::_('COM_AXISUBS_USERGUIDE_TITLE'); ?></h3>
                    <div class="user-guide-desc">
                        <?php echo JText::_('COM_AXISUBS_USERGUIDE_DESCRIPTION'); ?>
                    </div>
                    <a class="btn btn-large btn-success" target="_blank" href="https://www.flycart.org/products/joomla/axis-subscriptions">
                        <?php echo JText::_('COM_AXISUBS_USERGUIDE_READ_IT_NOW'); ?>
                    </a>
                </div>
                <?php if ( ! Axisubs::isPro() ) { ?>
                <br>
                <div class="panel-body-dashboard">
                    <h3><?php echo JText::_('COM_AXISUBS_SUPPORT_TITLE'); ?></h3>
                    <div class="support-desc">
                        <?php echo JText::_('COM_AXISUBS_SUPPORT_DESCRIPTION'); ?>
                    </div>
                    <a class="btn btn-large btn-success" target="_blank" href="<?php echo Axisubs::buildHelpLink('products/joomla/axis-subscriptions', 'apps'); ?>">
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-12">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart2', 'html5'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart3', 'html5'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-dashboard-chart4', 'html5'); ?>
        </div>

</div>
</div>
