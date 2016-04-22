<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>

<?php echo $this->getRenderedForm(); ?>