<?php
/**
 * Lemonway gateway register
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Gateway;

use Lemonway\Config\Plugin;

/**
 * Class RegisterGateway
 *
 * Registers the custom payment gateway for WooCommerce.
 */
class RegisterGateway {



	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_filter( 'woocommerce_payment_gateways', array( $this, 'registerGateway' ) );
	}

	/**
	 * Register payment gateway.
	 *
	 * Adds the custom payment gateway class to the list of available WooCommerce payment gateways.
	 *
	 * @return array The modified array with the addition of the custom payment gateway class.
	 */
	public function registerGateway( $gateways ) {
		$gateways[] = 'Lemonway\Integrations\Gateway\Lemonway';
		return $gateways;
	}
}
