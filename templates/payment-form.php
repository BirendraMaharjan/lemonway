<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

/**
 * Template for WC payment form.
 *
 * @see \Lemonway\App\Frontend\Templates
 * @var $args
 */

?>
<div id="lemonway-payment-container">
	<div class="lemonway-payment-method-item lemonway-payment-method-card">

		<input type="radio" id="lemonway-payment-type-card" name="lemonway_payment_type"
				class="lemonway-payment-type card" value="card"
				checked="checked"/>
		<label for="lemonway-payment-type-card"><?php echo esc_html__( 'Credit Card', 'lemonway' ); ?>
			<ul class="card-list">
				<li>
					<div
						style="background: url('<?php echo esc_url( lemonway()->getData()['plugin_url'] . '/assets/public/svg/visa-classic-svgrepo-com.svg' ); ?>') no-repeat center / cover; width: 40px; height: 25px;"
						title="<?php esc_html_e( 'Visa', 'lemonway' ); ?>"></div>
				</li>
				<li>
					<div
						style="background: url('<?php echo esc_url( lemonway()->getData()['plugin_url'] . '/assets/public/svg/mastercard-full-svgrepo-com.svg' ); ?>') no-repeat center / cover; width: 40px; height: 25px;"
						title="<?php esc_html_e( 'Mastercard', 'lemonway' ); ?>"></div>
				</li>
				<li>
					<div class="cross-line"
						style="background: url('<?php echo esc_url( lemonway()->getData()['plugin_url'] . '/assets/public/svg/american-express-svgrepo-com.svg' ); ?>') no-repeat center / cover; width: 40px; height: 25px;"
						title="<?php esc_html_e( 'American Express', 'lemonway' ); ?>"></div>
				</li>
			</ul>
		</label>
		<div class="lemonway-payment-method-card-fields" id="lemonway-payment-method-card-fields">

			<label for="holder-name"><?php esc_html_e( "Holder's Name", 'lemonway' ); ?></label>
			<div class="field textbox" id="holder-name"></div>

			<label for="card-number"><?php esc_html_e( 'Card Number', 'lemonway' ); ?></label>
			<div class="field textbox" id="card-number"></div>

			<label for="expiration-date"><?php esc_html_e( 'Expiration Date', 'lemonway' ); ?></label>
			<div class="field textbox" id="expiration-date"></div>

			<label for="cvv"><?php esc_html_e( 'CVV', 'lemonway' ); ?></label>
			<div class="field textbox" id="cvv"></div>

			<!--<label for="register-card">< ?php esc_html_e("Register Card?", 'lemonway'); ?></label>
			<span class="field checkbox" id="register-card"></span>-->
		</div>
	</div>
	<div class="lemonway-payment-method-item lemonway-payment-method-paypal">
		<input type="radio" id="lemonway-payment-type-paypal" name="lemonway_payment_type"
				class="lemonway-payment-type paypal" value="paypal"/>
		<label for="lemonway-payment-type-paypal"><?php echo esc_html__( 'Paypal', 'lemonway' ); ?>
			<ul class="card-list">
				<li>
					<div
						style="background: url('<?php echo esc_url( lemonway()->getData()['plugin_url'] . '/assets/public/svg/paypal-3-svgrepo-com.svg' ); ?>') no-repeat center / cover; width: 45px; height: 30px;"
						title="<?php esc_html_e( 'Paypal', 'lemonway' ); ?>"></div>
				</li>
			</ul>
		</label>
	</div>
	<!--<div class="lemonway-payment-method-item lemonway-payment-method-paypal">
		<input type="radio" id="lemonway-payment-type-bank" name="lemonway_payment_type"
		       class="lemonway-payment-type bank" value="paypal"/>
		<label for="lemonway-payment-type-bank"><?php /*echo esc_html__( 'Pay by bank', 'lemonway' ); */?>
			<ul class="card-list">
				<li>
					<div
						style="background: url('<?php /*echo esc_url( lemonway()->getData()['plugin_url'] . '/assets/public/svg/paypal-3-svgrepo-com.svg' ); */?>') no-repeat center / cover; width: 45px; height: 30px;"
						title="<?php /*esc_html_e( 'Paypal', 'lemonway' ); */?>"></div>
				</li>
			</ul>
		</label>
	</div>-->
</div>
<style>
	.cross-line {
		position: relative;
		display: inline-block;
	}

	.cross-line::after, .cross-line::before {
		content: '';
		position: absolute;
		top: 50%;
		left: 0;
		width: 100%;
		height: 3px;
		background: rgb(218, 3, 3);
		transition: all linear 0.3s;
	}

	.cross-line:hover::after, .cross-line:hover::before {
		background: transparent;
	}

	.cross-line::before {
		transform: rotate(30deg);
	}

	.cross-line::after {
		transform: rotate(-30deg);
	}

	.lemonway-payment-method-item {
		clear: both;
	}

	.lemonway-payment-method-item label {
		display: inline;
	}

	.lemonway-payment-method-item .card-list {
		margin: 0;
		float: right;
	}

	.lemonway-payment-method-item .card-list li {
		display: inline-block;
	}

	form > button {
		display: block;
		margin-top: 2em;
	}

	.field {
		height: 2rem;
		padding-inline: 0.5em;
		max-width: 25em;
		margin: 0.5em;
		border: 1px solid gray;
		border-radius: 0.25em;
	}

	.field.checkbox {
		overflow: hidden;
		display: inline-block;
		width: 1rem;
		height: 1rem;
		padding: 0;
		margin: 0;
	}

	.field:hover {
		border: 1px solid black;
	}

	.lw-hosted-fields-invalid {
		border: 1px solid red;
	}

	.lw-hosted-fields-valid.textbox {
		background-color: palegreen;
	}

	.lw-hosted-fields-focused {
		box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
	}

</style>