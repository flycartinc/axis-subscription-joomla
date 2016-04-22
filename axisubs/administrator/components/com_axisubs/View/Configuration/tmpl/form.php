<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Flycart\Axisubs\Admin\Helper\Axisubs;
?>
<div class="j2store-configuration ">
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">
	<input type="hidden" name="option" id="option" value="com_axisubs">
	<input type="hidden" name="view" id="view" value="Configurations">
	<input type="hidden" name="task" id="task" value="">
	<?php echo JHtml::_('form.token'); ?>
<?php
        $fieldsets = $this->form->getFieldsets();
        $shortcode = $this->form->getValue('text');
        $tab_count = 0;

        foreach ($fieldsets as $key => $attr)
        {

	            if ( $tab_count == 0 )
	            {
	                echo JHtml::_('bootstrap.startTabSet', 'configuration', array('active' => $attr->name));
	            }
	            echo JHtml::_('bootstrap.addTab', 'configuration', $attr->name, JText::_($attr->label, true));
	            ?>
	       

	            <div class="row-fluid">
	                <div class="span12">
	                    <?php
	                    $layout = '';
	                    $style = '';
	                    $fields = $this->form->getFieldset($attr->name);
	                    foreach ($fields as $key => $field)
	                    {
	                    	$pro = $field->getAttribute('pro');
	                    ?>
	                        <div class="control-group <?php echo $layout; ?>" <?php echo $style; ?>>
	                            <div class="control-label"><?php echo $field->label; ?></div>
	                            
	                            	<div class="controls"><?php echo $field->input; ?>
	                            	<br />
	                            	<small class="muted"><?php echo JText::_($field->description); ?></small>
	                            

	                            </div>
	                        </div>
	                    <?php
	                    }
	                    ?>
	                </div>
	            </div>
	           
	            <?php
	            echo JHtml::_('bootstrap.endTab');
	            $tab_count++;

        }
        ?>
       
 </form>
</div>
<?php $zone_id = Axisubs::config()->get('zone_id',''); ?>
<script>
jQuery(document).ready(function(){

	jQuery('#axisubs_country_id').change(function() {
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

				jQuery('#axisubs_zone_id').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				//alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	jQuery('#axisubs_country_id').trigger('change');
	jQuery('#axisubs_zone_id').val('<?php echo $zone_id; ?>');

});
</script>