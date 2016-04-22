<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Toolbar;

use FOF30\Inflector\Inflector;
use JToolBarHelper;
use JText;

defined('_JEXEC') or die;

class Toolbar extends \FOF30\Toolbar\Toolbar
{
	/**
	 * Renders the submenu (toolbar links) for all defined views of this component
	 *
	 * @return  void
	 */
	public function renderSubmenu()
	{
		$views = array(
			'Dashboard',	
			'Configuration',
			'Customers',
			'Subsribers',
			'Plans',
			'Setup'
		);

		foreach ($views as $label => $view)
		{
			if (!is_array($view))
			{
				$this->addSubmenuLink($view);
				continue;
			}

			$label = \JText::_($label);
			$this->appendLink($label, '', false);

			foreach ($view as $v)
			{
				$this->addSubmenuLink($v, $label);
			}
		}
	}

	/**
	 * Adds a link to the submenu (toolbar links)
	 *
	 * @param string $view   The view we're linking to
	 * @param array  $parent The parent view
	 */
	private function addSubmenuLink($view, $parent = null)
	{
		static $activeView = null;

		if (empty($activeView))
		{
			$activeView = $this->container->input->getCmd('view', 'cpanel');
		}

		if ($activeView == 'cpanels')
		{
			$activeView = 'cpanel';
		}

		$key = $this->container->componentName . '_TITLE_' . $view;

		// Exceptions to avoid introduction of a new language string
		if ($view == 'Dashboard')
		{
			$key = $this->container->componentName . '_TITLE_CPANEL';
		}

		if (strtoupper(\JText::_($key)) == strtoupper($key))
		{
			$altView = $this->container->inflector->isPlural($view) ? $this->container->inflector->singularize($view) : $this->container->inflector->pluralize($view);
			$key2    = strtoupper($this->container->componentName) . '_TITLE_' . strtoupper($altView);

			if (strtoupper(\JText::_($key2)) == $key2)
			{
				$name = ucfirst($view);
			}
			else
			{
				$name = \JText::_($key2);
			}
		}
		else
		{
			$name = \JText::_($key);
		}

		$link = 'index.php?option=' . $this->container->componentName . '&view=' . $view;

		$active = $view == $activeView;

		$this->appendLink($name, $link, $active, null, $parent);
	}

	/**
	 * Add a custom toolbar button
	 *
	 * @param string $id      The button ID
	 * @param array  $options Button options
	 */
	protected function addCustomBtn($id, $options = array())
	{
		$options = (array) $options;
		$a_class = 'btn btn-small';
		$href    = '';
		$task    = '';
		$text    = '';
		$rel     = '';
		$target  = '';
		$other   = '';

		if (isset($options['a.class']))
		{
			$a_class .= $options['a.class'];
		}
		if (isset($options['a.href']))
		{
			$href = $options['a.href'];
		}
		if (isset($options['a.task']))
		{
			$task = $options['a.task'];
		}
		if (isset($options['a.target']))
		{
			$target = $options['a.target'];
		}
		if (isset($options['a.other']))
		{
			$other = $options['a.other'];
		}
		if (isset($options['text']))
		{
			$text = $options['text'];
		}
		if (isset($options['class']))
		{
			$class = $options['class'];
		}
		else
		{
			$class = 'default';
		}

		if (isset($options['modal']))
		{
			\JHtml::_('behavior.modal');
			$a_class .= ' modal';
			$rel = "'handler':'iframe'";
			if (is_array($options['modal']))
			{
				if (isset($options['modal']['size']['x']) && isset($options['modal']['size']['y']))
				{
					$rel .= ", 'size' : {'x' : " . $options['modal']['size']['x'] . ", 'y' : " . $options['modal']['size']['y'] . "}";
				}
			}
		}

		$html = '<a id="' . $id . '" class="' . $a_class . '" alt="' . $text . '"';

		if ($rel)
		{
			$html .= ' rel="{' . $rel . '}"';
		}
		if ($href)
		{
			$html .= ' href="' . $href . '"';
		}
		if ($task)
		{
			$html .= " onclick=\"javascript:submitbutton('" . $task . "')\"";
		}
		if ($target)
		{
			$html .= ' target="' . $target . '"';
		}
		if ($other)
		{
			$html .= ' ' . $other;
		}
		$html .= ' >';

		$html .= '<span class="icon icon-' . $class . '" title="' . $text . '" > </span>';

		$html .= $text;

		$html .= '</a>';

		$bar = \JToolBar::getInstance();
		$bar->appendButton('Custom', $html, $id);
	}

	public function hideToolbar()
	{
		$doc = \JFactory::getDocument();
		//$doc->addScriptDeclaration('jQuery(".subhead").parent().remove();');
		echo '<script>jQuery(".subhead").parent().remove();</script>';
	}

	public function onConfigurationsAdd(){
		$option = $this->container->componentName;
	 	$subtitle_key = $option . '_TITLE_CONFIGURATIONS_EDIT';
	 	JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
	 	str_replace('com_', '', $option));
	 	JToolbarHelper::apply('apply', 'JTOOLBAR_APPLY');
		JToolbarHelper::save('save', 'JTOOLBAR_SAVE');
		JToolbarHelper::cancel('cancel', 'JTOOLBAR_CANCEL');
	} 

	public function onDashboards(){
	 	$option = $this->container->componentName;
	 	$subtitle_key = $option . '_DASHBOARD';
	 	JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
	 	str_replace('com_', '', $option));
	 	$this->hideToolbar();
	}

	public function onPlansBrowse(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_PLANS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		JToolbarHelper::addNew('add', "JTOOLBAR_NEW", false);
		JToolbarHelper::editList('edit', 'JTOOLBAR_EDIT');
		JToolbarHelper::publish('publish', 'JTOOLBAR_PUBLISH');
		JToolbarHelper::unpublish('unpublish', 'JTOOLBAR_UNPUBLISH');
		JToolbarHelper::deleteList('', 'remove', 'JTOOLBAR_DELETE');
	}

	public function onPlansRead(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_PLANS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		$this->addCustomBtn('listsubs',array( 'class'=>'list-view ','a.href'=>'index.php?option=com_axisubs&view=Plans', 'text'=>'List'));
	}

	public function onSubscriptionsRead(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_SUBSCRIPTIONS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		$this->addCustomBtn('listsubs',array( 'class'=>'list-view ','a.href'=>'index.php?option=com_axisubs&view=Subscriptions', 'text'=>'List'));
	}

	public function onSubscriptionInfosEdit(){
		$option = $this->container->componentName;
	 	$subtitle_key = $option . '_TITLE_SUBSCRIPTIONINFOS_EDIT';
	 	JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
	 	str_replace('com_', '', $option));
		JToolbarHelper::save('save', 'JTOOLBAR_SAVE');
		JToolbarHelper::cancel('cancel', 'JTOOLBAR_CANCEL');
	}

	public function onApps(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_APPS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
	}

	public function onReports(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_REPORTS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		$this->hideToolbar();
	}

	public function onSetups(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_SETUP';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		$this->hideToolbar();
	}

	public function onPayments(){
		$option = $this->container->componentName;
		$subtitle_key = $option . '_TITLE_PAYMENTS';
		JToolbarHelper::title(JText::_($option) . ' &ndash; <small>' . JText::_($subtitle_key) . '</small>',
		str_replace('com_', '', $option));
		$this->hideToolbar();
	}

}