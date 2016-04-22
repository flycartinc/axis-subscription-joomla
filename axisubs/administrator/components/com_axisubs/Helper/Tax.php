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
 * Performs tax calculations and loads tax rates
 * */
class Tax {

	/**
	 * Precision.
	 *
	 * @var int
	 */
	public static $precision;

	/**
	 * Round at subtotal.
	 *
	 * @var bool
	 */
	public static $round_at_subtotal;

	/**
	 * Load options.
	 *
	 * @access public
	 */
	public static function init() {
		self::$precision         = 4;
		self::$round_at_subtotal = 'yes';
	}

	/**
	 * Calculate tax for a line.
	 * @param  float  $price              Price to calc tax on
	 * @param  array  $rates              Rates to apply
	 * @param  boolean $price_includes_tax Whether the passed price has taxes included
	 * @param  boolean $suppress_rounding  Whether to suppress any rounding from taking place
	 * @return array                      Array of rates + prices after tax
	 */
	public static function calc_tax( $price, $rates, $price_includes_tax = false, $suppress_rounding = false ) {
		// Work in pence to X precision
		$price = self::precision( $price );

		if ( $price_includes_tax ) {
			$taxes = self::calc_inclusive_tax( $price, $rates );
		} else {
			$taxes = self::calc_exclusive_tax( $price, $rates );
		}

		// Round to precision
		if ( ! self::$round_at_subtotal && ! $suppress_rounding ) {
			$taxes = array_map( 'round', $taxes ); // Round to precision
		}

		// Remove precision
		$price     = self::remove_precision( $price );
		$taxes     = array_map( array( __CLASS__, 'remove_precision' ), $taxes );

		Axisubs::plugin()->event('AfterCalculateTax', 
								array( &$taxes, $price, $rates, $price_includes_tax, $suppress_rounding ) );
		return $taxes;
	}

	/**
	 * Calculate the shipping tax using a passed array of rates.
	 *
	 * @param   float		Price
	 * @param	array		Taxation Rate
	 * @return  array
	 */
	public static function calc_shipping_tax( $price, $rates ) {
		return self::calc_exclusive_tax( $price, $rates );
	}

	/**
	 * Multiply cost by pow precision.
	 * @param  float $price
	 * @return float
	 */
	private static function precision( $price ) {
		return $price * ( pow( 10, self::$precision ) );
	}

	/**
	 * Divide cost by pow precision.
	 * @param  float $price
	 * @return float
	 */
	private static function remove_precision( $price ) {
		return $price / ( pow( 10, self::$precision ) );
	}

	/**
	 * Round to precision.
	 *
	 * Filter example: to return rounding to .5 cents you'd use:
	 *
	 * public function euro_5cent_rounding( $in ) {
	 *      return round( $in / 5, 2 ) * 5;
	 * }
	 * add_filter( 'woocommerce_tax_round', 'euro_5cent_rounding' );
	 * @return double
	 */
	public static function round( $in ) {
		return round( $in, self::$precision ) ;
	}

	/**
	 * Calc tax from inclusive price.
	 *
	 * @param  float $price
	 * @param  array $rates
	 * @return array
	 */
	public static function calc_inclusive_tax( $price, $rates ) {
		$taxes = array();

		$regular_tax_rates = $compound_tax_rates = 0;

		foreach ( $rates as $key => $rate )
			if ( $rate['compound'] == 'yes' )
				$compound_tax_rates = $compound_tax_rates + $rate['rate'];
			else
				$regular_tax_rates  = $regular_tax_rates + $rate['rate'];

		$regular_tax_rate 	= 1 + ( $regular_tax_rates / 100 );
		$compound_tax_rate 	= 1 + ( $compound_tax_rates / 100 );
		$non_compound_price = $price / $compound_tax_rate;

		foreach ( $rates as $key => $rate ) {
			if ( ! isset( $taxes[ $key ] ) )
				$taxes[ $key ] = 0;

			$the_rate      = $rate['rate'] / 100;

			if ( $rate['compound'] == 'yes' ) {
				$the_price = $price;
				$the_rate  = $the_rate / $compound_tax_rate;
			} else {
				$the_price = $non_compound_price;
				$the_rate  = $the_rate / $regular_tax_rate;
			}

			$net_price       = $price - ( $the_rate * $the_price );
			$tax_amount      = $price - $net_price;
			//$taxes[ $key ]   += apply_filters( 'woocommerce_price_inc_tax_amount', $tax_amount, $key, $rate, $price );
			$taxes[ $key ]   += $tax_amount ; 
		}

		return $taxes;
	}

