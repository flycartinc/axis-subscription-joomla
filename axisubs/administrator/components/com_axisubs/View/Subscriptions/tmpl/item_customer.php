<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
//$customer = $this->item->customer ;
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
?>

<?php if (isset($this->subscriptioninfo)) :
    $subs_info = $this->subscriptioninfo ;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="panel-title">
            <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_CUSTOMER_DETAILS');?>
        </span>
        <span class="pull-right clickable">
        </span>
    </div>
    <div class="panelb">
<!--CUSTOMER DETAILS -->
        <table class="table borderless margin0">
            <tr>
                <td class="tbl_head">
                    <?php echo JText::_('COM_AXISUBS_CUSTOMER_ID');?>
                </td>
                <td>
                    <?php echo $subs_info->user_id; ?>
                </td>
            </tr>
            <tr>
                <td class="tbl_head">
                    <?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>
                </td>
                <td>
                    <?php echo $subs_info->billing_first_name."&nbsp".$subs_info->billing_last_name;; ?>
                </td>
            </tr>
            <tr>
                <td class="tbl_head">
                    <?php echo JText::_('AXISUBS_ADDRESS_COMPANY_NAME');?>
                </td>
                <td>
                    <?php echo $subs_info->billing_company; ?>
                </td>
            </tr>
            <tr>
                <td class="tbl_head"> <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?>
                </td>
                <td>
                    <?php echo $subs_info->billing_email; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <i class=" fa fa-home"></i>
                  <span class="tbl_head"> <?php echo JText::_('COM_AXISUBS_CUSTOMER_ADDRESS');?></span>
                    <div class="well margin0">
                        <?php echo $subs_info->billing_address1; ?>
                        <?php echo $subs_info->billing_address2; ?>  <br>
                        <?php
                        $stateSelected = Select::getZones($subs_info->billing_country);
                        if(isset($stateSelected[$subs_info->billing_state])){
                            echo $stateSelected[$subs_info->billing_state].', ';
                        }
                        echo Select::decodeCountry($subs_info->billing_country);
                        ?> <br>
                        <?php echo $subs_info->billing_zip; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<?php endif; ?>
