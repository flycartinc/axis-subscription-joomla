<?php
/**
 * @package      axisubs
 * @copyright    Copyright (c)2015-2019 Sasi varna kumar / J2Store.org
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 * @version      0.8
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// no direct access
defined('_JEXEC') or die();

// Load FOF if not already loaded
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('This component requires FOF 3.0.');
}

class Com_AxisubsInstallerScript extends \FOF30\Utils\InstallScript
{
	/**
	 * The component's name
	 *
	 * @var   string
	 */
	protected $componentName = 'com_axisubs';

	/**
	 * The title of the component (printed on installation and uninstallation messages)
	 *
	 * @var string
	 */
	protected $componentTitle = 'Axisubs';

	/**
	 * The minimum PHP version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumPHPVersion = '5.4.0';

	/**
	 * The minimum Joomla! version required to install this extension
	 *
	 * @var   string
	 */
	protected $minimumJoomlaVersion = '3.4.0';

	/**
	 * Obsolete files and folders to remove from both paid and free releases. This is used when you refactor code and
	 * some files inevitably become obsolete and need to be removed.
	 *
	 * @var   array
	 */
	protected $removeFilesAllVersions = array( );
	
	private $axisubsCliScripts = array(
		'axisubs-expirycontrol.php'
	);
	
	public function postflight($type, $parent)
	{
		// Call the parent method
		parent::postflight($type, $parent);

		$this->_installLocalisation($parent);	
		$this->_copyCliFiles($parent);
	}

	public function uninstall($parent)
	{
		parent::uninstall($parent);
	}

	function _installLocalisation($parent) {

		$installer = $parent->getParent();
		$db = JFactory::getDbo();

		//zones
		$sql = $installer->getPath('source').'/administrator/components/com_axisubs/sql/install/mysql/zones.sql';
		$this->_executeSQLFiles($sql);

		// create a currency object if no currencies are found yet.
		$sql = " INSERT INTO `#__axisubs_currencies` (`axisubs_currency_id`, `currency_title`, `currency_code`, `currency_position`, `currency_symbol`, `currency_num_decimals`, `currency_decimal`, `currency_thousands`, `currency_value`, `enabled`, `ordering`) VALUES (1, 'USD', 'USD', 'pre', '$', 2, '.', ',', 1.00000000, 1, 0);";
		$count_sql = "SELECT count(*) FROM #__axisubs_currencies ";
		$db->setQuery($count_sql);
		$currency_count = $db->loadResult();
		if ( !$currency_count ) {
			$this->_sqlexecute($sql);
		}

		// validate the configurations table
		$get_conf_sql = "SELECT config_meta_value FROM #__axisubs_configurations where config_meta_key='config_currency'; ";
		$db->setQuery($get_conf_sql);
		$conf_currency = $db->loadObjectList();
		$sql='';
		if ( empty($conf_currency) ) {
			$sql = " INSERT INTO `#__axisubs_configurations` (`config_meta_key`, `config_meta_value`, `config_meta_default`) VALUES ('config_currency', 'USD', ''); " ;
		}elseif (empty($conf_currency[0]->config_meta_value)) {
			$sql = " UPDATE `#__axisubs_configurations` SET `config_meta_value` = 'USD' WHERE `#__axisubs_configurations`.`config_meta_key` = 'config_currency'; ";
		}

		if (!empty($sql)) {
			$this->_sqlexecute($sql);
		}
	}
	
	/**
	 * Copies the CLI scripts into Joomla!'s cli directory
	 *
	 * @param JInstaller $parent
	 */
	private function _copyCliFiles($parent)
	{
		$src = $parent->getParent()->getPath('source');

		JLoader::import("joomla.filesystem.file");
		JLoader::import("joomla.filesystem.folder");

		foreach($this->axisubsCliScripts as $script) {
			if(JFile::exists(JPATH_ROOT.'/cli/'.$script)) {
				JFile::delete(JPATH_ROOT.'/cli/'.$script);
			}
			if(JFile::exists($src.'/cli/'.$script)) {
				JFile::move($src.'/cli/'.$script, JPATH_ROOT.'/cli/'.$script);
			}
		}
	}

	
	private function _executeSQLFiles($sql) {
		if(JFile::exists($sql)) {
			$db = JFactory::getDbo();
			$queries = JDatabaseDriver::splitSql(file_get_contents($sql));
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					try {
						$db->execute();
					}catch(Exception $e) {
						//do nothing as customer can do this very well by going to the tools menu
					}
				}
			}
		}
	}

	private function _sqlexecute($query) {
		$db = JFactory::getDbo();
		$db->setQuery($query);
		try {
			$db->execute();
		}catch(Exception $e) {
			//do nothing as customer can do this very well by going to the tools menu
		}
	}

}
