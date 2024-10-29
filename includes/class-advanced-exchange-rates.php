<?php

/**
 * Advanced Exchange Rates Plugin.
 *
 * @package Advanced Exchange Rates
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}

/**
 * Advanced Exchgane Rates Class Doc Comment
 *
 * @category Class
 * @package  Advanced Exchange Rates
 * @author   Karolína Vyskočilová <karolina@kybernaut.cz>
 */
class Advanced_Exchange_Rates
{
    const  ALREADY_BOOTSTRAPED = 1 ;
    const  DEPENDENCIES_UNSATISFIED = 2 ;
    const  NOT_CONNECTED = 3 ;
    /**
     * Filepath of main plugin file.
     *
     * @var string
     */
    public  $file ;
    /**
     * Plugin version.
     *
     * @var string
     */
    public  $version ;
    /**
     * Absolute plugin path.
     *
     * @var string
     */
    public  $plugin_path ;
    /**
     * Absolute plugin URL.
     *
     * @var string
     */
    public  $plugin_url ;
    /**
     * Absolute path to plugin includes dir.
     *
     * @var string
     */
    public  $includes_path ;
    /**
     * Flag to indicate the plugin has been boostrapped.
     *
     * @var bool
     */
    private  $_bootstrapped = false ;
    /**
     * Plugin name.
     *
     * @var string
     */
    private  $plugin_name = 'Advanced Exchange Rates for WooCommerce Multilingual' ;
    /**
     * Constructor.
     *
     * @param string $file    Filepath of main plugin file.
     * @param string $version Plugin version.
     */
    public function __construct( $file, $version )
    {
        $this->file = $file;
        $this->version = $version;
        // Path.
        $this->plugin_path = trailingslashit( plugin_dir_path( $this->file ) );
        $this->plugin_url = trailingslashit( plugin_dir_url( $this->file ) );
        $this->includes_path = $this->plugin_path . trailingslashit( 'includes' );
        // Updates.
        if ( version_compare( $version, get_option( 'aer_version' ), '>' ) ) {
            $this->run_updater( $version );
        }
    }
    
    /**
     * Handle updates.
     *
     * @param  string $new_version New version of the plugin.
     */
    private function run_updater( $new_version )
    {
        // Future updates should run here.
        // Update the plugin version.
        update_option( 'aer_version', $new_version );
    }
    
    /**
     * Maybe run the plugin.
     */
    public function maybe_run()
    {
        add_action( 'plugins_loaded', array( $this, 'bootstrap' ) );
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'init', array( $this, 'add_exchange_rate_services' ) );
        add_filter( 'plugin_action_links_' . plugin_basename( $this->file ), array( $this, 'plugin_action_links' ) );
    }
    
    /**
     * Bootstrap and try to check if plugin could run.
     *
     * @return void
     * @since 1.0.0
     */
    public function bootstrap()
    {
        try {
            if ( $this->_bootstrapped ) {
                throw new Exception( __( '%1$s in %2$s plugin can only be called once', 'advanced-exchange-rates' ), self::ALREADY_BOOTSTRAPED );
            }
            $this->_check_dependencies();
            $this->_bootstrapped = true;
            delete_option( 'aer_bootstrap_warning_message' );
        } catch ( Exception $e ) {
            if ( in_array( $e->getCode(), array( self::ALREADY_BOOTSTRAPED, self::DEPENDENCIES_UNSATISFIED ) ) ) {
                update_option( 'aer_bootstrap_warning_message', $e->getMessage() );
            }
            add_action( 'admin_notices', array( $this, 'show_bootstrap_warning' ) );
        }
    }
    
    public function show_bootstrap_warning()
    {
        $dependencies_message = get_option( 'aer_bootstrap_warning_message', '' );
        
        if ( !empty($dependencies_message) ) {
            ?>
			<div class="message error">
				<p>
					<strong><?php 
            echo  esc_html( $dependencies_message ) ;
            ?></strong>
				</p>
			</div>
			<?php 
        }
    
    }
    
    /**
     * Check dependencies.
     *
     * @throws Exception
     */
    protected function _check_dependencies()
    {
        if ( !function_exists( 'WC' ) ) {
            throw new Exception( sprintf( __( '%s requires WooCommerce to be activated', 'advanced-exchange-rates' ), $this->plugin_name ), self::DEPENDENCIES_UNSATISFIED );
        }
        if ( version_compare( WC()->version, '2.5', '<' ) ) {
            throw new Exception( sprintf( __( '%s requires WooCommerce version 2.5 or greater', 'advanced-exchange-rates' ), $this->plugin_name ), self::DEPENDENCIES_UNSATISFIED );
        }
        if ( !defined( 'ICL_SITEPRESS_VERSION' ) ) {
            throw new Exception( sprintf( __( '%s requires WPML to be activated', 'advanced-exchange-rates' ), $this->plugin_name ), self::DEPENDENCIES_UNSATISFIED );
        }
        
        if ( !defined( 'WCML_VERSION' ) ) {
            throw new Exception( sprintf( __( '%s requires WooCommerce Multilingual to be activated', 'advanced-exchange-rates' ), $this->plugin_name ), self::DEPENDENCIES_UNSATISFIED );
        } elseif ( version_compare( WCML_VERSION, '4.6.7', '<' ) ) {
            throw new Exception( sprintf( __( '%s requires WooCommerce Multilingual 4.6.7 in order to work, please update it.', 'advanced-exchange-rates' ), $this->plugin_name ), self::DEPENDENCIES_UNSATISFIED );
        }
    
    }
    
    /**
     * Load localisation files.
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain( 'advanced-exchange-rates', false, plugin_basename( $this->plugin_path ) . '/languages' );
    }
    
    /**
     * Add exchange rate services.
     */
    public function add_exchange_rate_services()
    {
        // WCML not installed & active.
        if ( !defined( 'WCML_VERSION' ) ) {
            return false;
        }
        global  $woocommerce_wpml ;
        require_once 'exchange-rates-services/exchangeratesapi.php';
        $woocommerce_wpml->multi_currency->exchange_rate_services->add_service( 'exchangeratesapi', new Advanced_Exchange_Rates_Exchange_Rates_API() );
    }
    
    /**
     * Return state of WCML multi currency mode.
     *
     * @return bool If multi currency mode enabled or not
     */
    public static function enabled()
    {
        // WCML not installed & active.
        if ( !defined( 'WCML_VERSION' ) ) {
            return false;
        }
        // If multi-currency mode not enabled.
        global  $woocommerce_wpml ;
        if ( 0 == $woocommerce_wpml->settings['enable_multi_currency'] ) {
            return false;
        }
        return true;
    }
    
    /**
     * Add relevant links to plugins page.
     *
     * @param array $links Plugin action links.
     *
     * @return array Plugin action links.
     */
    public function plugin_action_links( $links )
    {
        $plugin_links = array();
        if ( defined( 'WCML_VERSION' ) ) {
            $plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpml-wcml&tab=multi-currency' ) ) . '">' . esc_html__( 'Settings', 'advanced-exchange-rates' ) . '</a>';
        }
        $plugin_links[] = '<a href="https://kybernaut.cz/plugins/advanced-exchnage-rates">' . esc_html__( 'Docs', 'advanced-exchange-rates' ) . '</a>';
        return array_merge( $plugin_links, $links );
    }

}