<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();
use Flycart\Axisubs\Admin\Helper\Axisubs;
\JHtml::_('behavior.tooltip');
\JHtml::_('behavior.modal');
$doc = \JFactory::getDocument();
$model = $this->getModel();
$all_plugin_fields = Axisubs::plugin()->event('PlanAfterFormRender', array($model) );
?>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#tab0" data-toggle="tab" >
				<?php echo JText::_('COM_AXISUBS_PLAN_SETTINGS'); ?>
			</a>
		</li>
		<?php $n = 1;
		if (is_array($all_plugin_fields) && !empty($all_plugin_fields)):
		foreach ($all_plugin_fields as $plugin_fields): $n++; ?>
			<li >
				<a href="#tab<?php echo $n ?>" data-toggle="tab"><?php echo $plugin_fields->title ?></a>
			</li>
		<?php endforeach; 
		endif;?>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab0">
			<?php echo $this->getRenderedForm(); ?>	
		</div>			
		<?php $n = 1;
		if (is_array($all_plugin_fields) && !empty($all_plugin_fields)):
		foreach ($all_plugin_fields as $plugin_fields): $n++; ?>
			<div class="tab-pane " id="tab<?php echo $n ?>">
				<?php echo $plugin_fields->html ?>
			</div>
		<?php endforeach; 
		endif;?>
	</div>
</div>

<script type="text/javascript">
jQuery('#plantitle').change(function() {
      var title = jQuery(this).val();
      //alert(title); 
      jQuery("#slug").val(name_to_url(title));
    });

function name_to_url(name) {
    name = name.toLowerCase(); // lowercase
    name = name.replace(/^s+|s+$/g, '-'); // remove leading and trailing whitespaces
    name = name.replace(/s+/g, '-'); // convert (continuous) whitespaces to one -
    name = name.replace(/[^a-z-]/g, ''); // remove everything that is not [a-z] or -
    return name;
}
</script>