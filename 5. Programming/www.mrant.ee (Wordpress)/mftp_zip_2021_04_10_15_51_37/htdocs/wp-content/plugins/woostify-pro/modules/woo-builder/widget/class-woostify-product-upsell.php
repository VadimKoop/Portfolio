<?php
/**
 * Elementor Product Upsell Widget
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
class Woostify_Product_Upsell extends Widget_Base {
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
		return 'woostify-product-upsell';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Upsell', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-upsell';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'upsell', 'store' );
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

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 6,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Date', 'woostify-pro' ),
					'title'      => __( 'Title', 'woostify-pro' ),
					'price'      => __( 'Price', 'woostify-pro' ),
					'popularity' => __( 'Popularity', 'woostify-pro' ),
					'rating'     => __( 'Rating', 'woostify-pro' ),
					'rand'       => __( 'Random', 'woostify-pro' ),
					'menu_order' => __( 'Menu Order', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => __( 'ASC', 'woostify-pro' ),
					'desc' => __( 'DESC', 'woostify-pro' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		$product_id = \Woostify_Woo_Builder::init()->get_product_id();
		$product    = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			return;
		}

		$GLOBALS['product'] = $product;

		$settings = $this->get_settings_for_display();
		$limit    = '-1';
		$columns  = empty( $settings['columns'] ) ? 4 : $settings['columns'];
		$orderby  = empty( $settings['orderby'] ) ? 'rand' : $settings['orderby'];
		$order    = empty( $settings['order'] ) ? 'desc' : $settings['order'];

		woocommerce_upsell_display( $limit, $columns, $orderby, $order );
		unset( $GLOBALS['product'] );
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Upsell() );
