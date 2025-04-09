<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types = 1 );

namespace Lemonway\Common\Utils;

use Lemonway\Config\Plugin;

/**
 * Utility to show prettified wp_die errors, write debug logs as
 * string or array and to deactivate plugin and print a notice
 *
 * @package Lemonway\Config
 * @since 1.0.0
 */
class Errors {

	/**
	 * Get the plugin data in static form
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function getPluginData(): array {
		return Plugin::init()->data();
	}

	/**
	 * Prettified version of the wp_die error function.
	 *
	 * @param string $message   The error message to be displayed.
	 * @param string $subtitle  Optional. A specified title of the error.
	 * @param string $source    Optional. The file source of the error.
	 * @param string $exception Optional. The exception or error details.
	 * @param string $title     Optional. A general title of the error.
	 * @since 1.0.0
	 */
	public static function wpDie( $message = '', $subtitle = '', $source = '', $exception = '', $title = '' ) {
		if ( $message ) {
			$plugin = self::getPluginData();
			$title  = $title ? $title : $plugin['name'] . ' ' . $plugin['version'] . ' ' . __( '&rsaquo; Fatal Error', 'lemonway' );
			self::writeLog(
				array(
					'title'     => $title . ' - ' . $subtitle,
					'message'   => $message,
					'source'    => $source,
					'exception' => $exception,
				)
			);
			$source   = $source ? '<code>' .
				sprintf(  /* translators: %s: file path */
					__( 'Error source: %s', 'lemonway' ),
					$source
				) . '</code><BR><BR>' : '';
			$footer   = $source . '<a href="' . $plugin['uri'] . '">' . $plugin['uri'] . '</a>';
			$message  = '<p>' . $message . '</p>';
			$message .= $exception ? '<p><strong>Exception: </strong><BR>' . $exception . '</p>' : '';
			$message  = "<h1>{$title}<br><small>{$subtitle}</small></h1>{$message}<hr><p>{$footer}</p>";
			wp_die( $message, $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			wp_die();
		}
	}

	/**
	 * De-activates the plugin and shows notice error in back-end
	 *
	 * @param string $message The error message to be displayed.
	 * @param string $subtitle Optional. A specified title of the error.
	 * @param string $source Optional. The file source of the error.
	 * @param string $exception Optional. The exception or error details.
	 * @param string $title Optional. A general title of the error.
	 * @since 1.0.0
	 */
	public static function pluginDie( $message = '', $subtitle = '', $source = '', $exception = '', $title = '' ) {
		if ( $message ) {
			$plugin = self::getPluginData();
			$title  = $title ? $title : $plugin['name'] . ' ' . $plugin['version'] . ' ' . __( '&rsaquo; Plugin Requirement', 'lemonway' );
			self::writeLog(
				array(
					'title'     => $title . ' - ' . $subtitle,
					'message'   => $message,
					'source'    => $source,
					'exception' => $exception,
				)
			);
			$source = $source ? '<small>' .
				sprintf( /* translators: %s: file path */
					__( 'Error source: %s', 'lemonway' ),
					$source
				) . '</small> - ' : '';
			$footer = $source . '<a href="' . $plugin['uri'] . '"><small>' . $plugin['uri'] . '</small></a>';
			$error  = "<strong><h3>{$title}</h3>{$subtitle}</strong><p>{$message}</p><hr><p>{$footer}</p>";
			global $lemonway_die_notice;
			$lemonway_die_notice = $error;
			add_action(
				'admin_notices',
				static function () {
					global $lemonway_die_notice;
					echo wp_kses_post(
						sprintf(
							'<div class="notice notice-error"><p>%s</p></div>',
							$lemonway_die_notice
						)
					);
				}
			);
		}
		add_action(
			'admin_init',
			static function () {
				// deactivate_plugins( plugin_basename( LEMONWAY_PLUGIN_FILE ) ); // phpcs:disable ImportDetection.Imports.RequireImports.Symbol -- this constant is global.
			}
		);
	}

	/**
	 * Writes a log if wp_debug is enables
	 *
	 * @param mixed $log The data to be logged. It can be a string, array, object, or any other type of data.
	 * @since 1.0.0
	 */
	public static function writeLog( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			} else {
				error_log( $log ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		}
	}

	/**
	 * Writes a cron log if WP_DEBUG is enabled.
	 *
	 * @param mixed $message The data to be logged. It can be a string, array, object, or any other type of data.
	 * @since 1.0.0
	 */
	public static function writeLogCron( $message ) {

		// Define the maximum file size (e.g., 5MB).
		$max_file_size = 5 * 1024 * 1024; // 5MB in bytes.

		// Check if WP_DEBUG is enabled.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

			// Define the log directory and file path.
			$log_dir  = self::getPluginData()['plugin_path'] . '/log/';
			$log_file = $log_dir . 'cron-error.log';

			// Use WP_Filesystem methods to handle file operations.
			if ( ! function_exists( 'get_filesystem_method' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			$filesystem = get_filesystem_method();
			WP_Filesystem();

			// Ensure the log directory exists, create it if it doesn't.
			if ( ! $GLOBALS['wp_filesystem']->is_dir( $log_dir ) ) {
				$GLOBALS['wp_filesystem']->mkdir( $log_dir, 0755 ); // Create directory with proper permissions.
			}

			// Ensure the log file exists, create it if it doesn't.
			if ( ! $GLOBALS['wp_filesystem']->exists( $log_file ) ) {
				$GLOBALS['wp_filesystem']->put_contents( $log_file, '', FS_CHMOD_FILE ); // Create an empty log file.
			}

			// Check file size and delete it if it exceeds the limit.
			if ( $GLOBALS['wp_filesystem']->size( $log_file ) > $max_file_size ) {
				$GLOBALS['wp_filesystem']->delete( $log_file ); // Delete the log file.
				$GLOBALS['wp_filesystem']->put_contents( $log_file, '', FS_CHMOD_FILE ); // Recreate an empty log file.
			}

			// Convert arrays or objects to string if needed.
			if ( is_array( $message ) || is_object( $message ) ) {
				$message = wp_json_encode( $message, JSON_PRETTY_PRINT );
			}

			// Replace newlines with spaces to ensure single-line output.
			$message = preg_replace( '/\s+/', ' ', $message ); // Replace all types of whitespace with a single space.

			// Log the message using WordPress's debug log.
			if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
				// Use WordPress' built-in debugging function to log messages to the debug.log.
				if ( function_exists( 'error_log' ) ) {
					$GLOBALS['wp_filesystem']->put_contents( $log_file, '[' . gmdate( 'Y-m-d H:i:s' ) . '] ' . sanitize_text_field( $message ) . "\n", FS_CHMOD_FILE );
				}
			}
		}
	}
}
