<?php
/**
 * Woostify Buy Now Button Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Buy_Now_Button' ) ) :

	/**
	 * Woostify Buy Now Button Class
	 */
	class Woostify_Buy_Now_Button {

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
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'add_buy_now_button' ), 99 );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_BUY_NOW_BUTTON' ) ) {
				define( 'WOOSTIFY_PRO_BUY_NOW_BUTTON', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Gets default options.
		 *
		 * @return     Woostify  The default options.
		 */
		public function get_default_options() {
			$defaults = Woostify_Pro::get_instance()->default_options_value();

			return $defaults;
		}

		/**
		 * Gets the options.
		 *
		 * @return     Woostify  The options.
		 */
		public function get_options() {
			$options = Woostify_Pro::get_instance()->woostify_pro_options();

			return $options;
		}

		/**
		 * Script and style file.
		 */
		public function scripts() {
			$woostify_options = woostify_options( false );
			$options = Woostify_Pro::get_instance()->woostify_pro_options();
			if ( ! $woostify_options['shop_single_ajax_add_to_cart'] ) {
				// Buy now product script.
				wp_enqueue_script(
					'woostify-buy-now-button',
					WOOSTIFY_PRO_URI . 'modules/woocommerce/buy-now-button/js/script' . woostify_suffix() . '.js',
					array(),
					WOOSTIFY_PRO_VERSION,
					true
				);
			}

			// Style.
			wp_enqueue_style(
				'woostify-buy-now-button',
				WOOSTIFY_PRO_URI . 'modules/woocommerce/buy-now-button/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.woostify-buy-now.button:hover {
					background-color: ' . esc_attr( $options['shop_single_background_hover'] ) . ';
					color: ' . esc_attr( $options['shop_single_color_hover'] ) . ';
				}
				.woostify-buy-now.button {
					background-color: ' . esc_attr( $options['shop_single_background_buynow'] ) . ';
					color: ' . esc_attr( $options['shop_single_color_button_buynow'] ) . ';
					border-radius: ' . esc_attr( $options['shop_single_border_radius_buynow'] ) . 'px;
				}
			';
			wp_add_inline_style(
				'woostify-buy-now-button',
				$styles
			);
		}

		/**
		 * Adds a buy now button.
		 */
		public function add_buy_now_button() {
			$options = $this->get_options();
			if ( ! $options['shop_single_buy_now_button'] ) {
				return;
			}

			if ( function_exists( 'woostify_get_product_id' ) ) {
				$product_id = woostify_get_product_id();
			} else {
				$product_id = ( is_singular( 'woo_builder' ) || woostify_is_elementor_editor() ) ? woostify_get_last_product_id() : woostify_get_page_id();
			}

			$product = wc_get_product( $product_id );

			if ( empty( $product ) ) {
				return;
			}

			$variable = ! $product->is_type( 'variable' ) ? 'name="add-to-cart"' : '';
			?>

			<button data-checkout_url="<?php echo esc_attr( wc_get_checkout_url() ); ?>" type="submit" <?php echo wp_kses_post( $variable ); ?> value="<?php echo esc_attr( $product->get_id() ); ?>" class="woostify-buy-now single_add_to_cart_button button alt"><?php esc_html_e( 'Buy Now', 'woostify-pro' ); ?></button>
			<?php
		}
	}

	Woostify_Buy_Now_Button::get_instance();
endif;
