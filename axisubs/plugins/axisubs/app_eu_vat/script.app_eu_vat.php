<?php
/**
 * @package   App EU Vat - Axis Subscription
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
class plgAxisubsapp_eu_vatInstallerScript {
    function preflight($type, $parent) {

        /*if(!JComponentHelper::isEnabled('com_axisubs')) {
            Jerror::raiseWarning(null, 'Axisubs not found. Please install Axisubs before installing this plugin');
            return false;
        }*/
        
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
