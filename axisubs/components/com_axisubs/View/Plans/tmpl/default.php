<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
$config = Axisubs::config();
$is_including_tax = $config->get('config_including_tax', 0);
$display_tax_info = $config->get('display_price_with_tax_info', 0);
$get_duration = Axisubs::duration();
?>
<div class="axisubs-bs3">
<div class="row">
	<?php foreach ($this->items as $item) : ?>
		<div class="col-md-4">
            <div class="well">
        		<h2><a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=plan&slug='.$item->slug); ?>">
                    <?php echo $item->name; ?>
                    </a>
                </h2>
        		<?php echo $item->description; ?>
        		<hr>
                <h3> <?php echo Axisubs::currency()->format($item->price); ?> </h3>
                <?php
                $taxpercent = $item->getTaxPercent();
                if($display_tax_info && $taxpercent > 0  && $item->plan_type){
                    if($is_including_tax){
                        $taxText = JText::_('COM_AXISUBS_PLAN_INCLUDING_TAX_TEXT');
                    } else {
                        $taxText = 'Excl.';
                    }
                    ?>
                    <span> (<?php echo $taxText.' '.$taxpercent.'% '.JText::_('COM_AXISUBS_PLAN_TAX_CLASS'); ?>) </span>
                    <br>
                <?php
                }
                ?>
                <?php if ( $item->setup_cost > 0 ) : ?>
                    <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_SETUP_COST'); ?> </b> </span>
                    <span> <?php echo Axisubs::currency()->format($item->setup_cost); ?> </span>
                    <hr>
                <?php endif; ?>
                <span> <b> <?php echo JText::_('COM_AXISUBS_PLAN_DURATION'); ?> </b> </span>
                <?php if($item->plan_type){ ?>
        		<span> <?php echo $get_duration->getDurationInFormat($item->period, $item->period_unit); ?></span>
                <?php }else {
                    ?>
                    <span> <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_FOREVER'); ?> </span>
                <?php
                } ?>
                <?php if ( $item->hasTrial() ) : ?>
                    <hr>
                    <span > <b> <?php echo JText::_('COM_AXISUBS_PLAN_TRIAL_DURATION'); ?> </b></span>
                    <span>  <?php echo $get_duration->getDurationInFormat($item->trial_period, $item->trial_period_unit); ?></span>
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
