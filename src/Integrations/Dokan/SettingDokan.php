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
use Lemonway\Integrations\Lemonway\Api;

/**
 * Class SettingDokan
 *
 * Handles the settings for the Dokan plugin integration with Lemonway.
 */
class SettingDokan {

	/**
	 * Lemonway API instance.
	 *
	 * @var Api
	 */
	public $lemonway_api;
	/**
	 * Countries instance.
	 *
	 * @var Countries
	 */
	public $countires;
	/**
	 * Plugin metadata.
	 *
	 * @var array Contains data from the Plugin config class.
	 * @see Plugin
	 */
	protected $plugin = array();
	/**
	 * Default instance settings.
	 *
	 * @var array
	 */
	protected $default_instance = array(
		'title'   => '',
		'content' => '',
	);

	/**
	 * SettingDokan constructor.
	 * Initializes the Countries and Api instances.
	 */
	public function __construct() {
		$this->countires    = new Countries();
		$this->lemonway_api = new Api();
	}

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
	}

	/**
	 * Get all form fields without steps.
	 *
	 * @return array Merged form fields from all steps.
	 */
	public function fieldsWithoutStep() {
		return array_merge( ...array_values( $this->fields() ) );
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 *
	 * @return array The form fields.
	 */
	public function fields() {
		$fields          = array();
		$fields['step1'] = array(
			'email'           => array(
				'name'          => 'settings[lemonway][email]',
				'type'          => 'email',
				'label'         => esc_html__( 'Email', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your email address', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid email address', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'birthDateVerify' => array(
				'name'          => 'settings[lemonway][birthDateVerify]',
				'type'          => 'date',
				'label'         => esc_html__( 'Date of Birth', 'lemonway' ),
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Date of Birth', 'lemonway' ),
				'class'         => 'dokan-form-group lemonway-hide',
			),
		);
		$fields['step2'] = array(
			'account_title'                 => array(
				'name'          => 'settings[lemonway][account_title]',
				'type'          => 'select',
				'label'         => esc_html__( 'Title', 'lemonway' ),
				'placeholder'   => esc_html__( 'Title', 'lemonway' ),
				'default'       => 'M',
				'options'       => array(
					'M' => 'Mr',
					'F' => 'Mrs',
					'J' => 'Joint account',
					'U' => 'Unknown',
				),
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Account Title', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'firstname'                     => array(
				'name'          => 'settings[lemonway][firstname]',
				'type'          => 'text',
				'label'         => esc_html__( 'First Name', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your first name', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid first name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'lastname'                      => array(
				'name'          => 'settings[lemonway][lastname]',
				'type'          => 'text',
				'label'         => esc_html__( 'Last Name', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your last name', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid last name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'phone'                         => array(
				'name'          => 'settings[lemonway][phone]',
				'type'          => 'text',
				'label'         => esc_html__( 'Phone number', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your phone number', 'lemonway' ),
				'default'       => '',
				'required'      => false,
				'error_message' => esc_html__( 'Invalid phone name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'mobile'                        => array(
				'name'          => 'settings[lemonway][mobile]',
				'type'          => 'text',
				'label'         => esc_html__( 'Mobile number', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your mobile number', 'lemonway' ),
				'default'       => '',
				'required'      => false,
				'error_message' => esc_html__( 'Invalid mobile name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),

			'birth_date'                    => array(
				'name'          => 'settings[lemonway][birth_date]',
				'type'          => 'date',
				'label'         => esc_html__( 'Date of Birth', 'lemonway' ),
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Date of Birth', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'birth_city'                    => array(
				'name'          => 'settings[lemonway][birth_city]',
				'type'          => 'text',
				'label'         => esc_html__( 'City of birth', 'lemonway' ),
				'placeholder'   => esc_html__( 'City of birth', 'lemonway' ),
				'default'       => '',
				'required'      => false,
				'error_message' => esc_html__( 'Invalid mobile name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'birth_country'                 => array(
				'name'          => 'settings[lemonway][birth_country]',
				'type'          => 'select',
				'label'         => esc_html__( 'Country of birth', 'lemonway' ),
				'options'       => $this->countires->listCountries(),
				'required'      => true,
				'placeholder'   => esc_html__( 'Select Country', 'lemonway' ),
				'error_message' => esc_html__( 'Invalid country of birth', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'nationality'                   => array(
				'name'          => 'settings[lemonway][nationality]',
				'type'          => 'select',
				'label'         => esc_html__( 'Nationality', 'lemonway' ),
				'options'       => $this->countires->listCountries(),
				'placeholder'   => esc_html__( 'Select Nationality', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Nationality', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'address'                       => array(
				'name'        => 'settings[lemonway][address]',
				'type'        => 'text',
				'label'       => esc_html__( 'Address', 'lemonway' ),
				'placeholder' => esc_html__( 'Your address', 'lemonway' ),
				'default'     => '',
				'required'    => false,
				'class'       => 'dokan-form-group',
			),
			'postal_code'                   => array(
				'name'        => 'settings[lemonway][postal_code]',
				'type'        => 'text',
				'label'       => esc_html__( 'Postal Code', 'lemonway' ),
				'placeholder' => esc_html__( 'Your Postal Code', 'lemonway' ),
				'default'     => '',
				'required'    => false,
				'class'       => 'dokan-form-group',
			),
			'city'                          => array(
				'name'        => 'settings[lemonway][city]',
				'type'        => 'text',
				'label'       => esc_html__( 'Your City', 'lemonway' ),
				'placeholder' => esc_html__( 'Your city', 'lemonway' ),
				'default'     => '',
				'required'    => false,
				'class'       => 'dokan-form-group',
			),
			'country'                       => array(
				'name'          => 'settings[lemonway][country]',
				'type'          => 'select',
				'label'         => esc_html__( 'Country', 'lemonway' ),
				'options'       => $this->countires->listCountries(),
				'placeholder'   => esc_html__( 'Select Country', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Country', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'company_name'                  => array(
				'name'          => 'settings[lemonway][company_name]',
				'type'          => 'text',
				'label'         => esc_html__( 'Company Name', 'lemonway' ),
				'placeholder'   => esc_html__( 'Company name', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Company name', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'company_description'           => array(
				'name'          => 'settings[lemonway][company_description]',
				'type'          => 'text',
				'label'         => esc_html__( 'Company Description', 'lemonway' ),
				'placeholder'   => esc_html__( 'Company Description', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Company Description', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'company_websiteUrl'            => array(
				'name'          => 'settings[lemonway][company_websiteUrl]',
				'type'          => 'text',
				'label'         => esc_html__( 'Company Website URL', 'lemonway' ),
				'placeholder'   => esc_html__( 'Company Website URL', 'lemonway' ),
				'default'       => '',
				'required'      => false,
				'error_message' => esc_html__( 'Invalid Website URL', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'company_identificationNumber'  => array(
				'name'          => 'settings[lemonway][company_identificationNumber]',
				'type'          => 'text',
				'label'         => esc_html__( 'Company identification number', 'lemonway' ),
				'placeholder'   => esc_html__( 'Company identification number', 'lemonway' ),
				'default'       => '',
				'required'      => false,
				'error_message' => esc_html__( 'Invalid Company identification number', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'agree_term_condition'          => array(
				'name'          => 'settings[lemonway][agree_term_condition]',
				'type'          => 'checkbox',
				'label'         => esc_html__( 'Terms and Conditions - Santerris', 'lemonway' ),
				'description'   => sprintf( /* translators: %1$s is the link to the terms and policies page, %2$s is the text 'Terms & Policies' */ esc_html__( 'I have read and agree to the website %1$s', 'lemonway' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( get_the_permalink( 2243 ) ), esc_html__( ' Terms & Policies', 'lemonway' ) ) ),
				'default'       => '1',
				'required'      => true,
				'error_message' => esc_html__( 'Please read and accept the terms and conditions.', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'agree_term_condition_lemonway' => array(
				'name'          => 'settings[lemonway][agree_term_condition_lemonway]',
				'type'          => 'checkbox',
				'label'         => esc_html__( 'Terms & Policies - Lemonway', 'lemonway' ),
				'description'   => sprintf( /* translators: %1$s is the link to the terms and policies page, %2$s is the text 'Terms & Policies' */ esc_html__( 'I have read and agree to the website %1$s', 'lemonway' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://www.lemonway.com/de/allgemeine-nutzungsbedingungen' ), esc_html__( ' Terms & Policies', 'lemonway' ) ) ),
				'default'       => '1',
				'required'      => true,
				'error_message' => esc_html__( 'Please read and accept the terms and conditions of Lemonway.', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
		);
		$fields['step3'] = array(
			'upload_document_type' => array(
				'name'          => 'settings[lemonway][upload_document_type]',
				'type'          => 'select',
				'label'         => 'Document Type',
				'options'       => $this->lemonway_api->documentTypes(),
				'description'   => sprintf( esc_html__( 'If you have previously uploaded a document in a reserved slot(0-13) and need to upload another document of the same type, use the slot Other.', 'lemonway' ) ),
				'error_message' => esc_html__( 'Please select a document type.', 'lemonway' ),
				'placeholder'   => esc_html__( 'Select document', 'lemonway' ),
				'required'      => true,
				'class'         => 'dokan-form-group',
			),
			'upload_document'      => array(
				'name'          => 'upload_document',
				'type'          => 'file',
				'label'         => 'Document',
				'description'   => sprintf( esc_html__( 'Use JPG, JPEG, PNG or PDF', 'lemonway' ) ),
				'error_message' => esc_html__( 'Please select a document.', 'lemonway' ),
				'required'      => true,
				'accept'        => '.jpg, .jpeg, .png, .pdf',
				'class'         => 'dokan-form-group',
			),
		);

		$fields['iban'] = array(
			'iban_holder_name'         => array(
				'name'          => 'settings[lemonway][iban_holder_name]',
				'type'          => 'text',
				'label'         => esc_html__( 'Registered Bank Account Owner', 'lemonway' ),
				'placeholder'   => esc_html__( 'First and Last name, or Company Name', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Account Owner', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'iban_bic_code'            => array(
				'name'          => 'settings[lemonway][iban_bic_code]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank BIC/SWIFT codes', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank BIC/SWIFT codes', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank BIC/SWIFT codes', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'iban_number'              => array(
				'name'          => 'settings[lemonway][iban_number]',
				'type'          => 'text',
				'label'         => esc_html__( 'IBAN Number', 'lemonway' ),
				'placeholder'   => esc_html__( 'IBAN Number', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid IBAN Number', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'iban_bank_address_line_1' => array(
				'name'          => 'settings[lemonway][iban_bank_address_line_1]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Address Line', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Address Line', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid bank country', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			/* phpcs:disable */
			/*
			'iban_bank_address_line_2' => array(
				'name'          => 'settings[lemonway][iban_bank_address_line_2]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Address Line 2', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Address Line 2', 'lemonway' ),
				'default'       => '',
				'error_message' => esc_html__( 'Invalid Bank Branch code', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),*/

			/* 'iban_upload_document'      => array(
				'name'          => 'upload_document',
				'type'          => 'file',
				'label'         => 'Upload the proof of IBAN',
				'description'   => sprintf( esc_html__( 'Use JPG, JPEG, PNG or PDF', 'lemonway' ) ),
				'error_message' => esc_html__( 'Please select a document.', 'lemonway' ),
				'required'      => true,
				'class'         => 'dokan-form-group',
			),
			*/
			/* phpcs:enable */
		);

		$fields['bank'] = array(
			'bank_account_type'    => array(
				'name'          => 'settings[lemonway][bank_account_type]',
				'type'          => 'select',
				'label'         => esc_html__( 'Account Type', 'lemonway' ),
				'placeholder'   => esc_html__( 'Account Type', 'lemonway' ),
				'default'       => '0',
				'options'       => array(
					'0' => 'Other',
					'1' => 'IBAN',
					'2' => 'BBAN/RIB',
				),
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Account Type', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_holder_name'     => array(
				'name'          => 'settings[lemonway][bank_holder_name]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Holder Name', 'lemonway' ),
				'placeholder'   => esc_html__( 'First and Last name, or Company Name', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Account Owner', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_account_number'  => array(
				'name'          => 'settings[lemonway][bank_account_number]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Account Number', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Account Number', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Account Owner', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_holder_country'  => array(
				'name'          => 'settings[lemonway][bank_holder_country]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Holder country', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your bank holder country', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid country', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_bic_code'        => array(
				'name'          => 'settings[lemonway][bank_bic_code]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank BIC/SWIFT codes', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your Bank BIC/SWIFT codes', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank BIC/SWIFT codes', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_name'            => array(
				'name'          => 'settings[lemonway][bank_name]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Name', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your bank holder country', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid country', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_country'         => array(
				'name'          => 'settings[lemonway][bank_country]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank country', 'lemonway' ),
				'placeholder'   => esc_html__( 'Your bank country', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid bank country', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_branch_code'     => array(
				'name'          => 'settings[lemonway][bank_branch_code]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Branch code', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Branch code', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank Branch code', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_branch_street'   => array(
				'name'          => 'settings[lemonway][bank_branch_street]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Branch Street Address', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Branch code', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank Branch code', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_branch_zip_code' => array(
				'name'          => 'settings[lemonway][bank_branch_zip_code]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Branch Zip Code', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Branch code', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank Branch code', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
			'bank_branch_city'     => array(
				'name'          => 'settings[lemonway][bank_branch_city]',
				'type'          => 'text',
				'label'         => esc_html__( 'Bank Branch City', 'lemonway' ),
				'placeholder'   => esc_html__( 'Bank Branch code', 'lemonway' ),
				'default'       => '',
				'required'      => true,
				'error_message' => esc_html__( 'Invalid Bank Branch code', 'lemonway' ),
				'class'         => 'dokan-form-group',
			),
		);

		return $fields;
	}

	/**
	 * Render the form for a specific step.
	 *
	 * @param array  $args Arguments to pass to the form.
	 * @param string $step The form step to render.
	 */
	public function formStep( $args, $step = 'step1' ) {
		$fields = $this->fields()[ $step ];
		$fields = $this->replaceDefaultValue( $fields, $args );
		$fields = $this->modifyKeys( $fields );

		$fields = new Fields( $fields );
		$fields->render();
	}

	/**
	 * Replace default values in the fields array.
	 *
	 * @param array $fields Form fields.
	 * @param array $args Default values to replace.
	 *
	 * @return array Fields with replaced default values.
	 */
	public function replaceDefaultValue( $fields, $args ) {

		foreach ( $fields as $key => &$field ) {
			// Set the default value based on $args or keep the existing default if available.
			$field['default'] = $args[ $key ] ?? ( $field['default'] ?? '' );
		}

		return $fields;
	}

	/**
	 * Modify keys of the fields array.
	 *
	 * @param array $fields Form fields.
	 *
	 * @return array Modified form fields with new keys.
	 */
	public function modifyKeys( $fields ) {
		$modified_fields = array();
		foreach ( $fields as $key => $field ) {
			$new_key                     = str_replace( 'setting[payment][', 'setting[', $key );
			$modified_fields[ $new_key ] = $field;
		}

		return $modified_fields;
	}

	/**
	 * Render the template with provided arguments.
	 *
	 * @param array $args Arguments to pass to the template.
	 */
	public function template( $args ): void {
		lemonway()->templates()->get(
			'dokan-payment-template',
			null,
			$args
		);
	}
}
