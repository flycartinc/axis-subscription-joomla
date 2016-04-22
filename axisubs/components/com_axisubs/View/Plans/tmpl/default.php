<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<div class="axisubs-bs3">
<div class="row">
	<?php foreach ($this->items as $item) : ?>
		<div class="col-md-4">
            <div class="well">
        		<h2 class=""><a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=plan&slug='.$item->slug); ?>">
                    <?php echo $item->name; ?>
                    </a>
                </h2>
        		<?php echo $item->description; ?>
        		<hr>
                <h3> <?php echo Axisubs::currency()->format($item->price); ?> </h3>
                
                <?php if ( $item->setup_cost > 0 ) : ?>
                    <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_SETUP_COST'); ?> </b> </span>
                    <span> <?php echo Axisubs::currency()->format($item->setup_cost); ?> </span>
                    <br>
                <?php endif; ?>

                <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_DURATION'); ?> </b> </span>
        		<span> <?php echo $item->period; ?> <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_DAYS'); ?> </span>
                <?php if ( $item->hasTrial() ) : ?>
                    <hr>
                    <span > <b> <?php echo JText::_('COM_AXISUBS_PLAN_TRIAL_DURATION'); ?> </b></span>
                    <span>  <?php echo $item->trial_period; ?> 
                            <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_DAYS'); ?> </span>
                <?php endif; ?>
        		<hr>
        		<p><a class="btn btn-large btn-primary" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=subscribe&plan='.$item->slug); ?>">
        			<i class="icon-ok"></i> 
                    <?php if ( $item->hasTrial() ) : ?>
                        <?php echo JText::_('COM_AXISUBS_START_TRIAL'); ?>
                    <?php else: ?>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_NOW'); ?>
                    <?php endif; ?>
        			</a>
        		</p>
        	</div>
        </div>
    <?php endforeach; ?>
</div>
</div>