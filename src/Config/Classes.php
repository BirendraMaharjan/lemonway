<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\Config;

/**
 * This array is being used in ../Boostrap.php to instantiate the classes
 *
 * @package Lemonway\Config
 * @since 1.0.0
 */
final class Classes {

	/**
	 * Init the classes inside these folders based on type of request.
	 *
	 * @see Requester for all the type of requests or to add your own
	 */
	public static function get(): array {
		// phpcs:disable
		// ignore for readable array values one a single line
		return [
			[ 'init' => 'Integrations' ],
			[ 'init' => 'App\\Frontend', 'on_request' => 'frontend' ],
			[ 'init' => 'App\\Backend', 'on_request' => 'backend' ],
			];
		// phpcs:enable
	}
}
