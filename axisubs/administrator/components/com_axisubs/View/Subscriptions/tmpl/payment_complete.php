<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Select;
$curr = Axisubs::currency();
?>
<h3> <?php echo JText::_('COM_AXISUBS_PAYMENT_CONFIRMATION');?> </h3>
<form action="index.php" method="post">
	<input type="hidden" value="com_axisubs" name="option" >
	<input type="hidden" value="Subscription" name="view" >
	<input type="hidden" value="payment_complete" name="task" >
	<input type="hidden" value="<?php echo $this->subscription->axisubs_subscription_id; ?>" name="subscription_id" >
	<input type="hidden" value="<?php echo $this->subscription->user_id; ?>" name="user_id" >
	<?php echo JHtml::_('form.token'); ?>
<div class="row">
	<div class="col-md-4">
		 <table class="table borderless">
			<tr>
			    <div class="viewcontent-text">
			        <td class="">
			            <?php echo JText::_('COM_AXISUBS_TITLE_SUBSCRIPTIONS_ID');?>
			        </td>
			        <td>
			            <?php echo $this->subscription->axisubs_subscription_id; ?>
			        </td> 
			    </div>
			</tr>
			<tr>
			    <td class="">
			        <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_PLAN_AMOUNT');?>
			    </td>
			    <td class="">
			    <?php echo $curr->format( $this->subscription->total, $this->subscription->currency); ?>
			    </td>
			</tr>
		</table>
	</div>
	<div class="col-md-4">
		<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTION_DETAILS'); ?>
		<table class="table ">
			<tr>
				<td>
					<?php echo JText::_('COM_AXISUBS_CUSTOMER_PAYMENT_METHOD'); ?>
				</td>
				<td>
					<?php $payment_list = Select::getAllPaymentMethods(); 
						echo JHtml::_('select.genericlist', $payment_list, 'payment_processor', array(), 'value', 'text'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTION_PROCESSOR_STATUS'); ?>
				</td>
				<td>
					<input type="text" name="processor_status">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTION_ID'); ?>
				</td>
				<td>
					<input type="text" name="transaction_ref_id">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTION_AMOUNT'); ?>
				</td>
				<td>
					<input type="text" name="transaction_amount" value="<?php echo $this->subscription->total; ?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRANSACTION_CURRENCY'); ?>
				</td>
				<td>
					<input type="text" name="transaction_currency" value="<?php echo $this->subscription->currency; ?>">
				</td>
			</tr>
			<tr>
				<td>
					
				</td>
				<td>
					<button type="submit" class="btn btn-success"><i class="fa fa-tick"></i><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACTIONS_MARK_PAYMENT_COMPLETE');?></button>
				</td>
			</tr>
		</table>
	</div>
	<div class="col-md-4"></div>
</div>
</form>