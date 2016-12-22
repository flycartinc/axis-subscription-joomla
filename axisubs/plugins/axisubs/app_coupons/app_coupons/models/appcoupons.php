<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/AppModel.php');
use Flycart\Axisubs\Admin\Helper\AppModel;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use FOF30\Container\Container;

class AxisubsModelAppCoupons extends AppModel
{
	public $_element = 'app_coupons';

	/**
	 * Save Coupon
	 * */
	public function saveCoupon(){
		$app = JFactory::getApplication();
		$post = $app->input->post->getArray();
		$requiredFields = array('name', 'code', 'value');
		$valid = true;
		foreach ($requiredFields as $fields){
			if(!isset($post['jform'][$fields]) || trim($post['jform'][$fields]) == ''){
				$valid = false;
				$data['field'][] = $fields;
				$data['message'] = '<div class="alert alert-failed">'.JText::_('PLG_AXISUBS_APP_COUPONS_INVALID_FIELDS').'</div>';
			}
		}
		if($valid){
			foreach ($post['jform'] as $key => $value){
				if(is_array($value)){
					$item_data[$key] = implode(',', $value);
				} else {
					$item_data[$key] = $value;
				}
			}
			//For checking coupon code already exists or not to avoid duplicate code
			$available = $this->checkCouponCodeAlreadyExists($item_data['code'], $item_data['axisubs_coupon_id']);
			if($available){
				$data['field'][] = 'code';
				$data['message'] = '<div class="alert alert-failed">'.JText::_('PLG_AXISUBS_APP_COUPONS_COUPON_ALREADY_EXIST').'</div>';
				$valid = false;
			}
			if($valid) {
				$model = $this->_getModelObject();
				$modelC = $model->getClone();
				$modelC->load($item_data['axisubs_coupon_id']);
				$result = $modelC->save($item_data);
				if (isset($result->axisubs_coupon_id) && $result->axisubs_coupon_id) {
					$data['result'] = 'success';
					$data['message'] = '<div class="alert alert-success">' . JText::_('PLG_AXISUBS_APP_COUPONS_SAVE_SUCCESS') . '</div>';
				} else {
					$data['result'] = 'failed';
					$data['message'] = '<div class="alert alert-danger">' . JText::_('PLG_AXISUBS_APP_COUPONS_SAVE_FAILED') . '</div>';
				}
			} else {
				$data['result'] = 'failed';
			}
		} else {
			$data['result'] = 'failed';
		}
		echo json_encode($data);
	}

	/**
	 * Get single Coupon
	 * */
	public function getCoupon($id){
		$model = $this->_getModelObject();
		$modelC = $model->getClone();
		$modelC->load($id);
		return $modelC;
	}

	/**
	 * Delete Coupon
	 * */
	public function deleteCoupon($id){
		if($id){
			$model = $this->_getModelObject();
			$model->delete($id);
			$data['result'] = 'success';
			$data['message'] = '<div class="alert alert-success">'.JText::_('PLG_AXISUBS_APP_COUPONS_DELETED_SUCCESSFULLY').'</div>';
		} else {
			$data['result'] = 'failed';
			$data['message'] = '<div class="alert alert-danger">'.JText::_('PLG_AXISUBS_APP_COUPONS_DELETE_FAILED').'</div>';
		}
		return $data;
	}

	/**
	 * Get Coupon details by Coupon code
	 * */
	public function getCouponDetailsByCouponCode($couponCode){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__axisubs_coupons');
		$query->where('code = '.$db->q($couponCode));
		$query->where('published = 1');
		//$query->where('valid_from >= '. $db->q((string)$now).' AND valid_upto <= '. $db->q((string)$now));
		$db->setQuery($query);
		$data = $db->loadObject();

		return $data;
	}

	/**
	 * check Coupon code already exists
	 * */
	public function checkCouponCodeAlreadyExists($couponCode, $id = 0){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__axisubs_coupons');
		$query->where('code = '.$db->q($couponCode));
		if($id){
			$query->where('axisubs_coupon_id != '.$db->q($id));
		}
		$db->setQuery($query);
		$data = $db->loadObject();

		return $data;
	}

	public function validateCouponCode($couponCode, $planId){
		$today = date('Y-m-d H:i:s');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__axisubs_coupons');
		$query->where('code = '.$db->q($couponCode));
		$query->where('published = 1');
		//$query->where('valid_from >= '. $db->q((string)$now).' AND valid_upto <= '. $db->q((string)$now));
		$db->setQuery($query);
		$data = $db->loadObject();
		$status = true;
		if($data){
			$todaytime = strtotime($today);
			if($data->valid_from != '0000-00-00 00:00:00'){
				if(strtotime($data->valid_from) > $todaytime){
					$status = false;
				}
			}
			if($data->valid_upto != '0000-00-00 00:00:00'){
				if(strtotime($data->valid_upto) < $todaytime){
					$status = false;
				}
			}
			if($data->plans){
				if(Axisubs::isPro()){
					$plans = explode(',', $data->plans);
					if(!in_array($planId, $plans)){
						$status = false;
					}
				}
			}
			if($status){
				$data->valid = 1;
			} else {
				$data->valid = 0;
			}

		}
		return $data;
	}

