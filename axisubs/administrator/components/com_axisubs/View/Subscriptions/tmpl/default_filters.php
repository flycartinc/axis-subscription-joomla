<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Select;
$curr = Axisubs::currency();
$status_helper = Axisubs::status();
$model = $this->getModel();
?>
<div class="row">
	<div class="col-md-2">
		<?php echo JText::_('COM_AXISUBS_DATE_FILTER_LABEL'); ?> <br>
		<?php $date_filter_options =  Select::getDateRangeFilterOptions(); ?>
		<select name="filter_date_range" id="date-range" onchange="fillStartEndDateFields();" class="filter_field" >
			<?php foreach ($date_filter_options as $opt) : ?>
				<option value="<?php echo $opt['key']; ?>" 
					<?php echo ( $model->getState('filter_date_range','nofilter') == $opt['key'] ) ? 'selected="selected"': ''; ?>
						data-start-date="<?php echo $opt['start_date']; ?>" 
						data-end-date="<?php echo $opt['end_date']; ?>" 
					 	> <?php echo $opt['value']; ?> </option>	
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-md-3">
		<?php echo JText::_('COM_AXISUBS_DATE_FILTER_START_DATE_LABEL'); ?> 
		<?php echo Select::calendar('filter_start_date',
										$model->getState('filter_start_date',''),
										array('class'=>'filter_field'));?>
	</div>
	<div class="col-md-3">
		<?php echo JText::_('COM_AXISUBS_DATE_FILTER_END_DATE_LABEL'); ?> 
		<?php echo Select::calendar('filter_end_date',
										$model->getState('filter_end_date',''),
										array('class'=>'input-small filter_field'));?>

	</div>
	<div class="col-md-3 p-t-15">
		<label for="filter_price_start">
			<?php echo JText::_('COM_AXISUBS_FILTER_BY_PRICE_FROM_LABEL'); ?> 
		</label>	
		<input type="text" name="filter_price_from" value="<?php echo $model->getState('filter_price_from',''); ?>" 
							class="filter_price_from filter_field" style="width:100px" />
		
		<label for="filter_price_end">
			<?php echo JText::_('COM_AXISUBS_FILTER_BY_PRICE_TO_LABEL'); ?> 
		</label>
		<input type="text" name="filter_price_to" value="<?php echo $model->getState('filter_price_to',''); ?>" 
							class="filter_price_to filter_field" style="width:100px" />
	</div>
	<div class="col-md-2 pull-right">
		<button class="btn btn-success" onclick="this.form.submit();"> <i class="fa fa-search"></i> <?php echo JText::_('COM_AXISUBS_FILTER_SEARCH'); ?> </button>
		<button class="btn btn-info" onclick="resetFilters();" type="button"><i class="fa fa-cross"></i><?php echo JText::_('COM_AXISUBS_FILTER_RESET'); ?></button>
	</div>
</div>

<script>
	function fillStartEndDateFields(){
		jQuery(function() { 
	        var element = jQuery("#date-range").find('option:selected'); 
	        var st = element.data("start-date"); 
	        var e = element.data("end-date"); 

	        jQuery('#filterstartdate').val(st); 
	        jQuery('#filterenddate').val(e); 
	        jQuery('#filterenddate').closest("form").submit();
		});
	}
	function resetFilters(){
		jQuery('.filter_field').val('');
		jQuery('#filter_search').val('');
		jQuery('#date-range').val('nofilter');
		jQuery('#filterenddate').closest("form").submit();
	}
</script>