<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;

$curr = Axisubs::currency();
$status_helper = Axisubs::status();
$date_helper = Axisubs::date();
?>
<div class="panel panel-info cb-custm-chkbx">
	<div class="panel-heading"></div>

	<?php foreach($this->items as $k => $item){ ?>
		<div class="row row-item">
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
						<a href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Subscriptions&id='.$item->axisubs_subscription_id.'&task=read');?>">
								<?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?><?php echo $item->axisubs_subscription_id; ?> </a>
					</span>
				</dt>
				<dt class="plan">
					<span class="pcolor plan_name">
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

		<div class="col-md-3 planpadding">
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
					<a href="mailto:<?php  echo  $item->subscriptioninfo->billing_email; ?>"><?php  echo  $item->subscriptioninfo->billing_email; ?></a>
				</dt>
				<dt>
					<span class="pcolor">
						<?php echo JText::_('AXISUBS_ADDRESS_COMPANY_NAME');?>:
					</span>
					<?php echo  $item->subscriptioninfo->billing_company; ?>
				</dt>
				<dt class="hide">
					<span class="pcolor">
						<?php echo JText::_('AXISUBS_ADDRESS_PHONE');?>:
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
	                    <?php
						if($item->plan->plan_type){
							echo $date_helper->get_formatted_date ( $item->current_term_end );
						} else {
							echo JText::_('COM_AXISUBS_PLAN_RECURRING_UNLIMIT');
						}
						?>
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
					<span class="axisubs_plan_price-bold"><?php echo $curr->format( $item->total, $item->currency); ?></span>
				</dt>
			</dl>
		</div>
		<div class="col-md-2 planpadding">
			<div class="table-col table-options">
				<dl>
					<dt>
						<a class="btn-small hasTooltip" data-original-title="View" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=Subscriptions&id='.$item->axisubs_subscription_id.'&task=read');?>">
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
