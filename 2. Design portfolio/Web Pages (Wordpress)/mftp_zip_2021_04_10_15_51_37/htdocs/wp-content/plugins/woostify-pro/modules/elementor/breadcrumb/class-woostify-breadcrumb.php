<?php
/**
 * Elementor Breadcrumb Widget
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
class Woostify_Breadcrumb extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-breadcrumb';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Breadcrumb', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'meta', 'store', 'breadcrumb' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
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

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'woostify-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-breadcrumb' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Text color.
		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-bread'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .item-bread a' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover text color.
		$this->add_control(
			'hover_text_color',
			array(
				'label'     => __( 'Hover Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-bread a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Last bread color.
		$this->add_control(
			'last_item_color',
			array(
				'label'     => __( 'Last Item Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-bread:last-of-type' => 'color: {{VALUE}};',
				),
			)
		);

		// Delimiter color.
		$this->add_control(
			'delimiter_color',
			array(
				'label'     => __( 'Delimiter Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .item-bread.delimiter' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woostify-breadcrumb.woostify-theme-breadcrumb .item-bread:after' => 'color: {{VALUE}};', // For native theme breadcrumb.
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .item-bread',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		woostify_breadcrumb();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Breadcrumb() );
