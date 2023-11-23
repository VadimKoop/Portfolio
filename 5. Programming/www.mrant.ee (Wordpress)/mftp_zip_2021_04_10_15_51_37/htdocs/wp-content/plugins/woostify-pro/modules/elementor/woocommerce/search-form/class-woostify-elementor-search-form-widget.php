<?php
/**
 * Elementor Search Form Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Search form widget.
 */
class Woostify_Elementor_Search_Form_Widget extends Widget_Base {
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
		return 'woostify-search-form';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Search Form', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-site-search';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'search', 'form' );
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Text color.
		$this->add_control(
			'text_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'woostify-pro' ),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-search .search-field' => 'color: {{VALUE}};',
				),
			)
		);

		// Background color.
		$this->add_control(
			'bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-search:not(.category-filter) .search-field, {{WRAPPER}} .woocommerce-product-search.category-filter' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_control(
			'border_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Border Color', 'woostify-pro' ),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-search:not(.category-filter) .search-field, {{WRAPPER}} .woocommerce-product-search.category-filter' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Border radius.
		$this->add_responsive_control(
			'padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-product-search:not(.category-filter) .search-field, {{WRAPPER}} .woocommerce-product-search.category-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Search Icon.
		$this->add_control(
			'search_icon',
			array(
				'label'     => __( 'Search Icon', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Search.
		$this->add_control(
			'search_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-search:after' => 'color: {{VALUE}};',
				),
			)
		);

		// Background Search.
		$this->add_control(
			'search_background',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-product-search button[type="submit"]' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'name'     => 'parent_typo',
				'selector' => '{{WRAPPER}} .woocommerce-product-search:after',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
	}

	/**
	 * Render
	 */
	public function render() {
		?>
		<div class="woostify-search-form-widget site-search">
			<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			<div class="ajax-search-results"></div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Search_Form_Widget() );
