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
class Payment extends Api {

	public function paypal( array $data ) {
		$url  = $this->makeUrl( '/moneyins/paypal/init' );
		$args = array(
			'url'  => $url,
			'data' => $data,
		);

		return $this->makeRequest( $args );
	}

	public function paypalResume( $transaction_id ) {
		$url  = $this->makeUrl( '/moneyins/paypal/' . $transaction_id . '/resume' );
		$args = array(
			'url' => $url,
		);

		return $this->makeRequest( $args );
	}

	public function webinit( array $data ) {
		$url  = $this->makeUrl( '/moneyins/card/webinit' );
		$args = array(
			'url'  => $url,
			'data' => $data,
		);

		return $this->makeRequest( $args );
	}

	public function p2p( array $data ) {
		$url  = $this->makeUrl( '/p2p' );
		$args = array(
			'url'  => $url,
			'data' => $data,
		);

		return $this->makeRequest( $args );
	}
}
