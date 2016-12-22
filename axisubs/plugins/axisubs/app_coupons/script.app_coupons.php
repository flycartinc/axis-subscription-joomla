<?php
/**
 * @package   App Coupons - Axisubs
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class plgAxisubsapp_couponsInstallerScript {
    function preflight($type, $parent) {

        /*if(!JComponentHelper::isEnabled('com_axisubs')) {
            Jerror::raiseWarning(null, 'Axisubs not found. Please install Axisubs before installing this plugin');
            return false;
        }*/
        //For Coupons table
        $query = "CREATE TABLE IF NOT EXISTS `#__axisubs_coupons` (
                  `axisubs_coupon_id` int(10) NOT NULL AUTO_INCREMENT,
                  `name` varchar(255) NOT NULL,
                  `code` varchar(255) NOT NULL,
                  `published` int(2) NOT NULL,
                  `value_type` varchar(100) NOT NULL,
                  `value` varchar(100) NOT NULL,
                  `valid_from` datetime NOT NULL,
                  `valid_upto` datetime NOT NULL,
                  `plans` varchar(255) NOT NULL,
                  `customer_groups` varchar(255) NOT NULL,
                  `users` text NOT NULL,
                  `minimum_spend` int(10) NOT NULL,
                  `max_coupon` int(10) NOT NULL,
                  `max_coupon_per_customer` int(10) NOT NULL,
                  `created_on` datetime NOT NULL,
                  `created_by` int(10) NOT NULL,
                  `updated_on` datetime NOT NULL,
                  PRIMARY KEY (`axisubs_coupon_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->_executeQuery ( $query );

        //Discount table
        $query = "CREATE TABLE IF NOT EXISTS `#__axisubs_subscriptiondiscounts` (
                  `axisubs_subscriptiondiscount_id` int(10) NOT NULL AUTO_INCREMENT,
                  `subscription_id` int(10) NOT NULL,
                  `discount_customer_email` varchar(255) NOT NULL,
                  `discount_type` varchar(100) NOT NULL,
                  `discount_id` int(10) NOT NULL,
                  `discount_title` varchar(255) NOT NULL,
                  `discount_code` varchar(255) NOT NULL,
                  `discount_value` varchar(100) NOT NULL,
                  `discount_value_type` varchar(100) NOT NULL,
                  `discount_amount` varchar(100) NOT NULL,
                  `discount_tax` varchar(100) NOT NULL,
                  `discount_params` mediumtext NOT NULL,
                  PRIMARY KEY (`axisubs_subscriptiondiscount_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->_executeQuery ( $query );
    }

    private function _executeQuery($query) {
        $db = JFactory::getDbo ();
        $db->setQuery ( $query );
        try {
            $db->execute ();
        } catch ( Exception $e ) {
            // do nothing. we dont want to fail the install process.
        }
    }
}
