<?php
/**
 * Lemonway Withdraw Integration Class
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

use Lemonway\Integrations\Gateway\Helper;
use Lemonway\Integrations\Lemonway\Iban;
use Lemonway\Integrations\Lemonway\Payment;
use WC_Data_Exception;
use WeDevs\Dokan\Exceptions\DokanException;
use WeDevs\Dokan\Traits\RESTResponseError;
use WP_Error;

/**
 * Class Withdraw
 *
 * @package OnlinePaymentPlatformGateway\Integrations\Dokan
 */
class Withdraw extends Dokan {

	use RESTResponseError;

	/**
	 * Withdraw constructor.
	 *
	 * Initializes the class and hooks.
	 */
	public function init() {

		add_filter( 'dokan_get_seller_balance', array( $this, 'sellerBalance' ), PHP_INT_MAX, 1 );

		// add_filter( 'dokan_get_formatted_seller_balance', array($this, 'sellerBalance'), PHP_INT_MAX, 1 );

		add_filter( 'dokan_withdraw_is_valid_request', array( $this, 'processWithdraw' ), PHP_INT_MAX, 2 );
		add_filter( 'dokan_withdraw_manual_request_enable', array( $this, 'validWithdraw' ), PHP_INT_MAX, 1 );
		add_action( 'dokan_withdraw_content_after_balance', array( $this, 'lemonwayBalance' ) );
	}

	public function sellerBalance( $earning ) {
		return floor( $earning * pow( 10, wc_get_price_decimals() ) ) / pow( 10, wc_get_price_decimals() );
	}


	public function lemonwayBalance() {

		$merchant_id = Helper::getMerchantId();
		$balance     = $this->account->getDetails( $merchant_id, 'balance' );
		if ( is_wp_error( $balance ) ) {
			esc_html_e( 'Please reload and try later.', 'lemonway' );

			return false;
		}
		?>
		<p>
			<?php esc_html_e( 'Lemonway Balance:', 'lemonway' ); ?>
			<strong
				class="lemonway-text-success"><?php echo wp_kses_post( wc_price( floatval( $balance / 100 ) ) ); ?></strong>
		</p>
		<?php
	}

	public function validWithdraw( $result ) {

		$merchant_id = Helper::getMerchantId();

		$iban = $this->iban->retrieve( $merchant_id );

		if ( is_wp_error( $iban ) ) {

			// Special handling for missing IBAN
			if ($iban->get_error_code() === 'lemonway_iban_retrieve_error') {
				$this->displayLinkBankMessage();
				return false;
			}
			echo esc_html( $iban->get_error_message() );

			return false;
		}

		// Check if any IBAN has status 5 (verified)
		$verified_ibans = array_filter($iban, function($item) {
			return isset($item['status']) && $item['status'] === 5;
		});

		if (!empty($verified_ibans)) {
			// Valid IBAN found, return result
			return $result; // Note: You need to define $result in your actual code
		}

		// IBAN exists but is not verified
		printf(
			'<p class="iban-status">%s</p>',
			esc_html__('IBAN has not been verified yet.', 'lemonway')
		);

		return false;
	}

	/**
	 * Display message prompting user to link their bank account.
	 */
	public function displayLinkBankMessage() {
		esc_html_e('Please connect your bank account first.', 'lemonway');
		$bank_link_url = esc_url(dokan_get_navigation_url('settings/payment-manage-lemonway-edit') . '?link-bank');
		echo '<br><a href="' . $bank_link_url . '">' . esc_html__('Link Bank', 'lemonway') . '</a>';
	}

