<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Gateway;

use Lemonway\Common\Utils\Errors;
use Lemonway\Integrations\Lemonway\Payment;
use Lemonway\Integrations\Lemonway\Transaction;
use WC_Background_Process;
use WP_Query;

/**
 * Class BackgroundProcess
 *
 * @package OnlinePaymentPlatformGateway\Integrations\Widget
 */
class BackgroundProcess {
	/**
	 * Initialize the class.
	 */
	public function init() {
		/**
		 * Integration classes instantiates before anything else
		 *
		 * @see Bootstrap::__construct
		 *
		 * Widget is registered via the app/general/widgets class, but it is also
		 * possible to register from this class
		 * @see Widgets
		 */

		$this->hooks();
		$this->schedule_event();
	}


	/**
	 * Init all the hooks
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'lemonway_payment_p2p_transaction', array( $this, 'schedule_p2p_transaction' ) );
		add_action( 'lemonway_payment_status_checking', array( $this, 'payment_status_checking' ) );

		// add_action( 'wp_ajax_test_order_cron_hook', array( $this, 'schedule_p2p_transaction' ) );
		// add_action( 'wp_ajax_nopriv_test_order_cron_hook', array( $this, 'schedule_p2p_transaction' ) );
	}


	/**
	 * Schedule event.
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( 'lemonway_payment_p2p_transaction' ) ) {
			wp_schedule_event( time(), 'hourly', 'lemonway_payment_p2p_transaction' );
		}
		if ( ! wp_next_scheduled( 'lemonway_payment_status_checking' ) ) {
			wp_schedule_event( time(), 'hourly', 'lemonway_payment_status_checking' );
		}
	}

	public function payment_status_checking( $order_id = null ) {
		$args = array(
			'limit'        => -1,
			'post_status'  => 'any',
			'meta_key'     => 'lemonway_transaction_payment_status', // phpcs:ignore
			'meta_value'   => 'pending', // phpcs:ignore
			'meta_compare' => '=', // phpcs:ignore
		);

		if ( $order_id ) {
			$args['post__in'] = array( $order_id );
		}

		$orders = wc_get_orders( $args );

		if ( ! $orders ) {
			return false;
		}

		foreach ( $orders as $order ) {
			if ( $order->has_status( 'completed' ) || $order->has_status( 'processing' ) ) {
				continue;
			}

			$status = $order->get_meta( 'lemonway_transaction_payment_status' );

			if ( $status === 'completed' || $status === 'canceled' ) {
				continue;
			}

			$lemonway_transaction_id = Helper::getOrderTransactionID( $order );

			if ( empty( $lemonway_transaction_id ) ) {
				continue;
			}

			$transaction         = new transaction();
			$transaction_details = $transaction->retrieve( $lemonway_transaction_id );

			if ( is_wp_error( $transaction_details ) ) {
				continue;
			}

			( new Handler() )->lemonwayPaymentHandle( $order );
		}
	}

	/**
	 * Regenerate all lookup table data.
	 */
	public function schedule_p2p_transaction() {
		$i    = 0;
		$args = array(
			'post_type'      => 'shop_order',
			'post_status'    => array( 'wc-processing', 'wc-on-hold', 'wc-completed' ),
			'posts_per_page' => -1,
			'post_parent'    => 0,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'AND',
				array(
					'key'     => 'lemonway_transaction_payment_status',
					'value'   => 'completed',
					'compare' => '=',
				),
				array(
					'key'     => 'lemonway_payment_type',
					'compare' => 'EXISTS',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => 'lemonway_p2p_transaction_status',
						'value'   => 'completed',
						'compare' => '!=',
					),
					array(
						'key'     => 'lemonway_p2p_transaction_status',
						'compare' => 'NOT EXISTS',
					),
				),
			),
		);

		$query  = new WP_Query( $args );
		$orders = $query->posts;

		if ( ! $orders ) {
			return false;
		}

		$transaction = new Transaction();
		$payment     = new Payment();

		foreach ( $orders as $order ) {

			$order = wc_get_order( $order->ID );

			if ( $order->get_meta( 'lemonway_p2p_transaction_status' ) === 'completed' ) {
				continue;
			}

			if ( ! $order->has_status( 'processing' ) && ! $order->has_status( 'on-hold' ) && ! $order->has_status( 'wc-completed' ) ) {
				continue;
			}

			$order_id                     = $order->get_id();
			$lemonway_order_transition_id = Helper::getOrderTransactionID( $order_id );

			if ( ! $lemonway_order_transition_id ) {
				continue;
			}

			$response = $transaction->retrieve( $lemonway_order_transition_id );

			if ( is_wp_error( $response ) ) {
				Errors::writeLogCron( $response );
				Helper::log( $response, 'settlement p2p', 'debug', 'lemonway-settlement' );
				continue;
			}

			$transaction_status = $response['transactions']['value'][0]['status'];
			if ( $transaction_status !== 0 ) {
				continue;
			}

			$all_orders = Helper::getAllOrdersToProcessed( $order );

			$transaction_details = array();
			foreach ( $all_orders as $tmp_order ) {

				if ( ! empty( $tmp_order->get_meta( 'lemonway_p2p_transaction_id', true ) ) ) {
					continue;
				}

				$tmp_order_id       = $tmp_order->get_id();
				$vendor_id          = dokan_get_seller_id_by_order( $tmp_order_id );
				$vendor             = dokan()->vendor->get( $vendor_id );
				$vendor_raw_earning = dokan()->commission->get_earning_by_order( $tmp_order, 'seller' );
				$total_cost         = Helper::toCents( $tmp_order->get_total() );

				$store_info = dokan_get_store_info( $vendor_id );

				// 19% extra commission for Germany vendor
				$store_country = $store_info['address']['country'] ?? '';
				if ( $store_country === 'DE' ) {
					$vendor_raw_earning = $vendor_raw_earning + ( $vendor_raw_earning * 19 / 100 );
				}

				$data = array(
					'debitAccountId'  => Helper::getTechnicalAccountId(),
					'creditAccountId' => Helper::getMerchantId( $vendor_id ),
					'amount'          => Helper::toCents( $vendor_raw_earning ),
					'comment'         => "Settlement order_id {$order_id} and sub_order_id {$tmp_order_id} ",
				);

				$payment_response = $payment->p2p( $data );

				if ( is_wp_error( $payment_response ) ) {

					Errors::writeLogCron( $payment_response );


					$message     = esc_html__( 'P2P transition has been unsuccessful!', 'lemonway' );
					$log_message = sprintf(
						'Lemonway settlement transition, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
						absint( $tmp_order_id ),
						absint( $vendor_id ),
						wp_strip_all_tags( $message ),
						wp_json_encode( $payment_response )
					);
					Helper::log( $log_message, 'settlement p2p', 'info', 'lemonway-settlement' );
					break;
				}

				if ( $payment_response['transaction']['status'] !== 3 ) {
					continue;
				}

				$tmp_order->add_order_note(
					wp_kses_post(
						sprintf(
							'Lemonway settlement transition ID: %s',
							esc_html( $payment_response['transaction']['id'] )
						)
					)
				);

				$message     = esc_html__( 'P2P transition has been successful!', 'lemonway' );
				$log_message = sprintf(
					'Lemonway settlement transition ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
					absint( $payment_response['transaction']['id'] ),
					absint( $tmp_order_id ),
					absint( $vendor_id ),
					wp_strip_all_tags( $message ),
					wp_json_encode( $payment_response )
				);
				Helper::log( $log_message, 'settlement p2p', 'info', 'lemonway-settlement' );

				// Get existing transaction history from order meta.
				$existing_history = $order->get_meta( 'lemonway_payment_settlement_details' );

				// Initialize history array if empty.
				if ( empty( $existing_history ) ) {
					$existing_history = array();
				}
				// Add new transaction to the history.
				$existing_history[] = $payment_response;

				$transaction_details[] = $existing_history;

				$tmp_order->update_meta_data( 'lemonway_p2p_transaction_id', $payment_response['transaction']['id'] );

				$tmp_order->update_meta_data( 'lemonway_p2p_transaction_details', $payment_response );

				$tmp_order->save();

				$order->update_meta_data( 'lemonway_p2p_transaction_details', $transaction_details );
				$order->save();
			}

			if ( isset( $payment_response['transaction']['id'] ) ) :
				$order->update_meta_data( 'lemonway_p2p_transaction_status', 'completed' );

				$order->save();
			endif;

		}
	}
}
