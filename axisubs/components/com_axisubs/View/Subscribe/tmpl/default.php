<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;
use Flycart\Axisubs\Admin\Helper\Axisubs;
require_once JPATH_SITE . '/components/com_users/helpers/route.php';
?>
<form action="<?php echo JRoute::_('index.php'); ?>"  method="post" name="axisubs_subscribe_form" id="axisubs_subscribe_form">
    <div class="axisubs-bs3">
        <div class="row">
            <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribeform-beforecontent'); ?>
        </div>
        <div class="row">
          <h1 class="head-title"><strong><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TITLE'); ?></strong></h1>
<!--          <div class="help">Need help? <a href="mailto:support@j2store.org">flycart.org</a></div>-->
        </div>

        <div class="row">
            <?php
            $countRightModule = JDocumentHtml::getInstance()->countModules('axisubs-subscribeform-sidebar');
            ?>
            <div class="<?php echo ($countRightModule) ? 'col-md-8 col-lg-8': 'col-md-12 col-lg-12'?>">
                <div id="p-wrapper-hp">
                    <div id="p-main-content">
                        <div id="p-order">
                            <!--<h3 id="p-order-title">
              								<?php /* echo JText::_('COM_AXISUBS_SUBSCRIBE_ORDER_SUMMARY');*/ ?>
              							</h3> -->
                            <div id="p-order-summary-main-list">
                                <ul class="list-unstyled header-list">
                                    <li class="row">
                                      <div class="col-xs-8 summary-list">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ORDER_SUMMARY'); ?>
                                      </div>
                                      <div class="col-xs-4 text-right">
                                        <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ITEM_PRICE'); ?>
                                      </div>
                                    </li>
                                  </ul>
                              </div>
                            <div id="p-order-summary-main-list">
                                <ul class="list-unstyled">
                                    <li class="row">
                                        <div class="col-xs-8 summary-list">
                                          <div class="list-image">
                                              <?php $image = ($this->plan['image'] != '')? $this->plan['image'] : 'media/com_axisubs/images/ico_noimage.png';
                                              $image = JUri::base().$image;
                                              ?>
                                            <img src="<?php echo $image; ?>"/>
                                          </div>
                                            <div class="list-content">
                                              <span class="product-title"><strong>
                                                <?php echo $this->plan['name']; ?>
                                                ( <?php echo Axisubs::currency()->format($this->subscription->plan_price); ?> x <?php echo $this->subscription->plan_quantity; ?>)
                                              </strong></span>
                                              <?php echo $this->plan['description']; ?>

                                            </div>
                                        </div>
                                        <div class="col-xs-4 text-right">
                                             <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->plan_price); ?></span>
                                             <?php if ($this->subscription->setup_fee > 0): ?>
                                               <div>
                                               <span class="price_lable"><?php echo $this->plan['name']; ?> <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_SETUP_FEE'); ?>: </span>
                                               <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->setup_fee); ?></span>
                                             </div>
                                            <?php endif; ?>
                                             <div>
                                             <?php if ($this->subscription->tax > 0): ?>
                                                 <?php
                                                 foreach ($this->subscription->tax_details as $tax_item){
                                                     ?>
                                                     <span class="price_lable"><?php echo $tax_item['label']; ?> (<?php echo $tax_item['rate']; ?>%): </span>
                                                  <span class="product_price">   <?php

                                                     // display_price($plan_price, $tax_config_including_or_excluding )
                                                     echo Axisubs::currency()->format($tax_item['price']); ?></span>
                                                     <?php
                                                     }
                                                     ?>
                                              </div>
                                             <div>
                                               <span class="price_lable"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL_TAX'); ?>: </span>
                                               <span class="product_price"><?php echo Axisubs::currency()->format($this->subscription->tax); ?></span>
                                             </div>
                                        </div>
                                    </li>
                                    <?php endif; ?>
                                    <?php
                                    // To display more price list like discount
                                    echo $this->morePriceList;
                                    ?>
                                </ul>
                            </div>

                            <div id="p-order-summary-sub-list" class="text-right">

                            </div>
                            <div id="p-order-total" class="row text-right">
                                <div class="col-xs-12 product-total">
                                    <b><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TOTAL'); ?>:
                                    <?php echo Axisubs::currency()->format($this->subscription->total); ?></b>
                                </div>
                            </div>
                        </div>
                        <?php
                        if($this->subscription->plan->plan_type && (!$this->subscription->discount)){
                            //echo $this->coupon;
                        }
                        ?>
                        <?php
                        // To display options for discount and more
                        echo $this->optionsBelowPrice;
                        ?>
                    </div>
                    <?php
                    $userId =\JFactory::getUser()->id;
                    if(!$userId){
                    ?>
                    <ul class="nav nav-tabs bordcolor axisubs_tab">
                        <li class="active ">
                            <a data-toggle="tab" href="#axis-subscribeform-login" class="outlne">
                               <span> <?php echo JText::_('COM_AXISUBS_LOGIN_FORM_TITLE');?> </span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#axis-subscribeform-createuser" class="outlne" id="axis-subscribeform-createuser-tabtitle">
                                <span> <?php echo JText::_('COM_AXISUBS_CREATE_REGISTER_FORM_TAB');?> </span>
                            </a>
                        </li>
                    </ul>
                    <?php } ?>
                    <div class="tab-content">
                        <?php if(!$userId){ ?>
                        <div id="axis-subscribeform-login" class="tab-pane fade in active">
                            <div id="axisub-login-form" class="">
                                <h3><?php echo JText::_('COM_AXISUBS_LOGIN_FORM_TITLE');?></h3>
                                <div id="l-main-form">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="login_username">
                                                    <?php echo JText::_('COM_AXISUBS_LOGIN_USER_NAME'); ?>
                                                    <span>*</span>
                                                </label>
                                                <input id="login_username" name="login[username]" type="text" class="form-control" validate="true" required>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="login_password">
                                                    <?php echo JText::_('COM_AXISUBS_LOGIN_PASSWORD'); ?>
                                                    <span>*</span>
                                                </label>
                                                <input id="login_password" name="login[password]" class="form-control" validate="true" type="password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button class="btn btn-large btn-primary btn-lg" id="login_btn" onclick="loginUser(this)" type="button"><?php echo JText::_('COM_AXISUBS_LOGIN_FORM_BUTTON'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <ul class="unstyled">
                                                <li>
                                                    <a onclick="jQuery('#axis-subscribeform-createuser-tabtitle').click(); " >
                                                        <?php echo JText::_('COM_AXISUBS_NEW_SIGNUP'); ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind&Itemid=' . UsersHelperRoute::getRemindRoute()); ?>">
                                                        <?php echo JText::_('COM_AXISUBS_FORGOT_YOUR_USERNAME'); ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&Itemid=' . UsersHelperRoute::getResetRoute()); ?>">
                                                        <?php echo JText::_('COM_AXISUBS_FORGOT_YOUR_PASSWORD'); ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div id="axis-subscribeform-createuser" class="<?php echo $userId ? '': 'tab-pane fade' ?>">
                            <div id="p-payment-method-card">
                                <div id="p-main-form">
                                        <?php if ( \JFactory::getUser()->id <= 0 /*|| $this->customer->user_id <= 0*/ ): ?>
                                        <div id="p-account">
                                            <h3 id="p-account-title"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_ACCOUNT_INFORMATION'); ?></h3>
                                            <div id="p-account-fields">
                                             <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="customer[first_name]">
                                                                <?php echo JText::_('AXISUBS_ADDRESS_FIRST_NAME'); ?>
                                                                <span>*</span>
                                                            </label>
                                                            <input id="customer[first_name]" name="customer[first_name]" minlength="2" type="text" class="form-control" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="customer[last_name]">
                                                                <?php echo JText::_('AXISUBS_ADDRESS_LAST_NAME'); ?>
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
                                        <?php if($this->plan['plan_type']){ ?>
                                        <!-- CREDIT CARD FORM STARTS HERE -->
                                        <div id="p-payment">
                                            <h3 id="p-payment-title"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_PAYMENT_METHOD_INFORMATION'); ?></h3>
                                            <?php echo $this->loadTemplate('payment'); ?>
                                        </div>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <button type="button" onclick="registerUser(this)" id="subscribe_btn" class="btn btn-large btn-primary btn-lg"> <?php echo JText::_( 'AXISUBS_SUBSCRIBE'); ?> </button>
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
                </div>
            </div>
            <?php if($countRightModule){ ?>
            <div class="col-md-4 hidden-xs">
                <div class="subscribeform-sidebar">
                    <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribeform-sidebar'); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <?php echo $this->getContainer()->template->loadPosition('axisubs-subscribeform-beforecontent'); ?>
    </div>
    <hr class="clearfix dashed">

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
<?php
if(!$userId){
    $subscribeBtnText = JText::_( 'AXISUBS_SUBSCRIBE_REGISTERING_USER');
} else {
    $subscribeBtnText = JText::_( 'AXISUBS_SUBSCRIBE_BUTTON_LOADING_PLEASE_WAIT');
}
?>
<script>
    function loginUser(elem){
        (function($) {
            $('#task').val('loginUser');
            var fields = $( "#axisubs_subscribe_form" ).serializeArray();
            fields.push({'name':'ajax','value':'ajax'});
            $('.warning, .j2error').remove();
            $('#login_btn').prop('disabled', true);
            $('#login_btn').text('<?php echo JText::_('COM_AXISUBS_LOGIN_BUTTON_LOGIN_USER_PLEASE_WAIT'); ?>');
            $.ajax({
                type : 'post',
                url :  'index.php?option=com_axisubs&view=Subscribe&task=loginUser',
                dataType : 'json',
                data : fields,
                cache: false,
                async : false,
                success : function(json) {
                    if (json['error']) {
                        $.each( json['error'], function( key, value ) {
                            if (value) {
                                $('#axisubs_subscribe_form #'+key).after('<span class="j2error">' + value + '</span>');
                            }
                        });

                        if($( ".j2error" ).length) {
                            // incase the form is lengthy the error is not visible to user.
                            // this block scrolls to the error highlighted area
                            $('html, body').animate({
                                scrollTop: $(".j2error").offset().top - 80
                            }, 700);
                        }
                        $('#login_btn').prop('disabled', false);
                        $('#login_btn').text('<?php echo JText::_('COM_AXISUBS_LOGIN_FORM_BUTTON'); ?>');
                    }
                    if (json['success']) {
                        // success
                        $('#login_btn').text('<?php echo JText::_('COM_AXISUBS_LOGIN_BUTTON_LOGIN_SUCCESSFULL_PLEASE_WAIT'); ?>');
                    }
                    if (json['redirect']) {
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        })(axisubs.jQuery);
    }
function registerUser(elem){
	(function($) {
        $('#task').val('subscribeUser');
		var fields = $( "#axisubs_subscribe_form" ).serializeArray();
        fields.push({'name':'ajax','value':'ajax'});
		$('.warning, .j2error').remove();
		$('#subscribe_btn').prop('disabled', true);
		$('#subscribe_btn').text('<?php echo $subscribeBtnText; ?>');
		$.ajax({
			type : 'post',
			url :  'index.php?option=com_axisubs&view=Subscribe&task=subscribeUser',
			dataType : 'json',
			data : fields,
            cache: false,
			async : false,
			success : function(json) {
				if (json['error']) {
					$.each( json['error'], function( key, value ) {
						if (value) {
							$('#axisubs_subscribe_form #'+key).after('<span class="j2error">' + value + '</span>');
						}
					});
                    if($( ".j2error" ).length) {
                        // incase the form is lengthy the error is not visible to user.
                        // this block scrolls to the error highlighted area
                        $('html, body').animate({
                            scrollTop: $(".j2error").offset().top - 80
                        }, 700);
                    }

				}
				if (json['success']) {
                    // success
                    $('#subscribe_btn').text('<?php echo JText::_( 'AXISUBS_SUBSCRIBE_REGISTRATION_SUCCESSFULL'); ?>');
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
		$('#subscribe_btn').text('<?php echo JText::_( 'AXISUBS_SUBSCRIBE'); ?>');
	})(axisubs.jQuery);
}
</script>
<script>

jQuery(document).ready(function($){

    (function($) {
        $( ".axis-subscribeform-newuser" ).on( "click", function() {
            $("li a[href$='#axis-subscribeform-createuser']").click();
        });

        $( "#customer\\[first_name\\]" ).on( "paste keyup", function() {
            $( "#billing_address\\[first_name\\]" ).val( $( this ).val() );
        });

        $( "#customer\\[last_name\\]" ).on( "paste keyup", function() {
            $( "#billing_address\\[last_name\\]" ).val( $( this ).val() );
        });
    })(axisubs.jQuery);



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
