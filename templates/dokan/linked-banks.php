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
use Lemonway\Integrations\Gateway\Helper;


$lemonway_dokan        = new Dokan();
$lemonway_account_id   = Helper::getMerchantId();
$lemonway_linked_banks = $lemonway_dokan->iban->retrieve( $lemonway_account_id );

if ( is_wp_error( $lemonway_linked_banks ) || ! $lemonway_linked_banks ) {
	return;
}

?>

<h2><?php esc_html_e( 'Linked IBAN Account', 'lemonway' ); ?></h2>
<ul class="grid-list grid-list__iban">
	<?php
	foreach ( $lemonway_linked_banks as $key => $value ) :
		?>
		<li>
			<!--<div class="link-account-status">
				<button class="link-account-deactivate" data-id="<?php /*echo $value['id'] */ ?>">
					<?php /*esc_html_e( 'Deactivate', 'lemonway' ); */ ?>
				</button>
			</div>-->
			<p><strong><?php esc_html_e( 'Id: ', 'lemonway' ); ?></strong> <?php echo esc_html( $value['id'] ); ?> </p>
			<p>
				<strong><?php esc_html_e( 'Status: ', 'lemonway' ); ?></strong> <?php echo esc_html( $lemonway_dokan->lemonway_api->ibanStatus( $value['status'] ) ); ?>
			</p>
			<p><strong><?php esc_html_e( 'Iban: ', 'lemonway' ); ?></strong> <?php echo esc_html( $value['iban'] ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Holder Name:', 'lemonway' ); ?></strong> <?php echo esc_html( $value['holder'] ); ?>
			</p>
			<p>
				<strong><?php esc_html_e( 'Account Typ: ', 'lemonway' ); ?></strong> <?php echo esc_html( $lemonway_dokan->lemonway_api->ibanAccountType( $value['type'] ) ); ?>
			</p>
		</li>
		<?php
	endforeach;


	?>
</ul>
<!-- Modal -->
<div id="lemonway-modal">
	<div class="modal-body">
		<div class="modal-content">
			<p><?php esc_html_e( 'Are you sure you want to deactivate this account?', 'lemonway' ); ?></p>
		</div>
		<div class="modal-footer">
			<button id="lemonway-iban-deactivation"
					data-id=""><?php esc_html_e( 'Yes, Deactivate', 'lemonway' ); ?></button>
			<button id="lemonway-modal-close"><?php esc_html_e( 'Cancel', 'lemonway' ); ?></button>
		</div>
	</div>
</div>