	public function processWithdraw( $result, $args ) {
		$request = $_REQUEST;
		if ( ! isset( $request['status'] ) || $request['status'] !== 'approved' ) {
			return $result;
		}

		$seller_id            = $args['user_id'];
		$withdraw_amount      = $args['amount'];
		$withdraw_id          = $args['id'];
		$withdraw_details     = $args['id'];
		$withdraw_method      = $args['method'];
		$withdraw_charge      = $args['charge'];
		$withdraw_receivable  = $args['receivable'];
		$withdraw_charge_data = $args['charge_data'];
		$withdraw_note        = $args['note'];

		// check for merchant id.
		$merchant_id = Helper::getMerchantId( $seller_id );

		if ( ! $merchant_id ) {
			$message     = esc_html__(
				'Withdrawal has been canceled. Reason: No Lemonway Merchant id is found',
				'lemonway'
			);
			$log_message = sprintf(
				'Withdrawal ID: %s, Vendor ID: %s, Message: %s',
				absint( $withdraw_id ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Withdraw', 'debug'   );

			return new WP_Error( 'lemonway_dokan_rest_withdraw_error', wp_kses_post( $message ) );
		}

		$iban = $this->iban->retrieve( $merchant_id );

		if ( is_wp_error( $iban ) ) {

			$message = esc_html__(
				'Withdrawal has been canceled. Reason: No Lemonway IBAN id is found.',
				'lemonway'
			);

			$log_message = sprintf(
				'Withdrawal ID: %s, Vendor ID: %s, Message: %s',
				absint( $withdraw_id ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Withdraw', 'debug' );

			return new WP_Error( 'lemonway_dokan_rest_withdraw_error', wp_kses_post( $message ) );
		}

		if ( $iban[0]['status'] !== 5 ) {

			$message = esc_html__(
				'Withdrawal has been canceled. Reason: IBAN has not been verified yet.',
				'lemonway'
			);

			$log_message = sprintf(
				'Withdrawal ID: %s, Vendor ID: %s, Message: %s',
				absint( $withdraw_id ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Withdraw', 'debug' );

			return new WP_Error( 'lemonway_dokan_rest_withdraw_error', wp_kses_post( $message ) );
		}

		$data = array(
			'accountId'        => $merchant_id,
			'ibanId'           => $iban[0]['id'],
			'totalAmount'      => Helper::toCents( $withdraw_amount ),
			'commissionAmount' => Helper::toCents( $withdraw_charge ),
			'comment'          => "Withdraw Seller id: {$seller_id} Withdraw id: {$withdraw_id} Note: {$withdraw_note} ",
			'autoCommission'   => false,
			'reference'        => "withdraw id: {$withdraw_id}",
		);

		$lemonway_withdraw = $this->iban->withdraw( $data, $merchant_id );

		if ( is_wp_error( $lemonway_withdraw ) ) {

			$message  = esc_html__(
				'Withdrawal has been canceled.',
				'lemonway'
			);
			$message .= ' Reason: ';
			$message .= $this->lemonway_api->errorMessage( $lemonway_withdraw->get_error_message() );

			$log_message = sprintf(
				'Withdrawal ID: %s, Vendor ID: %s, Message: %s',
				absint( $withdraw_id ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Withdraw', 'debug' );

			return new WP_Error( 'lemonway_dokan_rest_withdraw_error', wp_kses_post( $message ) );

		}

		if ( ! empty( $lemonway_withdraw['id'] ) && 3 !== $lemonway_withdraw['status'] ) {

			$message  = esc_html__(
				'Withdrawal has been canceled.',
				'lemonway'
			);
			$message .= ' ';
			$message .= esc_html__( 'Reason: ', 'lemonway' );
			$message .= $lemonway_withdraw['error_message'];

			$log_message = sprintf(
				'Withdrawal ID: %s, Vendor ID: %s, Message: %s',
				absint( $withdraw_id ),
				absint( $seller_id ),
				$message
			);

			Helper::log( $log_message, 'Withdraw', 'debug' );

			return new WP_Error( 'lemonway_dokan_rest_withdraw_error', wp_kses_post( $message ) );
		}

		$message = sprintf(
		/* translators: 1: Withdraw amount */
			__( 'Withdraw for amount: %s', 'lemonway' ),
			$withdraw_amount
		);

		$log_message = sprintf(
			'Withdraw ID: %s, Vendor ID: %s, Message: %s, API Response: %s',
			absint( $withdraw_amount ),
			absint( $seller_id ),
			$message,
			wp_json_encode( $lemonway_withdraw )
		);
		Helper::log( $log_message, 'Withdraw', 'info' );

		// Get existing transaction history from order meta.
		$existing_history = get_user_meta( $seller_id, 'lemonway_payment_withdraw_details', true );

		// Initialize history array if empty.
		if ( empty( $existing_history ) ) {
			$existing_history = array();
		}

		// Add new transaction to the history.
		$existing_history[] = $lemonway_withdraw;
		update_user_meta( $seller_id, 'lemonway_payment_withdraw_details', $existing_history );

		$seller_withdraw_ids = get_user_meta( $seller_id, 'lemonway_payment_withdraw_ids', true ) ? get_user_meta( $seller_id, 'lemonway_payment_withdraw_ids', true ) : array();
		if ( ! in_array( $lemonway_withdraw['transaction']['id'], $seller_withdraw_ids, true ) ) {
			$seller_withdraw_ids[] = $lemonway_withdraw['transaction']['id'];
		}
		update_user_meta( $seller_id, 'lemonway_payment_withdraw_ids', $seller_withdraw_ids );

		return $result;
	}
}
