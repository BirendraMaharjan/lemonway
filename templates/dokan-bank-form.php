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

$lemonway_dokan       = new Dokan();
$lemonway_account_id  = Helper::getMerchantId();
$lemonway_linked_iban = $lemonway_dokan->iban->isLinkedIban( $lemonway_account_id );


?>
<div id="lemonway-link-bank-section">

	<?php
	$lemonway_data = $lemonway_dokan->lemonway_api->apiErrorMessage();
	if ( $lemonway_data ) {
		esc_html_e( 'Please reload.', 'lemonway' );
		echo '</div>';
		return;
	}
	?>

	<?php if ( $lemonway_linked_iban ) : ?>
		<div id="lemonway-link-bank-list">
			<?php
			lemonway()->templates()->get(
				'dokan/linked-banks'
			);
			?>
		</div><!-- #lemonway-link-bank-list -->
	<?php else : ?>

	<div id="lemonway-link-bank" class="<?php echo $lemonway_linked_iban ? 'hide' : ''; ?>">
		<h2><?php esc_html_e( 'Add New Account', 'lemonway' ); ?></h2>
		<?php $lemonway_dokan->setting_dokan->formStep( $args, 'iban' ); ?>
		<h5>
			<?php
			printf(
			/* translators: %s: Upload Document link */
				esc_html__(
					'Note: Please upload your proof of IBAN from %s',
					'lemonway'
				),
				'<a href="' . esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) . '?upload-document' ) . '">' . esc_html__( 'Upload Document', 'lemonway' ) . '</a>'
			);
			?>
		</h5>

		<div class="bottom-actions">

			<button class="ajax_prev save dokan-btn dokan-btn-theme lemonway-save-dokan-btn" type="submit"
					name="dokan_update_payment_settings" data-action="<?php echo esc_attr( 'bank_link' ); ?>">
				<?php esc_html_e( 'Add New Bank', 'lemonway' ); ?>
			</button>

			<a href="<?php echo esc_url( dokan_get_navigation_url( 'settings/payment' ) ); ?>">
				<?php esc_html_e( 'Cancel', 'lemonway' ); ?>
			</a>
			<input type="hidden" name="dokan_update_payment_settings">
			<?php if ( $lemonway_account_id ) : ?>
				<input type="hidden" name="merchant_id" value="<?php echo esc_attr( $lemonway_account_id ); ?>">
			<?php endif; ?>

		</div>
	</div><!-- #lemonway-link-bank -->
	<?php endif; ?>
</div><!-- #lemonway-link-bank-section -->

<style>
	#lemonway-link-bank.hide {
		display: none;
	}

	.grid-list {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 10px;
		list-style-position: inside;
		padding-left: 0;
		margin: 0;
	}

	.grid-list__iban li {
		background-color: #f0f0f0;
		border-radius: 5px;
		text-align: left;
		padding: 15px !important;
		position: relative;
	}

	.grid-list__iban li p {
		margin: 0 0 5px 0;
	}

	.link-account-deactivate {
		display: none;
		cursor: pointer;
		position: absolute;
		top: 1rem;
		right: 1rem;
	}


	.grid-list__iban li:hover .link-account-deactivate {
		display: inline-block;
	}

	#lemonway-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		display: none;
		justify-content: center;
		align-items: center;
		z-index: 9999;
	}

	#lemonway-modal.active {
		display: flex;
	}

	.modal-body {
		background: #fff;
		padding: 20px;
		border-radius: 5px;
		text-align: center;
		min-width: 480px;
		min-height: 250px;
	}

	.loading-spinner {
		border: 4px solid #f3f3f3; /* Light grey */
		border-top: 4px solid #3498db; /* Blue */
		border-radius: 50%;
		width: 50px;
		height: 50px;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}


	@media (max-width: 900px) {
		.grid-list {
			grid-template-columns: repeat(2, 1fr);
		/
		}
	}

	@media (max-width: 500px) {
		.grid-list {
			grid-template-columns: 1fr;
		}
	}

</style>