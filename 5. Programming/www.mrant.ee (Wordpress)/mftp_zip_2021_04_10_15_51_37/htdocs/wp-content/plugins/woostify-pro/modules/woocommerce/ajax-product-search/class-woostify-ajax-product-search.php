<?php
/**
 * Woostify Ajax Product Search Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Ajax_Product_Search' ) ) :

	/**
	 * Woostify Ajax Product Search Class
	 */
	class Woostify_Ajax_Product_Search {

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
			$woocommerce_helper = Woostify_Woocommerce_Helper::init();

			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_filter( 'woostify_options_admin_submenu_label', array( $this, 'woostify_options_admin_submenu_label' ) );

			// For mobile search form.
			add_action( 'woostify_site_search_end', array( $this, 'add_search_results' ) );
			// For dialog search form.
			add_action( 'woostify_dialog_search_content_end', array( $this, 'add_search_results' ) );

			// Save settings.
			add_action( 'wp_ajax_woostify_save_ajax_search_product_options', array( $woocommerce_helper, 'save_options' ) );

			// Ajax for front end.
			add_action( 'wp_ajax_ajax_product_search', array( $this, 'ajax_product_search' ) );
			add_action( 'wp_ajax_nopriv_ajax_product_search', array( $this, 'ajax_product_search' ) );

			// Add Setting url.
			add_action( 'admin_menu', array( $this, 'add_setting_url' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_AJAX_PRODUCT_SEARCH' ) ) {
				define( 'WOOSTIFY_AJAX_PRODUCT_SEARCH', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Adds search results.
		 */
		public function add_search_results() {
			?>
			<div class="ajax-search-results"></div>
			<?php
		}

		/**
		 * Sets up.
		 */
		public function scripts() {
			$options       = Woostify_Pro::get_instance()->woostify_pro_options();
			$addon_options = $this->get_options();

			// Style.
			wp_enqueue_style(
				'woostify-ajax-product-search',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/ajax-product-search/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.aps-highlight {
					color: ' . esc_attr( $addon_options['highlight_color'] ) . ';
				}
			';

			wp_add_inline_style( 'woostify-ajax-product-search', $styles );

			/**
			 * Script
			 */
			wp_enqueue_script(
				'woostify-ajax-product-search',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/ajax-product-search/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			$data = array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_error' => __( 'Sorry, something went wrong. Please refresh this page and try again!', 'woostify-pro' ),
				'ajax_nonce' => wp_create_nonce( 'ajax_product_search' ),
			);

			$term = get_terms( 'product_cat' );

			if ( '1' === $addon_options['filter'] && $term ) {
				$select  = '<div class="ajax-category-filter-box">';
				$select .= '<select class="ajax-product-search-category-filter">';
				$select .= '<option value="">' . esc_html__( 'All', 'woostify-pro' ) . '</option>';
				foreach ( $term as $k ) {
					$select .= '<option value="' . esc_attr( $k->term_id ) . '">' . esc_html( $k->name ) . '</option>';
				}
				$select .= '</select>';
				$select .= '</div>';

				$data['select'] = $select;
			}

			wp_localize_script(
				'woostify-ajax-product-search',
				'woostify_ajax_product_search_data',
				$data
			);
		}

		/**
		 * Update First submenu for Welcome screen.
		 */
		public function woostify_options_admin_submenu_label() {
			return true;
		}

		/**
		 * Highlight keyword
		 *
		 * @param      string $str     The string.
		 * @param      string $keyword The keyword.
		 *
		 * @return     string  Highlight string
		 */
		public function highlight_keyword( $str, $keyword ) {
			$str     = html_entity_decode( strtolower( trim( $str ) ) );
			$keyword = wp_specialchars_decode( strtolower( trim( $keyword ) ) );

			return str_replace( $keyword, '<span class="aps-highlight">' . $keyword . '</span>', $str );
		}

		/**
		 * Strip all ' ', '-', '_' character
		 *
		 * @param      string $str The string.
		 */
		public function strip_all_char( $str ) {
			$str = strtolower( $str );
			$str = str_replace( ' ', '', $str );
			$str = str_replace( '-', '', $str );
			$str = str_replace( '_', '', $str );

			return $str;
		}

		/**
		 * Ajax product search
		 */
		public function ajax_product_search() {
			check_ajax_referer( 'ajax_product_search', 'ajax_nonce', false );
			$addon_options = $this->get_options();

			$response = array();

			if ( ! isset( $_POST['ajax_product_search_keyword'] ) ) {
				wp_send_json_error();
			}

			$keyword = sanitize_text_field( wp_unslash( $_POST['ajax_product_search_keyword'] ) );
			$cat_id  = isset( $_POST['cat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_id'] ) ) : array();

			$args = array(
				's'              => $keyword,
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $addon_options['total_product'],
			);

			// Query by category id.
			if ( ! empty( $cat_id ) ) {
				$args['tax_query'][] = array( // phpcs:ignore
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat_id,
				);
			}

			// Exclude out of stock products.
			if ( '1' === $addon_options['out_stock'] ) {
				$args['meta_query'][] = array( // phpcs:ignore
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => 'NOT IN',
				);
			}

			$list_query = new WP_Query( $args );
			$list_id    = $list_query->have_posts() ? wp_list_pluck( $list_query->posts, 'ID' ) : array();

			// This query without keyword.
			unset( $args['s'] );
			$query_legacy   = new WP_Query( $args );
			$legacy_list_id = $query_legacy->have_posts() ? wp_list_pluck( $query_legacy->posts, 'ID' ) : array();

			$has_same_sku = array();
			// If search by SKU enable.
			if ( $addon_options['search_by_sku'] && ! empty( $legacy_list_id ) ) {
				$strip_keyword = $this->strip_all_char( $keyword );

				foreach ( $legacy_list_id as $k ) {
					$product     = wc_get_product( $k );
					$is_variable = $product->is_type( 'variable' );
					$sku         = $product->get_sku();

					// For product variable.
					if ( $is_variable ) {
						$product_variation = wc_get_product( $k );
						$children_ids      = $product_variation->get_children();

						if ( ! empty( $children_ids ) ) {
							foreach ( $children_ids as $v ) {
								$new_product = wc_get_product( $v );
								$new_sku     = $new_product->get_sku();

								if ( ! $new_sku ) {
									continue;
								}

								$new_strip_sku = $this->strip_all_char( $new_sku );

								if ( false !== strpos( $new_strip_sku, $strip_keyword ) ) {
									array_push( $has_same_sku, $v );
								}
							}
						}
					}

					if ( ! $sku ) {
						continue;
					}

					$strip_sku = $this->strip_all_char( $sku );

					if ( false !== strpos( $strip_sku, $strip_keyword ) ) {
						array_push( $has_same_sku, $k );
					}
				}
			}

			$product_ids = array_unique( array_merge( $has_same_sku, $list_id ) );

			ob_start();
			?>
			<div class="ajax-product-search-results">
				<?php
				if ( ! empty( $product_ids ) ) {
					foreach ( $product_ids as $pid ) {
						$product   = wc_get_product( $pid );
						$image     = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'thumbnail' );
						$image_src = $image ? $image[0] : wc_placeholder_img_src();
						$title     = get_the_title( $pid );
						$price     = $product ? $product->get_price_html() : '';
						$sku       = $product ? $product->get_sku() : '';
						$highlight = $addon_options['search_by_title'] ? $this->highlight_keyword( $title, $keyword ) : $title;
						?>
						<div class="aps-item">
							<a class="aps-link" href="<?php echo esc_url( get_permalink( $pid ) ); ?>"></a>
							<img class="aps-thumbnail" src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
							<div class="asp-content">
								<h4 class="aps-title"><?php echo wp_kses_post( $highlight ); ?></h4>
								<div class="aps-price"><?php echo wp_kses_post( $price ); ?></div>

								<?php if ( $sku && $addon_options['search_by_sku'] ) { ?>
									<div class="aps-sku"><?php esc_html_e( 'SKU', 'woostify-pro' ); ?>: <span><?php echo wp_kses_post( $this->highlight_keyword( $sku, $keyword ) ); ?></span></div>
								<?php } ?>
							</div>
						</div>
						<?php
					}
				} else {
					?>
					<div class="aps-no-posts-found">
						<?php esc_html_e( 'No products found!', 'woostify-pro' ); ?>
					</div>
				<?php } ?>
			</div>
			<?php
			$response['list_id'] = $product_ids;
			$response['size']    = count( $product_ids );
			$response['result']  = sprintf( /* translators: product */ _n( '%s product', '%s products', $response['size'], 'wpdocs_textdomain' ), $response['size'] );
			$response['content'] = ob_get_clean();

			wp_send_json_success( $response );
		}

		/**
		 * Add submenu
		 *
		 * @see  add_submenu_page()
		 */
		public function add_setting_url() {
			$sub_menu = add_submenu_page( 'woostify-welcome', 'Settings', __( 'Ajax Product Search', 'woostify-pro' ), 'manage_options', 'ajax-search-product-settings', array( $this, 'add_settings_page' ) );
		}

		/**
		 * Register settings
		 */
		public function register_settings() {
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_category_filter' );
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_remove_out_stock_product' );
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_total' );
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_by_title' );
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_by_sku' );
			register_setting( 'ajax-search-product-settings', 'woostify_ajax_search_product_highlight_color' );
		}

		/**
		 * Get addon option
		 */
		public function get_options() {
			$options                    = array();
			$options['filter']          = get_option( 'woostify_ajax_search_product_category_filter', '0' );
			$options['out_stock']       = get_option( 'woostify_ajax_search_product_remove_out_stock_product', '0' );
			$options['total_product']   = get_option( 'woostify_ajax_search_product_total', '-1' );
			$options['search_by_title'] = get_option( 'woostify_ajax_search_product_by_title', '1' );
			$options['search_by_sku']   = get_option( 'woostify_ajax_search_product_by_sku', '1' );
			$options['highlight_color'] = get_option( 'woostify_ajax_search_product_highlight_color', '#ff0000' );

			return $options;
		}

		/**
		 * Add Settings page
		 */
		public function add_settings_page() {
			$options = $this->get_options();
			?>

			<div class="woostify-options-wrap woostify-featured-setting woostify-ajax-search-product-setting" data-id="ajax-search-product" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-ajax-search-product-setting-nonce' ) ); ?>">

				<?php Woostify_Admin::get_instance()->woostify_welcome_screen_header(); ?>

				<div class="woostify-settings-box">
					<div class="woostify-welcome-container">
						<div class="woostify-settings-content">
							<h4 class="woostify-settings-section-title"><?php esc_html_e( 'Ajax Product Search', 'woostify-pro' ); ?></h4>

							<div class="woostify-settings-section-content">
								<table class="form-table">
									<tr>
										<th><?php esc_html_e( 'Filter', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_ajax_search_product_category_filter">
												<input name="woostify_ajax_search_product_category_filter" type="checkbox" id="woostify_ajax_search_product_category_filter" <?php checked( $options['filter'], '1' ); ?> value="<?php echo esc_attr( $options['filter'] ); ?>">
												<?php esc_html_e( 'Display category filter', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr>
										<th><?php esc_html_e( 'Out stock product', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_ajax_search_product_remove_out_stock_product">
												<input name="woostify_ajax_search_product_remove_out_stock_product" type="checkbox" id="woostify_ajax_search_product_remove_out_stock_product" <?php checked( $options['out_stock'], '1' ); ?> value="<?php echo esc_attr( $options['out_stock'] ); ?>">
												<?php esc_html_e( 'Remove Out of stock products in search results', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr>
										<th><?php esc_html_e( 'Total product', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_ajax_search_product_total">
												<input name="woostify_ajax_search_product_total" type="number" id="woostify_ajax_search_product_total" value="<?php echo esc_attr( $options['total_product'] ); ?>">
											</label>
											<p class="woostify-setting-description"><?php esc_html_e( 'The total number of products, enter -1 to search all products', 'woostify-pro' ); ?></p>
										</td>
									</tr>

									<tr>
										<th><?php esc_html_e( 'Search by', 'woostify-pro' ); ?>:</th>
										<td class="must-choose-one-option">
											<p>
												<label for="woostify_ajax_search_product_by_title">
													<input name="woostify_ajax_search_product_by_title" type="checkbox" id="woostify_ajax_search_product_by_title" <?php checked( $options['search_by_title'], '1' ); ?> value="<?php echo esc_attr( $options['search_by_title'] ); ?>">
													<?php esc_html_e( 'Product title', 'woostify-pro' ); ?>
												</label>
											</p>

											<p>
												<label for="woostify_ajax_search_product_by_sku">
													<input name="woostify_ajax_search_product_by_sku" type="checkbox" id="woostify_ajax_search_product_by_sku" <?php checked( $options['search_by_sku'], '1' ); ?> value="<?php echo esc_attr( $options['search_by_sku'] ); ?>">
													<?php esc_html_e( 'Product sku', 'woostify-pro' ); ?>
												</label>
											</p>
										</td>
									</tr>

									<tr>
										<th><?php esc_html_e( 'Highlight color', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_ajax_search_product_highlight_color">
												<input class="woostify-admin-color-picker" name="woostify_ajax_search_product_highlight_color" type="text" id="woostify_ajax_search_product_highlight_color" value="<?php echo esc_attr( $options['highlight_color'] ); ?>">
											</label>
										</td>
									</tr>
								</table>
							</div>

							<div class="woostify-settings-section-footer">
								<span class="save-options button button-primary"><?php esc_html_e( 'Save', 'woostify-pro' ); ?></span>
								<span class="spinner"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	Woostify_Ajax_Product_Search::get_instance();
endif;
