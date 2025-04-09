<?php
/**
 * Lemonway Gateway
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Gateway;

use Lemonway\Config\Plugin;
use Lemonway\Integrations\Lemonway\Payment;
use Lemonway\Integrations\Lemonway\Transaction;
use WC_Logger;
use WC_Order;
use WC_Payment_Gateway;

/**
 * Class WcGatewayLemonway
 *
 * WooCommerce's payment gateway integration for Lemonway.
 */
class Lemonway extends WC_Payment_Gateway {

	/**
	 * Whether logging is enabled.
	 *
	 * @var bool
	 */
	public static $log_enabled = true;
	/**
	 * Logger instance.
	 *
	 * @var WC_Logger|false
	 */
	public static $log = false;
	/**
	 * Plugin metadata from the Plugin configuration.
	 *
	 * @var array $plugin Will be filled with data from the plugin config class.
	 * @see Plugin
	 */
	protected $plugin = array();
	/**
	 * Whether logging is enabled.
	 *
	 * @var bool
	 */
	protected $setting_lemonway = array();


	/**
	 * Lemonway settings.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'       => 'title',
		'description' => 'description',
		'enabled'     => 'enabled',
	);

	/**
	 * Payment instance.
	 *
	 * Manages payment-related operations.
	 *
	 * @var Payment
	 */
	protected $payment;

	/**
	 * Transaction instance.
	 *
	 * Handles transaction-related operations.
	 *
	 * @var Transaction
	 */
	protected $transaction;

