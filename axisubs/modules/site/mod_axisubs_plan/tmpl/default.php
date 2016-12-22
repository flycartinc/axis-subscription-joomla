<?php
/**
 * @package   Axisubs Module - Subscription Management System
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Helper\Axisubs;

$get_duration = Axisubs::duration();
$config = Axisubs::config();
$is_including_tax = $config->get('config_including_tax', 0);
$display_tax_info = $config->get('display_price_with_tax_info', 0);
$taxPercent = $planDetails->getTaxPercent();
?>
<div class="axisubs-module_plan<?php echo $moduleclass_sfx ?>">
	<?php
	if(isset($planDetails->axisubs_plan_id) && $planDetails->axisubs_plan_id){
		?>
		<div class="axisubs_mod-plan_con">
			<?php if($params->get('show_plan_title', 1)){ ?>
			<div class="axisubs_mod-plan_title axisubs_mod-item">
				<h3><?php echo $planDetails->name; ?></h3>
			</div>
			<?php } ?>

			<?php if($params->get('show_plan_description', 1)){ ?>
			<div class="axisubs_mod-plan_description axisubs_mod-item">
				<?php echo $planDetails->description; ?>
			</div>
			<?php } ?>

			<?php if($params->get('show_plan_price', 1)){ ?>
			<div class="axisubs_mod-plan_price axisubs_mod-item">
				<?php echo Axisubs::currency()->format($planDetails->price); ?>
			</div>
			<?php } ?>
			<?php
			if($display_tax_info && $taxPercent > 0 && $planDetails->plan_type && $params->get('show_plan_tax', 1)){
				if($is_including_tax){
					$taxText = JText::_('COM_AXISUBS_PLAN_INCLUDING_TAX_TEXT');
				} else {
					$taxText = JText::_('COM_AXISUBS_PLAN_EXCLUDING_TAX_TEXT');
				}
				?>
				<div class="axisubs_mod-plan_tax axisubs_mod-item">
					(<?php echo $taxText.' '.$taxPercent.'% '.JText::_('COM_AXISUBS_PLAN_TAX_CLASS'); ?>)
				</div>
				<?php
			}
			?>
			<?php if($planDetails->setup_cost>0 && $params->get('show_plan_setup_cost', 1)){ ?>
			<div class="axisubs_mod-plan_setup_cost axisubs_mod-item">
				<span class="axisubs_mod-plan_label"><?php echo JText::_('COM_AXISUBS_PLAN_SETUP_COST'); ?></span>
				<?php echo Axisubs::currency()->format($planDetails->setup_cost); ?>
			</div>
			<?php } ?>
			<?php if($params->get('show_plan_setup_duration', 1)){ ?>
			<div class="axisubs_mod-plan_duration axisubs_mod-item">
				<span class="axisubs_mod-plan_label"><?php echo JText::_('COM_AXISUBS_PLAN_DURATION'); ?></span>
				<?php if($planDetails->plan_type){ ?>
					<span class="axisubs_mod-plan_data"> <?php echo $get_duration->getDurationInFormat($planDetails->period, $planDetails->period_unit); ?></span>
				<?php }else {
					?>
					<span class="axisubs_mod-plan_data"> <?php echo JText::_('COM_AXISUBS_PLAN_PERIOD_FOREVER'); ?> </span>
					<?php
				} ?>
				<?php if ( $planDetails->hasTrial() ) : ?>
				<div class="axisubs_mod-plan_duration_trial axisubs_mod-item">
					<span class="axisubs_mod-plan_label"><?php echo JText::_('COM_AXISUBS_PLAN_TRIAL_DURATION'); ?></span>
					<span class="axisubs_mod-plan_data"><?php echo $get_duration->getDurationInFormat($planDetails->trial_period, $planDetails->trial_period_unit); ?></span>
				</div>
				<?php endif; ?>
			</div>
			<?php } ?>
			<div class="axisubs_mod-plan_button axisubs_mod-item">
				<a class="btn btn-large btn-primary" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=subscribe&plan='.$planDetails->slug); ?>">
					<i class="icon-ok"></i>
					<?php if ( $planDetails->hasTrial() ) : ?>
						<?php echo JText::_('COM_AXISUBS_START_TRIAL'); ?>
					<?php else: ?>
						<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_NOW'); ?>
					<?php endif; ?>
				</a>
			</div>
		</div>
	<?php
	} else {
		echo JText::_('MOD_AXISUBS_PLAN_PLEASE_SELECT_A_PLAN');
	}
	?>
</div>