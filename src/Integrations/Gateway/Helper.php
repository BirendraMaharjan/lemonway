<?php
/**
 * Lemonway Gateway Helper
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Gateway;

/**
 * Class Helper
 *
 * Provides helper methods for interacting with Lemonway payment gateway settings.
 */
class Helper {


	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
	}

	/**
	 * Check if the Lemonway is enabled.
	 *
	 * @return bool True if the gateway is enabled, false otherwise.
	 */
	public static function isEnabled() {
		$settings = static::getSettings();

		return ! empty( $settings['enabled'] ) && 'yes' === $settings['enabled'];
	}

	/**
	 * Check if the payment gateway is in test mode.
	 *
	 * @return bool Whether the gateway is in test mode.
	 */
	public static function isTestMode() {
		$settings = static::getSettings();

		return ! empty( $settings['test_mode'] ) && 'yes' === $settings['test_mode'];
	}

	/**
	 * Check if the Lemonway is ready for use.
	 *
	 * @return bool True if the gateway is ready, false otherwise.
	 */
	public static function isReady() {
		if ( ! static::isEnabled() ||
			empty( static::getApiKey() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the current payment mode based on test mode setting.
	 *
	 * @return string 'sandbox' if test mode, otherwise 'live'.
	 */
	public static function getPaymentMode() {
		return self::isTestMode() ? 'sandbox' : 'live';
	}

	/**
	 * Get the gateway ID.
	 *
	 * @return string The gateway ID.
	 */
	public static function getGatewayId() {
		return 'lemonway-gateway';
	}

	public static function getGatewayTitle() {
		$settings = static::getSettings();

		return ! empty( $settings['title'] ) ? $settings['title'] : __( 'Lemonway Payment', 'lemonway' );
	}

	/**
	 * Get the payment gateway settings.
	 *
	 * @param string|null $key Optional. Specific setting key to retrieve.
	 * @return mixed|array|string|null The settings array or specific setting value if key provided.
	 */
	public static function getSettings( $key = null ) {
		$settings = get_option( 'woocommerce_' . static::getGatewayId() . '_settings', array() );
		if ( $key && isset( $settings[ $key ] ) ) {
			return $settings[ $key ];
		}

		return $settings;
	}

	/**
	 * Get PSU IP address based on test mode.
	 *
	 * @return string PSU IP address.
	 */
	public static function getPsuIpAddress() {
		$key      = self::isTestMode() ? 'test_psu_ip_address' : 'psu_ip_address';
		$settings = static::getSettings();

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
	}

	/**
	 * Get Technical Account.
	 *
	 * @return string Technical Account ID.
	 */
	public static function getTechnicalAccountId() {
		$key      = self::isTestMode() ? 'test_technical_account_id' : 'technical_account_id';
		$settings = static::getSettings();

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
	}

	/**
	 * Get API Key based on test mode.
	 *
	 * @return string API Key.
	 */
	public static function getApiKey() {
		$key      = self::isTestMode() ? 'test_api_key' : 'api_key';
		$settings = static::getSettings();

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
	}

	/**
	 * Get API Endpoint based on test mode.
	 *
	 * @return string API Endpoint.
	 */
	public static function getApiEndpoint() {
		$key      = self::isTestMode() ? 'test_api_endpoint' : 'api_endpoint';
		$settings = self::getSettings();

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
	}

	public static function isSellerEnableForReceivePayment( $seller_id ) {
		return self::getMerchantId( $seller_id ) && self::getMerchantKey( $seller_id );
	}

	/**
	 * Check if debug mode is enabled in settings.
	 *
	 * @return bool Whether debug mode is enabled.
	 */
	public static function isDebugEnabled() {
		$settings = self::getSettings();

		return ! empty( $settings['debug'] ) && 'yes' === $settings['debug'];
	}

	/**
	 * Get the seller merchant key based on test mode.
	 *
	 * @param bool|null $test_mode Optional. Whether test mode is enabled. Defaults to null (auto-detect).
	 * @return string Seller merchant key.
	 */
	public static function getMerchantKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = self::isTestMode();
		}
		return $test_mode ? 'lemonway_test_merchant_id' : 'lemonway_merchant_id';
	}

	/**
	 * Get the seller merchant ID based on test mode.
	 *
	 * @return string Seller merchant ID.
	 */
	public static function getMerchantId( $seller_id = null, $test_mode = null ) {
		$seller_id = $seller_id ?? get_current_user_id();
		return get_user_meta( $seller_id, self::getMerchantKey( $test_mode ), true );
	}

	/**
	 * Get the seller merchant key based on test mode.
	 *
	 * @param bool|null $test_mode Optional. Whether test mode is enabled. Defaults to null (auto-detect).
	 * @return string Seller merchant key.
	 */
	public static function getMerchantInternalKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = self::isTestMode();
		}
		return $test_mode ? 'lemonway_test_merchant_internal_id' : 'lemonway_merchant_internal_id';
	}

	/**
	 * Get the seller merchant ID based on test mode.
	 *
	 * @return string Seller merchant ID.
	 */
	public static function getMerchantInternalId( $seller_id = null, $test_mode = null ) {
		$seller_id = $seller_id ?? get_current_user_id();
		return get_user_meta( $seller_id, self::getMerchantInternalKey( $test_mode ), true );
	}

	/**
	 * Get the seller merchant key based on test mode.
	 *
	 * @param bool|null $test_mode Optional. Whether test mode is enabled. Defaults to null (auto-detect).
	 * @return string Seller merchant key.
	 */
	public static function getUploadDocumentKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = self::isTestMode();
		}
		return $test_mode ? 'lemonway_test_upload_document_id' : 'lemonway_upload_document_id';
	}

	/**
	 * Get the seller merchant ID based on test mode.
	 *
	 * @return string Seller merchant ID.
	 */
	public static function getUploadDocumentId( $attachment_id, $test_mode = null ) {
		return get_post_meta( $attachment_id, self::getUploadDocumentKey( $test_mode ), true );
	}

	public static function getUploadDocumentPostId( $meta_value ) {

		return lemonway()->getPostIdByMeta( self::getUploadDocumentKey(), $meta_value );
	}

	/**
	 * Credit card types that are available in MangoPay.
	 *
	 * @var array
	 */
	private static $available_pyament_types = array(
		'CB_VISA_MASTERCARD' => 'CB/Visa/Mastercard',
		'MAESTRO'            => 'Maestro*',
		'BCMC'               => 'Bancontact/Mister Cash',
		'P24'                => 'Przelewy24*',
		'DINERS'             => 'Diners*',
		'PAYLIB'             => 'PayLib',
		'IDEAL'              => 'iDeal*',
		'MASTERPASS'         => 'MasterPass*',
		'BANK_WIRE'          => 'Bankwire Direct*', // This is not actually a card.
	);

	/**
	 * Retrieves available credit card types.
	 *
	 * @return array
	 */
	public static function getAvailablePyamentTypes() {
		return self::$available_pyament_types;
	}

	/**
	 * Get the key for the order's transaction ID based on test mode.
	 *
	 * @param bool|null $test_mode Optional. Whether to use test mode. Defaults to null (automatic detection).
	 * @return string The transaction ID key.
	 */
	public static function getOrderTransactionIdKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = static::isTestMode();
		}
		return $test_mode ? 'lemonway_test_order_transaction_id' : 'lemonway_order_transaction_id';
	}

	/**
	 * Get the order's transaction ID.
	 *
	 * @param int $order The ID of the order.
	 * @return string The order's transaction ID.
	 */
	public static function getOrderTransactionID( $order ) {
		if ( ! is_object( $order ) ) {
			$order = wc_get_order( $order );
		}
		return $order->get_meta( static::getOrderTransactionIdKey() );
	}

	public static function getOrderTransferIdKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = static::isTestMode();
		}
		return $test_mode ? 'lemonway_test_order_transfer_id' : 'lemonway_order_transfer_id';
	}
	public static function getOrderTransferId( $order ) {
		if ( ! is_object( $order ) ) {
			$order = wc_get_order( $order );
		}
		return $order->get_meta( static::getOrderTransferIdKey() );
	}


	public static function getPaypalClientId() {
		$key      = self::isTestMode() ? 'test_paypal_client_id' : 'paypal_client_id';
		$settings = self::getSettings();

		return ! empty( $settings[ $key ] ) ? $settings[ $key ] : '';
	}



	/**
	 * Calculate percentage based on price and extra amount.
	 *
	 * @param float $price Total price.
	 * @param float $extra_amount Extra amount.
	 * @return float Percentage calculated.
	 */
	public static function getPercentage( $price, $extra_amount ) {
		$percentage = ( $extra_amount * 100 ) / $price;

		return $percentage;
	}

	/**
	 * Check if notice should be displayed on vendor dashboard.
	 *
	 * @return bool Whether to display notice on vendor dashboard.
	 */
	public static function displayNoticeOnVendorDashboard() {
		$key      = 'display_notice_on_vendor_dashboard';
		$settings = self::getSettings();

		return ! empty( $settings[ $key ] ) && 'yes' === $settings[ $key ];
	}

	/**
	 * Convert a value to cents (integer format).
	 *
	 * @param float $value The value to convert.
	 * @return int The value in cents.
	 */
	public static function toCents( $value ) {
		// Ensure the value is a valid decimal with two decimal places.
		$value = wc_format_decimal( $value, 2 );

		// Convert to cents and return as an integer.
		return intval( round( $value * 100 ) );
	}

	/**
	 * Get all orders to be processed based on whether the main order has suborders.
	 *
	 * @param $order The main WooCommerce order.
	 * @return array An array of orders to be processed.
	 */
	public static function getAllOrdersToProcessed( $order ) {

		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order instanceof \WC_Order ) {
			return array();
		}

		$order_id     = $order->get_id();
		$has_suborder = $order->get_meta( 'has_sub_order' );
		$all_orders   = array();

		if ( $has_suborder ) {
			$sub_orders = wc_get_orders(
				array(
					'type'   => 'shop_order',
					'parent' => $order_id,
					'limit'  => -1,
				)
			);
			foreach ( $sub_orders as $sub_order ) {
				$all_orders[] = $sub_order;
			}
		} else {
			$all_orders[] = $order;
		}

		return $all_orders;
	}


	/**
	 * Log messages for Lemonway integration.
	 *
	 * @param mixed  $message  The message or data to log.
	 * @param string $category Optional category label for the log entry.
	 * @param string $level    Log level: 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', or 'debug'.
	 */
	public static function log( $message, $category = '', $level = 'debug', $source = 'lemonway' ) {
		// Ensure WooCommerce logging is available.
		if ( ! function_exists( 'wc_get_logger' ) ) {
			return;
		}

		// Get logger instance.
		$logger = wc_get_logger();

		// Prepare message content.
		$data = is_array( $message ) || is_object( $message )
			? wp_json_encode( $message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
			: (string) $message;

		// Create meaningful log message with category prefix if available.
		$formatted_message = ! empty( $category )
			? "[{$category}] {$data}"
			: $data;

		// Set context for log entry.
		$context = array(
			'source'    => $source,
			'backtrace' => true,
		);

		// Log the message with appropriate level.
		$logger->log( $level, $formatted_message, $context );
	}

	public static function getRefundIdsByOrderKey( $test_mode = null ) {
		if ( null === $test_mode ) {
			$test_mode = static::isTestMode();
		}
		return $test_mode ? 'lemonway_test_order_refund_id' : 'lemonway_order_refund_id';
	}

	public static function getRefundIdsByOrder( $order ) {
		if ( is_numeric( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( ! $order instanceof \WC_Order ) {
			return array();
		}

		$refund_ids = $order->get_meta( self::getRefundIdsByOrderKey() );
		if ( $refund_ids === '' ) {
			$refund_ids = array();
		} elseif ( ! is_array( $refund_ids ) && $refund_ids !== '' ) {
			$refund_ids = (array) $refund_ids;
		}

		return $refund_ids;
	}

	public static function limitText( $text, $limit = 100 ) {
		$str_limit = $limit - 3;
		if ( mb_strlen( $text ) <= $limit ) {
			return $text;
		} else {
			return mb_substr( $text, 0, $str_limit ) . '...';
		}
	}
}
