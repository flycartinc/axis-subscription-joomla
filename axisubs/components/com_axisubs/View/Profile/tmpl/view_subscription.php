<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
$subscription = $this->subscription;
$this->subscriptioninfo = $this->subscription->subscriptioninfo ;
$curr = Axisubs::currency();
$date_helper = Axisubs::date();
$status_helper = Axisubs::status();
?>
<div class="axisubs-bs3 ">
<div class="row">
	<div class="col-md-1">
		<a href="<?php echo \JRoute::_('index.php?option=com_axisubs&view=Profile'); ?>" class="btn btn-success">
			<?php echo JText::_( 'COM_AXISUBS_BACK_TO_PROFILE'); ?>
		</a>
	</div>
</div>
<!-- Subscription information -->
<div >
    <h4 class="viewtitle-text viewh"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INFO');?> 
    </h4>
    <div class="row">
        <div class="col-md-10">
            <div class="col-md-6">
                <table class="table borderless">
                    <tr>
                        <div class="viewcontent-text">
                            <td class="">
                                <?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>
                            </td>
                            <td>
                                <?php echo $this->subscription->axisubs_subscription_id; ?>
                            </td> 
                        </div>
                    </tr>
                    <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN');?>
                        </td>
                        <td>
                            <a href="index.php?option=com_axisubs&view=plan&slug=<?php echo $this->subscription->plan->slug; ?>" class="tu" id="plans.details">
                                <?php echo  $this->subscription->plan->name; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN_AMOUNT');?>
                        </td>
                        <td class="">
                            <span class="plan_amount">
                                <?php echo $curr->format( $this->subscription->total, $this->subscription->currency); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="">
                            <?php echo JText::_('AXISUBS_CODE_SUBSCRIPTION_PLAN_PRICE');?>
                        </td>
                        <td class="">
                            <span class="plan_amount">
                                <?php echo $curr->format( $this->subscription->plan_price, $this->subscription->currency); ?>
                            </span>
                        </td>
                    </tr>
                    <?php if($this->subscription->setup_fee > 0){ ?>
                        <tr>
                            <td class="">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE');?>
                            </td>
                            <td class="">
                            <span class="plan_amount">
                                <?php echo $curr->format( $this->subscription->setup_fee, $this->subscription->currency); ?>
                            </span>
                            </td>
                        </tr>
                    <?php } if($this->subscription->tax > 0){ ?>
                        <?php
                        $paramsArray = $this->subscription->params;
                        if(isset($paramsArray['tax_details']) && !empty($paramsArray['tax_details'])){
                            foreach($paramsArray['tax_details'] as $taxDetail){
                                ?>
                                <tr>
                                    <td class="">
                                        <?php echo $taxDetail['label']; ?>(<?php echo $taxDetail['rate']; ?>%)
                                    </td>
                                    <td class="">
                                        <span class="plan_amount">
                                            <?php echo $curr->format( $taxDetail['price'], $this->subscription->currency); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        <tr>
                            <td class="">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_TAX');?>
                            </td>
                            <td class="">
                                <span class="plan_amount">
                                    <?php echo $curr->format( $this->subscription->tax, $this->subscription->currency); ?>
                                </span>
                            </td>
                        </tr>
                    <?php }
                    if($this->subscription->discount > 0){ ?>
                        <tr>
                            <td class="">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_DISCOUNT_AMOUNT');?>
                            </td>
                            <td class="">
                            <span class="plan_amount">
                                <?php echo $curr->format( $this->subscription->discount, $this->subscription->currency); ?>
                            </span>
                            </td>
                        </tr>
                    <?php }
                    if($this->subscription->discount_tax > 0){ ?>
                        <tr>
                            <td class="">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_DISCOUNT_TAX');?>
                            </td>
                            <td class="">
                            <span class="plan_amount">
                                <?php echo $curr->format( $this->subscription->discount_tax, $this->subscription->currency); ?>
                            </span>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </table>
            </div>
            <div class="col-md-6">
                <div class="viewhighlight-container" >
                    <div class="viewdetail-box">
                        <div class="viewhighlight-box highlight-border">
                            <span class="viewh1"><?php echo JText::_('AXISUBS_SUBSCRIPTION_STATUS');?>
                            </span>
                            <table class="table borderless">
                                <tr>
                                    <td class="center">
                                        <span class="label label-<?php echo $status_helper->get_label( $this->subscription->status ); ?> status-label" original-title=""> 
                                            <?php echo $status_helper->get_text( $this->subscription->status ); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div> 
                    </div>
                    <div class="viewdetail-box">
                        <div class="viewdetail-buttons_con">
                            <?php echo $this->additionalButtons; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table borderless">
                <?php if ( in_array($this->subscription->status, array('N','P') ) ) : 
                        $now = $date_helper->getCarbonDate(); 
                        $scheduled_start_date = '';
                        if ($this->subscription->start_date != '0000-00-00 00:00:00'){
                            $scheduled_start_date = $date_helper->getCarbonDate($this->subscription->start_date);
                        } 
                        if (!empty($scheduled_start_date) && $now->lt( $scheduled_start_date ) ) {
                        ?>
                    <tr>
                        <td colspan="2"> <span> <?php echo JText::_('COM_AXISUBS_SCHEDULED_START_DATE'); ?> </span> </td>
                        <td>
                            <?php echo $date_helper->get_formatted_date ( $this->subscription->start_date ) ; ?>
                        </td>
                    </tr>    
                <?php }
                    endif; ?>                
                <?php if ( $this->subscription->hasTrial() && ! in_array($this->subscription->status, array('N','P') ) ) { ?>
                    <tr>
                        <td colspan="2"> <span> <?php echo JText::_('COM_AXISUBS_TRIAL_PERIOD'); ?> </span> </td>
                    </tr>
                     <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON');?>
                        </td>
                        <td class="">
                        <?php echo $date_helper->get_formatted_date ( $this->subscription->trial_start ) ; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON');?>
                        </td>
                        <td class="">
                        <?php echo $date_helper->get_formatted_date ( $this->subscription->trial_end ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td> <?php echo JText::_('COM_AXISUBS_TRIAL_STATUS'); ?> </td>
                        <td> 
                            <?php 
                                $now = $date_helper->getCarbonDate(); 
                                $trial_start = $date_helper->getCarbonDate( $this->subscription->trial_start );
                                $trial_end = $date_helper->getCarbonDate( $this->subscription->trial_end );
                                if ( $trial_start->gte($now) ){
                                    // Trial yet to start - no of days to start
                                    $no_of_days_to_start = $now->diffInDays( $trial_start ) ;
                                ?>
                                    <?php echo JText::_('COM_AXISUBS_TRIAL_STATUS_DAYS_TO_START'); ?>
                                    <?php echo $no_of_days_to_start; ?> Days
                                <?php 
                                }elseif ( $trial_start->lte($now) && $trial_end->gte($now)){
                                    // trial is running - show no of days remaining
                                    $no_of_days_to_end = $trial_end->diffInDays( $now ) ;
                                ?>
                                    <?php echo JText::_('COM_AXISUBS_TRIAL_STATUS_DAYS_TO_END'); ?>
                                    <?php echo $no_of_days_to_end; ?> Days
                                <?php
                                }else {
                                    // Trial period ended
                                ?>
                                    <?php echo JText::_('COM_AXISUBS_TRIAL_STATUS_DAYS_TO_ENDED'); ?>
                                <?php    
                                }
                            ?>
                        </td>
                    </tr>
                <?php } // end hasTrial check ?>
                <?php if ( ! in_array($this->subscription->status, array('N','P') )) : ?>
                    <tr>
                        <td colspan="2"> <span>  <?php echo JText::_('COM_AXISUBS_CURRENT_TERM'); ?></span> </td>
                    </tr>
                     <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?>
                        </td>
                        <td class="">
                        <?php echo $date_helper->get_formatted_date ( $this->subscription->current_term_start ); ?>
                        </td>
                    </tr>
                     <tr>
                        <td class="">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?>
                        </td>
                        <td class="">
                        <?php
                        if($this->subscription->plan->plan_type){
                            echo $date_helper->get_formatted_date ( $this->subscription->current_term_end );
                        } else {
                            echo JText::_('COM_AXISUBS_PLAN_RECURRING_UNLIMIT');
                        }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            
                        </td>
                        <td>
                             <?php 
                                $now = $date_helper->getCarbonDate(); 
                                $term_start = $date_helper->getCarbonDate( $this->subscription->current_term_start );
                                $term_end = $date_helper->getCarbonDate( $this->subscription->current_term_end );
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
                        </td>
                    </tr>
                <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end subscription information -->

<!-- Billing details -->

<div class="row">
	<h4 class="viewtitle-text viewh">
		<?php echo JText::_('COM_AXISUBS_BILLING_BASIC_TITLE');?> 
	</h4>
    <div class="col-md-7">
        <table class="table table-bordered">
            <tr>
                <td>
                    <?php echo JText::_('COM_AXISUBS_CUSTOMER_NAME'); ?>
                </td>
                <td>
                    <?php echo $this->subscription->subscriptioninfo->billing_first_name; ?>
                    <?php echo $this->subscription->subscriptioninfo->billing_last_name; ?>
                </td>
            </tr>
            <?php if (!empty($this->subscription->subscriptioninfo->billing_company)): ?>
            <tr>
                <td>
                    <?php echo JText::_('AXISUBS_ADDRESS_COMPANY_NAME'); ?>
                </td>
                <td>
                    <?php echo $this->subscription->subscriptioninfo->billing_company; ?>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>
                    <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL'); ?>
                </td>
                <td>
                    <?php echo $this->subscription->subscriptioninfo->billing_email; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo JText::_('COM_AXISUBS_CUSTOMER_ADDRESS'); ?>
                </td>
                <td>
                    <?php echo $this->subscription->subscriptioninfo->billing_address1; ?>
                    <?php echo $this->subscription->subscriptioninfo->billing_address2; ?>,
                    <?php echo $this->subscription->subscriptioninfo->billing_city; ?>, <br>
                    <?php
                    $stateSelected = Select::getZones($this->subscription->subscriptioninfo->billing_country);
                    if(isset($stateSelected[$this->subscription->subscriptioninfo->billing_state])){
                        echo $stateSelected[$this->subscription->subscriptioninfo->billing_state].', ';
                    } ?>
                    <?php echo Select::decodeCountry($this->subscription->subscriptioninfo->billing_country); ?> <br>
                    <?php echo $this->subscription->subscriptioninfo->billing_zip; ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- end billing details -->

</div>