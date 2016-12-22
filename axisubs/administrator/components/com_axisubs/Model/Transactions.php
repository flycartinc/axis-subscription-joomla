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
use JRegistry;
use JFactory;

class Transactions extends DataModel
{

	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		// Always load the Filters behaviour
		$this->addBehaviour('Filters');
		$this->addBehaviour('RelationFilters');

		$this->belongsTo('subscription', 'Subscription', 'subscription_id', 'axisubs_subscription_id');
		$this->hasOne('customer', 'Customers', 'user_id', 'user_id');

		// Not NULL fields which do accept 0 values should not be part of auto-checks
		$this->fieldsSkipChecks = [ 'user_id',
									'hash',
									'transaction_status',
									'payment_processor',
									'processor_status',
									'subscription_profile_id',
									'transaction_ref_id',
									'transaction_amount',
									'transaction_currency',
									'prepayment',
									'postpayment',
									'authorize',
									'params',									
									'created_on',
									'modified_on',
									'subscription_ref_id',
									'billing_cycle'];
	}

	/**
	 * Method to update the status of the subscription
	 * This cannot be accessed from the frontend
	 * */
	function updateState( $new_state_flag ) {
		if (empty ( $this->axisubs_transaction_id ))
			return;
		$app = JFactory::getApplication();
		$old_status = $this->status;
		// update only when the status is new
		if ($new_state_flag !== $old_status) {

			// first update the order
			$this->transaction_status = $new_state_flag;
			
			$this->store();

			$this->touch();
		}
	}

}