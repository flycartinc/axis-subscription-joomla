<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
?>

<?php echo $this->getRenderedForm(); ?>

<script>
jQuery(document).ready(function(){

	jQuery('#billing_country').change(function() {
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

				jQuery('#billing_state').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	jQuery('#billing_country').trigger('change');
	jQuery('#billing_state').val('<?php echo $this->item->billing_state; ?>');

});
</script>