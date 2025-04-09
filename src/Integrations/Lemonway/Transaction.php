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
class Transaction extends Api {

	/**
	 * Api endpoint.
	 *
	 * @var string The type of entity for API requests
	 */
	protected $type = 'moneyins';


	public function retrieve( $transaction_id ) {
		$url  = $this->makeUrl( $this->type . '?transactionId=' . $transaction_id );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		return $this->makeRequest( $args );
	}

	public function retrieveAll() {
		$url  = $this->makeUrl( $this->type );
		$args = array(
			'url'    => $url,
			'method' => 'get',
		);

		return $this->makeRequest( $args );
	}

	public function retrieveAllby( $transaction_id ) {
		$url  = $this->makeUrl( $this->type );
		$args = array(
			'url'    => $url,
			'method' => 'get',
			'data'   => array(),
		);

		return $this->makeRequest( $args );
	}
}
