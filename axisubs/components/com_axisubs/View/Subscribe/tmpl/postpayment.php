<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
 
defined('_JEXEC') or die();

use Flycart\Axisubs\Admin\Helper\Axisubs;

$status_helper  = Axisubs::status();
if(isset($this->returnStatus)) {
	?>
	<?php if ($this->returnStatus == "cancel") { ?>
		<div class="">
			<p class="text-warning">
				<?php echo JText::_('COM_AXISUBS_PAYMENT_CANCELED_MESSAGE') ?>
			</p>
		</div>
	<?php } else {
		if (!empty($this->subscriptionDetails)) {
			?>
			<div class="">
				<?php if ($this->subscriptionDetails->transaction->transaction_status == 'paid') { ?>
					<p class="text-success">
						<?php echo JText::_('COM_AXISUBS_PAYMENT_SUCCESS_MESSAGE') ?>
					</p>
				<?php } else if ($this->subscriptionDetails->transaction->transaction_status == 'failed') { ?>
					<p class="text-danger">
						<?php echo JText::_('COM_AXISUBS_PAYMENT_FAILED_MESSAGE') ?>
					</p>
				<?php } else if ($this->subscriptionDetails->transaction->transaction_status == 'pending') { ?>
					<p class="text-warning">
						<?php echo JText::_('COM_AXISUBS_PAYMENT_PENDING_MESSAGE') ?>
					</p>
				<?php } ?>
			</div>
		<?php }
	}
}?>
<?php if(!empty($this->subscriptionDetails)){ ?>
	<div class="axisubs-postpayment">
		<div class="alert alert-message">
			<div class="axisubs-subscription-status-header">
				Subscription Details:
			</div>
			<div class="axisubs-subscription-status-item">
				<b><?php echo JText::_('COM_AXISUBS_PLAN_NAME').':'; ?> </b><?php echo $this->subscriptionDetails->plan->name; ?>
			</div>
			<?php if($this->subscriptionDetails->transaction->payment_processor){ ?>
			<div class="axisubs-subscription-status-item">
				<b><?php echo JText::_('COM_AXISUBS_PAYMENT_TYPE'); ?>: </b><?php echo JText::_('COM_AXISUBS_'.strtoupper($this->subscriptionDetails->transaction->payment_processor)); ?>
			</div>
			<?php } ?>
			<div class="axisubs-subscription-status-item">
				<b><?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_STATUS'); ?>: </b><?php echo $status_helper->get_text($this->subscriptionDetails->status); ?>
			</div>
		</div>
	</div>
<?php } ?>
<div class="axisubs-postpayment">
	<?php  echo $this->postPaymentForm ; ?>
</div>

<a class="btn btn-primary" href="index.php?option=com_axisubs&view=profile#subscribe">
	<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_VIEW_SUBSCRIPTIONS'); ?>
</a>
