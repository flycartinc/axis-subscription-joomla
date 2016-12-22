<?php
/**
 * @package   Axisubs - Tags
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined('_JEXEC') or die();

JLoader::import('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

use FOF30\Container\Container;
use Flycart\Axisubs\Admin\Model\Plans;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class plgSystemAxisubsTags extends JPlugin
{
	/**
	 * Should this plugin be allowed to run? True if FOF can be loaded and the Akeeba Subscriptions component is enabled
	 *
	 * @var  bool
	 */
	private $enabled = true;

	protected $planObjects = array();
	/**
	 * Public constructor. Overridden to load the language strings.
	 */
	public function __construct(& $subject, $config = array())
	{
		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			$this->enabled = false;
		}

		// Do not run if Akeeba Subscriptions is not enabled
		JLoader::import('joomla.application.component.helper');

		if (!JComponentHelper::isEnabled('com_axisubs'))
		{
			$this->enabled = false;
		}

		if (!is_object($config['params']))
		{
			JLoader::import('joomla.registry.registry');
			$config['params'] = new JRegistry($config['params']);
		}

		parent::__construct($subject, $config);

		// Load the language files
		$jlang = JFactory::getLanguage();
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('com_axisubs', JPATH_ADMINISTRATOR, null, true);

		$jlang->load('com_axisubs', JPATH_SITE, 'en-GB', true);
		$jlang->load('com_axisubs', JPATH_SITE, $jlang->getDefault(), true);
		$jlang->load('com_axisubs', JPATH_SITE, null, true);

		$jlang->load('plg_system_axisubstags', JPATH_ADMINISTRATOR, 'en-GB', true);
		$jlang->load('plg_system_axisubstags', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
		$jlang->load('plg_system_axisubstags', JPATH_ADMINISTRATOR, null, true);
	}

	/**
	 * Called when Joomla! is booting up and checks for axisubs tags.
	 */
	public function onAfterRender()
	{
		if (!$this->enabled){
			return;
		}

		$app = JFactory::getApplication();
		if ($app->getName() === 'site') {
			/**
			 * key => Function name
			 * value => tag
			 * */
			$tags = array('PlanPrice' => 'axisubs-plan-price',
				'PlanSetUpCost' => 'axisubs-plan-setup-cost',
				'PlanDuration' => 'axisubs-plan-duration',
				'SubscribeButton' => 'axisubs-subscribe-button');

			$buffer = $app->getBody();
			foreach ($tags as $functionName => $tag){
				preg_match_all("/{".$tag."}(.*?){\\/".$tag."}/", $buffer, $matches);
				if(!empty($matches['0'])){
					foreach($matches['0'] as $key => $priceTag){
						$planId = $matches['1'][$key];
						if(!isset($this->planObjects[$planId])){
							$this->planObjects[$planId] = $this->getPlanDetails($planId);
						}
						$fullFunctionName = 'process'.$functionName;
						$displayContent = $this->$fullFunctionName($planId);
						$displayContent = str_replace('$','\$',$displayContent);
						$buffer = preg_replace("/{".$tag."}".$planId."{\\/".$tag."}/i", $displayContent , $buffer);
					}
				}
			}
			$app->setBody($buffer);
		}
	}

	/**
	 * Process plan price
	 * */
	protected function processPlanPrice($planId){
		$currency = Axisubs::currency();
		$planPrice = $currency->format($this->planObjects[$planId]->price);
		return $planPrice;
	}

	/**
	 * Process plan price
	 * */
	protected function processPlanSetUpCost($planId){
		$currency = Axisubs::currency();
		$setupcost = $currency->format($this->planObjects[$planId]->setup_cost);
		return $setupcost;
	}

	/**
	 * Process plan price
	 * */
	protected function processPlanDuration($planId){
		$get_duration = Axisubs::duration();
 		if($this->planObjects[$planId]->plan_type){
			$planDuration = '<span class="axisubs_duration">'.$get_duration->getDurationInFormat($this->planObjects[$planId]->period, $this->planObjects[$planId]->period_unit).'</span>';
		} else {
			$planDuration = '<span class="axisubs_duration">'.JText::_('COM_AXISUBS_PLAN_PERIOD_FOREVER').'</span>';
		}
		return $planDuration;
	}

	/**
	 * Process plan price
	 * */
	protected function processSubscribeButton($planId){
		if ( $this->planObjects[$planId]->hasTrial() ){
			$text = JText::_('COM_AXISUBS_START_TRIAL');
		} else {
			$text = JText::_('COM_AXISUBS_SUBSCRIBE_NOW');
		}
		$html = '<a class="btn btn-large btn-primary" href="'.JRoute::_('index.php?option=com_axisubs&view=subscribe&plan='.$this->planObjects[$planId]->slug).'">';
		$html .= $text;
		$html .= '</a>';
		return $html;
	}

	/**
	 * Process plan details
	 * */
	protected function getPlanDetails($planId){
		$planModel = Container::getInstance('com_axisubs',array(),'admin')->factory->model('Plans')->tmpInstance();
		$planModel->getClone();
		$planModel->load($planId);
		return $planModel;
	}
}