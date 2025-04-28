<?php
/**
 * Lemonway Refund Integration Class
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

use Lemonway\Integrations\Gateway\Helper;
use Lemonway\Integrations\Lemonway\Payment;
use Lemonway\Integrations\Lemonway\Refund as LemonwayRefund;
use WeDevs\Dokan\Exceptions\DokanException;
use WP_Error;

/**
 * Class Refund
 *
 * Handles the Refund for the Dokan plugin with Lemonway.
 */
class Refund extends Dokan {

	/**
	 * Refund constructor.
	 *
	 * Initializes the class and hooks.
	 */
	public function init() {

		add_filter(
			'dokan_excluded_gateways_from_auto_process_api_refund',
			array(
				$this,
				'exclude_from_auto_process_api_refund',
			)
		);

		add_action( 'dokan_refund_request_created', array( $this, 'processRefund' ) );
		add_filter( 'dokan_refund_approve_vendor_refund_amount', array( $this, 'vendor_refund_amount' ), 100, 3 );
	}


	public function exclude_from_auto_process_api_refund( $gateways ) {
		$gateways[ Helper::getGatewayId() ] = Helper::getGatewayTitle();

		return $gateways;
	}

	public function vendor_refund_amount( $vendor_refund, $args, $refund ) {

		$order       = wc_get_order( $refund->get_order_id() );
		$seller_id   = $refund->get_seller_id();
		$merchant_id = Helper::getMerchantId( $seller_id );

		// p2p from vendor to technical account before refund.
		$data = array(
			'debitAccountId'  => $merchant_id,
			'creditAccountId' => Helper::getTechnicalAccountId(),
			'amount'          => absint( Helper::toCents( $vendor_refund ) ),
			'comment'         => Helper::limitText(
				sprintf(
					'Refund settlement. Order id: %s Vendor id: %s Request Amount: %s Reason: %s',
					absint( $order->get_id() ),
					absint( $seller_id ),
					sanitize_text_field( $refund->get_refund_amount() ),
					sanitize_text_field( $refund->get_refund_reason() )
				),
				140
			),
		);

		$payment_response = $this->lemonway_payment->p2p( $data );

		if ( is_wp_error( $payment_response ) ) {

			$message  = esc_html__( 'Refund has been canceled.', 'lemonway' );
			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= $this->lemonway_api->errorMessage( $payment_response->get_error_message() );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $payment_response )
			);

			Helper::log( $log_message, 'Refund', 'error' );

