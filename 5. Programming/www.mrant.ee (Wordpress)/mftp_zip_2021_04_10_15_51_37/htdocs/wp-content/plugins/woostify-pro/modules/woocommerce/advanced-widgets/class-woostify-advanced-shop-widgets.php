<?php
/**
 * Advanced Shop Widgets
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Advanced_Shop_Widgets' ) ) :

	/**
	 * Main Woostify Pro Class
	 */
	class Woostify_Advanced_Shop_Widgets {

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
			$this->define_constants();
			add_action( 'widgets_init', array( $this, 'add_widgets' ) );
			add_filter( 'getarchives_where', array( $this, 'get_product_archive' ), 10, 2 );
			add_filter( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_ADVANCED_SHOP_WIDGETS' ) ) {
				define( 'WOOSTIFY_PRO_ADVANCED_SHOP_WIDGETS', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Adds widgets.
		 */
		public function add_widgets() {
			if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
				return;
			}

			define( 'WOOSTIFY_WIDGETS_DIR', WOOSTIFY_PRO_PATH . 'modules/woocommerce/advanced-widgets/' );
			define( 'WOOSTIFY_WIDGETS_URI', WOOSTIFY_PRO_URI . 'modules/woocommerce/advanced-widgets/' );

			// Product archive.
			require_once WOOSTIFY_WIDGETS_DIR . 'product-archive/class-woostify-widget-product-archive.php';
			register_widget( 'Woostify_Widget_Product_Archive' );

			// Featured products.
			require_once WOOSTIFY_WIDGETS_DIR . 'product-feature/class-woostify-widget-featured-product.php';
			register_widget( 'Woostify_Widget_Featured_Product' );

			// Product categories.
			require_once WOOSTIFY_WIDGETS_DIR . 'product-categories/class-woostify-widget-product-categories.php';
			register_widget( 'Woostify_Widget_Product_Categories' );

			// Product filter.
			if ( defined( 'WOOSTIFY_PRO_VARIATION_SWATCHES' ) ) {
				require_once WOOSTIFY_WIDGETS_DIR . 'product-filter/class-woostify-widget-product-filter.php';
				register_widget( 'Woostify_Widget_Product_Filter' );
			}
		}

		/**
		 * Gets the product archive.
		 *
		 * @param string $where Post Query.
		 * @param array  $args  Argument.
		 */
		public function get_product_archive( $where, $args ) {
			$post_type = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
			$where     = 'WHERE post_type = "' . $post_type . '" AND post_status = "publish"';

			return $where;
		}

		/**
		 * Scripts and style
		 */
		public function scripts() {
			if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
				return;
			}

			$options = woostify_options( false );

			// Slick script and style.
			wp_register_script(
				'slick',
				WOOSTIFY_WIDGETS_URI . 'assets/js/slick' . woostify_suffix() . '.js',
				[ 'jquery' ],
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_register_script(
				'woostify-featured-product',
				WOOSTIFY_WIDGETS_URI . 'assets/js/featured-product' . woostify_suffix() . '.js',
				[ 'slick' ],
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_register_style(
				'slick',
				WOOSTIFY_WIDGETS_URI . 'assets/css/slick.css',
				[],
				WOOSTIFY_PRO_VERSION
			);

			wp_enqueue_style(
				'woostify-advanced-shop-widgets',
				WOOSTIFY_WIDGETS_URI . 'assets/css/style.css',
				[],
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.adv-products-filter.filter-by-select .pf-item.selected .pf-link {
					border-color: ' . esc_attr( $options['theme_color'] ) . ';
				}

				.adv-products-filter.filter-by-select .selected .pf-label {
					color: ' . esc_attr( $options['theme_color'] ) . ';
				}
			';
			wp_add_inline_style( 'woostify-advanced-shop-widgets', $styles );
		}
	}

	Woostify_Advanced_Shop_Widgets::get_instance();
endif;
