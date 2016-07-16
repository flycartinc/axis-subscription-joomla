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

<!--CUSTOMER DETAILS -->
<div class="axisubs-bs3">
    <div class="fstyle">
        <div class="viewheading"></div>
        
            <div class="viewbody" style="position:relative;">
                <div id="customer-details" style="position:relative">
                    <h2 class="viewtitle-text viewh">
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_CUSTOMER_DETAILS');?>
                    </h2>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-8 ">
                            <div class="row">
                                <div class="col-md-6 ">
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_ID');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_FIRST_NAME');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_LAST_NAME');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?>
                                        </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="padbot"> 
                                        <?php if($this->item->axisubs_customer_id==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->axisubs_customer_id;
                                        endif ?>                                         
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->first_name==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->first_name;
                                        endif ?>       
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->last_name==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->last_name; 
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->email==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->email; 
                                        endif ?>                                         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!--END OF CUSTOMER DETAILS -->




<!--BILLING INFO-->
                <div id="customer-details" style="position:relative">
                    <h2 class="viewtitle-text viewh">
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_CUSTOMER_DETAILS');?>
                    </h2>
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-8 ">
                            <div class="row">
                                <div class="col-md-6 ">
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_ADDRESS1');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_ADDRESS2');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_CITY');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_STATE');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_COUNTRY');?> 
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_USERS_FIELD_ZIP');?>
                                        </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="padbot">
                                        <?php if($this->item->address1==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->address1; 
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->address2==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->address2; 
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->city==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->city; 
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->state==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->state; 
                                        endif ?>  
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->country==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->country; 
                                        endif ?> 
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->zip==""): 
                                            echo "<br>"; 
                                        else :
                                            echo $this->item->zip; 
                                        endif ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!--END OF BILLING INFO-->

<!--SUBSCRIPTION  DETAILS -->
              <!--   <div id="customer-details" style="position:relative">
                    <h2 class="viewtitle-text viewh"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INFO');?> </h2>   
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                    <?php $this->item->subscriptions->getData(); ?>
                        <?php echo $this->loadTemplate('subscription');?> 
                    </div>
                </div> -->
    
   
<!--END OF SUBSCRIBE DETAILS -->

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
<!--End of Main Body-->




<!--SIDE BAR-->
        <div class="viewsidebar">   
            <h2 class="viewtitle-text viewh actionsub"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS');?></h2>
            <div id="viewactions" class="viewsubscription-sidebar">
                <dl>
                    <dt>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CREATE_NEW_SUBSCRIPTION_DESC');?>
                    </dt>
                    <dd>
                        <a id="" class="btn btn-default viewaction-button" href="index.php?option=com_axisubs&view=Subscriptions&task=add&user_id=<?php echo $this->item->user_id; ?>" >
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CREATE_NEW_SUBSCRIPTION');?>
                        </a>
                    </dd>        
                    <dt>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CHANGE_CUSTOMER_DETAILS_DESC');?>
                    </dt>
                    <dd>
                        <a id="cust-update-link" class="btn btn-default viewaction-button" href="index.php?option=com_axisubs&view=Customer&id=<?php echo $this->item->axisubs_customer_id; ?>">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CHANGE_CUSTOMER_DETAILS');?>
                        </a>
                    </dd>
                    <!-- <dt>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_REQUEST_PAYMENT_METHOD_DESC');?>
                    </dt>
                    <dd>
                        <a id="cust-update-link" class="btn btn-default viewaction-button" href="#">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_REQUEST_PAYMENT_METHOD');?>
                        </a>
                    </dd>
                    <dt>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_ADD_BILLING_INFO_DESC');?>
                    </dt>
                    <dd>
                        <a class="btn btn-default viewaction-button" href="#" id="addresses.new">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_ADD_BILLING_INFO');?>
                        </a>
                    </dd>
                    <dt>
                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_SUBSCRIPTION_DESC');?>
                    </dt>
                    <dd>
                        <a id="sub-act-add-bil" class="btn btn-default viewaction-button" href="#">
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_CUSTOMER');?>
                        </a>
                    
                    </dd> -->
                </dl>
            </div>
        </div>
    </div>
</div>