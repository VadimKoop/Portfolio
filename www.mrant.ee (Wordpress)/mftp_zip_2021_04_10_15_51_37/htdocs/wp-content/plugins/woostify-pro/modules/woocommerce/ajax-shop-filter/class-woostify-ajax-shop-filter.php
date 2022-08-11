<?php
/**
 * Woostify Ajax Shop Filter Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Ajax_Shop_Filter' ) ) {
	/**
	 * Woostify Ajax Shop Filter Class
	 */
	class Woostify_Ajax_Shop_Filter {

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
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );
			add_action( 'woocommerce_before_main_content', array( $this, 'clear_filter' ), 15 );
			add_action( 'wp_ajax_ajax_shop_filter', array( $this, 'ajax_shop_filter' ) );
			add_action( 'wp_ajax_nopriv_ajax_shop_filter', array( $this, 'ajax_shop_filter' ) );
			add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_AJAX_SHOP_FILTER' ) ) {
				define( 'WOOSTIFY_PRO_AJAX_SHOP_FILTER', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Adds body classes.
		 *
		 * @param      array $classes The body classes.
		 */
		public function add_body_classes( $classes ) {
			$classes[] = 'has-ajax-shop-filter';

			return $classes;
		}

		/**
		 * Clear filter
		 */
		public function clear_filter() {
			// Return if ony one url parameter and this value is empty.
			$empty_one_value = 1 === count( $_GET ) && empty( reset( $_GET ) ) ? true : false; // phpcs:ignore

			if ( empty( $_GET ) || is_singular() || is_customize_preview() || $empty_one_value ) { // phpcs:ignore
				return;
			}

			$url = wc_get_page_permalink( 'shop' );

			if ( is_product_category() ) {
				$url = get_term_link( get_queried_object()->term_id );
			}

			?>
			<div class="woostify-clear-filters widget woocommerce">
				<a class="woostify-clear-filter-item ti-close" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Clear Filters', 'woostify-pro' ); ?></a>

				<?php
				global $wp;
				$home = home_url( $wp->request );

				foreach ( $_GET as $k => $v ) { // phpcs:ignore
					if ( false !== strpos( $k, 'query_type' ) || false !== strpos( $k, 'post_type' ) || empty( $v ) ) {
						continue;
					}

					$get     = $_GET; // phpcs:ignore
					$comma   = false !== strpos( $v, ',' );
					$price   = false !== strpos( $k, 'price' );
					$_filter = false !== strpos( $k, '_filter' );
					$filter_ = false !== strpos( $k, 'filter_' );

					$name  = $k;
					$value = $v;
					$href  = '#';

					if ( $_filter ) {
						$name = str_replace( '_filter', '', $name );
					} elseif ( $filter_ ) {
						$name = str_replace( 'filter_', '', $name );
					} elseif ( 's' === $name ) {
						$name = __( 'Keyword', 'woostify-pro' );
					} elseif ( 'orderby' === $name ) {
						$name = __( 'Sort by', 'woostify-pro' );

						switch ( $v ) {
							case 'price':
								$name  = __( 'Sort by price', 'woostify-pro' ) . ':';
								$value = __( 'low to high', 'woostify-pro' );
								break;
							case 'price-desc':
								$name  = __( 'Sort by price', 'woostify-pro' ) . ':';
								$value = __( 'high to low', 'woostify-pro' );
								break;
							case 'date':
								$value = __( 'latest', 'woostify-pro' );
								break;
							case 'rating':
								$value = __( 'average rating', 'woostify-pro' );
								break;
							case 'popularity':
								$value = __( 'popularity', 'woostify-pro' );
								break;
							default:
								$name  = __( 'Default sorting', 'woostify-pro' );
								$value = '';
								break;
						}
					}

					$name = str_replace( '-', ' ', $name );
					$name = str_replace( '_', ' ', $name );
					$name = ucfirst( $name );

					// Price format.
					if ( $price ) {
						$value = wc_price( $v );
					}

					if ( $comma ) {
						foreach ( explode( ',', $v ) as $kv => $vl ) {
							$new_arr = explode( ',', $v );
							unset( $new_arr[ $kv ] );

							$new_str   = implode( ',', $new_arr );
							$get[ $k ] = $new_str;
							$href      = add_query_arg( $get, $home );
							?>
							<a class="woostify-clear-filter-item ti-close" href="<?php echo esc_url( $href ); ?>">
								<?php printf( '%s: %s', $name, $vl ); // phpcs:ignore ?>
							</a>
							<?php
						}
					} else {
						unset( $get[ $k ] );
						$href = add_query_arg( $get, $home );
						?>
						<a class="woostify-clear-filter-item ti-close" href="<?php echo esc_url( $href ); ?>">
							<?php printf( '%s%s %s', $name, 'orderby' === $k ? '' : ':', $value ); // phpcs:ignore ?>
						</a>
						<?php
					}
				}
				?>
			</div>
			<?php
		}

		/**
		 * Ajax shop filter
		 */
		public function ajax_shop_filter() {
			check_ajax_referer( 'ajax_shop_filter', 'ajax_nonce', false );

			$response = array();

			if ( ! isset( $_POST['ajax_product_search_keyword'] ) ) {
				wp_send_json_error();
			}

			$keyword = sanitize_text_field( wp_unslash( $_POST['ajax_product_search_keyword'] ) );
			$cat_id  = isset( $_POST['cat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_id'] ) ) : '';

			$args = array(
				'post_type'           => 'product',
				's'                   => $keyword,
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
			);

			// Query by category id.
			if ( ! $cat_id ) {
				$args['tax_query'] = array( // phpcs:ignore
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => intval( $cat_id ),
					),
				);
			}

			$query = new WP_Query( $args );

			ob_start();
			?>
			<div class="ajax-product-search-results">
				<?php
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
					}
					wp_reset_postdata();
				} else {
					?>
					<p class="woocommerce-info"><?php esc_html_e( 'No products found!', 'woostify-pro' ); ?></p>
					<?php
				}
				?>
			</div>
			<?php

			$response['content'] = ob_get_clean();

			wp_send_json_success( $response );
		}

		/**
		 * Script and style file.
		 */
		public function scripts() {
			$options = woostify_options( false );

			// Script.
			wp_enqueue_script(
				'woostify-ajax-shop-filter',
				WOOSTIFY_PRO_URI . 'modules/woocommerce/ajax-shop-filter/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_localize_script(
				'woostify-ajax-shop-filter',
				'woostify_ajax_shop_filter',
				array(
					'home_url' => home_url( '/' ),
					'shop_url' => wc_get_page_permalink( 'shop' ),
				)
			);

			// Style.
			wp_enqueue_style(
				'woostify-ajax-shop-filter',
				WOOSTIFY_PRO_URI . 'modules/woocommerce/ajax-shop-filter/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.woostify-clear-filter-item {
					border-color: ' . $options['theme_color'] . ';
				}
			';

			wp_add_inline_style( 'woostify-ajax-shop-filter', $styles );
		}
	}

	Woostify_Ajax_Shop_Filter::get_instance();
}
