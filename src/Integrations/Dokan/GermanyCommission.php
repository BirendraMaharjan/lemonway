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
		// Hook into the Dokan admin commission filter.
		//add_filter( 'dokan_get_earning_by_order', array( $this, 'get_dokan_admin_commission_with_vat' ), 20, 2 );

		/*add_filter( 'add_extra_commission_for_germany', function ($setting){
			$setting['percentage'] = 20;
			return $setting;
		}, 2);*/

		add_filter( 'dokan_order_line_item_commission_settings_before_save', array( $this, 'addExtraCommissionGorGermany' ), 20, 2 );
		// Hook into the template loading process to override the commission-meta-box-html.
		add_filter( 'dokan_get_template_part', array( $this, 'overrideDokanAdminCommissionTemplates' ), 10, 2 );


	}

	/**
	 * Sets up a new HTML widget instance.
	 */
	public function __construct() {
		$this->plugin = Plugin::init();
		/*add_action('init', function (){
			add_filter( 'dokan_order_line_item_commission_settings_before_save', array( $this, 'addExtraCommissionGorGermany' ), 20, 2 );
		});*/

	}

	/**
	 * Adds extra commission for vendors located in Germany.
	 *
	 * @param array           $setting Commission settings.
	 * @param WC_Order_Item   $order   Order item object.
	 *
	 * @return array Modified commission settings.
	 */
	public function addExtraCommissionGorGermany( $setting, $order ) {

		if ( ! is_array( $setting ) || ! is_a( $order, 'WC_Order_Item' ) ) {
			return $setting;
		}

		// Check if commission already applied
		$already_applied = $order->get_meta( '_germany_vendor_commission_applied', true );
		if ( $already_applied ) {
			return $setting;
		}

		// Get the main order
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
		// Base commission before increase
		$base_commission = $setting['percentage'] ?? 0;
		$base_flat_commission = $setting['flat'] ?? 0;
		if ( ! empty( $store_info['address']['country'] ) && strtoupper( $store_info['address']['country'] ) === 'DE' ) {
			$setting['percentage'] = round( $base_commission * 1.19, 2 );
			$setting['flat'] = round( $base_flat_commission * 1.19, 2 );
		}


		// Save commission meta
		$commission_data = array(
			'applied'            => true,
			'seller_id'          => $seller_id,
			'country'            => $store_info['address']['country'] ?? '', // country at time of order
			'order_id'           => $main_order->get_id(),
			'order_item_id'      => $order->get_id(),
			'commission'         => $base_commission,
			'germany_commission' => $setting['percentage'],
			'flat'               => $base_flat_commission,
			'germany_flat'       => $setting['flat'],
			'setting'            => $setting,
		);

		$order->update_meta_data( '_germany_vendor_commission', $commission_data );
		$order->update_meta_data( '_germany_vendor_commission_applied', true ); // Flag so it doesn't re-run
		$order->save_meta_data();

		return $setting;
	}



	/**
	 * Calculate admin commission with VAT for German vendors.
	 *
	 * @param float    $earning_or_commission Original commission value.
	 * @param WC_Order $order The WooCommerce order.
	 *
	 * @return float Adjusted earning or commission.
	 */
	public function get_dokan_admin_commission_with_vat( $earning_or_commission, $order ) {
		// Get the vendor ID.
		$seller_id = dokan_get_seller_id_by_order( $order );
		if ( ! $seller_id ) {
			return $earning_or_commission;
		}

		$vendor     = dokan()->vendor->get( $seller_id );
		$store_info = $vendor->get_shop_info();

		// Get full order total (excluding refunds).
		$order_total = (float) $order->get_total();

		// Calculate admin commission before VAT.
		$admin_commission = $order_total - $earning_or_commission;
		$germany_commission = 0;
		$total_commission = 0;

		/*print_r($admin_commission);
		echo "<br>";
		print_r($earning_or_commission);
		echo '</pre>';
		print_r($order_total);

		exit;*/

		// Apply 19% commission of admin commission if the store is in Germany.
		if ( isset( $store_info['address']['country'] ) && $store_info['address']['country'] === 'DE' ) {

			$germany_commission = $admin_commission * 0.19;
			$total_commission  = $admin_commission + $germany_commission;

			// Final earning = order total - (admin commission + 19 % german commission of admin commission).
			$earning_or_commission = $order_total - $total_commission;
		}

		$germany_commission_data = array(
			'seller_id' => $seller_id,
			'country' => $store_info['address']['country'],
			'total_order' => $order_total,
			'commission'         => $admin_commission,
			'germany_commission' => $germany_commission,
			'total_commission'   => $total_commission,
			'earning_or_commission'   => $earning_or_commission,
		);

		/*echo "<pre>";
			print_r($germany_commission_data);
			print_r($earning_or_commission);
		echo "</pre>"; exit;*/

		// Update order meta-data with German vendor commission.
		$order->update_meta_data( '_germany_vendor_commission', $germany_commission_data );
		$order->save();

		return $earning_or_commission;
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
