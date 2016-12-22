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
$duration = Axisubs::duration();
?>

<style>
.input-small{
margin-left:84px !important;
}
.chzn-container {
    display: table-header-group;
}
</style>
<?php
$model = $this->getModel();
//echo $model->getState('filter_order');
$listOrder = $model->getState('filter_order');
$listDirn  = $model->getState('filter_order_Dir');
$saveOrder = $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_axisubs&task=saveOrderAjax&view=Plans';
	JHtml::_('sortablelist.sortable', 'planList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
?>
<div class="panel panel-info cb-custm-chkbx">
	<?php
	echo JHtml::_('searchtools.sort', '', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
	?>
	<span class="pull-right">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'axisubs_plan_id', $this->lists->order_Dir, $this->lists->order, 'browse');
		?>
	</span>
	<!--<div class="panel-heading">
	</div>-->
	<table class="table table-striped" id="planList">
		<tbody>
		<?php foreach($this->items as $k => $item){ ?>
		<tr class="row<?php echo $k % 2; ?>">
			<td class="order nowrap center hidden-phone">
				<div class="row">
					<div class="planpadding">
					<?php
					$iconClass = '';
					$canChange = 1;
					if (!$canChange)
					{
						$iconClass = ' inactive';
					}
					elseif (!$saveOrder)
					{
						$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
					}
					?>
					<span class="sortable-handler<?php echo $iconClass ?>">
									<span class="icon-menu"></span>
								</span>
					<?php if ($canChange && $saveOrder) : ?>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
					<?php endif; ?>
					</div>
				</div>
			</td>
			<td>
		<div class="row">
			<div class="col-sm-3 planpadding plan-image" >
        <dl class="col-sm-6">
					<dt>
						<?php echo $this->readable_items[$k]->axisubs_plan_id; ?>
					</dt>
				</dl>
				<dl class="col-sm-6">
					<dt>
					<div class="list-image">
						<?php $image = ($item->image != '')? $item->image : 'media/com_axisubs/images/ico_noimage.png';
						$image = JUri::root().$image;
						?>
						<img src="<?php echo $image; ?>"/>
					</div>
					</dt>
				</dl>
			</div>
			<div class="col-sm-2 planpadding" >
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
											<?php echo JText::_('JPUBLISHED'); ?>
										</span>
								<?php else:?>
										<span class="pdeactive" original-title="">
										<?php echo JText::_('JUNPUBLISHED'); ?>
										</span>
								<?php endif;?>
						</dt>
						<dt class="axisubs_active_subs">
							<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_ACTIVE_SUBSCRIPTIONS');?>:
							</span>
							<span class="axisubs_active_subs_count"><?php echo $item->subs_count ? $item->subs_count : 0; ?>
							</span>
						</dt>
				</dl>
			</div>

			<div class="col-sm-2 planpadding">
				<dl>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_ALIAS');?>:
						</span>
						<?php echo $item->slug; ?>
					</dt>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_TRAIL_PERIOD');?>:</span>
						<?php echo $duration->getDurationInFormat($item->trial_period, $item->trial_period_unit); ?>
					</dt>
				</dl>
			</div>

			<div class="col-sm-2 planpadding">
				<dl>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_PRICES');?>:
						</span>
						<span class="axisubs_plan_price-bold"><?php echo $curr->format( $item->price, $item->currency); ?></span>
					</dt>
					<dt>
						<span class="pcolor"><?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_BILLING_PERIOD');?>:</span>
						<?php echo $duration->getDurationInFormat($item->period, $item->period_unit); ?>
					</dt>

				</dl>
			</div>

			<div class="col-sm-3 planpadding">
				<div class="table-col1 table-options">
					<dl>
						<dt>
						<a class="btn-small hasTooltip" data-original-title="View" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=plans&id='.$item->axisubs_plan_id.'&task=read');?>">
                <i class="fa fa-search"></i> <?php echo JText::_('COM_AXISUBS_VIEW_DETAILS');?></a>
						</dt>

						<dt>
							<div class="dropdown dropplan">
								<a class="dropdown-toggle btn" href="#" data-toggle="dropdown">
									<span class="icon-cog"></span> <?php echo JText::_('COM_AXISUBS_OPTION_DETAILS');?>
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
				</td>
		<?php }  ?>
</div>
<script>
jQuery(document).ready(function(){
	jQuery('div.dropdown').hover(function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
	}, function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
	});

	jQuery(".js-stools-column-order").click(function () {
		jQuery("input[name=filter_order]").val('ordering');
		jQuery("input[name=filter_order_Dir]").val(jQuery(this).attr('data-direction'));
		jQuery("#adminForm").submit();
	});
});
</script>
