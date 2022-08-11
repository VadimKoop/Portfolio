<?php
/**
 * Main Woostify Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Pro' ) ) {
	/**
	 * Main Woostify Pro Class
	 */
	class Woostify_Pro {

		/**
		 * Instance
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
		 * Woostify Pro Constructor.
		 */
		public function __construct() {
			$this->includes();

			add_action( 'init', array( $this, 'setup' ) );
			add_action( 'admin_init', array( $this, 'woostify_pro_updater' ), 0 );
			add_action( 'after_setup_theme', array( $this, 'module_list' ) );
			add_action( 'admin_menu', array( $this, 'add_new_admin_menu' ), 5 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'plugin_action_links_' . WOOSTIFY_PRO_PLUGIN_BASE, array( $this, 'action_links' ) );
			add_action( 'customize_register', array( $this, 'register_customizer' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_static' ), 100 );
			add_filter( 'woostify_options_admin_menu', '__return_true' );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_control_scripts' ) );
			add_action( 'customize_preview_init', array( $this, 'customizer_preview_scripts' ), 11 );
			add_action( 'woostify_pro_panel_column', array( $this, 'woostify_extract_modules' ), 5 );
			add_action( 'wp_ajax_woostify_pro_check_licenses', array( $this, 'woostify_process_license_key' ) );
			add_action( 'wp_ajax_module_action', array( $this, 'woostify_ajax_module_action' ) );
			add_action( 'wp_ajax_all_feature_activated', array( $this, 'woostify_ajax_all_feature_activated' ) );
			add_action( 'woostify_pro_panel_sidebar', array( $this, 'woostify_activation_section' ), 5 );
			add_action( 'admin_notices', array( $this, 'woostify_pro_print_notices' ) );

			do_action( 'woostify_pro_loaded' );
		}

		/**
		 * Woostify Pro Packages.
		 */
		public function woostify_pro_packages() {
			$names = array( 'Woostify Pro Dev', 'Woostify Pro – Lifetime', 'Woostify Pro – Business', 'Woostify Pro – Ultimate', 'Woostify Pro – Personal' );
			return $names;
		}

		/**
		 * Includes
		 *
		 * @since 1.2.0
		 */
		public function includes() {
			// Woocommerce helper.
			require_once WOOSTIFY_PRO_PATH . 'inc/class-woostify-woocommerce-helper.php';
		}

		/**
		 * Sets up.
		 */
		public function setup() {
			// Remove when start supporting WP 5.0 or later.
			$locale = is_admin() ? get_user_locale() : get_locale();

			if ( function_exists( 'determine_locale' ) ) {
				$locale = determine_locale();
			}

			$locale = apply_filters( 'woostify_pro_plugin_locale', $locale, 'woostify-pro' );

			load_textdomain(
				'woostify-pro',
				WP_LANG_DIR . '/woostify-pro/woostify-pro-' . $locale . '.mo'
			);
			load_textdomain(
				'woostify-pro',
				WOOSTIFY_PRO_PATH . 'languages/woostify-pro-' . $locale . '.mo'
			);
			load_plugin_textdomain(
				'woostify-pro',
				false,
				WOOSTIFY_PRO_PATH . 'languages/'
			);
		}

		/**
		 * Add new admin menu
		 */
		public function add_new_admin_menu() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			$woostify_admin = Woostify_Admin::get_instance();
			$page           = add_menu_page( 'Woostify Theme Options', __( 'Woostify Options', 'woostify-pro' ), 'manage_options', 'woostify-welcome', array( $woostify_admin, 'woostify_welcome_screen' ), 'none', 60 );

			add_submenu_page( 'woostify-welcome', 'Woostify Theme Options', __( 'Dashboard', 'woostify-pro' ), 'manage_options', 'woostify-welcome' );
			add_action( 'admin_print_styles-' . $page, array( $woostify_admin, 'woostify_welcome_static' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'woostify_pro_dashboard_static' ) );
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param      array $links  The links.
		 *
		 * @return     array
		 */
		public function action_links( $links = array() ) {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return $links;
			}

			$action_links = array(
				'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=woostify-welcome' ) ) . '" aria-label="' . esc_attr__( 'View Woostify Pro settings', 'woostify-pro' ) . '">' . esc_html__( 'Settings', 'woostify-pro' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Register customizer
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function register_customizer( $wp_customize ) {
			$customizer_dir = glob( WOOSTIFY_PRO_PATH . 'inc/customizer/*.php' );

			foreach ( $customizer_dir as $file ) {
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		}

		/**
		 * Script and style file for frontend.
		 */
		public function frontend_static() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			// General script.
			wp_enqueue_script(
				'woostify-pro-general',
				WOOSTIFY_PRO_URI . 'assets/js/frontend' . woostify_suffix() . '.js',
				array( 'jquery' ),
				WOOSTIFY_PRO_VERSION,
				true
			);

			// General style.
			wp_enqueue_style(
				'woostify-pro-general',
				WOOSTIFY_PRO_URI . 'assets/css/frontend.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			// RTL.
			if ( is_rtl() ) {
				wp_enqueue_style(
					'woostify-pro-rtl',
					WOOSTIFY_PRO_URI . 'assets/css/rtl.css',
					array(),
					WOOSTIFY_PRO_VERSION
				);
			}

			// Elementor kit.
			$elementor_kit = get_option( 'elementor_active_kit' );
			if ( $elementor_kit ) {
				$css_file = null;

				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $elementor_kit );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $elementor_kit );
				}

				if ( $css_file ) {
					$css_file->enqueue();
				}
			}
		}

		/**
		 * Woostify admin scripts.
		 */
		public function admin_scripts() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			// General style for Admin.
			wp_enqueue_style(
				'woostify-pro-backend',
				WOOSTIFY_PRO_URI . 'assets/css/backend.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			// For color picker.
			wp_enqueue_style( 'wp-color-picker' );
			wp_register_script(
				'woostify-admin-color-picker',
				'',
				array( 'jquery', 'wp-color-picker' ),
				WOOSTIFY_PRO_VERSION,
				true
			);
			wp_enqueue_script( 'woostify-admin-color-picker' );
			wp_add_inline_script(
				'woostify-admin-color-picker',
				"( function() {
					jQuery( '.woostify-admin-color-picker' ).wpColorPicker();
				} )( jQuery );"
			);
		}

		/**
		 * Woostify dashboard script
		 */
		public function woostify_pro_dashboard_static() {
			// For Edit post type screen.
			$screen                 = get_current_screen();
			$is_welcome             = false !== strpos( $screen->id, 'woostify-welcome' );
			$is_countdown_urgency   = false !== strpos( $screen->id, 'countdown-urgency-settings' );
			$is_swatches            = false !== strpos( $screen->id, 'variation-swatches-settings' );
			$is_sale_notification   = false !== strpos( $screen->id, 'sale-notification-settings' );
			$is_ajax_search_product = false !== strpos( $screen->id, 'ajax-search-product-settings' );

			// Script for edit post screen || some page setting.
			if ( $screen->post_type || $is_countdown_urgency || $is_swatches || $is_sale_notification || $is_ajax_search_product ) {
				wp_enqueue_script(
					'woostify-edit-screen',
					WOOSTIFY_PRO_URI . 'assets/js/edit-screen' . woostify_suffix() . '.js',
					array(),
					WOOSTIFY_PRO_VERSION,
					true
				);

				$data = array(
					'save'   => __( 'Save', 'woostify-pro' ),
					'saving' => __( 'Saving', 'woostify-pro' ),
					'saved'  => __( 'Saved', 'woostify-pro' ),
				);

				wp_localize_script(
					'woostify-edit-screen',
					'woostify_edit_screen',
					$data
				);
			}

			// For Woostify Dashboard page.
			if ( ! $is_welcome ) {
				return;
			}

			// STYLE.
			// Get current color scheme.
			global $_wp_admin_css_colors;
			$colors = $_wp_admin_css_colors[ get_user_option( 'admin_color' ) ]->colors;

			wp_enqueue_style(
				'woostify-pro-dashboard',
				WOOSTIFY_PRO_URI . 'assets/css/dashboard.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			wp_add_inline_style(
				'woostify-pro-dashboard',
				".woostify-pro-module .module-item.activated {
					border-left-color: $colors[3];
				}
				.woostify-pro-module .module-item:hover .module-name label {
					color: $colors[3];
				}"
			);

			wp_enqueue_script(
				'woostify-pro-dashboard',
				WOOSTIFY_PRO_URI . 'assets/js/dashboard' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			// SCRIPT.
			wp_localize_script(
				'woostify-pro-dashboard',
				'woostify_pro_dashboard',
				array(
					'ajax_nonce'                 => wp_create_nonce( 'dashboard_ajax_nonce' ),
					// Modules.
					'activate'                   => __( 'Activate', 'woostify-pro' ),
					'activating'                 => __( 'Activating...', 'woostify-pro' ),
					'deactivate'                 => __( 'Deactivate', 'woostify-pro' ),
					'deactivating'               => __( 'Deactivating...', 'woostify-pro' ),
					// License.
					'head'                       => get_option( 'woostify_pro_license_key', '' ), // License key.
					'receiving'                  => __( 'Receiving updates', 'woostify-pro' ),
					'not_receiving'              => __( 'Not receiving updates', 'woostify-pro' ),
					'license_empty'              => __( 'Please enter your license.', 'woostify-pro' ),
					'activate_success_message'   => __( 'Your license has been activated successfully!.', 'woostify-pro' ),
					'deactivate_success_message' => __( 'Your license has been deactivated.', 'woostify-pro' ),
					'failure_message'            => __( 'We are sorry, an error has occurred - Invalid License.', 'woostify-pro' ),
					'activate_label'             => __( 'Activate', 'woostify-pro' ),
					'deactivate_label'           => __( 'Deactivate', 'woostify-pro' ),
				)
			);
		}

		/**
		 * Add script for customizer controls
		 */
		public function customizer_control_scripts() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			wp_enqueue_script(
				'woostify-pro-customizer-controls',
				WOOSTIFY_PRO_URI . 'assets/js/customizer-controls' . woostify_suffix() . '.js',
				array( 'jquery' ),
				WOOSTIFY_PRO_VERSION,
				true
			);
		}

		/**
		 * Add script for customizer preview
		 */
		public function customizer_preview_scripts() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			wp_enqueue_script(
				'woostify-pro-customizer-preview',
				WOOSTIFY_PRO_URI . 'assets/js/customizer-preview' . woostify_suffix() . '.js',
				array( 'jquery' ),
				WOOSTIFY_PRO_VERSION,
				true
			);
		}

		/**
		 * Check to see if a module is active
		 *
		 * @param      string $module      The module.
		 * @param      string $definition  The definition.
		 *
		 * @return     boolean
		 */
		public function module_active( $module, $definition ) {
			// If we don't have the module or definition, bail.
			if ( ! $module && ! $definition ) {
				return false;
			}

			// If our module is active, return true.
			if ( 'activated' === get_option( $module ) || defined( $definition ) ) {
				return true;
			}

			// Not active? Return false.
			return false;
		}

		/**
		 * Default Options Value
		 */
		public function default_options_value() {
			$args = array(
				// HEADER.
				// Layout 1.
				'header_full_width'                  => false,
				// Layout 3.
				'header_left_content'                => '',
				// Layout 5.
				'header_center_content'              => '',
				// Layout 6.
				'header_right_content'               => '[header_content_block]',
				'header_content_bottom_background'   => '#212121',
				// Layout 7.
				'header_sidebar_content_bottom'      => '',
				// Layout 8.
				'header_8_search_bar_background'     => '#fcb702',
				'header_8_button_background'         => '#ffffff',
				'header_8_button_color'              => '#333333',
				'header_8_button_hover_background'   => '#333333',
				'header_8_button_hover_color'        => '#ffffff',
				'header_8_icon_color'                => '#000000',
				'header_8_icon_hover_color'          => '#cccccc',
				'header_8_button_text'               => __( 'Shop By Categories', 'woostify-pro' ),
				'header_8_right_content'             => '[header_single_block icon="ti-headphone-alt" icon_color="" heading="(+245)-1802-2019" href="megashop@info.com"]',
				'header_8_content_right_text_color'  => '#333333',

				// AJAX PRODUCT SEARCH.
				'ajax_product_search'                => true,

				// STICKY HEADER.
				'sticky_header_display'              => false,
				'sticky_header_disable_archive'      => true,
				'sticky_header_disable_index'        => false,
				'sticky_header_disable_page'         => false,
				'sticky_header_disable_post'         => false,
				'sticky_header_disable_shop'         => false,
				'sticky_header_background_color'     => '#ffffff',
				'sticky_header_enable_on'            => 'all-devices',
				'sticky_header_border_color'         => '#eaeaea',
				'sticky_header_border_width'         => 1,

				// SHOP PAGE.
				'shop_page_quick_view_position'      => 'top-right',
				'shop_product_quick_view_icon'       => true,
				'shop_product_quick_view_background' => '',
				'shop_product_quick_view_color'      => '',
				'shop_product_quick_view_bg_hover'   => '',
				'shop_product_quick_view_c_hover'    => '',
				'shop_product_quick_view_radius'     => '',
				// Product Loop Style.
				'product_loop_icon_position'         => 'bottom-right',
				'product_loop_icon_direction'        => 'horizontal',
				'product_loop_icon_color'            => '#b7b7b7',
				'product_loop_icon_background_color' => '#ffffff',

				// SHOP SINGLE.
				// Buy Now Hover.
				'shop_single_background_hover'       => '',
				'shop_single_color_hover'            => '',
				'shop_single_buy_now_button'         => '',
				'shop_single_background_buynow'      => '',
				'shop_single_color_button_buynow'    => '',
				'shop_single_border_radius_buynow'   => '',

				// Sticky button.
				'sticky_single_add_to_cart_button'   => 'top',
				'sticky_atc_button_on'               => 'both',
			);

			return $args;
		}

		/**
		 * Woostify Pro Options Value
		 */
		public function woostify_pro_options() {
			$args = wp_parse_args(
				get_option( 'woostify_pro_options', array() ),
				self::default_options_value()
			);

			return $args;
		}

		/**
		 * Woostify pro modules
		 */
		public function woostify_pro_modules() {
			// Elementor.
			$elementor_condition = defined( 'ELEMENTOR_VERSION' );
			$elementor_error     = __( 'Elementor must be activated', 'woostify-pro' );

			// Woocommerce.
			$woocommerce_condition = defined( 'WC_PLUGIN_FILE' );
			$woocommerce_error     = __( 'WooCommerce must be activated', 'woostify-pro' );

			// Required Elementor and Woocommerce.
			$woo_elementor_condition = $elementor_condition && $woocommerce_condition;
			$woo_elementor_error     = __( 'WooCommerce and Elementor must be activeted', 'woostify-pro' );

			$modules = array(
				'woostify_multiphe_header'          => __( 'Multiple Headers', 'woostify-pro' ),
				'woostify_sticky_header'            => __( 'Sticky Header', 'woostify-pro' ),
				'woostify_mega_menu'                => array(
					'title'       => __( 'Mega Menu', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'edit.php?post_type=mega_menu',
				),
				'woostify_elementor_widgets'        => array(
					'title'       => __( 'Elementor Bundle', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $elementor_condition,
					'error'       => $elementor_error,
				),
				'woostify_header_footer_builder'    => array(
					'title'       => __( 'Header Footer Builder', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'edit.php?post_type=hf_builder',
					'condition'   => $elementor_condition,
					'error'       => $elementor_error,
				),
				'woostify_woo_builder'              => array(
					'title'       => __( 'WooBuilder', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'edit.php?post_type=woo_builder',
					'condition'   => $woo_elementor_condition,
					'error'       => $woo_elementor_error,
				),
				'woostify_elementor_product_filter' => array(
					'title'       => __( 'Elementor Product Filter ', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'edit.php?post_type=product_filter',
					'condition'   => $woo_elementor_condition,
					'error'       => $woo_elementor_error,
				),
				'woostify_wc_ajax_shop_filter'      => array(
					'title'       => __( 'Ajax Product Filter ', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_ajax_product_search'   => array(
					'title'       => __( 'Ajax Product Search', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'admin.php?page=ajax-search-product-settings',
					'condition'   => defined( 'WC_PLUGIN_FILE' ),
					'error'       => $woocommerce_error,
				),
				'woostify_size_guide'               => array(
					'title'       => __( 'Size Guide', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'edit.php?post_type=size_guide',
					'condition'   => defined( 'WC_PLUGIN_FILE' ),
					'error'       => $woocommerce_error,
				),
				'woostify_wc_advanced_shop_widgets' => array(
					'title'       => __( 'Advanced Shop Widgets', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_buy_now_button'        => array(
					'title'       => __( 'Buy Now Button', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_sticky_button'         => array(
					'title'       => __( 'Sticky Single Add To Cart', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_quick_view'            => array(
					'title'       => __( 'Quick View', 'woostify-pro' ),
					'setting_url' => false,
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_countdown_urgency'     => array(
					'title'       => __( 'Countdown Urgency', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'admin.php?page=countdown-urgency-settings',
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_variation_swatches'    => array(
					'title'       => __( 'Variation Swatches', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'admin.php?page=variation-swatches-settings',
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
				'woostify_wc_sale_notification'     => array(
					'title'       => __( 'Sale Notification', 'woostify-pro' ),
					'setting_url' => get_admin_url() . 'admin.php?page=sale-notification-settings',
					'condition'   => $woocommerce_condition,
					'error'       => $woocommerce_error,
				),
			);

			return $modules;
		}

		/**
		 * Module List
		 */
		public function module_list() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) ) {
				return;
			}

			// Define modules dir.
			if ( ! defined( 'WOOSTIFY_PRO_MODULES_PATH' ) ) {
				define( 'WOOSTIFY_PRO_MODULES_PATH', WOOSTIFY_PRO_PATH . 'modules/' );
			}
			if ( ! defined( 'WOOSTIFY_PRO_MODULES_URI' ) ) {
				define( 'WOOSTIFY_PRO_MODULES_URI', WOOSTIFY_PRO_URI . 'modules/' );
			}

			// Multiple header.
			if ( $this->module_active( 'woostify_multiphe_header', 'WOOSTIFY_PRO_MULTIPLE_HEADER' ) ) {
				require_once WOOSTIFY_PRO_MODULES_PATH . 'multiple-header/class-woostify-multiple-header.php';
			}

			// Sticky header.
			if ( $this->module_active( 'woostify_sticky_header', 'WOOSTIFY_PRO_STICKY_HEADER' ) ) {
				require_once WOOSTIFY_PRO_MODULES_PATH . 'sticky-header/class-woostify-sticky-header.php';
			}

			// Mega menu.
			if ( $this->module_active( 'woostify_mega_menu', 'WOOSTIFY_PRO_MEGA_MENU' ) ) {
				require_once WOOSTIFY_PRO_MODULES_PATH . 'mega-menu/class-woostify-mega-menu.php';
			}

			/**
			 * Woocommerce Modules
			 */
			if ( woostify_is_woocommerce_activated() ) {
				// Ajax product tab. For Product Tab widget.
				require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/ajax-product-tabs/class-woostify-ajax-product-tab.php';

				// Size guide.
				if ( $this->module_active( 'woostify_size_guide', 'WOOSTIFY_PRO_SIZE_GUIDE' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/size-guide/class-woostify-size-guide.php';
				}

				// Ajax product search.
				if ( $this->module_active( 'woostify_wc_ajax_product_search', 'WOOSTIFY_PRO_AJAX_PRODUCT_SEARCH' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/ajax-product-search/class-woostify-ajax-product-search.php';
				}

				// Buy now button.
				if ( $this->module_active( 'woostify_wc_buy_now_button', 'WOOSTIFY_PRO_BUY_NOW_BUTTON' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/buy-now-button/class-woostify-buy-now-button.php';
				}

				// Sticky add to cart button on product page.
				if ( $this->module_active( 'woostify_wc_sticky_button', 'WOOSTIFY_PRO_STICKY_SINGLE_ADD_TO_CART' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/sticky-button/class-woostify-sticky-button.php';
				}

				// Advanced shop widgets.
				if ( $this->module_active( 'woostify_wc_advanced_shop_widgets', 'WOOSTIFY_PRO_ADVANCED_SHOP_WIDGETS' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/advanced-widgets/class-woostify-advanced-shop-widgets.php';
				}

				// Quick view popup.
				if ( $this->module_active( 'woostify_wc_quick_view', 'WOOSTIFY_PRO_QUICK_VIEW' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/quick-view/class-woostify-quick-view.php';
				}

				// Countdown urgency.
				if ( $this->module_active( 'woostify_wc_countdown_urgency', 'WOOSTIFY_PRO_COUNTDOWN_URGENCY' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/countdown-urgency/class-woostify-countdown-urgency.php';
				}

				// Variation swatches.
				if ( $this->module_active( 'woostify_wc_variation_swatches', 'WOOSTIFY_PRO_VARIATION_SWATCHES' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/variation-swatches/class-woostify-variation-swatches.php';
				}

				// Sale notification.
				if ( $this->module_active( 'woostify_wc_sale_notification', 'WOOSTIFY_PRO_SALE_NOTIFICATION' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/sale-notification/class-woostify-sale-notification.php';
				}

				// Ajax product filter.
				if ( $this->module_active( 'woostify_wc_ajax_shop_filter', 'WOOSTIFY_PRO_AJAX_SHOP_FILTER' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/ajax-shop-filter/class-woostify-ajax-shop-filter.php';
				}
			}

			/**
			 * Elementor Modules
			 */
			if ( woostify_is_elementor_activated() ) {
				// Header Footer Builder.
				if ( $this->module_active( 'woostify_header_footer_builder', 'WOOSTIFY_PRO_HEADER_FOOTER_BUILDER' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'header-footer-builder/class-woostify-header-footer-builder.php';
				}

				// Elementor Widgets.
				if ( $this->module_active( 'woostify_elementor_widgets', 'WOOSTIFY_PRO_ELEMENTOR_WIDGETS' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'elementor/class-woostify-elementor-widgets.php';
				}
			}

			// Required Elementor and Woocommerce.
			if ( woostify_is_elementor_activated() && woostify_is_woocommerce_activated() ) {
				// Woocommerce Builder.
				if ( $this->module_active( 'woostify_woo_builder', 'WOOSTIFY_PRO_WOO_BUILDER' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/class-woostify-woo-builder.php';
				}

				// Elementor product filter.
				if ( $this->module_active( 'woostify_elementor_product_filter', 'WOOSTIFY_PRO_PRODUCT_FILTER' ) ) {
					require_once WOOSTIFY_PRO_MODULES_PATH . 'product-filter/class-woostify-product-filter.php';
				}
			}
		}

		/**
		 * Set up the updater
		 **/
		public function woostify_pro_updater() {
			// Load EDD SL Plugin Updater.
			// Testing updater.
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require_once WOOSTIFY_PRO_PATH . 'inc/EDD_SL_Plugin_Updater.php';
			}

			// Retrieve our license key from the DB.
			$license_key = get_option( 'woostify_pro_license_key' );

			// License status.
			$license_status = get_option( 'woostify_pro_license_key_status', 'invalid' );

			// Item name.
			$item_name = get_option( 'woostify_pro_package_name' );

			// Item expires.
			$item_expires = get_option( 'woostify_pro_license_key_expires' );

			// Setup the updater.
			if ( $item_name && $license_key && $item_expires && 'valid' === $license_status ) {
				$edd_updater = new EDD_SL_Plugin_Updater(
					'https://woostify.com',
					WOOSTIFY_PRO_FILE,
					array(
						'version'   => WOOSTIFY_PRO_VERSION,
						'license'   => trim( $license_key ),
						'item_name' => rawurlencode( $item_name ),
						'author'    => 'Woostify',
						'url'       => home_url(),
						'beta'      => apply_filters( 'woostify_pro_beta_tester', false ),
					)
				);
			}
		}

		/**
		 * Build the area that allows us to activate and deactivate modules.
		 */
		public function woostify_extract_modules() {
			// Get current color scheme.
			global $_wp_admin_css_colors;
			$colors = $_wp_admin_css_colors[ get_user_option( 'admin_color' ) ]->colors;

			?>
			<div class="woostify-enhance__column woostify-pro-module">
				<h3 class="hndle">
					<?php
						/* translators: Woostify Pro Version */
						echo esc_html( sprintf( __( 'Woostify Pro %s', 'woostify-pro' ), WOOSTIFY_PRO_VERSION ) );
					?>
				</h3>

				<div class="woostify-module-list">
					<div class="active-all-item">
						<div class="module-name">
							<input type="checkbox" id="woostify-select-all" />

							<select name="woostify_multi_module_activate" class="multi-module-action">
								<option value=""><?php esc_html_e( 'Bulk Actions', 'woostify-pro' ); ?></option>
								<option value="activated"><?php esc_html_e( 'Activate', 'woostify-pro' ); ?></option>
								<option value="deactivated"><?php esc_html_e( 'Deactivate', 'woostify-pro' ); ?></option>
							</select>

							<button name="woostify_multi_activate" class="button multi-module-action-button" ><?php esc_html_e( 'Apply', 'woostify-pro' ); ?></button>
						</div>
					</div>

					<?php
					foreach ( $this->woostify_pro_modules() as $k => $v ) {
						$key      = get_option( $k );
						$label    = 'activated' === $key ? 'deactivate' : 'activate';
						$title    = $v;
						$disabled = '';

						if ( is_array( $v ) ) {
							$title = $v['title'];

							if ( isset( $v['condition'] ) && ! $v['condition'] ) {
								$label    = $v['error'];
								$disabled = 'disabled';
							}
						}

						$id = 'module-id-' . $k;
						?>
							<div class="module-item <?php echo esc_attr( $key ); ?> <?php echo esc_attr( $disabled ); ?>">
								<div class="module-name">
									<input type="checkbox" class="module-checkbox" name="woostify_module_checkbox[]" value="<?php echo esc_attr( $k ); ?>" id="<?php echo esc_attr( $id ); ?>" />
									<label for="<?php echo esc_attr( $id ); ?>">
										<?php echo esc_html( $title ); ?>
									</label>
								</div>

								<div class="module-action">
									<?php if ( is_array( $v ) && $v['setting_url'] ) { ?>
										<a class="module-setting-url" href="<?php echo esc_url( $v['setting_url'] ); ?>"><?php esc_html_e( 'Settings', 'woostify-pro' ); ?></a>
									<?php } ?>

									<button class="module-action-button wp-ui-text-highlight" data-value="<?php echo esc_attr( $key ); ?>" data-name="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></button>
								</div>
							</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Activate or Deactivated module using ajax.
		 */
		public function woostify_ajax_module_action() {
			check_ajax_referer( 'dashboard_ajax_nonce', 'ajax_nonce' );

			if ( isset( $_POST['name'] ) && isset( $_POST['status'] ) ) {
				$response = array();
				$name     = sanitize_text_field( wp_unslash( $_POST['name'] ) );
				$status   = sanitize_text_field( wp_unslash( $_POST['status'] ) );

				if ( 'activated' === $status ) {
					update_option( $name, 'deactivated' );
				} else {
					update_option( $name, 'activated' );
				}

				$response['status'] = get_option( $name );

				wp_send_json_success( $response );
			}

			wp_send_json_error();
		}

		/**
		 * Detect all featured area activated
		 */
		public function woostify_ajax_all_feature_activated() {
			/*Bail if the nonce doesn't check out*/
			if ( ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			$current = get_option( 'woostify_pro_fully_featured_activate' );

			/*Do another nonce check*/
			check_ajax_referer( 'dashboard_ajax_nonce', 'ajax_nonce' );
			$detect = isset( $_POST['detect'] ) ? sanitize_text_field( wp_unslash( $_POST['detect'] ) ) : '';

			if ( $detect !== $current ) {
				update_option( 'woostify_pro_fully_featured_activate', $detect );
			}

			wp_send_json_success();
		}

		/**
		 * Acrivation section
		 */
		public function woostify_activation_section() {
			$license_key  = get_option( 'woostify_pro_license_key', '' );
			$package_name = get_option( 'woostify_pro_package_name', '' );

			// Check again.
			if ( $license_key && $package_name ) {
				$api_params = array(
					'edd_action' => 'check_license',
					'license'    => $license_key,
					'item_name'  => rawurlencode( $package_name ),
					'url'        => home_url(),
				);

				// Connect.
				$connect = wp_remote_post(
					'https://woostify.com',
					array(
						'timeout'   => 60,
						'sslverify' => true,
						'body'      => $api_params,
					)
				);

				$body          = wp_remote_retrieve_body( $connect );
				$body_response = json_decode( $body );

				// Update license status.
				if ( $body_response->success && 'valid' === $body_response->license ) {
					update_option( 'woostify_pro_license_key_status', 'valid' );
				} else {
					update_option( 'woostify_pro_license_key_status', 'invalid' );
				}
			}

			$license_status = get_option( 'woostify_pro_license_key_status' );

			if ( 'valid' === $license_status ) {
				$message = sprintf( '<span class="license-key-message receiving-updates">%s</span>', __( 'Receiving updates', 'woostify-pro' ) );
			} else {
				$message = sprintf( '<span class="license-key-message not-receiving-updates">%s</span>', __( 'Not receiving updates', 'woostify-pro' ) );
			}

			// Hide license key.
			$license_key_lenth = strlen( $license_key );
			$license_key_value = $license_key_lenth > 0 ? str_repeat( '*', $license_key_lenth ) : '';
			?>

			<div class="woostify-enhance__column">
				<div id="woostify-license-keys">
					<h3 class="hndle">
						<?php esc_html_e( 'Your License Key', 'woostify-pro' ); ?>
						<span class="license-key-info">
							<?php echo wp_kses_post( $message ); ?>
							<a title="<?php esc_attr_e( 'Help', 'woostify-pro' ); ?>" href="https://woostify.com/contact/" target="_blank" rel="noopener">[?]</a>
						</span>
					</h3>

					<div class="wf-quick-setting-section">
						<div class="license-key-container">
							<form method="post" action="options.php" id="woostify_form_check_license">
								<p>
									<input class="widefat woostify-license-key-field" id="woostify_license_key_field" name="woostify_license_key_field" type="<?php echo esc_attr( apply_filters( 'woostify_pro_license_key_field', 'text' ) ); ?>" value="<?php echo esc_attr( $license_key_value ); ?>" placeholder="<?php esc_attr_e( 'Please enter your license key here', 'woostify-pro' ); ?>" <?php echo esc_attr( 'valid' === $license_status ? 'disabled' : '' ); ?> />
								</p>
								<?php
									$button_label = 'valid' === $license_status ? __( 'Deactivate', 'woostify-pro' ) : __( 'Activate', 'woostify-pro' );
								?>
								<button type="submit" class="button" id="woostify_pro_license_key_submit" name="woostify_pro_license_key_submit"><?php echo esc_html( $button_label ); ?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Return EDD response
		 *
		 * @param string $param The EDD param.
		 */
		public function woostify_get_edd_response( $param = 'item_name' ) {
			$items_name  = $this->woostify_pro_packages();
			$license_key = get_option( 'woostify_pro_license_key', '' );
			$data        = false;

			foreach ( $items_name as $k ) {
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $license_key,
					'item_name'  => rawurlencode( $k ),
					'url'        => home_url(),
				);

				$license_response = wp_remote_post(
					'https://woostify.com',
					array(
						'timeout'   => 60,
						'sslverify' => true,
						'body'      => $api_params,
					)
				);

				$res = json_decode( wp_remote_retrieve_body( $license_response ) );

				if ( $res->success && 'valid' === $res->license ) {
					$data = $res->{$param};
				}
			}

			return $data;
		}

		/**
		 * Process our saved license key.
		 */
		public function woostify_process_license_key() {
			// Bail if the nonce doesn't check out.
			if ( ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			// Do another nonce check.
			check_ajax_referer( 'dashboard_ajax_nonce', 'ajax_nonce' );

			// Grab the value being saved.
			$new = isset( $_POST['woostify_license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_license_key'] ) ) : '';

			// Return if license is empty.
			if ( empty( $new ) ) {
				return;
			}

			// Get the previously saved value.
			$old = get_option( 'woostify_pro_license_key' );

			// Get license status.
			$license_status = get_option( 'woostify_pro_license_key_status' );

			// Items name.
			$items_name = $this->woostify_pro_packages();

			$response = array();
			$license  = 'invalid';
			$success  = false;

			foreach ( $items_name as $name ) {
				// Activate license key.
				$api_params = array(
					'edd_action' => 'activate_license',
					'license'    => $new,
					'item_name'  => rawurlencode( $name ),
					'url'        => home_url(),
				);

				// Deactivate license key.
				if ( 'valid' === $license_status ) {
					$api_params = array(
						'edd_action' => 'deactivate_license',
						'license'    => $old,
						'item_name'  => rawurlencode( $name ),
						'url'        => home_url(),
					);
				}

				// Connect.
				$connect = wp_remote_post(
					'https://woostify.com',
					array(
						'timeout'   => 60,
						'sslverify' => true,
						'body'      => $api_params,
					)
				);

				// Get response.
				$body          = wp_remote_retrieve_body( $connect );
				$response[]    = $body;
				$body_response = json_decode( $body );

				// License activate success.
				if ( $body_response->success && 'valid' === $body_response->license ) {
					$license = 'valid';
					$success = true;
					update_option( 'woostify_pro_package_name', $body_response->item_name );
					update_option( 'woostify_pro_license_key_expires', $body_response->expires );
				}
			}

			// License activate failure.
			if ( ! $success && 'invalid' === $license ) {
				update_option( 'woostify_pro_package_name', '' );
			}

			// Update new license key.
			update_option( 'woostify_pro_license_key', $new );

			// Update license key status.
			update_option( 'woostify_pro_license_key_status', $license );

			// Send json for ajax handle.
			wp_send_json( $response );
		}

		/**
		 * Print admin notices.
		 */
		public function woostify_pro_print_notices() {
			if ( ! defined( 'WOOSTIFY_VERSION' ) || ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			// WOOSTIFY ADMIN NOTICE.
			// Warning if new version of Woostify Theme is available.
			$theme_min_ver = 'detect_new_woostify_version_' . WOOSTIFY_THEME_MIN_VERSION;
			if (
				is_admin() &&
				! get_user_meta( get_current_user_id(), $theme_min_ver ) &&
				version_compare( WOOSTIFY_THEME_MIN_VERSION, WOOSTIFY_VERSION, '>' )
			) {
				?>
				<div class="woostify-admin-notice notice notice-error is-dismissible" data-notice="<?php echo esc_attr( $theme_min_ver ); ?>">
					<div class="woostify-notice-content">
						<div class="woostify-notice-text">
							<?php
								$theme_upgrade_link = get_admin_url() . 'themes.php';

								$theme_message  = '<p>' . __( 'A new version of Woostify Theme is available. For better performance and compatibility of Woostify Pro Plugin, we recommend updating to the latest version.', 'woostify-pro' ) . '</p>';
								$theme_message .= '<p>' . sprintf( '<a href="%s" class="button">%s</a>', $theme_upgrade_link, __( 'Update Woostify Now', 'woostify-pro' ) ) . '</p>';

								echo wp_kses_post( $theme_message );
							?>
						</div>
					</div>
				</div>
				<?php
			}

			// Warning if new version of Woostify PRO is available.
			$plugin_min_ver = 'detect_new_pro_version_' . WOOSTIFY_PRO_MIN_VERSION;
			if (
				is_admin() &&
				defined( 'WOOSTIFY_PRO_MIN_VERSION' ) &&
				! get_user_meta( get_current_user_id(), $plugin_min_ver ) &&
				version_compare( WOOSTIFY_PRO_MIN_VERSION, WOOSTIFY_PRO_VERSION, '>' )
			) {
				?>
				<div class="woostify-admin-notice notice notice-error is-dismissible" data-notice="<?php echo esc_attr( $plugin_min_ver ); ?>">
					<div class="woostify-notice-content">
						<div class="woostify-notice-text">
							<?php
								$plugin_upgrade_link = get_admin_url() . 'update-core.php';

								$plugin_message  = '<p>' . __( 'A new version of Woostify Pro Plugin is available. For better performance and compatibility of Woostify Theme, we recommend updating to the latest version.', 'woostify-pro' ) . '</p>';
								$plugin_message .= '<p>' . sprintf( '<a href="%s" class="button">%s</a>', $plugin_upgrade_link, __( 'Update Woostify Pro Now', 'woostify-pro' ) ) . '</p>';

								echo wp_kses_post( $plugin_message );
							?>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}

	Woostify_Pro::get_instance();
}
