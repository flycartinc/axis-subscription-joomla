<?php
/**
 * @package   Axisubs Module - Subscription Management System
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
use FOF30\Container\Container;
class modAxisubsPlanHelper {

    /**
     * For get details of plan
     * */
    public function getSelectedPlan($params){
        $planId = $params->get('plan', '');
       if($planId){
            return $this->getPlanDetailsById($planId);
       } else {
           return false;
       }
    }

    /**
     * get plan details by id
     * */
    protected function getPlanDetailsById($planId){
        $planModel = Container::getInstance('com_axisubs',array(),'admin')->factory->model('Plans')->tmpInstance();
        $planModel->getClone();
        $planModel->load($planId);
        return $planModel;
    }
}
