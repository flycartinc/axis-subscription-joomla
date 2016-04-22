<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Form\Field;

defined('_JEXEC') or die;

use FOF30\Form\Field\Radio;
use Flycart\Axisubs\Admin\Helper\Axisubs;
use SimpleXMLElement;

defined('_JEXEC') or die;

class NewExistingCustomer extends Radio
{

	public function getInput()
	{
		$html = '';
		$html .= '<script>

				</script>';
		$html .= parent::getInput();
		return $html;
	}
}