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
class Woostify_Elementor_Toggle_Sidebar extends Widget_Base {
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
		return 'woostify-toogle-sidebar';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Toggle Sidebar', 'woostify-pro' );
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return array( 'woostify-elementor-widget' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-dual-button';
	}

	/**
	 * Add to cart button
	 */
	protected function toogle_sidebar() {
		$this->start_controls_section(
			'toogle_sidebar',
			array(
				'label' => __( 'Toogle Sidebar', 'woostify-pro' ),
			)
		);

		// Image alignment.
		$this->add_responsive_control(
			'toogle_alignment',
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
					'{{WRAPPER}} .woostify-toogle-sidebar' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'Text Name', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Filter', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Icon', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'theme',
				'options' => array(
					'theme' => __( 'Use Theme Icon', 'woostify-pro' ),
					'icon'  => __( 'Use Custom Icon', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'Choose Icon', 'woostify-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-bars',
					'library' => 'solid',
				),
				'condition' => array(
					'type' => 'icon',
				),
			)
		);

		// Icons Position.
		$this->add_control(
			'icon_position',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Icon Position', 'woostify-pro' ),
				'default' => 'before',
				'options' => array(
					'before' => esc_html__( 'Before', 'woostify-pro' ),
					'after'  => esc_html__( 'After', 'woostify-pro' ),
				),
			)
		);

		// Icon Size.
		$this->add_responsive_control(
			'icon_size',
			array(
				'label'           => __( 'Icon Size', 'woostify-pro' ),
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
					'size' => 15,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 15,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .icon-toogle-sidebar:before' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-svg-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .icon-position-before .icon-toogle-sidebar' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .icon-position-after .icon-toogle-sidebar ' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'toogle_padding',
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
					'{{WRAPPER}} .woostify-toogle-sidebar #toggle-sidebar-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Sidebar Content
	 */
	protected function toogle_sidebar_content() {
		$this->start_controls_section(
			'toogle_sidebar_content',
			array(
				'label' => __( 'Sidebar Content', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'sidebar_position',
			array(
				'label'   => esc_html__( 'Content Position', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'woostify-pro' ),
					'right' => esc_html__( 'Right', 'woostify-pro' ),
				),
			)
		);

		// Content Width.
		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => __( 'Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'selectors'  => array(
					'{{WRAPPER}} #sidebar-widgets' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => '30',
					'right'    => '20',
					'bottom'   => '30',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} #sidebar-widgets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button Style
	 */
	protected function toogle_sidebar_style() {
		$this->start_controls_section(
			'toogle_sidebar_style',
			array(
				'label' => __( 'Toogle Style', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toogle_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #toggle-sidebar-button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toogle_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} #toggle-sidebar-button',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'toogle_tabs' );

		// Normal.
		$this->start_controls_tab(
			'toogle_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'toogle_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #toggle-sidebar-button ' => 'color: {{VALUE}};',
				),
			)
		);

		// Background.
		$this->add_control(
			'toogle_background',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #toggle-sidebar-button ' => 'background: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'toogle_style_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'toogle_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #toggle-sidebar-button:hover ' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover Background.
		$this->add_control(
			'toogle_hover_background',
			array(
				'label'     => __( 'Background', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #toggle-sidebar-button:hover ' => 'background: {{VALUE}};',
				),
			)
		);

		// Hover color.
		$this->add_control(
			'border_hover_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #toggle-sidebar-button:hover ' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->toogle_sidebar();
		$this->toogle_sidebar_content();
		$this->toogle_sidebar_style();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings         = $this->get_settings_for_display();
		$text             = $settings['text'];
		$sidebar_position = $settings['sidebar_position'];
		$icon             = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_shop_bag_icon', 'ti-filter' ) : '';
		if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
			if ( is_array( $settings['icon']['value'] ) ) {
				$icon = 'custom-svg-icon';
			} else {
				$icon = $settings['icon']['value'];
			}
		}

		$render_icon  = '<i class="icon-toogle-sidebar ' . esc_attr( $icon ) . '"></i>';
		$icon_allowed = array(
			'i'   => array(
				'class' => array(),
			),
			'img' => array(
				'class' => array(),
				'src'   => array(),
				'alt'   => array(),
			),
		);

		if ( 'icon' === $settings['type'] && is_array( $settings['icon']['value'] ) && ! empty( $settings['icon']['value'] ) ) {
			$img_id      = $settings['icon']['value']['id'];
			$img_url     = $settings['icon']['value']['url'];
			$img_alt     = woostify_image_alt( $img_id, __( 'Account Icon', 'woostify-pro' ) );
			$render_icon = '<img class="icon-toogle-sidebar ' . esc_attr( $icon ) . '" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $img_alt ) . '">';
		}
		?>
			<div class="woostify-toogle-sidebar-widget">
				<div class="woostify-toogle-sidebar">
					<button id="toggle-sidebar-button" class="icon-position-<?php echo esc_attr( $settings['icon_position'] ); ?>">
						<?php
							echo wp_kses( $render_icon, $icon_allowed );
							echo esc_html( $text );
						?>
					</button>
				</div>
				<div id="sidebar-widgets" class="widget-area shop-widget content-sidebar-<?php echo esc_attr( $sidebar_position ); ?>" data-position="sidebar-widget-<?php echo esc_attr( $sidebar_position ); ?>">
				<?php
				if ( is_active_sidebar( 'sidebar-shop' ) ) {
					dynamic_sidebar( 'sidebar-shop' );
				} elseif ( is_user_logged_in() ) {
					$widget_text = sprintf(
						/* translators: 1: admin URL */
						__( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets / Woocommerce Sidebar</strong></a> and dragging widgets into this widget area.', 'woostify' ),
						esc_url( admin_url( 'widgets.php' ) )
					);

					$widget_text_allowed = array(
						'a'      => array(
							'href' => array(),
						),
						'strong' => array(),
					);
					?>
					<div class="widget widget_text default-widget">
						<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
						<div class="textwidget">
							<p><?php echo wp_kses( $widget_text, $widget_text_allowed ); ?></p>
						</div>
					</div>

					<div class="widget widget_text default-widget">
						<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
						<div class="textwidget">
							<p><?php echo wp_kses( $widget_text, $widget_text_allowed ); ?></p>
						</div>
					</div>

					<div class="widget widget_text default-widget">
						<h6 class="widgettitle"><?php esc_html_e( 'Sidebar Shop Widget', 'woostify' ); ?></h6>
						<div class="textwidget">
							<p><?php echo wp_kses( $widget_text, $widget_text_allowed ); ?></p>
						</div>
					</div>
				<?php } ?>
			</div>
			</div>
		<?php

	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Toggle_Sidebar() );
