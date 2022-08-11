<?php
/**
 * Woostify Multiple Header Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Multiple_Header' ) ) {
	/**
	 * Woostify Multiple Header Class
	 */
	class Woostify_Multiple_Header {
		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			add_action( 'wp', array( $this, 'wp_action' ) );
			add_action( 'customize_register', array( $this, 'register_customizer' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
			add_filter( 'woostify_has_header_layout_classes', array( $this, 'add_body_classes' ) );
			add_filter( 'woostify_header_layout_classes', array( $this, 'add_header_classes' ) );
			add_filter( 'woostify_setting_header_layout_choices', array( $this, 'update_header_layout_option' ) );
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_total_price_fragments' ) );

			// Header shortcode.
			add_shortcode( 'header_content_block', array( $this, 'header_content_block' ) );
			add_shortcode( 'header_single_block', array( $this, 'header_single_block' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_MULTIPLE_HEADER' ) ) {
				define( 'WOOSTIFY_PRO_MULTIPLE_HEADER', WOOSTIFY_PRO_VERSION );
			}

			define( 'WOOSTIFY_PRO_MULTIPLE_HEADER_URI', WOOSTIFY_PRO_URI . 'modules/multiple-header/' );
		}

		/**
		 * WP action
		 */
		public function wp_action() {
			$options_free    = woostify_options( false );
			$header_layout   = $options_free['header_layout'];
			$header_template = get_option( 'woostify_header_template_id' );

			if ( 'layout-1' === $header_layout || ( defined( 'WOOSTIFY_PRO_HEADER_FOOTER_BUILDER' ) && $header_template ) ) {
				return;
			}

			switch ( $header_layout ) {
				default:
				case 'layout-2':
					remove_action( 'woostify_site_header', 'woostify_menu_toggle_btn', 10 );
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );

					// Wrap toggle sidebar button.
					add_action( 'woostify_site_header', array( $this, 'layout_2_content_left' ), 10 );
					add_action( 'woostify_pro_header_layout_2_content_left', 'woostify_menu_toggle_btn' );
					break;
				case 'layout-3':
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );

					// Add left content.
					add_action( 'woostify_site_header', array( $this, 'layout_3_content_left' ), 10 );

					// Add nav box.
					add_action( 'woostify_site_header', array( $this, 'layout_3_main_nav' ), 220 );
					add_action( 'woostify_pro_header_layout_3_main_nav', 'woostify_primary_navigation', 220 );
					break;
				case 'layout-4':
					// Swap priority hooks.
					remove_action( 'woostify_site_header', 'woostify_site_branding', 20 );
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );
					add_action( 'woostify_site_header', 'woostify_primary_navigation', 20 );
					add_action( 'woostify_site_header', 'woostify_site_branding', 30 );
					break;
				case 'layout-5':
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );

					// Add left content.
					add_action( 'woostify_site_header', array( $this, 'layout_5_content_center' ), 30 );

					// Add nav box.
					add_action( 'woostify_site_header', array( $this, 'layout_5_main_nav' ), 220 );
					add_action( 'woostify_pro_header_layout_5_main_nav', 'woostify_primary_navigation', 220 );
					break;
				case 'layout-6':
					remove_action( 'woostify_site_header', 'woostify_default_container_open', 0 );
					remove_action( 'woostify_site_header', 'woostify_menu_toggle_btn', 10 );
					remove_action( 'woostify_site_header', 'woostify_site_branding', 20 );
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );
					remove_action( 'woostify_site_header', 'woostify_header_action', 50 );
					remove_action( 'woostify_site_header', 'woostify_default_container_close', 200 );

					add_action( 'woostify_site_header', array( $this, 'layout_6_content_top' ), 10 );
					add_action( 'woostify_site_header', array( $this, 'layout_6_content_bottom' ), 20 );

					add_action( 'woostify_pro_header_layout_6_content_top', 'woostify_menu_toggle_btn', 10 );
					add_action( 'woostify_pro_header_layout_6_content_top', 'woostify_site_branding', 20 );
					add_action( 'woostify_pro_header_layout_6_content_top', 'woostify_search', 30 );
					add_action( 'woostify_pro_header_layout_6_content_top', array( $this, 'header_layout_6_content_top_right' ), 40 );

					add_action( 'woostify_pro_header_layout_6_content_bottom', 'woostify_primary_navigation', 10 );
					add_action( 'woostify_pro_header_layout_6_content_bottom', array( $this, 'header_layout_6_content_bottm_right' ), 20 );
					break;
				case 'layout-7':
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );
					remove_action( 'woostify_toggle_sidebar', 'woostify_sidebar_menu_action', 40 );

					add_action( 'woostify_toggle_sidebar', array( $this, 'layout_7_content_bottom' ), 40 );
					add_action( 'woostify_toggle_sidebar', 'woostify_site_branding', 15 );
					add_action( 'woostify_toggle_sidebar', 'woostify_header_action', 25 );
					break;
				case 'layout-8':
					remove_action( 'woostify_site_header', 'woostify_default_container_open', 0 );
					remove_action( 'woostify_site_header', 'woostify_menu_toggle_btn', 10 );
					remove_action( 'woostify_site_header', 'woostify_site_branding', 20 );
					remove_action( 'woostify_site_header', 'woostify_primary_navigation', 30 );
					remove_action( 'woostify_site_header', 'woostify_header_action', 50 );
					remove_action( 'woostify_site_header', 'woostify_default_container_close', 200 );

					add_action( 'woostify_site_header', array( $this, 'layout_8_content_top' ), 10 );
					add_action( 'woostify_site_header', array( $this, 'layout_8_content_bottom' ), 20 );

					add_action( 'woostify_pro_header_layout_8_content_top', 'woostify_menu_toggle_btn', 10 );
					add_action( 'woostify_pro_header_layout_8_content_top', 'woostify_site_branding', 20 );
					add_action( 'woostify_pro_header_layout_8_content_top', 'woostify_primary_navigation', 30 );
					add_action( 'woostify_pro_header_layout_8_content_top', 'woostify_header_action', 40 );
					add_action( 'woostify_pro_header_layout_8_content_top', array( $this, 'header_layout_8_content_top_right' ), 50 );

					add_action( 'woostify_pro_header_layout_8_content_bottom', array( $this, 'woostify_vertical_menu' ), 10 );
					add_action( 'woostify_pro_header_layout_8_content_bottom', 'woostify_search', 20 );
					add_action( 'woostify_pro_header_layout_8_content_bottom', 'woostify_header_action', 30 );
					break;
			}
		}

		/**
		 * Left content
		 * Header Layout 2
		 */
		public function layout_2_content_left() {
			?>
			<div class="left-content">
				<?php do_action( 'woostify_pro_header_layout_2_content_left' ); ?>
			</div>
			<?php
		}

		/**
		 * Left content
		 * Header Layout 3
		 */
		public function layout_3_content_left() {
			$options_pro  = $this->options_pro();
			$left_content = $options_pro['header_left_content'];
			?>
			<div class="left-content">
				<?php echo do_shortcode( $left_content ); ?>
			</div>
			<?php
		}

		/**
		 * Main Nav
		 * Header Layout 3
		 */
		public function layout_3_main_nav() {
			?>
			<div class="navigation-box">
				<div class="navigation-box-inner">
					<div class="woostify-container">
						<?php do_action( 'woostify_pro_header_layout_3_main_nav' ); ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Center content
		 * Header Layout 5
		 */
		public function layout_5_content_center() {
			$options_pro    = $this->options_pro();
			$center_content = $options_pro['header_center_content'];
			?>
			<div class="center-content">
				<?php echo do_shortcode( $center_content ); ?>
			</div>
			<?php
		}

		/**
		 * Main Nav
		 * Header Layout 5
		 */
		public function layout_5_main_nav() {
			?>
			<div class="navigation-box">
				<div class="navigation-box-inner">
					<div class="woostify-container">
						<?php do_action( 'woostify_pro_header_layout_5_main_nav' ); ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Content top
		 * Header Layout 6
		 */
		public function layout_6_content_top() {
			?>
			<div class="header-content-top">
				<div class="woostify-container">
					<?php do_action( 'woostify_pro_header_layout_6_content_top' ); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Header 6 content top right
		 */
		public function header_layout_6_content_top_right() {
			$options_pro = $this->options_pro();

			if ( woostify_is_woocommerce_activated() ) {
				$shop_bag_icon = apply_filters( 'woostify_pro_header_shop_bag_icon', 'ti-shopping-cart' );
				$count         = WC()->cart->cart_contents_count;
				$price         = WC()->cart->get_cart_total();
				?>

				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="shopping-bag-button <?php echo esc_attr( $shop_bag_icon ); ?>">
					<span class="shop-cart-count"><?php echo esc_html( $count ); ?></span>
				</a>
				<?php
			}

			if ( ! $options_pro['header_right_content'] ) {
				return;
			}
			?>

			<div class="content-top-right"><?php echo wp_kses_post( do_shortcode( $options_pro['header_right_content'] ) ); ?></div>
			<?php
		}

		/**
		 * Header 6 content bottom right
		 */
		public function header_layout_6_content_bottm_right() {
			$options = woostify_options( false );
			if ( ! woostify_is_woocommerce_activated() || ! $options['header_shop_cart_icon'] ) {
				return;
			}

			$shop_bag_icon = apply_filters( 'woostify_pro_header_shop_bag_icon', 'ti-shopping-cart' );
			$count         = WC()->cart->cart_contents_count;
			$price         = WC()->cart->get_cart_total();
			?>

			<div class="content-bottom-right woostify-custom-cart">
				<span class="woostify-total-price"><?php echo wp_kses_post( $price ); ?></span>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="shopping-bag-button <?php echo esc_attr( $shop_bag_icon ); ?>">
					<span class="shop-cart-count"><?php echo esc_html( $count ); ?></span>
				</a>
			</div>
			<?php
		}

		/**
		 * Content bottom
		 * Header Layout 6
		 */
		public function layout_6_content_bottom() {
			?>
			<div class="header-content-bottom">
				<div class="woostify-container">
					<?php do_action( 'woostify_pro_header_layout_6_content_bottom' ); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Sidebar Bottom
		 * Header Layout 7
		 */
		public function layout_7_content_bottom() {
			$options_pro = $this->options_pro();
			?>
			<div class="sidebar-content-bottom">
				<?php echo wp_kses_post( $options_pro['header_sidebar_content_bottom'] ); ?>
			</div>
			<?php
		}

		/**
		 * Header Layout 8
		 * Content top
		 */
		public function layout_8_content_top() {
			?>
			<div class="header-content-top">
				<div class="woostify-container">
					<?php do_action( 'woostify_pro_header_layout_8_content_top' ); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Header Layout 8
		 * Content top right
		 */
		public function header_layout_8_content_top_right() {
			$options_pro = $this->options_pro();

			if ( ! $options_pro['header_8_right_content'] ) {
				return;
			}
			?>

			<div class="content-top-right"><?php echo wp_kses_post( do_shortcode( $options_pro['header_8_right_content'] ) ); ?></div>
			<?php
		}

		/**
		 * Header Layout 8
		 * Content bottom
		 */
		public function layout_8_content_bottom() {
			?>
			<div class="header-content-bottom">
				<div class="woostify-container">
					<?php do_action( 'woostify_pro_header_layout_8_content_bottom' ); ?>
				</div>
			</div>
			<?php
		}

		/**
		 * Header Layout 8
		 * Vertical menu
		 */
		public function woostify_vertical_menu() {
			$has_menu    = has_nav_menu( 'vertical' );
			$options_pro = $this->options_pro();

			if ( ! $has_menu && ! is_user_logged_in() ) {
				return;
			}
			?>

			<div class="vertical-menu-wrapper">
				<div class="toggle-vertical-menu-wrapper">
					<?php if ( $has_menu ) { ?>
						<button class="vertical-menu-button toggle-vertical-menu-button"><?php echo esc_html( $options_pro['header_8_button_text'] ); ?></button>
					<?php } else { ?>
						<a class="vertical-menu-button add-menu" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add a Vertical Menu', 'woostify-pro' ); ?></a>
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
									'walker'         => new Woostify_Walker_Menu(),
								)
							);
						?>
					</div>
				<?php } ?>
			</div>
			<?php
		}

		/**
		 * Update cart total price via ajax
		 *
		 * @param      array $fragments Fragments to refresh via AJAX.
		 * @return     array $fragments Fragments to refresh via AJAX
		 */
		public function cart_total_price_fragments( $fragments ) {
			$total = WC()->cart->get_cart_total();

			ob_start();
			?>
				<span class="woostify-total-price"><?php echo wp_kses_post( $total ); ?></span>
			<?php

			$fragments['span.woostify-total-price'] = ob_get_clean();

			return $fragments;
		}

		/**
		 * Add shortcode
		 *
		 * @param array $atts The atts.
		 */
		public function header_content_block( $atts ) {
			$defaults = array(
				'my_account'       => true,
				'my_account_url'   => '#',
				'my_account_label' => __( 'My Account', 'woostify-pro' ),

				'support'          => true,
				'support_url'      => '#',
				'support_label'    => __( 'Customer Help', 'woostify-pro' ),

				'checkout'         => true,
				'checkout_url'     => '#',
				'checkout_label'   => __( 'Checkout', 'woostify-pro' ),
			);

			if ( woostify_is_woocommerce_activated() ) {
				$defaults['my_account_url'] = wc_get_page_permalink( 'myaccount' );
				$defaults['checkout_url']   = wc_get_page_permalink( 'checkout' );
			}

			$atts = shortcode_atts( $defaults, $atts );

			ob_start();
			?>

			<div class="header-content-block">
				<?php
				if ( filter_var( $atts['my_account'], FILTER_VALIDATE_BOOLEAN ) && woostify_is_woocommerce_activated() ) {
					$account_icon     = apply_filters( 'woostify_header_my_account_icon', 'ti-user' );
					$my_account_url   = $atts['my_account_url'];
					$my_account_label = $atts['my_account_label'];
					?>
					<a class="header-block-item" href="<?php echo esc_url( $my_account_url ); ?>">
						<span class="header-block-item-icon <?php echo esc_attr( $account_icon ); ?>"></span>
						<span class="header-block-item-label"><?php echo esc_html( $my_account_label ); ?></span>
					</a>
					<?php
				}

				if ( filter_var( $atts['support'], FILTER_VALIDATE_BOOLEAN ) ) {
					$support_icon  = apply_filters( 'woostify_header_support_icon', 'ti-face-smile' );
					$support_url   = $atts['support_url'];
					$support_label = $atts['support_label'];
					?>
					<a class="header-block-item" href="<?php echo esc_url( $support_url ); ?>">
						<span class="header-block-item-icon <?php echo esc_attr( $support_icon ); ?>"></span>
						<span class="header-block-item-label"><?php echo esc_html( $support_label ); ?></span>
					</a>
					<?php
				}

				if ( filter_var( $atts['checkout'], FILTER_VALIDATE_BOOLEAN ) && woostify_is_woocommerce_activated() ) {
					$checkout_icon  = apply_filters( 'woostify_header_checkout_icon', 'ti-arrow-circle-right' );
					$checkout_url   = $atts['checkout_url'];
					$checkout_label = $atts['checkout_label'];
					?>
					<a class="header-block-item" href="<?php echo esc_url( $checkout_url ); ?>">
						<span class="header-block-item-icon <?php echo esc_attr( $checkout_icon ); ?>"></span>
						<span class="header-block-item-label"><?php echo esc_html( $checkout_label ); ?></span>
					</a>
					<?php
				}
				?>
			</div>

			<?php
			return ob_get_clean();
		}

		/**
		 * Add shortcode
		 *
		 * @param array $atts The atts.
		 */
		public function header_single_block( $atts ) {
			$defaults = array(
				'icon'       => 'ti-headphone-alt',
				'icon_color' => '',
				'heading'    => __( '(+245)-1802-2019', 'woostify-pro' ),
				'href'       => '',
				'text'       => '',
			);

			$atts = shortcode_atts( $defaults, $atts );

			ob_start();
			$heading    = woostify_sanitize_raw_html( $atts['heading'] );
			$icon_color = '' !== $atts['icon_color'] ? 'style="color: ' . esc_attr( $atts['icon_color'] ) . '"' : '';
			$href       = '<a href="' . esc_url( $atts['href'] ) . '">' . esc_html( $atts['href'] ) . '</a>';
			$text       = ( '' === $atts['href'] && '' !== $atts['text'] ) ? woostify_sanitize_raw_html( $atts['text'] ) : $href;
			?>

			<div class="header-single-block">
				<span class="header-single-block-icon <?php echo esc_attr( $atts['icon'] ); ?>" <?php echo wp_kses_post( $icon_color ); ?>></span>
				<div class="header-single-block-inner">
					<strong class="header-single-block-heading"><?php echo wp_kses_post( $heading ); ?></strong>
					<div class="header-single-block-text"><?php echo wp_kses_post( $text ); ?></div>
				</div>
			</div>

			<?php
			return ob_get_clean();
		}

		/**
		 * Gets default options.
		 *
		 * @return     Woostify  The default options.
		 */
		public function options_default() {
			$defaults = Woostify_Pro::get_instance()->default_options_value();
			return $defaults;
		}

		/**
		 * Gets the options.
		 *
		 * @return     Woostify  The options.
		 */
		public function options_pro() {
			$options = Woostify_Pro::get_instance()->woostify_pro_options();
			return $options;
		}

		/**
		 * Add Body class
		 */
		public function add_body_classes() {
			$options        = woostify_options( false );
			$layout         = $options['header_layout'];
			$header_builder = get_option( 'woostify_header_template_id' );

			if ( defined( 'WOOSTIFY_PRO_HEADER_FOOTER_BUILDER' ) && $header_builder ) {
				return '';
			}

			$classes = 'has-header-' . $layout;

			return $classes;
		}

		/**
		 * Add Header class
		 */
		public function add_header_classes() {
			$options_pro   = $this->options_pro();
			$options_free  = woostify_options( false );
			$header_layout = $options_free['header_layout'];
			$classes       = 'header-' . $header_layout;

			if ( 'layout-1' === $header_layout && $options_pro['header_full_width'] ) {
				$classes .= ' header-full-width';
			} elseif ( in_array( $header_layout, array( 'layout-3', 'layout-5' ), true ) ) {
				$classes .= ' has-navigation-box';
			}

			return $classes;
		}

		/**
		 * Sets up.
		 */
		public function scripts() {
			$options_free  = woostify_options( false );
			$options_pro   = $this->options_pro();
			$header_layout = $options_free['header_layout'];
			$screen_width  = $options_free['header_menu_breakpoint'];

			// Multiple header style.
			wp_enqueue_style(
				'woostify-pro-header-layout',
				WOOSTIFY_PRO_URI . 'modules/multiple-header/css/header-layout.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				@media ( min-width: ' . esc_attr( $screen_width + 1 ) . 'px ) {
					.header-layout-6 .header-content-bottom{
						background-color: ' . esc_attr( $options_pro['header_content_bottom_background'] ) . ';
					}

					.woostify-total-price,
					.shopping-bag-button,
					.my-account-icon,
					.header-search-icon {
						color: ' . esc_attr( $options_free['primary_menu_color'] ) . ';
					}

					.header-layout-8 .vertical-menu-wrapper .vertical-menu-button {
						background-color: ' . esc_attr( $options_pro['header_8_button_background'] ) . ';
						color: ' . esc_attr( $options_pro['header_8_button_color'] ) . ';
					}

					.header-layout-8 .vertical-menu-wrapper .vertical-menu-button:hover {
						background-color: ' . esc_attr( $options_pro['header_8_button_hover_background'] ) . ';
						color: ' . esc_attr( $options_pro['header_8_button_hover_color'] ) . ';
					}

					.header-layout-8 .header-content-bottom {
						background-color: ' . esc_attr( $options_pro['header_8_search_bar_background'] ) . ';
					}

					.header-layout-8 .woostify-total-price,
					.header-layout-8 .tools-icon {
						color: ' . esc_attr( $options_pro['header_8_icon_color'] ) . ';
					}

					.site-header.header-layout-8 .tools-icon:hover,
					.header-layout-8 .tools-icon.my-account:hover > a {
						color: ' . esc_attr( $options_pro['header_8_icon_hover_color'] ) . ';
					}

					.header-layout-8 .content-top-right * {
						color: ' . esc_attr( $options_pro['header_8_content_right_text_color'] ) . ';
					}

					.has-header-layout-7 .sidebar-menu {
						background-color: ' . esc_attr( $options_free['header_background_color'] ) . ';
					}

					.has-header-layout-2 .main-navigation .primary-navigation > li > a {
					    margin-left: 0;
					    margin-right: 0;
					}

					.has-header-layout-4 .header-layout-4 .woostify-container {
					    width: auto;
					    max-width: 100%;
					    padding: 0 70px;
					}

					.has-header-layout-4 .header-layout-4 .wrap-toggle-sidebar-menu {
					    display: none;
					}

					.has-header-layout-5 .header-layout-5 .wrap-toggle-sidebar-menu {
					    display: none;
					}

					.header-layout-6 .wrap-toggle-sidebar-menu,
					.header-layout-6 .header-content-top .shopping-bag-button {
					    display: none;
					}

					.header-layout-6 .site-branding,
					.header-layout-6 .content-top-right {
					    flex-basis: 330px;
					}

					.has-header-layout-7 #view {
					    width: calc(100% - 300px);
					    transform: translateX(300px);
					}

					.has-header-layout-7 .sidebar-menu {
					    transform: none;
					    z-index: 198;
					}

					.has-header-layout-7 .sidebar-menu .site-search {
					    display: none;
					}

					.has-header-layout-7 .main-navigation .primary-navigation > li > a {
					    margin-left: 0;
					    margin-right: 0;
					}

					.has-header-layout-7 .main-navigation .primary-navigation > li ul li.menu-item-has-children:after {
					    content: none;
					}

					.has-header-layout-7 .main-navigation .primary-navigation .sub-menu {
					    background-color: transparent;
					}

					.has-header-layout-7 .sidebar-menu .site-search {
					    margin-top: 15px;
					    margin-bottom: 30px;
					}

					.header-layout-7 {
					    display: none;
					}

					.has-header-layout-7 .sidebar-menu .tools-icon .tools-icon {
					    margin-right: 0;
					}

					.has-header-layout-7 .sidebar-menu .site-tools {
					    justify-content: flex-start;
					    margin-top: 15px;
					    margin-bottom: 30px;
					}

					.has-header-layout-7 .sidebar-menu .tools-icon {
					    margin-left: 0;
					    margin-right: 15px;
					    display: block;
					}

					.header-layout-8 .wrap-toggle-sidebar-menu,
					.header-layout-8 .header-search-icon {
					    display: none;
					}

					.header-layout-8 .header-content-top .site-tools {
					    display: none;
					}

					.header-layout-8 .header-content-top .woostify-container {
					    justify-content: space-between;
					}

					.header-layout-8 .header-content-top .wrap-toggle-sidebar-menu,
					.header-layout-8 .header-content-top .site-tools {
					    flex-basis: 50px;
					}

					.has-header-layout-3 .header-layout-3 .wrap-toggle-sidebar-menu {
						display: none;
					}

				}

				@media ( max-width: ' . esc_attr( $screen_width ) . 'px ) {
					.has-header-layout-3 .header-layout-3 .navigation-box, .has-header-layout-3 .header-layout-3 .left-content {
						display: none;
					}

					.has-header-layout-7 .sidebar-menu .site-tools {
					    display: none;
					}

					.header-layout-8 .header-content-top .woostify-container {
					    justify-content: space-between;
					}

				}
			';
			wp_add_inline_style(
				'woostify-pro-header-layout',
				$styles
			);
		}

		/**
		 * Override product style option
		 *
		 * @param array $options The product style option.
		 */
		public function update_header_layout_option( $options ) {
			$options['layout-2'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-2.jpg';
			$options['layout-3'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-3.jpg';
			$options['layout-4'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-4.jpg';
			$options['layout-5'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-5.jpg';
			$options['layout-6'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-6.jpg';
			$options['layout-7'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-7.jpg';
			$options['layout-8'] = WOOSTIFY_PRO_MULTIPLE_HEADER_URI . 'images/woostify-header-8.jpg';

			return $options;
		}

		/**
		 * Register customizer
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function register_customizer( $wp_customize ) {

			// Defaults value.
			$defaults = $this->options_default();

			// LAYOUT 1.
			// Full width.
			$wp_customize->add_setting(
				'woostify_pro_options[header_full_width]',
				array(
					'default'           => $defaults['header_full_width'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Switch_Control(
					$wp_customize,
					'woostify_pro_options[header_full_width]',
					array(
						'label'    => __( 'Full Width', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_full_width]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// After header fullwidth divider.
			$wp_customize->add_setting(
				'after_header_full_width_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'after_header_full_width_divider',
					array(
						'priority' => 46,
						'section'  => 'woostify_header',
						'settings' => 'after_header_full_width_divider',
						'type'     => 'divider',
					)
				)
			);

			// LAYOUT 3.
			// Left Content.
			$wp_customize->add_setting(
				'woostify_pro_options[header_left_content]',
				array(
					'default'           => $defaults['header_left_content'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_left_content]',
					array(
						'label'    => __( 'Left Content', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_left_content]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'textarea',
					)
				)
			);

			// After header left content divider.
			$wp_customize->add_setting(
				'after_header_left_content_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'after_header_left_content_divider',
					array(
						'priority' => 46,
						'section'  => 'woostify_header',
						'settings' => 'after_header_left_content_divider',
						'type'     => 'divider',
					)
				)
			);

			// LAYOUT 5.
			// Center Content.
			$wp_customize->add_setting(
				'woostify_pro_options[header_center_content]',
				array(
					'default'           => $defaults['header_center_content'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_center_content]',
					array(
						'label'    => __( 'Center Content', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_center_content]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'textarea',
					)
				)
			);

			// After header center content divider.
			$wp_customize->add_setting(
				'after_header_center_content_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'after_header_center_content_divider',
					array(
						'priority' => 46,
						'section'  => 'woostify_header',
						'settings' => 'after_header_center_content_divider',
						'type'     => 'divider',
					)
				)
			);

			// LAYOUT 6.
			// Background Nav section.
			$wp_customize->add_setting(
				'woostify_pro_options[header_content_bottom_background]',
				array(
					'default'           => $defaults['header_content_bottom_background'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_content_bottom_background]',
					array(
						'label'    => __( 'Background Nav Menu', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_content_bottom_background]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Right Content.
			$wp_customize->add_setting(
				'woostify_pro_options[header_right_content]',
				array(
					'default'           => $defaults['header_right_content'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_right_content]',
					array(
						'label'    => __( 'Right Content', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_right_content]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'textarea',
					)
				)
			);

			// LAYOUT 7.
			// Content bottom.
			$wp_customize->add_setting(
				'woostify_pro_options[header_sidebar_content_bottom]',
				array(
					'default'           => $defaults['header_sidebar_content_bottom'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_sidebar_content_bottom]',
					array(
						'label'    => __( 'Content Bottom', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_sidebar_content_bottom]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'textarea',
					)
				)
			);

			// LAYOUT 8.
			// Background search bar menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_search_bar_background]',
				array(
					'default'           => $defaults['header_8_search_bar_background'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_search_bar_background]',
					array(
						'label'    => __( 'Search Bar Background', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_search_bar_background]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Icon Color.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_icon_color]',
				array(
					'default'           => $defaults['header_8_icon_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_icon_color]',
					array(
						'label'    => __( 'Icon Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_icon_color]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Icon Hover Color.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_icon_hover_color]',
				array(
					'default'           => $defaults['header_8_icon_hover_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_icon_hover_color]',
					array(
						'label'    => __( 'Icon Hover Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_icon_hover_color]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Divider.
			$wp_customize->add_setting(
				'header_layout_8_button_text_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'header_layout_8_button_text_divider',
					array(
						'section'  => 'woostify_header',
						'settings' => 'header_layout_8_button_text_divider',
						'type'     => 'divider',
						'priority' => 45,
					)
				)
			);

			// Divider.
			$wp_customize->add_setting(
				'header_layout_8_button_heading_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'header_layout_8_button_heading_divider',
					array(
						'section'  => 'woostify_header',
						'settings' => 'header_layout_8_button_heading_divider',
						'type'     => 'heading',
						'priority' => 45,
						'label'    => __( 'Toggle Menu Button', 'woostify-pro' ),
					)
				)
			);

			// Text: Button toggle vertical menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_button_text]',
				array(
					'default'           => $defaults['header_8_button_text'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_8_button_text]',
					array(
						'label'    => __( 'Text', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_button_text]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'text',
					)
				)
			);

			// Background: Button toggle vertical menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_button_background]',
				array(
					'default'           => $defaults['header_8_button_background'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_button_background]',
					array(
						'label'    => __( 'Background Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_button_background]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Text color: Button toggle vertical menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_button_color]',
				array(
					'default'           => $defaults['header_8_button_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_button_color]',
					array(
						'label'    => __( 'Text Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_button_color]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Background: Button toggle vertical menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_button_hover_background]',
				array(
					'default'           => $defaults['header_8_button_hover_background'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_button_hover_background]',
					array(
						'label'    => __( 'Hover Background Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_button_hover_background]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Text color: Button toggle vertical menu.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_button_hover_color]',
				array(
					'default'           => $defaults['header_8_button_hover_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_button_hover_color]',
					array(
						'label'    => __( 'Hover Text Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_button_hover_color]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// Start content right layout 8 divider.
			$wp_customize->add_setting(
				'woostify_pro_header_layout_8_start_content_right',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'woostify_pro_header_layout_8_start_content_right',
					array(
						'section'  => 'woostify_header',
						'settings' => 'woostify_pro_header_layout_8_start_content_right',
						'priority' => 45,
						'type'     => 'divider',
					)
				)
			);

			// Right Content.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_right_content]',
				array(
					'default'           => $defaults['header_8_right_content'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_raw_html',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[header_8_right_content]',
					array(
						'label'    => __( 'Right Content', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_right_content]',
						'priority' => 45,
						'section'  => 'woostify_header',
						'type'     => 'textarea',
					)
				)
			);

			// Text content right color.
			$wp_customize->add_setting(
				'woostify_pro_options[header_8_content_right_text_color]',
				array(
					'default'           => $defaults['header_8_content_right_text_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[header_8_content_right_text_color]',
					array(
						'label'    => __( 'Right Content Color', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[header_8_content_right_text_color]',
						'priority' => 45,
						'section'  => 'woostify_header',
					)
				)
			);

			// End header layout 8 divider.
			$wp_customize->add_setting(
				'woostify_pro_header_layout_8_end',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'woostify_pro_header_layout_8_end',
					array(
						'section'  => 'woostify_header',
						'settings' => 'woostify_pro_header_layout_8_end',
						'priority' => 45,
						'type'     => 'divider',
					)
				)
			);
		}
	}

	Woostify_Multiple_Header::get_instance();
}