	/**
	 * Constructor for the payment gateway.
	 */
	public function __construct() {
		$this->plugin           = Plugin::init();
		$this->setting_lemonway = new Settings();
		$this->payment          = new Payment();
		$this->transaction      = new Transaction();

		$this->id                 = 'lemonway-gateway';
		$this->icon               = '';
		$this->has_fields         = true;
		$this->method_title       = $this->plugin->name();
		$this->method_description = $this->plugin->description();

		$this->supports = array(
			'products',
			'refunds',
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
	}

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->id,
			array(
				$this,
				'process_admin_options',
			)
		);
	}

	/**
	 * Initialize gateway settings form fields.
	 */
	public function init_form_fields() {
		$this->form_fields = $this->setting_lemonway->fields();
	}

	/**
	 * Logging method.
	 *
	 * @param string $message The log message.
	 */
	public static function log( $message ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = new WC_Logger();
			}
			self::$log->add( esc_html__( 'Lemonway', 'lemonway' ), $message );
		}
	}

	public function payment_fields() {
		if ( $this->description ) {
			echo wp_kses_post( wpautop( $this->description ) );
		}

		$available_payment_types = Helper::getAvailablePyamentTypes();

		lemonway()->templates()->get(
			'payment-form',
			null,
			array(
				'available_payment_types' => $available_payment_types,
			)
		);
		?>


		<?php
	}


	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id The ID of the order being processed.
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order instanceof WC_Order ) {
			return wc_add_notice( __( 'Payment error: No valid order found.', 'lemonway' ), 'error' );
		}

		$process_result = false;
		$payment_type   = ! empty( $_POST['lemonway_payment_type'] )
			? sanitize_text_field( wp_unslash( $_POST['lemonway_payment_type'] ) )
			: '';

		if ( $payment_type === 'paypal' ) {
			$process_result = $this->paypalProcess( $order_id );
		} elseif ( $payment_type === 'card' ) {
			$process_result = $this->cardProcess( $order_id );
		} elseif ( $payment_type === 'wire-transfer' ) {
			$process_result = true;
		}

		$order->update_status( 'pending', sprintf( /* translators: %s: Order ID */ __( 'Lemonway %s payment pending: Order #%s.', 'lemonway' ), esc_attr( $payment_type ), absint( $order_id ) ) );

		if ( is_wp_error( $process_result ) ) {
			$error_message = sprintf(
			/* translators: %1$s: Error message from payment gateway */
				__( 'Error while creating lemonway payment order: %1$s', 'lemonway' ),
				wp_json_encode( $process_result->get_error_message(), JSON_PRETTY_PRINT )
			);
			wc_add_notice( $error_message, 'error' );

			return array(
				'result'   => 'failure',
				'redirect' => false,
				'messages' => '<ul class="woocommerce-error" role="alert"><li>' . $error_message . '</li></ul>',
			);
		}

		update_post_meta( $order_id, 'lemonway_payment_return_data', $process_result );
		update_post_meta( $order_id, Helper::getOrderTransactionIdKey(), $process_result['id'] );
		update_post_meta( $order_id, 'lemonway_payment_type', $payment_type );
		update_post_meta( $order_id, 'lemonway_payment_mode', Helper::getPaymentMode() );

		$return = array(
			'result'                  => 'success',
			'order_id'                => $order_id,
			'payment_type'            => $payment_type,
			'success_redirect'        => $this->getPaymentReturnUrl( $order_id, 'success' ),
			'cancel_redirect'         => $this->getPaymentReturnUrl( $order_id, 'cancel' ),
			'lemonway_transaction_id' => $process_result['id'],
		);

		if ( $payment_type === 'paypal' ) {
			$return['paypal_order_id'] = $process_result['payPalOrderId'];
			$return['redirect']        = $process_result['redirectionUrl'];
		}
		if ( $payment_type === 'card' ) {
			$return['token'] = $process_result['webKitToken'];
		}

		return $return;
	}

	public function paypalProcess( $order_id ) {
		$order_id = absint( $order_id );
		$order    = wc_get_order( $order_id );

		// Calculate discounts.
		$total_discount = $order->get_total_discount();

		$data = array(
			'redirections'    => array(
				'returnUrl' => $this->getPaymentReturnUrl( $order_id, 'success' ),
				'errorUrl'  => $this->getPaymentReturnUrl( $order_id, 'error' ),
				'cancelUrl' => $this->getPaymentReturnUrl( $order_id, 'cancel' ),
			),
			'transaction'     => array(
				'reference'      => $order_id,
				'accountId'      => Helper::getTechnicalAccountId(),
				'totalAmount'    => Helper::toCents( $order->get_total() ),
				'comment'        => sprintf(
				/* translators: %s: Order ID */
					esc_html__( 'Order number %s', 'lemonway' ),
					$order_id
				),
				'autoCommission' => false,
			),
			'amountBreakdown' => array(
				'totalItems' => Helper::toCents( $order->get_subtotal() ),
				'shipping'   => Helper::toCents( $order->get_shipping_total() ),
				'discount'   => Helper::toCents( $order->get_total_discount() ),
			),
			'items'           => $this->getProductItemsForPaypal( $order ),
			'delivery'        => array(
				'address'  => array(
					'country'  => $order->get_billing_country(),
					'city'     => $order->get_billing_city(),
					'street'   => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(),
					'postCode' => $order->get_billing_postcode(),
					'state'    => $order->get_billing_state(),
				),
				'receiver' => array(
					'fullName' => $order->get_formatted_billing_full_name(),
				),
			),
		);

		return $this->payment->paypal( $data );
	}

	public function getPaymentReturnUrl( $order_id, $status = 'cancel' ) {
		$order_id     = absint( $order_id );
		$order        = wc_get_order( $order_id );
		$allow_status = array( 'cancel', 'success', 'error' );
		if ( ! in_array( $status, $allow_status, true ) ) {
			return false;
		}

		return add_query_arg(
			array(
				'lemonway-payment' => true,
				'payment'          => $status,
				'_wpnonce'         => wp_create_nonce( 'lemonway_payment_' . $order->get_id() ),
			),
			$this->get_return_url( $order )
		);
	}

	public static function getProductItemsForPaypal( \WC_Order $order ) {
		$items = array();

		foreach ( $order->get_items( 'line_item' ) as $key => $line_item ) {
			$product = wc_get_product( $line_item->get_product_id() );
			$type    = $product->is_downloadable() || $product->is_virtual() ? 2 : 1; // 1 = Physical. 2 = Digital.

			$seller_id = get_post_field( 'post_author', $product->get_id() );

			if ( $line_item->get_subtotal() <= 0 ) {
				continue;
			}

			$items[] = array(
				'merchantAccountId' => Helper::getMerchantId( $seller_id ),
				'description'       => $line_item->get_name(),
				'type'              => $type,
				'unitAmount'        => Helper::toCents( $line_item->get_subtotal() ),
				'quantity'          => 1,
			);
		}

		return $items;
	}

	public function cardProcess( $order_id ) {
		$order_id = absint( $order_id );
		$order    = wc_get_order( $order_id );

		$data = array(
			'returnUrl'      => $this->getPaymentReturnUrl( $order_id, 'success' ),
			'errorUrl'       => $this->getPaymentReturnUrl( $order_id, 'error' ),
			'cancelUrl'      => $this->getPaymentReturnUrl( $order_id, 'cancel' ),
			'registerCard'   => false,
			'reference'      => $order_id,
			'accountId'      => Helper::getTechnicalAccountId(),
			'totalAmount'    => Helper::toCents( $order->get_total() ),
			'comment'        => sprintf(
			/* translators: %s: Order ID */
				esc_html__( 'Order number %s', 'lemonway' ),
				$order_id
			),
			'autoCommission' => false,
		);

		return $this->payment->webinit( $data );
	}

	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $this->can_refund_order( $order ) ) {
			return new WP_Error( 'error', __( 'Refund failed.', 'lemonway' ) );
		}

		return true;
	}
}
