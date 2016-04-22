<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use CommerceGuys\Tax\TaxableInterface;
use JFactory;
use JText;

/**
 * Taxable helper.
 */
class Taxable implements TaxableInterface {
	
	public function isPhysical(){
		return false;
	}
}
