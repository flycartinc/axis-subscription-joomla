<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
namespace Flycart\Axisubs\Admin\Helper;
defined('_JEXEC') or die;

use FOF30\Controller\Controller;
use FOF30\Container\Container;
use JRegistry;
use JPluginHelper;
use JFactory;

class AppController extends Controller {

	// the same as the plugin's one!
	var $_element = '';
    var $name = 'AppController';
	
	public function __construct(Container $container, array $config = array())
    {
		parent::__construct ( $container, $config );
		$this->registerTask ( 'apply', 'save' );
	}
	
	/**
	 * Overrides the getView method, adding the plugin's layout path
	 */
 	public function getView($name = null, $config = array()){
        $config ['template_path'] = JPATH_SITE.'/plugins/axisubs/'.$this->_element.'/'.$this->_element.'/tmpl/';
    	$view = parent::getView( $name, $config );
    	return $view;
    }
    
    function save() {
    	$app = JFactory::getApplication ();
    	$data = $app->input->post->getArray();

    	$save_params = new JRegistry ();
    	$save_params->loadArray ( $data ['params'] );
    
    	$plugin_data = JPluginHelper::getPlugin ( 'axisubs', $this->_element );
    	$params = new JRegistry ();
    	$params->loadString ( $plugin_data->params );
    	$params->merge ( $save_params );
    	$json = $params->toString ();
    	$db = JFactory::getDbo ();
    
    	$query = $db->getQuery ( true )->update ( $db->qn ( '#__extensions' ) )->set ( $db->qn ( 'params' ) . ' = ' . $db->q ( $json ) )->where ( $db->qn ( 'element' ) . ' = ' . $db->q ( $this->_element ) )->where ( $db->qn ( 'folder' ) . ' = ' . $db->q ( 'axisubs' ) )->where ( $db->qn ( 'type' ) . ' = ' . $db->q ( 'plugin' ) );
    
    	$db->setQuery ( $query );
    	$result = $db->execute ();
    	if ($data ['appTask'] == 'apply' && isset ( $data ['app_id'] )) {
    		$url = 'index.php?option=com_axisubs&view=apps&task=view&id=' . $data ['app_id'];
			if(isset($data ['app_layout']) && $data ['app_layout'] != ''){
				$url .= '&app_layout='.$data ['app_layout'];
			}
    	} else {
    		$url = 'index.php?option=com_axisubs&view=apps';
			if(isset($data ['app_layout']) && $data ['app_layout'] != ''){
				$url .= '&task=view';
			}
			if(isset($data ['app_id']) && $data ['app_id'] != ''){
				$url .= '&id='.$data ['app_id'];
			}
    	}
		if($result){
			$this->setRedirect ( $url , \JText::_('COM_AXISUBS_SAVE_SUCCESS'));
		} else {
			$this->setRedirect ( $url , \JText::_('COM_AXISUBS_SAVE_FAILED'), 'error');
		}
    	
    }

    /**
     * Overrides the delete method, to include the custom models and tables.
     */
    public function delete()
    {	
    	//$this->includeCustomTables();
    	parent::delete();
    }

    protected function includeCustomModels(){
    	// Include the custom table
    	//F0FModel::addIncludePath(JPATH_SITE.'/plugins/axisubs/'.$this->_element.'/'.$this->_element.'/models');
    	JFactory::getApplication()->triggerEvent('includeCustomModels', array() );
    }

    protected function includeCustomModel( $name ){
    	
		JFactory::getApplication()->triggerEvent('includeCustomModel', array($name, $this->_element) );
    }

    protected function includeAxisubsModel( $name ){    	
		JFactory::getApplication()->triggerEvent('includeAxisubsModel', array($name) );
    }

    protected function baseLink(){
    	$id = JFactory::getApplication()->input->getInt('id', '');
    	return "index.php?option=com_axisubs&view=apps&task=view&id={$id}";
    }
}