	/**
	 * Calc tax from exclusive price.
	 *
	 * @param  float $price
	 * @param  array $rates
	 * @return array
	 */
	public static function calc_exclusive_tax( $price, $rates ) {
		$taxes = array();

		if ( $rates ) {
			// Multiple taxes
			foreach ( $rates as $key => $rate ) {

				if ( $rate['compound'] == 'yes' )
					continue;

				$tax_amount = $price * ( $rate['rate'] / 100 );

				// ADVANCED: Allow third parties to modify this rate
				$tax_amount =  $tax_amount ; 

				// Add rate
				if ( ! isset( $taxes[ $key ] ) )
					$taxes[ $key ] = $tax_amount;
				else
					$taxes[ $key ] += $tax_amount;
			}

			$pre_compound_total = array_sum( $taxes );

			// Compound taxes
			foreach ( $rates as $key => $rate ) {

				if ( $rate['compound'] == 'no' )
					continue;

				$the_price_inc_tax = $price + ( $pre_compound_total );

				$tax_amount = $the_price_inc_tax * ( $rate['rate'] / 100 );

				// ADVANCED: Allow third parties to modify this rate
				$tax_amount = $tax_amount ;

				// Add rate
				if ( ! isset( $taxes[ $key ] ) )
					$taxes[ $key ] = $tax_amount;
				else
					$taxes[ $key ] += $tax_amount;
			}
		}

		return $taxes;
	}

	/**
	 * Searches for all matching country/state/postcode tax rates.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function find_rates( $args = array() ) {
		/*$args = array(
			'country'   => '',
			'state'     => '',
			'city'      => '',
			'postcode'  => '',
			'tax_class' => ''
		) ;*/

		extract( $args, EXTR_SKIP );

		if ( ! $country ) {
			return array();
		}

		//$postcode          = wc_clean( $postcode );
		$valid_postcodes   = self::_get_wildcard_postcodes( $postcode );
		
		$matched_tax_rates = self::get_matched_tax_rates( $country, $state, $postcode, $city, $tax_class, $valid_postcodes );
		
