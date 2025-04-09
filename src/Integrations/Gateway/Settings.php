<?php
/**
 * Lemonway dokan setting
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Gateway;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Lemonway\Config\Plugin;
use function WooCommerce\PayPalCommerce\OrderTracking\tr;

/**
 * Class Settings
 *
 * Handles Lemonway settings for the WooCommerce payment gateway.
 */
class Settings {
	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'metaBox' ) );
	}

	public function metaBox() {

		$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
			? wc_get_page_screen_id( 'shop-order' )
			: 'shop_order';

		add_meta_box(
			'lemonway_payment_transactions_details',
			esc_html__( 'Lemonway Transaction Details:', 'lemonway' ),
			array( $this, 'transactionsDetails' ),
			$screen,
			'advanced',
			'default'
		);
		add_meta_box(
			'lemonway_payment_transactions_pending_details',
			esc_html__( 'Lemonway Transaction Pending Details:', 'lemonway' ),
			array( $this, 'transactionsPendingDetails' ),
			$screen,
			'advanced',
			'default'
		);
		add_meta_box(
			'lemonway_payment_transactions_error_details',
			esc_html__( 'Lemonway Transaction Error Details:', 'lemonway' ),
			array( $this, 'transactionsErrorDetails' ),
			$screen,
			'advanced',
			'default'
		);
		add_meta_box(
			'lemonway_payment_settlement_details',
			esc_html__( 'Lemonway Settlement Details:', 'lemonway' ),
			array( $this, 'settlementDetails' ),
			$screen,
			'advanced',
			'default'
		);
		add_meta_box(
			'lemonway_payment_refund_details',
			esc_html__( 'Lemonway Refund Details:', 'lemonway' ),
			array( $this, 'refundDetails' ),
			$screen,
			'advanced',
			'default'
		);
		add_meta_box(
			'lemonway_payment_refund_settlement_details',
			esc_html__( 'Lemonway Refund Settlement Details:', 'lemonway' ),
			array( $this, 'refundSettlementDetails' ),
			$screen,
			'advanced',
			'default'
		);
	}

	public function transactionsDetails( $post ) {
		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_payment_transactions_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	public function transactionsPendingDetails( $post ) {

		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_payment_transactions_pending_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	public function transactionsErrorDetails( $post ) {

		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_payment_transactions_error_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	public function settlementDetails( $post ) {

		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_p2p_transaction_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	public function refundDetails( $post ) {

		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_payment_refund_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	public function refundSettlementDetails( $post ) {

		$order = is_a( $post, 'WP_Post' ) ? wc_get_order( $post->ID ) : $post;

		if ( ! $order ) {
			echo '<p>' . esc_html__( 'No order found.', 'lemonway' ) . '</p>';
			return;
		}

		$transaction_history = $order->get_meta( 'lemonway_payment_refund_settlement_details' );

		if ( ! empty( $transaction_history ) ) {
			echo '<pre>' . esc_html( wp_json_encode( $transaction_history, JSON_PRETTY_PRINT ) ) . '</pre>';
		} else {
			echo '<p>' . esc_html__( 'No history available.', 'lemonway' ) . '</p>';
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function fields() {
		return array(
			'enabled'                   => array(
				'title'   => __( 'Enable/Disable', 'lemonway' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),
			'title'                     => array(
				'title'       => __( 'Title', 'lemonway' ),
				'type'        => 'safe_text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'lemonway' ),
				'default'     => __( 'Lemonway Payment', 'lemonway' ),
				'desc_tip'    => true,
			),
			'description'               => array(
				'title'       => __( 'Description', 'lemonway' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'lemonway' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'instructions'              => array(
				'title'       => __( 'Instructions', 'lemonway' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page and emails.', 'lemonway' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			'test_mode'                 => array(
				'title' => __( 'Sandbox', 'lemonway' ),
				'label' => __( 'Enable/Disable', 'lemonway' ),
				'type'  => 'checkbox',
			),
			'live_title'                => array(
				'title' => __( 'Live/Production Lemonway Details:', 'lemonway' ),
				'type'  => 'title',
			),
			'psu_ip_address'            => array(
				'title' => __( 'PSU-IP-Address', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'api_endpoint'              => array(
				'title' => __( 'API Endpoint', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'api_key'                   => array(
				'title' => __( 'API Key', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'technical_account_id'      => array(
				'title'   => __( 'Technical Account Id', 'lemonway' ),
				'type'    => 'safe_text',
				'default' => 'MKP',
			),
			'paypal_client_id'          => array(
				'title' => __( 'Paypal client Id', 'lemonway' ),
				'type'  => 'textarea',
			),
			'test_title'                => array(
				'title' => __( 'Test/Sandbox Lemonway Details:', 'lemonway' ),
				'type'  => 'title',
			),
			'test_psu_ip_address'       => array(
				'title' => __( 'PSU-IP-Address', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'test_api_endpoint'         => array(
				'title' => __( 'API Endpoint', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'test_api_key'              => array(
				'title' => __( 'API Key', 'lemonway' ),
				'type'  => 'safe_text',
			),
			'test_technical_account_id' => array(
				'title'   => __( 'Technical Account Id', 'lemonway' ),
				'type'    => 'safe_text',
				'default' => 'MKP',
			),
			'test_paypal_client_id'     => array(
				'title' => __( 'Paypal client Id', 'lemonway' ),
				'type'  => 'textarea',
			),
		);
	}
}
