<?php
/**
 * Elementor Cart collaterals Widget
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
class Woostify_Cart_Overview extends Widget_Base {
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
		return 'woostify-cart-overview';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Cart Overview', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'overview', 'store', 'cart' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->table_head();
		$this->table_value();
		$this->checkout_button();
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
					'{{WRAPPER}} .cart_totals .shop_table' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .cart_totals .shop_table tr' => 'border-style: {{VALUE}};',
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
					'{{WRAPPER}} .cart_totals .shop_table' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .cart_totals .shop_table tr' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .cart_totals .shop_table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart_totals .shop_table tr' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Padding.
		$this->add_control(
			'table_value_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart_totals th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .cart_totals td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// Color.
		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_totals th' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .cart_totals th',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Table value
	 */
	public function table_value() {
		$this->start_controls_section(
			'table_value',
			array(
				'label' => __( 'Table Value', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'table_value_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_totals td'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .cart_totals td a' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_value_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .cart_totals td',
			)
		);

		// Price.
		$this->add_control(
			'price_options',
			array(
				'label'     => __( 'Price', 'woostify_pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Price color.
		$this->add_control(
			'table_value_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_totals .shop_table .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_value_price_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .shop_table .woocommerce-Price-amount',
			)
		);

		// Total price.
		$this->add_control(
			'total_price_options',
			array(
				'label'     => __( 'Total Price', 'woostify_pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Price color.
		$this->add_control(
			'table_value_total_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart_totals .shop_table .order-total .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_value_total_price_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .shop_table .order-total .woocommerce-Price-amount',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get to checkout button
	 */
	public function checkout_button() {
		$this->start_controls_section(
			'checkout_button',
			array(
				'label' => __( 'Checkout Button', 'woostify-pro' ),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkout_button_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .checkout-button',
			)
		);

		// Padding.
		$this->add_control(
			'checkout_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_control(
			'checkout_button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'checkout_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'checkout_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'checkout_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .checkout-button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'checkout_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .checkout-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'normal_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .checkout-button',
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'checkout_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'checkout_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .checkout-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'checkout_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .checkout-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'hover_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .checkout-button:hover',
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

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
		if ( isset( $_POST['calc_shipping'] ) ) { // phpcs:ignore
			$cart = new \WC_Shortcode_Cart();
			$cart->calculate_shipping();
			$wc_cart->calculate_totals();
		}

		// Calc totals.
		$wc_cart->calculate_totals();
		?>

		<div class="cart-collaterals">
			<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals
			 */
			do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Cart_Overview() );
