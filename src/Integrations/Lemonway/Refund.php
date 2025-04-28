<?php
/**
 * Lemonway Refund Integration Class
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
class Refund extends Api {

	/**
	 * Refund a Money-In
	 */
	public function refund( $transaction_id, $data ) {

		$url  = $this->makeUrl( 'refund/' . $transaction_id );
		$args = array(
			'url'    => $url,
			'method' => 'put',
			'data'   => $data,
		);

		return $this->makeRequest( $args );
	}

	/**
	 * Refund a transaction without PSP process
	 *
	 * @param $transaction_id
	 *
	 * @return array|bool|string|string[]|WP_Error
	 */
	public function refundTransaction( $transaction_id ) {
		$url  = $this->makeUrl( '/refundcreate/' . $transaction_id );
		$args = array(
			'url'    => $url,
			'method' => 'put',
		);

		return $this->makeRequest( $args );
	}
}
