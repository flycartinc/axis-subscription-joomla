<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<h2> <?php echo JText::_('COM_AXISUBS_PAYMENT'); ?> </h2>
<div class="axisubs-bs3">	
	<div id="p-main-content">
        <div id="p-order">
            <h3 id="p-order-title">
				<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ORDER_SUMMARY'); ?>
			</h3>
            <div id="p-order-summary-main-list">
                <ul class="list-unstyled">
                    <li class="row">
                        <div class="col-xs-8">
                            <strong>
                                <?php echo $this->subscription->plan->name; ?> 
                                ( <?php echo Axisubs::currency()->format($this->subscription->plan_price); ?> x <?php echo $this->subscription->plan_quantity; ?>)
                                </strong>
                        </div>
                        <div class="col-xs-4 text-right">
                             <?php echo Axisubs::currency()->format($this->subscription->plan_price); ?>
                        </div>
                    </li>
                    <?php if ($this->subscription->setup_fee > 0): ?>
                    <li class="row">
                        <div class="col-xs-8">
                            <strong> <?php echo $this->subscription->plan->name; ?> - <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE'); ?></strong>
                        </div>
                        <div class="col-xs-4 text-right">
                            <?php echo Axisubs::currency()->format($this->subscription->setup_fee); ?>
                        </div>
                    </li>
                <?php endif; ?>
                    <?php if ($this->subscription->tax > 0): ?>
                        <li class="row">
                            <div class="col-xs-8">
                                <strong><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_TAX'); ?></strong>
                            </div>
                            <div class="col-xs-4 text-right">
                                <?php echo Axisubs::currency()->format($this->subscription->tax); ?>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div id="p-order-summary-sub-list" class="text-right">
                <hr class="clearfix dashed">
            </div>
            <div id="p-order-total" class="row text-right">
                <div class="col-xs-8"><strong><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL'); ?></strong>

                </div>
                <div class="col-xs-4 text-right">
                    <?php echo Axisubs::currency()->format($this->subscription->total); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_NAME'); ?>
                    </td>
                    <td>
                        <?php echo $this->subscription->subscriptioninfo->billing_first_name; ?>
                        <?php echo $this->subscription->subscriptioninfo->billing_last_name; ?>
                    </td>
                </tr>
                <?php if (!empty($this->subscription->subscriptioninfo->billing_company)): ?>
                <tr>
                    <td>
                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_COMPANY'); ?>
                    </td>
                    <td>
                        <?php echo $this->subscription->subscriptioninfo->billing_company; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>
                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL'); ?>
                    </td>
                    <td>
                        <?php echo $this->subscription->subscriptioninfo->billing_email; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_ADDRESS'); ?>
                    </td>
                    <td>
                        <?php echo $this->subscription->subscriptioninfo->billing_address1; ?>
                        <?php echo $this->subscription->subscriptioninfo->billing_address2; ?>,
                        <?php echo $this->subscription->subscriptioninfo->billing_city; ?>, <br>
                        <?php $stateSelected = Select::getZones($this->subscription->subscriptioninfo->billing_country);
                        if(isset($stateSelected[$this->subscription->subscriptioninfo->billing_state])){
                            echo $stateSelected[$this->subscription->subscriptioninfo->billing_state].', ';
                        } ?>
                        <?php echo Select::decodeCountry($this->subscription->subscriptioninfo->billing_country); ?> <br>
                        <?php echo $this->subscription->subscriptioninfo->billing_zip; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-4 pull-right ">
            <a href="#" onclick="processPayment()"  class="btn btn-lg btn-info" ><?php echo JText::_('AXISUBS_PAY_NOW');?></a>
            <!--
            <input type="button" onclick="jQuery('#axisubs-payment-form').submit();" class="btn btn-lg btn-info" value="<?php echo JText::_('AXISUBS_PAY_NOW'); ?>">
            -->
        </div>
    </div>
</div>
<?php  echo $this->prePaymentForm ; ?>
<form action="index.php" name="axisubsPaymentForm" id="axisubs-payment-form">
	<div class="axisubs-payment-fields">

	</div> 
	<input type="hidden" name="option" value="com_axisubs" >
	<input type="hidden" name="view" value="Subscribe" >
	<input type="hidden" name="task" value="confirmPayment" >
	<input type="hidden" name="orderpayment_type" value="<?php echo $this->payment_method; ?>" >
	<input type="hidden" name="subscription_id" value="<?php echo $this->subscription_id; ?>" >
	<input type="hidden" name="plan_id" value="<?php echo $this->plan_id; ?>" >
	<input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" >
	<div class="axisubs-pay-button pull-right hide ">
		<input type="submit" class="btn btn-lg btn-info" value="<?php echo JText::_('AXISUBS_PAY_NOW'); ?>">
	</div>
</form>

<div class="row">
	<!--<div class="col-md-4">
		<pre><?php  /*print_r( $this->subscription->getData() );*/  ?></pre>
	</div>
	<div class="col-md-4">
		<pre><?php  /*print_r( $this->subscription->customer->getData() ); */ ?></pre>
	</div>
	<div class="col-md-4">
		<pre><?php  /*print_r( $this->subscription->plan->getData() ); */ ?></pre>
	</div> -->
</div>

<script>
	// write a script to autosubmit the payment form 
</script>
