<?php
/**
 * Elementor My Account Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class widget.
 */
class Woostify_My_Account extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-my-account-page' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-my-account';
	}

	/**
	 * Style
	 */
	public function get_style_depends() {
		return array( 'elementor-font-awesome' );
	}

	/**
	 * Script
	 */
	public function get_script_depends() {
		return array( 'woostify-my-account-widget' );
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - My Account', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-navigator';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'account', 'user', 'store' );
	}

	/**
	 * Get saved tempalte
	 */
	public function get_saved_tempalte() {
		$arr         = woostify_narrow_data( 'post', 'elementor_library' );
		$arr['none'] = __( 'None', 'woostify-pro' );

		return $arr;
	}

	/**
	 * Get menu items
	 */
	public function get_menu_items() {
		$arr           = wc_get_account_menu_items();
		$arr['custom'] = __( 'Custom', 'woostify-pro' );

		return $arr;
	}

	/**
	 * Navigation
	 */
	protected function navigation() {
		$this->start_controls_section(
			'repeater',
			array(
				'label' => __( 'Navigation', 'woostify-pro' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'nav_title',
			array(
				'label'       => __( 'Title', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Dashboard', 'woostify-pro' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'nav_item',
			array(
				'label'   => __( 'Menu Item', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dashboard',
				'options' => $this->get_menu_items(),
			)
		);

		$repeater->add_control(
			'custom_tempate',
			array(
				'label'     => __( 'Template', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => $this->get_saved_tempalte(),
				'condition' => array(
					'nav_item' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'nav_url',
			array(
				'label'       => __( 'Url', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '#', 'woostify-pro' ),
				'label_block' => true,
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'nav_item',
							'operator' => '===',
							'value'    => 'custom',
						),
						array(
							'name'     => 'custom_tempate',
							'operator' => '===',
							'value'    => 'none',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'nav_icon',
			array(
				'label'   => __( 'Icon', 'woostify-pro' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value' => 'fas fa-shopping-cart',
				),
			)
		);

		$this->add_control(
			'navigation',
			array(
				'show_label'  => false,
				'title_field' => '{{{ nav_title }}}',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'nav_title' => __( 'Dashboard', 'woostify-pro' ),
						'nav_item'  => 'dashboard',
						'nav_icon'  => array(
							'value' => 'fas fa-cogs',
						),
					),
					array(
						'nav_title' => __( 'Orders', 'woostify-pro' ),
						'nav_item'  => 'orders',
						'nav_icon'  => array(
							'value' => 'fas fa-list-ul',
						),
					),
					array(
						'nav_title' => __( 'Download', 'woostify-pro' ),
						'nav_item'  => 'downloads',
						'nav_icon'  => array(
							'value' => 'fas fa-download',
						),
					),
					array(
						'nav_title' => __( 'Address', 'woostify-pro' ),
						'nav_item'  => 'edit-address',
						'nav_icon'  => array(
							'value' => 'fas fa-address-book',
						),
					),
					array(
						'nav_title' => __( 'Account Details', 'woostify-pro' ),
						'nav_item'  => 'edit-account',
						'nav_icon'  => array(
							'value' => 'fas fa-users-cog',
						),
					),
					array(
						'nav_title' => __( 'Logout', 'woostify-pro' ),
						'nav_item'  => 'customer-logout',
						'nav_icon'  => array(
							'value' => 'fas fa-sign-out-alt',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Menu items
	 */
	protected function menu_items() {
		// Start.
		$this->start_controls_section(
			'start',
			array(
				'label' => __( 'Tab Head', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'head_position',
			array(
				'label'   => __( 'Position', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'    => __( 'Top', 'woostify-pro' ),
					'right'  => __( 'Right', 'woostify-pro' ),
					'bottom' => __( 'Bottom', 'woostify-pro' ),
					'left'   => __( 'Left', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'   => __( 'Icon Position', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => __( 'Left', 'woostify-pro' ),
					'right' => __( 'Right', 'woostify-pro' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'      => __( 'Icon Space', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .has-icon-left .account-menu-item-icon'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .has-icon-right .account-menu-item-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_width',
			array(
				'label'      => __( 'Width', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'head_position',
							'operator' => '===',
							'value'    => 'left',
						),
						array(
							'name'     => 'head_position',
							'operator' => '===',
							'value'    => 'right',
						),
					),
				),
				'range'      => array(
					'px' => array(
						'min'  => 200,
						'max'  => 500,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 70,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-my-account-tab-head' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'head_inline',
			array(
				'label'        => __( 'Inline display', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'head_position',
							'operator' => '===',
							'value'    => 'top',
						),
						array(
							'name'     => 'head_position',
							'operator' => '===',
							'value'    => 'bottom',
						),
					),
				),
			)
		);

		$this->add_control(
			'head_bg',
			array(
				'label'     => __( 'Background color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-head' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'head_align',
			array(
				'label'     => __( 'Alignment', 'woostify-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-head' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-my-account-tab-head' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'for_menu_items',
			array(
				'label'     => __( 'Menu Items', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Text color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .account-menu-item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => __( 'Highlight color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .account-menu-item a:hover, {{WRAPPER}} .account-menu-item.active a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_head_typo',
				'selector' => '{{WRAPPER}} .account-menu-item a',
			)
		);

		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .account-menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Tab content
	 */
	protected function tab_content() {
		$this->start_controls_section(
			'tab_content',
			array(
				'label' => __( 'Tab Content', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'tab_content_bg',
			array(
				'label'     => __( 'Background color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-content' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'tab_content_align',
			array(
				'label'     => __( 'Alignment', 'woostify-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_content_padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woostify-my-account-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_content_margin',
			array(
				'label'      => __( 'Margin', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'after',
				'selectors'  => array(
					'{{WRAPPER}} .woostify-my-account-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'tab_content_color',
			array(
				'label'     => __( 'Text color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_content_link_color',
			array(
				'label'     => __( 'Link color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-content a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_content_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-my-account-tab-content a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_content_typo',
				'selector' => '{{WRAPPER}} .woostify-my-account-tab-content',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->navigation();
		$this->menu_items();
		$this->tab_content();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['navigation'] ) ) {
			return;
		}

		$head_inline = in_array( $settings['head_position'], array( 'top', 'bottom' ), true ) && $settings['head_inline'] ? ' head-inline' : '';
		?>
		<div class="woostify-my-account-widget position-<?php echo esc_attr( $settings['head_position'] ); ?><?php echo esc_attr( $head_inline ); ?>">
			<div class="woostify-my-account-tab-head">
				<?php
				foreach ( $settings['navigation'] as $k => $v ) {
					$url = isset( $v['nav_item'] ) ? wc_get_account_endpoint_url( $v['nav_item'] ) : '#';
					if ( 'custom' === $v['nav_item'] ) {
						$url = isset( $v['nav_url'] ) ? $v['nav_url'] : '#';
					}
					$icon          = isset( $v['nav_icon'] ) && ! empty( $v['nav_icon']['value'] ) ? $v['nav_icon']['value'] : '';
					$icon_position = 'has-icon-left';
					$label         = $v['nav_title'];

					if ( $icon && 'left' === $settings['icon_position'] ) {
						$label = '<span class="account-menu-item-icon ' . esc_attr( $icon ) . '"></span>' . $v['nav_title'];
					} elseif ( $icon && 'right' === $settings['icon_position'] ) {
						$icon_position = 'has-icon-right';
						$label         = $v['nav_title'] . '<span class="account-menu-item-icon ' . esc_attr( $icon ) . '"></span>';
					}
					?>
					<div class="account-menu-item account-menu-item-<?php echo esc_attr( $v['nav_item'] ); ?><?php echo esc_attr( 0 === $k ? ' active' : '' ); ?> <?php echo esc_attr( $icon_position ); ?>">
						<a data-id="tab-<?php echo esc_attr( $v['_id'] ); ?>" href="<?php echo esc_url( $url ); ?>">
							<?php echo wp_kses_post( $label ); ?>
						</a>
					</div>
				<?php } ?>
			</div>

			<div class="woostify-my-account-tab-content">
				<?php
				$my_account = new \WC_Shortcode_My_Account();
				foreach ( $settings['navigation'] as $k => $v ) {
					if ( 'customer-logout' === $v['nav_item'] || ( 'custom' === $v['nav_item'] && 'none' === $v['custom_tempate'] ) ) {
						continue;
					}
					?>
					<div class="my-account-tab-content-item<?php echo esc_attr( 0 === $k ? ' active' : '' ); ?>" id="tab-<?php echo esc_attr( $v['_id'] ); ?>">
						<?php
						switch ( $v['nav_item'] ) {
							case 'dashboard':
								woocommerce_account_content();
								break;
							case 'orders':
								woocommerce_account_orders( 1 );
								break;
							case 'downloads':
								if ( WC()->customer ) {
									woocommerce_account_downloads();
								} else {
									?>
									<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
										<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
											<?php esc_html_e( 'Browse products', 'woostify-pro' ); ?>
										</a>
										<?php esc_html_e( 'No downloads available yet.', 'woostify-pro' ); ?>
									</div>
									<?php
								}
								break;
							case 'edit-address':
								$my_account->edit_address();
								break;
							case 'edit-account':
								$my_account->edit_account();
								break;
							case 'payment-methods':
								woocommerce_account_payment_methods();
								break;
							case 'refund-requests':
								if ( class_exists( 'YITH_Advanced_Refund_System_My_Account' ) ) {
									$refund = new \YITH_Advanced_Refund_System_My_Account();
									$refund->my_refund_requests_content();
								}
								break;
							case 'points-and-rewards':
								if ( function_exists( 'woocommerce_points_rewards_my_points' ) ) {
									woocommerce_points_rewards_my_points( 1 );
								}
								break;
							case 'custom':
								$frontend = new \Elementor\Frontend();
								echo $frontend->get_builder_content_for_display( $v['custom_tempate'], true ); // phpcs:ignore
								wp_reset_postdata();
								break;
						}
						?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_My_Account() );