		return $matched_tax_rates;
	}

	/**
	 * Searches for all matching country/state/postcode tax rates.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function find_shipping_rates( $args = array() ) {
		$rates          = self::find_rates( $args );
		$shipping_rates = array();

		if ( is_array( $rates ) ) {
			foreach ( $rates as $key => $rate ) {
				if ( 'yes' === $rate['shipping'] ) {
					$shipping_rates[ $key ] = $rate;
				}
			}
		}

		return $shipping_rates;
	}

	/**
	 * Loop through a set of tax rates and get the matching rates (1 per priority).
	 *
	 * @param  string $country
	 * @param  string $state
	 * @param  string $postcode
	 * @param  string $city
	 * @param  string $tax_class
	 * @param  string[] $valid_postcodes
	 * @return array
	 */
	private static function get_matched_tax_rates( $country, $state, $postcode, $city, $tax_class, $valid_postcodes ) {

		$db = JFactory::getDbo();

		$valid_postcodes = array_map( array( $db, 'q'), $valid_postcodes );		

		$match_country   = $db->q( strtoupper(  $country  ) );
		$match_state     = $db->q( strtoupper(  $state ) );
		$match_tax_class = $db->q( $tax_class );
		$match_city      = $db->q( strtoupper( $city ) );
		
		$query = "
			SELECT tax_rates.*, tax_rates.axisubs_taxrate_id as tax_rate_id
			FROM #__axisubs_taxrates as tax_rates
			LEFT OUTER JOIN #__axisubs_taxratelocations as locations ON tax_rates.axisubs_taxrate_id = locations.tax_rate_id
			LEFT OUTER JOIN #__axisubs_taxratelocations as locations2 ON tax_rates.axisubs_taxrate_id = locations2.tax_rate_id
			WHERE tax_rate_country IN ( {$match_country}, '' )
			AND tax_rate_state IN ( {$match_state}, '' )
			AND tax_rate_class = {$match_tax_class}
			AND (
				locations.location_type IS NULL
				OR (
					locations.location_type = 'postcode'
					AND locations.location_code IN (" . implode( ",", $valid_postcodes ) . ")
					AND (
						locations2.location_type = 'city' AND locations2.location_code = {$match_city}
						OR 0 = (
							SELECT COUNT(*) FROM #__axisubs_taxratelocations as sublocations
							WHERE sublocations.location_type = 'city'
							AND sublocations.tax_rate_id = tax_rates.axisubs_taxrate_id
						)
					)
				)
				OR (
					locations.location_type = 'city'
					AND locations.location_code = {$match_city}
					AND 0 = (
							SELECT COUNT(*) FROM #__axisubs_taxratelocations as sublocations
							WHERE sublocations.location_type = 'postcode'
							AND sublocations.tax_rate_id = tax_rates.axisubs_taxrate_id
						)
				)
			)
			GROUP BY tax_rates.axisubs_taxrate_id
			ORDER BY tax_rate_priority, tax_rate_order
		" ;

		$db->setQuery ( $query );

		$found_rates   = $db->loadObjectList();

		$matched_tax_rates = array();
		$found_priority    = array();

		foreach ( $found_rates as $found_rate ) {
			if( !isset($found_rate->tax_rate_priority) ) {
				continue;
			}
			/*if ( in_array( $found_rate->tax_rate_priority, $found_priority ) ) {
				continue;
			}*/

			$matched_tax_rates[ $found_rate->tax_rate_id ] = array(
				'rate'     => $found_rate->tax_rate,
				'label'    => $found_rate->tax_rate_name,
				'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
				'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
			);

			$found_priority[] = $found_rate->tax_rate_priority;
		}

		Axisubs::plugin()->event('AfterMatchTaxRates', 
								array( &$matched_tax_rates, $country, $state, $postcode, $city, $tax_class ) );

		return $matched_tax_rates;
	}

	/**
	 * Get the customer tax location based on their status and the current page.
	 *
	 * Used by get_rates(), get_shipping_rates().
	 *
	 * @param  $tax_class string Optional, passed to the filter for advanced tax setups.
	 * @return array
	 */
	public static function get_tax_location( $tax_class = '' ) {
		$location = array();
		$config = Axisubs::config();

		$session = JFactory::getSession();

		if ( $session->has('customer_billing_country','axisubs') ) {
			
			$country_id = $session->get('customer_billing_country', $config->get('country_id','') ,'axisubs');
			$zone_id 	= $session->get('customer_billing_state', $config->get('zone_id','') ,'axisubs');
			$zip 		= $session->get('customer_billing_zip', $config->get('store_zip','') ,'axisubs');
			$city 		= $session->get('customer_billing_city', $config->get('store_city','') ,'axisubs');
			
			$location = array( $country_id, $zone_id, $zip, $city );

		} else {
			$location = array(
				$config->get('country_id'),
				$config->get('zone_id'),
				$config->get('store_zip'),
				$config->get('store_city')
			);	
		}		
		
		/*	
		if ( ! empty( WC()->customer ) ) {
			$location = WC()->customer->get_taxable_address();
		} elseif ( wc_prices_include_tax() || 'base' === get_option( 'woocommerce_default_customer_address' ) || 'base' === get_option( 'woocommerce_tax_based_on' ) ) {
			
		}*/

		return $location;
	}

	/**
	 * Get's an array of matching rates for a tax class.
	 * @param string $tax_class
	 * @return  array
	 */
	public static function get_rates( $tax_class = '' ) {

		$location          = self::get_tax_location( $tax_class );

		$matched_tax_rates = array();

		if ( sizeof( $location ) === 4 ) {
			list( $country, $state, $postcode, $city ) = $location;

			$matched_tax_rates = self::find_rates( array(
				'country' 	=> $country,
				'state' 	=> $state,
				'postcode' 	=> $postcode,
				'city' 		=> $city,
				'tax_class' => $tax_class
			) );
		}

		return $matched_tax_rates;
	}

	/**
	 * Get's an array of matching rates for the shop's base country.
	 *
	 * @param   string	Tax Class
	 * @return  array
	 */
	public static function get_base_tax_rates( $tax_class = '' ) {
		$config = Axisubs::config();

		return self::find_rates( array(
			'country' 	=> $config->get('country_id'),
			'state' 	=> $config->get('zone_id'),
			'postcode' 	=> $config->get('store_zip'),
			'city' 		=> $config->get('store_city'),
			'tax_class' => $tax_class
		) , $tax_class );
	}

	/**
	 * Alias for get_base_tax_rates().
	 *
	 * @deprecated 2.3
	 * @param   string	Tax Class
	 * @return  array
	 */
	public static function get_shop_base_rate( $tax_class = '' ) {
		return self::get_base_tax_rates( $tax_class );
	}

	/**
	 * Gets an array of matching shipping tax rates for a given class.
	 *
	 * @param   string	Tax Class
	 * @return  mixed
	 */
	public static function get_shipping_tax_rates( $tax_class = null ) {
		// See if we have an explicitly set shipping tax class
		if ( empty($tax_class ) ) {
			$tax_class = 'standard';
		}

		$location          = self::get_tax_location( $tax_class );
		$matched_tax_rates = array();

		if ( sizeof( $location ) === 4 ) {
			list( $country, $state, $postcode, $city ) = $location;

			if ( ! is_null( $tax_class ) ) {
				// This will be per item shipping
				$matched_tax_rates = self::find_shipping_rates( array(
					'country' 	=> $country,
					'state' 	=> $state,
					'postcode' 	=> $postcode,
					'city' 		=> $city,
					'tax_class' => $tax_class
				) );

			} 

			// Get standard rate if no taxes were found
			if ( ! sizeof( $matched_tax_rates ) ) {
				$matched_tax_rates = self::find_shipping_rates( array(
					'country' 	=> $country,
					'state' 	=> $state,
					'postcode' 	=> $postcode,
					'city' 		=> $city
				) );
			}
		}

		return $matched_tax_rates;
	}

	/**
	 * Return true/false depending on if a rate is a compound rate.
	 *
	 * @param   int		key
	 * @return  bool
	 */
	public static function is_compound( $key ) {
		if (empty($key ))
			return false;

		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('tax_rate_compound') 
			 -> from('#__axisubs_taxrates a')
		     -> where('tax_rate_id ='.$db->q($key));
		$db->setQuery();
		$res = $db->loadResult();

		return ($res) ? true : false;
	}

	/**
	 * Return a given rates label.
	 *
	 * @param mixed $key_or_rate Tax rate ID, or the db row itself in object format
	 * @return  string
	 */
	public static function get_rate_label( $key_or_rate ) {

		if ( is_object( $key_or_rate ) ) {
			$key       = $key_or_rate->tax_rate_id;
			$rate_name = $key_or_rate->tax_rate_name;
		} else {
			$key       = $key_or_rate;
			
			$db = JFactory::getDbo();
			$qry = $db->getQuery(true);
			$qry -> select('tax_rate_name') 
				 -> from('#__axisubs_taxrates')
			     -> where('tax_rate_id ='.$db->q($key));
			$db->setQuery();
			$rate_name = $db->loadResult();
		}

		return $rate_name;
	}

	/**
	 * Return a given rates percent.
	 *
	 * @param mixed $key_or_rate Tax rate ID, or the db row itself in object format
	 * @return  string
	 */
	public static function get_rate_percent( $key_or_rate ) {

		if ( is_object( $key_or_rate ) ) {
			$key      = $key_or_rate->tax_rate_id;
			$tax_rate = $key_or_rate->tax_rate;
		} else {
			$key      = $key_or_rate;

			$db = JFactory::getDbo();
			$qry = $db->getQuery(true);
			$qry -> select('tax_rate') 
				 -> from('#__axisubs_taxrates')
			     -> where('tax_rate_id ='.$db->q($key));
			$db->setQuery();
			$tax_rate = $db->loadResult();
		}

		return floatval( $tax_rate ) ;
	}

	/**
	 * Get a rates code. Code is made up of COUNTRY-STATE-NAME-Priority. E.g GB-VAT-1, US-AL-TAX-1.
	 *
	 * @access public
	 * @param mixed $key_or_rate Tax rate ID, or the db row itself in object format
	 * @return string
	 */
	public static function get_rate_code( $key_or_rate ) {

		if ( is_object( $key_or_rate ) ) {
			$key  = $key_or_rate->tax_rate_id;
			$rate = $key_or_rate;
		} else {
			$key  = $key_or_rate;

			$db = JFactory::getDbo();
			$qry = $db->getQuery(true);
			$qry -> select('*') 
				 -> from('#__axisubs_taxrates')
			     -> where('tax_rate_id ='.$db->q($key));
			$db->setQuery();
			$rate = $db->loadAssoc();
		}

		$code_string = '';

		if ( null !== $rate ) {
			$code   = array();
			$code[] = $rate->tax_rate_country;
			$code[] = $rate->tax_rate_state;
			$code[] = $rate->tax_rate_name ? $rate->tax_rate_name : 'TAX';
			$code[] = absint( $rate->tax_rate_priority );
			$code_string = strtoupper( implode( '-', array_filter( $code ) ) );
		}

		return $code_string;
	}

	/**
	 * Round tax lines and return the sum.
	 *
	 * @param   array
	 * @return  float
	 */
	public static function get_tax_total( $taxes ) {
		return array_sum( array_map( array( __CLASS__, 'round' ), $taxes ) );
	}

	/**
	 * Get store tax classes.
	 * @return array
	 */
	public static function get_tax_classes() {
		return array_filter( array_map( 'trim', explode( "\n", 'standard' ) ) );
	}

	/**
	 * format the postcodes.
	 * @param  string $postcode
	 * @return string
	 */
	private static function format_tax_rate_postcode( $postcode ) {
		return strtoupper( trim( $postcode ) );
	}

	/**
	 * format the city.
	 * @param  string $city
	 * @return string
	 */
	private static function format_tax_rate_city( $city ) {
		return strtoupper( trim( $city ) );
	}

	/**
	 * format the state.
	 * @param  string $state
	 * @return string
	 */
	private static function format_tax_rate_state( $state ) {
		$state = strtoupper( $state );
		return $state === '*' ? '' : $state;
	}

	/**
	 * format the country.
	 * @param  string $country
	 * @return string
	 */
	private static function format_tax_rate_country( $country ) {
		$country = strtoupper( $country );
		return $country === '*' ? '' : $country;
	}

	/**
	 * format the tax rate name.
	 * @param  string $name
	 * @return string
	 */
	private static function format_tax_rate_name( $name ) {
		return $name ;
	}

	/**
	 * format the rate.
	 * @param  double $rate
	 * @return string
	 */
	private static function format_tax_rate( $rate ) {
		return number_format( (double) $rate, 4, '.', '' );
	}

	/**
	 * format the priority.
	 * @param  string $priority
	 * @return int
	 */
	private static function format_tax_rate_priority( $priority ) {
		return absint( $priority );
	}

	/**
	 * format the class.
	 * @param  string $class
	 * @return string
	 */
	public static function format_tax_rate_class( $class ) {
		$sanitized_classes = array_map( 'sanitize_title', self::get_tax_classes() );
		if ( ! in_array( $class, $sanitized_classes ) ) {
			$class = '';
		}
		return $class === 'standard' ? '' : $class;
	}

	/**
	 * Prepare and format tax rate for DB insertion.
	 * @param  array $tax_rate
	 * @return array
	 */
	private static function prepare_tax_rate( $tax_rate ) {
		foreach ( $tax_rate as $key => $value ) {
			if ( method_exists( __CLASS__, 'format_' . $key ) ) {
				$tax_rate[ $key ] = call_user_func( array( __CLASS__, 'format_' . $key ), $value );
			}
		}
		return $tax_rate;
	}

	/**
	 * Get tax rate.
	 *
	 * Internal use only.
	 *
	 * @since 2.5.0
	 * @access private
	 *
	 * @param  int $tax_rate_id
	 *
	 * @return array
	 */
	public static function _get_tax_rate( $tax_rate_id ) {
		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('*') 
			 -> from('#__axisubs_taxrates')
		     -> where('tax_rate_id ='.$db->q($key));
		$db->setQuery();
		$rate = $db->loadAssoc();

		return $rate;
	}

	/**
	 * Update a tax rate.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param int $tax_rate_id
	 * @param array $tax_rate
	 */
	public static function _update_tax_rate( $tax_rate_id, $tax_rate ) {

		$tax_rate_id = absint( $tax_rate_id );

		$db = JFactory::getDbo();
		$qry = $db->getQuery(true);
		$qry -> select('*') 
			 -> from('#__axisubs_taxrates')
		     -> where('tax_rate_id ='.$db->q($key));
		$db->setQuery();
		$rate = $db->loadAssoc();
		/*
		$wpdb->update(
			$wpdb->prefix . "woocommerce_tax_rates",
			self::prepare_tax_rate( $tax_rate ),
			array(
				'tax_rate_id' => $tax_rate_id
			)
		);

		WC_Cache_Helper::incr_cache_prefix( 'taxes' );

		do_action( 'woocommerce_tax_rate_updated', $tax_rate_id, $tax_rate ); */
		/////////////////////////////////////
	}

	/**
	 * Delete a tax rate from the database.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  int $tax_rate_id
	 */
	public static function _delete_tax_rate( $tax_rate_id ) {
		
		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$tr_model = $container->factory->model('TaxRates');	
		$trloc_model = $container->factory->model('TaxRatelocations');	

		$tr_model->load($tax_rate_id)->delete();
		$trloc_model->load( array('tax_rate_id'=> $tax_rate_id ) )->delete();

	}

	/**
	 * Update postcodes for a tax rate in the DB.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  int $tax_rate_id
	 * @param  string $postcodes String of postcodes separated by ; characters
	 * @return string
	 */
	public static function _update_tax_rate_postcodes( $tax_rate_id, $postcodes ) {
		if ( ! is_array( $postcodes ) ) {
			$postcodes = explode( ';', $postcodes );
		}
		$postcodes = array_filter( array_diff( array_map( array( __CLASS__, 'format_tax_rate_postcode' ), $postcodes ), array( '*' ) ) );
		$postcodes = self::_get_expanded_numeric_ranges_from_array( $postcodes );

		self::_update_tax_rate_locations( $tax_rate_id, $postcodes, 'postcode' );
	}

	/**
	 * Update cities for a tax rate in the DB.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  int $tax_rate_id
	 * @param  string $cities
	 * @return string
	 */
	public static function _update_tax_rate_cities( $tax_rate_id, $cities ) {
		if ( ! is_array( $cities ) ) {
			$cities = explode( ';', $cities );
		}
		$cities = array_filter( array_diff( array_map( array( __CLASS__, 'format_tax_rate_city' ), $cities ), array( '*' ) ) );

		self::_update_tax_rate_locations( $tax_rate_id, $cities, 'city' );
	}

	/**
	 * Updates locations (postcode and city).
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  int $tax_rate_id
	 * @param string $type
	 * @return string
	 */
	private static function _update_tax_rate_locations( $tax_rate_id, $values, $type ) {

		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$trloc_model = $container->factory->model('TaxRatelocations');	
		
		if ( !empty($tax_rate_id) ) {
			$trloc_model->tax_rate_id( $tax_rate_id )->delete();
		}

		if ( sizeof( $values ) > 0 ) {
			$db = JFactory::getDbo();

			$sql = "( '" . implode( "', $tax_rate_id, '" . $db->q( $type ) . "' ),"
				."( '", array_map( array($db, 'q') , $values ) ) . "', $tax_rate_id, '" . $db->q( $type ) . "' )";

			$query = "INSERT INTO #__axisubs_taxratelocations ( location_code, tax_rate_id, location_type ) VALUES " ;
			$query .= $sql;

			$db = JFactory::getDbo();
			$db->setQuery($query);
			$db->execute();
		}

	}

	/**
	 * Expands ranges in an array (used for zipcodes). e.g. 101-105 would expand to 101, 102, 103, 104, 105.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  array  $values array of values
	 * @return array expanded values
	 */
	private static function _get_expanded_numeric_ranges_from_array( $values = array() ) {
		$expanded = array();
		foreach ( $values as $value ) {
			if ( strstr( $value, '-' ) ) {
				$parts = array_map( 'absint', array_map( 'trim', explode( '-', $value ) ) );

				for ( $expanded_value = $parts[0]; $expanded_value <= $parts[1]; $expanded_value ++ ) {
					if ( strlen( $expanded_value ) < strlen( $parts[0] ) ) {
						$expanded_value = str_pad( $expanded_value, strlen( $parts[0] ), "0", STR_PAD_LEFT );
					}
					$expanded[] = $expanded_value;
				}
			} else {
				$expanded[] = trim( $value );
			}
		}
		return array_filter( $expanded );
	}

	/**
	 * Get postcode wildcards in array format.
	 *
	 * Internal use only.
	 *
	 * @since 2.3.0
	 * @access private
	 *
	 * @param  string  $postcode array of values
	 * @return string[] Array of postcodes with wildcards
	 */
	private static function _get_wildcard_postcodes( $postcode ) {
		$postcodes         = array( '*', strtoupper( $postcode ), strtoupper( $postcode ) . '*' );
		$postcode_length   = strlen( $postcode );
		$wildcard_postcode = strtoupper( $postcode );

		for ( $i = 0; $i < $postcode_length; $i ++ ) {
			$wildcard_postcode = substr( $wildcard_postcode, 0, -1 );
			$postcodes[] = $wildcard_postcode . '*';
		}
		return $postcodes;
	}

	/**
	 * Used by admin settings page.
	 *
	 * @param string $tax_class
	 *
	 * @return array|null|object
	 */
	public static function get_rates_for_tax_class( $tax_class ) {

		// Get all the rates and locations. Snagging all at once should significantly cut down on the number of queries.
		$container = \FOF30\Container\Container::getInstance('com_axisubs');
		$tr_model = $container->factory->model('TaxRates');	
		$trloc_model = $container->factory->model('TaxRatelocations');	

		$arates     = $tr_model->tax_rate_class($tax_class)
							->sortBy('tax_rate_order')
							->get()
							->toArray();

		$locations = $trloc_model->get()->toArray();

		$rates = array();
		if ( ! empty( $arates ) ) {
			// Set the rates keys equal to their ids.
			foreach ($arates as $arate) {
				$rates[$arate['axisubs_taxrate_id']] = $arate ;
			}
		}

		// Drop the locations into the rates array.
		foreach ( $locations as $location ) {
			// Don't set them for unexistent rates.
			if ( ! isset( $rates[ $location->tax_rate_id ] ) ) {
				continue;
			}
			// If the rate exists, initialize the array before appending to it.
			if ( ! isset( $rates[ $location->tax_rate_id ]->{$location->location_type} ) ) {
				$rates[ $location->tax_rate_id ]->{$location->location_type} = array();
			}
			$rates[ $location->tax_rate_id ]->{$location->location_type}[] = $location->location_code;
		}

		return $rates;
	}
}

Tax::init();