<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
// No direct access to this file
namespace Flycart\Axisubs\Admin\Helper;
defined('_JEXEC') or die;

use FOF30\Model\Model;
use FOF30\Container\Container;
use JRegistry;
use JPluginHelper;
use JFactory;
use FOF30\Form\Form;

class AppModel extends Model {
	
	public $_element = '';
	
	/**
	 * Method to get a form object.
	 *
	 * @param   string          $name       The name of the form.
	 * @param   string          $source     The form filename (e.g. form.browse)
	 * @param   array           $options    Optional array of options for the form creation.
	 * @param   boolean         $clear      Optional argument to force load a new form.
	 * @param   bool|string     $xpath      An optional xpath to search for the fields.
	 *
	 * @return  mixed  Form object on success, False on error.
	 *
	 * @throws  Exception
	 *
	 * @see     Form
	 * @since   2.0
	 */
	protected function loadForm($name, $source, $options = array(), $clear = false, $xpath = 'config', $data = array())
	{
		
		//if(empty($this->_element)) return parent::loadForm($name, $source, $options, $clear, $xpath);
		
		// Handle the optional arguments.
		$options['control'] = isset($options['control']) ? $options['control'] : false;
	
		// Create a signature hash.
		$hash = md5($source . serialize($options));
	
		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}
	
		// Try to find the name and path of the form to load
		$paths = array();
		$paths[] = JPATH_SITE.'/plugins/axisubs/'.$this->_element;
		$name = $this->_element;
		$source = $this->_element;
		$formFilename = $this->findFormFilename($source, $paths);
		

		// No form found? Quit!
		if ($formFilename === false)
		{
			return false;
		}
	
		// Set up the form name and path
		$source = basename($formFilename, '.xml');
		$source = $formFilename;

		// Set up field paths
		$option         = $this->input->getCmd('option', 'com_axisubs');
		$componentPaths = $this->container->platform->getComponentBaseDirs($option);
		$view           = $this->name;
		$file_root      = $componentPaths['main'];
		$alt_file_root  = $componentPaths['alt'];

		// Get the form.
		try
		{
			$form = Form::getInstance($name, $source, $options, true, $xpath);
			
			// Allows data and form manipulation before preprocessing the form
			$this->onBeforePreprocessForm($form, $data);
	
			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

		}
		catch (Exception $e)
		{
			// The above try-catch statement will catch EVERYTHING, even PhpUnit exceptions while testing
			if(stripos(get_class($e), 'phpunit') !== false)
			{
				throw $e;
			}
			else
			{
				$this->setError($e->getMessage());
	
				return false;
			}
		}
	
		// Store the form for later.
		//$this->_forms[$hash] = $form;
		return $form;
	}
	





	/**
	 * A method for getting the form from the model.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   boolean  $source    The name of the form. If not set we'll try the form_name state variable or fall back to default.
	 *
	 * @return  mixed  A F0FForm object on success, false on failure
	 *
	 */
	public function getForm($data = array(), $loadData = true, $source = null)
	{
		$this->_formData = $data;

		if (empty($source))
		{
			$source = $this->getState('form_name', null);
		}

		if (empty($source))
		{
			$source = 'form.' . $this->name;
		}

		$name = $this->input->getCmd('option', 'com_axisubs') . '.' . $this->name . '.' . $source;

		$options = array(
			'control'	 => false,
			'load_data'	 => $loadData,
		);

		$this->onBeforeLoadForm($name, $source, $options);
 
		$form = $this->loadForm($name, $source, $options);

		$form->bind($data);

		if ($form instanceof Form)
		{
			$this->onAfterLoadForm($form, $name, $source, $options);
		}

		return $form;
	}


	/**
	 * Guesses the best candidate for the path to use for a particular form.
	 *
	 * @param   string  $source  The name of the form file to load, without the .xml extension.
	 * @param   array   $paths   The paths to look into. You can declare this to override the default F0F paths.
	 *
	 * @return  mixed  A string if the path and filename of the form to load is found, false otherwise.
	 *
	 */
	public function findFormFilename($source, $paths = array())
	{
       $paths = array_unique($paths);

		// Set up the suffixes to look into
		$suffixes = array();
		$temp_suffixes = $this->container->platform->getTemplateSuffixes();

		if (!empty($temp_suffixes))
		{
			foreach ($temp_suffixes as $suffix)
			{
				$suffixes[] = $suffix . '.xml';
			}
		}

		$suffixes[] = '.xml';

		// Look for all suffixes in all paths
		$result     = false;
		$filesystem = $this->container->filesystem;

		foreach ($paths as $path)
		{
			foreach ($suffixes as $suffix)
			{
				$filename = $path . '/' . $source . $suffix;

				if ($filesystem->fileExists($filename))
				{
					$result = $filename;
					break;
				}
			}

			if ($result)
			{
				break;
			}
		}

		return $result;
	}


}