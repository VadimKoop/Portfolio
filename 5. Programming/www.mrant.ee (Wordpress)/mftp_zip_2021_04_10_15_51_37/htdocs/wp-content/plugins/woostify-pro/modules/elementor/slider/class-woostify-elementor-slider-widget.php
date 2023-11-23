<?php
/**
 * Elementor Slider Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class for woostify elementor slider widget.
 */
class Woostify_Elementor_Slider_Widget extends Widget_Base {
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
		return 'woostify-slider';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Slider', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-slides';
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return array( 'woostify-elementor-widget' );
	}

	/**
	 * Add a style.
	 */
	public function get_style_depends() {
		return array( 'animate' );
	}

	/**
	 * Slides item
	 */
	private function section_slides() {
		$this->start_controls_section(
			'slides_section',
			array(
				'label' => esc_html__( 'Slides', 'woostify-pro' ),
			)
		);

		// Slides.
		$slides = new Repeater();

		// START TAB SLIDES.
		$slides->start_controls_tabs( 'tab_slides' );

		// Tab title start.
		$slides->start_controls_tab(
			'tab_slide_content',
			array(
				'label' => __( 'Content', 'woostify-pro' ),
			)
		);

		// Title.
		$slides->add_control(
			'title',
			array(
				'label'       => __( 'Title & Description', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Slide Heading', 'woostify-pro' ),
				'label_block' => true,
			)
		);

		// Description.
		$slides->add_control(
			'description',
			array(
				'type'       => Controls_Manager::TEXTAREA,
				'show_label' => false,
				'default'    => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'woostify-pro' ),
			)
		);

		// HR Divider.
		$slides->add_control(
			'button_divider',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		// Button 1.
		$slides->add_control(
			'btn1_text',
			array(
				'label'       => __( 'Button 1 Label & Link', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Click here', 'woostify-pro' ),
				'label_block' => true,
			)
		);
		$slides->add_control(
			'btn1_link',
			array(
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'woostify-pro' ),
				'show_label'  => false,
			)
		);

		// Button 2.
		$slides->add_control(
			'btn2_text',
			array(
				'label'       => __( 'Button 2 Label & Link', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Click here', 'woostify-pro' ),
				'label_block' => true,
			)
		);
		$slides->add_control(
			'btn2_link',
			array(
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'woostify-pro' ),
				'show_label'  => false,
			)
		);

		// Tab title end.
		$slides->end_controls_tab();

		// Tab background start.
		$slides->start_controls_tab(
			'tab_slide_background',
			array(
				'label' => __( 'Background', 'woostify-pro' ),
			)
		);

		// Background.
		$slides->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => __( 'Background', 'woostify-pro' ),
				'types'    => array( 'classic' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			)
		);

		// Tab background end.
		$slides->end_controls_tab();

		// Tab style start.
		$slides->start_controls_tab(
			'tab_slide_style',
			array(
				'label' => __( 'Style', 'woostify-pro' ),
			)
		);

		// Custom.
		$slides->add_control(
			'custom',
			array(
				'label'        => __( 'Custom', 'woostify-pro' ),
				'description'  => __( 'Set custom style that will only affect this specific slide.', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		// Content animation.
		$slides->add_control(
			'custom_content_animation',
			array(
				'label'     => __( 'Content animation', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => $this->custom_animation(),
				'condition' => array(
					'custom' => 'yes',
				),
			)
		);

		// Content width.
		$slides->add_responsive_control(
			'custom_content_width',
			array(
				'label'      => __( 'Content Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1170,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .woostify-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'custom' => 'yes',
				),
			)
		);

		// Horizontal Position.
		$slides->add_responsive_control(
			'custom_horizontal_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Horizontal Position', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .woostify-slide-container' => 'justify-content: {{VALUE}};',
				),
				'condition'   => array(
					'custom' => 'yes',
				),
				'separator'   => 'before',
			)
		);

		// Vertical Position.
		$slides->add_responsive_control(
			'custom_vertical_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Vertical Position', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .woostify-slide-inner' => 'align-items: {{VALUE}};',
				),
				'condition'   => array(
					'custom' => 'yes',
				),
			)
		);

		// Text Align.
		$slides->add_responsive_control(
			'custom_text_align',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Text Align', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .woostify-slide-container' => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'custom' => 'yes',
				),
			)
		);

		// Tab style end.
		$slides->end_controls_tab();

		// END TAB SLIDES.
		$slides->end_controls_tabs();

		// SLIDES.
		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'woostify-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $slides->get_controls(),
				'default'     => array(
					array(
						'background_color'      => '#ff0000',
						'background_background' => 'classic',
						'title'                 => __( 'Slide 1 Heading', 'woostify-pro' ),
						'description'           => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'woostify-pro' ),
						'btn1_text'             => __( 'Click Here', 'woostify-pro' ),
						'btn1_link'             => array(
							'url' => '#',
						),
					),
					array(
						'background_color'      => '#000de5',
						'background_background' => 'classic',
						'title'                 => __( 'Slide 2 Heading', 'woostify-pro' ),
						'description'           => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'woostify-pro' ),
						'btn1_text'             => __( 'Click Here', 'woostify-pro' ),
						'btn1_link'             => array(
							'url' => '#',
						),
						'btn2_text'             => __( 'Click Here', 'woostify-pro' ),
						'btn2_link'             => array(
							'url' => '#',
						),
					),
					array(
						'title'                 => __( 'Slide 3 Heading', 'woostify-pro' ),
						'description'           => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'woostify-pro' ),
						'btn1_text'             => __( 'Click Here', 'woostify-pro' ),
						'btn1_link'             => __( 'Click Here', 'woostify-pro' ),
						'background_color'      => '#000000',
						'background_background' => 'classic',
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Animation
	 */
	private function animation() {
		$animation = array(
			'pulse'        => __( 'Pulse', 'woostify-pro' ),
			'rubberBand'   => __( 'Rubber Band', 'woostify-pro' ),
			'shake'        => __( 'Shake', 'woostify-pro' ),
			'swing'        => __( 'Swing', 'woostify-pro' ),
			'tada'         => __( 'Tada', 'woostify-pro' ),
			'wobble'       => __( 'Wobble', 'woostify-pro' ),
			'jello'        => __( 'Jello', 'woostify-pro' ),
			'heartBeat'    => __( 'Heart Beat', 'woostify-pro' ),
			'zoomIn'       => __( 'Zoom In', 'woostify-pro' ),
			'fadeIn'       => __( 'Fade In', 'woostify-pro' ),
			'flipInX'      => __( 'FlipInX', 'woostify-pro' ),
			'flipInY'      => __( 'FlipInY', 'woostify-pro' ),
			'lightSpeedIn' => __( 'Light Speed In', 'woostify-pro' ),
			'fadeInLeft'   => __( 'Fade In Left', 'woostify-pro' ),
			'fadeInRight'  => __( 'Fade In Right', 'woostify-pro' ),
			'fadeInUp'     => __( 'Fade In Up', 'woostify-pro' ),
			'fadeInDown'   => __( 'Fade In Down', 'woostify-pro' ),
		);

		return $animation;
	}

	/**
	 * Specific slide animation
	 */
	private function custom_animation() {
		$animation = $this->animation();
		$animation = array( 'default' => __( 'Default', 'woostify-pro' ) ) + $animation;

		return $animation;
	}

	/**
	 * Slider options
	 */
	private function section_slider_options() {
		$this->start_controls_section(
			'slider_options',
			array(
				'label' => esc_html__( 'Slider Options', 'woostify-pro' ),
			)
		);

		// Navigation.
		$this->add_control(
			'navigation',
			array(
				'label'   => __( 'Navigation', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => array(
					'both'   => __( 'Arrows and Dots', 'woostify-pro' ),
					'arrows' => __( 'Arrows', 'woostify-pro' ),
					'dots'   => __( 'Dots', 'woostify-pro' ),
					'none'   => __( 'None', 'woostify-pro' ),
				),
			)
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
			array(
				'label'        => __( 'Autoplay', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Autoplay timeout.
		$this->add_control(
			'timeout',
			array(
				'label'   => __( 'Autoplay Timeout (ms)', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 500,
				'max'     => 50000,
				'step'    => 100,
				'default' => 5000,
			)
		);

		// Pause on hover.
		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => __( 'Pause On Hover', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Loop.
		$this->add_control(
			'loop',
			array(
				'label'        => __( 'Loop', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Speed.
		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Transition Speed (ms)', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 10,
				'max'     => 5000,
				'step'    => 10,
				'default' => 300,
			)
		);

		// Content animation.
		$this->add_control(
			'content_animation',
			array(
				'label'   => __( 'Content animation', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fadeIn',
				'options' => $this->animation(),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Slides General
	 */
	private function section_slides_style() {
		$this->start_controls_section(
			'slider_style',
			array(
				'label' => __( 'Slides', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Slide height.
		$this->add_responsive_control(
			'slider_height',
			array(
				'label'      => __( 'Slider Height', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Container width.
		$this->add_responsive_control(
			'container_width',
			array(
				'label'      => __( 'Container Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-container' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Content width.
		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => __( 'Content Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Padding.
		$this->add_responsive_control(
			'slide_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		// Horizontal Position.
		$this->add_responsive_control(
			'horizontal_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Horizontal Position', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .woostify-slide-container' => 'justify-content: {{VALUE}};',
				),
			)
		);

		// Vertical Position.
		$this->add_responsive_control(
			'vertical_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Vertical Position', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .woostify-slide-inner' => 'align-items: {{VALUE}};',
				),
			)
		);

		// Text Align.
		$this->add_responsive_control(
			'text_align',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Text Align', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .woostify-slide-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Title style
	 */
	private function section_title_style() {
		$this->start_controls_section(
			'title_style',
			array(
				'label' => __( 'Title', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Title spacing.
		$this->add_control(
			'title_space',
			array(
				'label'      => __( 'Spacing', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Title color.
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-title' => 'color: {{VALUE}};',
				),
			)
		);

		// Title typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woostify-slide-title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Description style
	 */
	private function section_description_style() {
		$this->start_controls_section(
			'description_style',
			array(
				'label' => __( 'Description', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Description spacing.
		$this->add_control(
			'description_space',
			array(
				'label'      => __( 'Spacing', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Description color.
		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-description' => 'color: {{VALUE}};',
				),
			)
		);

		// Description typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woostify-slide-description',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button style
	 */
	private function section_button_style() {
		$this->start_controls_section(
			'button_style',
			array(
				'label' => __( 'Buttons', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Button typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .woostify-slide-button',
			)
		);

		// Button border style.
		$this->add_control(
			'button_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
					'none'   => __( 'None', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button' => 'border-style: {{VALUE}};',
				),
			)
		);

		// Button border width.
		$this->add_control(
			'button_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Button border radius.
		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Button padding.
		$this->add_responsive_control(
			'button_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Button margin.
		$this->add_responsive_control(
			'button_margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-slide-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// HR Divider.
		$this->add_control(
			'button_style_divider',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		// START BUTTON STYLE.
		$this->start_controls_tabs( 'tab_button_style' );

		// Tab button normal start.
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Button color.
		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button' => 'color: {{VALUE}};',
				),
			)
		);

		// Button background color.
		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Button border color.
		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Tab button normal end.
		$this->end_controls_tab();

		// Tab button hover start.
		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Button hover color.
		$this->add_control(
			'button_color_hover',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Button background hover color.
		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Button border hover color.
		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-slide-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Tab button hover end.
		$this->end_controls_tab();

		// END TAB BUTTON STYLE.
		$this->end_controls_section();
	}

	/**
	 * Render slider options
	 */
	private function render_slider_options() {
		$settings = $this->get_settings_for_display();
		$arrows   = ( 'both' === $settings['navigation'] || 'arrows' === $settings['navigation'] ) ? true : false;
		$dots     = ( 'both' === $settings['navigation'] || 'dots' === $settings['navigation'] ) ? true : false;
		$options  = array(
			'items'              => 1,
			'autoplay'           => $settings['autoplay'],
			'autoplayTimeout'    => $settings['timeout'],
			'autoplayHoverPause' => $settings['pause_on_hover'],
			'controls'           => $arrows,
			'nav'                => $dots,
			'speed'              => $settings['speed'],
			'loop'               => $settings['loop'],
		);

		return wp_json_encode( $options );
	}

	/**
	 * Arrows
	 */
	private function section_arrows() {
		$this->start_controls_section(
			'arrows_section',
			array(
				'label'      => esc_html__( 'Arrows', 'woostify-pro' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'navigation',
							'operator' => '===',
							'value'    => 'both',
						),
						array(
							'name'     => 'navigation',
							'operator' => '===',
							'value'    => 'arrows',
						),
					),
				),
			)
		);

		// Arrows size.
		$this->add_control(
			'arrows_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Size', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Arrows border radius.
		$this->add_control(
			'arrows_border',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-controls [data-controls]' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Arrows position.
		$this->add_control(
			'arrows_position',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Position', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-controls [data-controls="prev"]' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tns-controls [data-controls="next"]' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Arrows background color.
		$this->add_control(
			'arrows_bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
				'default'   => '#ffffff',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
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
				'default'   => '#333333',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tns-controls [data-controls]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Dots
	 */
	private function section_dots() {
		$this->start_controls_section(
			'dots_section',
			array(
				'label'      => esc_html__( 'Dots', 'woostify-pro' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'navigation',
							'operator' => '===',
							'value'    => 'both',
						),
						array(
							'name'     => 'navigation',
							'operator' => '===',
							'value'    => 'dots',
						),
					),
				),
			)
		);

		// Dots size.
		$this->add_control(
			'dots_size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Size', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots border radius.
		$this->add_control(
			'dots_border',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots position.
		$this->add_control(
			'dots_position',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Position', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -150,
						'max'  => 150,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .tns-nav' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Dots background color.
		$this->add_control(
			'dots_bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
				'default'   => '#fefefe',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tns-nav [data-nav]' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		// Dot current background color.
		$this->add_control(
			'dots_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Current Dot', 'woostify-pro' ),
				'default'   => '#333333',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .tns-nav [data-nav].tns-nav-active' => 'background-color: {{VALUE}}',
				),
			)
		);

		// Dots alignment.
		$this->add_responsive_control(
			'dots_alignment',
			array(
				'type'           => Controls_Manager::CHOOSE,
				'label'          => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'        => array(
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
				'default'        => 'center',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => array(
					'{{WRAPPER}} .tns-nav' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		// TAB CONTENT.
		$this->section_slides();
		$this->section_slider_options();
		$this->section_arrows();
		$this->section_dots();

		// TAB STYLE.
		$this->section_slides_style();
		$this->section_title_style();
		$this->section_description_style();
		$this->section_button_style();
	}

	/**
	 * Render
	 */
	protected function render() {
		// Settings.
		$settings = $this->get_settings_for_display();
		$slides   = $settings['slides'];
		?>
			<div class="woostify-slider-widget<?php echo esc_attr( 'yes' === $settings['preload'] ? ' tns' : '' ); ?>" id="<?php echo esc_attr( uniqid( 'woositfy-slider-' ) ); ?>" data-tiny-slider='<?php echo wp_kses_post( $this->render_slider_options() ); ?>'>
				<?php
				foreach ( $slides as $k => $v ) {
					$animation = ( 'yes' === $v['custom'] && 'default' !== $v['custom_content_animation'] ) ? $v['custom_content_animation'] : $settings['content_animation'];
					?>
					<div class="woostify-slide tnsi elementor-repeater-item-<?php echo esc_attr( $slides[ $k ]['_id'] ); ?>" data-animate="<?php echo esc_attr( $animation ); ?>">
						<div class="woostify-slide-inner">

							<div class="woostify-slide-container">
								<div class="woostify-slide-content">
									<?php if ( ! empty( $v['title'] ) ) { ?>
										<h2 class="woostify-slide-title"><?php echo esc_html( $v['title'] ); ?></h2>
									<?php } ?>

									<?php if ( ! empty( $v['description'] ) ) { ?>
									<div class="woostify-slide-description"><?php echo esc_html( $v['description'] ); ?></div>
									<?php } ?>

									<div class="woostify-slide-button-wrapper">
										<?php if ( ! empty( $v['btn1_text'] ) ) { ?>
											<a class="woostify-slide-button" href="<?php echo esc_url( $v['btn1_link']['url'] ); ?>"><?php echo esc_html( $v['btn1_text'] ); ?></a>
										<?php } ?>

										<?php if ( ! empty( $v['btn2_text'] ) ) { ?>
										<a class="woostify-slide-button" href="<?php echo esc_url( $v['btn2_link']['url'] ); ?>"><?php echo esc_html( $v['btn2_text'] ); ?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		<?php
	}

	/**
	 * Render on Preview
	 */
	protected function _content_template() { // phpcs:ignore
		?>
		<#
			var tinyOptions = {
					"items": 1,
					"autoplay": settings.autoplay,
					"autoplayTimeout": settings.timeout,
					"autoplayHoverPause": settings.pause_on_hover,
					"controls": ( 'both' == settings.navigation || 'arrows' == settings.navigation ),
					"nav": ( 'both' == settings.navigation || 'dots' == settings.navigation ),
					"speed": settings.speed,
					"loop": settings.loop
				}
				tinyOptions = JSON.stringify( tinyOptions );
		#>
			<div class="woostify-slider-widget tns" data-tiny-slider="{{ tinyOptions }}">
				<#
				_.each( settings.slides, function( item ) {
					var animation = ( 'yes' == item.custom && 'default' != item.custom_content_animation ) ? item.custom_content_animation : settings.content_animation;
					#>
					<div class="woostify-slide tnsi elementor-repeater-item-{{ item._id }}" data-animate="{{ animation }}">
						<div class="woostify-slide-inner">

							<div class="woostify-slide-container">
								<div class="woostify-slide-content">
									<# if ( item.title ) { #>
										<h2 class="woostify-slide-title">{{{ item.title }}}</h2>
									<# } #>

									<# if ( item.description ) { #>
									<div class="woostify-slide-description">{{{ item.description }}}</div>
									<# } #>

									<div class="woostify-slide-button-wrapper">
										<# if ( item.btn1_text ) { #>
											<a class="woostify-slide-button" href="{{ item.btn1_link.url }}">{{{ item.btn1_text }}}</a>
										<# } #>

										<# if ( item.btn2_text ) { #>
										<a class="woostify-slide-button" href="{{ item.btn2_link.url }}">{{{ item.btn2_text }}}</a>
										<# } #>
									</div>
								</div>
							</div>
						</div>
					</div>
					<#
				} );
				#>
			</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Slider_Widget() );
