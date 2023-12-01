<?php
/**
 * Elementor Nav Menu Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Nav Menu widget.
 */
class Woostify_Elementor_Nav_Menu_Widget extends Widget_Base {
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
		return 'woostify-nav-menu';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Nav Menu', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
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
		return array( 'woostify', 'nav', 'menu' );
	}

	/**
	 * Get menu list
	 */
	private function get_nav_list() {
		$menus = wp_get_nav_menus();

		$options = array(
			'none' => __( 'None', 'woostify-pro' ),
		);

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Menu id.
		$this->add_control(
			'menu_id',
			array(
				'label'   => __( 'Select Menu', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_nav_list(),
			)
		);

		// Layout.
		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'Horizontal', 'woostify-pro' ),
					'vertical'   => __( 'Vertical', 'woostify-pro' ),
					'dropdown'   => __( 'Dropdown', 'woostify-pro' ),
				),
			)
		);

		// Pointer.
		$this->add_control(
			'pointer',
			array(
				'label'   => __( 'Pointer', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'      => __( 'None', 'woostify-pro' ),
					'underline' => __( 'Underline', 'woostify-pro' ),
					'overline'  => __( 'OverLine', 'woostify-pro' ),
				),
			)
		);

		// Color Pointer.
		$this->add_control(
			'pointer_color',
			array(
				'label'     => __( 'Pointer Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .envent-pointer-color > ul > li > a:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Indicator.
		$this->add_control(
			'indicator',
			array(
				'label'   => __( 'Indicator ', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'    => __( 'None', 'woostify-pro' ),
					'classic' => __( 'Classic', 'woostify-pro' ),
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
					'{{WRAPPER}} .woostify-nav-menu-widget' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Parent menu
	 */
	public function parent_menu() {

		$this->start_controls_section(
			'parent',
			array(
				'label'     => __( 'Parent Menu', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'menu_id!' => 'none',
				),
			)
		);

		// Padding.
		$this->add_responsive_control(
			'parent_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_responsive_control(
			'parent_margin',
			array(
				'type'               => Controls_Manager::DIMENSIONS,
				'label'              => esc_html__( 'Margin', 'woostify-pro' ),
				'size_units'         => array( 'px', 'em' ),
				'allowed_dimensions' => array( 'left', 'right' ),
				'separator'          => 'after',
				'selectors'          => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'margin: 0px {{RIGHT}}{{UNIT}} 0px {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'name'     => 'parent_typo',
				'selector' => '{{WRAPPER}} .primary-navigation > li > a',
			)
		);

		// Border width.
		$this->add_responsive_control(
			'parent_menu_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		// Border radius.
		$this->add_responsive_control(
			'parent_menu_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'parent_menu_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'woostify-pro' ),
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'border-style: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'parent_menu_item' );

		// Normal.
		$this->start_controls_tab(
			'prent_menu_item_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_responsive_control(
			'prent_menu_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_responsive_control(
			'parent_menu_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_responsive_control(
			'prent_menu_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'prent_menu_item_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_responsive_control(
			'prent_menu_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a:hover'                        => 'color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.menu-item-has-children:hover > a' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_responsive_control(
			'prent_menu_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a:hover'                        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.menu-item-has-children:hover > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_responsive_control(
			'prent_menu_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li > a:hover'                        => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.menu-item-has-children:hover > a' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();

		// Active.
		$this->start_controls_tab(
			'prent_menu_item_active',
			array(
				'label' => __( 'Active', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_responsive_control(
			'prent_menu_text_color_active',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li.current-menu-item > a'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-ancestor > a'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-parent > a'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_parent > a'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_ancestor > a'        => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_responsive_control(
			'parent_menu_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation > li.current-menu-item > a'            => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-ancestor > a'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-parent > a'          => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_parent > a'          => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_ancestor > a'        => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_responsive_control(
			'prent_menu_border_color_active',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation li.current-menu-item > a'              => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-ancestor > a'        => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current-menu-parent > a'          => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_parent > a'          => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .primary-navigation > li.current_page_ancestor > a'        => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Sub menu
	 */
	public function sub_menu() {

		$this->start_controls_section(
			'sub',
			array(
				'label'     => __( 'Sub Menu', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'menu_id!' => 'none',
				),
			)
		);

		// Box-shaodow.
		$this->add_control(
			'sub_box_shadow',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Box Shadow', 'woostify-pro' ),
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'after',
			)
		);

		// Padding.
		$this->add_responsive_control(
			'sub_padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'name'     => 'sub_typo',
				'selector' => '{{WRAPPER}} .primary-navigation ul a',
			)
		);

		// Border width.
		$this->add_responsive_control(
			'sub_menu_border_width',
			array(
				'label'      => __( 'Border Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		// Border radius.
		$this->add_responsive_control(
			'sub_menu_border_radius',
			array(
				'label'      => __( 'Border Radius', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Border style.
		$this->add_control(
			'sub_menu_border_style',
			array(
				'label'     => __( 'Border Style', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => __( 'None', 'woostify-pro' ),
					'solid'  => __( 'Solid', 'woostify-pro' ),
					'dashed' => __( 'Dashed', 'woostify-pro' ),
					'dotted' => __( 'Dotted', 'woostify-pro' ),
					'double' => __( 'Double', 'woostify-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'border-style: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'sub_menu_item' );

		// Normal.
		$this->start_controls_tab(
			'sub_menu_item_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_responsive_control(
			'sub_menu_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'color: {{VALUE}};',
				),
			)
		);

		// BG color.
		$this->add_responsive_control(
			'sub_menu_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Border color.
		$this->add_responsive_control(
			'sub_menu_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'sub_menu_item_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_responsive_control(
			'sub_menu_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		// Hover BG color.
		$this->add_responsive_control(
			'sub_menu_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Hover border color.
		$this->add_responsive_control(
			'sub_menu_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .primary-navigation ul a:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Sidebar
	 */
	public function sidebar_menu() {
		$this->start_controls_section(
			'sidebar_menu',
			array(
				'label'     => __( 'Sidebar Menu', 'woostify-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'menu_id!' => 'none',
				),
			)
		);

		// Note.
		$this->add_control(
			'sidebar_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This option is appear only mobile.', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		// Icon bar.
		$this->add_control(
			'icon_bar_color',
			array(
				'label'     => __( 'Icon Bar Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-icon-bar span' => 'background-color: {{VALUE}};',
				),
			)
		);

		// Menu position.
		$this->add_control(
			'sidebar_position',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Position', 'woostify-pro' ),
				'description' => __( 'Sidebar slide on Left or Right', 'woostify-pro' ),
				'options'     => array(
					'left'  => __( 'Left', 'woostify-pro' ),
					'right' => __( 'Right', 'woostify-pro' ),
				),
				'default'     => 'left',
			)
		);

		// Search form.
		$this->add_control(
			'sidebar_search',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Search Form', 'woostify-pro' ),
				'label_on'     => __( 'Show', 'woostify-pro' ),
				'label_off'    => __( 'Hide', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Logout or Login.
		$this->add_control(
			'sidebar_login_logout',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => __( 'Login / Logout', 'woostify-pro' ),
				'label_on'     => __( 'Show', 'woostify-pro' ),
				'label_off'    => __( 'Hide', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
		$this->parent_menu();
		$this->sub_menu();
		$this->sidebar_menu();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings  = $this->get_settings_for_display();
		$position  = 'none' !== $settings['menu_id'] ? $settings['sidebar_position'] : '';
		$pointer   = $settings['pointer'];
		$indicator = $settings['indicator'];
		$class[]   = 'menu-layout-' . $settings['layout'];
		$class[]   = 'yes' !== $settings['sub_box_shadow'] ? 'no-box-shadow' : '';
		$class     = implode( ' ', $class );
		?>
		<div class="woostify-nav-menu-widget <?php echo esc_attr( $class ); ?>" data-menu-position="<?php echo esc_attr( $position ); ?>">
			<span class="woostify-toggle-nav-menu-button woostify-icon-bar" arial-label="<?php esc_attr_e( 'Toogle Menu', 'woostify-pro' ); ?>"><span></span></span>
			<span class="woostify-close-nav-menu-button" arial-label="<?php esc_attr_e( 'Close Menu', 'woostify-pro' ); ?>"></span>
			<span class="woostify-nav-menu-overlay"></span>

			<div class="woostify-nav-menu-inner">
				<?php
				if ( 'yes' === $settings['sidebar_search'] ) {
					woostify_search();
				}
				?>

				<nav class="main-navigation envent-pointer-color envent-pointer-<?php echo esc_attr( $pointer ); ?> style-indicator-<?php echo esc_attr( $indicator ); ?>" aria-label="<?php esc_attr_e( 'Primary navigation', 'woostify-pro' ); ?>">
					<?php
					if ( 'none' !== $settings['menu_id'] ) {
						$args = array(
							'menu'           => $settings['menu_id'],
							'container'      => '',
							'menu_class'     => 'primary-navigation',
							'theme_location' => '__faker',
							'walker'         => new \Woostify_Walker_Menu(),
						);

						wp_nav_menu( $args );
					} elseif ( is_user_logged_in() ) {
						?>
						<a class="add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Primary Menu', 'woostify-pro' ); ?></a>
					<?php } ?>
				</nav>

				<?php
				if ( 'yes' === $settings['sidebar_login_logout'] ) {
					woostify_sidebar_menu_action();
				}
				?>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Nav_Menu_Widget() );
