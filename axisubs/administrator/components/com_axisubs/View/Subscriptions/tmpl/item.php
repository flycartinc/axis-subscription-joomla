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
                                    <?php echo $date_helper->get_formatted_date ( $this->item->current_term_end ); ?>
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

<!-- Current term Charges -->
            <h2 class="viewtitle-text viewh">
               <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_CHARGES');?>
            </h2>
            <div class="viewcontent2">No Charges found for current term</div>
<!--Current term Charges -->

<!-- NAVIGATION TAB -->
            <ul class="nav nav-tabs  viewsubscriptionTab">
                    <li role="presentation" class="active">
                        <a data-toggle="tab" href="#view-invoice"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INVOICES');?></a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#view-credit-note"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CREDIT_NOTE');?></a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#view-transactions"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTIONS');?></a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#view-eventLogs"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_EVENTS');?></a>
                    </li>
                    <li role="presentation">
                    <a data-toggle="tab" href="#view-email"><?php echo JText::_('COM_AXISUBS_USERS_FIELD_EMAIL');?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="view-invoice" class="tab-pane fade in active">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/invoices/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>invoices</a> found for this subscription</span>
                        </div>
                    </div>
                    <div id="view-transactions" class="tab-pane fade">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/transactions/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>transactions</a> found for this subscription</span>
                        </div>
                    </div>
                    <div id="view-credit-note" class="tab-pane fade">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/credit_notes/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>credit notes</a> found for this subscription</span>
                        </div>
                    </div>
                    <div id="view-eventLogs" class="tab-pane fade">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/events/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>events</a> found for this customer</span>
                        </div>
                    </div>
                    <div id="view-email" class="tab-pane fade">
                        <div class="view-bootstrap-filter">
                            <form class="form-inline" action="/events/tab" data-view-table-option-form="form">    
                            </form>
                        </div>
                        <div class="view-no-result view-blank-space">
                            <span>No <a>emails</a> found for this customer</span>
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
            <li>Signed up on 23-Oct-2014 12:55</li>
            <li>Started on 23-Oct-2014 12:55</li>
            <li>Trial Ends on 07-Nov-2014 12:55</li>
        </ul>
    </div>
</div>
