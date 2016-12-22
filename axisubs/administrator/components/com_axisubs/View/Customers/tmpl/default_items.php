<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$choose_flag = false;
$flag = \JFactory::getApplication()->input->get('flag','');
if (!empty($flag)){
	$choose_flag = true;
}

?>
<style>
.input-small{
margin-left:84px !important;
}

</style>
<div class="row">
    <?php if ($choose_flag) : ?>
        <div class="choose-customer text-center">
            <?php echo JText::_('COM_AXISUBS_CUSTOMER_CHOOSE_CUSTOMER_MESSAGE'); ?>
        </div>
    <?php endif; ?>
</div>
<div class="panel panel-info ">
	<div class="panel-heading">
		&nbsp;
	</div>

	<?php foreach($this->items as $k => $item){
		$item->a='';  ?>
		<div class="row row-item">
		<div class="col-md-1 planpadding" >
			<dl>
				<dt>
					<?php echo $this->readable_items[$k]->axisubs_customer_id; ?>
				</dt>
			</dl>
		</div>
		<div class="col-md-3 planpadding" >
			<dl>
				<dt>
				<span class="pcolor">
				<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Customers&id='.$item->axisubs_customer_id.'&task=read');?>">
					<?php echo JText::_('COM_AXISUBS_CUSTOMER_ID');?><?php echo $item->axisubs_customer_id; ?>
				</a>
				</span>
				</dt>
					<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_SUBSCRIPITION');?>:
					</span>

						<?php if(count($item->subscriptions)){
							foreach($item->subscriptions as $s => $subscriptions){
								?>
								<div class="load_more_subscriptions" data-attr="<?php echo $item->user_id; ?>">
									<a><?php echo $subscriptions->plan->name; ?></a><span class="more_subscriptions">...</span>
									<span class="more_subscriptions-data-left-arrow"></span>
									<div class="more_subscriptions-data">
									</div>
								</div>
									<?php
								break;
							}
						} else {
							echo 'No Subscriptions';
						}?>
					</dt>
				<dt>

				</dt>
			</dl>
		</div>


		<div class="col-md-3 planpadding">
			<dl>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>:
					</span>
					<?php  echo $item->first_name." ".$item->last_name; ?>
				</dt>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?>:
					</span><a href="mailto:<?php  echo $item->email; ?>"><?php  echo $item->email; ?></a>
				</dt>
				<dt>
					<span class="pcolor"><?php echo JText::_('AXISUBS_ADDRESS_COMPANY_NAME');?>:
					</span>
					<?php echo $item->company; ?>
					<?php if ($choose_flag) : ?>
						<a href="index.php?option=com_axisubs&view=Subscriptions&task=add&user_id=<?php echo $item->user_id; ?>" class="btn btn-primary">
							Choose customer
						</a>
					<?php endif; ?>
				</dt>
			</dl>
		</div>

		<div class="col-md-3 planpadding">
			<dl>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMERSS_CREATED_AT');?>:
					</span>
					<?php echo $item->created_on; ?>
				</dt>
				<dt>
					<span class="pcolor"><?php echo JText::_('AXISUBS_ADDRESS_PHONE');?>:
					</span><?php echo $item->phone; ?>
				</dt>
			</dl>
		</div>

		<div class="col-md-2 planpadding">
			<div class="table-col table-options">
				<dl>
					<dt>
						<a class="btn-small hasTooltip" data-original-title="View" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Customers&id='.$item->axisubs_customer_id.'&task=read');?>">
						  <i class="fa fa-search"></i> <?php echo JText::_('COM_AXISUBS_VIEW_DETAILS');?></a>
						</a>
					</dt>

					<dt>
						<div class="dropdown dropplan">
							<a class="dropdown-toggle btn" href="#" data-toggle="dropdown">
							<span class="icon-cog"></span> <?php echo JText::_('COM_AXISUBS_OPTION_DETAILS');?>
							</a>
							<ul class="dropdown-menu pull-right">
								<li>
								<a href="index.php?option=com_axisubs&view=Customer&id=<?php echo $item->axisubs_customer_id; ?>"><?php echo JText::_('COM_AXISUBS_CUSTOMER_ACTION_CHANGE_DETAILS');?></a>
								</li>
								<li>
									<a href="index.php?option=com_axisubs&view=Subscriptions&task=add&user_id=<?php echo $item->user_id; ?>"><?php echo JText::_('COM_AXISUBS_CUSTOMER_ACTION_ADD_NEW_SUBSCRIPTION');?></a>
								</li>
							</ul>
						</div>
					</dt>
				</dl>
		    </div>
		</div>
	</div>

	<?php }  ?>
	<?php if ($choose_flag) :?>
		<input type="hidden" name="flag" value="<?php echo \JFactory::getApplication()->input->get('flag',''); ?>" />
	<?php endif; ?>
</div>

<script>
jQuery(document).ready(function(){
	jQuery('div.dropdown').hover(function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
	}, function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
	});


	jQuery('.load_more_subscriptions').mouseout(function() {
		var contentDiv = jQuery(this).children('.more_subscriptions-data');
		contentDiv.hide();
		contentDiv.prev('.more_subscriptions-data-left-arrow').hide();
	}).mouseover(function() {
		var selected = jQuery(this);
		var contentDiv = selected.children('.more_subscriptions-data');
		//selected.find('.more_subscriptions-data').show();
		contentDiv.show();
		contentDiv.prev('.more_subscriptions-data-left-arrow').show();
		if(jQuery(this).attr('send-attr') == '1'){
			return;
		} else {
			selected.parent().find('.more_subscriptions-data').html("Loading..");
			selected.attr('send-attr', '1');
		}
		jQuery.ajax({
			url: 'index.php?option=com_axisubs&view=Customers&task=getSubscriptionsOfCustomer&id=' + jQuery(this).attr('data-attr'),
			//dataType: 'json',
			async	: false,
			success: function(json) {
				selected.parent().find('.more_subscriptions-data').html(json);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});
</script>
