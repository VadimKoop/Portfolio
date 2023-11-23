<?php
/**
 * Woostify Ajax Product Tab Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Ajax_Product_Tab' ) ) {
	/**
	 * Woostify Ajax Product Tab Class
	 */
	class Woostify_Ajax_Product_Tab {

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
			add_action( 'wp_ajax_product_tab', array( $this, 'render' ) );
			add_action( 'wp_ajax_nopriv_product_tab', array( $this, 'render' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_AJAX_PRODUCT_TAB' ) ) {
				define( 'WOOSTIFY_PRO_AJAX_PRODUCT_TAB', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Sets up.
		 */
		public function scripts() {
			wp_enqueue_script(
				'woostify-ajax-product-tab',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/ajax-product-tabs/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_localize_script(
				'woostify-ajax-product-tab',
				'woostify_ajax_product_tab_data',
				array(
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'woostify_ajax_product_tab' ),
				)
			);
		}

		/**
		 * Ajax single add to cart
		 */
		public function render() {
			$response = array();

			// Check nonce.
			check_ajax_referer( 'woostify_ajax_product_tab', 'ajax_nonce' );

			if ( ! isset( $_POST['tab_id'] ) || empty( $_POST['tab_query'] ) ) {
				wp_send_json_error();
			}

			$args    = (array) json_decode( sanitize_text_field( wp_unslash( $_POST['tab_query'] ) ), true ); // Return array, not stdClass output.
			$columns = isset( $_POST['tab_columns'] ) ? absint( $_POST['tab_columns'] ) : 3;
			$query   = new WP_Query( $args );

			ob_start();

			if ( ! $query->have_posts() ) {
				?>
				<p class="text-center no-products-found woocommerce-info"><?php esc_html_e( 'No products found!', 'woostify-pro' ); ?></p>
				<?php
			} else {
				global $woocommerce_loop;
				$woocommerce_loop['columns'] = $columns;

				woocommerce_product_loop_start();

				while ( $query->have_posts() ) :
					$query->the_post();

					wc_get_template_part( 'content', 'product' );
				endwhile;

				// Reset loop.
				woocommerce_reset_loop();
				wp_reset_postdata();

				woocommerce_product_loop_end();
			}
			$response['count']   = $query->post_count;
			$response['content'] = ob_get_clean();

			wp_send_json_success( $response );
		}
	}

	Woostify_Ajax_Product_Tab::get_instance();
}
