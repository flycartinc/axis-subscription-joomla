<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;

$this->subscriptioninfo = $this->item->subscriptioninfo ;
$curr = Axisubs::currency();
$date_helper = Axisubs::date();
$status_helper = Axisubs::status();

JHTML::_('behavior.modal');

//To trigger plugin for loading additional buttons
$plugin_helper = Axisubs::plugin();
$this->additionalButtons = $plugin_helper->eventWithHtml('LoadButtonsInSubscriptionDetail', array($this, $this->item));
?>

<div class="axisubs-bs3">
    <div class="fstyle">
        <div class="viewheading">
        </div>
        <div class="viewbody" style="position:relative;">
            <div id="customer-details" style="position:relative">
                <h2 class="viewtitle-text viewh"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INFO');?> 
                </h2>
                <div class="row">
	               <div class="col-md-4">
                        <?php echo $this->loadTemplate('customer');?> 
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-6">
                            <table class="table borderless">
                                <tr>
                                    <div class="viewcontent-text">
                                        <td class="">
                                            <?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>
                                        </td>
                                        <td>
                                            <?php echo $this->item->axisubs_subscription_id; ?>
                                        </td> 
                                    </div>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN');?>
                                    </td>
                                    <td>
                                        <a href="index.php?option=com_axisubs&view=Plans&id=<?php echo $this->item->plan->axisubs_plan_id; ?>&task=read" class="tu" id="plans.details">
                                            <?php echo  $this->item->plan->name; ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN_AMOUNT');?>
                                    </td>
                                    <td class="">
                                        <span class="plan_amount">
                                            <?php echo $curr->format( $this->item->total, $this->item->currency_code); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('AXISUBS_CODE_SUBSCRIPTION_PLAN_PRICE');?>
                                    </td>
                                    <td class="">
                                        <span class="plan_amount">
                                            <?php echo $curr->format( $this->item->plan_price, $this->item->currency); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php if($this->item->setup_fee > 0){ ?>
                                    <tr>
                                        <td class="">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE');?>
                                        </td>
                                        <td class="">
                                            <span class="plan_amount">
                                                <?php echo $curr->format( $this->item->setup_fee, $this->item->currency); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php } if($this->item->tax > 0){ ?>
                                    <?php
                                    $paramsArray = $this->item->params;
                                    if(isset($paramsArray['tax_details']) && !empty($paramsArray['tax_details'])){
                                        foreach($paramsArray['tax_details'] as $taxDetail){
                                            ?>
                                            <tr>
                                                <td class="">
                                                    <?php echo $taxDetail['label']; ?>(<?php echo $taxDetail['rate']; ?>%)
                                                </td>
                                                <td class="">
                                                    <span class="plan_amount">
                                                        <?php echo $curr->format( $taxDetail['price'], $this->item->currency); ?>
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
                                                <?php echo $curr->format( $this->item->tax, $this->item->currency); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php }
                                if($this->item->discount > 0){ ?>
                                    <tr>
                                        <td class="">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_DISCOUNT_AMOUNT');?>
                                        </td>
                                        <td class="">
                                            <span class="plan_amount">
                                                <?php echo $curr->format( $this->item->discount, $this->item->currency); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php }
                                if($this->item->discount_tax > 0){ ?>
                                    <tr>
                                        <td class="">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_DISCOUNT_TAX');?>
                                        </td>
                                        <td class="">
                                            <span class="plan_amount">
                                                <?php echo $curr->format( $this->item->discount_tax, $this->item->currency); ?>
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
                                                <td class="center p-t-20">
                                                    <span class="label label-<?php echo $status_helper->get_label( $this->item->status ); ?> status-label" original-title=""> 
                                                        <?php echo $status_helper->get_text( $this->item->status ); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div> 
                                </div>
                                <div class="viewdetail">
                                    <div class="viewdetail-buttons_con">
                                        <?php echo $this->additionalButtons; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table borderless">
                            <?php if ( in_array($this->item->status, array('N','P') ) ) : 
                                    $now = $date_helper->getCarbonDate(); 
                                    $scheduled_start_date = '';
                                    if ($this->item->start_date != '0000-00-00 00:00:00'){
                                        $scheduled_start_date = $date_helper->getCarbonDate($this->item->start_date);
                                    } 
                                    if (!empty($scheduled_start_date) && $now->lt( $scheduled_start_date ) ) {
                                    ?>
                                <tr>
                                    <td colspan="2"> <span> <?php echo JText::_('COM_AXISUBS_SCHEDULED_START_DATE'); ?> </span> </td>
                                    <td>
                                        <?php echo $date_helper->get_formatted_date ( $this->item->start_date ) ; ?>
                                    </td>
                                </tr>    
                            <?php }
                                endif; ?>                
                            <?php if ( $this->item->hasTrial() && ! in_array($this->item->status, array('N','P') ) ) { ?>
                                <tr>
                                    <td colspan="2"> <span> <?php echo JText::_('COM_AXISUBS_TRIAL_PERIOD'); ?> </span> </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $date_helper->get_formatted_date ( $this->item->trial_start ) ; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $date_helper->get_formatted_date ( $this->item->trial_end ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <?php echo JText::_('COM_AXISUBS_TRIAL_STATUS'); ?> </td>
                                    <td> 
                                        <?php 
                                            $now = $date_helper->getCarbonDate(); 
                                            $trial_start = $date_helper->getCarbonDate( $this->item->trial_start );
                                            $trial_end = $date_helper->getCarbonDate( $this->item->trial_end );
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
                            <?php if ( ! in_array($this->item->status, array('N','P') )) : ?>
                                <tr>
                                    <td colspan="2"> <span>  <?php echo JText::_('COM_AXISUBS_CURRENT_TERM'); ?></span> </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php echo $date_helper->get_formatted_date ( $this->item->current_term_start ); ?>
                                    </td>
                                </tr>
                                 <tr>
                                    <td class="">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?>
                                    </td>
                                    <td class="">
                                    <?php
                                    if($this->item->plan->plan_type){
                                    echo $date_helper->get_formatted_date ( $this->item->current_term_end );
                                    } else {
                                        echo JText::_('COM_AXISUBS_PLAN_RECURRING_UNLIMIT');
                                    } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        
                                    </td>
                                    <td>
                                         <?php 
                                            $now = $date_helper->getCarbonDate(); 
                                            $term_start = $date_helper->getCarbonDate( $this->item->current_term_start );
                                            $term_end = $date_helper->getCarbonDate( $this->item->current_term_end );
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
<!--END OF CUSTOMER DETAILS -->

<!-- NAVIGATION TAB -->
            <ul class="nav nav-tabs  viewsubscriptionTab">
                <li role="presentation" class="active">
                    <a data-toggle="tab" href="#view-transactions"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTIONS');?></a>
                </li>
                <!--<li role="presentation">
                    <a data-toggle="tab" href="#view-invoice"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INVOICES');?></a>
                </li>-->
                <li role="presentation">
                    <a data-toggle="tab" href="#view-credit-note"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_OTHERS');?></a>
                </li>
            </ul>
                <div class="tab-content">
                    <div id="view-transactions" class="tab-pane fade in active">
                        <div class="view-no-result view-blank-space">
                            <?php if(!isset($this->item->transaction->axisubs_transaction_id)){ ?>
                            <span><?php echo JText::_('COM_AXISUBS_NO_TRANSACTION_AVAILABLE'); ?></span>
                            <?php } else {
                                $transactionAvailable = 0;
                                ?>
                                <div class="transaction-history-c">
                                    <?php if($this->item->transaction->transaction_status){ ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_NO_TRANSACTION_STATUS'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <?php
                                            $transactionAvailable = 1;
                                            echo $status_helper->get_transactionStatusText($this->item->transaction->transaction_status);
                                            ?>
                                        </span>
                                    </div>
                                    <?php }
                                    if($this->item->transaction->payment_processor != ''){
                                    ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_PAYMENT_TYPE'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <?php
                                            $transactionAvailable = 1;
                                            echo JText::_('COM_AXISUBS_'.strtoupper($this->item->transaction->payment_processor));
                                            ?>
                                        </span>
                                    </div>
                                    <?php }
                                    if($this->item->transaction->processor_status != ''){
                                    ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PAYMENT_STATUS'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <?php
                                            $transactionAvailable = 1;
                                            echo strtoupper($this->item->transaction->processor_status);
                                            ?>
                                        </span>
                                    </div>
                                    <?php }
                                    if($this->item->transaction->transaction_ref_id != ''){
                                    ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PAYMENT_TRANSACTION_REFFERENCE_ID'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <?php
                                            $transactionAvailable = 1;
                                            echo strtoupper($this->item->transaction->transaction_ref_id);
                                            ?>
                                        </span>
                                    </div>
                                    <?php }
                                    if($this->item->transaction->transaction_currency != ''){
                                    ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_TRANSACTION_PAID_AMOUNT'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <?php
                                            $transactionAvailable = 1;
                                            echo strtoupper($this->item->transaction->transaction_amount).' '.$this->item->transaction->transaction_currency;
                                            ?>
                                        </span>
                                    </div>
                                    <?php }
                                    if($this->item->transaction->postpayment != ''){
                                        $transactionAvailable = 1;
                                    ?>
                                    <div class="transaction-history-item">
                                        <span class="transaction-history-text">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PAYMENT_RESPONSE'); ?>
                                        </span>
                                        <span class="transaction-history-data">
                                            <a href="#datatoload" class="modal"><?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PAYMENT_RESPONSE_BUTTON'); ?></a>
                                            <div id="datatoload">
                                                <h3><?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PAYMENT_RESPONSE'); ?></h3>
                                                <div class="datatoload-con">
                                                    <?php echo $this->item->transaction->postpayment; ?>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </div>
                                <?php if(!$transactionAvailable){?>
                                    <span><?php echo JText::_('COM_AXISUBS_NO_TRANSACTION_AVAILABLE'); ?></span>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <!--<div id="view-invoice" class="tab-pane fade in active">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/invoices/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>invoices</a> found for this subscription</span>
                        </div>
                    </div>-->

                    <div id="view-credit-note" class="tab-pane fade">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/others/tab" data-view-table-option-form="form">
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>data</a> found for this subscription</span>
                        </div>
                    </div>
                </div>
 <!-- END OF NAVIGATION TAB -->   





        </div>
    </div>
<!--End of Main Body-->

<!--SIDE BAR-->
    <div class="viewsidebar">   
           <h2 class="viewtitle-text viewh actionsub">
                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS');?>
            </h2>
        <div id="viewactions" class="viewsubscription-sidebar">
            <dl>
                <dt>
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CHANGE_CUSTOMER_DETAILS_DESC');?>
                </dt>
                <dd>
                    <a id="cust-update-link" class="btn btn-default viewaction-button" 
                         href="index.php?option=com_axisubs&view=SubscriptionInfo&task=edit&id=<?php echo $this->item->subscriptioninfo->axisubs_subscriptioninfo_id; ?>">
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CHANGE_CUSTOMER_DETAILS');?>
                    </a>
                </dd>
                
                <?php if ( in_array($this->item->status, array('N'))) : ?>
                <dt> 
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_PAYMENT_COMPLETE_DESC');?>
                </dt>
                <dd>
                    <a id="" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=mark_payment_complete&id=<?php echo $this->item->axisubs_subscription_id; ?>"> 
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_PAYMENT_COMPLETE');?>
                    </a>
                </dd>
                <?php endif; ?>

                <?php if ( $this->item->plan->hasTrial() ) : ?>

                <?php if ( in_array($this->item->status, array('P'))) : ?>
                <dt> 
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_START_TRIAL_DESC');?>
                </dt>
                <dd>
                    <a id="" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=startTrial&id=<?php echo $this->item->axisubs_subscription_id; ?>" > 
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_START_TRIAL'); ?>
                    </a>
                </dd>
                <?php endif; ?>

                <?php /*if ( in_array($this->item->status, array('T'))) : ?>
                <dt> 
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_EXTEND_TRIAL_DESC');?>
                </dt>
                <dd>
                    <a id="" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=extendTrial&id=<?php echo $this->item->axisubs_subscription_id; ?>" > 
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_EXTEND_TRIAL');?>
                    </a>
                </dd>
                <?php endif; */?>
                <?php endif; ?>
                
                <?php if ( in_array($this->item->status, array('N','T','A','F'))) : ?>
                <dt> 
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_PENDING_DESC');?>
                </dt>
                <dd>
                    <a id="" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=markPending&id=<?php echo $this->item->axisubs_subscription_id; ?>" >
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_PENDING');?>
                    </a>
                </dd>
                <?php endif; ?>
                
                <?php if ( in_array( $this->item->status, array('N','T','P','F') ) ) : ?>
                <dt> 
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_ACTIVE_DESC');?>
                </dt>
                <dd>
                    <a id="" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=markActive&id=<?php echo $this->item->axisubs_subscription_id; ?>"
                        >
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_ACTIVE');?>
                    </a>
                </dd>
                <?php endif; ?>

                <?php if ( in_array( $this->item->status, array('N','T','P','A','F','D') ) ) : ?>
                <dt>
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CANCEL_SUBSCRIPTION_DESC');?>
                </dt>
                <dd>
                    <a class="btn btn-default viewaction-button"  href="index.php?option=com_axisubs&view=Subscription&task=markCancel&id=<?php echo $this->item->axisubs_subscription_id; ?>" id="subscriptions.cancel">
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CANCEL_SUBSCRIPTION');?>
                    </a>
                </dd>
                <?php endif; ?>
                <?php if ( in_array( $this->item->status, array('N','T','P','A','F','C') ) ) : ?>
                <dt>
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_SUBSCRIPTION');?>
                </dt>
                <dd> 
                    <a id="viewdelete-sub" class="btn btn-default viewaction-button" 
                        href="index.php?option=com_axisubs&view=Subscription&task=markDelete&id=<?php echo $this->item->axisubs_subscription_id; ?>"
                        >
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_SUBSCRIPTION');?>
                    </a>
                </dd>
                <?php endif; ?>
            </dl>
        </div>
    </div>
    <div class="viewtimeline-container">
        <h3><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE');?></h3>
        <ul>
            <?php
            echo "<li>";
            echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE_SIGNEDUP_ON').' <b>'.$date_helper->format($this->item->created_on);
            echo "</b></li>";
            echo "<li>";
            echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE_STARTED_ON').' <b>'.$date_helper->format($this->item->current_term_start);
            echo "</b></li>";
            echo "<li>";
            echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE_END_ON').' <b>'.$date_helper->format($this->item->current_term_end);
            echo "</b></li>";
            if($this->item->trial_end != '0000-00-00 00:00:00'){ 
                echo "<li>";
                echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE_TRIAL_STARTS_ON').' <b>'.$date_helper->format($this->item->trial_end);
                echo "</b></li>";
            } ?>
        </ul>
    </div>
</div>
