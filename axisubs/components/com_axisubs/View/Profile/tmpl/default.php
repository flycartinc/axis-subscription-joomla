<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
//use \JFactory;
//use \JText;
$currency       = Axisubs::currency();
$status_helper  = Axisubs::status();
$date  = Axisubs::date();
$customer = $this->customer;
?>
<div class="axisubs-bs3">
    <!--Tab for profile page-->
    <ul class="nav nav-tabs bordcolor">
        <li class="active ">
            <a data-toggle="tab" href="#home" class="outlne">
                <?php echo "My Profile";?>
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#menu1" class="outlne">
                Subscription
            </a>
        </li>
    </ul>
    <!--End of tab-->
    <!--Tab Content for profile page-->
    <div class="tab-content">
        <!--First Tab Content-->
        <div id="home" class="tab-pane fade in active">
            <div class="col-md-2">
            </div>

            <!--First type-->
  <!--           <div class="col-md-8">
                <div class="row padtop">
                    <div class="col-md-3 ">
                        <div class=" padbot">
                            <i class="fa fa-user"></i>
                            <?php echo JText::_( 'COM_AXISUBS_CUSTOMERSS_NAME');?>
                        </div>
                        <div class=" padbot">
                            <i class="fa fa-list"></i>
                            <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ONLY_ID');?>
                        </div>
                        <div class=" padbot">
                            <i class="fa fa-building-o"></i>
                            <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_COMPANY');?>
                        </div>
                        <div class=" padbot">
                            <i class="fa fa-at"></i>
                            <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_EMAIL');?>
                        </div>
                        <div class="padbot">
                            <i class=" fa fa-home"></i>
                            <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ADDRESS');?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="padbot">
                            <?php if($customer->first_name=="" && $customer->last_name==""): echo "
                            <br>"; else : echo $customer->first_name.$customer->last_name; endif ?>

                        </div>
                        <div class="padbot">
                            <?php if($customer->user_id==""): echo "
                            <br>"; else : echo $customer->user_id; endif ?>
                        </div>
                        <div class="padbot">
                            <?php if($customer->company==""): echo "
                            <br>"; else : echo $customer->company; endif ?>
                        </div>
                        <div class="padbot">
                            <?php if($customer->email==""): echo "
                            <br>"; else : echo $customer->email; endif ?>
                        </div>
                        <div class="padbot">
                            <?php echo $customer->address1; ?>
                            <?php echo $customer->address2; ?>
                            <br>
                            <?php echo $customer->state; ?>
                            <?php echo $customer->country; ?>
                            <br>
                            <?php echo $customer->zip; ?>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- end of First type-->



            <!-- Second type-->
            <!-- <div class="col-md-6 subspadtop">
                <div class="panel panel-info">
                    <div class="panel-heading phead">
                        <h3 class="panel-title"> <i class="fa fa-user"></i> <?php echo $customer->first_name.$customer->last_name; ?></h3>
                    </div>
                    <div class="panel-body pbgcolour">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table ">
                                    <tbody>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-list"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ONLY_ID');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->user_id; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">
                                                <i class="fa fa-building-o"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_COMPANY');?>
                                            </td>
                                            <td class="">
                                                <?php echo $customer->company; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">
                                                <i class="fa fa-at"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_EMAIL');?>
                                            </td>
                                            <td class="">
                                                <?php echo $customer->email; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="">
                                                <i class=" fa fa-home"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ADDRESS');?> </td>
                                            <td class="">
                                                <?php echo $customer->address1; ?>
                                                <?php echo $customer->address2; ?>
                                                <br>
                                                <?php echo $customer->state; ?>
                                                <?php echo $customer->country; ?>
                                                <br>
                                                <?php echo $customer->zip; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- end of Second type-->
            <!-- Third  type-->
            <div class="col-md-6 subspadtop">
                <div class="panel panel-info">
                    <div class="panel-heading phead">
                        <h3 class="panel-title"> <i class="fa fa-user"></i> <?php echo $customer->first_name.$customer->last_name; ?></h3>
                    </div>
                    <div class="panel-body pbgcolour">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table ">
                                    <tbody>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-list"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ONLY_ID');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->user_id; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-building-o"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_COMPANY');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->company; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-at"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_EMAIL');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->email; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bordless">
                                                <i class=" fa fa-home"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ADDRESS');?> </td>
                                            <td class="bordless">
                                                <?php echo $customer->address1; ?>
                                                <?php echo $customer->address2; ?>
                                                <br>
                                                <?php echo $customer->state; ?>
                                                <?php echo $customer->country; ?>
                                                <br>
                                                <?php echo $customer->zip; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a href="index.php?option=com_axisubs&view=Profile&task=editCustomerAddress" class="btn btn-primary">
                                                    <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_EDIT_ADDRESS');?>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of Third type-->
        </div>
        <!-- end of Tab1 Content-->




        <!--  Tab2 Content-->
        <div id="menu1" class="tab-pane fade">
            <div class="row padtop">
                <div class="subscriptions col-md-12">
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th col width="1%">
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_ID'); ?>
                                </th>
                                <th width="20%">
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_PLAN_NAME'); ?>
                                </th>
                                <th width="14%">
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_START_DATE'); ?>
                                </th>
                                <th width="14%">
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_END_DATE'); ?>
                                </th>
                                <th width="10%">
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_STATUS'); ?>
                                </th>
                                <th>
                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_ACTIONS'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=5 ; ?>
                            <?php foreach ($this->subscriptions as $subscription) : ?>
                            <tr>
                                <td>
                                    <?php echo $subscription->axisubs_subscription_id; ?>
                                </td>
                                <td>
                                    <?php echo $subscription->plan->name; ?> -
                                    <?php echo $currency->format ( $subscription->total , $subscription->currency ) ; ?>
                                </td>
                                <td>
                                    <?php //echo $subscription->getBillingInfo(); ?>
                                    <?php echo $date->format( $subscription->current_term_start ); ?>
                                </td>
                                <td>
                                    <?php echo $date->format( $subscription->current_term_end ); ?>
                                </td>
                                <td>
                                    <span class="label label-<?php echo $status_helper->get_label( $subscription->status ); ?>"> 
                                            <?php echo $status_helper->get_text( $subscription->status ); ?>
                                        </span>
                                </td>
                                <td>
                                    <!-- Define the actions based on the subscription status and display the actions -->
                                    <?php if ( $subscription->isRenewalAllowed( $subscription->user_id ) ): ?>
                                    <a class="btn btn-info"  style=" " href="index.php?option=com_axisubs&view=subscribe&task=renew&subscription_id=<?php echo $subscription->axisubs_subscription_id; ?>">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_ACTION_RENEW'); ?>
                                    </a>
                                   <?php endif; ?>

                                   <?php if (false && $subscription->status != 'C'): ?>
                                    <a class="btn btn-danger"  style=" " href="index.php?option=com_axisubs&view=subscribe&task=cancelSubscription&subscription_id=<?php echo $subscription->axisubs_subscription_id; ?>">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_ACTION_CANCEL'); ?>
                                    </a>
                                   <?php endif; ?>

                                    <a class="btn btn-success" style=" " 
                                        href="index.php?option=com_axisubs&view=Profile&task=viewSubscription&subscription_id=<?php echo $subscription->axisubs_subscription_id; ?>"
                                         >  
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_ACTION_VIEW_DETAILS'); ?>
                                    </a>

                                </td>
                            </tr>
                            <?php $i++;?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
                        <!-- view button-->

                        <div class="col-md-12">
                            <?php echo "for view button";?>
                            <div class="row padtop">
                                <div class="col-md-4 ">
                                    <div class=" padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_ID'); ?>
                                    </div>
                                    <div class=" padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_PLAN_NAME'); ?>
                                    </div>
                                    <div class=" padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_START_DATE'); ?>
                                    </div>
                                    <div class=" padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_END_DATE'); ?>
                                    </div>
                                    <div class="padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON'); ?>
                                    </div>
                                    <div class="padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON'); ?>
                                    </div>
                                    <div class="padbot">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_STATUS'); ?>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="padbot">
                                        <?php if($subscription->axisubs_subscription_id==""): echo "
                                        <br>"; else : echo $subscription->axisubs_subscription_id; endif ?>
                                    </div>
                                    <div class="padbot">
                                        <?php if($subscription->plan->name==""): echo "
                                        <br>"; else : echo $subscription->plan->name ."-". $currency->format ( $subscription->total , $subscription->currency ) ; endif ?>

                                    </div>
                                    <div class="padbot">
                                        <?php  echo $date->format( $subscription->current_term_start ); 
                                        ?>
                                        <!-- <?php echo $date->format( $subscription->current_term_start ); ?> -->
                                    </div>
                                    <div class="padbot">
                                        <?php echo $date->format( $subscription->current_term_end ); 
                                       ?>
                                    </div>
                                    <div class="padbot">
                                        <?php echo $date->format( $subscription->trial_start ); ?>

                                    </div>
                                    <div class="padbot">
                                        <?php echo $date->format( $subscription->trial_end );  ?>

                                    </div>
                                    <div class="padbot">
                                        <span class="label label-<?php echo $status_helper->get_label( $subscription->status ); ?>"> 
                                            <?php echo $status_helper->get_text( $subscription->status ); ?>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!-- end of  view button-->





                            <!-- Subscription Normal view-->


                            <?php echo "for Subscription Normal view";?>
                            <?php $i=5 ; ?>
                            <?php foreach ($this->subscriptions as $subscription) : ?>
                            <div class="row subsnpadtop">
                                <div class="col-md-4 planpadding" >
                                    <dl>
                                        <dt class="profpad">
                                            <span class="pcolor">
                                                <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_ID'); ?>:
                                            </span> 
                                            <?php echo $subscription->axisubs_subscription_id; ?>
                                        </dt>
                                        <dt >
                                            <span class="pcolor">
                                                <?php echo JText::_( 'COM_AXISUBS_PROFILE_PLAN_NAME'); ?>:
                                            </span> 
                                            <?php echo $subscription->plan->name; ?> -<?php echo $currency->format ( $subscription->total , $subscription->currency ) ; ?>
                                        </dt>
                                    </dl>
                                </div>

                                <div class="col-md-3 planpadding">
                                    <dl>
                                        <dt class="profpad">
                                            <span class="pcolor">
                                                <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_START_DATE'); ?>:
                                            </span> 
                                            <?php echo $date->format( $subscription->current_term_start ); ?>
                                        </dt>
                                        <dt>
                                            <span class="pcolor">
                                                <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_END_DATE'); ?>:
                                            </span>
                                             <?php  echo $date->format( $subscription->current_term_end); ?>
                                        </dt>                           
                                    </dl>       
                                </div>
                                <div class="col-md-5 planpadding">
                                    <dl>
                                        <dt class="profpad">
                                            <span class="pcolor">
                                                <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_STATUS'); ?>:
                                            </span>
                                            <span class="label label-<?php echo $status_helper->get_label( $subscription->status ); ?>"> 
                                            <?php echo $status_helper->get_text( $subscription->status ); ?>
                                            </span>
                                        </dt>
                                        <dt >
                                            <a class="btn btn-info"  href="index.php?option=com_axisubs&view=subscribe&task=rene&subscription_id=<?php echo $subscription->axisubs_subscription_id; ?>">
                                        <?php echo JText::_( 'COM_AXISUBS_PROFILE_ACTION_RENEW'); ?>
                                            </a>
                                            
                                    <div class="example<?php echo $i;?> jqcollpadding " >
                                        <!--Trigger the details of subscription -->
                                         <div class="row padntop">
                                            <div class="col-md-6 ">
                                                <div class=" padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_ID'); ?>
                                                </div>
                                                <div class=" padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_PLAN_NAME'); ?>
                                                </div>
                                                <div class=" padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_START_DATE'); ?>
                                                </div>
                                                <div class=" padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_END_DATE'); ?>
                                                </div>
                                                <div class="padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON'); ?>
                                                </div>
                                                <div class="padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON'); ?>
                                                </div>
                                                <div class="padbot text-right">
                                                    <?php echo JText::_( 'COM_AXISUBS_PROFILE_SUBSCRIPTION_STATUS'); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="padbot">
                                                    <?php if($subscription->axisubs_subscription_id==""): echo "
                                                    <br>"; else : echo $subscription->axisubs_subscription_id; endif ?>
                                                </div>
                                                <div class="padbot">
                                                    <?php if($subscription->plan->name==""): echo "
                                                    <br>"; else : echo $subscription->plan->name ."-". $currency->format ( $subscription->total , $subscription->currency ) ; endif ?>

                                                </div>
                                                <div class="padbot">
                                                    <?php  
                                                        echo $date->format( $subscription->current_term_start); 
                                                    ?>
                                                </div>
                                                <div class="padbot">
                                                    <?php echo $date->format( $subscription->current_term_end); 
                                                    ?>
                                                </div>
                                                <div class="padbot">
                                                    <?php  echo $date->format( $subscription->trial_start); 
                                                    ?>
                                                </div>
                                                <div class="padbot">
                                                    <?php  echo $date->format( $subscription->trial_end); 
                                                    ?>
                                                </div>
                                                <div class="padbot">
                                                    <span class="label label-<?php echo $status_helper->get_label( $subscription->status ); ?>"> 
                                                        <?php echo $status_helper->get_text( $subscription->status ); ?>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        </dt>
                                    </dl>
                                
                            </div>
                            <hr />
                            <?php $i++;?>
                            <?php endforeach; ?>
        </div>
    </div><!--End of Tab Content for profile page-->
</div>

    
        

    <?php $i=5 ; ?>
    <?php foreach ($this->subscriptions as $subscription):?>
    <script>
        jQuery(document).ready(function() {
            jQuery('.example<?php echo $i;?>').hide().before('<a href="#" id="toggle-example<?php echo $i;?>" class="button btn-success jqpadding">View Details</a>');
            jQuery('a#toggle-example<?php echo $i;?>').click(function() {
                jQuery('.example<?php echo $i;?>').slideToggle(300);
                return false;
            });
        });
    </script>
    <?php $i++;?>
    <?php endforeach;?>