<?php
/**
 * Elementor Button Add To Cart Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class for woostify elementor Button Add To Cart widget.
 */
class Woostify_Elementor_Button_Add_To_Cart_Widget extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme', 'woocommerce-elements-single' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-button-add-to-cart';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Custom Add To Cart', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Add to cart button
	 */
	protected function add_to_cart_button() {
		$this->start_controls_section(
			'add_to_cart_button',
			array(
				'label' => __( 'Add To Cart Button', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Text Name', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Add To Cart', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'product_ids',
			array(
				'label' => esc_html__( 'Select Products', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'post_type',
					'name' => 'product',
				),
			)
		);

		// Image alignment.
		$this->add_responsive_control(
			'button_alignment',
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
				'tablet_default' => 'left',
				'mobile_default' => 'left',
				'selectors'      => array(
					'{{WRAPPER}} .woostify-button-add-to-cart' => 'text-align: {{VALUE}};',
				),
			)
		);

		// Icon.
		$this->add_control(
			'selected_icon',
			array(
				'label' => __( 'Choose Icon', 'woostify-pro' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		// Icons Spacing.
		$this->add_control(
			'icon_spacing',
			array(
				'label'     => __( 'Icon Spacing', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button i' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_css_id',
			array(
				'label'       => __( 'Button ID', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'woostify-pro' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'woostify-pro' ),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button Style
	 */
	protected function button_style() {
		$this->start_controls_section(
			'button_style',
			array(
				'label' => __( 'Button Style', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'add_to_cart_button_typo',
				'selector' => '{{WRAPPER}} .woostify-button-add-to-cart .button span',
			)
		);

		// Icons Size.
		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'add_to_cart_button_tabs' );

		// Normal.
		$this->start_controls_tab(
			'add_to_cart_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'add_to_cart_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'add_to_cart_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'add_to_cart_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'add_to_cart_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'add_to_cart_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'add_to_cart_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button:hover' => 'border-color: {{VALUE}};',
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
				'name'      => 'add_to_cart_button_border',
				'label'     => __( 'Border', 'woostify-pro' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .woostify-button-add-to-cart .button',
			)
		);

		$this->add_responsive_control(
			'add_to_cart_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '10',
					'right'    => '20',
					'bottom'   => '10',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-button-add-to-cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->add_to_cart_button();
		$this->button_style();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$buttonid = $settings['button_css_id'];
		$text     = $settings['text'];
		$id       = $settings['product_ids'];
		$product  = wc_get_product( $id );

		if ( ! $product ) {
			return;
		}

		if ( ! empty( $settings['selected_icon']['value'] ) ) {
			$icon = $settings['selected_icon']['value'];
		}
		if ( $product->get_type() !== 'variable' ) {
			$variation = 'product_type_simple add_to_cart_button ajax_add_to_cart';
			$url       = '?add-to-cart=' . $id;
		} else {
			$variation = 'product_type_variable';
			$url       = get_permalink( $id );
		}
		if ( $product->is_in_stock() ) {
			$url = '?add-to-cart=' . $id;
		} else {
			$url       = get_permalink( $id );
			$variation = 'product_type_simple';
		}

		?>
			<div class="woostify-button-add-to-cart">
				<a id="<?php echo esc_attr( $buttonid ); ?>" href="<?php echo esc_attr( $url ); ?>" data-quantity="1" class="loop-add-to-cart-btn button <?php echo esc_attr( $variation ); ?>" data-product_id="<?php echo esc_attr( $id ); ?>">
					<i class="<?php echo esc_attr( $icon ); ?>"></i>
					<span><?php echo esc_html( $text ); ?></span>
				</a>
			</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Button_Add_To_Cart_Widget() );
