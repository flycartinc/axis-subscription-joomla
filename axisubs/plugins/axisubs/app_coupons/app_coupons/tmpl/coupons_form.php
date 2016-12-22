<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

use Flycart\Axisubs\Admin\Helper\Axisubs;
JHtml::_('behavior.calendar');
$app = JFactory::getApplication();
$currentURL = 'index.php?option=com_axisubs&view=apps&task=view&layout=view&app_layout=coupons&id='.$app->input->get('id');
?>
<style>
    .axisubs-bs3 select[multiple], .axisubs-bs3 select[size]{
        height: auto !important;
    }
</style>
<div class="axisubs-heading">
    <h4 class="coupon_new_title"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_NEW_FORM'); ?></h4>
    <h4 class="coupon_edit_title"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_EDIT_FORM'); ?></h4>
</div>
<form class="" name="adminFormCoupon" id="adminFormCoupon" method="post" action="<?php echo $currentURL; ?>">
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_name" id="jform_name-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_NAME_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_NAME'); ?>
                </label>
            </div>
            <div class="controls">
                <input type="text" size="30" class="jform-name" value="" id="jform_name" name="jform[name]">
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_code" id="jform_code-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_CODE_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_CODE'); ?>
                </label>
            </div>
            <div class="controls">
                <input type="text" size="30" class="jform-code" value="" id="jform_code" name="jform[code]">
            </div>
        </div>
    </div>

    <?php if(Axisubs::isPro()){ ?>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_plans" id="jform_plans-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_PLAN_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_PLAN'); ?>
                </label>
            </div>
            <div class="controls">
                <?php echo \Flycart\Axisubs\Admin\Helper\Select::plans('', 'jform[plans][]', 'jform_plans', array('multiple' => 'multiple')); ?>
            </div>
        </div>
    </div>
    <?php } ?>


    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_published" id="jform_published-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS'); ?>
                </label>
            </div>
            <div class="controls">
                <select name="jform[published]" id="jform_published">
                    <option value="1"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS_YES'); ?></option>
                    <option value="0"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_STATUS_NO'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_value_type" id="jform_value_type-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE'); ?>
                </label>
            </div>
            <div class="controls">
                <select name="jform[value_type]" id="jform_value_type">
                    <option value="percent"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE_PERCENT'); ?></option>
                    <option value="fixed"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_TYPE_FIXED'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_value" id="jform_value-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALUE'); ?>
                </label>
            </div>
            <div class="controls">
                <input type="text" size="30" class="jform-value" value="" id="jform_value" name="jform[value]">
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_valid_from" id="jform_valid_from-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_FROM_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_FROM'); ?>
                </label>
            </div>
            <div class="controls">
                <?php
                echo JHTML::calendar('', 'jform[valid_from]', 'valid_from', '%Y-%m-%d',array('size'=>'8', 'class'=>' jform-valid_from'));
                ?>
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <label title="" class="hasTooltip" for="jform_valid_upto" id="jform_valid_upto-lbl" data-original-title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_TO_DESC'); ?>">
                    <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_FIELD_VALID_TO'); ?>
                </label>
            </div>
            <div class="controls">
                <?php
                echo JHTML::calendar('', 'jform[valid_upto]', 'valid_upto', '%Y-%m-%d',array('size'=>'8', 'class'=>' jform-valid_to'));
                ?>
            </div>
        </div>
    </div>
    <div class="control-group-form">
        <div class="control-group">
            <div class="control-label">
                <input type="hidden" size="30" class="jform-axisubs_coupon_id" value="" id="jform_axisubs_coupon_id" name="jform[axisubs_coupon_id]">
            </div>
            <div class="controls">
                <input type="button" value="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_SAVE'); ?>" name="submit" class="width-auto btn btn-success" onclick="saveCoupon()">
                <input type="button" value="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_CANCEL'); ?>" name="cancel" class="editpage width-auto btn btn-info" onclick="cancelCouponBtn()">
            </div>
        </div>
    </div>
