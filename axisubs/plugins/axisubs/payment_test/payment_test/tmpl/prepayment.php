<?php
/**
 * @package   Axisubs - Test payment
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */


defined('_JEXEC') or die('Restricted access'); ?>
<div class="onbeforetext">
	<?php echo $vars->onbeforepayment_text; ?>
</div>

<form action="index.php" method="POST" name="axisubsTestPaymentForm" id="axisubs-test-payment-form">
	<input type="hidden" name="option" value="com_axisubs" >
	<input type="hidden" name="view" value="Subscribe" >
	<input type="hidden" name="task" value="confirmPayment" >
	<input type="hidden" name="orderpayment_type" value="<?php echo $vars->payment_method; ?>" >
	<input type="hidden" name="subscription_id" value="<?php echo $vars->subscription_id; ?>" >
	<input type="hidden" name="plan_id" value="<?php echo $vars->plan_id; ?>" >
	<input type="hidden" name="user_id" value="<?php echo $vars->user_id; ?>" >
</form>

<script>
	if(typeof(axisubs) == 'undefined') {
	    var axisubs = {};
	}
	if(typeof(axisubs.jQuery) == 'undefined') {
	    axisubs.jQuery = jQuery.noConflict();
	}

	function processPayment(){
	    (function($){
	        $('#axisubs-test-payment-form').submit();
	    })(axisubs.jQuery);
	}
</script>