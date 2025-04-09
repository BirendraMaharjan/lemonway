<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

/**
 * Template for dokan payment option.
 *
 * @see \Lemonway\App\Frontend\Templates
 * @var $args
 */

use Lemonway\Integrations\Gateway\Helper;

use Lemonway\Integrations\Dokan\Dokan;

$lemonway_dokan = new Dokan();

$lemonway_is_seller_connected = $lemonway_dokan->isSellerConnected();

$lemonway_args = $lemonway_is_seller_connected ? $args['payment']['lemonway'][ Helper::getPaymentMode() ] : array();

?>
	<div class="dokan-lemonway-settings-template">
		<div>
			<div class="navigation">
				<ul>
					<?php if ( ! $lemonway_is_seller_connected ) : ?>
						<li>
							<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) . '?connect-account' ); ?>"><?php esc_html_e( 'Connect', 'lemonway' ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $lemonway_is_seller_connected ) : ?>
						<li>
							<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ); ?>"><?php esc_html_e( 'Details', 'lemonway' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) . '?edit-account' ); ?>"><?php esc_html_e( 'Update', 'lemonway' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) . '?upload-document' ); ?>"><?php esc_html_e( 'Upload Document', 'lemonway' ); ?></a>
						</li>
						<li><a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) . '?link-bank' ); ?>"><?php esc_html_e( 'Link Bank', 'lemonway' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
			<div>
				<?php
				//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$lw_template = isset( $_GET['link-bank'] ) ? 'dokan-bank-form' : 'dokan-payment-form';
				lemonway()->templates()->get(
					esc_attr( $lw_template ),
					null,
					$lemonway_args
				);
				?>
			</div>
		</div>
	</div>
<?php