</form>
<script>
    if(typeof(axisubs) == 'undefined') {
        var axisubs = {};
    }
    if(typeof(axisubs.jQuery) == 'undefined') {
        axisubs.jQuery = jQuery.noConflict();
    }

    /**
     * Add New Coupon
     * */
    function addCouponBtn(val){
        (function ($) {
            if(val == '1'){
                $('.coupon_edit_title').show();
                $('.coupon_new_title').hide();
            } else {
                $('.coupon_edit_title').hide();
                $('.coupon_new_title').show();
            }

            $('.axisubs-coupons-form-block').show();
            $('.axisubs-coupons-list-block').hide();
        })(axisubs.jQuery);
    }

    /**
     * Cancel Coupon
     * */
    function cancelCouponBtn(){
        (function ($) {
            $('input[name^="jform\["], select[name^="jform\["]').val('');
            $('.axisubs-coupons-form-block').hide();
            $('.axisubs-coupons-list-block').show();
        })(axisubs.jQuery);
    }

    /**
    * Save Coupon
    * */
    function saveCoupon(){
        (function ($) {
            $.ajax({
                type : 'post',
                url :  'index.php?option=com_axisubs&view=apps&task=view&layout=view&app_layout=coupons&app_task=ajaxsave&id=<?php echo $app->input->get('id'); ?>',
                data : $("#adminFormCoupon").serializeArray(),
                dataType : 'json',
                beforeSend: function() {
                    $('#adminFormCoupon input, #adminFormCoupon select').removeClass('invalid-bor');
                    $('.coupon_messages').html('');
                },
                success : function(data) {
                    if(data['result'] == 'success') {
                        setTimeout(
                            function()
                            {
                                window.location.href = '<?php echo $currentURL; ?>';
                            }, 2000);

                    } else {
                        if(data['field']){
                            $.each( data['field'], function( p_key, value ) {
                                $('#jform_'+value).addClass('invalid-bor').focus();
                            });
                        }
                    }
                    $('.coupon_messages').html(data['message']).focus();
                }
            });
        })(axisubs.jQuery);
    }

    /**
     * Edit Coupon
     * */
    function editCouponBtn(id){
        (function ($) {
            $.ajax({
                type : 'post',
                url :  'index.php?option=com_axisubs&view=apps&task=view&layout=view&app_layout=coupons&app_task=edit&id=<?php echo $app->input->get('id'); ?>',
                data : {'coupon_id': id},
                dataType : 'json',
                beforeSend: function() {
                },
                success : function(data) {
                    $.each( data, function( p_key, value ) {
                        $('input[name="jform\['+p_key+'\]"], select[name="jform\['+p_key+'\]"]').val(value);
                        if(p_key == 'plans'){
                            var str = value;
                            var strArray = str.split(",");
                            strArray.forEach(function (value, index, ar) {
                                $('select[name="jform\['+p_key+'\]\[\]"] option[value="' + value + '"]').attr("selected", 1);
                            });
                        }
                    });
                    addCouponBtn('1')
                }
            });
        })(axisubs.jQuery);
    }

    /**
     * Delete Coupon
     * */
    function deleteCouponBtn(id){
        (function ($) {
            $.ajax({
                type : 'post',
                url :  'index.php?option=com_axisubs&view=apps&task=view&layout=view&app_layout=coupons&app_task=delete&id=<?php echo $app->input->get('id'); ?>',
                data : {'coupon_id': id},
                dataType : 'json',
                beforeSend: function() {
                    $('.coupon_messages').html('');
                },
                success : function(data) {
                    if(data['result'] == 'success') {
                        setTimeout(
                            function()
                            {
                                window.location.href = '<?php echo $currentURL; ?>';
                            }, 2000);
                    }
                    $('.coupon_messages').html(data['message']).focus();
                }
            });
        })(axisubs.jQuery);
    }
</script>