<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Select;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$fieldsets = $this->form->getFieldsets();
$app = JFactory::getApplication();
?>
<div class="">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" 
		name="adminForm" id="adminForm" class="form-horizontal form-validate">
		<input type="hidden" value="com_axisubs" name="option">
		<input type="hidden" value="Customer" name="view">
		<input type="hidden" value="" name="task">
		<?php echo JHtml::_('form.token'); ?>

		<?php foreach ($fieldsets as $fieldset) : ?>
			<div id="<?php echo $fieldset->name; ?>" class="<?php echo $fieldset->class; ?>" >
			<h3><?php echo JText::_('COM_AXISUBS_USER_BASIC_TITLE'); ?></h3>
			<?php 
				$fields = $this->form->getFieldset($fieldset->name);
				foreach ($fields as $field) : ?>
				<div class="control-group" >
		    		<div class="control-label"><?php echo $field->label; ?></div>
		    		<div class="controls"><?php echo $field->input; ?></div>
		 		</div>
			<?php endforeach;?>
			</div>
		<?php endforeach; ?>
	</form>
</div>
<script>
jQuery(document).ready(function(){
	// For hide new/existing radio while editing
	<?php if($this->item->axisubs_customer_id){ ?>
	 jQuery("#new_or_existing_customer").parent().parent('.control-group').hide('slow');
	<?php } ?>

	jQuery('#user_id_id').change(function() {
		jQuery.ajax({
			url: 'index.php?option=com_axisubs&view=Customers&task=getUserDetails&id=' + jQuery(this).val(),
			dataType: 'json',
			async	: false,
			success: function(json) {
				if(json.exist_already == 1){
					var confirmResult = confirm("<?php echo JText::_('COM_AXISUBS_ALREADY_EXIST_CONFIRMBOX_TEXT'); ?>");
					if(confirmResult){
						var urlRedirect = 'index.php?option=com_axisubs&view=Customer&id='+json.id;
						window.location.href = urlRedirect;
					} else {
						//wait for 2 seconds
						setTimeout(
							function()
							{
								jQuery("#user_id, #user_id_id, #email").val('');
							}, 2000);

					}
				} else {
					jQuery.each(json, function(k, v) {
						jQuery('#'+k).val(v);
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	jQuery('#country').change(function() {
	     // get the zone / sub divisions and load
	    jQuery.ajax({
			url: 'index.php?option=com_axisubs&view=Customers&task=getState&country=' + jQuery(this).val(),
			dataType: 'json',
			async	: false,
			success: function(json) {
				
				html = '<option value=""><?php echo JText::_('JSELECT'); ?></option>';

				jQuery.each(json, function(k, v) {
				    html += '<option value="' + k + '"';
				    if (false) {
		      			html += ' selected="selected"';
		    		}
				    html += '>' + v + '</option>';
				});

				jQuery('#state').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	jQuery('#country').trigger('change');
	jQuery('#state').val('<?php echo $this->item->state; ?>');

});
</script>