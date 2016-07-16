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
$date_helper = Axisubs::date();
?>
<div class="panel panel-info cb-custm-chkbx">
	<div class="panel-heading"></div>

	<?php foreach($this->items as $k => $item){ ?>
		<div class="row">
		<div class="col-md-1 planpadding" >
			<dl>
				<dt>
					<?php echo $this->readable_items[$k]->axisubs_subscription_id; ?> 
				</dt>
			</dl>
		</div>
		<div class="col-md-3 planpadding" >
			<dl>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>:
					</span> 
					<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Subscriptions&id='.$item->axisubs_subscription_id.'&task=read');?>">
					<?php echo $item->axisubs_subscription_id; ?> </a>
				</dt>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_PLAN_NAME');?>:
					</span> 
					<?php echo $item->plan->name; ?>
				</dt>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_STATUS');?>:
					</span>
					<?php echo $this->readable_items[$k]->status; ?>
				</dt>
				
				<dt class="hide">
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_REFERENCES');?>:
					</span> 
					<?php echo $item->references; ?>
				</dt>
			</dl>
		</div>

		<div class="col-md-4 planpadding">
			<dl>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>:
					</span> 
					<?php  echo $item->subscriptioninfo->billing_first_name.$item->subscriptioninfo->billing_last_name; ?>
				</dt>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_USERS_FIELD_EMAIL');?>:
					</span>
					<?php  echo  $item->subscriptioninfo->billing_email; ?>
				</dt>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_CUSTOMER_COMPANY');?>:
					</span>
					<?php echo  $item->subscriptioninfo->billing_company; ?>
				</dt>
				<dt class="hide">
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_CUSTOMER_PHONE');?>:
					</span>
					<?php  echo $item->subscriptioninfo->billing_phone; ?>
				</dt>
			</dl>		
		</div>
		<div class="col-md-3 planpadding">
			<dl>
				<!-- <dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_CUSTOMERSS_CREATED_AT');?>:
					</span>
					<?php echo $item->created_on; ?>
				</dt> -->
				<?php if ( ! in_array($item->status, array('N','P') )) { ?>
	                 <dt>
	                    <span class="pcolor">
	                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?>
	                    </span>
	                    <?php echo $date_helper->get_formatted_date ( $item->current_term_start ); ?>
	                </dt>
	                <dt>
	                    <span class="pcolor">
	                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?>
	                    </span>
	                    <?php echo $date_helper->get_formatted_date ( $item->current_term_end ); ?>
	                </dt>
	                <dt>
	                    <span class="pcolor">
	                    	 <?php 
	                            $now = $date_helper->getCarbonDate(); 
	                            $term_start = $date_helper->getCarbonDate( $item->current_term_start );
	                            $term_end = $date_helper->getCarbonDate( $item->current_term_end );
	                            if ( $term_start->gte($now) ){
	                                // Trial yet to start - no of days to start
	                                $no_of_days_to_start = $now->diffInDays( $term_start ) ;
	                            ?>
	                                <?php echo JText::_('COM_AXISUBS_TERM_STATUS_DAYS_TO_START'); ?>
	                                <?php echo $no_of_days_to_start; ?> Days
	                            <?php 
	                            }elseif ( $term_start->lte($now) && $term_end->gte($now)){
	                                // trial is running - show no of days remaining
	                                $no_of_days_to_end = $term_end->diffInDays( $now ) ;
	                            ?>
	                                <?php echo $no_of_days_to_end; ?> 
	                                <?php echo JText::_('COM_AXISUBS_TERM_STATUS_DAYS_REMAINING'); ?>
	                            <?php
	                            }else {
	                                // Trial period ended
	                            ?>
	                                <?php echo JText::_('COM_AXISUBS_TERM_STATUS_ENDED'); ?>
	                            <?php    
	                            }
	                        ?>
	                    </span>
	                </dt>
	            <?php } ?>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('COM_AXISUBS_PLAN_PRICE');?>:
					</span>
					<?php echo $curr->format( $item->total, $item->currency); ?>
				</dt>
			</dl>
		</div>
		<div class="col-md-1 planpadding">
			<div class="table-col table-options">
				<dl>
					<dt>
						<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Subscriptions&id='.$item->axisubs_subscription_id.'&task=read');?>"class="aview">
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
								<a href="index.php?option=com_axisubs&view=Subscription&id=<?php echo $item->axisubs_subscription_id; ?>"><?php echo JText::_('COM_AXISUBS_CUSTOMER_ACTION_CHANGE_SUBSCRIPTION');?></a>
								</li>	
								<li>
									<a href="#"><?php echo JText::_('COM_AXISUBS_CUSTOMER_ACTION_CHANGE_CUSTOMER_DETAILS');?></a>
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


<style>
.chzn-container-single .chzn-single{
margin-bottom:10px;
}
 .btn-toolbar > .btn-group{
margin-left: 14px !important;
margin-bottom:10px;
}
.input-append{
	display:inline-flex;
	margin-left: 10px;
}
input#filter_search{
	width: 400px;
}
</style>