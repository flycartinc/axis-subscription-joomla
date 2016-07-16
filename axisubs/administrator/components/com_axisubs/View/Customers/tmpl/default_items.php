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
					<?php echo JText::_('COM_AXISUBS_CUSTOMER_ID');?>:
				</span> 
				<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Customers&id='.$item->axisubs_customer_id.'&task=read');?>">
					<?php echo $item->axisubs_customer_id; ?>
				</a>
				</dt>
				<?php if(0 == 0) :?>
					<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_SUBSCRIPITION');?>:
					</span>
					<span class="pactive" original-title="">1 active
					</span>
					</dt>
						<?php elseif(1 == 1):?>
							<dt>
								<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_SUBSCRIPITION');?>:
								</span>
								<span class="pdeactive" original-title="">cancelled
								</span>
							</dt>
						<?php elseif(2 == 2):?>
							<dt>
								<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_SUBSCRIPITION');?>:
								</span>
								<span class="trail" original-title=""> Trial
								</span>
							</dt>
						<?php else:?>
							<dt>
								<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_SUBSCRIPITION');?>:
								</span>
								<span class="nonrenewal" original-title="">Non Renewing
								</span>
							</dt>
				<?php endif;?>
				<dt>
										
				</dt>
			</dl>
		</div>


		<div class="col-md-4 planpadding">
			<dl>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>:
					</span>
					<?php  echo $item->first_name." ".$item->last_name; ?>
				</dt>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?>: 
					</span><?php  echo $item->email; ?>
				</dt>
				<dt>
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_COMPANY');?>:
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
					<span class="pcolor"><?php echo JText::_('COM_AXISUBS_CUSTOMER_PHONE');?>:
					</span><?php echo $item->phone; ?>
				</dt>
			</dl>
		</div>

		<div class="col-md-1 planpadding">
			<div class="table-col table-options">
				<dl>
					<dt>
						<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Customers&id='.$item->axisubs_customer_id.'&task=read');?>"class="aview">
							<i class="fa fa-align-justify"></i>
						</a>
					</dt>
			
					<dt>
						<div class="dropdown dropplan">
							<a class="droupdown-toggle" href="#" data-toggle="dropdown">
							<span class="icon-cog"></span>
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

	<?php }  ?>
	<?php if ($choose_flag) :?>
		<input type="text" name="flag" value="<?php echo \JFactory::getApplication()->input->get('flag',''); ?>" />
	<?php endif; ?>
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