			return $vendor_refund;
		}

		if ( 3 !== $payment_response['transaction']['status'] ) {

			$message  = esc_html__( 'Automatic refund is not possible for this order.', 'lemonway' );
			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= $this->lemonway_api->errorMessage( $payment_response->get_error_message() );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $payment_response )
			);

			Helper::log( $log_message, 'Refund', 'error' );

			return $vendor_refund;

		}

		$message = sprintf(
		/* translators: 1: Refund amount 2: Refund transaction id 3: Refund message */
			__( 'Refunded and settlement: %1$s. Refund ID: %2$s. Reason - %3$s', 'lemonway' ),
			wc_price( $refund->get_refund_amount(), array( 'currency' => $order->get_currency() ) ),
			$payment_response['transaction']['id'],
			$refund->get_refund_reason()
		);

		$log_message = sprintf(
			'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
			absint( $refund->get_id() ),
			absint( $refund->get_order_id() ),
			absint( $seller_id ),
			wp_strip_all_tags( $message ),
			wp_json_encode( $payment_response )
		);
		Helper::log( $log_message, 'Refund & settlement', 'info' );

		$order->update_meta_data( 'lemonway_refund_p2p_status_' . $refund->get_id(), 'completed' );
		$payment_existing_history   = (array) $order->get_meta( 'lemonway_payment_refund_settlement_details' );
		$payment_existing_history[] = $payment_response;
		$order->update_meta_data( 'lemonway_payment_refund_settlement_details', $payment_existing_history );
		// p2p from vendor to technical account before refund.

		return $vendor_refund;
	}

	/**
	 * Process refund request.
	 *
	 * @since 3.5.0
	 *
	 * @param object $refund refund.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function processRefund( $refund ) {
		// Get code editor suggestion on refund object.
		if ( ! $refund instanceof \WeDevs\DokanPro\Refund\Refund ) {
			return;
		}

		// Check if gateway is ready.
		if ( ! Helper::isReady() ) {
			return;
		}

		$order = wc_get_order( $refund->get_order_id() );

		// Return if $order is not instance of WC_Order.
		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		// Return if not paid with lemonway payment gateway.
		if ( Helper::getGatewayId() !== $order->get_payment_method() ) {
			return;
		}

		$seller_id = $refund->get_seller_id();

		// check if refund is approval.
		if ( ! dokan_pro()->refund->is_approvable( $refund->get_order_id() ) ) {

			$message = esc_html__( 'This refund is not allowed to approve', 'lemonway' );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			return;
		}

		$parent_order = ! empty( $order->get_parent_id() ) ? wc_get_order( $order->get_parent_id() ) : $order;

		// Check if transaction id exists.
		$transaction_id = Helper::getOrderTransactionID( $parent_order );
		if ( ! $transaction_id ) {

			$message = esc_html__( 'Refund has been canceled. No Lemonway transaction id is found', 'lemonway' );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			throw new DokanException( 'dokan_rest_refund_error', wp_kses_post( $message ), 400 );
		}

		// get merchant id.
		$merchant_id = Helper::getMerchantId( $seller_id );
		if ( ! $merchant_id ) {

			$message = esc_html__( 'Refund has been canceled. No Lemonway Merchant id is found.', 'lemonway' );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			throw new DokanException( 'dokan_rest_refund_error', wp_kses_post( $message ), 400 );
		}

		/**
		 * Handles manual refund.
		 * Here the order is being approved only if it is a manual refund.
		 */
		if ( $refund->is_manual() ) {
			return;
		}

		$balance = $this->account->getDetails( $merchant_id, 'balance' );
		$refund->set_refund_reason( '$message' )->cancel();

		if ( $balance < $refund->get_refund_amount() ) {

			$message  = esc_html__( 'Refund has been canceled.', 'lemonway' );
			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= sprintf( __( 'Amount is more than your Lemonway account balance (Lemonway balance: %s).', 'lemonway' ), html_entity_decode( strip_tags( wc_price( floatval( $balance / 100 ) ) ) ) );

			$log_message = sprintf(
				__( 'Refund ID: %1$s, Order ID: %2$s, Vendor ID: %3$s, Message: %4$s, API Response: %5$s', 'lemonway' ),
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $balance )
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			throw new DokanException( 'dokan_rest_refund_error', wp_kses_post( $message ), 400 );

		}

		// Refund to customer.
		$data = array(
			'amountToRefund' => Helper::toCents( $refund->get_refund_amount() ),
			'comment'        => Helper::limitText(
				sprintf(
					'Refund. Order id: %s Vendor id: %s Reason: %s',
					absint( $order->get_id() ),
					absint( $seller_id ),
					sanitize_text_field( $refund->get_refund_reason() )
				),
				140
			),
		);

		$lemonway_refund = $this->lemonway_refund->refund( $transaction_id, $data );

		if ( is_wp_error( $lemonway_refund ) ) {

			$message  = esc_html__( 'Refund has been canceled.', 'lemonway' );
			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= $this->lemonway_api->errorMessage( $lemonway_refund->get_error_message() );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $lemonway_refund )
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			throw new DokanException( 'dokan_rest_refund_error', wp_kses_post( $message ), 400 );
		}

		if ( 0 !== $lemonway_refund['transaction']['status'] ) {

			$message = esc_html__( 'Refund has been canceled.', 'lemonway' );

			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= $this->lemonway_api->errorMessage( $lemonway_refund->get_error_message() );

			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $lemonway_refund )
			);

			Helper::log( $log_message, 'Refund', 'error' );

			$refund->set_refund_reason( $message )->cancel();

			throw new DokanException( 'dokan_rest_refund_error', wp_kses_post( $message ), 400 );
		}

		$message = sprintf(
		/* translators: 1: Refund amount 2: Refund transaction id 3: Refund message */
			__( 'Refunded from account: %1$s. Refund ID: %2$s. Reason - %3$s', 'lemonway' ),
			wc_price( $refund->get_refund_amount(), array( 'currency' => $order->get_currency() ) ),
			$lemonway_refund['transaction']['id'],
			$refund->get_refund_reason()
		);

		$log_message = sprintf(
			'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
			absint( $refund->get_id() ),
			absint( $refund->get_order_id() ),
			absint( $seller_id ),
			wp_strip_all_tags( $message ),
			wp_json_encode( $lemonway_refund )
		);
		Helper::log( $log_message, 'Refund', 'info' );
		$order->add_order_note( $message );

		$order->update_meta_data( 'lemonway_refund_p2p_status_' . $refund->get_id(), 'processed' );
		$existing_history   = (array) $order->get_meta( 'lemonway_payment_refund_details' );
		$existing_history[] = $existing_history;
		$order->update_meta_data( 'lemonway_payment_refund_details', $existing_history );

		// store refund id as array, this will help track all partial refunds.
		$refund_ids   = (array) Helper::getRefundIdsByOrder( $order );
		$refund_ids[] = $lemonway_refund['transaction']['id'];
		$order->update_meta_data( Helper::getRefundIdsByOrderKey(), $refund_ids );

		// save metadata.
		$order->save();

		$args = array(
			Helper::getGatewayId()         => true,
			'lemonway_refund_id'           => $lemonway_refund['transaction']['id'],
			'lemonway_debit_account_id'    => $merchant_id,
			'lemonway_credit_account_id'   => Helper::getTechnicalAccountId(),
			'lemonway_sender_account'      => $lemonway_refund['transaction']['senderAccountId'],
			'lemonway_lemonway_commission' => $lemonway_refund['transaction']['lemonWayCommission'],
		);

		// Try to approve the refund.
		$refund = $refund->approve( $args );

		if ( is_wp_error( $refund ) ) {
			$message     = esc_html__( 'Refund has been canceled. Refund Failed', 'lemonway' );
			$log_message = sprintf(
				'Refund ID: %s, Order ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
				absint( $refund->get_id() ),
				absint( $refund->get_order_id() ),
				absint( $seller_id ),
				wp_strip_all_tags( $message ),
				wp_json_encode( $refund )
			);
			Helper::log( $log_message, 'Refund', 'error' );
		}
	}

	/**
	 * Withdraw entry for automatic refund as debit.
	 *
	 * This method adds a vendor withdraw entry for automatic refunds processed through the Online Payment Platform Gateway.
	 * It records essential information in the Dokan vendor balance table, such as vendor ID, transaction ID, transaction type,
	 * particulars, debit amount, credit amount, status, transaction date, and balance date.
	 *
	 * @param \WeDevs\DokanPro\Refund\Refund $refund The refund object.
	 * @param array                          $args An array of arguments provided for the refund.
	 * @param float                          $vendor_refund The vendor refund amount.
	 *
	 * @return void
	 */
	public function add_vendor_withdraw_entry( $refund, $args, $vendor_refund ) {

		$order = wc_get_order( $refund->get_order_id() );

		// return if $order is not instance of WC_Order.
		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		// return if not paid with dokan paypal marketplace payment gateway.
		if ( Helper::getGatewayId() !== $order->get_payment_method() ) {
			return;
		}

		global $wpdb;

		//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->insert(
			$wpdb->dokan_vendor_balance,
			array(
				'vendor_id'    => $refund->get_seller_id(),
				'trn_id'       => $refund->get_order_id(),
				'trn_type'     => 'dokan_refund',
				'perticulars'  => maybe_serialize( $args ),
				'debit'        => $vendor_refund,
				'credit'       => 0,
				'status'       => 'wc-completed',
				'trn_date'     => current_time( 'mysql' ),
				'balance_date' => current_time( 'mysql' ),
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%f',
				'%f',
				'%s',
				'%s',
				'%s',
			)
		);
	}
}
