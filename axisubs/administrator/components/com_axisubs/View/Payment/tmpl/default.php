<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// load tooltip behavior
\JHtml::_('behavior.framework');
\JHtml::_('behavior.modal');
\JHtml::_('behavior.tooltip');
\JHtml::_('behavior.multiselect');
\JHtml::_('dropdown.init');
\JHtml::_('formbehavior.chosen', 'select');

$sortFields = array(
		'id'				=> JText::_('JGRID_HEADING_ID'),
		'name' 			=> JText::_('COM_ATS_TICKETS_HEADING_TITLE'),
		'state' 			=> JText::_('JSTATUS'),
);


$sidebar = \JHtmlSidebar::render();
$model = $this->getModel();

$total = count($model->get('items')); $counter = 0;
$col = 4;
?>
<script type="text/javascript">
		Joomla.orderTable = function() {
			table = document.getElementById("sortTable");
			direction = document.getElementById("directionTable");
			order = table.options[table.selectedIndex].value;
			if (order != '$order')
			{
				dirn = 'asc';
			}
			else {
				dirn = direction.options[direction.selectedIndex].value;
			}
			Joomla.tableOrdering(order, dirn);
		}
</script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
<form action="<?php echo JRoute::_('index.php?option=com_axisubs&view=Payments'); ?>" method="post" name="adminForm"
	  id="adminForm" xmlns="http://www.w3.org/1999/html">

	<input type="hidden" name="task" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $model->getState('order',''); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $model->getState('order_Dir',''); ?>" />
	<input type="hidden" id="token" name="<?php echo \JFactory::getSession()->getFormToken(); ?>" value="1" />

 <?php if(!empty( $sidebar )): ?>
   <div id="j-sidebar-container" class="span2">
      <?php echo $sidebar ; ?>
   </div>
   <div id="j-main-container" class="span10">
     <?php else : ?>
     <div id="j-main-container">
    <?php endif;?>

<div class="axisubs apps">

	<div class="app-search">
				<input type="text" name="search" id="search"
				value="<?php echo $this->escape($this->getModel()->getState('search',''));?>"
				class="input-large" onchange="document.adminForm.submit();"
				placeholder="<?php echo JText::_('AXISUBS_APP_NAME'); ?>"
				/>
				<nobr>
				<button class="btn btn-success" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button class="btn btn-info" type="button" onclick="document.id('search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</nobr>

	</div>

	<h2 class="app-heading"><?php echo JText::_('COM_AXISUBS_TITLE_PAYMENT_APPS')?></h2>

	<?php $i = -1 ?>
	<?php foreach($model->get('items') as $i =>$app): ?>
	<?php
		$i++;
		$app->published = $app->enabled;
		//load the language files
		\JFactory::getLanguage()->load('plg_axisubs_'.$app->element, JPATH_ADMINISTRATOR);
	//var_dump($app->manifest_cache);
		$params = new JRegistry;
		$params->loadString($app->manifest_cache);
	?>
	<?php $rowcount = ((int) $counter % (int) $col) + 1; ?>
		<?php if ($rowcount == 1) : ?>
			<?php $row = $counter / $col; ?>
			<div class="axisubs-apps-row <?php echo 'row-'.$row; ?> row-fluid">
		<?php endif;?>
		<div class="span<?php echo round((12 / $col));?>">
			<div class="app-container">
				<div class="panel panel-warning">
					<div class="panel-body">
					<div class="app-image">
						<?php if(JFile::exists(JPATH_SITE.'/plugins/axisubs/'.$app->element.'/images/'.$app->element.'.png')):?>
							<img src="<?php echo JUri::root(true).'/plugins/axisubs/'.$app->element.'/images/'.$app->element.'.png'; ?>" />
						<?php elseif(JFile::exists(JPATH_SITE.'/media/axisubs/images/'.$app->element.'.png')): ?>
							<img src="<?php echo JUri::root(true).'/media/axisubs/images/'.$app->element.'.png'; ?>" />
						<?php else: ?>
							<img src="<?php echo JUri::root(true).'/media/axisubs/images/app_placeholder.png'; ?>" />
						<?php endif;?>
					</div>

					<div class="app-name">
	                     <h3 class="panel-title"><?php echo JText::_($app->name); ?></h3>
	                 </div>

					<div class="app-description">
						<?php
						$desc = $params->get('description');
						echo JString::substr(JText::_($desc), 0, 100).'...';
						?>
					</div>
					<div class="app-footer">
						<span class="author">
							<?php echo $params->get('author'); ?>
						</span>

						<span class="version pull-right"><strong><?php echo JText::_('AXISUBS_APP_VERSION'); ?> : <?php echo $params->get('version'); ?></strong></span>
					</div>
					</div>
					<div class="panel-footer">
						<div class="app-action">
							<?php if($app->enabled): ?>
							<a
							class="app-button app-button-open j2-flat-button"
							href="<?php echo 'index.php?option=com_axisubs&view=apps&return=Payments&task=view&layout=view&id='.$app->extension_id?>" >
							<?php echo JText::_('AXISUBS_OPEN'); ?>
							<i class="fa fa-arrow-circle-right"></i>
							</a>
							<?php endif; ?>

							<?php if($app->enabled): ?>
								<a
								class="app-button app-button-unpublish j2-flat-button"
								href="<?php echo 'index.php?option=com_axisubs&view=apps&return=Payments&task=unpublish&id='.$app->extension_id.'&'.\JFactory::getSession()->getFormToken().'=1'; ?>" >
									<?php echo JText::_('AXISUBS_DISABLE'); ?>
								</a>
							<?php else: ?>
							<a
							class="app-button app-button-publish j2-flat-button"
							href="<?php echo 'index.php?option=com_axisubs&view=apps&return=Payments&task=publish&id='.$app->extension_id.'&'.\JFactory::getSession()->getFormToken().'=1'; ?>" >
								<?php echo JText::_('AXISUBS_ENABLE'); ?>
							</a>
							<?php endif;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $counter++; ?>
		<?php if (($rowcount == $col) or ($counter == $total)) : ?>
			</div>
		<?php endif; ?>
		<?php endforeach; ?>
		<?php //  echo $this->pagination->getPagesLinks(); ?>
		<div class="pagination">
				<?php  echo $model->get('pagination')->getListFooter(); ?>
		</div>
	</div>
	</form>
</div>
</div>

<style>
.btn-toolbar > .btn-group{
margin-left: 14px !important;
margin-bottom:10px;
}
</style>