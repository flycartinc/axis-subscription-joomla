<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;
JHtml::_('behavior.framework');
JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
  Joomla.submitbutton = function(pressbutton) {
		if(pressbutton == 'save' || pressbutton == 'apply') {
			document.adminForm.task ='view';
			document.getElementById('appTask').value = pressbutton;
		}

		if(pressbutton == 'cancel') {
			Joomla.submitform('cancel');
		}

		var atask = jQuery('#appTask').val();

		Joomla.submitform('view');
  }
</script>

<form class="form-horizontal form-validate" id="adminForm" 	name="adminForm" method="post" action="index.php">
	<input type="hidden" name="option" value="com_axisubs" >
	<input type="hidden" name="view" value="Apps" >
	<input type="hidden" name="task" value="view" id="task" >
	<input type="hidden" name="appTask" value="" id="appTask" >
	<input type="hidden" name="table" value="" id="table" >
	<input type="hidden" name="id" value="<?php echo $vars->id; ?>" id="id" >
	<input type="hidden" name="app_id" value="<?php echo $vars->id; ?>" id="app_id" >
	<?php echo JHTML::_( 'form.token' ); ?>
	
<!-- Render form -->

<?php
        $fieldsets = $vars->form->getFieldsets();
        $shortcode = $vars->form->getValue('text');
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
	                    $fields = $vars->form->getFieldset($attr->name);
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
<!-- end Render form -->
</form>