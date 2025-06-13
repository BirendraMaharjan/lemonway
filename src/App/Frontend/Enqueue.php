<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\App\Frontend;

use Lemonway\Common\Abstracts\Base;
use Lemonway\Integrations\Gateway\Handler;

/**
 * Class Enqueue
 *
 * @package Lemonway\App\Frontend
 * @since 1.0.0
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This frontend class is only being instantiated in the frontend as requested in the Bootstrap class
		 *
		 * @see Requester::isFrontend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );
	}

	/**
	 * Enqueue scripts function
	 *
	 * @since 1.0.0
	 */
	public function enqueueScripts() {
		// Enqueue CSS.
		foreach (
			array(
				array(
					'deps'    => array(),
					'handle'  => 'lemonway-frontend-css',
					'media'   => 'all',
					'source'  => plugins_url( '/assets/public/css/frontend.css', LEMONWAY_PLUGIN_FILE ), // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global
					'version' => $this->plugin->version(),
				),
			) as $css ) {
			wp_enqueue_style( $css['handle'], $css['source'], $css['deps'], $css['version'], $css['media'] );
		}
		// Enqueue JS.
		foreach (
			array(
				array(
					'type'      => 'enqueue',
					'deps'      => array( 'jquery', 'wp-i18n' ),
					'handle'    => 'lemonway-frontend-js',
					'in_footer' => true,
					'source'    => plugins_url( '/assets/public/js/frontend.js', LEMONWAY_PLUGIN_FILE ),
					'version'   => $this->plugin->version(),
				),
				array(
					'type'      => 'register',
					'deps'      => array(),
					'handle'    => 'lemonway-card-sdk',
					'in_footer' => true,
					'source'    => Handler::getCardHostedFieldsSdkUrl(),
					'version'   => null,
				),
				array(
					'type'      => 'register',
					'deps'      => array(),
					'handle'    => 'lemonway-paypal-sdk',
					'in_footer' => true,
					'source'    => Handler::getPaypalSdkUrl(),
					'version'   => null,
				),
				array(
					'type'      => 'register',
					'deps'      => array( 'jquery', 'wp-i18n' ),
					'handle'    => 'lemonway-payment-js',
					'in_footer' => true,
					'source'    => plugins_url( '/assets/public/js/payment.js', LEMONWAY_PLUGIN_FILE ),
					'version'   => $this->plugin->version(),
				),
			) as $js ) {
			$js['type'] === 'enqueue' ?
			wp_enqueue_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] ) :
			wp_register_script( $js['handle'], $js['source'], $js['deps'], $js['version'], $js['in_footer'] );
		}

		// localize script and send variables.
		wp_localize_script(
			'lemonway-frontend-js',
			'ajaxObj',
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'security'    => wp_create_nonce( 'lemonway-ajax-nonce' ),
				'loadingText' => esc_attr__( 'Loading...', 'lemonway' ),
			)
		);

		$this->paymentScripts();
	}

	public function paymentScripts() {
		if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
			return;
		}

		// Check if Lemonway is an available payment gateway.
		if ( ! class_exists( 'WC_Payment_Gateways' ) ) {
			return;
		}

		$gateways = WC()->payment_gateways->get_available_payment_gateways();

		if ( ! isset( $gateways['lemonway-gateway'] ) ) {
			return;
		}

		// loading this scripts only in checkout page.
		if ( is_checkout_pay_page() || ( ! is_order_received_page() && is_checkout() ) ) {
			global $wp;
			// get order id if this is an order review page.
			$order_id = isset( $wp->query_vars['order-pay'] ) ? $wp->query_vars['order-pay'] : null;

			wp_enqueue_script( 'lemonway-card-sdk' );
			wp_enqueue_script( 'lemonway-paypal-sdk' );
			wp_enqueue_script( 'lemonway-payment-js' );

			$data = array(
				'is_checkout_page'     => is_checkout(),
				'lemonway_payment'     => true,
				'nonce'                => wp_create_nonce( 'lemonway_checkout_nonce' ),
				'is_checkout_pay_page' => is_checkout_pay_page(),
				'order_id'             => $order_id,
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
			);

			// Localize the script with new data.
			wp_localize_script( 'lemonway-payment-js', 'lemonway_payment', $data );
		}
	}
}
