<?php
/**
 * Elementor Checkout order review Widget
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
class Woostify_Checkout_Payment extends Widget_Base {
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
		return 'woostify-checkout-payment';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Checkout Payment', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'checkout', 'payment', 'store', 'cart' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->payment_method();
		$this->privacy_policy();
		$this->terms_conditions();
		$this->order_button();
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
	 * Payment meoth
	 */
	public function payment_method() {
		$this->start_controls_section(
			'payment_method',
			array(
				'label' => __( 'Payment Methods', 'woostify-pro' ),
			)
		);

		// Heading.
		$this->add_control(
			'payment_heading',
			array(
				'label' => __( 'Heading', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Color.
		$this->add_control(
			'payment_heading_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wc_payment_method label' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_heading_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .wc_payment_method label',
			)
		);

		// Description.
		$this->add_control(
			'payment_description',
			array(
				'label'     => __( 'Description', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'payment_description_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wc_payment_method .payment_box' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wc_payment_methods .woocommerce-notice' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'payment_description_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wc_payment_method .payment_box' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wc_payment_methods .woocommerce-notice' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wc_payment_method .payment_box:before' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'payment_description_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .wc_payment_method .payment_box',
			)
		);

		// Margin.
		$this->add_control(
			'payment_method_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_methods' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Privacy policy
	 */
	public function privacy_policy() {
		$this->start_controls_section(
			'privacy_policy',
			array(
				'label' => __( 'Privacy Policy', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'privacy_policy_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-privacy-policy-text a' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'privacy_policy_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-privacy-policy-text, {{WRAPPER}} .woocommerce-privacy-policy-text a',
			)
		);

		// Margin.
		$this->add_control(
			'privacy_policy_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper .woocommerce-privacy-policy-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Privacy policy
	 */
	public function terms_conditions() {
		$this->start_controls_section(
			'terms_conditions',
			array(
				'label' => __( 'Terms and Conditions', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'terms_conditions_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text a' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'terms_conditions_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text, {{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text a',
			)
		);

		// Margin.
		$this->add_control(
			'terms_conditions_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper .validate-required' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Order button
	 */
	public function order_button() {
		$this->start_controls_section(
			'order_button',
			array(
				'label' => __( 'Order Button', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_button_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		// Padding.
		$this->add_control(
			'order_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_control(
			'order_button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #place_order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'order_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'order_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'order_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'order_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'order_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'order_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'order_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'order_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'normal_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		$wc_cart = WC()->cart;
		if ( ! $wc_cart || $wc_cart->is_empty() ) {
			return;
		}

		// Calc totals.
		$wc_cart->calculate_totals();

		woocommerce_checkout_payment();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Checkout_Payment() );
