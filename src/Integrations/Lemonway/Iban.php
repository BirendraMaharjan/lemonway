<?php
/**
 * Lemonway Account Integration Class
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Lemonway;

use Lemonway\Integrations\Gateway\Helper;
use WP_Error;

/**
 * Class Account
 *
 * Handles integration with Lemonway API for account management.
 *
 * @package Lemonway\Integrations\Lemonway
 * @since 1.0.0
 */
class Iban extends Api {

	/**
	 * Api endpoint.
	 *
	 * @var string The type of entity for API requests
	 */
	protected $type = 'moneyouts';


	/**
	 * Retrieve IBAN information for a specific account.
	 *
	 * @param string $account_id The unique identifier for the account.
	 * @return array|WP_Error Array of IBANs on success, WP_Error on failure.
	 */
	public function retrieve( $account_id ) {
		$url  = $this->makeUrl( $this->type . '/' . $account_id . '/iban' );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		$response = $this->makeRequest( $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Validate response data
		if (empty($response['ibans']) || empty($response['ibans'][0]['id'])) {
			return new WP_Error(
				'lemonway_iban_retrieve_error',
				esc_html__('IBAN not found for this account.', 'lemonway'),
				$response
			);
		}

		return $response['ibans'];
	}

	public function unregister( $iban_id, $account_id ) {
		$url = $this->makeUrl( $this->type . '/iban/' . $iban_id . '/unregister' );

		$args = array(
			'url'    => $url,
			'method' => 'put',
			'data'   => array(
				'wallet' => $account_id,
			),
		);

		return $this->makeRequest( $args );
	}

	public function iban( array $data, string $account_id ) {
		$url = $this->makeUrl( 'moneyouts/iban/' );

		$args = array(
			'url'    => $url,
			'method' => 'post',
			'data'   => array(
				'accountId'      => $account_id,
				'holder'         => $data['iban_holder_name'],
				'bic'            => $data['iban_bic_code'],
				'iban'           => $data['iban_number'],
				'domiciliation1' => $data['iban_bank_address_line_1'],
				//'domiciliation2' => $data['iban_bank_address_line_2'],
			),
		);

		if ( ! empty( $data['comment'] ) ) {
			$args['data']['comment'] = sanitize_text_field( $data['comment'] );
		}

		return $this->makeRequest( $args );
	}

	public function bank( array $data, string $account_id ) {
		$url = $this->makeUrl( 'moneyouts/iban/extended/' );

		$args = array(
			'url'    => $url,
			'method' => 'post',
			'data'   => array(
				'wallet'                  => $account_id,
				'accountType'             => 1,
				'holderName'              => 'Jean Dupont',
				'accountNumber'           => 'GB33BUKB20201555555555',
				'holderCountry'           => 'US',
				'bicCode'                 => '0002029205',
				'bankName'                => 'CHBFSFCU',
				'bankCountry'             => 'US',
				'intermediaryBicCode'     => 'IRVTDEFX',
				'intermediaryBankName'    => 'Bank OF NEW YORK MELLON',
				'bankBranchCode'          => '0002029205',
				'intermediaryBankCountry' => 'DE',
				'bankBranchAddress'       =>
					array(
						'Street'  => 'Dupont',
						'ZipCode' => '44600',
						'City'    => 'Kathmandu',
					),
				'comment'                 => 'Previous bank account closed',
			),
		);

		$args = array(
			'url'    => $url,
			'method' => 'post',
			'data'   => array(
				'wallet'                  => $account_id,
				'accountType'             => $data['bank_account_type'],
				'holderName'              => $data['bank_holder_name'],
				'accountNumber'           => $data['bank_account_number'],
				'holderCountry'           => $data['bank_holder_country'],
				'bicCode'                 => $data['bank_bic_code'],
				'bankName'                => $data['bank_name'],
				'bankCountry'             => $data['bank_country'],
				'bankBranchCode'          => $data['bank_branch_code'],
				'intermediaryBankCountry' => 'DE',
				'bankBranchAddress'       =>
					array(
						'Street'  => $data['bank_branch_street'],
						'ZipCode' => $data['bank_branch_zip_code'],
						'City'    => $data['bank_branch_city'],
					),
			),
		);

		if ( ! empty( $data['comment'] ) ) {
			$args['data']['comment'] = sanitize_text_field( $data['comment'] );
		}
		return $this->makeRequest( $args );
	}

	public function isLinkedIban( $merchant_id = null ) {

		if ( empty( $merchant_id ) ) {
			$merchant_id = Helper::getMerchantId();
		}
		$iban = $this->retrieve( $merchant_id );

		if ( is_wp_error( $iban ) ) {
			return false;
		}

		if ( ! empty( $iban[0]['id'] ) ) {
			return true;
		}

		return false;
	}

	public function withdraw( array $data, string $account_id ) {
		$url = $this->makeUrl( $this->type );

		$args = array(
			'url'    => $url,
			'method' => 'post',
			'data'   => $data,
		);

		return $this->makeRequest( $args );
	}
}
