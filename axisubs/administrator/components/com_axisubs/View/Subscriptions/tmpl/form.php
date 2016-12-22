<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

use Flycart\Axisubs\Admin\Helper\Select;

\JHtml::_('behavior.tooltip');
\JHtml::_('behavior.modal');
$fieldsets = $this->form->getFieldsets();
?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" 
		name="adminForm" id="adminForm" class="form-horizontal form-validate">
	<input type="hidden" value="com_axisubs" name="option">
	<input type="hidden" value="Subscription" name="view">
	<input type="hidden" value="" name="task">
	<?php echo JHtml::_('form.token'); ?>
	<div class="row">
		<div class="col-md-4 well ">
			<h4> <?php echo JText::_('COM_AXISUBS_SUBSCRIPTION_CUSTOMER_DETAILS');?> </h4>
			<table class="table">
				<tr>
					<td>
						<?php echo JText::_('COM_AXISUBS_CUSTOMERSS_NAME');?>
					</td>
					<td>
						<?php echo $this->customer->first_name . ' '.$this->customer->last_name ; ?> 
						<?php if (! empty($this->customer->company) ){ ?>
							<br> <span class="muted">
								[ <?php echo $this->customer->company; ?> ]
							</span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('COM_AXISUBS_CUSTOMER_EMAIL');?> 
					</td>
					<td>
						<?php echo $this->customer->email ; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
	<?php foreach ($fieldsets as $fieldset) : ?>
			<div id="<?php echo $fieldset->name; ?>" class="<?php echo $fieldset->class; ?>" >
			<h3><?php echo JText::_($fieldset->label); ?></h3>
			<?php 
				$fields = $this->form->getFieldset($fieldset->name);
				foreach ($fields as $field) :
					$prependText = $appendText = $wrapperClass = '' ;
					$prependText = $field->getAttribute('prepend_text');	
	    			$appendText = $field->getAttribute('append_text');	
	    		
		    		if ($prependText || $appendText)
					{
						$wrapperClass = $prependText ? 'input-prepend' : '';
						$wrapperClass .= $appendText ? 'input-append' : '';
					}
				?>
				<?php if ($field->name == 'subscription_dates'){ ?>
					<div class="control-group" >
						<div class="control-label"> <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_STARTS_ON');?> </div>
						<div class="controls"> <?php echo $this->item->trial_start; ?> </div>
					</div>
					<div class="control-group" >
						<div class="control-label"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_TRAIL_ENDS_ON');?></div>
						<div class="controls"> <?php echo $this->item->trial_end; ?> </div>
					</div>
					<div class="control-group" >
						<div class="control-label"><?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_STARTS_ON');?></div>
						<div class="controls"> <?php echo $this->item->current_term_start; ?> </div>
					</div>
					<div class="control-group" >
						<div class="control-label">  <?php echo JText::_('COM_AXISUBS_SUBSCRIBE_CURRENT_TERM_ENDS_ON');?></div>
						<div class="controls"> <?php echo $this->item->current_term_end; ?> </div>
					</div>
				<?php }else { ?>
					<div class="control-group" >
			    		<div class="control-label"><?php echo $field->label; ?></div>
			    		<div class="controls">
			    			<?php if (!empty($wrapperClass)) : ?>
			    				<div class="<?php echo $wrapperClass; ?>">
			    			<?php endif; ?>
			    			<?php if (!empty($prependText)) : ?>
			    				<span class="add-on"><?php echo $prependText; ?></span>
			    			<?php endif; ?>
			    			
			    			<?php echo $field->input; ?>

							<?php if (!empty($appendText)) : ?>
			    				<span class="add-on"><?php echo $appendText; ?></span>
			    			<?php endif; ?>
			    			<?php if (!empty($wrapperClass)) : ?>
			    				</div>
			    			<?php endif; ?>
			    			<span class="muted"><?php echo JText::_($field->description); ?></span>
			    		</div>
			 		</div>
			 	<?php } ?>	
			<?php endforeach;?>
			</div>
		<?php endforeach; ?>
	</div>	

<?php if ( isset( $this->customer ) ) : 
	$customer = $this->customer ; ?>
	<input type="hidden" name="user_id" value="<?php echo $this->customer->user_id; ?>" />
<?php endif; ?>
	<input type="hidden" name="axisubs_subscription_id" value="<?php echo $this->item->axisubs_subscription_id; ?>" />

</form>