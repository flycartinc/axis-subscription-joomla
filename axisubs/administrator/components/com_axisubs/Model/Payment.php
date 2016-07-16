<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JFactory;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use Flycart\Axisubs\Admin\Helper\Plugin;
use JLoader;
use JPluginHelper;	

class Payment extends DataModel
{


	/**
     * Gets a list of Payment plugins and their titles
     *
     * @param   string  $country    Additional filtering based on the country
     *
     * @return  array
     */

	public function __construct(Container $container, array $config = array())
	{

		$this->tableName = "#__extensions";
		$this->idFieldName = "extension_id";
		$this->fieldsSkipChecks = [ 'params',
			'custom_data',
			'system_data',
			'checked_out',
			'checked_out_time' ] ;
		parent::__construct($container, $config);
	}

	public function getPaymentPlugins($country = '')
	{
		JLoader::import('joomla.plugin.helper');
		JPluginHelper::importPlugin('axisubs');

		$payment_plugins = Axisubs::plugin()->getPluginsWithEvent( 'onAxisubsGetPaymentPlugins');
		return $payment_plugins;
	}

	/**
	 * Method to buildQuery to return list of data
	 * @see DataModel::buildQuery()
	 * @return query
	 */
	public function buildQuery($overrideLimits = false) {
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$this->getSelectQuery($query);
		$this->getWhereQuery($query);
		$query->order('app.name ASC, app.extension_id');
		return $query;
	}

	/**
	 * Method to getSelect query
	 * @param unknown_type $query
	 */
	protected function getSelectQuery(&$query)
	{
		$query->select("app.extension_id,app.name,app.type,app.folder,app.element,app.params,app.enabled,app.ordering, app.manifest_cache")
			->from("#__extensions as app");
	}

	protected function getWhereQuery(&$query)
	{
		$db = JFactory::getDbo();
		$query->where("app.type=".$db->q('plugin'));
		$query->where("app.element LIKE 'payment_%'");
		$query->where("app.folder='axisubs'");

		$search = $this->getState('search', '');
		if($search){
			$query->where(
				$db->qn('app.').'.'.$db->qn('name').' LIKE '.$db->q('%'.$search .'%')
			);
		}
	}
}
