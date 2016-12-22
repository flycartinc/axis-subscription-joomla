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
use JText;

defined('_JEXEC') or die;

class Name extends Text
{
	protected function getInput()
	{
		$this->name = 'first_name';
		$this->hint = 'AXISUBS_ADDRESS_FIRST_NAME';
		$this->class = 'span4';
		$html = parent::getInput();

		$html .= '  <input type="text" value="" id="last_name" name="last_name" '
				.' placeholder ="'.JText::_('AXISUBS_ADDRESS_LAST_NAME') .'" '
				.' class="'.$this->class.'" > ' ; 
		return $html .= '';
	}
}