<?php
/**
 * Elementor Product Data Tabs Widget
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
class Woostify_Product_Data_Tabs extends Widget_Base {
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
		return 'woostify-product-data-tabs';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Data Tabs', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'tabs', 'store' );
	}

	/**
	 * Gets the script depends.
	 */
	public function get_script_depends() {
		return array( 'wc-single-product' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->tab_heading();
		$this->tab_content();
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
	 * Tab heading
	 */
	protected function tab_heading() {
		$this->start_controls_section(
			'tab_heading',
			array(
				'label' => __( 'Tab Heading', 'woostify-pro' ),
			)
		);

		$this->add_responsive_control(
			'heading_align',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
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
					'{{WRAPPER}} .woocommerce-tabs .tabs' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li:not(.active) a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'active_color',
			array(
				'label'     => __( 'Active Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs li.active a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .tabs a',
			)
		);

		$this->add_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_block',
			array(
				'label'        => __( 'Heading Block', 'woostify-pro' ),
				'description'  => __( 'The Heading in a separate line. This option available only for mobile devices.', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Tab content
	 */
	protected function tab_content() {
		$this->start_controls_section(
			'tab_content',
			array(
				'label' => __( 'Tab Content', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'tab_content_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_content_typo',
				'selector' => '{{WRAPPER}} .woocommerce-Tabs-panel, {{WRAPPER}} .woocommerce-Tabs-panel p',
			)
		);

		$this->add_control(
			'review_submit_button_options',
			array(
				'label'     => __( 'Review Submit Button', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'submit_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'submit_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'submit_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #commentform input[type="submit"]' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'submit_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #commentform input[type="submit"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'submit_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'submit_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #commentform input[type="submit"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'submit_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #commentform input[type="submit"]:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'submit_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #commentform input[type="submit"]:hover' => 'border-color: {{VALUE}};',
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
				'name'      => 'review_submit_button_border',
				'label'     => __( 'Border', 'woostify-pro' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} #commentform input[type="submit"]',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'review_submit_button_typo',
				'selector' => '{{WRAPPER}} #commentform input[type="submit"]',
			)
		);

		$this->add_control(
			'review_submit_button_align',
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
					'{{WRAPPER}} .form-submit' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'review_submit_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #commentform input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'review_submit_button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #commentform input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$product_id = \Woostify_Woo_Builder::init()->get_product_id();
		$product    = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			return;
		}
		?>
		<div class="woostify-product-data-tabs <?php echo esc_attr( 'yes' === $settings['heading_block'] ? 'with-heading-block' : '' ); ?>">
			<?php
			$GLOBALS['product'] = $product;

			$GLOBALS['post'] = get_post( $product_id ); // phpcs:ignore
			setup_postdata( $GLOBALS['post'] );

			wc_get_template( 'single-product/tabs/tabs.php' );
			wp_reset_postdata();
			unset( $GLOBALS['product'] );
			?>
		</div>
		<?php
		// On render widget from Editor - trigger the init manually.
		if ( woostify_is_elementor_editor() ) {
			?>
			<script>
				jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
			</script>
			<?php
		}
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Data_Tabs() );
