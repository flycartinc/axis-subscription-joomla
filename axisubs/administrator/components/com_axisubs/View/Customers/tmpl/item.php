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
$user_helper = Axisubs::user();
$date_helper = Axisubs::date();

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
                                <div class="col-md-2 ">
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_ID');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_FIRST_NAME');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_LAST_NAME');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?>
                                        </div>
                                </div>
                                <div class="col-md-10">
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
                                <div class="col-md-2 ">
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_LINE1');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_LINE2');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_CITY');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_STATE');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_COUNTRY');?>
                                        </div>
                                        <div class="text-right padbot">
                                            <?php echo JText::_('AXISUBS_ADDRESS_ZIP');?>
                                        </div>

                                </div>
                                <div class="col-md-10">
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
                                        $stateSelected = Select::getZones($this->item->country);
                                        if(isset($stateSelected[$this->item->state])) {
                                            echo $stateSelected[$this->item->state];
                                        }
                                        endif ?>
                                    </div>
                                    <div class="padbot">
                                        <?php if($this->item->country==""):
                                            echo "<br>";
                                        else :
                                            echo Select::decodeCountry($this->item->country);
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
                 <div id="customer-details" style="position:relative">
                    <h2 class="viewtitle-text viewh"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_INFO');?> </h2>
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <?php echo $this->loadTemplate('subscription');?>
                    </div>
                </div>


<!--END OF SUBSCRIBE DETAILS -->

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
        <div class="viewsidebar">
            <h2 class="viewtitle-text viewh actionsub"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_USER_GROUPS');?></h2>
            <div id="viewusergroups" class="viewsubscription-sidebar">
                <?php
                $userJoomla = JFactory::getUser($this->item->user_id);
                jimport( 'joomla.user.helper' );
                $groups = JUserHelper::getUserGroups($userJoomla->id);
                $userGroups = $user_helper->getAllUserGroups();
                foreach ($groups as $key => $group){
                ?>
                <dd>
                    <div class="subscribed-usergroup">
                        <?php echo $userGroups[$group]; ?>
                    </div>
                </dd>
                <?php } ?>
            </div>
        </div>
        <div class="viewtimeline-container">
            <h2 class="viewtitle-text viewh actionsub"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE');?></h2>
            <ul>
                <?php
                echo "<li>";
                echo JText::_('COM_AXISUBS_SUBSCRIBE_TIMELINE_SIGNEDUP_ON').' <b>'.$date_helper->format($userJoomla->registerDate);
                echo "</b></li>";
                ?>
            </ul>
        </div>
    </div>
</div>
