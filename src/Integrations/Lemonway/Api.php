<?php
/**
 * Lemonway Integration
 *
 * @package   Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Lemonway;

use Lemonway\Integrations\Gateway\Helper;
use WP_Error;


/**
 * Class Api
 *
 * @package Lemonway\Integrations\Lemonway
 * @since 1.0.0
 */
class Api {

	/**
	 * Indicates whether the API is in test mode.
	 *
	 * @var bool Indicates if the API is in test mode.
	 */
	protected $test_mode = false;

	/**
	 * This URL is used to authenticate API requests.
	 *
	 * @var string URL for API authentication.
	 */
	protected $api_auth_url = 'https://auth.lemonway.com/oauth/api/v1/oauth/token';

	/**
	 * Base URL for the API requests.
	 *
	 * @var string Base URL for the API.
	 */
	protected $api_base_url;

	/**
	 * Additional request headers for API requests.
	 *
	 * @var array Additional request headers.
	 */
	protected $additional_request_header = array();

	/**
	 * The meta key for storing the Lemonway bearer access token.
	 *
	 * This key is used to save and retrieve the access token
	 * for Lemonway API authentication in the WordPress database.
	 *
	 * @var string
	 */
	protected $bearer_token_key = '_lemonway_bearer_access_token';

	/**
	 * Initializes the API.
	 */
	public function init() {
	}

	/**
	 * Api constructor.
	 * Sets the API to test mode if applicable and initializes base URL.
	 */
	public function __construct() {
		if ( Helper::isTestMode() ) {
			$this->test_mode        = true;
			$this->bearer_token_key = '_lemonway_test_bearer_access_token';
			$this->api_auth_url     = 'https://sandbox-api.lemonway.fr/oauth/api/v1/oauth/token';
		}
		$this->api_base_url = Helper::getApiEndpoint();
	}

	/**
	 * Displays an admin notice with the API error message.
	 */
	public function adminNotice(): void {
		$this->apiErrorMessage( true );
	}

	/**
	 * Constructs a full URL for API requests.
	 *
	 * @param string $path The API path.
	 * @return string The full URL.
	 */
	public function makeUrl( $path ): string {
		return $this->api_base_url . 'v2/' . $path;
	}

	/**
	 * Retrieves the API key.
	 *
	 * @return string The API key.
	 */
	public function getApiKey(): string {
		return Helper::getApiKey();
	}


	/**
	 * Constructs the headers for an API request.
	 *
	 * @param bool $content_type_json Whether the content type is JSON.
	 * @param bool $request_with_token Whether to include a bearer token.
	 * @return array|WP_Error|bool|string The headers or WP_Error on failure.
	 */
	public function getHeader( bool $content_type_json = true, bool $request_with_token = false ) {
		$content_type = $content_type_json ? 'json' : 'x-www-form-urlencoded';
		$headers      = array( 'Content-Type' => 'application/' . $content_type );

		if ( ! $request_with_token ) {
			$headers['Authorization'] = 'Basic ' . $this->getApiKey();
			$headers['accept']        = 'application/json';
			$headers['Ignorecache']   = true;
			return $headers;
		}

		$access_token = $this->getBearerToken();

		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}

		$headers['Authorization']  = 'Bearer ' . $access_token;
		$headers['PSU-IP-Address'] = Helper::getPsuIpAddress();

