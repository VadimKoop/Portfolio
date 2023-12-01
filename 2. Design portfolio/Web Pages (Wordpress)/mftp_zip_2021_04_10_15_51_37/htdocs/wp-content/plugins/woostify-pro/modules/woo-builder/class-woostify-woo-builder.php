<?php
/**
 * Woostify template builder for woocommerce
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Woo_Builder' ) ) {
	/**
	 * Class for woostify Header Footer builder.
	 */
	class Woostify_Woo_Builder {
		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Already my account page
		 *
		 * @var already_my_account
		 */
		public static $already_my_account = false;

		/**
		 * Already cart page
		 *
		 * @var already_cart
		 */
		public static $already_cart = false;

		/**
		 * Already cart empty
		 *
		 * @var already_cart_empty
		 */
		public static $already_cart_empty = false;

		/**
		 * Already checkout page
		 *
		 * @var already_checkout
		 */
		public static $already_checkout = false;

		/**
		 * Already thankyou page
		 *
		 * @var already_thankyou
		 */
		public static $already_thankyou = false;

		/**
		 * Meta Option
		 *
		 * @var $meta_option
		 */
		private static $meta_option;

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

			// Register WC hooks in preview mode.
			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && 'woo_builder' === get_post_type( $_REQUEST['post'] ) && is_admin() ) { // phpcs:ignore
				add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
			}

			add_action( 'init', array( $this, 'init_action' ), 0 );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 5 );
			add_filter( 'template_include', array( $this, 'single_template' ), 20 );

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );

			// Register product template widget.
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'add_widgets' ) );

			// Script for woobuilder widget.
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'frontend_scripts' ) );

			// Render template.
			add_action( 'woostify_my_account_page_content', array( $this, 'render_my_account_page' ) );
			add_action( 'woostify_cart_page_content', array( $this, 'render_cart_page' ) );
			add_action( 'woostify_cart_empty_content', array( $this, 'render_cart_empty' ) );
			add_action( 'woostify_checkout_page_content', array( $this, 'render_checkout_page' ) );
			add_action( 'woostify_thankyou_page_content', array( $this, 'render_thankyou_page' ) );

			// Scripts and styles.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 200 );

			// Print dialog template.
			add_action( 'admin_footer', array( $this, 'add_new_template' ) );

			// Create new template.
			add_action( 'admin_action_woostify_add_new_template_builder', array( $this, 'admin_action_new_post' ) );

			// Search product data.
			add_action( 'wp_ajax_woo_builder_conditions_search_data', array( $this, 'woostify_woo_builder_conditions_search_data' ) );

			// Search product preview.
			add_action( 'wp_ajax_woo_builder_preview_search_data', array( $this, 'woostify_woo_builder_preview_search_data' ) );

			// Search product preview.
			add_action( 'wp_ajax_woo_builder_select_product_preview', array( $this, 'woostify_woo_builder_select_product_preview' ) );

			// Save conditons.
			add_action( 'wp_ajax_save_woo_builder_conditions', array( $this, 'woostify_save_woo_builder_conditions' ) );

			// Add body class.
			add_filter( 'body_class', array( $this, 'woostify_body_classes' ) );

			// Add Template Type column on 'woo_builder' list in admin screen.
			add_filter( 'manage_woo_builder_posts_columns', array( $this, 'add_column_head' ), 10 );
			add_action( 'manage_woo_builder_posts_custom_column', array( $this, 'add_column_content' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'remove_view_actions' ), 10 );
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 */
		public function woostify_body_classes( $classes ) {
			if (
				$this->product_page_woobuilder() ||
				$this->shop_archive_woobuilder() ||
				( is_account_page() && $this->template_exist( 'woostify_my_account_page' ) ) ||
				( is_cart() && $this->template_exist( 'woostify_cart_empty' ) && WC()->cart->is_empty() ) ||
				( is_cart() && $this->template_exist( 'woostify_cart_page' ) && ! WC()->cart->is_empty() ) ||
				( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) && $this->template_exist( 'woostify_checkout_page' ) ) ||
				( is_checkout() && is_wc_endpoint_url( 'order-received' ) && $this->template_exist( 'woostify_thankyou_page' ) )
			) {
				$classes[] = 'woobuilder-active';
			}

			return array_filter( $classes );
		}

		/**
		 * Detect product page build with woobuilder
		 */
		public function product_page_woobuilder() {
			$product_id = woostify_get_page_id();
			$product    = wc_get_product( $product_id );
			if ( ! $product || ! is_singular( 'product' ) ) {
				return false;
			}

			$product_cat = $product->get_category_ids();
			$product_tag = $product->get_tag_ids();
			$template_id = $this->get_template_id( 'woostify_product_page' );
			$select_id   = false;

			$str_cat = implode( '_', $product_cat );
			$str_tag = implode( '_', $product_tag );

			if ( empty( $template_id ) ) {
				return false;
			}

			$include_data = array();
			$exclude_data = array();

			foreach ( $template_id as $k => $v ) {
				$include = $v['include'];
				$exclude = $v['exclude'];

				if ( ! empty( $include ) ) {
					foreach ( $include as $in ) {
						$in_cat_id = false !== strpos( $in, 'in-cat-' );
						$in_tag_id = false !== strpos( $in, 'in-tag-' );
						if ( 'all' === $in ) {
							array_push( $include_data, $k );
						} elseif ( $in_cat_id ) {
							$str_in_cat_id = str_replace( 'in-cat-', '', $in );
							if ( 'in-cat-all' === $in || false !== strpos( $str_cat, $str_in_cat_id ) ) {
								array_push( $include_data, $k );
							}
						} elseif ( $in_tag_id ) {
							$str_in_tag_id = str_replace( 'in-tag-', '', $in );
							if ( 'in-cat-all' === $in || false !== strpos( $str_tag, $str_in_tag_id ) ) {
								array_push( $include_data, $k );
							}
						}
					}
				}

				if ( ! empty( $exclude ) ) {
					foreach ( $exclude as $ex ) {
						$ex_cat_id = false !== strpos( $ex, 'in-cat-' );
						$ex_tag_id = false !== strpos( $ex, 'in-tag-' );
						if ( $ex_cat_id ) {
							$str_ex_cat_id = str_replace( 'in-cat-', '', $ex );
							if ( 'in-cat-all' === $ex || false !== strpos( $str_cat, $str_ex_cat_id ) ) {
								array_push( $exclude_data, $k );
							}
						} elseif ( $ex_tag_id ) {
							$str_ex_tag_id = str_replace( 'in-tag-', '', $ex );
							if ( 'in-tag-all' === $ex || false !== strpos( $str_tag, $str_ex_tag_id ) ) {
								array_push( $exclude_data, $k );
							}
						}
					}
				}
			}

			$output_data = array_diff( array_unique( $include_data ), array_unique( $exclude_data ) );

			if ( ! empty( $output_data ) ) {
				$select_id = array_shift( $output_data );
			}

			return $select_id ? $select_id : false;
		}

		/**
		 * Detect shop archive page build with woobuilder
		 */
		public function shop_archive_woobuilder() {
			$template_id = $this->get_template_id();
			$select_id   = false;

			if ( empty( $template_id ) || ! woostify_is_product_archive() ) {
				return false;
			}

			$include_data = array();
			$exclude_data = array();

			foreach ( $template_id as $k => $v ) {
				$include = $v['include'];
				$exclude = $v['exclude'];

				if ( ! empty( $include ) ) {
					foreach ( $include as $in ) {
						$in_cat_id = (int) str_replace( 'in-cat-', '', $in );
						$in_tag_id = (int) str_replace( 'in-tag-', '', $in );

						if (
							'all' === $in ||
							( is_shop() && 'shop-page' === $in ) ||
							( is_tax( 'product_cat', $in_cat_id ) && false !== strpos( $in, 'in-cat-' ) ) ||
							( is_tax( 'product_tag', $in_cat_id ) && false !== strpos( $in, 'in-tag-' ) )
						) {
							array_push( $include_data, $k );
						}
					}
				}

				if ( ! empty( $exclude ) ) {
					foreach ( $exclude as $ex ) {
						$ex_cat_id = (int) str_replace( 'in-cat-', '', $ex );
						$ex_tag_id = (int) str_replace( 'in-tag-', '', $ex );

						if (
							( is_shop() && 'shop-page' === $ex ) ||
							( is_tax( 'product_cat', $ex_cat_id ) && 'in-cat-all' !== $ex && false !== strpos( $ex, 'in-cat-' ) ) ||
							( is_tax( 'product_tag', $ex_tag_id ) && 'in-tag-all' !== $ex && false !== strpos( $ex, 'in-tag-' ) )
						) {
							array_push( $exclude_data, $k );
						}
					}
				}
			}

			$output_data = array_diff( array_unique( $include_data ), array_unique( $exclude_data ) );

			if ( ! empty( $output_data ) ) {
				$select_id = array_shift( $output_data );
			}

			return $select_id ? $select_id : false;
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_WOO_BUILDER' ) ) {
				define( 'WOOSTIFY_PRO_WOO_BUILDER', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Is builder preview
		 */
		public function get_product_id() {
			$product_id = woostify_get_product_id();

			return $product_id;
		}

		/**
		 * Add item conditon
		 */
		public function add_new_condition() {
			$post_id = sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ); // phpcs:ignore
			$data    = get_post_meta( $post_id, 'woostify_woo_builder_template', true );

			// Text.
			$all_text = 'woostify_shop_page' === $data ? esc_html__( 'All Product Archives', 'woostify-pro' ) : esc_html__( 'All Products', 'woostify-pro' );
			?>

			<div class="woostify-condition-item">
				<select class="woostify-condition-item-type">
					<option value="include"><?php esc_html_e( 'Include', 'woostify-pro' ); ?></option>
					<option value="exclude"><?php esc_html_e( 'Exclude', 'woostify-pro' ); ?></option>
				</select>

				<select class="woostify-condition-item-field">
					<option value="all"><?php echo esc_html( $all_text ); ?></option>
					<?php if ( 'woostify_shop_page' === $data ) { ?>
						<option value="shop-page"><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></option>
					<?php } ?>
					<option value="in-cat"><?php esc_html_e( 'In Product Category', 'woostify-pro' ); ?></option>
					<option value="in-tag"><?php esc_html_e( 'In Product Tag', 'woostify-pro' ); ?></option>
				</select>

				<div class="woostify-condition-item-search">
					<span class="woostify-condition-item-search-view" data-id="all"><?php esc_html_e( 'All', 'woostify-pro' ); ?></span>
					<div class="woostify-condition-item-search-content">
						<input type="text" class="woostify-condition-item-search-field">
						<div class="woostify-condition-item-search-result"></div>
					</div>
				</div>

				<span class="woostify-condition-item-remove dashicons dashicons-no-alt"></span>
			</div>
			<?php
		}

		/**
		 * Get item condition
		 */
		public function get_item_condition() {
			// Get post ID.
			$post_id    = sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ); // phpcs:ignore
			$template   = get_post_meta( $post_id, 'woostify_woo_builder_template', true );
			$conditions = get_post_meta( $post_id, 'woostify_woo_builder_conditions', true );

			// Text.
			$all_text = 'woostify_shop_page' === $template ? esc_html__( 'All Product Archives', 'woostify-pro' ) : esc_html__( 'All Products', 'woostify-pro' );

			if ( ! empty( $conditions ) ) {
				$exclude_all = array(
					'data_type'  => 'exclude',
					'data_field' => 'all',
					'data_id'    => 'all',
				);

				// Print one condition item if has Exclude All.
				if ( $exclude_all === $conditions ) {
					?>
					<div class="woostify-condition-item">
						<select class="woostify-condition-item-type">
							<option value="include"><?php esc_html_e( 'Include', 'woostify-pro' ); ?></option>
							<option value="exclude" selected="selected"><?php esc_html_e( 'Exclude', 'woostify-pro' ); ?></option>
						</select>

						<select class="woostify-condition-item-field">
							<option value="all"  selected="selected"><?php echo esc_html( $all_text ); ?></option>
							<?php if ( 'woostify_shop_page' === $template ) { ?>
								<option value="shop-page"><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></option>
							<?php } ?>
							<option value="in-cat"><?php esc_html_e( 'In Product Category', 'woostify-pro' ); ?></option>
							<option value="in-tag"><?php esc_html_e( 'In Product Tag', 'woostify-pro' ); ?></option>
						</select>

						<div class="woostify-condition-item-search">
							<span class="woostify-condition-item-search-view" data-id="all"><?php esc_html_e( 'All', 'woostify-pro' ); ?></span>
							<div class="woostify-condition-item-search-content">
								<input type="text" class="woostify-condition-item-search-field">
								<div class="woostify-condition-item-search-result"></div>
							</div>
						</div>

						<span class="woostify-condition-item-remove dashicons dashicons-no-alt"></span>
					</div>
					<?php
				} else {
					foreach ( $conditions as $k ) {
						$data_type  = ! empty( $k['data_type'] ) ? $k['data_type'] : 'exclude';
						$data_field = ! empty( $k['data_field'] ) ? $k['data_field'] : 'all';
						$data_id    = ! empty( $k['data_id'] ) ? $k['data_id'] : 'all';

						switch ( $data_field ) {
							case 'in-cat':
								$taxonomy = 'product_cat';
								break;
							case 'in-tag':
								$taxonomy = 'product_tag';
								break;
							default:
								$taxonomy = 'faker__term';
								break;
						}

						$term      = get_term_by( 'id', $data_id, $taxonomy );
						$term_id   = empty( $term ) ? 'all' : $term->term_id;
						$term_name = empty( $term ) ? esc_html__( 'All', 'woostify-pro' ) : $term->name;

						$has_search_field = in_array( $data_field, array( 'in-cat', 'in-tag' ), true ) ? ' has-search-field' : '';
						?>
						<div class="woostify-condition-item<?php echo esc_attr( $has_search_field ); ?>">
							<select class="woostify-condition-item-type">
								<option value="include" <?php selected( $data_type, 'include' ); ?>><?php esc_html_e( 'Include', 'woostify-pro' ); ?></option>
								<option value="exclude" <?php selected( $data_type, 'exclude' ); ?>><?php esc_html_e( 'Exclude', 'woostify-pro' ); ?></option>
							</select>

							<select class="woostify-condition-item-field">
								<option value="all" <?php selected( $data_field, 'all' ); ?>><?php echo esc_html( $all_text ); ?></option>
								<?php if ( 'woostify_shop_page' === $template ) { ?>
									<option value="shop-page" <?php selected( $data_field, 'shop-page' ); ?>><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></option>
								<?php } ?>
								<option value="in-cat" <?php selected( $data_field, 'in-cat' ); ?>><?php esc_html_e( 'In Product Category', 'woostify-pro' ); ?></option>
								<option value="in-tag" <?php selected( $data_field, 'in-tag' ); ?>><?php esc_html_e( 'In Product Tag', 'woostify-pro' ); ?></option>
							</select>

							<div class="woostify-condition-item-search">
								<span class="woostify-condition-item-search-view" data-id="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_html( $term_name ); ?></span>
								<div class="woostify-condition-item-search-content">
									<input type="text" class="woostify-condition-item-search-field">
									<div class="woostify-condition-item-search-result"></div>
								</div>
							</div>

							<span class="woostify-condition-item-remove dashicons dashicons-no-alt"></span>
						</div>
						<?php
					}
				}
			} else {
				$this->add_new_condition();
			}
		}

		/**
		 * Editor scripts
		 */
		public function enqueue_editor_scripts() {
			$screen         = get_current_screen();
			$is_woo_builder = false !== strpos( $screen->id, 'woo_builder' );

			// Return if not WooBuilder.
			if ( ! $is_woo_builder ) {
				return;
			}

			$post_id  = sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ); // phpcs:ignore
			$template = get_post_meta( $post_id, 'woostify_woo_builder_template', true );
			$template = str_replace( 'woostify_', '', $template );

			// Apply for Shop and Product page only.
			if ( ! in_array( $template, array( 'shop_page', 'product_page' ), true ) ) {
				return;
			}
			?>

			<script type="text/html" id="woostify-woobuilder-conditions-<?php echo esc_attr( $template ); ?>-html">
				<?php $this->add_new_condition(); ?>
			</script>

			<script type="text/html" id="woostify-woobuilder-conditions-html">
				<div class="woostify-woobuilder-conditions">
					<div class="woostify-woobuilder-conditions-inner">
						<div class="woostify-woobuilder-conditions-header">
							<span class="woostify-woobuilder-conditions-logo">
								<img src="<?php echo esc_url( WOOSTIFY_THEME_URI . 'assets/images/logo.svg' ); ?>" alt="<?php esc_attr_e( 'Admin woostify logo image', 'woostify-pro' ); ?>">
							</span>
							<span class="woostify-woobuilder-conditions-title"><?php esc_html_e( 'Publish Settings', 'woostify-pro' ); ?></span>
							<span class="woostify-woobuilder-conditions-close-btn dashicons dashicons-no-alt"></span>
						</div>

						<div class="woostify-woobuilder-conditions-content">
							<div class="woostify-woobuilder-conditions-content-inner">
								<div class="woostify-woobuilder-condition-item-wrapper">
									<?php $this->get_item_condition(); ?>
								</div>
								<span class="woostify-condition-add-button" data-type="<?php echo esc_attr( $template ); ?>"><?php esc_html_e( 'Add Condition', 'woostify-pro' ); ?></span>
							</div>
						</div>

						<div class="woostify-woobuilder-conditions-footer">
							<span class="save-options"><?php esc_html_e( 'Save &amp; Close', 'woostify-pro' ); ?></span>
						</div>
					</div>
				</div>
			</script>
			<?php

			wp_enqueue_style(
				'woostify-woo-builder-editor',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/css/editor-style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			wp_enqueue_script(
				'woostify-woo-builder-editor',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/js/editor-script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			$selected_id = get_post_meta( $post_id, 'woostify_woo_builder_select_product_preview', true );
			$product_id  = $selected_id ? $selected_id : $this->get_product_id();
			$preview_url = 'shop_page' === $template ? get_permalink( wc_get_page_id( 'shop' ) ) : ( $selected_id ? get_permalink( $selected_id ) : get_permalink( $product_id ) );

			$data = array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'          => wp_create_nonce( 'woostify_woo_builder_condition' ),
				'post_id'             => $post_id,
				'post_status'         => get_post_status( $post_id ),
				'post_type'           => get_post_type( $post_id ),
				'product_id'          => $product_id,
				'preview_url'         => $preview_url,
				'searching_text'      => __( 'Searching...', 'woostify-pro' ),
				'condition_text'      => __( 'Display Conditions', 'woostify-pro' ),
				'all_text'            => __( 'All', 'woostify-pro' ),
				'select_preview'      => $selected_id ? get_the_title( $selected_id ) : __( 'Select Produc Preview', 'woostify-pro' ),
				'preview_text'        => __( 'Preview', 'woostify-pro' ),
				'search_placeholder'  => __( 'Please enter 1 or more characters', 'woostify-pro' ),
				'is_product_template' => 'product_page' === $template ? true : false,
			);

			wp_localize_script(
				'woostify-woo-builder-editor',
				'woostify_woo_builder_editor',
				$data
			);
		}

		/**
		 * Init action
		 */
		public function init_action() {
			// Create custom post type.
			$args = array(
				'label'               => __( 'WooBuilder', 'woostify-pro' ),
				'supports'            => array( 'title', 'editor', 'thumbnail', 'elementor' ),
				'rewrite'             => array( 'slug' => 'woo-builder' ),
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
			register_post_type( 'woo_builder', $args );

			// Product page.
			if ( $this->template_exist() ) {
				remove_action( 'woostify_after_header', 'woostify_content_top', 30 );
			}

			// Cart page.
			if ( $this->template_exist( 'woostify_cart_page' ) ) {
				// Remove Cart page layout class name.
				add_filter( 'woostify_cart_page_layout_class_name', '__return_empty_string' );
			}

			// Checkout page.
			if ( $this->template_exist( 'woostify_checkout_page' ) ) {
				// Remove default coupon form.
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
				// Remove multi step checkout.
				add_filter( 'woostify_disable_multi_step_checkout', '__return_true' );
			}
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
					$order['woo_builder_type']    = __( 'Type', 'woostify-pro' );
					$order['woo_builder_include'] = __( 'Include', 'woostify-pro' );
					$order['woo_builder_exclude'] = __( 'Exclude', 'woostify-pro' );
					$order['woo_builder_author']  = __( 'Author', 'woostify-pro' );
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
			$conditions = get_post_meta( $post_id, 'woostify_woo_builder_conditions', true );
			$data       = get_post_meta( $post_id, 'woostify_woo_builder_template', true );
			$all_text   = 'woostify_shop_page' === $data ? esc_html__( 'All Product Archives', 'woostify-pro' ) : esc_html__( 'All Products', 'woostify-pro' );

			switch ( $column_name ) {
				case 'woo_builder_type':
					$template = woostify_get_metabox( $post_id, 'woostify_woo_builder_template' );

					switch ( $template ) {
						case 'woostify_shop_page':
							$title = __( 'Shop Page', 'woostify-pro' );
							break;
						case 'woostify_product_page':
							$title = __( 'Product Single', 'woostify-pro' );
							break;
						case 'woostify_cart_page':
							$title = __( 'Cart Page', 'woostify-pro' );
							break;
						case 'woostify_cart_empty':
							$title = __( 'Cart Empty', 'woostify-pro' );
							break;
						case 'woostify_checkout_page':
							$title = __( 'Checkout Page', 'woostify-pro' );
							break;
						case 'woostify_thankyou_page':
							$title = __( 'Thankyou Page', 'woostify-pro' );
							break;
						case 'woostify_my_account_page':
							$title = __( 'My Account Page', 'woostify-pro' );
							break;
						default:
							$title = __( 'Unknown', 'woostify-pro' );
							break;
					}
					?>
					<span><?php echo esc_html( $title ); ?></span>
					<?php
					break;
				case 'woo_builder_include':
					if ( ! empty( $conditions ) ) {
						foreach ( $conditions as $k => $v ) {
							if ( ! isset( $v['data_type'] ) || 'exclude' === $v['data_type'] ) {
								continue;
							}

							if ( 'all' === $v['data_field'] ) {
								?>
								<span><?php echo esc_html( $all_text ); ?></span>
								<?php
							} elseif ( 'in-tag' === $v['data_field'] ) {
								if ( 'all' === $v['data_id'] ) {
									?>
									<span><?php esc_html_e( 'All Product Tag', 'woostify-pro' ); ?></span>
									<?php
								} else {
									$tag_id   = str_replace( 'in-tag-', '', $v['data_id'] );
									$tag_term = get_term_by( 'id', $tag_id, 'product_tag' );

									if ( ! empty( $tag_term ) ) {
										?>
										<span><a href="<?php echo esc_url( get_term_link( $tag_term, 'product_tag' ) ); ?>"><?php echo esc_html( $tag_term->name ); ?></a></span>
										<?php
									}
								}
							} elseif ( 'in-cat' === $v['data_field'] ) {
								if ( 'all' === $v['data_id'] ) {
									?>
									<span><?php esc_html_e( 'All Product Category', 'woostify-pro' ); ?></span>
									<?php
								} else {
									$cat_id   = str_replace( 'in-cat-', '', $v['data_id'] );
									$cat_term = get_term_by( 'id', $cat_id, 'product_cat' );

									if ( ! empty( $cat_term ) ) {
										?>
										<span><a href="<?php echo esc_url( get_term_link( $cat_term, 'product_cat' ) ); ?>"><?php echo esc_html( $cat_term->name ); ?></a></span>
										<?php
									}
								}
							} elseif ( 'shop-page' === $v['data_field'] ) {
								?>
								<span><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></a></span>
								<?php
							}
						}
					}
					break;
				case 'woo_builder_exclude':
					if ( ! empty( $conditions ) ) {
						foreach ( $conditions as $k => $v ) {
							if ( ! isset( $v['data_type'] ) || 'include' === $v['data_type'] ) {
								continue;
							}

							if ( 'all' === $v['data_field'] ) {
								?>
								<span><?php echo esc_html( $all_text ); ?></span>
								<?php
							} elseif ( 'in-tag' === $v['data_field'] ) {
								if ( 'all' === $v['data_id'] ) {
									?>
									<span><?php esc_html_e( 'All Product Tag', 'woostify-pro' ); ?></span>
									<?php
								} else {
									$tag_id   = str_replace( 'in-tag-', '', $v['data_id'] );
									$tag_term = get_term_by( 'id', $tag_id, 'product_tag' );

									if ( ! empty( $tag_term ) ) {
										?>
										<span><?php echo esc_html( $tag_term->name ); ?></span>
										<?php
									}
								}
							} elseif ( 'in-cat' === $v['data_field'] ) {
								if ( 'all' === $v['data_id'] ) {
									?>
									<span><?php esc_html_e( 'All Product Category', 'woostify-pro' ); ?></span>
									<?php
								} else {
									$cat_id   = str_replace( 'in-cat-', '', $v['data_id'] );
									$cat_term = get_term_by( 'id', $cat_id, 'product_cat' );

									if ( ! empty( $cat_term ) ) {
										?>
										<span><?php echo esc_html( $cat_term->name ); ?></span>
										<?php
									}
								}
							} elseif ( 'shop-page' === $v['data_field'] ) {
								?>
								<span><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></span>
								<?php
							}
						}
					}
					break;
				case 'woo_builder_author':
					$author_id   = get_post_field( 'post_author', $post_id );
					$author_name = get_the_author_meta( 'nicename', $author_id );
					echo esc_html( $author_name );
					break;
			}
		}

		/**
		 * Removes view actions.
		 *
		 * @param array $actions The actions.
		 */
		public function remove_view_actions( $actions ) {
			if ( 'woo_builder' === get_post_type() ) {
				unset( $actions['view'] );
			}

			return $actions;
		}

		/**
		 * Add Theme Builder admin menu
		 */
		public function add_admin_menu() {
			add_submenu_page( 'woostify-welcome', 'WooBuilder', __( 'WooBuilder', 'woostify-pro' ), 'manage_options', 'edit.php?post_type=woo_builder' );
		}

		/**
		 * Adds widgets.
		 */
		public function add_widgets() {
			$widgets = glob( WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/widget/class-woostify-*.php' );

			foreach ( $widgets as $file ) {
				if ( file_exists( $file ) ) {
					require_once $file;
				}
			}
		}

		/**
		 * Adds scripts.
		 */
		public function frontend_scripts() {
			wp_register_script(
				'woostify-elementor-woobuilder-widget',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/js/woo-builder-handle' . woostify_suffix() . '.js',
				array( 'jquery' ),
				WOOSTIFY_PRO_VERSION,
				true
			);
		}

		/**
		 * Check template exist
		 *
		 * @param      string $template_type The template type.
		 */
		public function template_exist( $template_type = 'woostify_product_page' ) {
			$args = array(
				'post_type'      => 'woo_builder',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_query'     => array( // phpcs:ignore
					array(
						'key'   => 'woostify_woo_builder_template',
						'value' => $template_type,
					),
				),
			);

			$query = new WP_Query( $args );

			// Check have posts.
			if ( $query->have_posts() ) {
				return $query->posts[0]->ID; // Return ID.
			}

			return false;
		}

		/**
		 * Get template id
		 *
		 * @param string $template The template name.
		 */
		public function get_template_id( $template = 'woostify_shop_page' ) {
			$id = $this->template_exist( $template );
			if ( ! $id ) {
				return false;
			}

			$args = array(
				'post_type'      => 'woo_builder',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'meta_query'     => array( // phpcs:ignore
					array(
						'key'   => 'woostify_woo_builder_template',
						'value' => $template,
					),
				),
			);

			$query = new WP_Query( $args );

			$post_id = array();

			while ( $query->have_posts() ) {
				$query->the_post();

				$include = array();
				$exclude = array();

				$conditions  = get_post_meta( get_the_ID(), 'woostify_woo_builder_conditions', true );
				$exclude_all = array(
					'data_type'  => 'exclude',
					'data_field' => 'all',
					'data_id'    => 'all',
				);

				if ( empty( $conditions ) || $exclude_all === $conditions ) {
					continue;
				}

				foreach ( $conditions as $k ) {
					$data_type  = ! empty( $k['data_type'] ) ? $k['data_type'] : 'exclude';
					$data_field = ! empty( $k['data_field'] ) ? $k['data_field'] : 'all';
					$data_id    = ! empty( $k['data_id'] ) ? $k['data_id'] : 'all';
					$data_field = in_array( $data_field, array( 'in-cat', 'in-tag' ), true ) ? $data_field . '-' . $data_id : $data_field;

					switch ( $data_field ) {
						case 'in-cat':
							$taxonomy = 'product_cat';
							break;
						case 'in-tag':
							$taxonomy = 'product_tag';
							break;
						default:
							$taxonomy = 'faker__term';
							break;
					}

					if ( 'include' === $data_type ) {
						array_push( $include, $data_field );
					} else {
						array_push( $exclude, $data_field );
					}
				}

				$new_include = array_diff( $include, $exclude );
				$new_exclude = array_diff( $exclude, $include );

				if ( empty( $new_include ) || ! empty( $new_exclude['all'] ) ) {
					continue;
				}

				$post_id[ get_the_ID() ]['include'] = $new_include;
				$post_id[ get_the_ID() ]['exclude'] = $new_exclude;
			}
			wp_reset_postdata();

			return $post_id;
		}

		/**
		 * Single woo_builder template
		 *
		 * @param string $template The path of the template to include.
		 */
		public function single_template( $template ) {
			if ( is_singular( 'product' ) && $this->template_exist() ) {
				$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/product-page.php';
			} elseif ( is_account_page() && is_user_logged_in() && $this->template_exist( 'woostify_my_account_page' ) ) {
				$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/my-account-page.php';
			} elseif ( woostify_is_product_archive() && $this->template_exist( 'woostify_shop_page' ) ) {
				$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/shop-page.php';
			} elseif ( is_cart() ) {
				if ( $this->template_exist( 'woostify_cart_empty' ) && WC()->cart->is_empty() ) {
					$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/cart-empty.php';
				} elseif ( $this->template_exist( 'woostify_cart_page' ) && ! WC()->cart->is_empty() ) {
					$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/cart-page.php';
				}
			} elseif ( is_checkout() ) {
				global $wp;
				if (
					( isset( $wp->query_vars['order-received'] ) || isset( $wp->query_vars['order'] ) || is_wc_endpoint_url( 'order-received' ) ) &&
					$this->template_exist( 'woostify_thankyou_page' )
				) {
					$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/thankyou-page.php';
				} elseif ( $this->template_exist( 'woostify_checkout_page' ) ) {
					$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/checkout-page.php';
				}
			} elseif ( is_singular( 'woo_builder' ) && file_exists( WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/woo-builder.php' ) ) {
				$template = WOOSTIFY_PRO_MODULES_PATH . 'woo-builder/template/woo-builder.php';
			}

			return $template;
		}

		/**
		 * Register WC hooks.
		 */
		public function register_wc_hooks() {
			WC()->frontend_includes();
		}

		/**
		 * Render cart page
		 */
		public function render_cart_page() {
			$id = $this->template_exist( 'woostify_cart_page' );

			if ( ! $id || self::$already_cart ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			wp_reset_postdata();
			self::$already_cart = true;
		}

		/**
		 * Render cart empty
		 */
		public function render_cart_empty() {
			$id = $this->template_exist( 'woostify_cart_empty' );

			if ( ! $id || self::$already_cart_empty ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			wp_reset_postdata();
			self::$already_cart_empty = true;
		}

		/**
		 * Render my account page
		 */
		public function render_my_account_page() {
			$id = $this->template_exist( 'woostify_my_account_page' );

			if ( ! $id || self::$already_my_account ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			self::$already_my_account = true;
		}

		/**
		 * Render checkout page
		 */
		public function render_checkout_page() {
			$id = $this->template_exist( 'woostify_checkout_page' );

			if ( ! $id || self::$already_checkout ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			wp_reset_postdata();
			self::$already_checkout = true;
		}

		/**
		 * Render thankyou page
		 */
		public function render_thankyou_page() {
			$id = $this->template_exist( 'woostify_thankyou_page' );

			if ( ! $id || self::$already_thankyou ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			wp_reset_postdata();
			self::$already_thankyou = true;
		}

		/**
		 * Admin action new post.
		 *
		 * When a new post action is fired the title is set to 'Elementor' and the post ID.
		 *
		 * Fired by `admin_action_elementor_new_post` action.
		 *
		 * @since 1.9.0
		 * @access public
		 */
		public function admin_action_new_post() {
			check_admin_referer( 'woostify_add_new_template_builder' );
			$post_data = array();

			if ( ! \Elementor\User::is_current_user_can_edit_post_type( 'woo_builder' ) ) {
				return;
			}

			$template_type           = isset( $_GET['template_type'] ) ? sanitize_text_field( wp_unslash( $_GET['template_type'] ) ) : '';
			$post_data['post_type']  = 'woo_builder';
			$post_data['post_title'] = isset( $_GET['post_title'] ) ? sanitize_text_field( wp_unslash( $_GET['post_title'] ) ) : 'Woo Builder #1';
			$post_data               = apply_filters( 'woostify_woo_builder_create_new_post_meta', $post_data );
			$new_post_id             = wp_insert_post( $post_data );

			// Update post meta.
			update_post_meta( $new_post_id, 'woostify_woo_builder_template', $template_type );

			$url = add_query_arg(
				array(
					'post'   => $new_post_id,
					'action' => 'elementor',
				),
				admin_url( 'post.php' )
			);

			wp_safe_redirect( $url );
			die();
		}

		/**
		 * Print template
		 */
		public function add_new_template() {
			$screen              = get_current_screen();
			$is_edit_woo_builder = $screen ? 'edit-woo_builder' === $screen->id : false;
			if ( ! $is_edit_woo_builder ) {
				return;
			}
			?>

			<div class="woostify-add-new-template-builder">
				<div class="woostify-add-new-template-inner">
					<div class="woostify-add-new-template-header">
						<span class="woostify-add-new-template-logo">
							<img src="<?php echo esc_url( WOOSTIFY_THEME_URI . 'assets/images/logo.svg' ); ?>" alt="<?php esc_attr_e( 'Admin woostify logo image', 'woostify-pro' ); ?>">
						</span>
						<span class="woostify-add-new-template-title"><?php esc_html_e( 'New Template', 'woostify-pro' ); ?></span>
						<span class="woostify-add-new-template-close-btn dashicons dashicons-no-alt"></span>
					</div>

					<div class="woostify-add-new-template-content">
						<form class="woostify-add-new-template-form">
							<input type="hidden" name="post_type" value="woo_builder">
							<input type="hidden" name="action" value="woostify_add_new_template_builder">
							<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'woostify_add_new_template_builder' ) ); ?>">

							<div class="woostify-add-new-template-form-title"><?php esc_html_e( 'Choose Template Type', 'woostify-pro' ); ?></div>

							<div class="woostify-add-new-template-item">
								<label class="woostify-add-new-template-label"><?php esc_html_e( 'Select the type of template you want to work on', 'woostify-pro' ); ?>:</label>

								<div class="woostify-add-new-template-option-wrapper">
									<select name="template_type" required="required">
										<option value=""><?php esc_html_e( 'Select...', 'woostify-pro' ); ?></option>
										<option value="woostify_shop_page"><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?></option>
										<option value="woostify_product_page"><?php esc_html_e( 'Product Page', 'woostify-pro' ); ?></option>
										<option value="woostify_my_account_page"><?php esc_html_e( 'My Account Page', 'woostify-pro' ); ?></option>
										<option value="woostify_cart_page"><?php esc_html_e( 'Cart Page', 'woostify-pro' ); ?></option>
										<option value="woostify_cart_empty"><?php esc_html_e( 'Cart Empty', 'woostify-pro' ); ?></option>
										<option value="woostify_checkout_page"><?php esc_html_e( 'Checkout Page', 'woostify-pro' ); ?></option>
										<option value="woostify_thankyou_page"><?php esc_html_e( 'Thankyou Page', 'woostify-pro' ); ?></option>
									</select>
								</div>
							</div>

							<div class="woostify-add-new-template-item">
								<label class="woostify-add-new-template-label"><?php esc_html_e( 'Name your template', 'woostify-pro' ); ?>:</label>

								<div class="woostify-add-new-template-option-wrapper">
									<input type="text" placeholder="<?php esc_attr_e( 'Enter template name', 'woostify-pro' ); ?>" name="post_title" required="required">
								</div>
							</div>

							<button class="woostify-add-new-template-form-submit"><?php esc_html_e( 'Create Template', 'woostify-pro' ); ?></button>
						</form>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Get last wc order
		 */
		public function get_wc_order() {
			$args = array(
				'post_type'      => 'shop_order',
				'post_status'    => 'any',
				'posts_per_page' => 1,
			);

			$order_query = new \WP_Query( $args );

			if ( $order_query->have_posts() ) {
				$order_id = $order_query->posts[0]->ID;
			}

			if ( $order_id ) {
				return wc_get_order( $order_id );
			}

			return false;
		}

		/**
		 * Search product preview data
		 */
		public function woostify_woo_builder_preview_search_data() {
			check_ajax_referer( 'woostify_woo_builder_condition', 'ajax_nonce' );

			$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : false;
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';

			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				's'              => $keyword,
			);

			$query = new WP_Query( $args );

			ob_start();
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					?>
					<span class="result-item" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-url="<?php echo esc_attr( get_the_permalink() ); ?>"><?php the_title(); ?></span>
					<?php
				}

				wp_reset_postdata();
			} else {
				?>
				<span class="result-item"><?php esc_html_e( 'Nothing Found', 'woostify-pro' ); ?></span>
				<?php
			}
			$res['content'] = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Selected product preview
		 */
		public function woostify_woo_builder_select_product_preview() {
			check_ajax_referer( 'woostify_woo_builder_condition', 'ajax_nonce' );

			$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : false;
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$selected_id = isset( $_POST['selected_id'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_id'] ) ) : false;
			if ( $selected_id ) {
				update_post_meta( $post_id, 'woostify_woo_builder_select_product_preview', $selected_id );
				wp_send_json_success();
			}

			wp_send_json_error();
		}

		/**
		 * Search product data
		 */
		public function woostify_woo_builder_conditions_search_data() {
			check_ajax_referer( 'woostify_woo_builder_condition', 'ajax_nonce' );

			$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : false;
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$field_value = isset( $_POST['field_value'] ) ? sanitize_text_field( wp_unslash( $_POST['field_value'] ) ) : '';
			$keyword     = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';

			switch ( $field_value ) {
				case 'in-cat':
					$taxonomy = 'product_cat';
					break;
				case 'in-tag':
					$taxonomy = 'product_tag';
					break;
				default:
					$taxonomy = 'faker__term';
					break;
			}

			$args = array(
				'hide_empty' => true,
				'search'     => $keyword,
			);
			$cats = get_terms( $taxonomy, $args );

			ob_start();
			if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
				foreach ( $cats as $k ) {
					?>
					<span class="result-item" data-id="<?php echo esc_attr( $k->term_id ); ?>"><?php echo esc_html( $k->name ); ?></span>
					<?php
				}
			} else {
				?>
				<span class="result-item"><?php esc_html_e( 'Nothing Found', 'woostify-pro' ); ?></span>
				<?php
			}
			$res['content'] = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Save conditions
		 */
		public function woostify_save_woo_builder_conditions() {
			check_ajax_referer( 'woostify_woo_builder_condition', 'ajax_nonce' );

			$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : false;
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$conditions = isset( $_POST['conditions'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['conditions'] ) ), true ) : array();
			$conditions = array_unique( $conditions, SORT_REGULAR );

			$res['post_id']    = $post_id;
			$res['conditions'] = $conditions;

			$exclude_all = array(
				'data_type'  => 'exclude',
				'data_field' => 'all',
				'data_id'    => 'all',
			);

			if ( in_array( $exclude_all, $conditions, true ) ) {
				$conditions = $exclude_all;
			}

			update_post_meta( $post_id, 'woostify_woo_builder_conditions', $conditions );

			wp_send_json_success( $res );
		}

		/**
		 * Admin enqueue styles and scripts.
		 */
		public function admin_enqueue_assets() {
			$screen              = get_current_screen();
			$is_edit_woo_builder = $screen ? 'edit-woo_builder' === $screen->id : false;
			if ( ! $is_edit_woo_builder ) {
				return;
			}

			wp_enqueue_script(
				'woostify-woo-builder-add-new-template',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/js/add-new-template' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_enqueue_style(
				'woostify-woo-builder-add-new-template',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/css/add-new-template.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_assets() {
			wp_enqueue_style(
				'woostify-woo-builder',
				WOOSTIFY_PRO_MODULES_URI . 'woo-builder/assets/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			wp_enqueue_style( 'elementor-frontend' );

			// Register font awesome for My account widget.
			$url = ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all' . woostify_suffix() . '.css';
			wp_register_style(
				'elementor-font-awesome',
				$url,
				array(),
				WOOSTIFY_PRO_VERSION
			);

			// For My widget css.
			$options = woostify_options( false );
			wp_add_inline_style( 'woostify-style', '.elementor-widget-woostify-my-account .account-menu-item.active a { color: ' . esc_attr( $options['theme_color'] ) . ' }' );
		}
	}

	Woostify_Woo_Builder::init();
}

