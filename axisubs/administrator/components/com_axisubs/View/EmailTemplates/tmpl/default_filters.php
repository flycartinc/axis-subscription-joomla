<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Flycart\Axisubs\Admin\Helper\Select;
$eventSelect = Select::getTriggersList();
$eventSelect = array(''=> JText::_('COM_AXISUBS_DATE_FILTER_SELECT_EVENT'))+$eventSelect;
$model = $this->getModel();
$eventFilter = JHtml::_('select.genericlist', $eventSelect, 'filter_event', 'onchange="this.form.submit()"', 'value', 'text', $model->getState('filter_event', ''));
?>
<div class="filter_event_emailtemplate">
    <?php echo $eventFilter; ?>
</div>
<script>
    jQuery(document).ready(function(){
        // for move the filters select
        jQuery(".filter_event_emailtemplate").appendTo("#filter-bar");
    });
</script>
