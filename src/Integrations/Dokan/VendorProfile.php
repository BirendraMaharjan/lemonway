<?php
/**
 * Lemonway
 *
 * @package   lemonway
 */

declare( strict_types=1 );

namespace Lemonway\Integrations\Dokan;

use Lemonway\Integrations\Gateway\Helper;
use WP_Error;

/**
 * Class VendorProfile
 *
 * @package OnlinePaymentPlatformGateway\Integrations\Dokan
 */
class VendorProfile extends Dokan {
	public function init() {
		add_action( 'wp_ajax_lemonway_dokan_settings', array( $this, 'profile' ) );
		add_action( 'wp_ajax_lemonway_deactivate_link_bank_account', array( $this, 'DeactivateLinkBankAccount' ) );
	}

	public function profile() {
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'dokan_payment_settings_nonce' ) ) {
			wp_send_json_error( __( 'Are you cheating?', 'lemonway' ) );
		}

		if ( ! isset( $_POST['form_step'] ) ) {
			wp_send_json_error( __( 'Invalid Account Data', 'lemonway' ) );
		}

		$form_step = sanitize_text_field( wp_unslash( $_POST['form_step'] ) );

		if ( isset( $_POST['settings']['lemonway'] ) ) {
			$post_data = array_map( 'sanitize_text_field', wp_unslash( $_POST['settings']['lemonway'] ) );
			if ( ! is_array( $post_data ) ) {
				wp_send_json_error( __( 'Invalid Account Data provided', 'lemonway' ) );
			}
		} elseif ( $form_step !== 'disconnect' ) {
			wp_send_json_error( __( 'Invalid Account Data', 'lemonway' ) );
		}

		$merchant_id = isset( $_POST['merchant_id'] ) ? sanitize_text_field( wp_unslash( $_POST['merchant_id'] ) ) : '';

		switch ( $form_step ) {
			case 'disconnect':
				$result = $this->disconnect();
				break;
			case 'step1':
				$result = $this->checkEmail( $post_data );
				break;
			case 'verification':
				$result = $this->verification( $post_data );
				break;
			case 'step2':
				$result = $this->information( $post_data, $merchant_id );
				break;
			case 'step3':
				if ( isset( $_FILES['upload_document'] ) && ! empty( $_FILES['upload_document']['name'] ) ) {
					$post_data['upload_document'] = array_map( 'sanitize_text_field', $_FILES['upload_document'] );
				} else {
					$post_data['upload_document'] = '';
				}
				$result = $this->uploadDocument( $post_data );
				break;
			case 'bank_link':
				$result = $this->bankLink( $post_data );
				break;
			default:
				$success_msg = esc_html__( 'Your information has been saved successfully', 'lemonway' );
				$result      = array(
					'msg' => $success_msg,
				);
				break;
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( wp_json_encode( $result->get_error_message(), JSON_PRETTY_PRINT ) );
		}

		wp_send_json_success( $result );
	}

	/**
	 * Disconnects the Lemonway payment method for the current Dokan store.
	 *
	 * @return array Array containing result information after disconnecting.
	 */
	public function disconnect() {
		$vendor_id = $this->vendor_id;

		if ( ! $this->vendor_id ) {
			wp_send_json_error( esc_html__( 'Vendor not found.', 'lemonway' ) );
		}

		$dokan_settings = $this->getDokanSettings();
		$dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ]['connected'] = false;

		update_user_meta( $vendor_id, 'dokan_profile_settings', $dokan_settings );
		delete_user_meta( $vendor_id, Helper::getMerchantKey() );

		return array(
			'form_step'    => 'verified',
			'msg'          => esc_html__( 'Disconnect Successfully!', 'lemonway' ),
			'redirect_url' => esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ),
			'btn_text'     => esc_html__( 'Redirecting...', 'lemonway' ),
		);
	}

	/**
	 * Checks the validity of an email address and retrieves account information if available.
	 *
	 * @param array $data Array containing email address under 'email' key.
	 */
	public function checkEmail( $data ) {

		$email = sanitize_email( $data['email'] );

		if ( ! is_email( $email ) ) {
			wp_send_json_error( esc_html__( 'Invalid email address.', 'lemonway' ) );
		}

		$response = $this->account->retrieve( $email );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message() );
		}

		$result = array(
			'form_step' => 'step2',
			'msg'       => esc_html__( 'Please fill out all required fields with valid data.', 'lemonway' ),
			'btn_text'  => esc_html__( 'Connect Account', 'lemonway' ),
		);

		if ( $response['email'] === $email ) {
			$result = array(
				'form_step' => 'verification',
				'msg'       => esc_html__( 'The email address is already in use. Please enter a Date of Birth to verify Account.', 'lemonway' ),
				'btn_text'  => esc_html__( 'Verify Account', 'lemonway' ),
			);
		}

		return $result;
	}

	public function verification( $data ) {

		$email = sanitize_email( $data['email'] );

		$birth_date_verify = sanitize_text_field( $data['birthDateVerify'] );
		$response          = $this->account->retrieve( $email );

		foreach ( $this->setting_dokan->fields()['step1'] as $key => $value ) {
			$message = empty( $value['error_message'] ) ? esc_html__( 'Invalid', 'lemonway' ) : $value['error_message'];

			if ( ! empty( $value['required'] ) && empty( $data[ $key ] ) ) {
				wp_send_json_error( $value['label'] . ': ' . $message );
			}
		}

		$new_birth_date_verify = gmdate( 'd/m/Y', strtotime( $birth_date_verify ) );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message() );
		}

		if ( $response['birth']['date'] !== $new_birth_date_verify ) {
			wp_send_json_error( esc_html__( "The email address and date of birth don\'t match.", 'lemonway' ) );
		}

		$vendor_id = $this->vendor_id;
		update_user_meta( $vendor_id, Helper::getMerchantKey(), sanitize_text_field( $data['id'] ) );
		update_user_meta( $vendor_id, Helper::getMerchantInternalKey(), sanitize_text_field( $data['internalId'] ) );

		$response = $this->insertDokanSettingsInfoFromApi( $response );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message() );
		}

		return array(
			'form_step'    => 'verified',
			'msg'          => esc_html__( 'Connect Successfully!', 'lemonway' ),
			'redirect_url' => esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ),
			'btn_text'     => esc_html__( 'Redirecting...', 'lemonway' ),
		);
	}

	public function insertDokanSettingsInfoFromApi( $data ) {

		$vendor_id = $this->vendor_id;

		if ( ! $this->vendor_id ) {
			wp_send_json_error( esc_html__( 'Vendor not found.', 'lemonway' ) );
		}

		$dokan_settings = $this->getDokanSettings();

		if ( $data['id'] !== $dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ]['lemonway_account_id'] ) {
			$new_data['email'] = sanitize_email( $data['email'] );

			$new_data['account_title'] = sanitize_text_field( ! empty( $data['clientTitle'] ) ? $data['clientTitle'] : '' );
			$new_data['firstname']     = sanitize_text_field( ! empty( $data['firstname'] ) ? $data['firstname'] : '' );
			$new_data['lastname']      = sanitize_text_field( ! empty( $data['lastname'] ) ? $data['lastname'] : '' );

			$new_data['phone']  = sanitize_text_field( ! empty( $data['phoneNumber'] ) ? $data['phoneNumber'] : '' );
			$new_data['mobile'] = sanitize_text_field( ! empty( $data['mobileNumber'] ) ? $data['mobileNumber'] : '' );

			if ( ! empty( $data['birth']['date'] ) ) {
				$new_data['birth_date'] = sanitize_text_field(
					gmdate(
						'Y/m/d',
						strtotime(
							str_replace( '/', '-', $data['birth']['date'] )
						)
					)
				);
			}

			$new_data['birth_city']    = sanitize_text_field( ! empty( $data['birth']['city'] ) ? $data['birth']['city'] : '' );
			$new_data['birth_country'] = sanitize_text_field( ! empty( $data['birth']['Country'] ) ? $data['birth']['Country'] : '' );

			$new_data['nationality'] = sanitize_text_field( ! empty( $data['nationality'] ) ? $data['nationality'] : '' );

			$new_data['address']     = sanitize_text_field( ! empty( $data['adresse']['street'] ) ? $data['adresse']['street'] : '' );
			$new_data['postal_code'] = sanitize_text_field( ! empty( $data['adresse']['postCode'] ) ? $data['adresse']['postCode'] : '' );
			$new_data['city']        = sanitize_text_field( ! empty( $data['adresse']['city'] ) ? $data['adresse']['city'] : '' );
			$new_data['country']     = sanitize_text_field( ! empty( $data['adresse']['country'] ) ? $data['adresse']['country'] : '' );

			$new_data['account_type']                 = sanitize_text_field( ! empty( $data['accountType'] ) && $data['accountType'] === 1 ? 'company' : 'individual' );
			$new_data['company_name']                 = sanitize_text_field( ! empty( $data['company']['name'] ) ? $data['company']['name'] : '' );
			$new_data['company_description']          = sanitize_text_field( ! empty( $data['company']['description'] ) ? $data['company']['description'] : '' );
			$new_data['company_websiteUrl']           = sanitize_text_field( ! empty( $data['company']['websiteUrl'] ) ? $data['company']['websiteUrl'] : '' );
			$new_data['company_identificationNumber'] = sanitize_text_field( ! empty( $data['company']['identificationNumber'] ) ? $data['company']['identificationNumber'] : '' );

			$new_data['agree_term_condition']          = '1';
			$new_data['agree_term_condition_lemonway'] = '1';
			$new_data['lemonway_account_id']           = sanitize_text_field( $data['id'] );
			$new_data['lemonway_account_internalId']   = sanitize_text_field( $data['internalId'] );
			$new_data['connected']                     = true;

			if ( ! isset( $dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ] ) ) {
				// Initialize the key if it doesn't exist, here we set it as an empty array.
				$dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ] = array();
			}

			$dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ] = array_merge( $dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ], $new_data );
		} else {
			$dokan_settings['payment'][ $this->slug ][ Helper::getPaymentMode() ]['connected'] = true;
		}

		update_user_meta( $vendor_id, $this->dokanSettingsKey(), $dokan_settings );
		update_user_meta( $vendor_id, Helper::getMerchantKey(), sanitize_text_field( $data['id'] ) );
		update_user_meta( $vendor_id, Helper::getMerchantInternalKey(), sanitize_text_field( $data['internalId'] ) );
	}

	public function information( $data, $merchant_id = null ) {

		$vendor_id = $this->vendor_id;

		if ( ! $this->vendor_id ) {
			wp_send_json_error( esc_html__( 'Vendor not found.', 'lemonway' ) );
		}

		$dokan_settings = $this->getDokanSettings();

		$new_data['email']        = sanitize_email( $data['email'] );
		$new_data['account_type'] = sanitize_text_field( $data['account_type'] );
		$new_data['vendor_id'] = $vendor_id;


		foreach ( $this->setting_dokan->fields()['step2'] as $key => $value ) {
			$message = empty( $value['error_message'] ) ? esc_html__( 'Invalid', 'lemonway' ) : $value['error_message'];

			if ( ! empty( $value['required'] ) && empty( $data[ $key ] ) ) {
				wp_send_json_error( $value['label'] . ': ' . $message );
			}
			$new_data[ $key ] = sanitize_text_field( $data[ $key ] );
		}

		if ( $merchant_id ) {
			if ( $merchant_id !== Helper::getMerchantId() ) {
				wp_send_json_error( esc_html__( 'Are you cheating?', 'lemonway' ) );
			}

			$account = $this->account->userAccount( $new_data, $merchant_id );
			$result  = array(
				'form_step'    => 'completed',
				'msg'          => esc_html__( 'Account has been updated.', 'lemonway' ),
				'redirect_url' => esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ),
				'btn_text'     => esc_html__( 'Redirecting...', 'lemonway' ),
			);
		} else {
			$account = $this->account->userAccount( $new_data );

			$result = array(
				'form_step' => 'step3',
				'msg'       => esc_html__( 'Account has been created. Please upload document for KYC verification.', 'lemonway' ),
				'btn_text'  => esc_html__( 'Upload Document', 'lemonway' ),
			);
		}

		if ( is_wp_error( $account ) ) {
			wp_send_json_error( $account->get_error_message() );
		}

		$account_id          = false;
		$account_internal_id = false;

		if ( isset( $account['legalAccount'] ) ) {
			$account_id          = $account['legalAccount']['id'];
			$account_internal_id = $account['legalAccount']['internalId'];
		} elseif ( isset( $account['account'] ) ) {
			$account_id          = $account['account']['id'];
			$account_internal_id = $account['account']['internalId'];
		} else {
			wp_send_json_error( esc_html__( 'Lemonway account processing error.', 'lemonway' ) );
		}

		if ( ! $vendor_id && ! $account_id && ! $account_internal_id ) {
			wp_send_json_error( esc_html__( 'Lemonway account error.', 'lemonway' ) );
		}

		$new_data['lemonway_account_id']          = sanitize_text_field( $account_id );
		$new_data['lemonway_account_internal_id'] = sanitize_text_field( $account_internal_id );
		$new_data['connected']                    = true;

		$lemonway_setting[ $this->slug ][ Helper::getPaymentMode() ] = $new_data;

		$dokan_settings['payment'] = array_merge( $dokan_settings['payment'], $lemonway_setting );

		update_user_meta( $vendor_id, $this->dokanSettingsKey(), $dokan_settings );
		update_user_meta( $vendor_id, Helper::getMerchantKey(), sanitize_text_field( $account_id ) );
		update_user_meta( $vendor_id, Helper::getMerchantInternalKey(), sanitize_text_field( $account_internal_id ) );

		return $result;
	}

	public function uploadDocument( $data ) {

		$vendor_id = $this->vendor_id;

		if ( ! $this->vendor_id ) {
			wp_send_json_error( esc_html__( 'Vendor not found.', 'lemonway' ) );
		}

		$document = $this->getLemonwayDocumentSetting();

		$upload_document = array();

		foreach ( $this->setting_dokan->fields()['step3'] as $key => $value ) {
			$message = empty( $value['error_message'] ) ? esc_html__( 'Invalid', 'lemonway' ) : $value['error_message'];

			if ( ! empty( $value['required'] ) && ( $data[ $key ] !== '0' && empty( $data[ $key ] ) ) ) {
				wp_send_json_error( $value['label'] . ': ' . $message );
			}

			if ( $value['type'] === 'file' ) {

				if ( empty( $data[ $key ]['tmp_name'] ) ) {
					wp_send_json_error( $value['label'] . ': ' . $message );
				}

				if ( ! empty( $data[ $key ]['tmp_name'] ) ) {

					$upload = $this->validateAndUploadFile( $data[ $key ], $value, $data['upload_document_type'] );

					if ( is_wp_error( $upload ) ) {
						wp_send_json_error( $upload->get_error_message() );
					}

					$upload_document[ $data['upload_document_type'] ][ $key ] = sanitize_text_field( $upload );
					$upload_document[ $data['upload_document_type'] ]['user'] = Helper::getMerchantId( $vendor_id );
				}
			} else {
				$upload_document[ $data['upload_document_type'] ][ $key ] = sanitize_text_field( $data[ $key ] );
			}
		}

		$document = array_merge( $document, $upload_document );

		update_user_meta( $vendor_id, $this->lemonwayDocumentKey(), $document );

		return array(
			'form_step'    => 'completed',
			'msg'          => esc_html__( 'Document upload successful. KYC verification is currently in process.', 'lemonway' ),
			'redirect_url' => esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit' ) ),
			'btn_text'     => esc_html__( 'Redirecting...', 'lemonway' ),
		);
	}

	private function validateAndUploadFile( $file, $value, $document_type ) {
		$file_type     = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$allowed_types = array( 'jpg', 'png', 'jpeg', 'pdf' );
		$max_file_size = 4 * 1024 * 1024; // 4 MB
		$min_file_size = 10 * 1024; // 100 KB

		$file_size = $file['size'];

		if ( ! in_array( $file_type, $allowed_types, true ) ) {
			return new WP_Error( 'invalid_file_type', $value['label'] . ': ' . esc_html__( 'Sorry, only JPG, JPEG, PNG, & PDF files are allowed.', 'lemonway' ) );
		}
		if ( $file_size < $min_file_size ) {
			return new WP_Error( 'file_too_small', $value['label'] . ': ' . esc_html__( 'Sorry, your file is too small. Minimum size is 100 KB.', 'lemonway' ) );
		}
		if ( $file_size > $max_file_size ) {
			return new WP_Error( 'file_too_large', $value['label'] . ': ' . esc_html__( 'Sorry, your file is too large.', 'lemonway' ) );
		}

		$upload_id = $this->handleDocumentUploadOnlyOne( $file );

		if ( is_wp_error( $upload_id ) ) {
			return $upload_id;
		}
		$upload_document = $this->account->uploadDocument( Helper::getMerchantId(), $upload_id, $document_type );

		if ( is_wp_error( $upload_document ) ) {
			$this->deleteAttachment( $upload_id );
			$message = esc_html__( 'Document upload failed. Please try again later.', 'lemonway' );
			if ( $upload_document->get_error_message() === 'DUPLICATED_DOCUMENT_FOUND' ) {
				$message = esc_html__( 'Document upload failed. This type of document has already been uploaded.', 'lemonway' );
			}

			return new WP_Error( 'upload_failed', $value['label'] . ': ' . $message );
		}

		if ( ! empty( $upload_document->errors ) ) {
			return new WP_Error( 'upload_failed', $value['label'] . ': ' . esc_html__( 'Document upload failed.', 'lemonway' ) );
		}

		update_post_meta( $upload_id, Helper::getUploadDocumentKey(), $upload_document['uploadDocument']['id'] );

		return $upload_id;
	}

	public function handleDocumentUploadOnlyOne( $file ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		add_filter( 'upload_dir', array( $this, 'customUploadFolder' ) );
		$upload_file = wp_handle_upload( $file, array( 'test_form' => false ) );
		remove_filter( 'upload_dir', array( $this, 'customUploadFolder' ) );

		if ( $upload_file && ! isset( $upload_file['error'] ) ) {
			// File uploaded successfully.
			$filename      = $upload_file['file'];
			$filetype      = wp_check_filetype( $filename, null );
			$wp_upload_dir = wp_upload_dir();

			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => sanitize_file_name( basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			// Insert the attachment into the WordPress media library.
			$attach_id = wp_insert_attachment( $attachment, $filename );

			return $attach_id;
		} else {
			return new WP_Error( 'upload_error', $upload_file['error'] );
		}
	}

	private function deleteAttachment( $attachment_id ) {
		if ( ! is_numeric( $attachment_id ) ) {

			return false;
		}

		$attachment = get_post( $attachment_id );
		if ( ! $attachment || $attachment->post_type !== 'attachment' ) {

			return false;
		}

		if ( ! current_user_can( 'delete_post', $attachment_id ) ) {

			return false;
		}

		$file_path = get_attached_file( $attachment_id );
		if ( ! $file_path ) {

			return false;
		}

		if ( wp_delete_attachment( $attachment_id, true ) ) {

			if ( file_exists( $file_path ) ) {
				if ( unlink( $file_path ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	public function bankLink( $data ) {

		$vendor_id = $this->vendor_id;

		if ( ! $this->vendor_id ) {
			wp_send_json_error( esc_html__( 'Vendor not found.', 'lemonway' ) );
		}

		$merchant_id = Helper::getMerchantId( $vendor_id );

		if ( ! $merchant_id ) {
			wp_send_json_error( esc_html__( 'Merchant not found.', 'lemonway' ) );
		}

		foreach ( $this->setting_dokan->fields()['iban'] as $key => $value ) {
			$message = empty( $value['error_message'] ) ? esc_html__( 'Invalid', 'lemonway' ) : $value['error_message'];

			if ( ! empty( $value['required'] ) && $data[ $key ] === '' ) {
				wp_send_json_error( $value['label'] . ': ' . $message );
			}
			$new_data[ $key ] = sanitize_text_field( $data[ $key ] );
		}

		$account = $this->iban->iban( $data, $merchant_id );

		if ( is_wp_error( $account ) ) {
			wp_send_json_error( $account->get_error_message() );
		}

		$new_data['ibanId'] = $account['ibanId'];
		$new_data['status'] = $account['status'];
		$store_data['iban'] = $new_data;

		$iban_setting = $this->getLemonwayIbanSetting();

		$document = array_merge( $iban_setting, $store_data );

		update_user_meta( $vendor_id, $this->lemonwayIbanKey(), $document );

		return array(
			'form_step'    => 'bank_link',
			'msg'          => esc_html__( 'Bank Link successful.', 'lemonway' ),
			'redirect_url' => esc_url( dokan_get_navigation_url( 'settings/payment-manage-lemonway-edit/?link-bank=added' ) ),
			'btn_text'     => esc_html__( 'Reloading...', 'lemonway' ),
		);
	}

	public function DeactivateLinkBankAccount() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lemonway-ajax-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__('Nonce verification failed.', 'lemonway') ) );

			return; // Stop execution if nonce verification fails
		}

		if ( ! isset( $_POST['iban_id'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__('IBAN ID missing.', 'lemonway') ) );
		}



		$vendor_id = $this->vendor_id;
		if ( ! $this->vendor_id ) {
			wp_send_json_error( array( 'message' => esc_html__('Vendor not found.', 'lemonway') ) );
		}

		$merchant_id = Helper::getMerchantId( $vendor_id );
		if ( ! $merchant_id ) {
			wp_send_json_error( array( 'message' => esc_html__('Merchant not found.', 'lemonway') ) );
		}


		$iban_id = $_POST['iban_id'];

		$account = $this->iban->unregister( $iban_id, $merchant_id );

		if ( ! empty( $account['id'] ) ) {
			wp_send_json_success( array( 'message' => esc_html__( 'IBAN has been deactivated.', 'lemonway' ) ) );
		}
		wp_send_json_error( array( 'message' => esc_html__('Merchant not found.', 'lemonway') ) );

	}

	public function customUploadFolder( $dir_data ) {
		$custom_dir = 'lemonway';

		return array(
			'path'    => $dir_data['basedir'] . '/' . $custom_dir,
			'url'     => $dir_data['url'] . '/' . $custom_dir,
			'subdir'  => '/' . $custom_dir,
			'basedir' => $dir_data['error'],
			'error'   => $dir_data['error'],
		);
	}
}
