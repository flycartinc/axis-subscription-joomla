<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Form\Field;

defined('_JEXEC') or die;

use FOF30\Form\Field\Text;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use SimpleXMLElement;

defined('_JEXEC') or die;

class Price extends Text
{
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$x = parent::setup($element, $value, $group);

		static $currencyPosition = null;
		static $currencySymbol = null;

		if (is_null($currencyPosition))
		{
			$currencyPosition = Axisubs::currency()->getSymbolPosition();
			$currencySymbol = Axisubs::currency()->getSymbol();
		}

		if ($currencyPosition == 'pre')
		{
			$this->form->setFieldAttribute($this->fieldname, 'prepend_text', $currencySymbol);
		}
		else
		{
			$this->form->setFieldAttribute($this->fieldname, 'append_text', $currencySymbol);
		}

		return $x;
	}
}