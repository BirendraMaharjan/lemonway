<?php
/**
 * Lemonway Account Integration Class
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Lemonway;

use WP_Error;

/**
 * Class Account
 *
 * Handles integration with Lemonway API for account management.
 *
 * @package Lemonway\Integrations\Lemonway
 * @since 1.0.0
 */
class Account extends Api {

	/**
	 * Api endpoint.
	 *
	 * @var string The type of entity for API requests
	 */
	protected $type = 'accounts';

	/**
	 * Retrieves account information by email.
	 *
	 * @param string $email The email address associated with the account.
	 * @return WP_Error | false Account information on success, WP_Error on failure.
	 */
	public function retrieve( string $email ) {
		$url  = $this->makeUrl( $this->type . '/retrieve' );
		$args = array(
			'url'  => $url,
			'data' => array(
				'accounts' => array(
					array(
						'email' => sanitize_email( $email ),
					),
				),
			),
		);

		$response = $this->makeRequest( $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( empty( $response['accounts'][0]['id'] ) ) {
			return false;
		}

		return $response['accounts'][0];
	}

	public function getDetails( string $account_id, $key = null ) {
		$url  = $this->makeUrl( $this->type . '/' . $account_id );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		$response = $this->makeRequest( $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( empty( $response['account']['id'] ) || $account_id !== $response['account']['id'] ) {
			return false;
		}

		if ( $key !== null && array_key_exists( (string) $key, $response['account'] ) ) {
			return $response['account'][ (string) $key ];
		}

		return $response['account'];
	}

	/**
	 * Checks if an email is verified in Lemonway system.
	 *
	 * @param string $email The email address to check.
	 * @return bool True if email is verified, false otherwise.
	 */
	public function verifiedEmail( string $email ): bool {
		$result = $this->retrieve( $email );
		return isset( $result['email'] ) && $result['email'] === $email;
	}

	/**
	 * Generates a unique account ID for sellers.
	 *
	 * @param string $seller_id The seller's identifier.
	 * @param int    $total_length Total length of the generated account ID.
	 * @return string The generated unique account ID.
	 */
	public function generateSellerUniqueAccountId( string $seller_id, int $total_length = 20 ): string {
		$seller_id          = (string) $seller_id;
		$random_part_length = $total_length - strlen( $seller_id );

		if ( $random_part_length <= 0 ) {
			return substr( $seller_id, 0, $total_length );
		}

		$random_string = substr( bin2hex( random_bytes( $random_part_length ) ), 0, $random_part_length );
		return $seller_id . $random_string;
	}

	/**
	 * Creates or updates a user account for a merchant.
	 *
	 * @param array    $data User account details including email, name, address, birth info, and company details.
	 * @param int|null $merchant_id Merchant ID for updating an existing account (optional).
	 *
	 * @return mixed API response from the request.
	 */
	public function userAccount( array $data, $merchant_id = null ) {
		$url    = $this->makeUrl( $this->type . '/legal' );
		$method = 'post';

		if ( $merchant_id ) :
			$url    = $this->makeUrl( $this->type . '/legal/' . $merchant_id );
			$method = 'put';
		endif;

		$args = array(
			'method' => $method,
			'url'    => $url,
			'data'   => array(
				'email'              => sanitize_email( $data['email'] ?? '' ),
				'title'              => sanitize_text_field( $data['account_title'] ?? '' ),
				'firstName'          => sanitize_text_field( $data['firstname'] ?? '' ),
				'lastName'           => sanitize_text_field( $data['lastname'] ?? '' ),
				'adresse'            => array(
					'country'  => sanitize_text_field( $data['country'] ?? '' ),
					'postCode' => sanitize_text_field( $data['postal_code'] ?? '' ),
					'city'     => sanitize_text_field( $data['city'] ?? '' ),
					'street'   => sanitize_text_field( $data['address'] ?? '' ),
				),
				'birth'              => array(
					'date'    => sanitize_text_field( $data['birth_date'] ?? '' ),
					'city'    => sanitize_text_field( $data['birth_city'] ?? '' ),
					'country' => sanitize_text_field( $data['birth_country'] ?? '' ),
				),
				'nationality'        => sanitize_text_field( $data['nationality'] ?? '' ),
				'payerOrBeneficiary' => 2,
				'company'            => array(
					'name'        => sanitize_text_field( $data['company_name'] ?? '' ),
					'description' => sanitize_text_field( $data['company_description'] ?? '' ),
				),
			),
		);

		if ( ! $merchant_id ) :
			$args['data']['accountId'] = $this->generateSellerUniqueAccountId( sanitize_text_field( $data['vendor_id'] ?? '' ) . '-user-' );
		endif;

		if ( ! empty( $data['phone'] ) ) {
			$args['phoneNumber'] = strval( sanitize_text_field( $data['phone'] ) );
		}

		if ( ! empty( $data['mobile'] ) ) {
			$args['mobileNumber'] = strval( sanitize_text_field( $data['mobile'] ) );
		}

		if ( ! empty( $data['company_identificationNumber'] ) ) {
			$args['data']['company']['identificationNumber'] = $data['company_identificationNumber'];
		}

		if ( ! empty( $data['company_websiteUrl'] ) ) {
			$args['data']['company']['websiteUrl'] = $data['company_websiteUrl'];
		}

		return $this->makeRequest( $args );
	}

	/**
	 * Uploads a document to a Lemonway account.
	 *
	 * @param string $account_id The ID of the account to upload the document to.
	 * @param int    $upload_id The attachment ID of the document to upload.
	 * @param string $upload_type The type of the document being uploaded.
	 * @return array|WP_Error Uploaded document information on success, WP_Error on failure.
	 */
	public function uploadDocument( string $account_id, int $upload_id, string $upload_type ) {
		$document_url  = wp_get_attachment_url( $upload_id );
		$document_name = basename( get_attached_file( $upload_id ) );

		$file_content = file_get_contents( esc_url( $document_url ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( $file_content === false ) {
			return new WP_Error( 'lemonway_document_error', 'Failed to get the file content.' );
		}

		$base64_encoded = base64_encode( $file_content ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$url            = $this->makeUrl( $this->type . '/' . $account_id . '/documents/upload' );

		$args = array(
			'url'  => $url,
			'data' => array(
				'type'   => sanitize_text_field( $upload_type ),
				'buffer' => sanitize_text_field( $base64_encoded ),
				'name'   => sanitize_text_field( $document_name ),
			),
		);

		return $this->makeRequest( $args );
	}

	/**
	 * Retrieves uploaded documents for a Lemonway account.
	 *
	 * @param string $account_id The ID of the account to retrieve documents for.
	 * @return array|WP_Error Retrieved document information on success, WP_Error on failure.
	 */
	public function retrieveUploadDocument( string $account_id ) {
		$url  = $this->makeUrl( $this->type . '/' . $account_id . '/documents' );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		return $this->makeRequest( $args );
	}

	public function kycstatus( string $account_id ) {
		$url  = $this->makeUrl( $this->type . '/kycstatus' );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		return $this->makeRequest( $args );
	}
}
