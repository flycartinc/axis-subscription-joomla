<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Controller;

defined('_JEXEC') or die;

use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Controller\DataController;
use FOF30\Inflector\Inflector;
use FOF30\Container\Container;
use JFactory;

class SubscriptionInfo extends DataController
{

	
	public function __construct(Container $container, array $config = array())
	{
		$this->taskMap = [	'save' 	=> 'save',
							'apply' => 'apply' ];
		parent::__construct($container, $config);
	}

	public function onBeforeBrowse(){
		$app = JFactory::getApplication();
		$url = 'index.php?option=com_axisubs&view=Subscriptions';
		$app->redirect($url);
	}

	public function onAfterApplySave(){
		$app = JFactory::getApplication();
		$subs_id = $app->input->get('subscription_id',0);
		$url = 'index.php?option=com_axisubs&view=Subscription';
		if ($subs_id > 0 ){
			$url .='&task=read&id='.$subs_id ;
		}
		$app->redirect($url,\JText::_('COM_AXISUBS_LBL_SUBSCRIPTIONINFO_SAVED'),'message');
		return ;		
	}
}

