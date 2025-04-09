<?php
/**
 * Lemonway form files
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Form;

/**
 * Class Fields
 *
 * Generates HTML form fields based on provided configurations.
 */
class Fields {

	/**
	 * HTML elements container.
	 *
	 * @var array An array to store HTML elements.
	 */
	public $html = array();

	/**
	 * Form fields container.
	 *
	 * @var array An array to store form fields.
	 */
	public $fields = array();

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
	}

	/**
	 * Constructor.
	 *
	 * @param array $fields Array of field configurations.
	 */
	public function __construct( array $fields = array() ) {
		$this->fields = $fields;
		if ( ! empty( $fields ) ) {
			$this->generateFields( $fields );
		}
	}

	/**
	 * Generate HTML for each field based on its configuration.
	 *
	 * @param array $fields Array of field configurations.
	 */
	public function generateFields( array $fields ): void {
		foreach ( $fields as $key => $field ) {
			$name         = ! empty( $field['name'] ) ? $field['name'] : $key;
			$this->html[] = $this->generateFieldHtml( $key, $name, $field );
		}
	}

	/**
	 * Render all generated HTML fields.
	 */
	public function render(): void {
		echo implode( '', $this->html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Generate HTML for a single field based on its type.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the field.
	 */
	private function generateFieldHtml( string $key, string $name, array $field ): string {
		switch ( $field['type'] ) {
			case 'text':
				return $this->formGroup( $field['type'], $key, $field, $this->inputText( $key, $name, $field ) );
			case 'email':
				return $this->formGroup( $field['type'], $key, $field, $this->inputEmail( $key, $name, $field ) );
			case 'textarea':
				return $this->formGroup( $field['type'], $key, $field, $this->inputTextarea( $key, $name, $field ) );
			case 'checkbox':
				return $this->formGroup( $field['type'], $key, $field, $this->inputCheckbox( $key, $name, $field ) );
			case 'radio':
				return $this->formGroup( $field['type'], $key, $field, $this->inputRadio( $key, $name, $field ) );
			case 'select':
				return $this->formGroup( $field['type'], $key, $field, $this->inputSelect( $key, $name, $field ) );
			case 'date':
				return $this->formGroup( $field['type'], $key, $field, $this->inputDate( $key, $name, $field ) );
			case 'file':
				return $this->formGroup( $field['type'], $key, $field, $this->inputFile( $key, $name, $field ) );
			case 'hidden':
				return $this->inputHidden( $key, $name, $field );
			default:
				return '';
		}
	}

	/**
	 * Generate HTML for a form group (label, input, description).
	 *
	 * @param string $type Field type.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @param string $input_html HTML for the input field.
	 * @return string Generated HTML for the form group.
	 */
	private function formGroup( string $type, string $name, array $field, string $input_html ): string {
		$class       = ! empty( $field['class'] ) ? esc_attr( $field['class'] ) : '';
		$class      .= ! empty( $field['required'] ) ? ' field-required' : '';
		$label       = ! empty( $field['label'] ) ? '<label for="form-input-' . esc_attr( $name ) . '">' . esc_html( $field['label'] ) . '</label>' : '';
		$description = ! empty( $field['description'] ) ? '<small><br>' . wp_kses_post( $field['description'] ) . '</small>' : '';

		return '<div class="form-group ' . $class . ' field-type-' . esc_attr( $type ) . '">' . $label . $input_html . $description . '</div>';
	}

	/**
	 * Generate HTML for a text input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputText( string $key, string $name, array $field ): string {
		$value       = ! empty( $field['default'] ) ? esc_attr( $field['default'] ) : '';
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$required    = ! empty( $field['required'] ) ? 'required' : '';

		return '<input id="form-input-' . esc_attr( $key ) . '" type="text" name="' . esc_attr( $name ) . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control" ' . $required . '>';
	}

	/**
	 * Generate HTML for an email input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputEmail( string $key, string $name, array $field ): string {
		$value       = ! empty( $field['default'] ) ? esc_attr( $field['default'] ) : '';
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$required    = ! empty( $field['required'] ) ? 'required' : '';

		return '<input id="form-input-' . esc_attr( $key ) . '" type="email" name="' . esc_attr( $name ) . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control" ' . $required . '>';
	}

	/**
	 * Generate HTML for a date input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputDate( $key, $name, $field ) {
		$value       = ! empty( $field['default'] ) ? esc_attr( $field['default'] ) : '';
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$required    = ! empty( $field['required'] ) ? 'required' : '';

		return '<input id="form-input-' . esc_attr( $key ) . '" type="text" name="' . esc_attr( $name ) . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control" ' . $required . '>';
	}

	/**
	 * Generate HTML for a textarea input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputTextarea( $key, string $name, array $field ): string {
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$required    = ! empty( $field['required'] ) ? 'required' : '';
		$value       = ! empty( $field['default'] ) ? esc_html( $field['default'] ) : '';

		return '<textarea id="form-textarea-' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" placeholder="' . $placeholder . '" class="form-control" ' . $required . '>' . $value . '</textarea>';
	}

	/**
	 * Generate HTML for a checkbox input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputCheckbox( $key, string $name, array $field ): string {
		$checked  = ! empty( $field['default'] ) ? 'checked' : '';
		$required = ! empty( $field['required'] ) ? 'required' : '';
		$label    = ! empty( $field['label'] ) ? esc_html( $field['label'] ) : '';

		return '<input id="form-checkbox-' . esc_attr( $key ) . '" type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . $checked . ' ' . $required . '>' . $label;
	}

	/**
	 * Generate HTML for a radio input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputRadio( $key, string $name, array $field ): string {
		$html = '';
		foreach ( $field['options'] as $option_value => $option_label ) {
			$checked  = ! empty( $field['default'] ) && $field['default'] === $option_value ? 'checked' : '';
			$required = ! empty( $field['required'] ) ? 'required' : '';

			$html .= '<div class="form-check">';
			$html .= '<input class="form-check-input" type="radio" name="' . esc_attr( $name ) . '" id="form-radio-' . esc_attr( $key ) . '-' . esc_attr( $option_value ) . '" value="' . esc_attr( $option_value ) . '" ' . $checked . ' ' . $required . '>';
			$html .= '<label class="form-check-label" for="form-radio-' . esc_attr( $key ) . '-' . esc_attr( $option_value ) . '">' . esc_html( $option_label ) . '</label>';
			$html .= '</div>';
		}
		return $html;
	}

	/**
	 * Generate HTML for a select field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputSelect( $key, string $name, array $field ): string {
		$required    = ! empty( $field['required'] ) ? 'required' : '';
		$placeholder = ! empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$html        = '<select id="form-select-' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" class="form-control" ' . $required . '>';

		if ( ! empty( $placeholder ) ) {
			$html .= '<option value="">' . esc_html( $placeholder ) . '</option>';
		}

		if ( ! empty( $field['options'] ) ) :
			foreach ( $field['options'] as $option_value => $option_label ) {
				$selected = ! empty( $field['default'] ) && $field['default'] === $option_value ? 'selected' : '';
				$html    .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '>' . esc_html( $option_label ) . '</option>';
			}
		endif;

		$html .= '</select>';
		return $html;
	}

	/**
	 * Generate HTML for a file input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputFile( $key, $name, $field ) {
		$accept   = isset( $field['accept'] ) ? 'accept="' . esc_attr( $field['accept'] ) . '"' : '';
		$required = ! empty( $field['required'] ) ? 'required' : '';

		$html = sprintf(
			'<input id="form-file-%s" type="file" name="%s" class="form-control" %s %s>',
			esc_attr( $key ),
			esc_attr( $name ),
			$required,
			$accept
		);

		return $html;
	}


	/**
	 * Generate HTML for a hidden input field.
	 *
	 * @param string $key Field key.
	 * @param string $name Field name.
	 * @param array  $field Field configuration.
	 * @return string Generated HTML for the text input field.
	 */
	private function inputHidden( $key, string $name, array $field ): string {
		$value = ! empty( $field['default'] ) ? esc_attr( $field['default'] ) : '';

		return '<input id="form-hidden-' . esc_attr( $key ) . '" type="hidden" name="' . esc_attr( $name ) . '" value="' . $value . '">';
	}
}
