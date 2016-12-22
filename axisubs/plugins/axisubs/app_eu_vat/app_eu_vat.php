<?php
/**
 * @package   App EU Vat - Axis Subscription
 * @copyright Copyright (c)2016-2020 Ashlin / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Plugins/App.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_axisubs/Helper/Axisubs.php');

use Flycart\Axisubs\Admin\Helper\Plugins\App;
use Flycart\Axisubs\Admin\Helper\Axisubs;

class plgAxisubsApp_EU_Vat extends App
{

	/**
	 * @var $_element  string  Should always correspond with the plugin's filename,
	 *                         forcing it to be unique
	 */
    var $_element   = 'app_eu_vat';

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
     * For validating fields
     * */
    public function onAxisubsValidateBillingFields(&$errors, $data){
        if ($this->params->get('validate_vat_number', 0)) {
            if(isset($data['vat_number']) && isset($data['country'])){
                if($data['vat_number'] != '' && $data['country'] != ''){
                    $country = $data['country'];
                    $this->includeCustomModel('AppEUVat');
                    $container = \FOF30\Container\Container::getInstance('com_axisubs');
                    $model = new AxisubsModelAppEUVat($container, $config = array('name' => 'AxisubsModelAppEUVat'));
                    $eu_countries = $model->getEUCountries();
                    if (in_array($country, $eu_countries)) {
                        $validate_vat_number = $model->validateVatNumber($country, $data['vat_number']);
                        if (!$validate_vat_number) {
                            $errors['vat_number'] = JText::_('PLG_AXISUBS_APP_EU_VAT_VALIDATE_VAT_FIELD_ERROR_MSG');
                        }
                    }
                }
            }
        }
    }

    /**
     * check tax is applicable or not
     * */
    function onAxisubsCheckTaxIsApplicable(&$enableTax, $object){
        $this->includeCustomModel('AppEUVat');
        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppEUVat($container, $config = array('name' => 'AxisubsModelAppEUVat'));
        $eu_countries = $model->getEUCountries();

        $config = Axisubs::config();
        $country = $config->get('country_id');

        //Rule 1: Home country's individuals and businesses are charged tax
        //Rule 2: EU individuals and businesses with no valid VAT Number are charged tax
        if(isset($object->customer->vat_number) && isset($object->customer->country)) {
            if ($object->customer->vat_number != '' && $object->customer->country != '') {
                if (in_array($object->customer->country, $eu_countries)) {
                    //Rule 3: EU (non-home country) individuals and businesses with VALID VAT are charged 0 tax
                    if ($object->customer->country != $country){
                        $validate_vat_number = $model->validateVatNumber($object->customer->country, $object->customer->vat_number);
                        if ($validate_vat_number) {
                            $enableTax = 0;
                        }
                    }

                }
            }
        }

        if(isset($object->customer->country)) {
            // Sub Rule 1: EU individuals (non-home country ) are charged tax
            if ($this->params->get('apply_digital_rules', 0)) {
                if(isset($object->customer->company)) {
                    if ($object->customer->country == $country && $object->customer->company == '') {
                        $enableTax = 0;
                    }
                } else {
                    if ($object->customer->country == $country) {
                        $enableTax = 0;
                    }
                }
            }

            //Sub Rule 2: Non EU residents are charged 0 percent tax
            if ($this->params->get('no_tax_for_non_eu', 1)) {
                if (!in_array($object->customer->country, $eu_countries)) {
                    $enableTax = 0;
                }
            }
        }
    }

    /**
     * App view
     * */
    public function viewList() {
        $app = JFactory::getApplication();
        JToolBarHelper::title(JText::_('AXISUBS_APP').'-'.JText::_('PLG_AXISUBS_'.strtoupper($this->_element)),'axisubs-logo');
        JToolBarHelper::apply('apply');
        JToolBarHelper::save();

        $vars = new JObject();
        $this->includeCustomModel('AppEUVat');

        $container = \FOF30\Container\Container::getInstance('com_axisubs');
        $model = new AxisubsModelAppEUVat( $container, $config = array('name'=>'AxisubsModelAppEUVat') );

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