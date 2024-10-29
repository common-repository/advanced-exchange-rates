<?php

/**
 * Plugin Name: Advanced Exchange Rates for WooCommerce Multilingual
 * Plugin URI: https://kybernaut.cz
 * Description: Adds a free currency exchange rate service based on European Central Bank (via exchangeratesapi.io).
 * Version: 1.0.6
 * Author: Karolína Vyskočilová
 * Author URI: https://kybernaut.cz
 * Copyright: © 2021 Karolína Vyskočilová
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: advanced-exchange-rates
 * Domain Path: /languages
 * WC tested up to: 5.1.0
 * WC requires at least: 2.6
 *
 * @package Advanced Exchange Rates
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

define( 'AER_VERSION', '1.0.6' );
// Let Freemius deactivate the free version.

if ( function_exists( 'advanced_exchange_rates_fs' ) ) {
    advanced_exchange_rates_fs()->set_basename( false, __FILE__ );
    return;
}

// Run Freemius integration snippet.

if ( !function_exists( 'advanced_exchange_rates_fs' ) ) {
    /**
     * Create a helper function for easy SDK access.
     *
     * @return object advanced_exchange_rates_fs
     * @since 1.0.0
     */
    function advanced_exchange_rates_fs()
    {
        global  $advanced_exchange_rates_fs ;
        
        if ( !isset( $advanced_exchange_rates_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $advanced_exchange_rates_fs = fs_dynamic_init( array(
                'id'             => '3314',
                'slug'           => 'advanced-exchange-rates',
                'type'           => 'plugin',
                'public_key'     => 'pk_cead17961f3c6ab0eea4ebae3428c',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'first-path' => 'plugins.php',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $advanced_exchange_rates_fs;
    }
    
    // Init Freemius.
    advanced_exchange_rates_fs();
    // Signal that SDK was initiated.
    do_action( 'advanced_exchange_rates_fs_loaded' );
}

/**
 * Return instance of Advanced_Exchange_Rates.
 *
 * @return Advanced_Exchange_Rates
 */
function advanced_exchange_rates()
{
    static  $plugin ;
    
    if ( !isset( $plugin ) ) {
        require_once 'includes/class-advanced-exchange-rates.php';
        $plugin = new Advanced_Exchange_Rates( __FILE__, AER_VERSION );
    }
    
    return $plugin;
}

advanced_exchange_rates()->maybe_run();