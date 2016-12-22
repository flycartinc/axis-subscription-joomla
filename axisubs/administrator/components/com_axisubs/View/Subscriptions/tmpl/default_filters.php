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
$searchFiltersMore = array('filter_date_range',
	'filter_start_date',
	'filter_end_date',
	'filter_price_from',
	'filter_price_to',
	'plan_id',
	'status',
	'payment_processor');
$searchFilters = 0;
foreach ($searchFiltersMore as $value){
	if($model->getState($value) != ''){
		if($value == 'filter_date_range'){
			if($model->getState($value) != 'nofilter'){
				$searchFilters = 1;
			}
		} else {
			$searchFilters = 1;
		}
	}
}
?>
<style type="text/css">

	.filter-select{
		margin-bottom: 10px;
	}
	.axisubs-bs3 .input-append .btn{
		background-color: #286090;
		color: #fff;
		margin: 0 auto;
	}
	.axisubs-bs3 .planpadding dl dt{
		line-height: 2.3;
	}
	#j-main-container .filter-select{
		margin: 10px 0 0 15px;
		display: inline-block;
	}
</style>
<div class="row search-tools-more<?php echo $searchFilters ? ' active' : ''; ?>">
	<div class="search-each-container">
		<button title="<?php echo JText::_('COM_AXISUBS_ADVANCE_SEARCH_TITLE'); ?>" class="btn hasTooltip btn-primary search-tools-filter<?php echo $searchFilters ? ' active' : ''; ?>" type="button">
			<?php echo JText::_('COM_AXISUBS_ADVANCE_SEARCH_TEXT'); ?> <span class="caret<?php echo $searchFilters ? ' uparrow' : ''; ?>"></span>
		</button>
	</div>
	<div class="search-item-container">
		<label for="filter_date_range">
			<?php echo JText::_('COM_AXISUBS_DATE_FILTER_LABEL'); ?>
		</label>
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
	<div class="search-item-container">
		<label for="filter_start_date">
			<?php echo JText::_('COM_AXISUBS_DATE_FILTER_START_DATE_LABEL'); ?>
		</label>
		<?php echo Select::calendar('filter_start_date',
										$model->getState('filter_start_date',''),
										array('class'=>'filter_field'));?>
	</div>
	<div class="search-item-container">
		<label for="filter_end_date">
			<?php echo JText::_('COM_AXISUBS_DATE_FILTER_END_DATE_LABEL'); ?>
		</label>
		<?php echo Select::calendar('filter_end_date',
										$model->getState('filter_end_date',''),
										array('class'=>'input-small filter_field'));?>

	</div>
	<div class="search-item-container">
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
	<div class="search-item-container pull-right">
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
		jQuery('#plan_id, #status, #payment_processor').val('');
		jQuery('#filterenddate').closest("form").submit();
	}
</script>
<script>
	jQuery(document).ready(function(){
		jQuery('div.dropdown').hover(function() {
			jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(200);
		}, function() {
			jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
		});
		// for move the filters select
		jQuery("#j-sidebar-container > .filter-select").appendTo("#j-main-container > .row");
		jQuery(".search-each-container").appendTo("#filter-bar");

		// For search tool filter
		jQuery(".search-tools-filter").click(function(){
			if(jQuery(this).hasClass("active")){
				jQuery('.search-tools-more').slideToggle('slow');
				jQuery(this).removeClass("active").children('span').removeClass('uparrow');
			} else {
				jQuery('.search-tools-more').slideToggle('slow');
				jQuery(this).addClass("active").children('span').addClass('uparrow');
			}
		});
		jQuery("#filter-bar > .btn-group button").first().addClass('btn-success');
		jQuery("#filter-bar > .btn-group button").last().addClass('btn-danger');

	});
</script>
