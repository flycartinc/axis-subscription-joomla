<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

\JHtml::_('behavior.tooltip');
\JHtml::_('behavior.modal');
$doc = \JFactory::getDocument();
?>
<?php echo $this->getRenderedForm(); ?>
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