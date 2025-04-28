<?php
/**
 * Dokan integration for Lemonway payment gateway.
 *
 * Integrates Lemonway with Dokan to manage payment settings, withdrawals, and KYC verification.
 *
 * @package   lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Dokan;

use Lemonway\Config\Plugin;
use Lemonway\Integrations\Gateway\Helper;
use Lemonway\Integrations\Lemonway\Account;
use Lemonway\Integrations\Lemonway\Api;
use Lemonway\Integrations\Lemonway\Iban;
use Lemonway\Integrations\Lemonway\Payment;
use Lemonway\Integrations\Lemonway\Refund;



/**
 * Class Dokan
 *
 * Main class for integrating Lemonway with Dokan plugin.
 *
 * @package Lemonway\Integrations\Dokan
 */
class Dokan {

	/**
	 * Plugin metadata.
	 *
	 * @var array Contains metadata from the Plugin class.
	 */
	protected $plugin = array();

	/**
	 * Lemonway API instance.
	 *
	 * @var Api Instance of Lemonway API for payment processing.
	 */
	public $lemonway_api;

	/**
	 * Dokan settings instance.
	 *
	 * @var SettingDokan Instance of Dokan settings handler.
	 */
	public $setting_dokan = array();

	/**
	 * Account instance.
	 *
	 * @var Account Instance of Lemonway account handler.
	 */
	protected $account;

	/**
	 * Instance of Iban.
	 *
	 * @var iban
	 */
	public $iban;

	/**
	 * Instance of refund.
	 *
	 * @var iban
	 */
	public $lemonway_refund;

	/**
	 * Instance of payment.
	 *
	 * @var iban
	 */
	public $lemonway_payment;

	/**
	 * Title of the plugin.
	 *
	 * @var string Represents the title of the Lemonway plugin.
	 */
	protected $title;


	/**
	 * Slug of the plugin.
	 *
	 * @var string Represents the slug of the Lemonway plugin.
	 */
	protected $slug;

	/**
	 * Holds the ID of the current vendor.
	 *
	 * @var int|null Vendor ID, or null if no vendor is logged in.
	 */
	public $vendor_id = null;

	/**
	 * Initialize the class.
	 *
	 * Sets up necessary hooks and initializes plugin dependencies.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		add_filter( 'dokan_withdraw_methods', array( $this, 'registerLemonwayWithdrawMethod' ), 99 );
		add_filter( 'dokan_withdraw_method_icon', array( $this, 'getIcon' ), 10, 2 );
		add_filter( 'dokan_withdraw_method_settings_title', array( $this, 'paymentHeading' ), 10, 2 );

		add_filter( 'dokan_payment_settings_required_fields', array( $this, 'requiredFields' ), 10, 2 );
		add_filter( 'dokan_withdraw_method_additional_info', array( $this, 'additionalInfo' ), 10, 2 );

		// vndor withdraw.
		add_filter( 'dokan_get_seller_active_withdraw_methods', array( $this, 'customMethodInActiveWithdrawMethod' ), 99, 2 );
		add_filter( 'dokan_withdraw_withdrawable_payment_methods', array( $this, 'includeMethodInWithdrawMethodSection' ) );

		// Remove payment nav form vendor dashboard.
		add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokanNav' ) );
	}


	/**
	 * Dokan Nav function.
	 *
	 * @param array $urls Vendor dashboard URLs.
	 *
	 * @return array
	 */
	public function dokanNav( $urls ) {
		$user_id = get_current_user_id();

		if ( ! dokan_is_seller_enabled( $user_id ) ) {
			unset( $urls['settings']['submenu']['payment'] );
		}

		return $urls;
	}

