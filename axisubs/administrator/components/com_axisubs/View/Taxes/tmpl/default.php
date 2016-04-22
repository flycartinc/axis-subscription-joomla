<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Select;
defined( '_JEXEC' ) or die( 'Restricted access' );
$config = Axisubs::config();
$sidebar = JHtmlSidebar::render();
$country = Select::getCountries();
$zone_id = Axisubs::config()->get('zone_id','');
$country_id = Axisubs::config()->get('country_id','');
$zones = Select::getStates();
?>

<style>
	.axisubs-bs3 input{
		width: 80px;
	}
</style>

<div class="axisubs-bs3">
	<?php if(!empty( $sidebar )): ?>
	<div id="j-sidebar-container">
	  <?php echo $sidebar ; ?>
	</div>
	<?php endif;?>
	<div id="j-main-container-create" class="row">
		<div class="col-md-12">
			<div class="notification"></div>
			<form name="adminForm" id="adminForm" action="index.php" method="post">
				<input type="hidden" name="option" value="com_axisubs" />
				<input type="hidden" name="view" value="Taxes" />
				<input type="hidden" name="task" value="updateTax" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php //echo $model->getState('order',''); ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php //echo $model->getState('order_Dir',''); ?>" />
				<input type="hidden" id="token" name="<?php echo \JFactory::getSession()->getFormToken(); ?>" value="1" />

				<table class="table table-bordered">
					<tr>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_COUNTRY'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_STATE'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_RATE'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_NAME'); ?></th>
						<!-- <th><?php echo JText::_('COM_AXISUBS_TAXRATE_PRIORITY'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_SHIPPING'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_COMPOUND'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_ORDERING'); ?></th>
						<th><?php echo JText::_('COM_AXISUBS_TAXRATE_CLASS'); ?></th> -->
						<th></th>
					</tr>
					<tr class="tax_tr">
						<td>
							<input type="hidden" id="axisubs_taxrate_id" name="axisubs_taxrate_id" value="" />
							<select id="axisubs_country_id" name="tax_rate_country">
								<?php foreach($country as $key=>$value):?>
								<option value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</td>
						<td>
							<select id="axisubs_zone_id" name="tax_rate_state">
								<?php foreach($zones as $key=>$value):?>
									<option value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</td>
						<td>
							<input type="text" id="tax_rate" name="tax_rate" value="" />
						</td>
						<td>
							<input type="text" id="tax_rate_name" name="tax_rate_name" value="" />
						</td>
					<!-- 	<td>
							<input type="text" id="tax_rate_priority" name="tax_rate_priority" value="" />
						</td>
						<td>
							<input type="text" id="tax_rate_shipping" name="tax_rate_shipping" value="" />
						</td>
						<td>
							<input type="text" id="tax_rate_compound" name="tax_rate_compound" value="" />
						</td>

						<td>
							<input type="text" id="tax_rate_order" name="tax_rate_order" value="" />
						</td> -->
						<td>
							<input type="hidden" id="tax_rate_priority" name="tax_rate_priority" value="" />
							<input type="hidden" id="tax_rate_shipping" name="tax_rate_shipping" value="" />
							<input type="hidden" id="tax_rate_compound" name="tax_rate_compound" value="" />
							<input type="hidden" id="tax_rate_class" name="tax_rate_class" value="standard" />
						</td>
					</tr>

					<tr>
						<td>
							<input type="submit" class="btn btn-info" name="submit" value="<?php echo JText::_('AXISUBS_CREATE_TAX_RATE');?>" />

						</td>
					</tr>

				</table>
			</form>
		</div>
	</div>

	<div id="j-main-container" class="row">
        <div class="col-md-12">
        	<div class="alert alert-info">
        		<b><?php echo JText::_('AXISUBS_TAXES_IMPORTANT_TAX_SETTINGS'); ?></b>
        		<a href="index.php?option=com_axisubs&view=Configuration#tax_settings" class="btn btn-warning pull-right"><?php echo JText::_('AXISUBS_TAXES_MANAGE_SETTING'); ?></a>
        		<table width="30%">
        			<tr>
        				<td> <?php echo JText::_('AXISUBS_TAXES_SETTING_IS_TAX_ENABLED'); ?> </td>
        				<td> <?php echo $config->get('enable_tax',1)? JText::_('JYES'): JText::_('JNO') ; ?> </td>
        			</tr>
        			<tr >
        				<td><?php echo JText::_('AXISUBS_TAXES_SETTING_PRICE_ENTERED_AS'); ?></td>
        				<td><?php echo $config->get('config_including_tax',1)? JText::_('AXISUBS_TAXES_SETTING_PRICE_ENTERED_INCLUDING_TAXES'): JText::_('AXISUBS_TAXES_SETTING_PRICE_ENTERED_EXCLUDING_TAXES') ; ?></td>
        			</tr>
        		</table>
			</div>
        </div>
        <!-- list all the tax based plugins -->


        <div class="col-md-12">
        	<table class="table table-bordered">
        		<tr>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_ID'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_COUNTRY'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_STATE'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_RATE'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_NAME'); ?></th>
        			<!-- <th><?php echo JText::_('COM_AXISUBS_TAXRATE_PRIORITY'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_SHIPPING'); ?></th>
        			<th><?php echo JText::_('COM_AXISUBS_TAXRATE_COMPOUND'); ?></th>
					<th><?php echo JText::_('COM_AXISUBS_TAXRATE_ORDERING'); ?></th>
					<th><?php echo JText::_('COM_AXISUBS_TAXRATE_CLASS'); ?></th> -->

        		</tr>

        	<?php foreach ($this->taxrates as $key=>$tax_rate) : ?>
        		<tr class="taxrate_<?php echo $tax_rate->axisubs_taxrate_id; ?>">
        			<td>
						<input type="hidden" name="axisubs_taxrate_id" value="<?php echo $tax_rate->axisubs_taxrate_id; ?>" />
						<?php echo $tax_rate->axisubs_taxrate_id; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate_country" value="<?php echo $tax_rate->tax_rate_country; ?>" />
						<?php echo $tax_rate->tax_rate_country; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate_state" value="<?php echo $tax_rate->tax_rate_state; ?>" />
						<?php echo $tax_rate->tax_rate_state; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate" value="<?php echo $tax_rate->tax_rate; ?>" />
						<?php echo $tax_rate->tax_rate; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate_name" value="<?php echo $tax_rate->tax_rate_name; ?>" />
						<?php echo $tax_rate->tax_rate_name; ?>
					</td>
        			<!-- <td>
						<input type="hidden" name="tax_rate_priority" value="<?php echo $tax_rate->tax_rate_priority; ?>" />
						<?php echo $tax_rate->tax_rate_priority; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate_shipping" value="<?php echo $tax_rate->tax_rate_shipping; ?>" />
						<?php echo $tax_rate->tax_rate_shipping; ?>
					</td>
        			<td>
						<input type="hidden" name="tax_rate_compound" value="<?php echo $tax_rate->tax_rate_compound; ?>" />
						<?php echo $tax_rate->tax_rate_compound; ?>
					</td>
					<td>
						<input type="hidden" name="tax_rate_order" value="<?php echo $tax_rate->tax_rate_order; ?>" />
						<?php echo $tax_rate->tax_rate_order; ?>
					</td>
					<td>
						<input type="hidden" name="tax_rate_class" value="<?php echo $tax_rate->tax_rate_class; ?>" />
						<?php echo $tax_rate->tax_rate_class; ?>
					</td> -->
					<td>
						<a href="#" class="btn btn-info" onclick="editTax('taxrate_<?php echo $tax_rate->axisubs_taxrate_id;?>')"><?php echo JText::_('AXISUBS_EDIT');?></a>
					</td>
					<td>
						<a href="#" class="btn btn-danger" onclick="deleteTax('<?php echo $tax_rate->axisubs_taxrate_id;?>')"><?php echo JText::_('AXISUBS_DELETE');?></a>
					</td>
        		</tr>
        	<?php endforeach; ?>
				<tfoot>
					<tr>
						<td colspan="10">
						</td>
					</tr>
				</tfoot>
        	</table>
        </div>

	</div>
</div>
<script>
	if(typeof(axisubs) == 'undefined') {
		var axisubs = {};
	}
	if(typeof(axisubs.jQuery) == 'undefined') {
		axisubs.jQuery = jQuery.noConflict();
	}

	function editTax(key){
		(function ($) {
			var data = $('.'+key+' input').serializeArray();
			$.each( data, function( p_key, value ) {
				console.log(value.name);
				if(value.name == 'tax_rate_country'){
					$('#axisubs_country_id').val(value.value);
				}else if(value.name == 'tax_rate_state'){
					$('#axisubs_zone_id').val(value.value);
				}
				$('#'+value.name).val(value.value);
			});
		})(axisubs.jQuery);
	}

	function deleteTax(id){
		(function ($) {
			$.ajax({
				type : 'post',
				url :  'index.php?option=com_axisubs&view=Taxes&task=deleteTax',
				data : 'taxrate_id=' + id,
				dataType : 'json',
				beforeSend: function() {
					$('.notification').html('');
				},
				success : function(data) {
					if(data['success']) {
						$('.notification').html('<span class="alert alert-notice">'+data['success']+'</span>');
						$('.taxrate_'+id).remove();
					}
					if(data['error']){
						$('.notification').html('<span class="j2error">'+data['error']+'</span>');
					}
				}
			});
		})(axisubs.jQuery);
	}
</script>


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