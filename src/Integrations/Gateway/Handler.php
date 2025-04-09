<?php
/**
 * Lemonway Gateway
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Gateway;

use Lemonway\Integrations\Lemonway\Payment;
use Lemonway\Integrations\Lemonway\Transaction;

/**
 * Class Handler
 *
 * Provides Handler methods for interacting with Lemonway payment gateway.
 */
class Handler {

	/**
	 * The payment object for handling payment operations.
	 *
	 * @var Payment $payment
	 */
	protected $payment;

	/**
	 * The transaction object for handling transaction operations.
	 *
	 * @var Transaction $transaction
	 */
	protected $transaction;

	public function __construct() {
		$this->payment     = new Payment();
		$this->transaction = new Transaction();
	}

	public function init() {

		add_action( 'woocommerce_pay_order_after_submit', array( $this, 'display_paypal_button' ), 20 );
		add_action( 'woocommerce_review_order_after_submit', array( $this, 'display_paypal_button' ) );

		add_action( 'woocommerce_after_checkout_validation', array( $this, 'checkoutVendorValidation' ), 15, 2 );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'addToCardVendorValidation' ), 10, 2 );

		add_action( 'wp_ajax_lemonway_create_order', array( $this, 'createOrder' ) );
		add_action( 'wp_ajax_lemonway_capture_payment', array( $this, 'capturePayment' ) );
		add_action( 'wp_ajax_nopriv_lemonway_capture_payment', array( $this, 'capturePayment' ) );

		add_action( 'template_redirect', array( $this, 'lemonwayPaymentResponse' ) );

		add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'thankyouPage' ), 10, 2 );
		add_action( 'woocommerce_thankyou_lemonway-gateway', array( $this, 'addPendingStatus' ), 10, 1 );
		add_action( 'woocommerce_email_after_order_table', array( $this, 'emailPendingStatusNote' ), 10, 1 );
	}

	public function addPendingStatus( $order_id ) {
		$order          = wc_get_order( $order_id );
		$payment_status = $order->get_meta( 'lemonway_transaction_payment_status' );

		if ( $payment_status !== 'pending' ) {
			return;
		}
		?>
		<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
			<li class="woocommerce-order-overview__payment-method method-status">
				<?php esc_html_e( 'Payment Status:', 'lemonway' ); ?>
				<strong><?php esc_html_e( 'Pending', 'lemonway' ); ?></strong>
			</li>
		</ul>
		<?php
	}

	public function thankyouPage( $thankyou_text, $order ) {
		if ( ! $order ) {
			return $thankyou_text;
		}
		$payment_status = $order->get_meta( 'lemonway_transaction_payment_status' );

		if ( $payment_status !== 'pending' ) {
			return $thankyou_text;
		}
		$thankyou_text .= ' ';
		$thankyou_text .= esc_html__( 'Please be aware of that the payment is still in the status "pending".', 'lemonway' );

		return $thankyou_text;
	}
	public function emailPendingStatusNote( $order ) {
		$payment_status = $order->get_meta( 'lemonway_transaction_payment_status' );
		if ( $payment_status !== 'pending' ) {
			return '';
		}
		return printf(
			'<p><strong>%s</strong> %s</p>',
			esc_html__( 'Payment status note: ', 'lemonway' ),
			esc_html__( 'Please be aware that the payment is still in the status "pending".', 'lemonway' )
		);
	}

	public function createOrder() {
		$order_id = $this->do_validation();

		$lemonway        = new Lemonway();
		$process_payment = $lemonway->process_payment( $order_id );

		if ( isset( $process_payment['result'] ) && $process_payment['result'] === 'failure' ) {
			WC()->session->set( 'wc_notices', array() );
			wp_send_json_error(
				array(
					'data' => $process_payment,
				)
			);
		}

		wp_send_json_success(
			array(
				'data' => $process_payment,
			)
		);
	}

	public function lemonwayPaymentResponse() {

		if ( ! is_checkout() || empty( $_SERVER['REQUEST_URI'] ) || ! isset( $_GET['key'] ) ) {
			return;
		}

		global $wp;
		$order_id = wc_get_order_id_by_order_key( wc_clean( wp_unslash( $_GET['key'] ) ) );
		$order    = wc_get_order( $order_id );

		if (
			empty( $wp->query_vars['order-received'] ) ||
			! $order ||
			$order_id === 0 ||
			$order->get_order_key() !== sanitize_text_field( wp_unslash( $_GET['key'] ) ) ||
			$order->has_status( [ 'completed', 'processing' ] ) ||
			Helper::getGatewayId() !== $order->get_payment_method() ) {
			return;
		}


		if (
			isset( $_GET['lemonway-payment'], $_GET['payment'] ) &&
			$_GET['lemonway-payment'] === '1' &&
			$_GET['payment'] === 'success' ) {

			$this->lemonwayPaymentHandle( $order );
		} else {
			$message = __( 'Payment has been unsuccessful!', 'lemonway' );
			if ( isset( $transaction_details['transactions']['value'][0]['PSP'] ) ) {
				$message .= ' ' . $transaction_details['transactions']['value'][0]['PSP']['message'];
			}

			wc_add_notice( $message, 'error' );
			$redirect_url = $order->get_checkout_payment_url();
			wp_safe_redirect( $redirect_url );

			exit;
		}
	}

	public static function getCardHostedFieldsSdkUrl() {
		return esc_url_raw( Helper::isTestMode() ? 'https://sandbox-webkit.lemonway.fr/hosted-fields/sdk/@lw/hosted-fields-sdk-2.0.0.iife.js' : 'https://webkit.lemonway.fr/hosted-fields/sdk/@lw/hosted-fields-sdk-2.0.0.iife.js' );
	}

	public static function getPaypalSdkUrl() {
		$client_id         = Helper::getPaypalClientId();
		$merchant_id       = Helper::isTestMode() ? 'LR8FJ9ETQ5YC6' : 'KCTDS29WU63AG';
		$paypal_js_sdk_url = Helper::isTestMode() ? 'https://www.sandbox.paypal.com/sdk/js?' : 'https://www.paypal.com/sdk/js?';

		$currency           = get_woocommerce_currency();
		$paypal_js_sdk_url .= "client-id={$client_id}&merchant-id={$merchant_id}&currency={$currency}&intent=capture";

		return esc_url_raw( $paypal_js_sdk_url );
	}

	public function display_paypal_button() {
		?>
		<div id="lemonway-paypal-button-container">
		</div>
		<?php
	}

	/**
	 * Validation after checkout
	 *
	 * @param $data
	 * @param $errors
	 *
	 * @return void
	 * @since 3.3.0
	 */
	public function checkoutVendorValidation( $data, $errors ) {
		if ( ! Helper::isEnabled() ) {
			return;
		}
		if ( Helper::getGatewayId() !== $data['payment_method'] ) {
			return;
		}
		if ( ! is_object( WC()->cart ) ) {
			return;
		}
		$available_vendors = array();
		foreach ( WC()->cart->get_cart() as $item ) {
			$product_id = $item['data']->get_id();

			$available_vendors[ get_post_field( 'post_author', $product_id ) ][] = $item['data'];
		}

		// If it's subscription product return early.
		$subscription_product = wc_get_product( $product_id );

		if ( $subscription_product && 'product_pack' === $subscription_product->get_type() ) {
			return;
		}

		foreach ( array_keys( $available_vendors ) as $vendor_id ) {
			if ( ! Helper::isSellerEnableForReceivePayment( $vendor_id ) ) {

				$vendor_products = array();
				foreach ( $available_vendors[ $vendor_id ] as $product ) {
					$vendor_products[] = sprintf( '<a href="%s">%s</a>', $product->get_permalink(), $product->get_name() );
				}

				$errors->add(
					'lemonway-not-configured',
					wp_kses(
						sprintf(
						/* translators: %s: vendor products */
							__( '<strong>Error!</strong> Remove product %s and continue checkout, this product/vendor is not eligible to be paid with Lemonway', 'lemonway' ),
							implode( ', ', $vendor_products )
						),
						array(
							'strong' => array(),
						)
					)
				);
			}
		}
	}

	public function addToCardVendorValidation( $passed, $product_id ) {
		if ( ! Helper::isEnabled() ) {
			return $passed;
		}

		// check if payment gateway available.
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
		if ( ! array_key_exists( Helper::getGatewayId(), $available_gateways ) ) {
			return $passed;
		}

		if ( count( $available_gateways ) > 1 ) {
			return $passed;
		}

		// get post author.
		$seller_id = get_post_field( 'post_author', $product_id );

		if ( ! Helper::isSellerEnableForReceivePayment( $seller_id ) ) {
			$message = wp_kses(
				sprintf(
				// translators: 1) Product title.
					__( '<strong>Error!</strong> Could not add product %1$s to cart, this product/vendor is not eligible to be paid with %2$s', 'lemonway' ),
					get_the_title( $product_id ),
					Helper::getGatewayTitle()
				),
				array(
					'strong' => array(),
				)
			);
			wc_add_notice( $message, 'error' );

			return false;

		}

		return $passed;
	}

	public function capturePayment() {
		try {
			$order = $this->do_validation();

			$result = $this->lemonwayPaymentHandle( $order );
		} catch ( \Exception $e ) {
			$result =  array(
				'type'    => 'lemonway_capture_payment',
				'status'  => 'failed',
				'message' => $e->getMessage(),
			);
		}

		if ( $result['status'] === 'completed' ) {
			wp_send_json_success( $result );
		}
		wp_send_json_error( $result );
	}

	public function lemonwayPaymentHandle( $order ) {

		$lemonway_transaction_id = Helper::getOrderTransactionID( $order );
		$lemonway_payment_type = $order->get_meta( 'lemonway_payment_type' );

		$status = 'cancelled';
		$message = esc_html__( 'Payment has been unsuccessful!', 'lemonway' );

		if ( $lemonway_payment_type === 'paypal' ) :
			$processor = $this->payment->paypalResume( $lemonway_transaction_id );
			if ( is_wp_error( $processor ) ) {

				$message = $this->payment->errorMessage( $processor->get_error_message() );
				$log_message = sprintf(
					'Order ID: %s, Transaction ID: %s, Message: %s, API Response: %s',
					absint( $order->get_id() ),
					absint( $lemonway_transaction_id ),
					wp_strip_all_tags( $message ),
					wp_json_encode( $processor )
				);
				Helper::log( $log_message, 'Payment PayPal Resume', 'error' );

				return array(
					'type'    => 'lemonwy_payment_paypal_resume',
					'status'  => 'failed',
					'message' => esc_html__( 'Payment has been failed.', 'lemonway' ),
					'data'    => $processor,
				);
			}
		endif;

		$transaction_details = $this->transaction->retrieve( $lemonway_transaction_id );
		if ( is_wp_error( $transaction_details ) ) {
			$message = $this->payment->errorMessage( $transaction_details->get_error_message() );
			$log_message = sprintf(
				'Order ID: %s, Transaction ID: %s, Message: %s, API Response: %s',
				absint( $order->get_id() ),
				absint( $lemonway_transaction_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $transaction_details )
			);
			Helper::log( $log_message, 'Payment Transaction Retrieve', 'error' );

			return array(
				'type'    => 'lemonwy_payment_transaction_retrieve',
				'status'  => 'failed',
				'message' => esc_html__( 'Payment has been failed.', 'lemonway' ),
				'data'    => $transaction_details,
			);
		}

		$existing_history = (array) $order->get_meta( 'lemonway_payment_transactions_details' );
		$existing_history[] = $transaction_details;
		$order->update_meta_data( 'lemonway_payment_transactions_details', $existing_history );


		$transaction_amount = intval( $transaction_details['transactions']['value'][0]['creditAmount'] ) === Helper::toCents( $order->get_total() );
		$transaction_status = $transaction_details['transactions']['value'][0]['status'];
		$transaction_id = $transaction_details['transactions']['value'][0]['id'];

		if( $transaction_amount ) {
			if ( $transaction_status === 0 ) {
				$status = 'completed';
				$message = esc_html__( 'Payment has been unsuccessful!', 'lemonway' );
				$order->payment_complete();

				// Schedule event to check payment status later (e.g., in 2 minutes)
				if ( ! wp_next_scheduled( 'lemonway_payment_p2p_transaction', array( $order->get_id() ) ) ) {
					wp_schedule_single_event( time() + 10, 'lemonway_payment_p2p_transaction', array( $order->get_id() ) );
				}
			} else if ( $transaction_status === 4 ) {
				$status = 'pending';
				$message = esc_html__( 'Payment has been pending!', 'lemonway' );
			}
		}

		$all_orders = Helper::getAllOrdersToProcessed( $order );
		foreach ( $all_orders as $tmp_order ) {
			$tmp_order->add_order_note(
				sprintf(
				/* translators: %s: Transaction ID */
					esc_html__( 'Lemonway payment transaction id %s. Payment Status: %s', 'lemonway' ),
					$transaction_id,
					$status
				)
			);
			if ( $status === 'pending' ) {
				$tmp_order->update_status( 'on-hold' );
			}

			$tmp_order->update_meta_data( 'lemonway_transaction_payment_status', $status );
			$tmp_order->update_meta_data( 'lemonway_transaction_lemonway_payment_status', trim( $tmp_order->get_meta( 'lemonway_transaction_lemonway_payment_status', true ) . ', ' . sanitize_text_field( $transaction_status ), ', ' ) );

			$tmp_order->save();
		}

		$log_message = sprintf(
			'Order ID: %s, Transaction ID: %s, Message: %s, API Response: %s',
			absint( $order->get_id() ),
			absint( $lemonway_transaction_id ),
			wp_strip_all_tags( $message ),
			wp_json_encode( $transaction_details )
		);
		Helper::log( $log_message, 'Payment Transaction Retrieve', 'info' );

		return array(
			'type'    => 'lemonway_payment_verification',
			'status'  => $status,
			'message' => $message,
			'data'    => $transaction_details,
		);
	}

	/**
	 * Do the necessary validation
	 *
	 * @param $post_data
	 *
	 * @return int|string
	 * @since 3.3.0
	 */
	public function do_validation() {
		/**
		Nonce check it later
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ) ) ) {
			wp_send_json_error(
				array(
					'type'    => 'nonce',
					'message' => __( 'Nonce validation failed.', 'lemonway' ),
				)
			);
		}
		*/

		$order_id = ( isset( $_POST['order_id'] ) ) ? sanitize_key( wp_unslash( $_POST['order_id'] ) ) : 0;
		$result = array();
		if ( ! $order_id ) {
			$result =  array(
				'type'    => 'no_order_id',
				'status'  => 'failed',
				'message' => esc_html__( 'Order id not found.', 'lemonway' ),
			);
		}

		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			$result =  array(
				'type'    => 'no_order',
				'status'  => 'failed',
				'message' => esc_html__( 'Order not found.', 'lemonway' ),
			);
		}

		$transaction_id = $order->get_meta( Helper::getOrderTransactionIdKey() );
		if ( ! $transaction_id ) {
			$result =  array(
				'type'    => 'no_lemonway_transaction_id',
				'status'  => 'failed',
				'message' => esc_html__( 'Lemonway transaction id not found.', 'lemonway' ),
			);
		}

		if ( ! empty( $result ) ) {
			wp_send_json_error( $result );
		}

		return $order;
	}
}
