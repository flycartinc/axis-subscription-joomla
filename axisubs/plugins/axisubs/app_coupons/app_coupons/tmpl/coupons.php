<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

JHtml::_('behavior.calendar');
$app = JFactory::getApplication();
?>
<style>
    .axisubs-coupons-form-block{
        display: none;
    }
    .invalid-bor{
        border: 1px solid red !important;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group{
        display: inline;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group .control-label{
        display: inline-block;
        text-align: right;
        width: 170px;
        margin-right: 20px;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group .controls{
        display: inline;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group .controls select{
        margin-bottom:15px;
        height: 34px;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group .controls input{
        margin-bottom:15px;
        height: 34px;
    }
    .axisubs-coupon_form-con form .control-group-form .control-group .controls .input-append .btn{
        background-color: #286090;
        border: 1px solid #286090;
        color: #ffffff;
        height: 34px;
        margin: 0 auto;
        width: 42px;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table{
        border: 1px solid #ddd;
        margin: 20px 0px 0px;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table thead{
        background-color: #E66346;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table thead tr th{
        color: #fff;
        font-size: 14px;
        text-transform: uppercase;
        padding: 12px;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table tbody tr td{
        padding: 8px 12px;
        line-height: 3;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table tbody tr{
        background: #fff;
    }
    .axisubs-bs3 .axisubs-coupons-list-block .axisubs-coupons-list .table tbody tr:nth-of-type(2n+2){
        background: #e9e9e9;
    }
</style>
<div class="axisubs-bs3">
    <div>
        <a href="#" onclick="addCouponBtn()" class="btn btn-success axisubs-coupons-list-block"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_NEW_LINK'); ?></a>
    </div>
    <div class="coupon_messages">
    </div>
    <div class="axisubs-coupons-con axisubs-coupons-list-block">
        <form name="adminForm" id="adminForm" method="post" action="index.php?option=com_axisubs&view=apps&task=view&layout=view&app_layout=coupons&id=<?php echo $app->input->get('id'); ?>">
            <div class="search-container">
                <!-- Filters -->
                <?php echo $this->_getLayout('coupons_filter', $vars); ?>
            </div>
            <div class="axisubs-coupons-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_NAME'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_CODE'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_FROM'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_TO'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS'); ?></b>
                            </th>
                            <th>
                                <b><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_BUTTON'); ?></b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                <?php if(count($vars->items)){
                    foreach ($vars->items as $key => $item) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $item->name; ?>
                            </td>
                            <td>
                                <?php echo $item->code; ?>
                            </td>
                            <td>
                                <?php
                                if($item->value_type == 'percent'){
                                    echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE_PERCENT');
                                } else {
                                    echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE_FIXED');
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo $item->value; ?>
                            </td>
                            <td>
                                <?php echo $item->valid_from; ?>
                            </td>
                            <td>
                                <?php echo $item->valid_upto; ?>
                            </td>
                            <td>
                                <?php
                                if($item->published){
                                    echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS_YES');
                                } else {
                                    echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS_NO');
                                }
                                ?>
                            </td>
                            <td>
                                <a href="#" onclick="editCouponBtn('<?php echo $item->axisubs_coupon_id; ?>')" class="btn btn-info"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_EDIT_LINK'); ?></a>
                                <a href="#" onclick="deleteCouponBtn('<?php echo $item->axisubs_coupon_id; ?>')" class="btn btn-danger"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_DELETE_LINK'); ?></a>
                            </td>
                        </tr>
                        <?php
                    }
                    } else {
                    echo JText::_('PLG_AXISUBS_APP_COUPONS_NO_DATA_AVAILABLE');
                }?>
                    </tbody>
                </table>
            </div>
            <?php echo $vars->pagination->getListFooter(); ?>
        </form>
    </div>
    <div class="axisubs-coupon_form-con axisubs-coupons-form-block">
        <!-- Coupon Form -->
        <?php echo $this->_getLayout('coupons_form', $vars); ?>
    </div>
</div>