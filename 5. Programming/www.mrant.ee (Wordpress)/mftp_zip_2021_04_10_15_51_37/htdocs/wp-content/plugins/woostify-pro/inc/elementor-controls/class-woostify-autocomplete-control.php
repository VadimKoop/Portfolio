<?php
/**
 * Autocomplete control
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main class
 */
class Woostify_Autocomplete_Control extends \Elementor\Base_Data_Control {
	/**
	 * Control type
	 */
	public function get_type() {
		return 'autocomplete';
	}

	/**
	 * Assets
	 */
	public function enqueue() {
		$autocomplete_uri = WOOSTIFY_PRO_URI . 'inc/elementor-controls/assets/';
		wp_register_style(
			'woostify-autocomplete',
			$autocomplete_uri . 'css/autocomplete-control.css',
			array(),
			WOOSTIFY_PRO_VERSION
		);
		wp_enqueue_style( 'woostify-autocomplete' );

		// Scripts.
		wp_register_script(
			'woostify-autocomplete',
			$autocomplete_uri . 'js/autocomplete' . woostify_suffix() . '.js',
			array(),
			WOOSTIFY_PRO_VERSION,
			true
		);
		wp_enqueue_script( 'woostify-autocomplete' );
		wp_localize_script(
			'woostify-autocomplete',
			'woostify_autocomplete',
			array(
				'nonce'     => wp_create_nonce( 'woostify-autocomplete' ),
				'searching' => __( 'Searching...', 'woostify-pro' ),
			)
		);
	}

	/**
	 * Get default value
	 */
	public function get_default_value() {
		return array();
	}

	/**
	 * Get default settings
	 */
	protected function get_default_settings() {
		return array(
			'label_block' => true,
			'placeholder' => esc_html__( 'Please enter 1 or more characters', 'woostify-pro' ),
			'query'       => array(
				'type' => 'post_type', // Available: post_type, term.
				'name' => 'product',
			),
		);
	}

	/**
	 * Content
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<div class="wty-autocomplete">
					<div class="wty-autocomplete-selection">
						<input autocomplete="off" type="text" class="wty-autocomplete-search" placeholder="{{ data.placeholder }}" data-nonce="<?php echo esc_attr( wp_create_nonce( $control_uid ) ); ?>" name="woostify_autocomplete_search"/>

						<input id="<?php echo esc_attr( $control_uid ); ?>" type="hidden" class="wty-autocomplete-selected" data-setting="{{ data.name }}" name="woostify_autocomplete_selected" />
					</div>

					<div class="wty-autocomplete-dropdown"></div>
				</div>
			</div>
		</div>

		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