	public function updateDiscountDetails($subscription_id, $planid, $coupon, $discount, $discount_tax){
		$user = JFactory::getUser();
		$item_data['axisubs_subscriptiondiscount_id'] = null;
		$item_data['subscription_id'] = $subscription_id;
		$item_data['discount_customer_email'] = $user->email;
		$item_data['discount_type'] = 'coupons';
		$item_data['discount_id'] = $coupon->axisubs_coupon_id;
		$item_data['discount_title'] = $coupon->name;
		$item_data['discount_code'] = $coupon->code;
		$item_data['discount_value'] = $coupon->value;
		$item_data['discount_value_type'] = $coupon->value_type;
		$item_data['discount_amount'] = $discount;
		$item_data['discount_tax'] = $discount_tax;
		$model = $this->_getModelDiscountObject();
		$modelD = $model->getClone();
		$modelD->load(array('subscription_id' => $subscription_id));
		$result = $modelD->save($item_data);
	}

	public function calculateDiscount($planid, $coupon, $totalPrice){
		//$plan = $this->getPlan($planid);
		$amount = 0;
		if($totalPrice>0) {
			if ($coupon->value_type == 'percent') {
				$amount = round((($coupon->value / 100) * $totalPrice), 2);
			} else if ($coupon->value_type == 'fixed') {
				$amount = round($coupon->value, 2);
			} else {
				$amount = 0;
			}
			if ($amount > $totalPrice) {
				$amount = 0;
			}
		}
		return $amount;
	}
	
	public function getPlan($planid){
		$planModel = Container::getInstance('com_axisubs')->factory->model('Plans')->tmpInstance();
		$plan = $planModel->axisubs_plan_id($planid)->get()->first();
		return $plan;
	}

	/**
	 * get list of Coupons
	 * */
	public function getCoupons(){
		$vars = new JObject();

		//get model Object
		$model = $this->_getModelObject();
		// Search Filter
		//$this->filterListingData($model);

		// List state information
		$app = JFactory::getApplication();
		$limit = $app->getUserStateFromRequest('limit', 'limit', $app->get('list_limit'), 'int');
		$limitstart = $app->input->get('limitstart', 0);

		$model->setState('limitstart', $limitstart);
		$model->setState('limit', $limit);
		$model->populateState();
		$vars->items = $model->get('Items');
		$vars->pagination = new JPagination(count($vars->items), $model->getState('limitstart'), $model->getState('limit'));
		$vars->items = $vars->items->slice($limitstart,$limit);
		$vars->model = $model;
		$vars->lists = $model->getLists();

		return $vars;
	}

	// For filters
	/*protected function filterListingData(&$model){
		$db = $model->getDbo();
		$filter_search = $model->getState('filter_search');
		if($filter_search) {
			$filter_search = '%' . $filter_search . '%';
			$model->whereRaw(
			//$db->qn('title') . ' LIKE ' . $db->q('%'.$filter_search.'%')
				'(' .
				'(' . $db->qn('title') .
				' LIKE ' . $db->q($filter_search) . ') OR ' .
				'(' . $db->qn('metadesc') .
				' LIKE ' . $db->q($filter_search) . ')'
				. ')'
			);
		}
		$model->order('axisubs_coupon_id DESC');

	}*/

	/**
	 * Get Model Object
	 * */
	protected function _getModelDiscountObject(){
		// Load data through model
		$container = \FOF30\Container\Container::getInstance('com_axisubs', array(), 'admin');
		$container->factory->setSection('admin');
		$config = array('tableName' => '#__axisubs_subscriptiondiscounts',
			'idFieldName' => 'axisubs_subscriptiondiscount_id',
			'autoChecks' => false);
		$model = $container->factory->model('Subscriptiondiscounts', $config);

		return $model;
	}

	/**
	 * Get Model Object
	 * */
	protected function _getModelObject(){
		// Load data through model
		$container = \FOF30\Container\Container::getInstance('com_axisubs', array(), 'admin');
		$container->factory->setSection('admin');
		$config = array('tableName' => '#__axisubs_coupons',
			'idFieldName' => 'axisubs_coupon_id',
			'autoChecks' => false);
		$model = $container->factory->model('Coupons', $config);

		return $model;
	}
}