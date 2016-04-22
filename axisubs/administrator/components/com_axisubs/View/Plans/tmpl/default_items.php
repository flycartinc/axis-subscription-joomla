<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
use Flycart\Axisubs\Admin\Helper\Axisubs;

$curr = Axisubs::currency();
$status_helper = Axisubs::status();
?>

<style>
.input-small{
margin-left:84px !important;
}
.chzn-container {
    display: table-header-group;
}
</style>

<div class="panel panel-info cb-custm-chkbx">
	<div class="panel-heading">
	</div>
		<?php foreach($this->items as $k => $item){ ?>

		<div class="row">
			<div class="col-md-1 planpadding" >
				<dl>
					<dt>
						<?php echo $this->readable_items[$k]->axisubs_plan_id; ?>
					</dt>
				</dl>
			</div>
			<div class="col-md-3 planpadding" >
				<dl>
						<dt>
							<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>:
							</span> 
							<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=plans&id='.$item->axisubs_plan_id.'&task=read');?>"> <?php echo $item->name; ?> 
							</a>
						</dt>
						<dt>
								<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_STATUS');?>:
								</span>
								<?php if($item->enabled == 1) :?>
										<span class="pactive" original-title="">
											<?php echo JText::_('COM_AXISUBS_ACTIVE'); ?>
										</span>
								<?php else:?>
										<span class="pdeactive" original-title="">
										<?php echo JText::_('COM_AXISUBS_INACTIVE'); ?>
										</span>
								<?php endif;?>
						</dt>
				</dl>
			</div>

			<div class="col-md-4 planpadding">
				<dl>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_ALIAS');?>:
						</span> 
						<?php echo $item->slug; ?>
					</dt>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_TRAIL_PERIOD');?>:</span>
						<?php echo $item->trial_period; ?>
					</dt>
				</dl>		
			</div>

			<div class="col-md-3 planpadding">
				<dl>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_PRICES');?>:
						</span> 
						<?php echo $curr->format( $item->price, $item->currency); ?>
					</dt>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_BILLING_PERIOD');?>:</span> 
						<?php echo $item->period; ?>
					</dt>
			
				</dl>
			</div>

			<div class="col-md-1 planpadding">
				<div class="table-col1 table-options">
					<dl>
						<dt>
							<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=plans&id='.$item->axisubs_plan_id.'&task=read');?>" class="aview">
							<i class="fa fa-align-justify"></i>
							</a>
						</dt>
			
						<dt>
							<div class="dropdown dropplan">
								<a class="dropdown-toggle" href="#" data-toggle="dropdown">
									<span class="icon-cog"></span>
								</a>
								<ul class="dropdown-menu pull-right">
									<li>
										<a href ="index.php?option=com_axisubs&view=Plan&id=<?php echo $item->axisubs_plan_id; ?>">
											<?php echo JText::_('COM_AXISUBS_PLAN_EDIT_PLAN'); ?>
										</a>
									</li>	
									<!-- <li>
										<a href ="#"><?php echo JText::_('COM_AXISUBS_PLAN_CLONE_PLAN'); ?></a>
									</li> -->
									<li>
										<a href ="index.php?option=com_axisubs&view=Plan&task=deleteprompt&id=<?php echo $item->axisubs_plan_id; ?>">
											<?php echo JText::_('COM_AXISUBS_PLAN_DELETE_PLAN'); ?>
										</a>
									</li>
								</ul>
							</div>
						</dt>
					</dl>
		    	</div>
			</div>
		</div>
		<?php }  ?>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('div.dropdown').hover(function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
	}, function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
	});
});
</script>
