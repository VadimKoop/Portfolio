<?php
/**
 * Elementor Product Tab Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product tab widget.
 */
class Woostify_Elementor_Product_Tab_Widget extends Widget_Base {
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
		return 'woostify-product-tab';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return __( 'Woostify - Product Tab', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->filter();
		$this->product();
		$this->section_icons_style();
		$this->section_sale_flash();
	}

	/**
	 * General
	 */
	private function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => esc_html__( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'     => __( 'Grid', 'woostify-pro' ),
					'carousel' => __( 'Carousel', 'woostify-pro' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Title', 'woostify-pro' ),
			)
		);

		$repeater->add_control(
			'data',
			array(
				'label'     => __( 'Source', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'select',
				'separator' => 'before',
				'options'   => array(
					'select'    => __( 'Manual Selection', 'woostify-pro' ),
					'latest'    => __( 'Latest Products', 'woostify-pro' ),
					'featured'  => __( 'Featured', 'woostify-pro' ),
					'best-sell' => __( 'Best Sellers', 'woostify-pro' ),
					'sale'      => __( 'On Sale', 'woostify-pro' ),
				),
			)
		);

		$repeater->add_control(
			'cat_ids',
			array(
				'label'     => esc_html__( 'Categories', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
				'condition' => array(
					'data' => 'select',
				),
			)
		);

		$repeater->add_control(
			'product_ids',
			array(
				'label'     => esc_html__( 'Products', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'post_type',
					'name' => 'product',
				),
				'condition' => array(
					'data' => 'select',
				),
			)
		);

		$repeater->add_control(
			'ex_cat_ids',
			array(
				'label'     => esc_html__( 'Exclude Categories', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
				'condition' => array(
					'data!' => 'latest',
				),
			)
		);

		$repeater->add_control(
			'ex_product_ids',
			array(
				'label'     => esc_html__( 'Exclude Products', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'post_type',
					'name' => 'product',
				),
				'condition' => array(
					'data!' => 'latest',
				),
			)
		);

		$repeater->add_control(
			'total',
			array(
				'label'     => esc_html__( 'Total Products', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'col',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Columns', 'woostify-pro' ),
				'default' => 4,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			)
		);

		$repeater->add_control(
			'orderby',
			array(
				'label'     => esc_html__( 'Order By', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'name',
				'condition' => array(
					'data!' => array(
						'best-sell',
						'latest',
					),
				),
				'options'   => array(
					'ID'     => esc_html__( 'ID', 'woostify-pro' ),
					'name'   => esc_html__( 'Name', 'woostify-pro' ),
					'title'  => esc_html__( 'Title', 'woostify-pro' ),
					'price'  => esc_html__( 'Price', 'woostify-pro' ),
					'rating' => esc_html__( 'Rating', 'woostify-pro' ),
					'date'   => esc_html__( 'Date', 'woostify-pro' ),
					'rand'   => esc_html__( 'Random', 'woostify-pro' ),
				),
			)
		);

		$repeater->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'ASC',
				'condition' => array(
					'data!' => 'latest',
				),
				'options'   => array(
					'ASC'  => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC' => esc_html__( 'DESC', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'list',
			array(
				'label'       => __( 'Product Tab', 'woostify-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title' => __( 'Featured', 'woostify-pro' ),
						'data'  => 'featured',
					),
					array(
						'title' => __( 'Best Sellers', 'woostify-pro' ),
						'data'  => 'best-sell',
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product
	 */
	private function product() {

		$this->start_controls_section(
			'product',
			array(
				'label' => __( 'Product', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_title',
			array(
				'label' => __( 'Title', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_title_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woocommerce-loop-product__title a',
			)
		);

		// Title Spacing.
		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'           => __( 'Spacing', 'woostify-pro' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => array(
					'px' => array(
						'max' => 200,
					),
				),
				'devices'         => array(
					'desktop',
					'tablet',
					'mobile',
				),
				'desktop_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .woostify-products-tab-content .woocommerce-loop-product__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_style_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_style_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_style_title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-loop-product__title a ' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_style_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_style_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-loop-product__title a:hover ' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Price.
		$this->add_control(
			'product_price',
			array(
				'label'     => __( 'Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Price.
		$this->add_control(
			'product_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .price ins span, {{WRAPPER}} .woostify-products-tab-content .price span' => 'color: {{VALUE}};',
				),
			)
		);

		// Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .price ins span, {{WRAPPER}} .woostify-products-tab-content .price span',
			)
		);

		// Regular Price.
		$this->add_control(
			'product_regular_price',
			array(
				'label'     => __( 'Sale Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Regular Price.
		$this->add_control(
			'product_regular_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .price del span ' => 'color: {{VALUE}};',
				),
			)
		);

		// Regular Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_regular_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .price del span',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Tabs
	 */
	private function filter() {

		$this->start_controls_section(
			'filter',
			array(
				'label' => __( 'Filter', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'filter_tab',
			array(
				'label' => __( 'Tab', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Alignment.
		$this->add_control(
			'alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'   => array(
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-widget .woostify-products-tab-head' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_margin',
			array(
				'label'              => __( 'Margin', 'woostify-pro' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em' ),
				'allowed_dimensions' => array( 'top', 'bottom' ),
				'selectors'          => array(
					'{{WRAPPER}} .woostify-products-tab-head' => 'margin: {{TOP}}{{UNIT}} 0px {{BOTTOM}}{{UNIT}} 0px;',
				),
			)
		);

		$this->add_control(
			'heading_item_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'heading_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-head',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typo',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-btn',
			)
		);

		$this->add_control(
			'filter_list',
			array(
				'label'     => __( 'Tab List', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'list_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-btn',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'style_tabs' );

		// Normal.
		$this->start_controls_tab(
			'style_tabs_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'tabs_title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'color: {{VALUE}};',
				),
			)
		);

		// Background.
		$this->add_control(
			'tabs_background_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn' => 'background: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'style_tabs_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'style_title_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover Background.
		$this->add_control(
			'tabs_background_hover_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'background: {{VALUE}};',
				),
			)
		);

		// Hover border.
		$this->add_control(
			'style_title_hover_border',
			array(
				'label'     => __( 'Border', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'style_tabs_active',
			array(
				'label' => __( 'Active', 'woostify-pro' ),
			)
		);

		// Active color.
		$this->add_control(
			'style_title_active_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'color: {{VALUE}};',
				),
			)
		);

		// Active Background.
		$this->add_control(
			'tabs_background_active_color',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'background: {{VALUE}};',
				),
			)
		);

		// Active border.
		$this->add_control(
			'style_title_active_border',
			array(
				'label'     => __( 'Border', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-btn.active' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Sale Flash
	 */
	private function section_sale_flash() {
		$this->start_controls_section(
			'section_sale_flash',
			array(
				'label' => __( 'Sale Flash', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Color.
		$this->add_control(
			'product_sale_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_sale_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_sale_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale',
			)
		);

		// Border Sale radius.
		$this->add_responsive_control(
			'product_sale_border_radius',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'size_units' => array(
					'px',
					'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Sale Width.
		$this->add_control(
			'sale_width',
			array(
				'label'     => __( 'Width', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Sale Height.
		$this->add_control(
			'sale_height',
			array(
				'label'     => __( 'Height', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .woostify-tag-on-sale' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button Style.
	 */
	private function section_icons_style() {
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Button.
		$this->add_control(
			'product_button',
			array(
				'label' => __( 'Add To Cart', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Button Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_button_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .button',
			)
		);

		// Border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_button_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .button',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_button_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_text_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'product_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Button Spacing.
		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'           => __( 'Spacing', 'woostify-pro' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => array(
					'px' => array(
						'max' => 200,
					),
				),
				'devices'         => array(
					'desktop',
					'tablet',
					'mobile',
				),
				'desktop_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 0,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .woostify-products-tab-content .button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icons_quickview',
			array(
				'label'     => __( 'Quick View', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_quickview_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_quickview_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_quickview_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_quickview_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_quickview_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_quickview_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_quickview_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .quick-view-with-text:hover.product-quick-view-btn:before,
					{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:hover,
					{{WRAPPER}} .woostify-products-tab-content .quick-view-with-icon:hover.product-quick-view-btn:before' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_quickview_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .product-quick-view-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'icons_wishlist',
			array(
				'label'     => __( 'Wishlist', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_style_wishlist_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:before',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_wishlist_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_wishlist_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_wishlist_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:before' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'product_wishlist_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_wishlist_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_hover_wishlist_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl-position-after:hover.tinvwl_add_to_wishlist_button:before' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'product_wishlist_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-products-tab-content .tinvwl_add_to_wishlist_button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Product tab render
	 */
	public function product_tab_render() {
		$settings = $this->get_settings_for_display();
		$list     = $settings['list'];
		$response = array();

		foreach ( $list as $k ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $k['total'],
				'order'          => $k['order'],
			);

			// Category ids.
			$cat_ids    = $k['cat_ids'];
			$ex_cat_ids = $k['ex_cat_ids'];

			// Product ids.
			$product_ids    = $k['product_ids'];
			$ex_product_ids = $k['ex_product_ids'];

			switch ( $k['data'] ) {
				case 'select':
				default:
					if ( ! empty( $cat_ids ) ) {
						$args['tax_query'] = array( // phpcs:ignore
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'term_id',
								'operator' => 'IN',
								'terms'    => $cat_ids,
							),
						);
					}

					if ( ! empty( $product_ids ) ) {
						$args['post__in'] = $product_ids;
					}

					if ( ! empty( $ex_product_ids ) ) {
						$args['post__not_in'] = $ex_product_ids;
					}

					break;
				case 'latest':
					$args = array(
						'post_type'   => 'product',
						'post_status' => 'publish',
						'order'       => 'DESC',
						'orderby'     => 'ID',
					);

					break;
				case 'featured':
					$args['tax_query'] = array( // phpcs:ignore
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'featured',
							'operator' => 'IN',
						),
					);

					if ( ! empty( $ex_cat_ids ) ) {
						$args['tax_query'] = array( // phpcs:ignore
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $ex_cat_ids,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $ex_product_ids ) ) {
						$args['post__not_in'] = $ex_product_ids;
					}

					break;
				case 'best-sell':
					$args['meta_key'] = 'total_sales'; // phpcs:ignore

					if ( ! empty( $ex_cat_ids ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $ex_cat_ids,
							'operator' => 'NOT IN',
						);
					}

					if ( ! empty( $ex_product_ids ) ) {
						$args['post__not_in'] = $ex_product_ids;
					}

					break;
				case 'sale':
					$args['meta_query'] = array( // phpcs:ignore
						'relation' => 'OR',
						array(
							'key'     => '_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						),
						array(
							'key'     => '_min_variation_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						),
					);

					$args['tax_query'] = array( // phpcs:ignore
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $ex_cat_ids,
							'operator' => 'NOT IN',
						),
					);

					if ( ! empty( $ex_product_ids ) ) {
						$args['post__not_in'] = $ex_product_ids;
					}

					break;
			}

			switch ( $k['orderby'] ) {
				case 'price':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_price'; // phpcs:ignore
					break;
				case 'rating':
					$args['orderby']  = 'meta_value_num';
					$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore
					break;
				default:
					if ( 'best-sell' === $k['data'] ) {
						$args['orderby'] = 'meta_value_num';
					} else {
						$args['orderby'] = $k['orderby'];
					}
					break;
			}

			array_push( $response, $args );
		}

		return $response;
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$list     = $settings['list'];
		if ( empty( $list ) ) {
			return;
		}

		$data        = $this->product_tab_render();
		$arrow_left  = apply_filters( 'woostify_product_tab_carousel_arrow_left_icon', 'ti-angle-left' );
		$arrow_right = apply_filters( 'woostify_product_tab_carousel_arrow_right_icon', 'ti-angle-right' );

		$list_first = array_shift( $list );
		$data_first = array_shift( $data );

		// Detect first slider init.
		$has_slider = ! empty( $data_first['posts_per_page'] ) && intval( $data_first['posts_per_page'] ) > intval( $list_first['col'] ) ? 'has-slider' : '';
		?>

		<div class="woostify-products-tab-widget" data-layout="<?php echo esc_attr( $settings['layout'] ); ?>-layout">
			<div class="woostify-products-tab-head">
				<div class="woostify-products-tab-head-buttons">
					<span class="woostify-products-tab-btn ready active" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>"><?php echo esc_html( $list_first['title'] ); ?></span>

					<?php
					if ( ! empty( $list ) ) {
						foreach ( $list as $v ) {
							?>
							<span class="woostify-products-tab-btn" data-id="<?php echo esc_attr( $v['_id'] ); ?>"><?php echo esc_html( $v['title'] ); ?></span>
							<?php
						}
					}
					?>
				</div>

				<?php if ( 'carousel' === $settings['layout'] ) { ?>
					<div class="woostify-product-tab-carousel-arrows">
						<div class="woostify-product-tab-arrows-container active <?php echo esc_attr( $has_slider ? '' : 'hidden' ); ?>" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>">
							<span class="<?php echo esc_attr( $arrow_left ); ?>"></span>
							<span class="<?php echo esc_attr( $arrow_right ); ?>"></span>
						</div>

						<?php
						if ( ! empty( $list ) ) {
							foreach ( $list as $v ) {
								?>
								<div class="woostify-product-tab-arrows-container" data-id="<?php echo esc_attr( $v['_id'] ); ?>">
									<span class="<?php echo esc_attr( $arrow_left ); ?>"></span>
									<span class="<?php echo esc_attr( $arrow_right ); ?>"></span>
								</div>
								<?php
							}
						}
						?>
					</div>
				<?php } ?>
			</div>

			<div class="woostify-products-tab-body">
				<div class="woostify-products-tab-content active <?php echo esc_attr( $has_slider ); ?>" data-columns="<?php echo esc_attr( $list_first['col'] ); ?>" data-id="<?php echo esc_attr( $list_first['_id'] ); ?>">
					<?php
					$query = new \WP_Query( $data_first );

					$product_columns = array(
						'products columns-6',
						'products columns-5',
						'products columns-4',
						'products columns-3',
						'products columns-2',
						'products columns-1',
					);

					ob_start();
					if ( $query->have_posts() ) {
						woocommerce_product_loop_start();

						while ( $query->have_posts() ) {
							$query->the_post();

							wc_get_template_part( 'content', 'product' );
						}

						// Reset loop.
						woocommerce_reset_loop();
						wp_reset_postdata();

						woocommerce_product_loop_end();
					}
					$content_loop = ob_get_clean();

					for ( $i = 0; $i < 6; $i++ ) {
						$str = strpos( $content_loop, $product_columns[ $i ] );

						if ( ! $str ) {
							continue;
						}

						if ( false !== $str ) {
							$content_loop = str_replace( $product_columns[ $i ], 'products columns-' . $list_first['col'], $content_loop );
							break;
						}
					}

					echo $content_loop; // phpcs:ignore
					?>
				</div>

				<?php
				if ( ! empty( $list ) ) {
					foreach ( $list as $i => $j ) {
						?>
						<div class="woostify-products-tab-content" data-columns="<?php echo esc_attr( $j['col'] ); ?>" data-id="<?php echo esc_attr( $j['_id'] ); ?>" data-query='<?php echo wp_json_encode( $data[ $i ] ); ?>'></div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Tab_Widget() );
