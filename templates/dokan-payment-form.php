<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

/**
 * Template for dokan payment form.
 *
 * @see \Lemonway\App\Frontend\Templates
 * @var $args
 */

use Lemonway\Integrations\Dokan\Dokan;
use Lemonway\Integrations\Dokan\SettingDokan;
use Lemonway\Integrations\Gateway\Helper;

$lemonway_dokan_setting = new SettingDokan();
$lemonway_dokan         = new Dokan();

$lemonway_is_seller_connected = $lemonway_dokan->isSellerConnected();

$lemonway_class      = '';
$lemonway_edit_mode  = false;
$lemonway_form_step  = 'step1';
$lemonway_account_id = false;
$lemonway_btn_text   = esc_html__( 'Continue', 'lemonway' );


if ( isset( $_GET['edit-account'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lemonway_edit_mode  = true;
	$lemonway_account_id = Helper::getMerchantId();
	$lemonway_form_step  = 'step2';
	$lemonway_class      = 'lemonway-hide';
	$lemonway_btn_text   = esc_html__( 'Save', 'lemonway' );
} elseif ( isset( $_GET['upload-document'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lemonway_edit_mode  = true;
	$lemonway_account_id = Helper::getMerchantId();
	$lemonway_form_step  = 'step3';
	$lemonway_class      = 'lemonway-hide';
	$lemonway_btn_text   = esc_html__( 'Upload Document', 'lemonway' );
} elseif ( isset( $_GET['connect-account'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lemonway_edit_mode = true;
	$lemonway_class     = 'lemonway-hide';
}

if ( ! $lemonway_edit_mode && ! $lemonway_is_seller_connected ) {
	return;
}
$lw_account_type = $args['account_type'] ?? '';

?>

	<fieldset
		<?php
		if ( ! $lemonway_edit_mode ) {
			echo esc_attr( 'disabled="disabled"' );
		}
		?>
	>
		<div class="dokan-lemonway-settings-form">
			<div class="step step1
		<?php
		if ( $lemonway_form_step !== 'step1' ) {
			echo esc_attr( $lemonway_class );
		}
		?>
		">
				<h4><?php $lemonway_edit_mode ? esc_html_e( 'Your Email', 'lemonway' ) : esc_html_e( 'Enter Email', 'lemonway' ); ?></h4>
				<?php
				$lemonway_dokan_setting->formStep( $args, 'step1' );
				?>
			</div>
			<div class="step step2
		<?php
		if ( $lemonway_form_step !== 'step2' ) {
			echo esc_attr( $lemonway_class );
		}
		?>
		">
				<h4><?php esc_html_e( 'Information', 'lemonway' ); ?></h4>
				<div class="lemonway-settings-company-fields 
				<?php
				if ( $lw_account_type !== 'company' ) {
					echo 'lemonway-hide';}
				?>
				">
					<h5><?php esc_html_e( 'Company Information', 'lemonway' ); ?></h5>
					<?php
					$lemonway_dokan_setting->formStep( $args, 'company' );
					?>
				</div>
				<?php
				$lemonway_dokan_setting->formStep( $args, 'step2' );
				?>
			</div>
			<div class="step step3
		<?php
		if ( $lemonway_form_step !== 'step3' ) {
			echo esc_attr( $lemonway_class );
		}
		?>
		">
				<h4><?php esc_html_e( 'Upload Document', 'lemonway' ); ?></h4>
				<?php
				if ( $lemonway_edit_mode ) {
					$lemonway_dokan_setting->formStep( $args, 'step3' );
				}
				$lemonway_dokan->getDocumentsHtml();
				?>
			</div>
		</div>
	</fieldset>

<?php
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
if ( ! isset( $_GET['page'] ) || 'dokan-seller-setup' !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) :
	?>
	<div class="bottom-actions">
		<?php if ( ! $lemonway_edit_mode ) : ?>
			<a class="dokan-btn dokan-btn-theme"
				href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ); ?>?edit-account">
				<?php esc_html_e( 'Edit', 'lemonway' ); ?>
			</a>
		<?php else : ?>
			<button class="ajax_prev save dokan-btn dokan-btn-theme lemonway-save-dokan-btn" type="submit"
					name="dokan_update_payment_settings" data-action="<?php echo esc_attr( $lemonway_form_step ); ?>">
				<?php echo esc_html( $lemonway_btn_text ); ?>
			</button>
		<?php endif; ?>
		<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment' ) ); ?>">
			<?php esc_html_e( 'Cancel', 'lemonway' ); ?>
		</a>
		<input type="hidden" name="dokan_update_payment_settings">
		<?php if ( $lemonway_account_id ) : ?>
			<input type="hidden" name="merchant_id" value="<?php echo esc_attr( $lemonway_account_id ); ?>">
		<?php endif; ?>
		<?php if ( isset( $args['connected1'] ) ) : ?>
			<button class="ajax_prev disconnect lemonway-disconnect-dokan-btn dokan-btn dokan-btn-danger"
					type="button" name="settings[lemonway][disconnect]">
				<?php esc_html_e( 'Disconnect', 'lemonway' ); ?>
			</button>
		<?php endif; ?>
	</div>
	<?php
endif;
