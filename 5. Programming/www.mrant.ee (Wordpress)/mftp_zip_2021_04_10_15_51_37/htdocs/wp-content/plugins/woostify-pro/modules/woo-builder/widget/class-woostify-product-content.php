<?php
/**
 * Elementor Product Content Widget
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
class Woostify_Product_Content extends Widget_Base {
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
		return 'woostify-product-content';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Content', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-post-content';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'content', 'store' );
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
					'left'    => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-content-widget' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-content-widget .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .woostify-product-content-widget .woocommerce-product-details__short-description',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		$product_id = \Woostify_Woo_Builder::init()->get_product_id();
		if ( ! $product_id ) {
			return;
		}

		$GLOBALS['post'] = get_post( $product_id ); // phpcs:ignore
		setup_postdata( $GLOBALS['post'] );

		$settings = $this->get_settings_for_display();
		?>
			<div class="woostify-product-content-widget">
				<?php the_content(); ?>
			</div>
		<?php
		wp_reset_postdata();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Product_Content() );
