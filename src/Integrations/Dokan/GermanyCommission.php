<?php
/**
 * Dokan integration for Lemonway payment gateway.
 *
 * Integrates Lemonway with Dokan to manage payment settings, withdrawals, and KYC verification.
 *
 * @package   lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

use Lemonway\Config\Plugin;
use WC_Order;
use WC_Order_Item;

/**
 * Class Dokan
 *
 * Main class for integrating Lemonway with Dokan plugin.
 *
 * @package Lemonway\Integrations\Dokan
 */
class GermanyCommission {

	/**
	 * Plugin configuration.
	 *
	 * @var array $plugin Will be filled with data from the plugin config class.
	 * @see Plugin
	 */
	protected $plugin = array();

	/**
	 * Initialize the class.
	 *
	 * Sets up necessary hooks and initializes plugin dependencies.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->plugin = Plugin::init();

		// add_filter( 'dokan_order_line_item_commission_settings_before_save', array( $this, 'addExtraCommissionGorGermany' ), 20, 2 );
		// Hook into the template loading process to override the commission-meta-box-html.
		add_filter( 'dokan_get_template_part', array( $this, 'overrideDokanAdminCommissionTemplates' ), 10, 2 );
	}

	/**
	 * Adds extra commission for vendors located in Germany.
	 *
	 * @param array         $setting Commission settings.
	 * @param WC_Order_Item $order   Order item object.
	 *
	 * @return array Modified commission settings.
	 */
	public function addExtraCommissionGorGermany( $setting, $order ) {

		if ( ! is_array( $setting ) || ! is_a( $order, 'WC_Order_Item' ) ) {
			return $setting;
		}

		// Check if commission already applied.
		$already_applied = $order->get_meta( '_santerris_germany_vendor_commission_applied', true );
		if ( $already_applied ) {
			return $setting;
		}

		// Get the main order.
		$main_order = $order->get_order();
		if ( ! $main_order instanceof WC_Order ) {
			return $setting;
		}

		$seller_id = dokan_get_seller_id_by_order( $main_order );
		if ( empty( $seller_id ) ) {
			return $setting;
		}

		$vendor = dokan()->vendor->get( $seller_id );
		if ( ! $vendor || ! method_exists( $vendor, 'get_shop_info' ) ) {
			return $setting;
		}

		$store_info = $vendor->get_shop_info();
		// Base commission before increase.
		$base_commission      = floatval( $setting['percentage'] ?? 0 );
		$base_flat_commission = floatval( $setting['flat'] ?? 0 );
		if ( ! empty( $store_info['address']['country'] ) && strtoupper( $store_info['address']['country'] ) === 'DE' ) {
			$setting['percentage'] = round( $base_commission * 1.19, 2 );
			$setting['flat']       = round( $base_flat_commission * 1.19, 2 );
		}

		$base_commission      = floatval( $setting['percentage'] ?? 0 );
		$base_flat_commission = floatval( $setting['flat'] ?? 0 );
		// Save commission meta.
		$commission_data = array(
			'applied'            => true,
			'seller_id'          => $seller_id,
			'country'            => $store_info['address']['country'] ?? '', // country at time of order.
			'order_id'           => $main_order->get_id(),
			'order_item_id'      => $order->get_id(),
			'commission'         => $base_commission,
			'germany_commission' => $setting['percentage'],
			'flat'               => $base_flat_commission,
			'germany_flat'       => $setting['flat'],
			'setting'            => $setting,
		);

		$new = get_post_meta( $order->get_order_id(), '_germany_vendor_commission', true );
		if ( ! isset( $new['country'] ) ) {
			$commission_data['note'] = 'Before commission functionality was applied';
		}

		$order->update_meta_data( '_santerris_germany_vendor_commission_data', $commission_data );
		$order->update_meta_data( '_santerris_germany_vendor_commission_applied', true ); // Flag so it doesn't re-run.
		$order->save_meta_data();

		return $setting;
	}


	/**
	 * Override the Dokan template to load the custom commission meta box HTML.
	 *
	 * @param string $template The original template file path.
	 * @param string $slug The name of the template.
	 *
	 * @return string The custom template file path if overridden.
	 */
	public function overrideDokanAdminCommissionTemplates( $template, $slug ) {
		if ( 'orders/commission-meta-box-html' !== $slug ) {
			return $template;
		}

		// Debug the custom template path.
		$custom_template = $this->plugin->templatePath() . '/dokan/templates/orders/commission-meta-box-html.php';

		// Check if the custom template exists.
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}

		return $template; // Return the default template if not overridden.
	}
}
