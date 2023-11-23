<?php
/**
 * Elementor Checkout coupon form Widget
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
class Woostify_Checkout_Coupon_Form extends Widget_Base {
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
		return 'woostify-checkout-coupon-form';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Coupon Form', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'checkout', 'form', 'store', 'coupon' );
	}

	/**
	 * Scripts
	 */
	public function get_script_depends() {
		return array( 'woostify-coupon-form-widget' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->toggle_form();
		$this->coupon_form();
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
	 * Toggle form
	 */
	public function toggle_form() {
		$this->start_controls_section(
			'toggle',
			array(
				'label' => __( 'Toggle Coupon Form', 'woostify-pro' ),
			)
		);

		$this->add_responsive_control(
			'toggle_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info:not(.showcoupon)' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Button Toggle Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info .showcoupon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Button Toggle Hover Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info .showcoupon:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
			)
		);

		$this->add_control(
			'toggle_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'toggle_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toggle_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Coupon form
	 */
	public function coupon_form() {
		$this->start_controls_section(
			'form',
			array(
				'label' => __( 'Coupon Form', 'woostify-pro' ),
			)
		);

		// General.
		$this->add_control(
			'form_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon',
			)
		);

		// Coupon code.
		$this->add_control(
			'coupon_code_heading',
			array(
				'label'     => __( 'Coupon Code', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'coupon_code_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-form-coupon [name="coupon_code"]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'coupon_code_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon [name="coupon_code"]',
			)
		);

		$this->add_control(
			'coupon_code_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon [name="coupon_code"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_code_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon [name="coupon_code"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'coupon_code_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-form-coupon [name="coupon_code"]',
			)
		);

		// Update coupon button.
		$this->add_control(
			'check_coupon_heading',
			array(
				'label'     => __( 'Apply Coupon Code', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'apply_coupon_tabs' );

		// Normal.
		$this->start_controls_tab(
			'apply_coupon_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'apply_coupon_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'apply_coupon_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'apply_coupon_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'apply_coupon_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'apply_coupon_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border.
		$this->add_control(
			'apply_coupon_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} [name="apply_coupon"]:hover' => 'border-color: {{VALUE}};',
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
				'name'     => 'apply_coupon_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} [name="apply_coupon"]',
			)
		);

		$this->add_control(
			'apply_coupon_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon [name="apply_coupon"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'apply_coupon_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-form-coupon [name="apply_coupon"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		if ( ! wc_coupons_enabled() ) {
			return;
		}

		// Get checkout object.
		$wc_cart = WC()->cart;
		if ( ! $wc_cart || $wc_cart->is_empty() ) {
			return;
		}

		// Calc totals.
		$wc_cart->calculate_totals();
		?>

		<div class="woocommerce woostify-coupon-modified">
			<div class="woocommerce-form-coupon-toggle">
				<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woostify-pro' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'woostify-pro' ) . '</a>' ), 'notice' ); ?>
			</div>

			<div class="checkout_coupon woocommerce-form-coupon" method="post" style="display: none;">
				<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woostify-pro' ); ?></p>

				<p class="form-row form-row-first">
					<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woostify-pro' ); ?>" id="coupon_code" value="" />
				</p>

				<p class="form-row form-row-last">
					<button class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woostify-pro' ); ?>"><?php esc_html_e( 'Apply coupon', 'woostify-pro' ); ?></button>
				</p>

				<div class="clear"></div>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Checkout_Coupon_Form() );
