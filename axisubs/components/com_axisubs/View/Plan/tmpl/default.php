<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
//use \JText;
$currency = Axisubs::currency();
?>
<div class="axisubs-bs3">

<!-- after plan title -->
<div class="row-fluid">
    <h3><?php echo $this->item->name; ?></h3>
</div>
<div class="row-fluid">
    <div class="col-md-5">
        <!-- before plan content -->
        <?php echo $this->item->description; ?>
        <!-- after plan content -->
    </div>
    <div class="col-md-5">
                
        <!-- before duration and trial details -->
        <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_DURATION'); ?> </b> </span>
        <span> <?php echo $this->item->period; ?> <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_DAYS'); ?> </span>
        <br>
        <?php if ( $this->item->hasTrial() ) : ?>
            <span > <b> <?php echo JText::_('COM_AXISUBS_PLAN_TRIAL_DURATION'); ?> </b></span>
            <span>  <?php echo $this->item->trial_period; ?> 
                    <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_DAYS'); ?> </span>
        <?php endif; ?>
        <br>
        <!-- after duration and trial details -->
            
        <!-- pre price display information -->
        <!-- Plan price -->
        <h3 class="plan-price" > <?php echo Axisubs::currency()->format($this->item->price); ?> </h3>
        <br>

        <!-- Setup cost -->
        <?php if ( $this->item->setup_cost > 0 ) : ?>
            <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_SETUP_COST'); ?> </b> </span>
            <span class="plan-setup-cost" > <?php echo Axisubs::currency()->format($this->item->setup_cost); ?> </span>
            <br>
        <?php endif; ?>

        <!-- post price display information -->
        <br>
        <!-- before subscribe button -->
        <!-- Subscribe button -->
        <p><a class="btn btn-large btn-primary" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=subscribe&plan='.$this->item->slug); ?>">
            <i class="icon-ok"></i> 
            <?php if ( $this->item->hasTrial() ) : ?>
                <?php echo JText::_('COM_AXISUBS_START_TRIAL'); ?>
            <?php else: ?>
                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_NOW'); ?>
            <?php endif; ?>
            </a>
        </p>
        <!-- after subscribe button -->
    </div>
</div>

<div class="row">
</div>

</div>