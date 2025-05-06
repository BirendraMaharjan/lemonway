<?php
/**
 * Lemonway Refund Integration Class
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

/**
 * Class Refund
 *
 * Handles the Refund for the Dokan plugin with Lemonway.
 */
class ReturnWarranty extends Dokan {

	/**
	 * Refund constructor.
	 *
	 * Initializes the class and hooks.
	 */
	public function init() {

		add_filter( 'dokan_rma_warranty_type', array( $this, 'warrantyTypes' ) );
		add_filter( 'dokan_rma_warranty_length', array( $this, 'warrantyLength' ) );


		// Assuming $dokan_rma_product is your instance
		$this->removeClassAction( 'woocommerce_before_add_to_cart_button', 'Dokan_RMA_Frontend', 'show_product_warranty', 10 );
		$this->removeClassAction( 'dokan_product_edit_after_inventory_variants', 'Dokan_RMA_Product', 'load_rma_content', 30 );


		remove_filter( 'woocommerce_my_account_my_orders_actions', [ 'Dokan_RMA_Order', 'request_button' ] );
		remove_filter( 'dokan_my_account_my_sub_orders_actions', [ 'Dokan_RMA_Order', 'request_button' ] );

		// My order list table actions
		add_filter( 'woocommerce_my_account_my_orders_actions', [ $this, 'requestButton' ], 10, 2 );
		add_filter( 'dokan_my_account_my_sub_orders_actions', [ $this, 'requestButton' ], 10, 2 );

		// Hook into the template loading process to override the rma/request-warranty.
		add_filter( 'dokan_get_template_part', array( $this, 'overrideDokanRmaRequestWarrantyTemplates' ), 10, 2 );


		/*add_action( 'dokan_new_seller', [ $this, 'set_rma_on_new_vendor' ] );
		add_action( 'wp_login', [ $this, 'set_rma_on_vendor_login' ], 10, 2 );*/
	}

	public function set_rma_on_new_vendor( $user_id ) {
		if ( $this->needs_rma_settings( $user_id ) ) {
			update_user_meta( $user_id, '_dokan_rma_settings', $this->get_default_rma_settings() );
		}
	}

	public function set_rma_on_vendor_login( $user_login, $user ) {
		if ( $this->needs_rma_settings( $user->ID ) ) {
			update_user_meta( $user->ID, '_dokan_rma_settings', $this->get_default_rma_settings() );
		}
	}

	private function needs_rma_settings( $user_id ) {
		$user = get_userdata( $user_id );
		if ( ! $user || ! in_array( 'seller', (array) $user->roles, true ) ) {
			return false;
		}
		$existing = get_user_meta( $user_id, '_dokan_rma_settings', true );
		return empty( $existing );
	}

	private function get_default_rma_settings() {
		$reasons = array_column( dokan_get_option('rma_reasons', 'dokan_rma'), 'id' );

		return array(
			'label'      => '',
			'type'   => 'included_warranty',
			"reasons" => $reasons,
			"length" => "lifetime",
			"length_value" => "",
			"length_duration" => "",
			"addon_settings" => []
		);
	}

	/**
	 * Override the Dokan template to load the custom commission meta box HTML.
	 *
	 * @param string $template The original template file path.
	 * @param string $slug The name of the template.
	 *
	 * @return string The custom template file path if overridden.
	 */
	public function overrideDokanRmaRequestWarrantyTemplates( $template, $slug ) {
		if ( 'rma/request-warranty' !== $slug ) {
			return $template;
		}

		// Debug the custom template path.
		$custom_template = $this->plugin->templatePath() . '/dokan/templates/rma/request-warranty.php';

		// Check if the custom template exists.
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}

		return $template; // Return the default template if not overridden.
	}

	function removeClassAction( $hook, $class_name, $method_name, $priority = 10 ) {
		global $wp_filter;

		if ( isset( $wp_filter[ $hook ] ) ) {
			foreach ( $wp_filter[ $hook ]->callbacks[ $priority ] ?? [] as $callback ) {
				if ( is_array( $callback['function'] ) &&
				     is_object( $callback['function'][0] ) &&
				     get_class( $callback['function'][0] ) === $class_name &&
				     $callback['function'][1] === $method_name ) {

					remove_action( $hook, [ $callback['function'][0], $method_name ], $priority );
				}
			}
		}
	}



	public function warrantyTypes( $type  ) {

		unset( $type['addon_warranty'] );
		unset( $type['no_warranty'] );

		return $type;
	}

	public function warrantyLength( $length  ) {

		unset( $length['limited'] );

		return $length;
	}

	public function requestButton( $actions, $order ) {
		$allowed_status = dokan_get_option( 'rma_order_status', 'dokan_rma', 'wc-completed' );

		if ( $allowed_status != 'wc-' . $order->get_status() ) {
			return $actions;
		}

		$url = esc_url_raw( wc_get_account_endpoint_url( 'request-warranty' ) . $order->get_id() ) ;
		$actions['request_warranty'] = array( 'url' => $url, 'name' => __( 'Request Refund', 'dokan' ) );
		return $actions;
	}


}