	/**
	 * Constructor.
	 *
	 * Initializes plugin dependencies and sets up necessary hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin           = Plugin::init();
		$this->setting_dokan    = new SettingDokan();
		$this->lemonway_api     = new Api();
		$this->account          = new Account();
		$this->lemonway_refund  = new Refund();
		$this->lemonway_payment = new Payment();
		$this->iban             = new Iban();
		$this->vendor_id        = dokan_get_current_user_id();

		$this->title = $this->plugin->title();
		$this->slug  = $this->plugin->slug();
	}

	public function includeMethodInWithdrawMethodSection( $methods ) {
		$methods[] = $this->slug;
		return $methods;
	}
	public function customMethodInActiveWithdrawMethod( $active_payment_methods, $vendor_id ) {
		$store_info = dokan_get_store_info( $vendor_id );

		if ( isset( $store_info['payment'][ $this->slug ][ Helper::getPaymentMode() ] ) && $store_info['payment'][ $this->slug ][ Helper::getPaymentMode() ]['connected'] !== false ) {
			$active_payment_methods[] = $this->slug;
		}

		return $active_payment_methods;
	}


	/**
	 * Register new withdrawal method.
	 *
	 * Adds Lemonway as a withdrawal method in Dokan.
	 *
	 * @param array $methods Available withdrawal methods.
	 *
	 * @return array Modified list of withdrawal methods.
	 */
	public function registerLemonwayWithdrawMethod( $methods ) {
		$methods[ $this->slug ] = array(
			'title'        => $this->title,
			'callback'     => array( $this, 'dokanWithdrawMethodCustom' ),
			'apply_charge' => true,
		);

		return $methods;
	}

	/**
	 * Get the withdrawal method icon.
	 *
	 * Retrieves the icon URL for Lemonway withdrawal method.
	 *
	 * @param string $method_icon Method icon URL.
	 * @param string $method_key Method key.
	 *
	 * @return string Modified method icon URL.
	 */
	public function getIcon( $method_icon, $method_key ) {
		if ( $this->slug === $method_key ) {
			$method_icon = $this->plugin->url() . '/assets/public/images/lemonway-icon.jpg';
		}

		return $method_icon;
	}

	/**
	 * Modify payment heading.
	 *
	 * Modifies the payment settings title displayed in Dokan settings.
	 *
	 * @param string $heading Current heading title.
	 * @param string $slug    Slug identifier.
	 *
	 * @return string Modified heading title.
	 */
	public function paymentHeading( $heading, $slug ) {
		if ( ! in_array( $slug, array( $this->slug, $this->slug . '-edit' ), true ) ) {
			return $heading;
		}

		return $this->title . ' ' . esc_html__( 'Setting', 'lemonway' );
	}

	/**
	 * Modify required fields for payment settings.
	 *
	 * Modifies the list of required fields for Lemonway payment settings in Dokan.
	 *
	 * @param array  $required_fields Current required fields.
	 * @param string $payment_method_id Payment method identifier.
	 *
	 * @return array Modified list of required fields.
	 */
	public function requiredFields( $required_fields, $payment_method_id ) {
		if ( $this->slug === $payment_method_id ) {
			$required_fields = array( Helper::getPaymentMode() );
		}
		return $required_fields;
	}

	/**
	 * Modify additional information for payment method.
	 *
	 * Modifies additional information displayed for Lemonway payment method in Dokan.
	 *
	 * @param string $method_info Additional information HTML.
	 * @param string $method_key Method key.
	 *
	 * @return string Modified additional information HTML.
	 */
	public function additionalInfo( $method_info, $method_key ) {

		if ( $method_key !== $this->slug ) {
			return $method_info;
		}

		// Get the connection status as a translated string.
		$connection_status = $this->isSellerConnected()
			? esc_html__( 'Connected', 'lemonway' )
			: esc_html__( 'Not Connected', 'lemonway' );

		$method_info = sprintf(
			wp_kses_post( '- %1$s  : %2$s ' ),
			esc_html__( 'Connect', 'lemonway' ),
			$connection_status
		);

		return $method_info;
	}

	/**
	 * Check if a seller is connected to a payment method.
	 *
	 * Checks if the current seller is connected to the Lemonway payment method.
	 *
	 * @since DOKAN_PRO_SINCE
	 *
	 * @return bool True if connected, false otherwise.
	 */
	public function isSellerConnected() {
		$is_connected   = false;
		$store_id       = dokan_get_current_user_id();
		$store_settings = get_user_meta( $store_id, 'dokan_profile_settings', true );

		if ( isset( $store_settings['payment']['lemonway'][ Helper::getPaymentMode() ]['connected'] ) && $store_settings['payment']['lemonway'][ Helper::getPaymentMode() ]['connected'] ) {
			$is_connected = true;
		}

		return $is_connected;
	}

