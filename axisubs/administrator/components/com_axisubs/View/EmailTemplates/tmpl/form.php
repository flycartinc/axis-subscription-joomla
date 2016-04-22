<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// No direct access
defined ( '_JEXEC' ) or die ();
use Flycart\Axisubs\Admin\Helper\AxisHtml;
use Flycart\Axisubs\Admin\Helper\Select;
$viewTemplate = $this->getRenderedForm();
$fieldsets = $this->form->getFieldsets();
$mailcontents = $this->item->getEmailContent();
$emailtemplate_id = $this->item->axisubs_emailtemplate_id;
?>
<div class="axisubs-bs3">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" 
		name="adminForm" id="adminForm" class="form-horizontal form-validate">
		<input type="hidden" value="com_axisubs" name="option">
		<input type="hidden" value="EmailTemplates" name="view">
		<input type="hidden" value="" name="task">
		<?php echo JHtml::_('form.token'); ?>
		<h4><?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_FILTER_SETTINGS'); ?></h4>
		<div class="row">
		<?php foreach ($fieldsets as $fieldset) : ?>
			<div id="<?php echo $fieldset->name; ?>" class="<?php echo $fieldset->class; ?>" >
			<h3><?php echo JText::_('COM_AXISUBS_USER_BASIC_TITLE'); ?></h3>
			<?php 
				$fields = $this->form->getFieldset($fieldset->name);
				foreach ($fields as $field) : ?>
				<?php if ($field->name == 'recipientshortcodes') { ?>
					<?php echo Select::recipientShortCodes('recipientshortcodes'); ?>
				<?php } else{ ?>
				<div class="control-group" >
		    		<div class="control-label"><?php echo JText::_($field->label); ?></div>
		    		<div class="controls"><?php echo $field->input; ?></div>
		 		</div>
				<?php } ?>
			<?php endforeach;?>
			</div>
		<?php endforeach; ?>
		</div>
		<h4><?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_TAB_CONTENT_TAB'); ?></h4>
		<div class="row">
			<div class="col-md-12">

				<?php if ( count($mailcontents) > 0 ): ?>
				<!-- Tab head -->
				<ul class="nav nav-tabs bordcolor">
				<?php foreach ($mailcontents as $lang_id => $content): 
					$active_class = '';
					if ( $content->is_default == 1 ){
						$active_class = 'active ';
					}
					?>
					<li class="<?php echo $active_class; ?> ">
			            <a data-toggle="tab" href="#mailcontent<?php echo $lang_id; ?>">
			                <img src="<?php echo JURI::root(); ?>/media/mod_languages/images/<?php echo $content->language->image;?>.gif" alt="">
			                <?php echo $content->language->title_native; ?> 
			                <span class="muted">
			                	[ <?php echo $content->language->lang_code; ?> ]
			                </span>
			            </a>
			        </li>	
				<?php endforeach ?>	
				</ul>
				<!-- end Tab head -->
				<!-- Tab body -->
				<div class="tab-content">
				<?php foreach ($mailcontents as $lang_id => $content): 
					$field_name_prefix = 'emailcontent['.$lang_id.']';
					$active_class = '';
					if ( $content->is_default == 1 ){
						$active_class = ' in active ';
					}
					?>
					<?php echo AxisHtml::hidden($field_name_prefix.'[language_id]',$lang_id); ?>
					<?php echo AxisHtml::hidden($field_name_prefix.'[emailtemplate_id]',$emailtemplate_id); ?>
					 <div id="mailcontent<?php echo $lang_id; ?>" class="tab-pane fade <?php echo $active_class; ?>">
						<table class="table bordered">
							<tr>
								<td>
									<?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_SUBJECT'); ?>
								</td>
								<td>
									<?php 
									$field_val = '';
									if (isset($content->fields['subject']) 
											&& isset( $content->fields['subject']->content) ){
										$field_val = $content->fields['subject']->content;
									}
									echo AxisHtml::text( $field_name_prefix.'[subject]',
																$field_val ); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
								<div class="row">
									<div class="col-md-9">
										<?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_BODY_HTML'); ?>
										<br>
										<?php 
										$field_val = '';
										if (isset($content->fields['body_html']) 
												&& isset( $content->fields['body_html']->content) ){
											$field_val = $content->fields['body_html']->content;
										}
										echo AxisHtml::editor( $field_name_prefix.'[body_html]',
																	$field_val ); ?>	
									</div>
									<div class="col-md-2">
										<br><br>
										<a class="btn btn-success" onclick="insertShortCode('editor_emailcontent\\[1\]\\\[body_html\\]',jQuery('#shortcode<?php echo $lang_id; ?>').attr('value'));">
											<i class="icon-arrow-left"></i>
										</a>
										<?php  echo Select::shortcodes('shortcode'.$lang_id); ?>
									</div>
									<div class="col-md-1"></div>
								</div>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo JText::_('COM_AXISUBS_EMAILTEMPLATES_BODY_PLAIN'); ?>
								</td>
								<td>
									<?php 
									$field_val = '';
									if (isset($content->fields['body_plain']) 
											&& isset( $content->fields['body_plain']->content) ){
										$field_val = $content->fields['body_plain']->content;
									}
									echo AxisHtml::textarea( $field_name_prefix.'[body_plain]',
																$field_val ); ?>
								</td>
							</tr>
						</table>
					</div>
				<?php endforeach ?>	
				</div>
				<!-- end Tab body -->
				<?php endif ?>
			</div>			
		</div>
	</form>
</div>
<style>
<?php foreach ($mailcontents as $lang_id => $content): ?>
#shortcode<?php echo $lang_id; ?>_chzn{	display: none;	}
#shortcode<?php echo $lang_id; ?>{	   display: inline !important;	}
<?php endforeach; ?>
#recipientshortcodes_chzn{	display: none;	}
#recipientshortcodes{	   display: inline !important;	}
</style>
<script type="text/javascript">
function insertShortCode(editor, value) {
	(function($){
	 var editor = $("#"+editor);
	 jInsertEditorText(value,editor);
    })(jQuery.noConflict());
}

function insertRecipientShortCode(textarea, value) {
	(function($){
	 var t = $("#"+textarea);
	 t.val( t.val() + value + "," );
    })(jQuery.noConflict());
}

(function($) {
	$(document).ready(function(){ 

		<?php foreach ($mailcontents as $lang_id => $content): ?>
		var divdbl = $( "#shortcode<?php echo $lang_id; ?> > optgroup > option" );
		divdbl.dblclick(function() {
			insertShortCode('something',jQuery('#shortcode<?php echo $lang_id; ?>').attr('value'));
		});
		<?php endforeach; ?>

		var divdbl = $( "#recipientshortcodes > option" );
		divdbl.dblclick(function() {
			insertRecipientShortCode('recipients', jQuery('#recipientshortcodes').attr('value') );
		});
	});	
})(jQuery.noConflict());
</script>