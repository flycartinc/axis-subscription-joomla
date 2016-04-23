<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/App.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\App;

class plgAxisubsApp_JUsergroup extends App
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_jusergroup';

	/**
	 * Public constructor
	 *
	 * @param object $subject
	 * @param array  $config
	 */
	public function __construct(& $subject, $config = array())
	{
		$config['templatePath'] = dirname(__FILE__);
		$config['name']         = 'joomla';

		parent::__construct($subject, $config);
	}

	/**
	 * Method to integrate the plan form 
	 * */
	function onAxisubsPlanAfterFormRender($plan){
        $vars = new JObject();
        $vars->plan = $plan ;
        $vars->title='Joomla User Group';
        $vars->html = $this->_getLayout('form', $vars);

        return $vars;
    }

	/**
	 * Called whenever the administrator asks to refresh integration status.
	 *
	 * @param   int $user_id The Joomla! user ID to refresh information for.
	 *
	 * @return  void
	 */
	public function onAxisubsUserRefresh($user_id)
	{
		// Load groups
		$addGroups    = array();
		$removeGroups = array();
		$this->loadUserGroups($user_id, $addGroups, $removeGroups);

		if (empty($addGroups) && empty($removeGroups))
		{
			return;
		}

		// Get DB connection
		$db = JFactory::getDBO();

		// Add to Joomla! groups
		if (!empty($addGroups))
		{
			// 1. Delete existing assignments
			$groupSet = array();

			foreach ($addGroups as $group)
			{
				$groupSet[] = $db->q($group);
			}

			$query = $db->getQuery(true)
			            ->delete($db->qn('#__user_usergroup_map'))
			            ->where($db->qn('user_id') . ' = ' . $user_id)
			            ->where($db->qn('group_id') . ' IN (' . implode(', ', $groupSet) . ')');

			$db->setQuery($query);
			$db->execute();

			// 2. Add new assignments
			$query = $db->getQuery(true)
			            ->insert($db->qn('#__user_usergroup_map'))
			            ->columns(array(
				            $db->qn('user_id'),
				            $db->qn('group_id'),
			            ));

			foreach ($addGroups as $group)
			{
				$query->values($db->q($user_id) . ', ' . $db->q($group));
			}

			$db->setQuery($query);
			$db->execute();
		}

		// Remove from Joomla! groups
		if (!empty($removeGroups))
		{
			$query    = $db->getQuery(true)
			               ->delete($db->qn('#__user_usergroup_map'))
			               ->where($db->qn('user_id') . ' = ' . $db->q($user_id));

			$groupSet = array();

			foreach ($removeGroups as $group)
			{
				$groupSet[] = $db->q($group);
			}

			$query->where($db->qn('group_id') . ' IN (' . implode(', ', $groupSet) . ')');
			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * Used by the template to render selection fields
	 *
	 * @param   \Flycart\Axisubs\Admin\Model\Plans  	  $plan  Plan object
	 * @param   string                                    $type  add or remove
	 *
	 * @return  string  The HTML for the drop-down field
	 */
	protected function getSelectField(\Flycart\Axisubs\Admin\Model\Plans $plan, $type)
	{
		if (!in_array($type, ['add', 'remove']))
		{
			return '';
		}

		$key = "jusergroup_{$type}groups";

		if (isset($plan->params[$key]))
		{
			$groupList = $plan->params[$key];
		}
		else
		{
			$groupList = array();
		}

		return JHtml::_('access.usergroup', "params[$key][]", $groupList, array(
			'multiple' => 'multiple',
			'size'     => 8,
			'class'    => 'input-large'
		), false);
	}
}