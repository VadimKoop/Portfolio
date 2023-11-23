<?php
/**
 * Elementor Cart form Widget
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
class Woostify_Cart_Form extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-cart-page' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-cart-form';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Cart Form', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'form', 'store', 'cart' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->table_head();
		$this->cart_item();
		$this->product_name();
		$this->product_price();
		$this->product_quantity();
		$this->table_foot();
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

		// Table border.
		$this->add_control(
			'table_border',
			array(
				'label'     => __( 'Table Border', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Border style.
		$this->add_control(
			'table_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'woostify-pro' ),
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'table_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'table_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Table head
	 */
	public function table_head() {
		$this->start_controls_section(
			'table_head',
			array(
				'label' => __( 'Table Head', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => __( 'Show Table Head', 'woostify-pro' ),
				'description'  => __( 'This option available only for desktop', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		// Color.
		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} thead th, {{WRAPPER}} tbody td:before' => 'color: {{VALUE}};',
				),
			)
		);

		// Background color.
		$this->add_control(
			'heading_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} thead th' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} thead th',
			)
		);

		// Padding.
		$this->add_control(
			'heading_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Cart item
	 */
	public function cart_item() {
		$this->start_controls_section(
			'cart_item',
			array(
				'label' => __( 'Cart Item', 'woostify-pro' ),
			)
		);

		// Background color.
		$this->add_control(
			'cart_item_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_item td' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'cart_item_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents .cart_item td' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'cart_item_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents .cart_item td' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'cart_item_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents .cart_item td' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'cart_item_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents .cart_item td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product name
	 */
	public function product_name() {
		$this->start_controls_section(
			'product_name',
			array(
				'label' => __( 'Product Name', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_name_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-name a' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover Color.
		$this->add_control(
			'product_name_hover_color',
			array(
				'label'     => __( 'Hover Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-name a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} tbody .product-name a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product price
	 */
	public function product_price() {
		$this->start_controls_section(
			'price',
			array(
				'label' => __( 'Product Price', 'woostify-pro' ),
			)
		);

		// Single price.
		$this->add_control(
			'single_price',
			array(
				'label' => __( 'Single Price', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Color.
		$this->add_control(
			'single_price_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-price' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'single_price_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} tbody .product-price',
			)
		);

		// Total price.
		$this->add_control(
			'subtotal_price',
			array(
				'label'     => __( 'Subtotal Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'subtotal_price_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-subtotal' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtotal_price_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} tbody .product-subtotal',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product quantity
	 */
	public function product_quantity() {
		$this->start_controls_section(
			'quantity',
			array(
				'label' => __( 'Product Quantity', 'woostify-pro' ),
			)
		);

		// Min-width.
		$this->add_control(
			'quantity_min_width',
			array(
				'label'      => __( 'Min Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 100,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents tbody .quantity' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Color.
		$this->add_control(
			'quantity_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-quantity .qty'                   => 'color: {{VALUE}};',
					'{{WRAPPER}} tbody .product-quantity .quantity .product-qty' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'quantity_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} tbody .product-quantity .qty',
			)
		);

		// Border style.
		$this->add_control(
			'quantity_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} tbody .product-quantity .quantity' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'quantity_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} tbody .product-quantity .quantity' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'quantity_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} tbody .product-quantity .quantity' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Border radius.
		$this->add_control(
			'quantity_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} tbody .product-quantity .quantity' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Table foot
	 */
	public function table_foot() {
		$this->start_controls_section(
			'table_foot',
			array(
				'label' => __( 'Table Foot', 'woostify-pro' ),
			)
		);

		// Background color.
		$this->add_control(
			'table_foot_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'table_foot_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'dashed',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .actions' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'table_foot_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Border width.
		$this->add_control(
			'table_foot_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .actions' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Coupon.
		$this->add_control(
			'coupon',
			array(
				'label'     => __( 'Coupon', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Coupon background placeholder.
		$this->add_control(
			'coupon_bg_placeholder',
			array(
				'label'        => __( 'Show Background Placeholder', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Coupon border style.
		$this->add_control(
			'coupon_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
					'none'   => __( 'None', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} [name="coupon_code"]'  => 'border-style: {{VALUE}};',
				),
			)
		);

		// Coupon border color.
		$this->add_control(
			'coupon_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="coupon_code"]'  => 'border-color: {{VALUE}};',
				),
			)
		);

		// Coupon border width.
		$this->add_control(
			'coupon_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} [name="coupon_code"]'  => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} [name="apply_coupon"]' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Coupon text color.
		$this->add_control(
			'coupon_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} [name="coupon_code"]' => 'color: {{VALUE}};',
				),
			)
		);

		// Coupon button text color.
		$this->add_control(
			'coupon_button_text_color',
			array(
				'label'     => __( 'Button Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]' => 'color: {{VALUE}};',
				),
			)
		);

		// Update cart.
		$this->add_control(
			'update_cart',
			array(
				'label'     => __( 'Update Cart Button', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'update_cart_tabs' );

		// Normal.
		$this->start_controls_tab(
			'update_cart_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'update_cart_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'update_cart_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// DISABLED.
		$this->start_controls_tab(
			'update_cart_disabled',
			array(
				'label' => __( 'Disabled', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'update_cart_disabled_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]:disabled' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'update_cart_disabled_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]:disabled' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Filter.
		$this->add_control(
			'update_cart_disabled_filter',
			array(
				'label'      => __( 'Filter Grayscale', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .actions [name="update_cart"]:disabled' => 'filter: grayscale({{SIZE}}{{UNIT}});',
				),
			)
		);

		// END DISABLED.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'update_cart_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'update_cart_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'update_cart_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .actions [name="update_cart"]:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button height.
		$this->add_responsive_control(
			'table_foot_height',
			array(
				'label'      => esc_html__( 'Button Height', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-cart-form__contents [name="update_cart"]' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .cart.wishlist_table [name="update_cart"]' => 'height: {{SIZE}}{{UNIT}};',
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

		$settings = $this->get_settings_for_display();
		?>
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents <?php echo esc_attr( 'yes' === $settings['show_heading'] ? 'show-heading' : '' ); ?>">
				<thead>
					<tr>
						<th class="product-remove">&nbsp;</th>
						<th class="product-thumbnail">&nbsp;</th>
						<th class="product-name"><?php esc_html_e( 'Product', 'woostify-pro' ); ?></th>
						<th class="product-price"><?php esc_html_e( 'Price', 'woostify-pro' ); ?></th>
						<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woostify-pro' ); ?></th>
						<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woostify-pro' ); ?></th>
					</tr>
				</thead>

				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					$cart_items = $wc_cart ? $wc_cart->get_cart() : array();
					if ( empty( $cart_items ) ) {
						?>
						<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woostify-pro' ); ?></p>
						<?php
					} else {
						foreach ( $cart_items as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

									<td class="product-remove">
										<?php
											echo apply_filters( // phpcs:ignore
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'woostify-pro' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												),
												$cart_item_key
											);
										?>
									</td>

									<td class="product-thumbnail">
									<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail; // phpcs:ignore
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore
									}
									?>
									</td>

									<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woostify-pro' ); ?>">
									<?php
									if ( ! $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore

									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woostify-pro' ) . '</p>', $product_id ) );
									}
									?>
									</td>

									<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woostify-pro' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore
										?>
									</td>

									<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woostify-pro' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore
									?>
									</td>

									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woostify-pro' ); ?>">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore
										?>
									</td>
								</tr>
								<?php
							}
						}
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr>
						<td colspan="6" class="actions">

							<?php if ( wc_coupons_enabled() ) { ?>
								<div class="coupon <?php echo 'yes' !== $settings['coupon_bg_placeholder'] ? 'no-backround-image' : ''; ?>">
									<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woostify-pro' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woostify-pro' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woostify-pro' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woostify-pro' ); ?></button>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div>
							<?php } ?>

							<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woostify-pro' ); ?>"><?php esc_html_e( 'Update cart', 'woostify-pro' ); ?></button>

							<?php do_action( 'woocommerce_cart_actions' ); ?>

							<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Cart_Form() );