		return array_merge( $headers, $this->additional_request_header );
	}

	/**
	 * Makes a GET request to the specified URL.
	 *
	 * @param string $url The URL to request.
	 * @return WP_Error|bool|array|string The response body or WP_Error on failure.
	 */
	public function getRequest( $url ) {
		$header = $this->getHeader( true, true );

		if ( is_wp_error( $header ) ) {
			return $header;
		}

		$args = array(
			'timeout'     => '30',
			'redirection' => '30',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $header,
			'cookies'     => array(),
		);

		$response = wp_remote_get( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = wp_remote_retrieve_body( $response );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'lemonway_request_error', $body, $response['response'] );
		}

		return json_decode( $body, true );
	}

	/**
	 * Makes a request with the specified parameters.
	 *
	 * @param array $data The request parameters.
	 * @return array|WP_Error|bool|string The response body or WP_Error on failure.
	 */
	public function makeRequest( array $data = array() ) {
		$defaults = array(
			'url'                => '',
			'data'               => array(),
			'method'             => 'post',
			'header'             => true,
			'content_type_json'  => true,
			'request_with_token' => true,
		);

		$parsed_args = wp_parse_args( $data, $defaults );

		$header = $parsed_args['header'] === true ? $this->getHeader( $parsed_args['content_type_json'], $parsed_args['request_with_token'] ) : array();

		if ( is_wp_error( $header ) ) {
			return $header;
		}

		$args = array(
			'timeout'     => '120',
			'redirection' => '120',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $header,
			'cookies'     => array(),
		);

		if ( ! empty( $parsed_args['data'] ) ) {
			$args['body'] = $parsed_args['content_type_json'] ? wp_json_encode( $parsed_args['data'] ) : $parsed_args['data'];
		}

		$valid_methods = array( 'get', 'post', 'put', 'delete', 'patch' );
		$method        = strtolower( $parsed_args['method'] );

		$args['method'] = in_array( $method, $valid_methods, true ) ? strtoupper( $method ) : 'POST';
		$response       = wp_remote_request( esc_url_raw( $parsed_args['url'] ), $args );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'lemonway_request_error', $response->get_error_message() );
		}

		$body     = json_decode( wp_remote_retrieve_body( $response ), true );
		$debug_id = wp_remote_retrieve_header( $response, 'cf-ray' );

		if ( isset( $body['Error'] ) ) {
			return new WP_Error( 'lemonway_request_error', $this->errorMessage( $body['Error']['Message'] ) );
		}

		if ( isset( $body['code'] ) ) {
			return new WP_Error( 'lemonway_request_error', $this->errorMessage( $body['message'] ) );
		}

		if ( isset( $body['error'] ) ) {
			return new WP_Error( 'lemonway_request_error', $this->errorMessage( $body['error']['message'] ) );
		}

		$status_code = wp_remote_retrieve_response_code( $response );

		if ( ! in_array( $status_code, array( 200, 201, 202, 204 ), true ) ) {
			return empty( $body )
				? new WP_Error( 'lemonway_request_error', $this->errorMessage( wp_remote_retrieve_response_message( $response ) ) )
				: new WP_Error( 'lemonway_request_error', $body, array( 'lemonway_debug_id' => $debug_id ) );
		}

		if ( $debug_id ) {
			$body['lemonway_debug_id'] = $debug_id;
		}

		return $body;
	}

	/**
	 * Retrieves the bearer token from transient or creates a new one.
	 *
	 * @return WP_Error|bool|array|string The bearer token or WP_Error on failure.
	 */
	public function getBearerToken() {
		if ( get_transient( $this->bearer_token_key ) ) {
			return get_transient( $this->bearer_token_key );
		}

		$access_token = $this->createBearerToken();

		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}

		return $access_token;
	}

	/**
	 * Creates a new bearer token.
	 *
	 * @return WP_Error|bool|array|string The bearer token or WP_Error on failure.
	 */
	public function createBearerToken() {
		$url = $this->api_auth_url;

		$response = $this->makeRequest(
			array(
				'url'                => $url,
				'data'               => array( 'grant_type' => 'client_credentials' ),
				'method'             => 'post',
				'header'             => true,
				'content_type_json'  => false,
				'request_with_token' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( isset( $response['access_token'] ) && isset( $response['expires_in'] ) ) {
			set_transient( $this->bearer_token_key, $response['access_token'], $response['expires_in'] );

			return $response['access_token'];
		}

		return false;
	}

	/**
	 * Check the API status.
	 *
	 * @return array|WP_Error|bool|string True if the API is accessible, otherwise an error message.
	 */
	public function checkApi() {
		$url = $this->makeUrl( '/accounts/' . Helper::getTechnicalAccountId() );
		return $this->getRequest( $url );
	}

	/**
	 * Retrieves the error message from the API response.
	 *
	 * @param bool $display Whether to display the error message (default: false).
	 * @return bool|string True if no error, otherwise the error message.
	 */
	public function apiErrorMessage( bool $display = false ) {

		$api_check = $this->checkApi();

		if ( is_wp_error( $api_check ) ) {
			$error_data = $api_check->get_error_data();
			$error      = is_null( $error_data ) ? array(
				'code'    => 'API Error Response',
				'message' => wp_json_encode( $this->errorMessage( $api_check->get_error_message() ), JSON_PRETTY_PRINT ),
			) : $error_data;
		} elseif ( ! empty( $api_check['error'] ) ) {
			$error = array(
				'code'    => $api_check['error']['code'],
				'message' => esc_html__( 'Invalid Lemonway Payment Data', 'lemonway' ),
			);
		} else {
			return false;
		}

		if ( ! $display ) {
			return wp_json_encode( $error );
		}

		$environment = $this->test_mode ? esc_html__( 'Sandbox', 'lemonway' ) : esc_html__( 'Production', 'lemonway' );
		$code        = esc_html( $error['code'] );
		$message     = esc_html( $error['message'] );

		printf(
			'<div class="notice notice-error is-dismissible">
            <h3>%s</h3>
            <br>
            <div>
                <p><strong>%s: </strong> %s</p>
                <p><strong>%s: </strong> %s</p>
                <p><strong>%s: </strong> %s</p>
            </div>
        </div>',
			esc_html__( 'Lemonway API Endpoints status', 'lemonway' ),
			esc_html__( 'Environment', 'lemonway' ),
			esc_html( $environment ),
			esc_html__( 'Code', 'lemonway' ),
			esc_html( $code ),
			esc_html__( 'Error Message', 'lemonway' ),
			wp_kses_post( $message )
		);

		return false;
	}


	/**
	 * Processes a refund via the API.
	 *
	 * @param int|null $key Optional. The key of the document status to retrieve.
	 * @return array|string The response body or WP_Error on failure.
	 */
	public function documentTypes( $key = null ) {
		$document_types = array(
			'0'  => esc_html__( 'ID card (both sides in one file)', 'lemonway' ),
			'1'  => esc_html__( 'Proof of address', 'lemonway' ),
			'2'  => esc_html__( 'Scan of a proof of IBAN', 'lemonway' ),
			'3'  => esc_html__( 'Passport (European Union)', 'lemonway' ),
			'4'  => esc_html__( 'Passport (outside the European Union)', 'lemonway' ),
			'5'  => esc_html__( 'Residence permit (both sides in one file)', 'lemonway' ),
			'6'  => esc_html__( 'Other document type', 'lemonway' ),
			'7'  => esc_html__( 'Official company registration document (Kbis extract or equivalent)', 'lemonway' ),
			'11' => esc_html__( 'Driver licence (both sides in one file)', 'lemonway' ),
			'12' => esc_html__( 'Status', 'lemonway' ),
			'13' => esc_html__( 'Selfie', 'lemonway' ),
			'21' => esc_html__( 'SDD mandate', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}

	/**
	 * Success (0): the transaction has been completed, funds are credited   *
	 * Pending (4): the PSP didn’t send us the final status of the transaction yet   *
	 * Error PSP (6): there is an error, you have to verify the error code to have the reason    *
	 * Cancelled (7): you have decided to cancel the transaction     *
	 * Authorized (16): you have created a pre-authorized transaction.
	 */
	public function paymentStatus( $key = null ) {
		$document_types = array(
			'0'  => esc_html__( 'Success', 'lemonway' ),
			'4'  => esc_html__( 'Pending', 'lemonway' ),
			'6'  => esc_html__( 'Error PSP', 'lemonway' ),
			'7'  => esc_html__( 'Cancelled', 'lemonway' ),
			'16' => esc_html__( 'Authorized', 'lemonway' ),

		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}

	/**
	 * Retrieves the balance from the API.
	 *
	 * @param int|null $key Optional. The key of the document status to retrieve.
	 * @return array|string The response body or WP_Error on failure.
	 */
	public function documentStatus( $key = null ) {
		$document_types = array(
			'1'  => esc_html__( 'Waiting for manual verification', 'lemonway' ),
			'2'  => esc_html__( 'Accepted', 'lemonway' ),
			'3'  => esc_html__( 'Rejected', 'lemonway' ),
			'4'  => esc_html__( 'Rejected: Unreadable by human', 'lemonway' ),
			'5'  => esc_html__( 'Rejected: Expired document', 'lemonway' ),
			'6'  => esc_html__( 'Rejected: Wrong type of document', 'lemonway' ),
			'7'  => esc_html__( 'Rejected: Wrong holder name', 'lemonway' ),
			'11' => esc_html__( 'Rejected: Duplicated document', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}

	public function ibanAccountType( $key = null ) {
		$document_types = array(
			'0' => esc_html__( 'Other', 'lemonway' ),
			'1' => esc_html__( 'IBAN', 'lemonway' ),
			'2' => esc_html__( 'BBAN/RIB', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}
	public function ibanStatus( $key = null ) {
		$document_types = array(
			'1' => esc_html__( 'None', 'lemonway' ),
			'2' => esc_html__( 'Internal', 'lemonway' ),
			'3' => esc_html__( 'Not used', 'lemonway' ),
			'4' => esc_html__( 'Waiting to be verified by Lemonway', 'lemonway' ),
			'5' => esc_html__( 'Activated', 'lemonway' ),
			'6' => esc_html__( 'Rejected by the bank', 'lemonway' ),
			'7' => esc_html__( 'Rejected, no owner', 'lemonway' ),
			'8' => esc_html__( 'Deactivated', 'lemonway' ),
			'9' => esc_html__( 'Rejected by Lemonway', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}

	public function bankAccountStatus( $key = null ) {
		$document_types = array(
			'1' => esc_html__( 'N/A', 'lemonway' ),
			'2' => esc_html__( 'Registered vai API', 'lemonway' ),
			'3' => esc_html__( 'Not used', 'lemonway' ),
			'4' => esc_html__( 'To be verified', 'lemonway' ),
			'5' => esc_html__( 'Enabled', 'lemonway' ),
			'6' => esc_html__( 'Rejected by bank', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $document_types ) ) {
			return $document_types[ (string) $key ];
		}

		return $document_types;
	}

	public function errorMessage( $key = null ) {
		$error_types = array(
			'Forbidden'                               => esc_html__( 'Forbidden: Please try again.', 'lemonway' ),
			'AMOUNT NOT ALLOWED'                      => esc_html__( 'Amount not allowed', 'lemonway' ),
			'Incorrect URL address format '           => esc_html__( 'Invalid Website URL', 'lemonway' ),
			'Amount higher than your account balance' => esc_html__( 'Amount higher than your account balance', 'lemonway' ),
		);
		if ( $key !== null && array_key_exists( (string) $key, $error_types ) ) {
			return $error_types[ (string) $key ];
		}

		return wp_json_encode( $key );
	}
}
