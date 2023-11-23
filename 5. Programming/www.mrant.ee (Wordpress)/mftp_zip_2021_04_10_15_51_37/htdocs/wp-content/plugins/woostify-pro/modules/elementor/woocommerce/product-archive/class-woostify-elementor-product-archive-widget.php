<?php
/**
 * Elementor Product Archive Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product archive widget.
 */
class Woostify_Elementor_Product_Archive_Widget extends Widget_Base {
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
		return 'woostify-product-archive';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Product Archive', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Controls
	 */
	protected function _register_controls() {
		$this->section_general();
	}

	/**
	 * General
	 */
	private function section_general() {
		$this->start_controls_section(
			'product_general',
			array(
				'label' => esc_html__( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Product Archive', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'        => __( 'Show product counts', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'none',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'archive_title',
			array(
				'label' => __( 'Title', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color.
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-archive .product-archive-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .woostify-product-archive .product-archive-title',
			)
		);

		// Title Spacing.
		$this->add_responsive_control(
			'title_spacing',
			array(
				'label' => __( 'Spacing', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-archive .product-archive-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'archive_list',
			array(
				'label' => __( 'Archive', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'archive_typo',
				'selector' => '{{WRAPPER}} .product-archive-list li a',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'archive_style_tabs' );

		// Normal.
		$this->start_controls_tab(
			'archive_style_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'archive_style_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-archive-list li a' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'archive_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'archive_style_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-archive-list li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'archive_count',
			array(
				'label' => __( 'Counts', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'    => array(
					'count' => 'yes',
				),
			)
		);

		// Color.
		$this->add_control(
			'count_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-archive-list li' => 'color: {{VALUE}};',
				),
				'condition'    => array(
					'count' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typo',
				'selector' => '{{WRAPPER}} .product-archive-list li',
				'condition'    => array(
					'count' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title    = $settings['title'];
		$count    = $settings['count'];

		if ( 'yes' == $count ) {
			$count = 'on';
		}

		$product_archive_query = array(
			'post_type'       => 'product',
			'type'            => 'monthly',
			'echo'            => 0,
			'show_post_count' => $count,
		);

		?>
			<div class="woostify-product-archive">
				<h3 class="product-archive-title"><?php echo esc_html( $title ); ?></h3>
			</div>
			<ul class="product-archive-list">
				<?php echo wp_get_archives( $product_archive_query ); ?>
			</ul>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Archive_Widget() );
