<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\App\Backend;

use Lemonway\Common\Abstracts\Base;
use Lemonway\Integrations\Lemonway\Api;

/**
 * Class Notices
 *
 * @package Lemonway\App\Backend
 * @since 1.0.0
 */
class Notices extends Base {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * This backend class is only being instantiated in the backend as requested in the Bootstrap class
		 *
		 * @see Requester::isAdminBackend()
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here for admin notices specific functions
		 */
		add_action( 'admin_notices', array( $this, 'apiInfoAdminNotice' ) );
	}

	/**
	 * Example admin notice
	 *
	 * @since 1.0.0
	 */
	public function apiInfoAdminNotice() {
		$api = new \Lemonway\Integrations\Lemonway\Api();
		$api->adminNotice();
	}
}
