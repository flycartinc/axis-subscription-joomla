<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
//use \JFactory;
//use \JText;
$currency       = Axisubs::currency();
$status_helper  = Axisubs::status();
$date  = Axisubs::date();
$customer = $this->customer;
$user = JFactory::getUser();
$app = JFactory::getApplication();
?>
<div class="axisubs-bs3">
    <!--Tab for profile page-->
    <ul class="nav nav-tabs bordcolor">
        <li class="active ">
            <a data-toggle="tab" href="#home" class="outlne">
                <?php echo JText::_('COM_AXISUBS_MY_PROFILE_TAB');?>
            </a>
        </li>
        <li>
            <a data-toggle="tab" href="#subscribe" class="outlne">
                <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_TAB');?>
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
                            <?php echo JText::_( 'AXISUBS_ADDRESS_COMPANY_NAME');?>
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
                                                <?php echo JText::_( 'AXISUBS_ADDRESS_COMPANY_NAME');?>
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
            <div class="row">
                <?php echo $this->getContainer()->template->loadPosition('axisubs-profile-beforeaddress'); ?>
            </div>
            <div class="col-md-12 subspadtop axisubs-profile">
                <div class="panel panel-info">
                    <div class="panel-heading phead">
                        <h3 class="panel-title"> <i class="fa fa-user"></i> <?php echo $customer->first_name." ".$customer->last_name; ?></h3>
                    </div>
                    <div class="panel-body pbgcolour">
                        
                        <div class="row">
                            <div class="col-md-6">
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
                                        <?php if($customer->company != ''){ ?>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-building-o"></i>
                                                <?php echo JText::_( 'AXISUBS_ADDRESS_COMPANY_NAME');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->company; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-at"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_EMAIL');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $customer->email; ?>
                                            </td>
                                        </tr>
                                        <?php if($customer->address1 != ''){ ?>
                                        <tr>
                                            <td class="bordless">
                                                <i class=" fa fa-home"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ADDRESS');?> </td>
                                            <td class="bordless">
                                                <?php echo $customer->address1; ?>
                                                <?php echo $customer->address2; ?>
                                                <br>
                                                <?php echo $customer->city; ?>
                                                <br>
                                                <?php
                                                $stateSelected = Select::getZones($customer->country);
                                                if(isset($stateSelected[$customer->state])){
                                                    echo $stateSelected[$customer->state].', ';
                                                }
                                                 ?>
                                                <?php echo Select::decodeCountry($customer->country); ?>
                                                <br>
                                                <?php echo $customer->zip; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
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
                            <div class="col-md-6">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-calendar"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_SIGN_UP_ON');?>
                                            </td>
                                            <td class="bordless">
                                                <?php echo $date->format($user->registerDate); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bordless">
                                                <i class="fa fa-calendar-o"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_LAST_VISIT');?>
                                            </td>
                                            <td class="bordless">
                                                <?php
                                                if($user->lastvisitDate != "0000-00-00 00:00:00"){
                                                    echo $date->format($user->lastvisitDate);
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bordless axisubs-active-subscr-title" colspan="2">
                                                <i class="fa fa-check-square-o"></i>
                                                <?php echo JText::_( 'COM_AXISUBS_CUSTOMER_ACTIVE_SUBSCRIPTIONS');?>
                                            </td>
                                        </tr>
                                        <?php
                                        if(count($this->active_subscriptions)) {
                                            foreach ($this->active_subscriptions as $active_subscriptions) : ?>
                                                <tr>
                                                    <td class="bordless" colspan="2">
                                                        <div class="axisubs-active-subscr-list">
                                                            <i class="fa fa-check-circle-o"></i>
                                                            <?php echo $active_subscriptions->plan->name; ?> -
                                                            <?php echo $currency->format ( $active_subscriptions->total , $active_subscriptions->currency ) ; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach;?>
                                            <tr>
                                                <td class="bordless" colspan="2">
                                                    <div class="axisubs-active-subscr-list">
                                                        <a href="#subscribe" class="axis-profile-subscriptions"><?php echo JText::_('COM_AXISUBS_CUSTOMER_VIEW_ALL_SUBSCRIPTIONS'); ?></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        } else { ?>
                                            <tr>
                                                <td class="bordless" colspan="2">
                                                    <?php echo JText::_('COM_AXISUBS_CUSTOMER_NO_ACTIVE_SUBSCRIPTIONS_FOUND') ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php echo $this->getContainer()->template->loadPosition('axisubs-profile-afteraddress'); ?>
            </div>
            <!-- end of Third type-->
        </div>
        <!-- end of Tab1 Content-->

        <!--  Tab2 Content-->
        <div id="subscribe" class="tab-pane fade">
            <form action="<?php echo JRoute::_('index.php?option=com_axisubs&view=Profile&Itemid='.$app->input->get('Itemid')); ?>#subscribe" method="post">
                <div class="row">
                    <?php echo $this->getContainer()->template->loadPosition('axisubs-profile-beforesubslist'); ?>
                </div>
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
                                        <?php
                                        if($subscription->plan->plan_type){
                                            echo $date->format( $subscription->current_term_end );
                                        } else {
                                            echo JText::_('COM_AXISUBS_PLAN_RECURRING_UNLIMIT');
                                        } ?>
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
                        <div class="pagination">
                            <?php
                            if(count($this->subscriptions)) {
                                echo $this->paginationS->getLimitBox();
                                echo $this->paginationS->getListFooter();
                            }
                            ?>
                        </div>
                    </div>
                    <?php if(count($this->new_subscriptions)){ ?>
                    <div class="axisubs-past-subscription-lists col-md-12">
                        <div class="axisubs-past-subscription-lists-c">
                            <h3><?php echo JText::_('COM_AXISUBS_TRIED_SUBSCRIPTION_TITLE'); ?></h3>
                            <div class="axisubs-past-subscription-list-c">
                                <?php foreach ($this->new_subscriptions as $new_subscription) : ?>
                                <div class="axisubs-past-subscription-list row">
                                    <div class="col-md-3">
                                        <span class="axisubs-text-b"><?php echo JText::_('COM_AXISUBS_PLAN_NAME'); ?>:</span> <span class="axisubs-text-content"><?php echo $new_subscription->plan->name; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="axisubs-text-b"><?php echo JText::_('COM_AXISUBS_PLAN_PRICE'); ?>:</span> <span class="axisubs-text-content"><?php echo $currency->format ( $new_subscription->total , $new_subscription->currency ) ; ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <a class="btn btn-success" href="<?php echo JRoute::_('index.php?option=com_axisubs&view=subscribe&plan='.$new_subscription->plan->slug); ?>"><?php echo JText::_('COM_AXISUBS_PLAN_TRY_AGAIN'); ?></a>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-danger remove_new_subscription" attr-data="<?php echo $new_subscription->axisubs_subscription_id; ?>"><?php echo JText::_('COM_AXISUBS_PLAN_REMOVE'); ?></button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <?php echo $this->getContainer()->template->loadPosition('axisubs-profile-aftersubslist'); ?>
                </div>
            </form>
        </div>
    </div><!--End of Tab Content for profile page-->
</div>
<script>
    (function($) {
        <?php if($app->input->get('limitstart', '') === '0' || $app->input->get('start')){
            ?>
        $("li a[href$='#subscribe']").click();
        <?php
        } ?>
        var hash = window.location.hash;
        if(hash != ''){
            $("li a[href$='"+hash+"']").click();
        }
        $( ".axis-profile-subscriptions" ).on( "click", function() {
            $("li a[href$='#subscribe']").click();
        });
        //For removing New Subscription
        $(".remove_new_subscription").click(function(){
            var button = $(this);
            var id = $(this).attr('attr-data');
            $(this).html('<?php echo JText::_('COM_AXISUBS_PLAN_REMOVING'); ?>');
            $.ajax({
                url: 'index.php',
                type: "POST",
                data: { option: "com_axisubs",
                    view: "Profile",
                    task: "removeSubscription",
                    id: id  },
                dataType: 'json',
                async   : false,
                success: function(json) {
                    if(json.status == 1){
                        button.html('<?php echo JText::_('COM_AXISUBS_PLAN_REMOVED'); ?>');
                        button.parent().parent('.axisubs-past-subscription-list').hide('slow');
                    } else {
                        button.html('<?php echo JText::_('COM_AXISUBS_PLAN_REMOVE'); ?>');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    })(axisubs.jQuery);
</script>