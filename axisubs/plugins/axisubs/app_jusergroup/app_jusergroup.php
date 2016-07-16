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
use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Model\Subscriptions;

class plgAxisubsApp_JUsergroup extends App
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_jusergroup';

    /**
	 * Public constructor. Overridden to load the language strings.
	 */
	public function __construct(& $subject, $config = array())
	{
    	if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			$this->enabled = false;
		}
		parent::__construct($subject, $config);
	}


    /**
	 * Plans to Groups to Add mapping
	 *
	 * @var  array
	 */
	protected $addGroups = array();

	/**
	 * Plans to Groups to Remove mapping
	 *
	 * @var  array
	 */
	protected $removeGroups = array();

	/**
	 * Method to integrate the plan form 
	 * */
	function onAxisubsPlanAfterFormRender($plan){
        $vars = new JObject();
        $vars->plan = $plan ;
        $vars->title= JText::_('PLG_AXISUBS_JUSERGROUP_PLAN_TAB_TITLE');
        $vars->html = $this->_getLayout('form', $vars);

        return $vars;
    }

    function AfterSubscriptionStatusUpdate($subscription, $old_subscription_status){
    	if ( isset($subscription->user_id) ) {
    		$this->onAxisubsUserRefresh($subscription->user_id);
    	}

    }

	function onAxisubsAfterSubscriptionStatusUpdate($subscription, $old_subscription_status){
		if ( isset($subscription->user_id) ) {
			$this->onAxisubsUserRefresh($subscription->user_id);
		}

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
		$addGroups = array(); $removeGroups = array();
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



	/**
	 * Load the groups to add / remove for a user
	 *
	 * @param   int     $user_id              The Joomla! user ID
	 * @param   array   $addGroups            Array of groups to add (output)
	 * @param   array   $removeGroups         Array of groups to remove (output)
	 * @param   string  $addGroupsVarName     Property name of the map of the groups to add
	 * @param   string  $removeGroupsVarName  Property name of the map of the groups to remove
	 *
	 * @return  void  We modify the $addGroups and $removeGroups arrays directly
	 */
	protected function loadUserGroups($user_id, array &$addGroups, array &$removeGroups, $addGroupsVarName = 'addGroups', $removeGroupsVarName = 'removeGroups')
	{

		$this->loadGroupAssignments();

		// Make sure we're configured
		if (empty($this->$addGroupsVarName) && empty($this->$removeGroupsVarName))
		{
			return;
		}

		// Get all of the user's subscriptions
		/** @var Subscriptions $subscriptionsModel */
		$subscriptionsModel = Container::getInstance('com_axisubs')->factory->model('Subscriptions')->tmpInstance();

		$subscriptions = $subscriptionsModel
			->user_id($user_id)
			->get(true);

		// Make sure there are subscriptions set for the user
		if (!$subscriptions->count())
		{
			return;
		}

		// Get the initial list of groups to add/remove from
		/** @var Subscriptions $sub */
		foreach ($subscriptions as $sub)
		{
			$plan = $sub->plan_id;

			if ($sub->status=='A')
			{
				// Enabled subscription, add groups
				if (empty($this->$addGroupsVarName))
				{
					continue;
				}

				if (!array_key_exists($plan, $this->$addGroupsVarName))
				{
					continue;
				}

				$addGroupsVar = $this->$addGroupsVarName;
				$groups       = $addGroupsVar[ $plan ];

				foreach ($groups as $group)
				{
					if (!in_array($group, $addGroups))
					{
						if (is_numeric($group) && !($group > 0))
						{
							continue;
						}

						$addGroups[] = $group;
					}
				}
			}
			else
			{
				// Disabled subscription, remove groups
				if (empty($this->$removeGroupsVarName))
				{
					continue;
				}

				if (!array_key_exists($plan, $this->$removeGroupsVarName))
				{
					continue;
				}

				$removeGroupsVar = $this->$removeGroupsVarName;
				$groups          = $removeGroupsVar[ $plan ];

				foreach ($groups as $group)
				{
					if (!in_array($group, $removeGroups))
					{
						if (is_numeric($group) && !($group > 0))
						{
							continue;
						}

						$removeGroups[] = $group;
					}
				}
			}
		}

		// If no groups are detected, do nothing
		if (empty($addGroups) && empty($removeGroups))
		{
			return;
		}

		// Sort the lists
		asort($addGroups);
		asort($removeGroups);

		// Clean up the remove groups: if we are asked to both add and remove a user
		// from a group, add wins.
		if (!empty($removeGroups) && !empty($addGroups))
		{
			$temp         = $removeGroups;
			$removeGroups = array();

			foreach ($temp as $group)
			{
				if (!in_array($group, $addGroups))
				{
					$removeGroups[] = $group;
				}
			}
		}
	}

	/**
	 * Load the add / remove group to plan ID map from the subscription plan options
	 *
	 * @return  void
	 */
	protected function loadGroupAssignments()
	{
		$this->addGroups    = array();
		$this->removeGroups = array();

		/** @var plans $model */
		$model           = Container::getInstance('com_axisubs')->factory->model('Plans')->tmpInstance();
		$plans          = $model->get(true);
		$addgroupsKey    = strtolower('jusergroup') . '_addgroups';
		$removegroupsKey = strtolower('jusergroup') . '_removegroups';

		if ($plans->count())
		{
			foreach ($plans as $plan)
			{

				if (isset($plan->params[$addgroupsKey]))
				{
					$content = $plan->params[$addgroupsKey];

					if (is_array($content))
					{
						$content = array_filter($content);
					}

					$this->addGroups[ $plan->axisubs_plan_id ] = $content;
				}

				if (isset($plan->params[$removegroupsKey]))
				{
					$content = $plan->params[$removegroupsKey];

					if (is_array($content))
					{
						$content = array_filter($content);
					}

					$this->removeGroups[ $plan->axisubs_plan_id ] = $content;
				}
			}
		}
	}


}