	/**
	 * Custom callback for bank in store settings.
	 *
	 * Handles custom display for bank details in Dokan store settings.
	 *
	 * @param array $store_settings Store settings data.
	 *
	 * @return void
	 */
	public function dokanWithdrawMethodCustom( $store_settings ) {
		if ( dokan_is_seller_enabled( get_current_user_id() ) ) {
			$this->setting_dokan->template( $store_settings );
		} else {
			dokan_seller_not_enabled_notice();
		}
	}

	public function dokanSettingsKey() {
		return 'dokan_profile_settings';
	}
	public function getDokanSettings( $vendor_id = null ) {

		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}

		$dokan_setting = get_user_meta( $vendor_id, $this->dokanSettingsKey(), true );

		return $dokan_setting ?? array();
	}

	public function getLemonwaySettings( $vendor_id = null ) {

		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}

		$dokan_setting = get_user_meta( $vendor_id, $this->dokanSettingsKey(), true );

		return $this->getDokanSettings( $vendor_id )['payment'][ $this->slug ][ Helper::getPaymentMode() ] ?? array();
	}
	public function lemonwayProfileKey() {
		return 'lemonway_profile_settings_' . Helper::getPaymentMode();
	}

	public function getLemonwayProfileSetting( $vendor_id = null ) {

		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}

		$lemonway_profile = get_user_meta( $vendor_id, $this->lemonwayProfileKey(), true );

		return is_array( $lemonway_profile ) ? $lemonway_profile : array();
	}

	public function lemonwayDocumentKey() {
		return 'lemonway_profile_document_settings_' . Helper::getPaymentMode();
	}

	public function getLemonwayDocumentSetting( $vendor_id = null ) {

		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}

		$lemonway_profile_document = get_user_meta( $vendor_id, $this->lemonwayDocumentKey(), true );

		return is_array( $lemonway_profile_document ) ? $lemonway_profile_document : array();
	}

	public function lemonwayIbanKey() {
		return 'lemonway_profile_iban_settings_' . Helper::getPaymentMode();
	}

	public function getLemonwayIbanSetting( $vendor_id = null ) {

		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}

		$lemonway_iban = get_user_meta( $vendor_id, $this->lemonwayIbanKey(), true );

		return is_array( $lemonway_iban ) ? $lemonway_iban : array();
	}

	public function getDocumentsHtml( $vendor_id = null, $type = null ) {
		if ( ! $vendor_id ) {
			$vendor_id = $this->vendor_id;
		}
		$documents = $this->account->retrieveUploadDocument( Helper::getMerchantId( $vendor_id ) );

		if ( ! is_wp_error( $documents ) ) :
			?>
			<style>
				.grid-list {
					display: grid;
					grid-template-columns: repeat(3, 1fr);
					gap: 10px;
					list-style-position: inside;
					padding-left: 0;
					margin: 0;
				}

				.grid-list li {
					background-color: #f0f0f0;
					border-radius: 5px;
					text-align: center;
					padding: 5px !important;
				}

				@media (max-width: 900px) {
					.grid-list {
						grid-template-columns: repeat(2, 1fr); /
					}
				}

				@media (max-width: 500px) {
					.grid-list {
						grid-template-columns: 1fr;
					}
				}

			</style>
			<?php
			echo '<ul class="grid-list">';
			foreach ( $documents['documents'] as $key => $value ) :
				if ( $type !== null && $type !== $value['type'] ) {
					continue;
				}
				$attachment_id = Helper::getUploadDocumentPostId( $value['id'] );
				?>
				<li>
					<a href="<?php echo esc_url( wp_get_attachment_url( $attachment_id ) ); ?>" target="_blank" rel="noopener noreferrer">
						<h4>
							<?php echo esc_attr( $this->lemonway_api->documentTypes( $value['type'] ) ); ?>
						</h4>
					</a>
					<h6>
						<strong><?php esc_html_e( 'Status:', 'lemonway' ); ?></strong> <?php echo esc_attr( $this->lemonway_api->documentStatus( $value['status'] ) ); ?>
					</h6>
					<?php if ( ! empty( $lemonway_value['comment'] ) ) : ?>
						<p>
							<strong><?php esc_html_e( 'Document Comments:', 'lemonway' ); ?></strong> <?php echo wp_kses_post( $lemonway_value['comment'] ); ?>
						</p>
					<?php endif; ?>
				</li>
				<?php
			endforeach;
			echo '</ul>';
		endif;
	}
}
