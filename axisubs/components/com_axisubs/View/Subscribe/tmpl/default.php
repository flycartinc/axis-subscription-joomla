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
<form action="<?php echo JRoute::_('index.php'); ?>"  method="post" name="axisubs_subscribe_form" id="axisubs_subscribe_form">
    <div class="axisubs-bs3">
        <div class="h3 text-center">
		<?php echo JText::_('COM_AXISUBS_SUBSCRIBE_THANKYOU_HEADING'); ?>
            <hr class="clearfix dashed">
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div id="p-wrapper-hp">
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
                                                <?php echo $this->plan['name']; ?> 
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
                                            <strong> <?php echo $this->plan['name']; ?> <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE'); ?></strong>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                            <?php echo Axisubs::currency()->format($this->subscription->setup_fee); ?>
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
                    <div id="p-payment-method-card">
                        <div id="p-main-form">
                            <input id="coupon" name="coupon" value="" type="hidden">
                                <?php if ( \JFactory::getUser()->id <= 0 || $this->customer->user_id <= 0 ): ?>
                                <div id="p-account">
                                    <h3 id="p-account-title"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACCOUNT_INFORMATION'); ?></h3>
                                    <div id="p-account-fields">
                                     <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customer[first_name]">
                                                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_FIRST_NAME'); ?>
                                                        <span>*</span>
                                                    </label>
                                                    <input id="customer[first_name]" name="customer[first_name]" minlength="2" type="text" class="form-control" required>
                        
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customer[last_name]">
                                                        <?php echo JText::_('COM_AXISUBS_CUSTOMER_LAST_NAME'); ?>
                                                    </label>
                                                    <input id="customer[last_name]" name="customer[last_name]"minlength="1" class="form-control" value="" validate="true" type="text" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customer[email]"><?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL'); ?>
                                                        <span>*</span>
                                                    </label>
                                                    <input id="customer[email]" name="customer[email]" class="form-control" value="" validate="true" type="email" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customer[password1]"><?php echo JText::_('COM_AXISUBS_CUSTOMER_PASSWORD1'); ?>
                                                        <span>*</span>
                                                    </label>
                                                    <input id="customer[password1]" name="customer[password1]" class="form-control" value="" validate="true" type="password" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="customer[password2]"><?php echo JText::_('COM_AXISUBS_CUSTOMER_PASSWORD2'); ?>
                                                        <span>*</span>
                                                    </label>
                                                    <span id="customer[password1].err" class="text-danger pull-right">&nbsp;</span>
                                                    <input id="customer[password2]" name="customer[password2]" class="form-control" value="" validate="true" type="password" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                                <div id="p-billing">
                                    <h3 id="p-billing-title"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_BILLING_INFORMATION'); ?></h3>
                                    <!-- Billing address fields -->
                                    <div id="p-billing-fields">
                                    <?php echo $this->loadAnyTemplate('site:com_axisubs/Subscribe/billing_fields'); ?>
                                    </div>
                                </div>

                                <!-- CREDIT CARD FORM STARTS HERE -->
                                <div id="p-payment">
                                    <h3 id="p-payment-title"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_PAYMENT_METHOD_INFORMATION'); ?></h3>
                                    <?php echo $this->loadTemplate('payment'); ?>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button type="button" onclick="registerUser(this)" id="subscribe_btn" class="btn btn-large btn-success btn-lg btn-block"> <?php echo JText::_( 'AXISUBS_SUBSCRIBE'); ?> </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-main-footer">
                                    <hr class="clearfix dashed">
                                    <div class="error-holder">
                                        <div class="p-status-flash p-main-status text-danger">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 hidden-xs">
                <div class="p-well">
                    <div>
                        <h4>We value your Privacy.</h4>
                        <p>We will not sell or distribute your contact information. Read our Privacy Policy.</p>
                        <hr>
                        <h4>Billing Enquiries</h4>
                        <p>Do not hesitate to reach our support team if you have any queries.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="axisubs">
		<div class="row-fluid">
			<div class="hide" style="display:none;">
				<input type="checkbox" name="custom[confirm_informed]" id="custom_confirm_informed" checked />
				<input type="checkbox" name="custom[confirm_postal]" id="custom_confirm_postal" checked />
				<input type="checkbox" name="custom[confirm_withdrawal]" id="custom_confirm_withdrawal" checked />
				<input type="checkbox" name="custom[agreetotos]" id="custom_agreetotos" checked />
            </div>
			<div class="span8 center">
			<input type="hidden" name="axisubs_customer_id" id="axisubs_customer_id" value="<?php // echo $user['axisubs_customer_id']; ?>">
            <input type="hidden" name="plan_id" id="plan_id" value="<?php echo $this->plan['axisubs_plan_id']; ?>">
			<input type="hidden" name="slug" id="slug" value="<?php echo $this->plan['slug']; ?>">
			<input type="hidden" name="option" id="option" value="com_axisubs">
			<input type="hidden" name="view" id="view" value="Subscribe">
			<input type="hidden" name="task" id="task" value="subscribeUser">
			<input type="hidden" name="user_id" id="user_id" value="<?php echo \JFactory::getUser()->id;?>">
			</div>			
		</div>
	</div>
</form>

<script>
function registerUser(elem){
	(function($) {
		var fields = $( "#axisubs_subscribe_form" ).serializeArray();
        fields.push({'name':'ajax','value':'ajax'});
		$('.warning, .j2error').remove();
		$('#subscribe_btn').prop('disabled', true);
		$('#subscribe_btn').text('Registering user... Please wait');

		$.ajax({
			type : 'post',
			url :  'index.php?option=com_axisubs&view=Subscribe&task=subscribeUser',
			dataType : 'json',
			data : fields,
			async : false,
			success : function(json) {
				if (json['error']) {
					$.each( json['error'], function( key, value ) {
						if (value) {
							$('#axisubs_subscribe_form #'+key).after('<span class="j2error">' + value + '</span>');
						}
					});
					// incase the form is lengthy the error is not visible to user.
					// this block scrolls to the error highlighted area
					$('html, body').animate({
				        scrollTop: $(".j2error").offset().top-80
				    }, 700);
				
				}
				if (json['success']) {
                    // success
                    $('#subscribe_btn').text('Registration Successful');
				}
				if (json['redirect']) {
					window.location = json['redirect']; 						
				}
			},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		});
		
		$('#subscribe_btn').prop('disabled', false);
		$('#subscribe_btn').text('Subscribe');
	})(axisubs.jQuery);
}
</script>
<script>

jQuery(document).ready(function($){

    $( "#customer\\[first_name\\]" ).on( "paste keyup", function() { 
         $( "#billing_address\\[first_name\\]" ).val( $( this ).val() );
    });

    $( "#customer\\[last_name\\]" ).on( "paste keyup", function() { 
         $( "#billing_address\\[last_name\\]" ).val( $( this ).val() );
    });

    /*$("#axisubs_subscribe_form").validate({
          rules: {
           'customer\\[password1\\]': {
              required:true,
              minlength:5
            },
            'customer\\[password2\\]':{
              required:true,
              minlength:5,
              equalTo:"'customer\\[password1\\]'"
            }
          },
          messages: {
           'customer\\[password1\\]': {
            required:"Enter the Password",
          },
          'customer\\[password2\\]': {
            equalTo:"Oops, Password doesn't seem to match."
          }
      }
    });*/
});
</script>
<style>
    .has-success{
        border: 1px solid #444;
    }
    .has-error{
        border: 1px solid #DD0000!important;
    }
</style>