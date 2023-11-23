<?php
/**
 * Elementor Posts Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class for woostify elementor posts widget.
 */
class Woostify_Elementor_Posts_Widget extends Widget_Base {
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
		return 'woostify-post';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Posts', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-post';
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return array(
			'woostify-elementor-widget',
		);
	}

	/**
	 * General
	 */
	private function section_general() {
		$this->start_controls_section(
			'post_content',
			array(
				'label' => esc_html__( 'General', 'woostify-pro' ),
			)
		);

		// Layout.
		$this->add_control(
			'layout',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Layout', 'woostify-pro' ),
				'default' => 'grid',
				'options' => array(
					'grid'     => __( 'Grid', 'woostify-pro' ),
					'carousel' => __( 'Carousel', 'woostify-pro' ),
					'list'     => __( 'List', 'woostify-pro' ),
				),
			)
		);

		// Columns.
		$this->add_responsive_control(
			'columns',
			array(
				'separator'      => 'before',
				'type'           => Controls_Manager::SELECT,
				'label'          => esc_html__( 'Columns', 'woostify-pro' ),
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options'        => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			)
		);

		// Columns gap for Grid layout.
		$this->add_responsive_control(
			'columns_gap',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Columns Gap', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ht-grid'      => 'margin: 0px -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ht-grid-item' => 'padding: 0px {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'grid',
				),
			)
		);

		// Columns gap for Carousel layout.
		$this->add_responsive_control(
			'columns_gap_carousel',
			array(
				'type'           => Controls_Manager::SLIDER,
				'label'          => esc_html__( 'Columns Gap', 'woostify-pro' ),
				'size_units'     => array( 'px' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'        => array(
					'unit' => 'px',
					'size' => 15,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 15,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 15,
				),
				'condition'      => array(
					'layout' => 'carousel',
				),
			)
		);

		// Grid space.
		$this->add_responsive_control(
			'space',
			array(
				'type'               => Controls_Manager::DIMENSIONS,
				'label'              => esc_html__( 'Space', 'woostify-pro' ),
				'size_units'         => array( 'px', 'em' ),
				'default'            => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'allowed_dimensions' => array(
					'top',
					'bottom',
				),
				'selectors'          => array(
					'{{WRAPPER}} .ht-grid-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'layout' => array(
						'grid',
						'list',
					),
				),
			)
		);

		// Thumbnail image.
		$this->add_control(
			'image',
			array(
				'separator'    => 'before',
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Image', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Post meta.
		$this->add_control(
			'meta',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Post Meta', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Pagination.
		$this->add_control(
			'pagination',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Pagination', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => 'grid',
				),
			)
		);

		// Carousel controls.
		$this->add_control(
			'controls',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Controls', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout' => 'carousel',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Image
	 */
	private function section_image() {
		$this->start_controls_section(
			'image_section',
			array(
				'label'     => esc_html__( 'Image', 'woostify-pro' ),
				'condition' => array(
					'image' => 'yes',
				),
			)
		);

		// Image size.
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'medium_large',
			)
		);

		// Image alignment.
		$this->add_responsive_control(
			'image_alignment',
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
					'{{WRAPPER}} .pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Image space.
		$this->add_responsive_control(
			'image_space',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '20',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wg-post-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'layout'     => array(
					'grid',
					'carousel',
				),
			)
		);

		// Image space. Layout 2.
		$this->add_responsive_control(
			'image_space_layout',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '30',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wg-post-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'layout'     => 'list',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Post Meta
	 */
	private function section_post_meta() {
		$this->start_controls_section(
			'post_meta_section',
			array(
				'label'     => esc_html__( 'Post Meta', 'woostify-pro' ),
				'condition' => array(
					'meta' => 'yes',
				),
			)
		);

		// Posted on.
		$this->add_control(
			'date',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Publish Date', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Author.
		$this->add_control(
			'author',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Author', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Category.
		$this->add_control(
			'category',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Categories', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Comments.
		$this->add_control(
			'comment',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Comments', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Post meta typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'post_meta',
				'label'      => __( 'Typography', 'woostify-pro' ),
				'scheme'     => Scheme_Typography::TYPOGRAPHY_1,
				'selector'   => '{{WRAPPER}} .wg-post-meta-item a',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'date',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'author',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'category',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'comment',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		// Post meta alignment.
		$this->add_responsive_control(
			'post_meta_alignment',
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
				'default'        => 'left',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => array(
					'{{WRAPPER}} .wg-post-meta' => 'text-align: {{VALUE}};',
				),
				'conditions'     => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'date',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'author',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'category',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'comment',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		// Post meta space.
		$this->add_responsive_control(
			'post_meta_space',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wg-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'date',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'author',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'category',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'comment',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'hr_tab_post_meta',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		// START TAB POST META.
		$this->start_controls_tabs( 'tab_post_meta' );

		// Tab title start.
		$this->start_controls_tab(
			'tab_title',
			array(
				'label' => __( 'Title', 'woostify-pro' ),
			)
		);

		// Title.
		$this->add_control(
			'title',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Title color.
		$this->add_control(
			'title_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'woostify-pro' ),
				'default'   => '#3e3e3e',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .wg-post-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title' => 'yes',
				),
			)
		);

		// Title typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'woostify-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .wg-post-title',
				'condition' => array(
					'title' => 'yes',
				),
			)
		);

		// Title space.
		$this->add_responsive_control(
			'title_space',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '10',
					'right'    => '0',
					'bottom'   => '10',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wg-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'title' => 'yes',
				),
			)
		);

		// Title alignment.
		$this->add_responsive_control(
			'title_alignment',
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
				'default'        => 'left',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => array(
					'{{WRAPPER}} .wg-post-title' => 'text-align: {{VALUE}};',
				),
				'condition'      => array(
					'title' => 'yes',
				),
			)
		);

		// Tab title end.
		$this->end_controls_tab();

		// Tab excerpt start.
		$this->start_controls_tab(
			'tab_excerpt',
			array(
				'label' => __( 'Excerpt', 'woostify-pro' ),
			)
		);

		// Post excerpt.
		$this->add_control(
			'excerpt',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Excerpt color.
		$this->add_control(
			'excerpt_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'woostify-pro' ),
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .wg-post-summary' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'excerpt' => 'yes',
				),
			)
		);

		// Excerpt typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'label'     => __( 'Typography', 'woostify-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .wg-post-summary',
				'condition' => array(
					'excerpt' => 'yes',
				),
			)
		);

		// Limit excrept.
		$this->add_control(
			'limit_excerpt',
			array(
				'type'      => Controls_Manager::NUMBER,
				'label'     => __( 'Limit Excerpt', 'woostify-pro' ),
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'default'   => 15,
				'condition' => array(
					'excerpt' => 'yes',
				),
			)
		);

		// Excrept alignment.
		$this->add_responsive_control(
			'excrept_alignment',
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
				'default'        => 'left',
				'tablet_default' => 'center',
				'mobile_default' => 'center',
				'selectors'      => array(
					'{{WRAPPER}} .wg-post-summary' => 'text-align: {{VALUE}};',
				),
				'condition'      => array(
					'excerpt' => 'yes',
				),
			)
		);

		// Tab excerpt end.
		$this->end_controls_tab();

		// Tab read more start.
		$this->start_controls_tab(
			'tab_read_more',
			array(
				'label' => __( 'Read More', 'woostify-pro' ),
			)
		);

		// Read more button.
		$this->add_control(
			'read_more',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Read more color.
		$this->add_control(
			'read_more_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'woostify-pro' ),
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .wg-read-more-button a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'read_more' => 'yes',
				),
			)
		);

		// Read more typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'read_more_typography',
				'label'     => __( 'Typography', 'woostify-pro' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .wg-read-more-button a',
				'condition' => array(
					'read_more' => 'yes',
				),
			)
		);

		// Read more text.
		$this->add_control(
			'read_more_text',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Text', 'woostify-pro' ),
				'default'   => __( 'Read More', 'woostify-pro' ),
				'condition' => array(
					'read_more' => 'yes',
				),
			)
		);

		// Read more alignment.
		$this->add_responsive_control(
			'read_more_alignment',
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
					'{{WRAPPER}} .wg-read-more-button' => 'text-align: {{VALUE}};',
				),
				'condition'      => array(
					'read_more' => 'yes',
				),
			)
		);

		// Read more space.
		$this->add_responsive_control(
			'read_more_space',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '30',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .wg-read-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'read_more' => 'yes',
				),
			)
		);

		// Tab read more end.
		$this->end_controls_tab();

		// END TAB POST META.
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Pagination
	 */
	private function section_pagination() {
		$this->start_controls_section(
			'pagination_section',
			array(
				'label'     => esc_html__( 'Pagination', 'woostify-pro' ),
				'condition' => array(
					'layout'     => 'grid',
					'pagination' => 'yes',
				),
			)
		);

		// Pagination alignment.
		$this->add_responsive_control(
			'pagination_alignment',
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
					'{{WRAPPER}} .pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Pagination space.
		$this->add_responsive_control(
			'pagination_space',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Space', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '30',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	private function section_controls() {
		$this->start_controls_section(
			'controls_section',
			array(
				'label'     => esc_html__( 'Controls', 'woostify-pro' ),
				'condition' => array(
					'layout'   => 'carousel',
					'controls' => 'yes',
				),
			)
		);

		// START TAB CONTROLS.
		$this->start_controls_tabs( 'tab_controls' );

		// Tab title start.
		$this->start_controls_tab(
			'tab_arrows',
			array(
				'label' => __( 'Arrows', 'woostify-pro' ),
			)
		);

		// Arrows.
		$this->add_control(
			'arrows',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Show arrows on hover.
		$this->add_control(
			'arrows_on_hover',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show On Hover', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'arrows' => 'yes',
				),
			)
		);

		// Arrows size.
		$this->add_responsive_control(
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
				'condition'  => array(
					'arrows' => 'yes',
				),
			)
		);

		// Arrows border radius.
		$this->add_responsive_control(
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
				'condition'  => array(
					'arrows' => 'yes',
				),
			)
		);

		// Arrows position.
		$this->add_responsive_control(
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
				'condition'  => array(
					'arrows' => 'yes',
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
				'condition' => array(
					'arrows' => 'yes',
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
				'condition' => array(
					'arrows' => 'yes',
				),
			)
		);

		// Tab arrows end.
		$this->end_controls_tab();

		// Tab dots start.
		$this->start_controls_tab(
			'tab_dots',
			array(
				'label' => __( 'Dots', 'woostify-pro' ),
			)
		);

		// Dots.
		$this->add_control(
			'dots',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Show dots on hover.
		$this->add_control(
			'dots_on_hover',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show On Hover', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'condition'    => array(
					'dots' => 'yes',
				),
			)
		);

		// Dots size.
		$this->add_responsive_control(
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
				'condition'  => array(
					'dots' => 'yes',
				),
			)
		);

		// Dots border radius.
		$this->add_responsive_control(
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
				'condition'  => array(
					'dots' => 'yes',
				),
			)
		);

		// Dots position.
		$this->add_responsive_control(
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
				'condition'  => array(
					'dots' => 'yes',
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
				'condition' => array(
					'dots' => 'yes',
				),
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
				'condition' => array(
					'dots' => 'yes',
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
				'condition'      => array(
					'dots' => 'yes',
				),
			)
		);

		// Tab dots end.
		$this->end_controls_tab();

		// END TAB CONTROLS.
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Generate Tiny slider settings
	 *
	 * @param      array $settings The widget settings.
	 * @param      int   $desktop  The col desktop.
	 * @param      int   $tablet   The col tablet.
	 * @param      int   $mobile   The col mobile.
	 *
	 * @return     string Tiny slider data
	 */
	private function tiny_slider_options( $settings, $desktop, $tablet, $mobile ) {

		// This function works only Carousel Layout.
		if ( 'carousel' !== $settings['layout'] ) {
			return '';
		}

		$options = array(
			'items'      => 3,
			'controls'   => 'yes' === $settings['arrows'] ? true : false,
			'nav'        => 'yes' === $settings['dots'] ? true : false,
			'loop'       => false,
			'autoHeight' => true,
			'gutter'     => 15,
			'mouseDrag'  => true,
			'responsive' => array(
				240  => array(
					'items'  => $mobile,
					'gutter' => absint( $settings['columns_gap_carousel_mobile']['size'] ),
				),
				767  => array(
					'items'  => $tablet,
					'gutter' => absint( $settings['columns_gap_carousel_tablet']['size'] ),
				),
				1024 => array(
					'items'  => $desktop,
					'gutter' => absint( $settings['columns_gap_carousel']['size'] ),
				),
			),
		);

		$tiny_slider_options = "data-tiny-slider='" . wp_json_encode( $options ) . "'";

		return $tiny_slider_options;
	}

	/**
	 * Query
	 */
	private function section_query() {
		$this->start_controls_section(
			'post_query',
			array(
				'label' => esc_html__( 'Query', 'woostify-pro' ),
			)
		);

		// Category ids.
		$this->add_control(
			'cat_ids',
			array(
				'label' => esc_html__( 'Categories', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'term',
					'name' => 'category',
				),
			)
		);

		// Post ids.
		$this->add_control(
			'post_ids',
			array(
				'label' => esc_html__( 'Posts', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'post_type',
					'name' => 'post',
				),
			)
		);

		// Exclude post ids.
		$this->add_control(
			'exclude_post_ids',
			array(
				'label' => esc_html__( 'Exclude Posts', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'post_type',
					'name' => 'post',
				),
			)
		);

		// Posts per page.
		$this->add_control(
			'count',
			array(
				'label'   => esc_html__( 'Posts Per Page', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
			)
		);

		// Orderby.
		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'id',
				'options' => array(
					'id'            => esc_html__( 'ID', 'woostify-pro' ),
					'author'        => esc_html__( 'Author', 'woostify-pro' ),
					'title'         => esc_html__( 'Title', 'woostify-pro' ),
					'name'          => esc_html__( 'Name', 'woostify-pro' ),
					'date'          => esc_html__( 'Date', 'woostify-pro' ),
					'rand'          => esc_html__( 'Random', 'woostify-pro' ),
					'modified'      => esc_html__( 'Modified', 'woostify-pro' ),
					'comment_count' => esc_html__( 'Comment Count', 'woostify-pro' ),
				),
			)
		);

		// Order.
		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC' => esc_html__( 'DESC', 'woostify-pro' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->section_general();
		$this->section_image();
		$this->section_post_meta();
		$this->section_pagination();
		$this->section_controls();
		$this->section_query();
	}

	/**
	 * Render
	 */
	protected function render() {

		// Settings.
		$settings = $this->get_settings_for_display();

		// Layout.
		$layout = $settings['layout'];

		// Carousel settings for Preview mode.
		$tiny = $this->tiny_slider_options( $settings, $settings['columns'], $settings['columns_tablet'], $settings['columns_mobile'] );

		// Classes.
		if ( 'grid' === $layout ) {
			$grid   = array();
			$grid[] = 'ht-grid'; // Defined grid.
			$grid[] = 'ht-grid-' . $settings['columns']; // On desktop.
			$grid[] = 'ht-grid-tablet-' . $settings['columns_tablet']; // On tablet.
			$grid[] = 'ht-grid-mobile-' . $settings['columns_mobile']; // On mobile.

			// Wrapper classes.
			$wrapper_classes = 'woostify-posts-widget grid-layout';

			// Inner classes.
			$inner_classes = implode( ' ', $grid );

			// Items classes.
			$item_classes = 'wg-post-item ht-grid-item';
		} elseif ( 'list' === $layout ) {
			$grid   = array();
			$grid[] = 'ht-grid'; // Defined grid.
			$grid[] = 'ht-grid-' . $settings['columns']; // On desktop.
			$grid[] = 'ht-grid-tablet-' . $settings['columns_tablet']; // On tablet.
			$grid[] = 'ht-grid-mobile-' . $settings['columns_mobile']; // On mobile.

			// Wrapper classes.
			$wrapper_classes = 'woostify-posts-widget grid-layout grid-layout-flex';

			// Inner classes.
			$inner_classes = implode( ' ', $grid );

			// Items classes.
			$item_classes = 'wg-post-item ht-grid-item';
		} else {
			// Show controls on hover.
			$carousel = array();
			if ( 'yes' === $settings['controls'] ) {
				if ( 'yes' === $settings['arrows'] && 'yes' === $settings['arrows_on_hover'] ) {
					$carousel[] = 'arrows-on-hover';
				}

				if ( 'yes' === $settings['dots'] && 'yes' === $settings['dots_on_hover'] ) {
					$carousel[] = 'dots-on-hover';
				}
			}

			if ( count( $carousel ) > 1 ) {
				$carousel = array( 'controls-on-hover' );
			}

			$carousel[] = 'woostify-posts-widget carousel-layout';

			// Wrapper classes.
			$wrapper_classes = implode( ' ', $carousel );

			// Items classes.
			$inner_classes = 'woostify-post-slider tns';

			// Items classes.
			$item_classes = 'wg-post-item tnsi';
		}

		// Category ids.
		$cat_ids = $settings['cat_ids'];

		// Post ids.
		$post_ids         = $settings['post_ids'];
		$exclude_post_ids = $settings['exclude_post_ids'];

		$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : get_query_var( 'paged' );
		$args  = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $settings['count'],
			'orderby'             => $settings['order_by'],
			'order'               => $settings['order'],
			'paged'               => $paged ? $paged : 1,
			'ignore_sticky_posts' => 1,
		);

		// Categories.
		if ( ! empty( $cat_ids ) ) {
			$args['cat'] = $cat_ids;
		}

		// Post ids.
		if ( ! empty( $post_ids ) ) {
			$args['post__in'] = $post_ids;
		}

		// Exclude post ids.
		if ( ! empty( $exclude_post_ids ) ) {
			if ( ! empty( $post_ids ) ) {
				$diff             = array_diff( $post_ids, $exclude_post_ids );
				$args['post__in'] = $diff;
			} else {
				$args['post__not_in'] = $exclude_post_ids;
			}
		}

		// Query.
		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			?>
			<p class="no-posts-found"><?php esc_html_e( 'No posts found!', 'woostify-pro' ); ?></p>
			<?php
		}
		?>

		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="<?php echo esc_attr( $inner_classes ); ?>" <?php echo wp_kses_post( $tiny ); ?>>
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					<div class="<?php echo esc_attr( $item_classes ); ?>">
						<?php
						// Thumbnail image.
						if ( 'yes' === $settings['image'] && has_post_thumbnail() ) {
							$img_id   = get_post_thumbnail_id( get_the_ID() );
							$img_alt  = woostify_image_alt( $img_id, __( 'Post thumbnail', 'woostify-pro' ) );
							$img_size = $settings['image_size'];
							$img_kses = array(
								'img' => apply_filters(
									'woostify_pro_widget_posts_image_sanitize',
									array(
										'class'  => array(),
										'width'  => array(),
										'height' => array(),
										'alt'    => array(),
										'srcset' => array(),
										'sizes'  => array(),
									)
								),
							);
							$img      = get_the_post_thumbnail( get_the_ID(), $img_size, array( 'alt' => $img_alt ) );
							?>
							<a class="wg-post-image" href="<?php the_permalink(); ?>">
								<?php echo wp_kses( $img, $img_kses ); ?>
							</a>
							<?php
						}

						// Post meta.
						if ( 'yes' === $settings['meta'] ) {
							?>
							<div class="wg-post-content">
								<div class="wg-post-meta">
									<?php
									// Publish date.
									if ( 'yes' === $settings['date'] ) {
										?>
										<span class="wg-post-meta-item wg-post-date">
											<a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
										</span>
										<?php
									}

									// Author.
									if ( 'yes' === $settings['author'] ) {
										$author_id        = get_the_author_meta( 'ID' );
										$author_nickname  = get_the_author_meta( 'nickname' );
										$author_nicename  = get_the_author_meta( 'user_nicename' );
										$author_posts_url = get_author_posts_url( $author_id, $author_nicename );
										?>
										<span class="wg-post-meta-item wg-post-author">
											<a href="<?php echo esc_url( $author_posts_url ); ?>">
												<?php echo esc_html( $author_nickname ); ?>
											</a>
										</span>
										<?php
									}

									// Post categories.
									if ( 'yes' === $settings['category'] ) {
										$categories = get_the_category_list( __( ', ', 'woostify-pro' ) );
										?>
										<span class="wg-post-meta-item wg-post-categories">
											<?php echo wp_kses_post( $categories ); ?>
										</span>
										<?php
									}

									// Comments.
									if ( 'yes' === $settings['comment'] ) {
										?>
										<span class="wg-post-meta-item wg-post-comments">
											<?php
											comments_popup_link(
												__( 'No comments', 'woostify-pro' ),
												__( '1 Comment', 'woostify-pro' ),
												__( '% Comments', 'woostify-pro' )
											);
											?>
										</span>
										<?php
									}
									?>
								</div>
								<?php
								// Post title.
								if ( 'yes' === $settings['title'] ) {
									?>
									<h3 class="wg-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<?php
								}

								// Excerpt.
								if ( 'yes' === $settings['excerpt'] ) {
									$num_words = absint( $settings['limit_excerpt'] );
									$content   = wp_trim_words( get_the_excerpt(), $num_words );
									?>
									<div class="wg-post-summary">
										<?php echo wp_kses_post( $content ); ?>
									</div>
									<?php
								}

								// Read more button.
								if ( 'yes' === $settings['read_more'] && ! $settings['read_more_text'] ) {
									?>
									<div class="wg-read-more-button">
										<a href="<?php the_permalink(); ?>"><?php echo esc_html( $settings['read_more_text'] ); ?></a>
									</div>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>

			<?php
			// Pagination.
			if ( 'yes' === $settings['pagination'] && 'grid' === $layout ) {
				?>
				<div class="wg-post-pagination pagination">
					<?php
					$max       = $query->max_num_pages;
					$pagi_args = array(
						'base'    => preg_replace( '/\?.*/', '/', get_pagenum_link( 1 ) ) . '%_%',
						'format'  => 'page/%#%',
						'current' => max( 1, $paged ),
						'total'   => $max,
						'type'    => 'list',
					);

					$pagination = paginate_links( $pagi_args );

					echo wp_kses_post( $pagination );
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Posts_Widget() );
