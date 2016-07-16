<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */

namespace Flycart\Axisubs\Admin\Model;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Model\DataModel;
use JLoader;

class EmailTemplates extends DataModel {

	use Mixin\FOF3Utils, Mixin\ImplodedArrays;

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->addBehaviour('RelationFilters');

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [  'type', 'event', 'ordering' ,'cc','bcc','params' ];
	}

	public function getEmailContent( $emailtemplate_id  = 0 ){
		if ( $emailtemplate_id ==0 ){
			$emailtemplate_id = $this->axisubs_emailtemplate_id;
		}
		if ( empty($emailtemplate_id) ){
			return array();
		}
		
		$mail_content=array();

		$config = \JFactory::getConfig();
		$defualt_lang_code = $config->get('language');

		// get the list of language in the site
		$languages = \JLanguageHelper::getLanguages();
		
		$etconent_model = $this->getModel('EmailTemplateContents');

		foreach ($languages as $language) {
			$mail_content[$language->lang_id] = new \stdClass();
			$mail_content[$language->lang_id]->language  = $language;

			$etcontent = $etconent_model ->emailtemplate_id($emailtemplate_id)
							->language_id($language->lang_id)
							->get()
							->toArray();
			$fields = array();
			foreach ($etcontent as $c) {
				$fields[$c['field']] = (Object) $c;
			}

			$mail_content[$language->lang_id]->fields  = $fields;
			$mail_content[$language->lang_id]->is_default  = 0;
			if ( $defualt_lang_code == $language->lang_code ){
				$mail_content[$language->lang_id]->is_default  = 1;
			}
		}
	
		return $mail_content;
	}

	function saveEmailContent($emailcontent) {
		$fields_to_save = array('subject','body_html','body_plain');
		foreach ($emailcontent as $lang_id => $content) {
			
			$data = array(   'language_id' =>  $content['language_id'],
							 'emailtemplate_id' =>  $content['emailtemplate_id'],
							 );

			$etconent_model = $this->getModel('EmailTemplateContents');

			foreach ($fields_to_save as $field) {
				
				$data['field'] =  $field;
				$etconent_model->load( $data , true);
		//$presaved_data = $etconent_model->getData(); print_r( $presaved_data ); 
				$data['content'] =  $content[$field];
				$etconent_model->bind( $data );
				$etconent_model->store();

				unset($data['content']);
				$etconent_model->reset();

			}
		}
	}	

	/**
	 * Converts the loaded comma-separated list of Payment plugins into an array
	 *
	 * @param   string  $value  The comma-separated list
	 *
	 * @return  array  The exploded array
	 */
	protected function getPaymentmethodAttribute($value)
	{
		return $this->getAttributeForImplodedArray($value);
	}

	/**
	 * Converts the array of Payment plugins into a comma separated list
	 *
	 * @param   array  $value  The array of values
	 *
	 * @return  string  The imploded comma-separated list
	 */
	protected function setPaymentmethodAttribute($value)
	{
		return $this->setAttributeForImplodedArray($value);
	}

	/**
	 * Converts the loaded comma-separated list of Payment plugins into an array
	 *
	 * @param   string  $value  The comma-separated list
	 *
	 * @return  array  The exploded array
	 */
	protected function getSubscriptionStatusAttribute($value)
	{
		return $this->getAttributeForImplodedArray($value);
	}

	/**
	 * Converts the array of Payment plugins into a comma separated list
	 *
	 * @param   array  $value  The array of values
	 *
	 * @return  string  The imploded comma-separated list
	 */
	protected function setSubscriptionStatusAttribute($value)
	{
		return $this->setAttributeForImplodedArray($value);
	}

}