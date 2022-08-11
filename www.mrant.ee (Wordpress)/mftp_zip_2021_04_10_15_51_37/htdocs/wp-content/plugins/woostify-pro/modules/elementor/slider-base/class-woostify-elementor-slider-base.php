<?php
/**
 * Elementor Slider Base
 *
 * @package  Woostify Pro
 */

namespace Elementor;

/**
 * Woostify Elementor Slider Base.
 */
abstract class Woostify_Elementor_Slider_Base extends Widget_Base {
	/**
	 * Scripts
	 */
	public function get_script_depends() {
		return [ 'woostify-elementor-widget' ];
	}

	/**
	 * Slider options
	 */
	protected function slider_options() {
		$this->start_controls_section(
			'slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'woostify-pro' ),
			]
		);

		// Columns.
		$this->add_responsive_control(
			'columns',
			[
				'type'           => Controls_Manager::SELECT,
				'label'          => esc_html__( 'Columns', 'woostify-pro' ),
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'options'        => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				],
			]
		);

		// Columns gap.
		$this->add_responsive_control(
			'gap',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Columns Gap', 'woostify-pro' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 15,
				],
			]
		);

		// Navigation.
		$this->add_control(
			'navigation',
			[
				'label'   => __( 'Navigation', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both'   => __( 'Arrows and Dots', 'woostify-pro' ),
					'arrows' => __( 'Arrows', 'woostify-pro' ),
					'dots'   => __( 'Dots', 'woostify-pro' ),
					'none'   => __( 'None', 'woostify-pro' ),
				],
				'separator' => 'before',
			]
		);

		// Slide by.
		$this->add_control(
			'slideby',
			[
				'label'   => __( 'Slide By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					1      => 1,
					2      => 2,
					3      => 3,
					4      => 4,
					5      => 5,
					'page' => __( 'Page', 'woostify-pro' ),
				],
			]
		);

		// Preload.
		$this->add_control(
			'preload',
			array(
				'label'        => __( 'Preload', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Autoplay.
		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Autoplay timeout.
		$this->add_control(
			'timeout',
			[
				'label'     => __( 'Autoplay Timeout (ms)', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 500,
				'max'       => 10000,
				'step'      => 100,
				'default'   => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		// Pause on hover.
		$this->add_control(
			'pause_on_hover',
			[
				'label'        => __( 'Pause On Hover', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Loop.
		$this->add_control(
			'loop',
			[
				'label'        => __( 'Loop', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		// Speed.
		$this->add_control(
			'speed',
			[
				'label'   => __( 'Transition Speed (ms)', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 10,
				'max'     => 5000,
				'step'    => 10,
				'default' => 300,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Arrows
	 */
	protected function arrows() {
		$this->start_controls_section(
			'arrows',
			[
				'label'      => esc_html__( 'Arrows', 'woostify-pro' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'navigation',
							'operator' => '==',
							'value'    => 'both',
						],
						[
							'name'     => 'navigation',
							'operator' => '==',
							'value'    => 'arrows',
						],
					],
				],
			]
		);

		// Arrows size.
		$this->add_responsive_control(
			'arrows_size',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Size', 'woostify-pro' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Arrows border radius.
		$this->add_responsive_control(
			'arrows_border',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-controls [data-controls]' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Arrows position.
		$this->add_responsive_control(
			'arrows_position',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Horizontal Position', 'woostify-pro' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-controls [data-controls="prev"]' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tns-controls [data-controls="next"]' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Tab Arrows Style.
		$this->start_controls_tabs(
			'arrows_style_tabs',
			[
				'separator' => 'before',
			]
		);
			$this->start_controls_tab(
				'arrows_style_normal_tab',
				[
					'label' => __( 'Normal', 'woostify-pro' ),
				]
			);

				// Arrows background color.
				$this->add_control(
					'arrows_bg_color',
					[
						'type'    => Controls_Manager::COLOR,
						'label'   => esc_html__( 'Background Color', 'woostify-pro' ),
						'default' => '#ffffff',
						'scheme'  => [
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .tns-controls [data-controls]' => 'background-color: {{VALUE}}',
						],
					]
				);

				// Arrows color.
				$this->add_control(
					'arrows_color',
					[
						'type'    => Controls_Manager::COLOR,
						'label'   => esc_html__( 'Color', 'woostify-pro' ),
						'default' => '#333333',
						'scheme'  => [
							'type'  => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'selectors' => [
							'{{WRAPPER}} .tns-controls [data-controls]' => 'color: {{VALUE}}',
						],
					]
				);

			$this->end_controls_tab();

			// Tab background start.
			$this->start_controls_tab(
				'arrows_style_hover_tab',
				[
					'label' => __( 'Hover', 'woostify-pro' ),
				]
			);

				// Arrows hover background color.
				$this->add_control(
					'arrows_bg_color_hover',
					[
						'type'    => Controls_Manager::COLOR,
						'label'   => esc_html__( 'Background Color', 'woostify-pro' ),
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .tns-controls [data-controls]:hover' => 'background-color: {{VALUE}}',
						],
						'separator' => 'before',
					]
				);

				// Arrows hover color.
				$this->add_control(
					'arrows_color_hover',
					[
						'type'      => Controls_Manager::COLOR,
						'label'     => esc_html__( 'Color', 'woostify-pro' ),
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .tns-controls [data-controls]:hover' => 'color: {{VALUE}}',
						],
					]
				);

			$this->end_controls_tab();
		$this->end_controls_tabs();

		// Hide on Tablet.
		$this->add_control(
			'arrows_on_tablet',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide on Tablet', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			]
		);

		// Hide on Mobile.
		$this->add_control(
			'arrows_on_mobile',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide on Mobile', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Dots
	 */
	protected function dots() {
		$this->start_controls_section(
			'dots',
			[
				'label'      => esc_html__( 'Dots', 'woostify-pro' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'navigation',
							'operator' => '==',
							'value'    => 'both',
						],
						[
							'name'     => 'navigation',
							'operator' => '==',
							'value'    => 'dots',
						],
					],
				],
			]
		);

		// Dots size.
		$this->add_responsive_control(
			'dots_size',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Size', 'woostify-pro' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-nav [data-nav]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Dots border radius.
		$this->add_responsive_control(
			'dots_border',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-nav [data-nav]' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Dots position.
		$this->add_responsive_control(
			'dots_position',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Vertical Position', 'woostify-pro' ),
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-nav' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Dots background color.
		$this->add_control(
			'dots_bg_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Background Color', 'woostify-pro' ),
				'default' => '#f5f5f5',
				'scheme'  => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-nav [data-nav]' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		// Dot current background color.
		$this->add_control(
			'dots_color',
			[
				'type'    => Controls_Manager::COLOR,
				'label'   => esc_html__( 'Active Dot', 'woostify-pro' ),
				'default' => '#333333',
				'scheme'  => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .tns-nav [data-nav].tns-nav-active' => 'background-color: {{VALUE}}',
				],
			]
		);

		// Hide on Tablet.
		$this->add_control(
			'dots_on_tablet',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide on Tablet', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			]
		);

		// Hide on Mobile.
		$this->add_control(
			'dots_on_mobile',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide on Mobile', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			]
		);

		// Dots alignment.
		$this->add_responsive_control(
			'dots_alignment',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Alignment', 'woostify-pro' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'        => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => [
					'{{WRAPPER}} .tns-nav' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get Slider options
	 */
	protected function get_slider_options() {
		$settings = $this->get_settings_for_display();

		// Arrows.
		$arrows        = ( 'both' == $settings['navigation'] || 'arrows' == $settings['navigation'] ) ? true : false;
		$arrows_tablet = ( ( $arrows && 'yes' == $settings['arrows_on_tablet'] ) || ! $arrows ) ? false : true;
		$arrows_mobile = ( ( $arrows && 'yes' == $settings['arrows_on_mobile'] ) || ! $arrows ) ? false : true;

		// Dots.
		$dots        = ( 'both' == $settings['navigation'] || 'dots' == $settings['navigation'] ) ? true : false;
		$dots_tablet = ( ( $dots && 'yes' == $settings['dots_on_tablet'] ) || ! $dots ) ? false : true;
		$dots_mobile = ( ( $dots && 'yes' == $settings['dots_on_mobile'] ) || ! $dots ) ? false : true;

		// For Mobile First.
		$options  = [
			'items'              => absint( $settings['columns_mobile'] ),
			'autoplay'           => 'yes' == $settings['autoplay'] ? true : false,
			'autoplayTimeout'    => absint( $settings['timeout'] ),
			'autoplayHoverPause' => 'yes' == $settings['pause_on_hover'] ? true : false,
			'controls'           => $arrows_mobile,
			'nav'                => $dots_mobile,
			'speed'              => absint( $settings['speed'] ),
			'loop'               => 'yes' == $settings['loop'] ? true : false,
			'gutter'             => absint( $settings['gap_mobile']['size'] ),
			'slideBy'            => $settings['slideby'],
			'responsive'         => [
				// Tablet option.
				768 => [
					'items'    => absint( $settings['columns_tablet'] ),
					'gutter'   => absint( $settings['gap_tablet']['size'] ),
					'controls' => $arrows_tablet,
					'nav'      => $dots_tablet,
				],
				// Desktop option.
				992 => [
					'items'     => absint( $settings['columns'] ),
					'gutter'    => absint( $settings['gap']['size'] ),
					'controls'  => $arrows,
					'nav'       => $dots,
				],
			],
		];

		return json_encode( $options );
	}
}
