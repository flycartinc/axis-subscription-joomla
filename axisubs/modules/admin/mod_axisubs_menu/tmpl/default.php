<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'media/com_axisubs/css/font-awesome.min.css');
$icons = array (
		'COM_AXISUBS_MAINMENU_DASHBOARD' => 'fa fa-th-large',
		'COM_AXISUBS_MAINMENU_CUSTOMERS' => 'fa fa-user',
		'COM_AXISUBS_MAINMENU_SUBSCRIPTIONS' => 'fa fa-rss',
		'COM_AXISUBS_MAINMENU_PLANS' => 'fa fa-file-powerpoint-o',
		'COM_AXISUBS_MAINMENU_REPORTS' => 'fa fa-sticky-note',
		'COM_AXISUBS_MAINMENU_APPS' => 'fa fa-th',
		'COM_AXISUBS_MAINMENU_SETUP' => 'fa fa-cubes',
		'Payments' => 'fa fa-money',
		'EmailTemplates' => 'fa fa-envelope',
		'Taxes' => 'fa fa-newspaper-o',
		'Currencies' => 'fa fa-money',
		'Configuration' => 'fa fa-cog'
);


$menus =  array (
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


?>
<ul id="menu" class="nav">
	<li class="dropdown" >
		<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('COM_AXISUBS');?><span class="caret"></span></a>
			<ul aria-labelledby="dropdownMenu" role="menu" class="dropdown-menu">
			<?php foreach($menus as $key => $value):?>
                  <?php if(isset($value['submenu']) && count($value['submenu'])):?>
                  <li class="dropdown-submenu">
                    <a href="#" tabindex="-1">
                    	<i class="<?php echo isset($value['icon']) ? $value['icon'] : '';?>"></i>
                    	<span class="submenu-title"><?php echo $value['name'];?></span>
                    </a>
                    <ul class="dropdown-menu">

                    <!-- Here starts Submenu -->
                     <?php foreach($value['submenu'] as $key => $value): ?>
                      	<li>
                      		<a href="<?php echo 'index.php?option=com_AXISUBS&view='.strtolower($key);?>"  tabindex="-1">
                      			<i class="<?php echo !empty($value) ? $value: '';?>"></i>
                      			<span>
	                           		<?php echo JText::_('COM_AXISUBS_TITLE_'.JString::strtoupper($key));?>
	                           	</span>
	                         </a>
	                       </li>
                         <?php endforeach;?>
                    </ul>
                  </li>
                 <?php else:?>
                  <li>
						<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo 'index.php?option=com_axisubs&view='.$value['viewname']; ?>">							
							<i class="<?php echo isset($value['icon']) ? $value['icon'] : '';?>"></i>
                  			<span class="submenu-title"><?php echo $value['name'];?></span>
						</a>
					</li>
                <?php endif; ?>
               <?php endforeach;?>
			</ul>
	</li>
</ul>