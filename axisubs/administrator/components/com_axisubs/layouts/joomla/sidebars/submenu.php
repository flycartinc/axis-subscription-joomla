<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die;

$app = \JFactory::getApplication() ;
$menus = array (
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_DASHBOARD' ),
				'viewname' => 'Dashboard',
				'icon' => 'fa fa-th-large'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_CUSTOMERS' ),
				'viewname' => 'Customers',
				'icon' => 'fa fa-user'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_SUBSCRIPTIONS' ),
				'viewname' => 'Subscriptions',
				'icon' => 'fa fa-rss'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_PLANS' ),
				'viewname' => 'Plans',
				'icon' => 'fa fa-file-powerpoint-o'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_REPORTS' ),
				'viewname' => 'Reports',
				'icon' => 'fa fa-sticky-note'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_APPS' ),
				'viewname' => 'Apps',
				'icon' => 'fa fa-th'
		),
		array (
				'name' => JText::_ ( 'COM_AXISUBS_MAINMENU_SETUP' ),
				'viewname' => 'Setup',
				'icon' => 'fa fa-cubes',
				'submenu' => array (
						'Payments' => 'fa fa-money',
						'EmailTemplates' => 'fa fa-envelope',
						'Taxes' => 'fa fa-newspaper-o',
						'Currencies' => 'fa fa-money',
						'Configuration' => 'fa fa-cog'
				)
		)
	);

// Get installed version
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->quoteName('manifest_cache'))->from($db->quoteName('#__extensions'))
->where($db->quoteName('element').' = '.$db->quote('com_axisubs'))
->where($db->quoteName('type').' = '.$db->quote('component'));
$db->setQuery($query);
$version = json_decode($db->loadResult());
$view = $app->input->get('view');
?>

<!-- axisubs sidebar navigation begins -->
<nav role="navigation" class="navbar-default ">
    <div class="sidebar-collapse">
        <ul id="side-menu" class="nav-pills">
            <li class="nav-header">
                <h2 class="menud"> <?php echo JText::_('COM_AXISUBS'); ?> </h2><?php echo  "V ".$version->version; ?>
            </li>
            <li class="">
             <ul class="nav navbar-nav ">
				 <li class="dropdown">
            <?php foreach($menus as $k => $menu_item) { 
				//~ echo $view;
				// check if the menu is currenct view
					
				$class_is_active='';
				if(isset($menu_item['viewname']) && !empty($view))
					$class_is_active = ($view==$menu_item['viewname'])?'active':'';
					$has_submenu = '';
					if (isset($menu_item['submenu']) && count($menu_item['submenu']) > 0 )
					{
						$has_submenu = 'dropdow';
					}
					
				?>
					<li class="<?php echo $class_is_active; ?> <?php echo $has_submenu; ?> "> 
                    <a  class="admin-menu-item" class="dropdown-toggle" data-toggle="dropdown"
						<?php if(isset($menu_item['viewname'])&&!empty($menu_item['viewname'])) { ?>
						href="<?php echo 'index.php?option=com_axisubs&view='.$menu_item['viewname']; ?>"
						<?php } //endif ?>
                     >  <div class="menud1">
						<i class="<?php echo $menu_item['icon']; ?>"></i> 
						<span class="nav-label ">
						<?php echo $menu_item['name']; ?> 
						</span></div>
                    </a>
                   
                   <!-- submenu for the group goes here --> 
                   <?php if(isset($menu_item['submenu'])) { ?>
				
						
					<ul class="dropdown-menu ">
					    <ul class="nav nav-second-level collapse in admin-submenu-item">
							
					<?php	 foreach($menu_item['submenu'] as $sub_vname => $sub_icon) {
			                    $class_is_active='';
								if(!empty($view))
									$class_is_active = ($view==$sub_vname)?'active':'';
			            ?>
			           <li class="<?php echo $class_is_active; ?>">
								<a  href="<?php echo 'index.php?option=com_axisubs&view='.$sub_vname; ?>">
										<i class="<?php echo $sub_icon; ?>"></i> 
										<span class="nav-label">
											<?php echo JText::_('COM_AXISUBS_MAINMENU_'.strtoupper($sub_vname)); ?>
										</span>
								</a>
						</li>
			       <?php 	} // end for submenu ?>
			            </ul> </ul>
				  <?php	 } //end if submenu ?>            
                </li>
			<?php	} // end foreach $menus ?>
        </ul>
        </li>
        </ul>
    </div>
</nav>

<!-- joomla searchbar begins -->
<?php  if ($displayData->displayMenu && $displayData->displayFilters) : ?>
<?php endif; ?>
<?php if ($displayData->displayFilters) : ?>
<div class="filter-select hidden-phone">
<?php foreach ($displayData->filters as $filter) : ?>
	<label for="<?php echo $filter['name']; ?>"
	class="element-invisible"><?php echo $filter['label']; ?></label> 
	<select name="<?php echo $filter['name']; ?>"
	id="<?php echo $filter['name']; ?>" class="span12 small"
	onchange="this.form.submit()">
		<?php if (!$filter['noDefault']) : ?>
			<option value=""><?php echo $filter['label']; ?></option>
		<?php endif; ?>
		<?php echo $filter['options']; ?>

	</select>
<?php endforeach; ?>
</div>
<?php endif; ?>
<!-- end joomla searchbar -->
<script type="text/javascript">
	jQuery(document).ready(function(){
			var li = jQuery(".submenu-list").html();
			jQuery('.submenu-list li').each(function(i ,value)
				{
				  if(jQuery(value).attr('class') =='active'){
					  var main_menu = jQuery( value ).parent().addClass('in');
				  }

				});
		});

	jQuery(document).ready(function() {
		jQuery("#akeeba-renderjoomla").addClass('axisubs-bs3');
		jQuery("#j-main-container").attr("class", 'axisubs-bs3');
		jQuery("#j-sidebar-container").attr("class", 'col-md-12');
	});

jQuery(document).ready(function(){
	jQuery('ul.nav  li.dropdow').hover(function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(300);
	}, function() {
	  jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
	});
});
</script>