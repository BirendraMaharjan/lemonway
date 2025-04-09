<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\App\Backend;

use Lemonway\Common\Abstracts\Base;

/**
 * Class Settings
 *
 * @package Lemonway\App\Backend
 * @since 1.0.0
 */
class Settings extends Base {

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
		 * Add plugin code here for admin settings specific functions
		 */

		// Hook the function to the filter with the plugin base name.
		add_action( 'plugin_action_links_' . plugin_basename( LEMONWAY_PLUGIN_FILE ), array( $this, 'addSettingsLink' ) );
	}

	/**
	 * Adds a custom settings link to the plugin action links.
	 *
	 * @since 1.0.0
	 */
	public function addSettingsLink( $links ) {
		$url           = esc_url(
			add_query_arg(
				'page',
				'wc-settings&tab=checkout&section=lemonway-gateway',
				get_admin_url() . 'admin.php'
			)
		);
		$settings_link = "<a href='$url'>" . esc_html__( 'Settings', 'lemonway' ) . '</a>';

		array_unshift( $links, $settings_link );

		return $links;
	}
}
