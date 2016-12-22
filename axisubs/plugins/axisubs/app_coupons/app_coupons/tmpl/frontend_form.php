<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<style>
    .invalid-field{
        border: 1px solid red !important;
    }
    #axisub_coupon_code{
        width: auto;
        display: inline-block;
    }
</style>
<?php
$app = JFactory::getApplication();
$session = $app->getSession();
$code = $session->get('axisubs_coupon_code', '');
if($code == ''){
if($vars->page == 'default'){
?>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group coupon-form">
            <input class="form-control" placeholder="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_COUPON_CODE_PLACEHOLDER'); ?>" type="text" name="coupon_code" id="axisub_coupon_code" />
            <button class="btn btn-success axisub_coupon_code_btn" onclick="applyCoupon()" type="button"><?php echo JText::_('PLG_AXISUBS_APP_COUPONS_APPLY'); ?></button>
        </div>
        <div class="form-group axisub-coupon_message">
        </div>
    </div>
</div>
<script type="application/javascript">
    function applyCoupon(){
        (function ($) {
            var couponField = $('#axisub_coupon_code');
            couponField.removeClass('invalid-field');
            var coupon = couponField.val();
            if(coupon != '') {
                $.ajax({
                    type: 'post',
                    url: 'index.php',
                    data: {'coupon_code': coupon, 'option': 'com_axisubs', 'view': 'Subscribe', 'task': 'applyCoupon', 'plan_id': '<?php echo $vars->plan_id; ?>'},
                    dataType: 'json',
                    beforeSend: function () {
                        $('.axisub-coupon_message').html('');
                        $('.axisub_coupon_code_btn').attr('disabled', 'disabled');
                    },
                    success: function (data) {
                        if (data['result'] == 'success') {
                            couponField.val('');
                            window.location.reload();
                        }
                        $('.axisub-coupon_message').html(data['message']);
                        $('.axisub_coupon_code_btn').removeAttr('disabled');
                    }
                });
            } else {
                couponField.addClass('invalid-field');
            }
        })(axisubs.jQuery);
    }
</script>
<?php }
} ?>