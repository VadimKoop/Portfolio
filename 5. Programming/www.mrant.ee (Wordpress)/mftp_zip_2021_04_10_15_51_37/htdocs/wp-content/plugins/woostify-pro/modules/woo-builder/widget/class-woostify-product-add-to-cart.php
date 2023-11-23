<?php
/**
 * Elementor Product Add To Cart Widget
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
class Woostify_Product_Add_To_Cart extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-product' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-product-add-to-cart';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Add To Cart', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'add to cart', 'store', 'cart' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->variations();
		$this->product_quantity();
		$this->add_to_cart_button();
	}

	/**
	 * General
	 */
	protected function general() {
		$this->start_controls_section(
			'start',
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
	 * Variations
	 */
	protected function variations() {
		$this->start_controls_section(
			'variations',
			array(
				'label' => __( 'Product Variations', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'variations_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This group option available only for Product Variable.', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'vr_color',
			array(
				'label'     => __( 'Label Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .variations .label label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'vr_block',
			array(
				'label'        => __( 'Label Block', 'woostify-pro' ),
				'description'  => __( 'Whether to display the label in a separate line.', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'vr_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'vr_block' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .variations-label-block .variations .label label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'vr_typo',
				'selector' => '{{WRAPPER}} .variations .label label',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product quantity
	 */
	protected function product_quantity() {
		$this->start_controls_section(
			'product_quantity',
			array(
				'label' => __( 'Product Quantity', 'woostify-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pq_typo',
				'selector' => '{{WRAPPER}} .quantity .qty',
			)
		);

		$this->add_control(
			'pq_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .qty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pq_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'pq_width',
			array(
				'label'      => __( 'Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .quantity' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pq_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart .quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pq_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart .quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pq_border_style',
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
					'none'   => __( 'None', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .cart .quantity' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pq_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart .quantity' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pq_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .cart .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pq_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .cart .quantity' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pq_minus_plus_button',
			array(
				'label'     => __( 'Increase or decrease button', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pq_crease_button_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .quantity .product-qty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'pq_crease_button_size',
			array(
				'label'      => __( 'Font Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .quantity .product-qty' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add to cart button
	 */
	protected function add_to_cart_button() {
		$this->start_controls_section(
			'add_to_cart_button',
			array(
				'label' => __( 'Add To Cart Button', 'woostify-pro' ),
			)
		);

		$this->add_responsive_control(
			'add_to_cart_button_width',
			array(
				'label'      => __( 'Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .single_add_to_cart_button.button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'add_to_cart_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'add_to_cart_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'add_to_cart_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single_add_to_cart_button.button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'add_to_cart_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single_add_to_cart_button.button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'add_to_cart_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'add_to_cart_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single_add_to_cart_button.button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'add_to_cart_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single_add_to_cart_button.button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'add_to_cart_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .single_add_to_cart_button.button:hover' => 'border-color: {{VALUE}};',
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
				'name'      => 'add_to_cart_button_border',
				'label'     => __( 'Border', 'woostify-pro' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .single_add_to_cart_button.button',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'add_to_cart_button_typo',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button.button',
			)
		);

		$this->add_responsive_control(
			'add_to_cart_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .single_add_to_cart_button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'add_to_cart_button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .single_add_to_cart_button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings   = $this->get_settings_for_display();
		$product_id = \Woostify_Woo_Builder::init()->get_product_id();
		$product    = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			return;
		}

		$is_variable = $product->is_type( 'variable' );

		$GLOBALS['product'] = $product;

		$label_block = ( 'yes' === $settings['vr_block'] && $is_variable ) ? 'variations-label-block' : '';
		?>
		<div class="woostify-wrapper-add-to-cart <?php echo esc_attr( $label_block ); ?>">
			<?php woocommerce_template_single_add_to_cart(); ?>
		</div>
		<?php
		unset( $GLOBALS['product'] );
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Add_To_Cart() );
