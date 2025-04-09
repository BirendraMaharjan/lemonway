<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\Config;

use Lemonway\Common\Abstracts\Base;
use Lemonway\Common\Utils\Errors;

/**
 * Check if any requirements are needed to run this plugin. We use the
 * "Requirements" package from "MicroPackage" to check if any PHP Extensions,
 * plugins, themes or PHP/WP version are required.
 *
 * @docs https://github.com/micropackage/requirements
 *
 * @package Lemonway\Config
 * @since 1.0.0
 */
final class Requirements extends Base {

	/**
	 * Represents the requirements.
	 *
	 * @var array
	 */
	public $requirements;

	/**
	 * Specifications for the requirements
	 *
	 * @return array : used to specify the requirements
	 * @since 1.0.0
	 */
	public function specifications(): array {
		return apply_filters(
			'lemonway_plugin_requirements',
			array(
				'php' => $this->plugin->requiredPhp(),
				'wp'  => $this->plugin->requiredWp(),
			)
		);
	}

	/**
	 * Plugin requirements checker
	 *
	 * @since 1.0.0
	 */
	public function check() {
		$this->requirements = $this->specifications();

		// Check PHP version.
		$php_version_check = version_compare( phpversion(), $this->requirements['php'], '>=' );
		if ( ! $php_version_check ) {
			$this->displayRequirementNotice(
				'Invalid PHP version',
				sprintf(
				/* translators: %1$1s: required php version, %2$2s: current php version */
					__( 'You must be using PHP %1$1s or greater. You are currently using PHP %2$2s.', 'lemonway' ),
					$this->requirements['php'],
					phpversion()
				)
			);
		}

		// Check WordPress version.
		$wp_version_check = version_compare( get_bloginfo( 'version' ), $this->requirements['wp'], '>=' );
		if ( ! $wp_version_check ) {
			$this->displayRequirementNotice(
				'Invalid WordPress version',
				sprintf(
				/* translators: 1: Required WordPress version, 2: Current WordPress version */
					__( 'You must be using WordPress %1$1s or greater. You are currently using WordPress %2$2s.', 'lemonway' ),
					$this->requirements['wp'],
					get_bloginfo( 'version' )
				)
			);
		}
	}

	/**
	 * Compares PHP & WP versions and kills plugin if it's not compatible
	 *
	 * @since 1.0.0
	 */
	public function versionCompare() {
		foreach (
			array(
				// PHP version check.
				array(
					'current' => phpversion(),
					'compare' => $this->plugin->requiredPhp(),
					'title'   => __( 'Invalid PHP version', 'lemonway' ),
					'message' => sprintf( /* translators: %1$1s: required php version, %2$2s: current php version */
						__( 'You must be using PHP %1$1s or greater. You are currently using PHP %2$2s.', 'lemonway' ),
						$this->plugin->requiredPhp(),
						phpversion()
					),
				),
				// WP version check.
				array(
					'current' => get_bloginfo( 'version' ),
					'compare' => $this->plugin->requiredWp(),
					'title'   => __( 'Invalid WordPress version', 'lemonway' ),
					'message' => sprintf( /* translators: %1$1s: required WordPress version, %2$2s: current WordPress version */
						__( 'You must be using WordPress %1$1s or greater. You are currently using WordPress %2$2s.', 'lemonway' ),
						$this->plugin->requiredWp(),
						get_bloginfo( 'version' )
					),
				),
			) as $compat_check ) {
			if ( version_compare(
				$compat_check['compare'],
				$compat_check['current'],
				'>='
			) ) {
				// Kill plugin.
				Errors::pluginDie(
					$compat_check['message'],
					$compat_check['title'],
					plugin_basename( __FILE__ )
				);
			}
		}
	}

	/**
	 * Displays a notice and kills the plugin
	 *
	 * @param string $title The title of the notice.
	 * @param string $message The message to display in the notice.
	 *
	 * @since 1.0.0
	 */
	private function displayRequirementNotice( string $title, string $message ) {
		Errors::pluginDie(
			$message,
			$title,
			plugin_basename( __FILE__ )
		);
	}
}
