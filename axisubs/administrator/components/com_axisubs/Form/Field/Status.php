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

class Status extends Text
{	
	protected $type = 'Status';

	public function getInput() {
		return '';
	}

	public function getStatic() {
		return '';
	}

	public function getRepeatable() {

		$status_helper  = Axisubs::status();
		$cls = $status_helper->get_label( $this->item->status );
		$html = '<span class=" axisubs-status label label-'.$cls.'">'.
					$status_helper->get_text( $this->item->status )
				.'</span>';
		return $html;
	}
}