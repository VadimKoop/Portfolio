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
class Woostify_Checkout_Overview extends Widget_Base {
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
		return 'woostify-checkout-overview';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Checkout Overview', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'checkout', 'order review', 'store', 'cart' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->order_table();
	}

	/**
	 * Order table
	 */
	public function order_table() {
		$this->start_controls_section(
			'order_table',
			array(
				'label' => __( 'Options', 'woostify-pro' ),
			)
		);

		// Border style.
		$this->add_control(
			'order_table_style',
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
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tr' => 'border-bottom-style: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'order_table_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tr' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'order_table_border_width',
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
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tr' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'table_padding',
			array(
				'label'              => __( 'Padding', 'woostify-pro' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'allowed_dimensions' => array( 'top', 'bottom' ),
				'size_units'         => array( 'px', 'em' ),
				'selectors'          => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table th' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}} .woocommerce-checkout-review-order-table td' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} 0;',
				),
			)
		);

		// Table head.
		$this->add_control(
			'table_head',
			array(
				'label'     => __( 'Table Head', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'table_head_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_head_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table thead th',
			)
		);

		// Table content.
		$this->add_control(
			'table_content',
			array(
				'label'     => __( 'Table Content', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'table_conten_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tbody td' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .cart-subtotal' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .cart-discount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .woocommerce-remove-coupon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .woocommerce-shipping-totals' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_content_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tbody td, {{WRAPPER}} .woocommerce-checkout-review-order-table .cart-subtotal, {{WRAPPER}} .woocommerce-checkout-review-order-table .woocommerce-shipping-totals, {{WRAPPER}} .woocommerce-checkout-review-order-table .cart-discount',
			)
		);

		// Table foot.
		$this->add_control(
			'table_foot',
			array(
				'label'     => __( 'Table Foot', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'table_foot_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .order-total' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_foot_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table .order-total .woocommerce-Price-amount, {{WRAPPER}} .woocommerce-checkout-review-order-table .order-total th',
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

		woocommerce_order_review();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Checkout_Overview() );
