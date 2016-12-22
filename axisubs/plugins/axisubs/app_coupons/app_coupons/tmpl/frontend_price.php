<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<style>
    .axisubs_coupon_remove {
        color: red;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
    }
    .axisubs_coupon_code_con {
        background: #08c none repeat scroll 0 0;
        border-radius: 2px;
        color: #fff;
        display: inline-block;
        padding: 0 5px;
    }
    .axisubs_coupon_amount_con {
        padding: 0 5px;
    }
</style>
<?php
$app = JFactory::getApplication();
$session = $app->getSession();
$code = $session->get('axisubs_coupon_code', '');
if($code != ''){
?>
    <?php if ($vars->data->subscription->discount > 0): ?>
        <li class="row">
            <div class="col-xs-8 summary-list">
                <strong><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_DISCOUNT'); ?> </strong>
                <span class="axisubs_coupon_amount_con" >
                    <?php
                    if($vars->couponItem->value_type == 'percent'){
                        echo $vars->couponItem->value.'% ';
                    } else {
                //        echo $vars->couponItem->value;
                        echo Axisubs::currency()->format($vars->couponItem->value);
                    }
                    ?>
                </span>
                <span class="axisubs_coupon_code_con" >
                    <span><?php echo $code; ?></span>
                    <span class="axisubs_coupon_remove hastip" onclick="removeCoupon()" title="<?php echo JText::_('PLG_AXISUBS_APP_COUPONS_REMOVE_COUPON_TEXT_TITLE'); ?>">
                        <?php echo JText::_('PLG_AXISUBS_APP_COUPONS_REMOVE_COUPON_TEXT'); ?>
                    </span>
                </span>
            </div>
            <div class="col-xs-4 text-right">
                <?php echo '- '.Axisubs::currency()->format($vars->data->subscription->discount); ?>
            </div>
        </li>
    <?php endif; ?>
    <?php if ($vars->data->subscription->discount_tax > 0): ?>
        <li class="row">
            <div class="col-xs-8 summary-list">
                <strong><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_DISCOUNT_TAX'); ?></strong>
            </div>
            <div class="col-xs-4 text-right">
                <?php echo Axisubs::currency()->format($vars->data->subscription->discount_tax); ?>
            </div>
        </li>
    <?php endif; ?>

<?php } ?>
<script type="application/javascript">
    function removeCoupon(){
        (function ($) {
            $.ajax({
                type: 'post',
                url: 'index.php',
                data: {'option': 'com_axisubs', 'view': 'Subscribe', 'task': 'applyCoupon', 'apptask': 'removeCoupon', 'plan_id': '<?php echo $vars->plan_id; ?>'},
                dataType: 'json',
                beforeSend: function () {
                    $('.axisubs_coupon_remove').attr('disabled', 'disabled');
                },
                success: function (data) {
                    <?php if($vars->page == 'payment'){
                    ?>
                    window.history.back();
                    <?php
                    } else {
                    ?>
                    window.location.reload();
                    <?php
                    }  ?>
                }
            });
        })(axisubs.jQuery);
    }
</script>