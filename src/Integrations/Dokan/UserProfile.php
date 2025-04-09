<?php
/**
 * Lemonway setting option for dokan
 *
 * @package Lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

use Lemonway\Config\Plugin;
use Lemonway\Integrations\Form\Countries;
use Lemonway\Integrations\Form\Fields;
use Lemonway\Integrations\Gateway\Helper;
use Lemonway\Integrations\Lemonway\Api;

/**
 * Class SettingDokan
 *
 * Handles the settings for the Dokan plugin integration with Lemonway.
 */
class UserProfile extends Dokan {
	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'show_user_profile', array( $this, 'addMetaFields' ), 30 );
		add_action( 'edit_user_profile', array( $this, 'addMetaFields' ), 30 );
	}

	/**
	 * Add fields to user profile
	 *
	 * @return void|false
	 */
	public function addMetaFields( $user ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return;
		}

		if ( ! user_can( $user, 'dokandar' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return;
		}

		?>
		<h3><?php esc_html_e( 'Lemonway Payment Options', 'lemonway' ); ?></h3>


		<?php
		$vendor_id     = $user->ID;
		$lemonway_args = $this->getLemonwaySettings( $vendor_id );

		if ( empty( $lemonway_args ) ) {
			esc_html_e( 'Not Connected', 'lemonway' );
			return;}
		?>
		<table class="form-table">
			<tbody>
			<tr>
				<th><?php esc_html_e( 'Email', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $lemonway_args['email'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'First Name', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $lemonway_args['firstname'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Lastname Name', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $lemonway_args['lastname'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Nationality', 'lemonway' ); ?>
				</th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $this->setting_dokan->countires->getCountryByAlpha3( $lemonway_args['nationality'] )['name'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Date of Birth', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $lemonway_args['birth_date'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Address Country', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( $this->setting_dokan->countires->getCountryByAlpha3( $lemonway_args['country'] )['name'] ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Account/Merchant ID', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( Helper::getMerchantId() ); ?>">
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Account Internal ID', 'lemonway' ); ?></th>
				<td>
					<input disabled type="text" class="regular-text" value="<?php echo esc_attr( Helper::getMerchantInternalId() ); ?>">
				</td>
			</tr><tr>
				<th><?php esc_html_e( 'Terms and Conditions', 'lemonway' ); ?></th>
				<td>
					<input disabled type="checkbox" value="<?php echo esc_attr( $lemonway_args['agree_term_condition'] ); ?>" <?php checked( $lemonway_args['agree_term_condition'] ); ?>>
				</td>
			</tr><tr>
				<th><?php esc_html_e( 'Terms & Policies', 'lemonway' ); ?></th>
				<td>
					<input disabled type="checkbox" value="<?php echo esc_attr( $lemonway_args['agree_term_condition_lemonway'] ); ?>" <?php checked( $lemonway_args['agree_term_condition_lemonway'] ); ?>>
				</td>
			</tr>
			<tr>
				<th colspan="2"><?php esc_html_e( 'Documents', 'lemonway' ); ?>
					<?php $this->getDocumentsHtml( $vendor_id ); ?>
				</th>
			</tr>
			</tbody>
		</table>
		<?php
	}
}
