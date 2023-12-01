<?php
/**
 * Elementor Checkout form Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class widget.
 */
class Woostify_Checkout_Form extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-checkout-page' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-checkout-form';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Checkout Form', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'checkout', 'form', 'store', 'cart' );
	}

	/**
	 * Get Elementor frontend style.
	 */
	public function get_script_depends() {
		return array( 'select2' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->label_field();
		$this->text_field();
		$this->dropdown_select_field();
		$this->ship_to_different_address();
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'woostify_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Label field
	 */
	public function label_field() {
		$this->start_controls_section(
			'label',
			array(
				'label' => __( 'Label Field', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields__field-wrapper label, {{WRAPPER}} .woocommerce-shipping-fields__field-wrapper label, {{WRAPPER}} #order_comments_field label',
			)
		);

		// Color.
		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-billing-fields__field-wrapper label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-fields__field-wrapper label' => 'color: {{VALUE}};',
					'{{WRAPPER}} #order_comments_field label' => 'color: {{VALUE}};',
				),
			)
		);

		// Required color.
		$this->add_control(
			'label_required_color',
			array(
				'label'     => __( 'Required Character Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .validate-required .required' => 'color: {{VALUE}};',
				),
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'label_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-billing-fields__field-wrapper label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-shipping-fields__field-wrapper label' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} #order_comments_field' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'label_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-billing-fields__field-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-shipping-fields__field-wrapper label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} #order_comments_field label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Text field
	 */
	public function text_field() {
		$this->start_controls_section(
			'text_field',
			array(
				'label' => __( 'Text Field', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_field_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field',
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'text_field_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'text_field_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'text_field_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'separator' => 'before',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'text_field_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border radius.
		$this->add_control(
			'text_field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'text_field_tabs' );

		// Normal.
		$this->start_controls_tab(
			'text_field_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'text_field_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'text_field_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'text_field_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text, .select2-container--default .select2-search--dropdown .select2-search__field' => 'border-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Focus.
		$this->start_controls_tab(
			'text_field_focus',
			array(
				'label' => __( 'Focus', 'woostify-pro' ),
			)
		);

		// Focus color.
		$this->add_control(
			'text_field_focus_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus' => 'color: {{VALUE}};',
				),
			)
		);

		// Focus BG color.
		$this->add_control(
			'text_field_focus_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'text_field_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .form-row .input-text:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Dropdown select field
	 */
	public function dropdown_select_field() {
		$this->start_controls_section(
			'dropdown_select',
			array(
				'label' => __( 'Dropdown Select Field', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'dropdown_select_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .select2-selection__rendered, #select2-billing_country-results, #select2-shipping_country-results, .select2-search__field',
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'dropdown_select_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .select2-container--default' => 'text-align: {{VALUE}};',
					'#select2-billing_country-results'  => 'text-align: {{VALUE}};',
					'#select2-shipping_country-results' => 'text-align: {{VALUE}};',
					'.select2-search__field'            => 'text-align: {{VALUE}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'dropdown_select_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .select2-container .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'dropdown_select_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'separator' => 'before',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .select2-container:not(.select2-container--open) .select2-selection--single' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection--single' => 'border-style: {{VALUE}};',
					'.single-woo_builder .select2-container.select2-container--open .select2-dropdown' => 'border-style: {{VALUE}};',
					'.woocommerce-checkout .select2-container.select2-container--open .select2-dropdown' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'dropdown_select_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .select2-container:not(.select2-container--open) .select2-selection--single' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection--single' => 'border-width: {{SIZE}}{{UNIT}};',
					'.single-woo_builder .select2-container.select2-container--open .select2-dropdown' => 'border-width: {{SIZE}}{{UNIT}}; border-top: 0;',
					'.woocommerce-checkout .select2-container.select2-container--open .select2-dropdown' => 'border-width: {{SIZE}}{{UNIT}}; border-top: 0;',
				),
			)
		);

		// Border radius.
		$this->add_control(
			'dropdown_select_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .select2-container:not(.select2-container--open) .select2-selection--single' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection--single' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0px 0px;',
					'.single-woo_builder .select2-container.select2-container--open .select2-dropdown' => 'border-radius: 0px 0px {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
					'.woocommerce-checkout .select2-container.select2-container--open .select2-dropdown' => 'border-radius: 0px 0px {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'dropdown_select_tabs' );

		// Normal.
		$this->start_controls_tab(
			'dropdown_select_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'dropdown_select_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-selection__rendered' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'dropdown_select_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-container:not(.select2-container--open) .select2-selection--single' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'dropdown_select_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-container--default .select2-selection--single' => 'border-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Focus.
		$this->start_controls_tab(
			'dropdown_focus',
			array(
				'label' => __( 'Focus', 'woostify-pro' ),
			)
		);

		// Focus color.
		$this->add_control(
			'dropdown_select_focus_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection__rendered' => 'color: {{VALUE}};',
					'#select2-billing_country-results'  => 'color: {{VALUE}};',
					'#select2-shipping_country-results' => 'color: {{VALUE}};',
				),
			)
		);

		// Focus BG color.
		$this->add_control(
			'dropdown_select_focus_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection--single' => 'background-color: {{VALUE}};',
					'.single-woo_builder .select2-container.select2-container--open .select2-dropdown' => 'background-color: {{VALUE}};',
					'.woocommerce-checkout .select2-container.select2-container--open .select2-dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'dropdown_select_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .select2-container.select2-container--open .select2-selection--single' => 'border-color: {{VALUE}};',
					'.single-woo_builder .select2-container.select2-container--open .select2-dropdown' => 'border-color: {{VALUE}};',
					'.woocommerce-checkout .select2-container.select2-container--open .select2-dropdown' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Dropdown selection selected option.
		$this->add_control(
			'dropdown_selection_selected',
			array(
				'label'     => __( 'Dropdown Selection Selected Option', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Text color.
		$this->add_control(
			'dropdown_selection_selected_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#select2-billing_country-results .select2-results__option[aria-selected="true"]'  => 'color: {{VALUE}};',
					'#select2-shipping_country-results .select2-results__option[aria-selected="true"]' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'dropdown_selection_selected_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#select2-billing_country-results .select2-results__option[aria-selected="true"]'  => 'background-color: {{VALUE}};',
					'#select2-shipping_country-results .select2-results__option[aria-selected="true"]' => 'background-color: {{VALUE}};',
					// For frontend.
					'#select2-billing_country-results .select2-results__option[data-selected="true"]'  => 'background-color: {{VALUE}};',
					'#select2-shipping_country-results .select2-results__option[data-selected="true"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Dropdown selection highlight option.
		$this->add_control(
			'dropdown_selection_highlight',
			array(
				'label'     => __( 'Dropdown Selection Highlight Option', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Text color.
		$this->add_control(
			'dropdown_selection_highlight_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#select2-billing_country-results .select2-results__option.select2-results__option--highlighted'  => 'color: {{VALUE}};',
					'#select2-shipping_country-results .select2-results__option.select2-results__option--highlighted' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'dropdown_selection_highlight_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#select2-billing_country-results .select2-results__option--highlighted'  => 'background-color: {{VALUE}} !important;',
					'#select2-shipping_country-results .select2-results__option--highlighted' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Ship to different address
	 */
	public function ship_to_different_address() {
		$this->start_controls_section(
			'ship_to_different_address',
			array(
				'label' => __( 'Ship To Different Address', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ship_to_different_address_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #ship-to-different-address',
			)
		);

		// Color.
		$this->add_control(
			'ship_to_different_address_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #ship-to-different-address' => 'color: {{VALUE}};',
				),
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'ship_to_different_address_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} #ship-to-different-address' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'ship_to_different_address_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} #ship-to-different-address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		// Get checkout object.
		$wc_cart = WC()->cart;
		if ( ! $wc_cart || $wc_cart->is_empty() ) {
			return;
		}

		// Calc totals.
		$wc_cart->calculate_totals();

		// Get checkout object.
		$checkout = WC()->checkout();

		if ( $checkout->get_checkout_fields() ) {
			do_action( 'woocommerce_checkout_before_customer_details' );
			?>
			<div class="col2-set" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php
			do_action( 'woocommerce_checkout_after_customer_details' );
		}
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Checkout_Form() );
