<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
//$customer = $this->item->customer ;

use Flycart\Axisubs\Admin\Helper\Axisubs;


$curr = Axisubs::currency();
$status_helper = Axisubs::status();
?>

<!--PLan -->
<div class="axisubs-bs3">
    <div class="fstyle">
        <div class="viewheading">
        </div>
            <div class="viewbody" style="position:relative;">
                <div id="customer-details" style="position:relative">
                    <h1 class="viewtitle-text viewh">
                    <?php echo $this->item->name; ?>
                    </h1>
                    <div class="row">
                        <div class="col-md-2"></div>
	                    <div class="col-md-8">
                            <table class="table  borderless">
                                <div class="viewcontent-text">
                                    <tr>
                                        <td class="text-right">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_NAME');?>
                                        </td>                                       
                                        <td>
                                            <a href="#"><?php echo $this->item->name; ?></a>
                                        </td>
                                    </tr>
	                                <tr>
                                        <td class="text-right">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ID');?>
                                        </td>
                                        <td><?php echo $this->item->axisubs_plan_id; ?></td>
                                    </tr> 
                                    <tr>
   	                                    <td class="text-right"> 
                                        <?php echo JText::_('AXISUBS_SUBSCRIPTION_STATUS');?>&nbsp;&nbsp;</td>
                                        <td>
                                            <?php if($this->item->enabled==1) :?>                                           
                                                <span class="pactive" original-title=""> 
                                                <?php echo JText::_('AXISUBS_STATUS_CONFIRMED');?>
                                                </span>
                                           <?php else : ?>
                                                <span class="pdeactive" original-title=""> 
                                                <?php echo JText::_('AXISUBS_STATUS_EXPIRED');?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr >
   	                                    <td class="text-right"> 
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TAXABLE');?>
                                        </td>
   	                                    <td>Yes</td>
                                    </tr>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
<!--END OF PLAN-->





<!--Pricing & Billing Interval -->
                <h2 class="viewtitle-text viewh">
                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_PRICING_BILLING_INTERVAL');?> 
                </h2>   
                <div class="row">
                    <div class="col-md-8">
                        <div style="position: relative;">
                            <table class="table borderless">
                                <tr>
                                    <div class="viewcontent-text">
                                        <td class="text-right">
                                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_RECURRING_CHARGE_MODEL');?>
                                        </td>
                                        <td>
                                            <?php echo $this->item->charge_model; ?>
                                        </td> 
                                    </div>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php echo JText::_('COM_AXISUBS_PLAN_CONTENT_PRICES');?>
                                    </td>
                                    <td><?php echo $curr->format( $this->item->getPrice(), $this->item->currency);?> </td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE');?>
                                    </td>
                                <td class=""><?php echo $curr->format( $this->item->getSetupCost(), $this->item->currency);?></td>
                                </tr>
                                 <tr>
                                    <td class="text-right">
                                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_PERIOD');?>
                                    </td>
                                    <td><?php echo $this->item->period."Days"; ?></td>
                                </tr>
                            </table>
                        </div> 
                    </div> 
                </div> 
             
<!--END OF Pricing & Billing Interval -->

<!--Trial & Freemium -->
                <h2 class="viewtitle-text viewh"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_FREEMIUM');?> 
                </h2>   
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <div style="position: relative;">
    	                    <table class="table borderless">
                                <tr>
                                    <div class="viewcontent-text">
                                        <td class="text-right">
                                        <?php echo JText::_('COM_AXISUBS_TRIAL_PERIOD');?>
                                        </td>
                                        <td><?php echo $this->item->trial_period."Days"; ?></td> 
                                    </div>
                                </tr>
                                <tr>            
                                    <td class="text-right">
                                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_FREE_QUANTITY');?>
                                    </td>
                                    <td>0
                                    </td>
                                </tr>
                            </table>
                        </div> 
                    </div> 
                </div>
            
<!--END OF Trial & Freemium -->


  
            </div>

<!--End of Main Body-->

<!--SIDE BAR-->
            <div class="viewplan-sidebar">
                <div class="viewplan-sidebar-h1">
                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS');?>
                </div>
                <div class="viewsidebar-content">
                    <dl>
                        <dt>
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_EDIT_PLAN_DESC');?>
                        </dt>
                            <dd>
                                <a href ="index.php?option=com_axisubs&view=Plan&id=<?php echo $this->item->axisubs_plan_id; ?>"
                                    class="btn btn-default viewaction-button" id="plans.edit">
                                    <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_EDIT_PLAN');?>
                                </a>
                            </dd>
                    </dl>      
                   <!-- <dl>  
                        <dt>
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CLONE_PLAN_DESC');?>
                        </dt>
                        <dd>
                            <a class="btn btn-default viewaction-button" href="#" id="plans.clone">
                                 <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_CLONE_PLAN');?>
                            </a>
                        </dd> 
                    </dl> -->
                    <dl>  
                        <dt>
                            <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_PLAN_DESC');?>
                        </dt>
                        <dd>
                            <a  href ="index.php?option=com_axisubs&view=Plan&task=deleteprompt&id=<?php echo $this->item->axisubs_plan_id; ?>"
                                class="btn btn-default viewaction-button" 
                                id="plans.delete" ajax="true" popup="server" oncontextmenu="return false">
                                <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_DELETE_PLAN');?>
                            </a>
                        </dd>
                    </dl>
                </div>
            </div>
                
            <!--End of SIDE BAR-->
               
    </div>
</div>
