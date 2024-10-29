<?php
/**
 * New rate service for Exchange rates API
 *
 * @package Advanced Exchange Rates
 */

/**
 * Class Advanced_Exchange_Rates_Echange_Rates_API
 *
 * Get rates from European Central Bank (https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html) via free & opensource service Exchange Rates API (https://exchangeratesapi.io/) by Madis Väin (https://github.com/madisvain)
 * 
 * @category Class
 * @author Karolína Vyskočilová <karolina@kybernaut.cz>
 */
class Advanced_Exchange_Rates_Exchange_Rates_API extends WCML_Exchange_Rate_Service {

	private $id      = 'exchangeratesapi';
	private $name    = 'European Central Bank (exchangeratesapi.io)';
	private $url     = 'https://exchangeratesapi.io/';
	private $api_url = 'https://api.exchangeratesapi.io/latest?base=%1$s&symbols=%2$s';

	protected $requires_key = false;

	public function __construct() {
		parent::__construct( $this->id, $this->name, $this->api_url, $this->url );
	}

	/**
	 * @param string $from
	 * @param  array  $tos
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_rates( $from, $tos ) {

		parent::clear_last_error();
		$rates = array();

		$url = sprintf( $this->api_url, $from, implode( ',', $tos ) );

		$data = wp_safe_remote_get( $url );

		if ( is_wp_error( $data ) ) {

			$http_error = implode( "\n", $data->get_error_messages() );
			parent::save_last_error( $http_error );
			throw new Exception( $http_error );

		}

		$json = json_decode( $data['body'] );

		if ( ! isset( $json->base, $json->rates ) ) {
			if ( isset( $json->error->info ) ) {
				$error = $json->error->info;
			} else {
				$error = __( 'Cannot get exchange rates. Connection failed.', 'woocommerce-multilingual' );
			}
			parent::save_last_error( $error );
			throw new Exception( $error );
		}

		foreach ( $json->rates as $to => $rate ) {
			$rates[ $to ] = round( $rate, WCML_Exchange_Rates::DIGITS_AFTER_DECIMAL_POINT );
		}

		return $rates;

	}

}
