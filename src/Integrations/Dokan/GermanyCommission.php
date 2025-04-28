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
// Hook into the Dokan admin commission filter
		add_filter( 'dokan_get_earning_by_order', [ $this, 'get_dokan_admin_commission_with_vat' ], 20, 3 );

		// Hook into the template loading process to override the commission-meta-box-html
		add_filter( 'dokan_get_template_part', [ $this, 'overrideDokanAdminCommissionTemplates' ], 10, 3 );
	}

	/**
	 * Sets up a new HTML widget instance.
	 */
	public function __construct() {
		$this->plugin = Plugin::init();
	}


	/**
	 * Calculate admin commission with VAT for German vendors.
	 *
	 * @param float $earning_or_commission Original commission value.
	 * @param WC_Order $order The WooCommerce order.
	 * @param string $context The context for calculating commission.
	 *
	 * @return float Adjusted earning or commission.
	 */
	public function get_dokan_admin_commission_with_vat( $earning_or_commission, $order, $context ) {
		// Get the vendor ID
		$seller_id = dokan_get_seller_id_by_order( $order );
		if ( ! $seller_id ) {
			return $earning_or_commission;
		}

		$vendor     = dokan()->vendor->get( $seller_id );
		$store_info = $vendor->get_shop_info();

		// Get full order total (excluding refunds)
		$order_total = (float) $order->get_total();

		// Calculate admin commission before VAT
		$admin_commission = $order_total - $earning_or_commission;

		// Apply 19% commission of admin commission if the store is in Germany
		if ( isset( $store_info['address']['country'] ) && $store_info['address']['country'] === 'DE' ) {

			$german_commission = $admin_commission * 0.19;
			$total_commission = $admin_commission + $german_commission;

			// Final earning = order total - (admin commission + 19 % german commission of admin commission)
			$earning_or_commission = $order_total - $total_commission;

			$german_commission_data = array(
				'commission' => $admin_commission,
				'germany_commission' => $german_commission,
				'total_commission' => $total_commission
			);

			// Update order meta-data with German vendor commission
			$order->update_meta_data( '_german_vendor_commission', $german_commission_data );
			$order->save();
		}

		return $earning_or_commission;
	}

	/**
	 * Override the Dokan template to load the custom commission meta box HTML.
	 *
	 * @param string $template The original template file path.
	 * @param string $template_name The name of the template.
	 * @param string $template_path The path of the template.
	 *
	 * @return string The custom template file path if overridden.
	 */
	public function overrideDokanAdminCommissionTemplates( $template, $slug, $args ) {
		if ( 'orders/commission-meta-box-html' !== $slug ) {
			return $template;
		}

		// Debug the custom template path
		$custom_template = $this->plugin->templatePath() . '/dokan/templates/orders/commission-meta-box-html.php';


		// Check if the custom template exists
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}

		return $template; // Return the default template if not overridden
	}

}
