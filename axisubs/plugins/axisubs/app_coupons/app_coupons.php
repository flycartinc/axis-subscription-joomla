<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/App.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\App;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class plgAxisubsApp_Coupons extends App
{

	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_coupons';

    private $events =  array('');

    /**
     * Magic function used to handle all the email sending events
     * */
    public function __call($name, $arguments) 
    {
        $func_name = str_replace('onAxisubs', '', $name);
    }

    /**
     * Overriding
     *
     * @param $options
     * @return unknown_type
     */
    function onAxisubsGetAppView( $row )
    {

	   	if (!$this->_isMe($row))
    	{
    		return null;
    	}

    	$html = $this->viewList();

    	return $html;
    }
    
    /**
     * For loading Coupon price List in front end
     * */
    function onAxisubsLoadContentInPriceList($viewData, $plan_id, $page){
        $vars = new JObject();
        $app = JFactory::getApplication();
        $session = $app->getSession();
        $code = $session->get('axisubs_coupon_code', '');
        if($code != ''){
            $this->includeCustomModel('AppCoupons');
            $container  = \FOF30\Container\Container::getInstance('com_axisubs');
            $model      = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
            $couponItem = $model->getCouponDetailsByCouponCode($code);
            $vars->couponItem = $couponItem;
        }
        $vars->page = $page;
        $vars->plan_id = $plan_id;
        $vars->data = $viewData;
        $html = $this->_getLayout('frontend_price', $vars);
        return $html;
    }

    /**
     * For loading Coupon form in front end
     * */
    function onAxisubsLoadContentBelowPriceList($viewData, $plan_id, $page){
        $vars = new JObject();
        $app = JFactory::getApplication();
        $session = $app->getSession();
        $code = $session->get('axisubs_coupon_code', '');
        if($code != ''){
            $this->includeCustomModel('AppCoupons');
            $container  = \FOF30\Container\Container::getInstance('com_axisubs');
            $model      = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
            $couponItem = $model->getCouponDetailsByCouponCode($code);
            $vars->couponItem = $couponItem;
        }
        $vars->page = $page;
        $vars->plan_id = $plan_id;
        $vars->data = $viewData;
        $html = $this->_getLayout('frontend_form', $vars);
        return $html;
    }

    /**
     * set Coupon code in session
     * */
    function onAxisubsApplyCouponCode($couponcode, $plan_id, $apptask = ''){
        $app = JFactory::getApplication();
        $session = $app->getSession();
        if($apptask == ''){
            $this->includeCustomModel('AppCoupons');
            $container  = \FOF30\Container\Container::getInstance('com_axisubs');
            $model      = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
            $status     = $model->validateCouponCode($couponcode, $plan_id);
            if(isset($status->valid) && $status->valid){
                $session->set('axisubs_coupon_code', $couponcode);
                //$subscribeModel = \FOF30\Container\Container::getInstance('com_axisubs')->factory->model('Subscriptions');
                //$subscribeModel->resetSubscriptionPlan($plan_id);
                $result['result'] = 'success';
                $result['message'] = '<p class="alert alert-success">'.JText::_('PLG_AXISUBS_APP_COUPONS_APPLIED_SUCCESSFULLY').'</p>';
            } else {
                //$session->set('axisubs_coupon_code', '');
                $result['result'] = 'failed';
                $result['message'] = '<p class="alert alert-danger">'.JText::_('PLG_AXISUBS_APP_COUPONS_APPLIED_FAILED').'</p>';
            }
            echo json_encode($result);
        } else if($apptask == 'removeCoupon'){
            $subscribeModel = \FOF30\Container\Container::getInstance('com_axisubs')->factory->model('Subscriptions');
            $subscribeModel->resetSubscriptionPlan($plan_id);
            $session->set('axisubs_coupon_code', '');
            echo '1';
        }
        $app->close();
    }

    /**
     * For calculating value of coupon code
     * */
    function onAxisubsGetDiscountFromCouponCode($couponCode, $planId, $subscription_id, $total_price){
        $app        = JFactory::getApplication();
        $session    = $app->getSession();
        $this->includeCustomModel('AppCoupons');
        $container  = \FOF30\Container\Container::getInstance('com_axisubs');
        $model      = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
        $status     = $model->validateCouponCode($couponCode, $planId);
        if($status){
            if($status->valid){
                $discount = $model->calculateDiscount($planId, $status, $total_price);
                if($discount){
                    $session->set('axisubs_coupon_code_value', $discount);
                } else {
                    $session->set('axisubs_coupon_code_value', '');
                    $session->set('axisubs_coupon_code', '');
                }
            } else {
                $session->set('axisubs_coupon_code_value', '');
                $session->set('axisubs_coupon_code', '');
            }            
        } else {
            $session->set('axisubs_coupon_code_value', '');
            $session->set('axisubs_coupon_code', '');
        }
    }

    function onAxisubsApplyCouponCodeInSubscription($couponCode, $planId, $subscription_id, $discount, $discount_tax)
    {
        $app        = JFactory::getApplication();
        $session    = $app->getSession();
        $this->includeCustomModel('AppCoupons');        
        $container  = \FOF30\Container\Container::getInstance('com_axisubs');
        $model      = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
        if ($subscription_id) {
            $status     = $model->validateCouponCode($couponCode, $planId);
            if($status) {                
                if ($status->valid) {
                    //$discount = $model->calculateDiscount($planId, $status);
                    $model->updateDiscountDetails($subscription_id, $planId, $status, $discount, $discount_tax);
                }
            }
        }
    }

    public function onAxisubsClearAppSession(){
        $app        = JFactory::getApplication();
        $session    = $app->getSession();
        $session->set('axisubs_coupon_code', '');
        $session->set('axisubs_coupon_code_value', '');
    }

    /**
     *Save Coupon
    */
    function saveCoupons(){
        ob_clean();
        $app = JFactory::getApplication();
        $this->includeCustomModel('AppCoupons');
        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
        $model->saveCoupon();
        $app->close();
    }

    /**
     * Edit Coupon
     * */
    function editCoupons(){
        ob_clean();
        $app = JFactory::getApplication();
        $this->includeCustomModel('AppCoupons');
        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
        $coupon = $model->getCoupon($app->input->get('coupon_id'));
        echo json_encode($coupon->getData());
        $app->close();
    }

    /**
     * Delete Coupon
     * */
    function deleteCoupon(){
        ob_clean();
        $app = JFactory::getApplication();
        $this->includeCustomModel('AppCoupons');
        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );
        $coupon = $model->deleteCoupon($app->input->get('coupon_id'));
        echo json_encode($coupon);
        $app->close();
    }

    /**
     * View Coupons
     * */
    function viewCoupons()
    {
        $app = JFactory::getApplication();

        $vars = new JObject();
        $this->includeCustomModel('AppCoupons');
        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );

        $vars = $model->getCoupons();
        $html = $this->_getLayout('coupons', $vars);
        return $html;
    }

    /**
     * Validates the data submitted based on the suffix provided
     * A controller for this plugin, you could say
     *
     * @param $task
     * @return html
     */
    function viewList()
    {
    	$app = JFactory::getApplication();
        if($app->input->get('app_layout') == 'coupons'){
            if($app->input->get('app_task') == 'ajaxsave'){
                $this->saveCoupons();
            } else if($app->input->get('app_task') == 'edit'){
                $this->editCoupons();
            } else if($app->input->get('app_task') == 'delete'){
                $this->deleteCoupon();
            }
            $html = $this->viewCoupons();
            return $html;
        }
        
    	$option = 'com_axisubs';
    	$ns = $option.'.app';
    	$html = "";
    	JToolBarHelper::title(JText::_('AXISUBS_APP').'-'.JText::_('PLG_AXISUBS_'.strtoupper($this->_element)),'axisubs-logo');
        //JToolBarHelper::apply('apply');
        //JToolBarHelper::save();

	   	$vars = new JObject();
	    $this->includeCustomModel('AppCoupons');

        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppCoupons( $container, $config = array('name'=>'AxisubsModelAppCoupons') );

        $data = $this->params->toArray();
        $newdata = array();
        $newdata['params'] = $data;
        $form = $model->getForm($newdata);
        $vars->form = $form;

    	$id = $app->input->getInt('id', '0');
    	$vars->id = $id;
    	$html = $this->_getLayout('default', $vars);
    	return $html;
    }
}