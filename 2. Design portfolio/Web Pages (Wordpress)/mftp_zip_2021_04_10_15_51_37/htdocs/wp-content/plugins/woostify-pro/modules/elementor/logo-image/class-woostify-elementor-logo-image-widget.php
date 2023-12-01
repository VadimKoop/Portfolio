<?php
/**
 * Elementor Logo Image Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Logo Image widget.
 */
class Woostify_Elementor_Logo_Image_Widget extends Widget_Base {
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
		return 'woostify-logo-image';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Logo Image', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-logo';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'logo', 'image', 'brand' );
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
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Alignment', 'woostify-pro' ),
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .woostify-logo-image-widget .site-branding' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Logo Style
	 */
	private function logo_style() {
		$this->start_controls_section(
			'logo_style',
			array(
				'label' => esc_html__( 'Image', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_width',
			array(
				'label'      => __( 'Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 2000,
						'step' => 1,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-logo-image-widget .custom-logo-link img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_max_width',
			array(
				'label'      => __( 'Max Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 2000,
						'step' => 1,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-logo-image-widget .custom-logo-link img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'image_tabs' );

		// Normal.
		$this->start_controls_tab(
			'image_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label' => __( 'Opacity', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-logo-link img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .custom-logo-link img',
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'image_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label' => __( 'Opacity', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 1,
				),
				'range' => array(
					'px' => array(
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-logo-link:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .custom-logo-link:hover img',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label' => __( 'Transition Duration', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-logo-link img' => 'transition-duration: {{SIZE}}s',
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
				'name'     => 'box_style_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .custom-logo-link img',
			)
		);

		// Border Box radius.
		$this->add_responsive_control(
			'box_style_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .custom-logo-link img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name' => 'image_box_shadow',
				'exclude' => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .custom-logo-link img',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() {
		$this->general();
		$this->logo_style();
	}

	/**
	 * Render
	 */
	public function render() {
		?>
		<div class="woostify-logo-image-widget">
			<?php woostify_site_branding(); ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Logo_Image_Widget() );
