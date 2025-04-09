<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\Common;

use Lemonway\App\Frontend\Templates;
use Lemonway\Common\Abstracts\Base;

/**
 * Main function class for external uses
 *
 * @see lemonway()
 * @package Lemonway\Common
 */
class Functions extends Base {
	/**
	 * Get plugin data by using lemonway()->getData()
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function getData(): array {
		return $this->plugin->data();
	}

	/**
	 * Get the template instantiated class using lemonway()->templates()
	 *
	 * @return Templates
	 * @since 1.0.0
	 */
	public function templates(): Templates {
		return new Templates();
	}

	public function getPostIdByMeta( $meta_key, $meta_value ) {
		global $wpdb;

		// Execute the query and return the post ID.
		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s LIMIT 1",
				$meta_key,
				$meta_value
			)
		);
	}
}
