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

class SubscriptionInfos extends DataModel {
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [ 'billing_first_name','billing_last_name','billing_email','billing_phone',
									'billing_company','vat_number','billing_address1',
									'billing_address2','billing_city','billing_state',
									'billing_zip','billing_country','created_on',
									'created_from_ip','params','notes'];
	}
}