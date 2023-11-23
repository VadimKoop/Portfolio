<?php
/**
 * Elementor Size guide Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WOOSTIFY_PRO_SIZE_GUIDE' ) ) {
	return;
}

/**
 * Main class
 */
class Woostify_Size_Guide extends Widget_Base {
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
		return 'woostify-size-guide';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Size Guide', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'side guide', 'shop', 'product', 'woocommerce' );
	}

	/**
	 * Scripts
	 */
	public function get_script_depends() {
		return array( 'woostify-size-guide' );
	}

	/**
	 * Style
	 */
	public function get_script_style() {
		return array( 'woostify-size-guide' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
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

		// Alignment.
		$this->add_responsive_control(
			'alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'condition' => array(
					'size_guide!' => '',
				),
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
					'{{WRAPPER}} .woostify-size-guide-table-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Button heading.
		$this->add_control(
			'size_guide_button_heading',
			array(
				'label'     => __( 'Size Guide Button', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'size_guide!' => '',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs(
			'size_guide_button_tabs',
			array(
				'condition' => array(
					'size_guide!' => '',
				),
			)
		);

		// Normal.
		$this->start_controls_tab(
			'size_guide_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'size_guide_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-size-guide-button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'size_guide_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-size-guide-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'size_guide_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'size_guide_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-size-guide-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'size_guide_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-size-guide-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border.
		$this->add_control(
			'size_guide_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-size-guide-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typo',
				'selector'  => '{{WRAPPER}} .woostify-size-guide-button',
				'condition' => array(
					'size_guide!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'label'     => __( 'Border', 'woostify-pro' ),
				'selector'  => '{{WRAPPER}} .woostify-size-guide-button',
				'condition' => array(
					'size_guide!' => '',
				),
			)
		);

		// Padding.
		$this->add_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'size_guide!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-size-guide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'size_guide!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-size-guide-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Render
	 */
	public function render() {
		if ( ! class_exists( 'Woostify_Woo_Builder' ) ) {
			return;
		}

		$size_guide = \Woostify_Size_Guide::get_instance();
		$size_guide->size_guide_content();
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Size_Guide() );
