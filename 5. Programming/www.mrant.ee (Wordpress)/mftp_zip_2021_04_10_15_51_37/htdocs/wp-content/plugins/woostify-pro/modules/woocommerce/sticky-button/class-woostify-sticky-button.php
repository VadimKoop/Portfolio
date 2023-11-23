<?php
/**
 * Woostify Sticky Button Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Sticky_Button' ) ) :

	/**
	 * Woostify Sticky Button Class
	 */
	class Woostify_Sticky_Button {

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
			add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 10 );
			add_filter( 'body_class', [ $this, 'add_body_class' ] );
			add_action( 'woocommerce_before_single_product', [ $this, 'add_to_cart_section' ], 50 );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_STICKY_SINGLE_ADD_TO_CART' ) ) {
				define( 'WOOSTIFY_PRO_STICKY_SINGLE_ADD_TO_CART', WOOSTIFY_PRO_VERSION );
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
			// Script.
			wp_enqueue_script(
				'woostify-sticky-single-add-to-cart',
				WOOSTIFY_PRO_URI . 'modules/woocommerce/sticky-button/js/script' . woostify_suffix() . '.js',
				[],
				WOOSTIFY_PRO_VERSION
			);

			// Style.
			wp_enqueue_style(
				'woostify-sticky-single-add-to-cart',
				WOOSTIFY_PRO_URI . 'modules/woocommerce/sticky-button/css/style.css',
				[],
				WOOSTIFY_PRO_VERSION
			);
		}

		/**
		 * Body class
		 *
		 * @param      array $classes  The classes.
		 */
		public function add_body_class( $classes ) {
			$options       = $this->get_options();
			$single_button = $options['sticky_single_add_to_cart_button'];

			if ( is_singular( 'product' ) && 'none' != $single_button ) {
				$classes[] = 'has-single-sticky-button';
			}

			return $classes;
		}

		/**
		 * Add section add to cart
		 */
		public function add_to_cart_section() {
			global $product;

			// Options.
			$options = $this->get_options();

			// Product type.
			$simple   = $product->is_type( 'simple' );
			$variable = $product->is_type( 'variable' );
			$external = $product->is_type( 'external' );

			if ( 'outofstock' === $product->get_stock_status() ) {
				return;
			}

			// Button text.
			$text = $external ? $product->single_add_to_cart_text() : __( 'Add To Cart', 'woostify-pro' );

			// Section classes.
			$class[] = 'sticky-add-to-cart-section';
			$class[] = 'from-' . $options['sticky_single_add_to_cart_button'];
			$class[] = 'sticky-on-' . ( 'both' == $options['sticky_atc_button_on'] ? 'all-devices' : $options['sticky_atc_button_on'] );
			$class[] = $variable ? 'variations-product' : '';
			$class[] = $variable && 1 >= count( $product->get_available_variations() ) ? 'no-need-update-price' : '';
			$class[] = $product->get_stock_status();
			$class   = implode( ' ', array_filter( $class ) );

			// Button classes.
			$button_class[] = 'sticky-atc-button button';
			$button_class[] = $external && ! $product->add_to_cart_url() ? 'disabled' : '';
			$button_class   = implode( ' ', array_filter( $button_class ) );

			// Product data.
			$data = [
				'valid_qty'          => __( 'Please enter a valid quantity for this product', 'woostify-pro' ),
				'currency'           => get_woocommerce_currency_symbol(),
				'currency_pos'       => get_option( 'woocommerce_currency_pos' ),
				'currency_separator' => get_option( 'woocommerce_price_thousand_sep' ),
				'currency_decimal'   => get_option( 'woocommerce_price_decimal_sep' ),
				'price'              => $variable ? $product->get_variation_price( 'min', true ) : $product->get_price(),
				'regular_price'      => $variable ? $product->get_variation_price( 'max', true ) : $product->get_regular_price(),
				'sale_price'         => $product->get_sale_price(),
			];
			?>

			<div class="<?php echo esc_attr( $class ); ?>">
				<div class="woostify-container">
					<div class="sticky-atc-left">
						<div class="sticky-atc-image">
							<?php echo $product->get_image( 'thumbnail' ); // WPCS: XSS ok. ?>
						</div>
						<h3 class="sticky-atc-title"><?php echo esc_html( $product->get_title() ); ?></h3>
					</div>
					<div class="sticky-atc-right">
						<div class="sticky-atc-price">
							<?php echo $product->get_price_html(); // WPCS: XSS ok. ?>
						</div>
						<input class="sticky-atc-data" type="hidden" data-product='<?php echo json_encode( $data ); ?>'>
						<button class="<?php echo esc_attr( $button_class ); ?>"><?php echo esc_html( $text ); ?></button>
					</div>
				</div>
			</div>
			<?php
		}
	}

	Woostify_Sticky_Button::get_instance();
endif;
