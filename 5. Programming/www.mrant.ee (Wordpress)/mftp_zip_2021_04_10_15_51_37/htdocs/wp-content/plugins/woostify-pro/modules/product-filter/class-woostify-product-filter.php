<?php
/**
 * Woostify template builder for woocommerce
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Product_Filter' ) ) {
	/**
	 * Class for woostify Header Footer builder.
	 */
	class Woostify_Product_Filter {
		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function init() {
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

			add_action( 'init', array( $this, 'init_action' ) );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'add_meta_boxes_product_filter', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );

			// Register product template widget.
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'add_widgets' ) );

			// Script.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'frontend_scripts' ) );

			// Product filter.
			add_action( 'wp_ajax_woostify_product_filter', array( $this, 'woostify_product_filter' ) );
			add_action( 'wp_ajax_nopriv_woostify_product_filter', array( $this, 'woostify_product_filter' ) );

			// Add Template Type column on 'woo_builder' list in admin screen.
			add_filter( 'manage_product_filter_posts_columns', array( $this, 'add_column_head' ), 10 );
			add_action( 'manage_product_filter_posts_custom_column', array( $this, 'add_column_content' ), 10, 2 );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_PRODUCT_FILTER' ) ) {
				define( 'WOOSTIFY_PRO_PRODUCT_FILTER', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Init
		 */
		public function init_action() {
			// Register prodyuct_filter post type.
			$args = array(
				'label'               => _x( 'Product Filter', 'post type label', 'woostify-pro' ),
				'singular_name'       => _x( 'Product Filter', 'post type singular name', 'woostify-pro' ),
				'supports'            => array( 'title' ),
				'rewrite'             => array( 'slug' => 'product-filter' ),
				'show_in_rest'        => true,
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'product_filter', $args );

			// Flush rewrite rules.
			if ( ! get_option( 'woostify_product_filter_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				update_option( 'woostify_product_filter_flush_rewrite_rules', true );
			}
		}

		/**
		 * Add size guide admin menu
		 */
		public function add_admin_menu() {
			add_submenu_page( 'woostify-welcome', esc_html__( 'Product Filter', 'woostify-pro' ), esc_html__( 'Product Filter', 'woostify-pro' ), 'manage_options', 'edit.php?post_type=product_filter' );
		}

		/**
		 * Metabox
		 */
		public function add_meta_box() {
			add_meta_box(
				'woostify_product_filter',
				__( 'Product Filter Settings', 'woostify-pro' ),
				array( $this, 'add_meta_box_callback' ),
				null,
				'normal',
				'high'
			);
		}

		/**
		 * Metabox callback
		 *
		 * @param object $post The post.
		 */
		public function add_meta_box_callback( $post ) {
			wp_nonce_field( 'woostify_product_filter_metabox_nonce', 'woostify_product_filter_nonce_value' );
			$wc_tax      = wc_get_attribute_taxonomies();
			$post_id     = $post->ID;
			$filter_type = get_post_meta( $post_id, 'woostify_product_filter_type', true );
			$filter_data = get_post_meta( $post_id, 'woostify_product_filter_data', true );

			// Check range.
			$filter_check_range_min   = get_post_meta( $post_id, 'woostify_product_filter_check_range_min', true );
			$filter_check_range_max   = get_post_meta( $post_id, 'woostify_product_filter_check_range_max', true );
			$filter_check_range_query = get_post_meta( $post_id, 'woostify_product_filter_check_range_query', true );
			$filter_check_range_query = $filter_check_range_query ? $filter_check_range_query : '_price';

			// Range slider.
			$filter_range_slider_from  = get_post_meta( $post_id, 'woostify_product_filter_range_slider_from', true );
			$filter_range_slider_to    = get_post_meta( $post_id, 'woostify_product_filter_range_slider_to', true );
			$filter_range_slider_query = get_post_meta( $post_id, 'woostify_product_filter_range_slider_query', true );
			$filter_range_slider_query = $filter_range_slider_query ? $filter_range_slider_query : '_price';

			// Product category data.
			$product_cat_exclude_ids   = get_post_meta( $post_id, 'woostify_product_filter_product_cat_exclude_ids', true );
			$product_cat_include_child = intval( get_post_meta( $post_id, 'woostify_product_filter_product_cat_include_child', true ) );
			$product_cat_expand        = intval( get_post_meta( $post_id, 'woostify_product_filter_product_cat_expand', true ) );
			?>

			<table class="form-table admin-product-filter">
				<tbody>
					<tr class="woostify-filter-item">
						<th><?php esc_html_e( 'Filter Type', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="w-filter-container">
								<select class="woostify-filter-value" name="woostify_product_filter_type">
									<option value=""><?php esc_html_e( 'Select filter type', 'woostify-pro' ); ?></option>
									<option value="radio" <?php selected( $filter_type, 'radio' ); ?>><?php esc_html_e( 'Radio', 'woostify-pro' ); ?></option>
									<option value="search" <?php selected( $filter_type, 'search' ); ?>><?php esc_html_e( 'Search', 'woostify-pro' ); ?></option>
									<option value="select" <?php selected( $filter_type, 'select' ); ?>><?php esc_html_e( 'Select', 'woostify-pro' ); ?></option>
									<option value="rating" <?php selected( $filter_type, 'rating' ); ?>><?php esc_html_e( 'Rating', 'woostify-pro' ); ?></option>
									<option value="range_slider" <?php selected( $filter_type, 'range_slider' ); ?>><?php esc_html_e( 'Range Slider', 'woostify-pro' ); ?></option>
									<option value="checkbox" <?php selected( $filter_type, 'checkbox' ); ?>><?php esc_html_e( 'Checkbox', 'woostify-pro' ); ?></option>
									<option value="check_range" <?php selected( $filter_type, 'check_range' ); ?>><?php esc_html_e( 'Check range', 'woostify-pro' ); ?></option>
									<option value="date_range" <?php selected( $filter_type, 'date_range' ); ?>><?php esc_html_e( 'Date range', 'woostify-pro' ); ?></option>
									<option value="sort_order" <?php selected( $filter_type, 'sort_order' ); ?>><?php esc_html_e( 'Sort order', 'woostify-pro' ); ?></option>
								</select>
							</div>
						</td>
					</tr>

					<?php // For Radio, Select, Checkbox field. ?>
					<tr class="woostify-filter-item <?php echo esc_attr( in_array( $filter_type, array( 'radio', 'select', 'checkbox' ), true ) ? '' : 'hidden' ); ?>" data-type="radio|select|checkbox">
						<th><?php esc_html_e( 'Data Source', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="w-filter-container">
								<select class="woostify-filter-value" name="woostify_product_filter_data">
									<option value=""><?php esc_html_e( 'Select data source', 'woostify-pro' ); ?></option>
									<option value="product_cat" <?php selected( $filter_data, 'product_cat' ); ?>><?php esc_html_e( 'Product Category', 'woostify-pro' ); ?></option>
									<option value="product_tag" <?php selected( $filter_data, 'product_tag' ); ?>><?php esc_html_e( 'Product Tag', 'woostify-pro' ); ?></option>
									<?php
									if ( ! empty( $wc_tax ) ) {
										foreach ( $wc_tax as $tax ) {
											if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
												?>
												<option value="<?php echo esc_attr( $tax->attribute_id ); ?>" <?php selected( $filter_data, $tax->attribute_id ); ?>><?php esc_html_e( 'Product attribute', 'woostify-pro' ); ?>: <?php echo esc_html( $tax->attribute_label ); ?></option>
												<?php
											}
										}
									}
									?>
								</select>

								<div class="w-filter-category-data">
									<div class="w-filter-category-item">
										<span><?php esc_html_e( 'Exclude Categories', 'woostify-pro' ); ?></span>
										<input type="text" value="<?php echo esc_attr( $product_cat_exclude_ids ); ?>" placeholder="<?php esc_attr_e( 'Enter some product categories id by "," separating values.', 'woostify-pro' ); ?>" name="woostify_product_filter_product_cat_exclude_ids">
									</div>

									<div class="w-filter-category-item">
										<label for="w-filter-source-category-child">
											<input type="checkbox" <?php checked( $product_cat_include_child, 1 ); ?> value="<?php echo esc_attr( $product_cat_include_child ); ?>" id="w-filter-source-category-child" name="woostify_product_filter_product_cat_include_child">
											<span><?php esc_html_e( 'Include category children', 'woostify-pro' ); ?></span>
										</label>
									</div>

									<div class="w-filter-category-item<?php echo esc_attr( 1 !== $product_cat_include_child ? ' hidden' : '' ); ?>">
										<label for="w-filter-source-category-expand">
											<input type="checkbox" <?php checked( $product_cat_expand, 1 ); ?> value="1" id="w-filter-source-category-expand" name="woostify_product_filter_product_cat_expand">
											<span><?php esc_html_e( 'Expand by default', 'woostify-pro' ); ?></span>
										</label>
									</div>
								</div>
							</div>
						</td>
					</tr>

					<?php // Range slider. ?>
					<tr class="woostify-filter-item <?php echo esc_attr( 'range_slider' === $filter_type ? '' : 'hidden' ); ?>" data-type="range_slider">
						<th><?php esc_html_e( 'Range Slider', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="w-filter-container">
								<div class="w-filter-item-50">
									<label class="w-filter-item-col">
										<span><?php esc_html_e( 'From', 'woostify-pro' ); ?></span>
										<input type="number" placeholder="<?php esc_attr_e( 'Enter value', 'woostify-pro' ); ?>" class="w-filter-range-slider range-slider-from" name="woostify_product_filter_range_slider_from" value="<?php echo esc_attr( $filter_range_slider_from ); ?>">
									</label>

									<label class="w-filter-item-col">
										<span><?php esc_html_e( 'To', 'woostify-pro' ); ?></span>
										<input type="number" placeholder="<?php esc_attr_e( 'Enter value', 'woostify-pro' ); ?>" class="w-filter-range-slider range-slider-to" name="woostify_product_filter_range_slider_to" value="<?php echo esc_attr( $filter_range_slider_to ); ?>">
									</label>
								</div>

								<label class="w-filter-item-query">
									<span><?php esc_html_e( 'Query field key', 'woostify-pro' ); ?></span>
									<input type="text" class="w-filter-item-query-value" value="<?php echo esc_attr( $filter_range_slider_query ); ?>" readonly name="woostify_product_filter_range_slider_query">
								</label>
							</div>
						</td>
					</tr>

					<?php // Check range. ?>
					<tr class="woostify-filter-item <?php echo esc_attr( 'check_range' === $filter_type ? '' : 'hidden' ); ?>" data-type="check_range">
						<th><?php esc_html_e( 'Price Range', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="w-filter-container w-filter-check-range">
								<div class="w-filter-range-item-wrap">
									<?php
									if ( ! empty( $filter_check_range_min ) && ! empty( $filter_check_range_max ) ) {
										foreach ( $filter_check_range_min as $k => $v ) {
											?>
											<div class="w-filter-range-item">
												<span class="w-filter-range-item-remove dashicons dashicons-no-alt"></span>

												<div class="w-filter-item-50">
													<div class="w-filter-item-col">
														<label><?php esc_html_e( 'Min value', 'woostify-pro' ); ?></label>
														<input class="w-filter-required" required type="number" name="woostify_product_filter_check_range_min[]" value="<?php echo esc_attr( $v ); ?>">
													</div>

													<div class="w-filter-item-col">
														<label><?php esc_html_e( 'Max value', 'woostify-pro' ); ?></label>
														<input class="w-filter-required" required type="number" name="woostify_product_filter_check_range_max[]" value="<?php echo esc_attr( isset( $filter_check_range_max[ $k ] ) ? $filter_check_range_max[ $k ] : 0 ); ?>">
													</div>
												</div>
											</div>
											<?php
										}
									}
									?>
								</div>

								<label class="w-filter-item-query">
									<span><?php esc_html_e( 'Query field key', 'woostify-pro' ); ?></span>
									<input type="text" class="w-filter-item-query-value" value="<?php echo esc_attr( $filter_check_range_query ); ?>" readonly name="woostify_product_filter_check_range_query">
								</label>

								<button class="w-filter-range-item-add button button-primary button-large" type="button"><?php esc_html_e( 'Add new option', 'woostify-pro' ); ?></button>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}

		/**
		 * Save metabox
		 *
		 * @param int    $post_id The post ID.
		 * @param object $post    The post.
		 */
		public function save_meta_box( $post_id, $post ) {
			$nonce = isset( $_POST['woostify_product_filter_nonce_value'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_nonce_value'] ) ) : false;
			if (
				wp_is_post_revision( $post_id ) ||
				! current_user_can( 'edit_post', $post_id ) ||
				'product_filter' !== $post->post_type ||
				! $nonce ||
				! wp_verify_nonce( $nonce, 'woostify_product_filter_metabox_nonce' )
			) {
				return;
			}

			$filter_type = isset( $_POST['woostify_product_filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_type'] ) ) : false;
			$filter_data = isset( $_POST['woostify_product_filter_data'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_data'] ) ) : false;

			// Product category data.
			$product_cat_exclude_ids   = isset( $_POST['woostify_product_filter_product_cat_exclude_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_product_cat_exclude_ids'] ) ) : false;
			$product_cat_include_child = isset( $_POST['woostify_product_filter_product_cat_include_child'] ) ? intval( $_POST['woostify_product_filter_product_cat_include_child'] ) : 0;
			$product_cat_expand        = isset( $_POST['woostify_product_filter_product_cat_expand'] ) ? intval( $_POST['woostify_product_filter_product_cat_expand'] ) : 0;

			// Check range.
			$filter_check_range_min   = isset( $_POST['woostify_product_filter_check_range_min'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['woostify_product_filter_check_range_min'] ) ) : array();
			$filter_check_range_max   = isset( $_POST['woostify_product_filter_check_range_max'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['woostify_product_filter_check_range_max'] ) ) : array();
			$filter_check_range_query = isset( $_POST['woostify_product_filter_check_range_query'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_check_range_query'] ) ) : false;
			$filter_check_range_query = $filter_check_range_query ? $filter_check_range_query : '_price';

			// Range slider.
			$filter_range_slider_from  = isset( $_POST['woostify_product_filter_range_slider_from'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_range_slider_from'] ) ) : false;
			$filter_range_slider_to    = isset( $_POST['woostify_product_filter_range_slider_to'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_range_slider_to'] ) ) : false;
			$filter_range_slider_query = isset( $_POST['woostify_product_filter_range_slider_query'] ) ? sanitize_text_field( wp_unslash( $_POST['woostify_product_filter_range_slider_query'] ) ) : false;
			$filter_range_slider_query = $filter_range_slider_query ? $filter_range_slider_query : '_price';

			// Update post meta.
			update_post_meta( $post_id, 'woostify_product_filter_type', $filter_type );
			update_post_meta( $post_id, 'woostify_product_filter_data', $filter_data );
			update_post_meta( $post_id, 'woostify_product_filter_product_cat_exclude_ids', $product_cat_exclude_ids );
			update_post_meta( $post_id, 'woostify_product_filter_product_cat_include_child', $product_cat_include_child );
			update_post_meta( $post_id, 'woostify_product_filter_product_cat_expand', $product_cat_expand );
			update_post_meta( $post_id, 'woostify_product_filter_check_range_min', $filter_check_range_min );
			update_post_meta( $post_id, 'woostify_product_filter_check_range_max', $filter_check_range_max );
			update_post_meta( $post_id, 'woostify_product_filter_check_range_query', $filter_check_range_query );
			update_post_meta( $post_id, 'woostify_product_filter_range_slider_from', $filter_range_slider_from );
			update_post_meta( $post_id, 'woostify_product_filter_range_slider_to', $filter_range_slider_to );
			update_post_meta( $post_id, 'woostify_product_filter_range_slider_query', $filter_range_slider_query );
		}

		/**
		 * Adds widgets.
		 */
		public function add_widgets() {
			$args = array(
				'post_type'      => 'product_filter',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);

			$filter = new WP_Query( $args );
			if ( ! $filter->have_posts() ) {
				return;
			}

			require_once WOOSTIFY_PRO_MODULES_PATH . 'product-filter/widget/class-woostify-filter-base.php';

			while ( $filter->have_posts() ) {
				$filter->the_post();

				$filter_type = get_post_meta( get_the_ID(), 'woostify_product_filter_type', true );
				if ( ! $filter_type ) {
					continue;
				}

				$filter_type = str_replace( '_', '-', $filter_type );
				$file_path   = WOOSTIFY_PRO_MODULES_PATH . 'product-filter/widget/class-woostify-filter-type-' . $filter_type . '.php';

				if ( file_exists( $file_path ) ) {
					require_once $file_path;
				}
			}

			wp_reset_postdata();
		}

		/**
		 * Product filter
		 */
		public function woostify_product_filter() {
			check_ajax_referer( 'woostify_product_filter', 'ajax_nonce' );

			$per_page = isset( $_POST['per_page'] ) ? sanitize_text_field( wp_unslash( $_POST['per_page'] ) ) : get_option( 'posts_per_page' );
			$per_page = intval( $per_page );
			$paged    = isset( $_POST['paged'] ) ? intval( wp_unslash( $_POST['paged'] ) ) : ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
			$data     = isset( $_POST['data'] ) ? (array) json_decode( sanitize_text_field( wp_unslash( $_POST['data'] ) ), true ) : false;
			$no_posts = '<span class="woocommerce-info">' . esc_html__( 'No posts found!', 'woostify-pro' ) . '</span>';

			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'paged'          => $paged,
			);

			$filtered_html = '';

			if ( ! empty( $data ) ) {
				foreach ( $data as $k => $v ) {
					if ( empty( $v ) ) {
						continue;
					}

					switch ( $k ) {
						case 'keyword':
							$args['s'] = $v;

							$filtered_html .= '<span class="w-filter-key-remove" data-type="search">' . sprintf( /* translators: keyword */__( 'Keyword: %s', 'woostify-pro' ), $v ) . ' <span class="w-filter-key-remove-icon"></span></span>';
							break;
						case 'date_query':
							if ( ! empty( $v[0] ) && ! empty( $v[1] ) ) {
								$args['date_query'][] = array(
									'after'  => $v[0],
									'before' => $v[1],
								);

								$filtered_html .= '<span class="w-filter-key-remove" data-type="date-range">' . sprintf( /* translators: date range */__( 'Date: %1$s - %2$s', 'woostify-pro' ), $v[0], $v[1] ) . ' <span class="w-filter-key-remove-icon"></span></span>';
							}
							break;
						case false !== strpos( $k, 'check_range' ):
						case false !== strpos( $k, 'range_slider' ):
							$meta_key = false !== strpos( $k, 'check_range' ) ? str_replace( 'check_range', '', $k ) : str_replace( 'range_slider', '', $k );

							$filter_name = str_replace( '_', '', $meta_key );
							$filter_name = str_replace( '-', '', $filter_name );
							$filter_name = ucfirst( $filter_name );

							if ( false !== strpos( $k, 'check_range' ) ) {
								$check_range_query = array(
									'relation' => 'OR',
								);

								foreach ( $v as $vv ) {
									$value_arr = json_decode( $vv, true );

									array_push(
										$check_range_query,
										array(
											'key'     => $meta_key,
											'value'   => $value_arr,
											'compare' => 'BETWEEN',
											'type'    => 'NUMERIC',
										)
									);

									$value_from = wp_kses( wc_price( $value_arr[0] ), array() );
									$value_to   = wp_kses( wc_price( $value_arr[1] ), array() );

									$filtered_html .= '<span class="w-filter-key-remove" data-type="check-range" data-source="' . $meta_key . '" data-value="' . $vv . '">' . sprintf( /* translators: check range */__( '%1$s: %2$s - %3$s', 'woostify-pro' ), $filter_name, $value_from, $value_to ) . ' <span class="w-filter-key-remove-icon"></span></span>';
								}

								$args['meta_query'][][] = $check_range_query;
							} else {
								$args['meta_query'][] = array(
									'key'     => $meta_key,
									'value'   => $v,
									'compare' => 'BETWEEN',
									'type'    => 'NUMERIC',
								);

								if ( isset( $v[0] ) && isset( $v[1] ) ) {
									$slider_from = wp_kses( wc_price( $v[0] ), array() );
									$slider_to   = wp_kses( wc_price( $v[1] ), array() );

									$filtered_html .= '<span class="w-filter-key-remove" data-type="range-slider" data-source="' . $meta_key . '">' . sprintf( /* translators: range slider */__( '%1$s: %2$s - %3$s', 'woostify-pro' ), $filter_name, $slider_from, $slider_to ) . ' <span class="w-filter-key-remove-icon"></span></span>';
								}
							}
							break;
						case 'rating':
							$args['meta_query'][] = array(
								'key'     => '_wc_average_rating',
								'value'   => $v,
								'compare' => '>=',
								'type'    => 'NUMERIC',
							);

							$filtered_html .= '<span class="w-filter-key-remove" data-type="rating">' . sprintf( /* translators: rating */__( 'Rating: %s', 'woostify-pro' ), $v ) . ' <span class="w-filter-key-remove-icon"></span></span>';
							break;
						case false !== strpos( $k, 'pa_' ):
						case 'product_cat':
						case 'product_tag':
							$args['tax_query'][] = array(
								'taxonomy'         => $k,
								'terms'            => $v,
								'include_children' => false,
							);

							if ( 'product_cat' === $k ) {
								$filter_key = __( 'Category', 'woostify-pro' );
							} elseif ( 'product_tag' === $k ) {
								$filter_key = __( 'Tag', 'woostify-pro' );
							} else {
								$filter_key = str_replace( 'pa_', '', $k );
								$filter_key = str_replace( '_', ' ', $filter_key );
								$filter_key = str_replace( '-', ' ', $filter_key );
								$filter_key = ucfirst( $filter_key );
							}

							if ( is_array( $v ) ) {
								foreach ( $v as $vv ) {
									$get_terms = get_term_by( 'id', $vv, $k );

									$filtered_html .= '<span class="w-filter-key-remove" data-type="terms" data-source="' . $get_terms->taxonomy . '" data-id="' . $get_terms->term_id . '" data-multi="yes">' . sprintf( /* translators: taxonomy name, value */__( '%1$s: %2$s', 'woostify-pro' ), $filter_key, $get_terms->name ) . ' <span class="w-filter-key-remove-icon"></span></span>';
								}
							} else {
								$get_term = get_term_by( 'id', $v, $k );

								$filtered_html .= '<span class="w-filter-key-remove" data-type="terms" data-source="' . $get_term->taxonomy . '" data-id="' . $get_term->term_id . '">' . sprintf( /* translators: taxonomy name, value*/__( '%1$s: %2$s', 'woostify-pro' ), $filter_key, $get_term->name ) . ' <span class="w-filter-key-remove-icon"></span></span>';
							}
							break;
						case 'sort-order':
							$args['orderby'] = 'meta_value_num';

							switch ( $v ) {
								case 'rating':
									$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore
									break;
								case 'popularity':
									$args['meta_key'] = 'total_sales'; // phpcs:ignore
									break;
								case 'date':
									$args['orderby'] = 'date';
									break;
								case 'menu_order':
									break;
								case 'price':
									$args['meta_key'] = '_price'; // phpcs:ignore
									$args['order']    = 'ASC';
									break;
								case 'price-desc':
									$args['meta_key'] = '_price'; // phpcs:ignore
									break;
							}
							break;
					}
				}
			}

			if ( $filtered_html ) {
				$filtered_html .= '<span class="w-filter-key-remove" data-type="clear">' . __( 'Clear', 'woostify-pro' ) . ' <span class="w-filter-key-remove-icon"></span></span>';
			}

			$products = new WP_Query( $args );

			// Pagination.
			$pagination = false;
			if ( $products->have_posts() ) {
				ob_start();
				$base  = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
				$total = ceil( $products->found_posts / $per_page );

				echo paginate_links( // phpcs:ignore
					array(
						'base'      => $base,
						'format'    => '',
						'add_args'  => false,
						'current'   => max( 1, $paged ),
						'total'     => $total,
						'prev_text' => esc_html__( 'Prev', 'woostify-pro' ),
						'next_text' => esc_html__( 'Next', 'woostify-pro' ),
						'type'      => 'list',
						'end_size'  => 3,
						'mid_size'  => 3,
					)
				);
				$pagination = ob_get_clean();
			}

			// Products.
			ob_start();
			if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {
					$products->the_post();

					wc_get_template_part( 'content', 'product' );
				}

				wp_reset_postdata();
			} else {
				echo wp_kses_post( $no_posts );
			}
			$res['count'] = $products->found_posts;
			$res['paged'] = $paged;
			$res['query'] = $data;
			$res['args']  = $args;

			$res['filtered']   = $filtered_html;
			$res['pagination'] = $pagination;
			$res['content']    = ob_get_clean();

			// Response.
			wp_send_json_success( $res );
		}

		/**
		 * Column head
		 *
		 * @param      array $defaults  The defaults.
		 */
		public function add_column_head( $defaults ) {
			$order = array();
			$title = 'title';
			foreach ( $defaults as $key => $value ) {
				$order[ $key ] = $value;

				if ( $key === $title ) {
					$order['product_filter_type'] = __( 'Type', 'woostify-pro' );
					$order['product_filter_data'] = __( 'Data Source', 'woostify-pro' );
				}
			}

			return $order;
		}

		/**
		 * Column content
		 *
		 * @param      string $column_name  The column name.
		 * @param      int    $post_id      The post id.
		 */
		public function add_column_content( $column_name, $post_id ) {
			$type = get_post_meta( $post_id, 'woostify_product_filter_type', true );
			$data = get_post_meta( $post_id, 'woostify_product_filter_data', true );

			switch ( $column_name ) {
				case 'product_filter_type':
					$type = $type ? str_replace( '_', ' ', $type ) : '-';
					?>
					<span><?php echo esc_html( ucwords( $type ) ); ?></span>
					<?php
					break;
				case 'product_filter_data':
					switch ( $data ) {
						case 'product_cat':
							$title = __( 'Product Category', 'woostify-pro' );
							break;
						case 'product_tag':
							$title = __( 'Product Tag', 'woostify-pro' );
							break;
						case 'product_attr':
							$title = __( 'Product Attribute', 'woostify-pro' );
							break;
						default:
							$title = '-';
							break;
					}
					?>
					<span><?php echo esc_html( $title ); ?></span>
					<?php
					break;
			}
		}

		/**
		 * Admin scripts
		 */
		public function admin_enqueue_assets() {
			$screen = get_current_screen();
			if ( is_object( $screen ) && 'product_filter' === $screen->post_type ) {
				$item_node  = '<div class="w-filter-range-item">';
				$item_node .= '<span class="w-filter-range-item-remove dashicons dashicons-no-alt"></span>';
				$item_node .= '<div class="w-filter-item-50">';
				$item_node .= '<div class="w-filter-item-col">';
				$item_node .= '<label>' . __( 'Min value', 'woostify-pro' ) . '</label>';
				$item_node .= '<input class="w-filter-required" required type="number" name="woostify_product_filter_check_range_min[]">';
				$item_node .= '</div>';
				$item_node .= '<div class="w-filter-item-col">';
				$item_node .= '<label>' . __( 'Max value', 'woostify-pro' ) . '</label>';
				$item_node .= '<input class="w-filter-required" required type="number" name="woostify_product_filter_check_range_max[]">';
				$item_node .= '</div>';
				$item_node .= '</div>';
				$item_node .= '</div>';

				wp_enqueue_style(
					'woostify-product-filter',
					WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/css/backend.css',
					array(),
					WOOSTIFY_PRO_VERSION
				);

				wp_enqueue_script(
					'woostify-product-filter',
					WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/js/backend' . woostify_suffix() . '.js',
					array(),
					WOOSTIFY_PRO_VERSION,
					true
				);

				$data = array(
					'item_node' => $item_node,
				);

				wp_localize_script(
					'woostify-product-filter',
					'woostify_product_filter',
					$data
				);
			}
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_assets() {
			wp_enqueue_style(
				'woostify-product-filter',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/css/product-filter.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function frontend_scripts() {
			// Date picker lib.
			wp_register_style(
				'tiny-datepicker',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/css/tiny-date-picker.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			wp_register_script(
				'tiny-datepicker',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/js/lib/tiny-date-picker' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			$days = array(
				esc_html_x( 'Sun', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Mon', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Tue', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Web', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Thu', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Fri', 'Day of the week', 'woostify-pro' ),
				esc_html_x( 'Sat', 'Day of the week', 'woostify-pro' ),
			);

			$months = array(
				esc_html_x( 'January', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'February', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'March', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'April', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'May', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'June', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'July', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'August', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'September', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'October', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'November', 'Month of the year', 'woostify-pro' ),
				esc_html_x( 'December', 'Month of the year', 'woostify-pro' ),
			);

			wp_localize_script(
				'tiny-datepicker',
				'woostify_datepicker_data',
				array(
					'today'  => __( 'Today', 'woostify-pro' ),
					'clear'  => __( 'Clear', 'woostify-pro' ),
					'close'  => __( 'Close', 'woostify-pro' ),
					'days'   => $days,
					'months' => $months,
				)
			);

			// Range slider lib.
			wp_register_style(
				'nouislider',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/css/nouislider.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			wp_register_script(
				'nouislider',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/js/lib/nouislider' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			// General filter script.
			wp_register_script(
				'woostify-product-filter',
				WOOSTIFY_PRO_MODULES_URI . 'product-filter/assets/js/product-filter' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			$data = array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'woostify_product_filter' ),
			);

			wp_localize_script(
				'woostify-product-filter',
				'woostify_product_filter',
				$data
			);
		}
	}

	Woostify_Product_Filter::init();
}

