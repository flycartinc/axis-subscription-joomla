<?php
/**
 * @package   Axisubs - Subscription Management System
 * @copyright Copyright (c)2016-2020 Sasi varna kumar / Flycart Technologies
 * @license   GNU General Public License version 3, or later
 */
namespace Flycart\Axisubs\Admin\Helper;

defined( '_JEXEC' ) or die( 'Restricted access' );

use JFactory;
use JText;

/**
 * Permission helper.
 */
class SubscriptionFactory{
	public static $instance = null;

	public function __construct($properties=null) {
	}

	/**
	 * get an instance
	 * @param array $config
	 * @return \Flycart\Axisubs\Admin\Helper\Permission
	 * * */
	public static function getInstance(array $config = array())
	{
		if (!self::$instance)
		{
			self::$instance = new self($config);
		}

		return self::$instance;
	}

	/**
	 * Creates or gets the next renewal record for the current subscription supplied
	 * @param 	int 	$subscription 	subscription record or a subscription id
	 * @return 	bool/subscription object	
	 * */
	public function getNextRenewal( $subscription ){
		
		$current_sub = '';
		if ( $subscription instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $subscription instanceof \Flycart\Axisubs\Site\Model\Subscriptions ) {
			$current_sub = $subscription;
		}else{
			$subscription_id = (int) $subscription ;
			if ($subscription_id > 0) {
				$sub = $this->getModel('Subscription');
				$sub->load( $subscription_id ) ;
				if ( $sub->axisubs_subscription_id > 0 ) {
					$current_sub = $sub;
				}
			}
		}

		// validate if a valid subscription record is available
		if ( !($current_sub instanceof \Flycart\Axisubs\Admin\Model\Subscriptions || $current_sub instanceof \Flycart\Axisubs\Site\Model\Subscriptions) ) {
			return false;
		}

		// first check if there is a pending subscription in this state alredy exists
		$subs_model = $this->getModel('Subscriptions');
		$latest_pending_sub = $subs_model->user_id( $current_sub->user_id )
								->plan_id( $current_sub->plan_id )
								->ref_subscription_id( $current_sub->ref_subscription_id )
								->status( 'P' ) // active and future subscriptions
								->get()
								->sortByDesc( 'current_term_end' )
								->first();
		if ( count($latest_pending_sub->transaction) == 0 ) {
			// no transaction record associated yet, then this is the subscription to be processed
			return $latest_pending_sub ;
		}

		// based on current subscription status create the next renewal record and return it.
		if ( $current_sub->isRecurring() ) {
			$subs_data = $current_sub->getData();
			$new_subscription_data = [  'plan_id'				=>	$subs_data['plan_id'],
										'user_id'				=>	$subs_data['user_id'],
										'ref_subscription_id' 	=>  $subs_data['axisubs_subscription_id'],
										'plan_quantity'			=>	$subs_data['plan_quantity'] ,
										'currency_code' 		=> 	$subs_data['currency_code'],
										'currency_value' 		=> 	$subs_data['currency_value'],
										'language'				=>  $subs_data['language'] 
										];

			$new_subscription = $subs_model->getClone();

			$new_subscription->save( $new_subscription_data );

			$new_subscription->currency_code	=	$subs_data['currency_code'];
			$new_subscription->currency_value	=	$subs_data['currency_value'];
			$new_subscription->language 		=	$subs_data['language'];
			$new_subscription->save();
			$new_subscription->markPending();
			
			return $new_subscription;
		}

	}
}
