<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
//use \JFactory;
//use \JText;
$customer = $this->customer;
?>
<div class="axisubs-bs3 ">
<form action="index.php" name="axisubsCustomerForm" id="axisubs-customer-form" method="post">
	<input type="hidden" name="option" value="com_axisubs" >
	<input type="hidden" name="view" value="Profile" >
	<input type="hidden" name="task" value="saveCustomerAddress" >
	<input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>" >
<div class="row">
	<div class="col-md-9">
		<?php echo $this->loadAnyTemplate('site:com_axisubs/Subscribe/billing_fields'); ?>		
	</div>
</div>	

<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
    	<div class="pull-right ">
			<button type="button" class="btn btn-lg btn-info" id="saveCustomerAddressBtn"
					onclick="saveCustomerAddress();">
					<?php echo JText::_('AXISUBS_CUSTOMER_SAVE_ADDRESS'); ?>
			</button>
		</div>
	</div>
</div>
</form>
</div>

<script>	
function saveCustomerAddress(elem){
	(function($) {
		var fields = $( "#axisubs-customer-form" ).serializeArray();
        fields.push({'name':'ajax','value':'ajax'});
		$('.warning, .j2error').remove();
		$('#saveCustomerAddressBtn').prop('disabled', true);
		$('#saveCustomerAddressBtn').text('<?php echo JText::_('AXISUBS_CUSTOMER_SAVE_ADDRESS_SAVING'); ?>');

		$.ajax({
			type : 'post',
			url :  'index.php?option=com_axisubs&view=Profile&task=saveCustomerAddress',
			dataType : 'json',
			data : fields,
			async : false,
			success : function(json) {
				if (json['error']) {
					$.each( json['error'], function( key, value ) {
						if (value) {
							$('#axisubs-customer-form #'+key).after('<span class="j2error">' + value + '</span>');
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
                    $('#saveCustomerAddressBtn').text('<?php echo JText::_('AXISUBS_CUSTOMER_SAVE_ADDRESS_SAVING'); ?>');
				}
				if (json['redirect']) {
					$('#saveCustomerAddressBtn').text('<?php echo JText::_('AXISUBS_CUSTOMER_SAVE_ADDRESS_SAVING'); ?>');
					window.location = json['redirect']; 						
				}
			},
		error: function(xhr, ajaxOptions, thrownError) {
			//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		});
		
		$('#saveCustomerAddressBtn').prop('disabled', false);
		$('#saveCustomerAddressBtn').text('<?php echo JText::_('AXISUBS_CUSTOMER_SAVE_ADDRESS'); ?>');
	})(axisubs.jQuery);
}
</script>