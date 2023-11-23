<?php
/**
 * Elementor Product Images Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor product images widget.
 */
class Woostify_Elementor_Product_Images_Widget extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-product', 'woocommerce-elements-single' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-product-images';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Product Images', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-product-images';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'store', 'image', 'product', 'gallery', 'lightbox' );
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'Arrows', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'woostify_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'To change other gallery layout, go to Customize -> WooCommerce -> Product Single -> Product Image', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		// Arrows border radius.
		$this->add_control(
			'arrows_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-controls [data-controls]' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Tab Arrows Style.
		$this->start_controls_tabs(
			'arrows_style_tabs',
			array(
				'separator' => 'before',
			)
		);
			$this->start_controls_tab(
				'arrows_style_normal_tab',
				array(
					'label' => __( 'Normal', 'woostify-pro' ),
				)
			);

				// Arrows background color.
				$this->add_control(
					'arrows_bg_color',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .tns-controls [data-controls]' => 'background-color: {{VALUE}}',
						),
					)
				);

				// Arrows color.
				$this->add_control(
					'arrows_color',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => esc_html__( 'Color', 'woostify-pro' ),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .tns-controls [data-controls]' => 'color: {{VALUE}}',
						),
					)
				);

			$this->end_controls_tab();

			// Tab background start.
			$this->start_controls_tab(
				'arrows_style_hover_tab',
				array(
					'label' => __( 'Hover', 'woostify-pro' ),
				)
			);

				// Arrows hover background color.
				$this->add_control(
					'arrows_bg_color_hover',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .tns-controls [data-controls]:hover' => 'background-color: {{VALUE}}',
						),
						'separator' => 'before',
					)
				);

				// Arrows hover color.
				$this->add_control(
					'arrows_color_hover',
					array(
						'type'      => Controls_Manager::COLOR,
						'label'     => esc_html__( 'Color', 'woostify-pro' ),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .tns-controls [data-controls]:hover' => 'color: {{VALUE}}',
						),
					)
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Main carousel
	 */
	public function product_images() {
		$this->start_controls_section(
			'product_images',
			array(
				'label' => __( 'Product Images', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Arrows size.
		$this->add_control(
			'arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-images .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Arrows icon size.
		$this->add_control(
			'arrows_icon_size',
			array(
				'label'      => __( 'Arrows Icon Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-images .tns-controls [data-controls]:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_image_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #product-images .image-item',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Thumb carousel
	 */
	public function product_thumbnails() {
		$this->start_controls_section(
			'product_thumbnails',
			array(
				'label' => __( 'Product Thumbnails', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Arrows thumbnail size.
		$this->add_control(
			'arrows_thumb_size',
			array(
				'label'      => __( 'Arrows Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-thumbnail-images .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Arrows thumbnail icon size.
		$this->add_control(
			'arrows_thum_icon_size',
			array(
				'label'      => __( 'Arrows Icon Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-thumbnail-images .tns-controls [data-controls]:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Active border color.
		$this->add_control(
			'thumb_active_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Active Border Color', 'woostify-pro' ),
				'default'   => '',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .product-thumbnail-images .tns-nav-active img' => 'border: 1px solid {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->product_images();
		$this->product_thumbnails();
	}

	/**
	 * Render
	 */
	public function render() {
		$product_id = woostify_get_product_id();
		$product    = wc_get_product( $product_id );

		if ( empty( $product ) ) {
			return;
		}

		$GLOBALS['product'] = $product;
		?>
		<div class="woostify-product-images-widget">
			<?php
				woostify_single_product_gallery_open();
				woostify_single_product_gallery_image_slide();
				woostify_single_product_gallery_thumb_slide();
				woostify_single_product_gallery_dependency();
				woostify_single_product_gallery_close();
			?>
		</div>
		<?php
		unset( $GLOBALS['product'] );
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Images_Widget() );
