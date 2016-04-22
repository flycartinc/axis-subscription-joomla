<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
$payment_form = '';
?>

<div class="axisubs-payments">
<?php if (isset($this->payments)): ?>
<?php foreach ($this->payments as $payment) : 
		if ( !empty($payment->checked) ){
			$payment_form = $payment->payment_form;
		}
	?>
    <?php echo Axisubs::plugin()->eventWithHtml('BeforeDisplayPaymentMethod', array( $payment->element ) ); ?>
	<input value="<?php echo $payment->element; ?>" class="payment_plugin"
		name="payment_plugin" type="radio"
		onclick="axisubsGetPaymentForm('<?php echo $payment->element; ?>', 'axisubs_payment_form_div');"
		<?php echo (!empty($payment->checked)) ? "checked" : ""; ?>
		title="<?php echo JText::_('AXISUBS_SELECT_A_PAYMENT_METHOD'); ?>" />

	<label class="payment-plugin-image-label <?php echo $payment->element; ?>" >
	<?php if(!empty($payment->image)): ?>
		<img class="payment-plugin-image <?php echo $payment->element; ?>" 
			 src="<?php echo JUri::root().JPath::clean($payment->image); ?>" />
	<?php endif; ?>
		<?php echo $payment->display_name; ?> 
	</label>

	<?php echo Axisubs::plugin()->eventWithHtml('AfterDisplayPaymentMethod', array( $payment->element )); ?>
<?php endforeach; ?>
<?php endif; ?>
	<div>
		<div id="axisubs_payment_form_validation"></div>
	</div>
	<div id="axisubs_payment_form_div">
		<?php echo $payment_form; ?>
	</div>
</div>

<script type="text/javascript">
<!--						
function axisubsGetPaymentForm(element, container) {
	var url = '<?php echo JRoute::_('index.php'); ?>';
	var data = 'option=com_axisubs&view=Subscribe&task=getPaymentForm&ajax=ajax&payment_element='+ element;
	axisubsDoTask(url, container, document.adminForm, '', data);
}
//-->
</script>