<?php
/**
 * Lemonway
 *
 * @package   lemonway
 *
 * Plugin Name:     Lemonway
 * Plugin URI:
 * Description:     A secure, compliant and modular solution to simplify payments for product, service and financial marketplaces in Europe
 * Version:         1.0.0
 * Author:           Ganga Kafle
 * Author URI:
 * Text Domain:     lemonway
 * Requires Plugins: woocommerce, dokan-lite, dokan-pro
 * Domain Path:     /languages
 * Requires PHP:    7.1
 * Requires WP:     5.5.0
 * Namespace:       Lemonway
 */

declare( strict_types = 1 );

/**
 * Define the default root file of the plugin
 *
 * @since 1.0.0
 */
const LEMONWAY_PLUGIN_FILE = __FILE__;

/**
 * Load PSR4 autoloader
 *
 * @since 1.0.0
 */
$lemonway_autoloader = require plugin_dir_path( LEMONWAY_PLUGIN_FILE ) . 'vendor/autoload.php';

/**
 * Setup hooks (activation, deactivation, uninstall)
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, array( 'Lemonway\Config\Setup', 'activation' ) );
register_deactivation_hook( __FILE__, array( 'Lemonway\Config\Setup', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Lemonway\Config\Setup', 'uninstall' ) );

/**
 * Bootstrap the plugin
 *
 * @since 1.0.0
 */
if ( ! class_exists( '\Lemonway\Bootstrap' ) ) {
	wp_die( esc_html__( 'Lemonway is unable to find the Bootstrap class.', 'lemonway' ) );
}
add_action(
	'plugins_loaded',
	static function () use ( $lemonway_autoloader ) {
		/**
		 * Callback function
		 *
		 * @see \Lemonway\Bootstrap
		 */
		try {
			new \Lemonway\Bootstrap( $lemonway_autoloader );
		} catch ( Exception $e ) {
			wp_die( esc_html__( 'Lemonway is unable to run the Bootstrap class.', 'lemonway' ) );
		}
	}
);

/**
 * Create a main function for external uses
 *
 * @return \Lemonway\Common\Functions
 * @since 1.0.0
 */
function lemonway(): \Lemonway\Common\Functions {
	return new \Lemonway\Common\Functions();
}
