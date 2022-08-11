<?php
/**
 * Elementor Toggle Menu Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 */
class Woostify_Elementor_Toggle_Menu_Widget extends Widget_Base {
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
		return 'woostify-toggle-vertical-menu';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Toggle Vertical Menu', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-menu-bar';
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return array( 'woostify-elementor-widget' );
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'nav', 'menu', 'toggle' );
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

		// Height.
		$this->add_control(
			'button_height',
			array(
				'label'     => __( 'Height', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-wrapper .vertical-menu-button' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Alignment.
		$this->add_responsive_control(
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Icons
	 */
	public function icon() {
		$this->start_controls_section(
			'icon_section',
			array(
				'label' => __( 'Icon', 'woostify-pro' ),
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

		// Icon.
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
					'{{WRAPPER}} .position-icon-left .vertical-menu-button .icon-toogle-vertical'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .position-icon-right .vertical-menu-button .icon-toogle-vertical' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Icons width.
		$this->add_control(
			'icon_width',
			array(
				'label'     => __( 'Icon Width', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'condition' => array(
					'type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} .custom-svg-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Icons Position.
		$this->add_control(
			'icon_position',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Icon Position', 'woostify-pro' ),
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'woostify-pro' ),
					'right' => esc_html__( 'Right', 'woostify-pro' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'icon_typo',
				'selector' => '{{WRAPPER}} .toggle-vertical-menu-button .icon-toogle-vertical:before',
			)
		);

		// TAB START.
		$this->start_controls_tabs(
			'toggle_icon',
			array(
				'separator' => 'before',
			)
		);

		// Normal.
		$this->start_controls_tab(
			'icon_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .toggle-vertical-menu-button .icon-toogle-vertical:before' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'icon_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover Color.
		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .toggle-vertical-menu-button:hover .icon-toogle-vertical:before' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Button
	 */
	public function button() {
		$this->start_controls_section(
			'button',
			array(
				'label' => __( 'Button', 'woostify-pro' ),
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'button_alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button' => 'justify-content: {{VALUE}};',
				),
			)
		);

		// Text.
		$this->add_responsive_control(
			'button_text',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Text', 'woostify-pro' ),
				'default'     => esc_html__( 'Shop By Categories', 'woostify-pro' ),
				'placeholder' => esc_html__( 'Enter your button text', 'woostify-pro' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typo',
				'selector' => '{{WRAPPER}} .vertical-menu-button',
			)
		);

		// TAB START.
		$this->start_controls_tabs(
			'toggle_button',
			array(
				'separator' => 'before',
			)
		);

		// Normal.
		$this->start_controls_tab(
			'toggle_button_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'toggle_button_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_control(
			'toggle_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'toggle_button_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'toggle_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_control(
			'toggle_button_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_control(
			'toggle_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .vertical-menu-button:hover' => 'border-color: {{VALUE}};',
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
				'name'     => 'toggle_button_border',
				'label'    => __( 'Border', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .vertical-menu-button',
			)
		);

		// Padding.
		$this->add_responsive_control(
			'toggle_button_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .vertical-menu-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_responsive_control(
			'toggle_button_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .vertical-menu-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Vertical menu
	 */
	public function vertical_menu() {
		$this->start_controls_section(
			'verticcal_menu',
			array(
				'label' => __( 'Vertical Menu', 'woostify-pro' ),
			)
		);

		// Position.
		$this->add_control(
			'verticcal_menu_position',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Position', 'woostify-pro' ),
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'woostify-pro' ),
					'right' => esc_html__( 'Right', 'woostify-pro' ),
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
		$this->icon();
		$this->button();
		$this->vertical_menu();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$has_menu = has_nav_menu( 'vertical' );
		$icon     = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_toogle_icon', 'ti-toggle-vertical' ) : '';
		if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
			if ( is_array( $settings['icon']['value'] ) ) {
				$icon = 'custom-svg-icon';
			} else {
				$icon = $settings['icon']['value'];
			}
		}

		$render_icon  = '<i class="icon-toogle-vertical ' . esc_attr( $icon ) . '"></i>';
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
			$render_icon = '<img class="icon-toogle-vertical ' . esc_attr( $icon ) . '" src="' . esc_url( $img_url ) . '" alt="' . esc_attr( $img_alt ) . '">';
		}
		?>
		<div class="vertical-menu-wrapper position-<?php echo esc_attr( $settings['verticcal_menu_position'] ); ?> position-icon-<?php echo esc_attr( $settings['icon_position'] ); ?>">
			<div class="toggle-vertical-menu-wrapper">
				<?php if ( $has_menu ) { ?>
					<button class="vertical-menu-button toggle-vertical-menu-button">
						<?php
						echo wp_kses( $render_icon, $icon_allowed );
						echo esc_html( $settings['button_text'] );
						?>
					</button>
				<?php } else { ?>
					<a class="vertical-menu-button add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>">
						<?php
						echo wp_kses( $render_icon, $icon_allowed );
						esc_html_e( 'Add a Vertical Menu', 'woostify-pro' );
						?>
					</a>
				<?php } ?>
			</div>

			<?php if ( $has_menu ) { ?>
				<div class="site-vertical-menu">
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'vertical',
								'menu_class'     => 'vertical-navigation',
								'container'      => '',
								'walker'         => new \Woostify_Walker_Menu(),
							)
						);
					?>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Toggle_Menu_Widget() );
