<?php
/**
 * Size guide
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Size_Guide' ) ) {
	/**
	 * Class for woostify size guide.
	 */
	class Woostify_Size_Guide {
		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Meta Option
		 *
		 * @var $meta_option
		 */
		private static $meta_option;

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
			add_action( 'init', array( $this, 'init_action' ), 0 );
			// Register Size Guide post type.
			add_action( 'admin_menu', array( $this, 'add_size_guide_admin_menu' ) );
			add_action( 'load-post.php', array( $this, 'size_guide_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'size_guide_metabox' ) );
			add_filter( 'template_include', array( $this, 'single_size_guide' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'single_content' ), 5 );

			// Add Apply For column on 'size_guide' list in admin screen.
			add_filter( 'manage_size_guide_posts_columns', array( $this, 'add_apply_for_column_head' ), 10 );
			add_action( 'manage_size_guide_posts_custom_column', array( $this, 'add_apply_for_column_content' ), 10, 2 );

			// Ajax select data.
			add_action( 'wp_ajax_woostify_size_guide_select_categories', array( $this, 'select_categories' ) );
			add_action( 'wp_ajax_woostify_size_guide_select_products', array( $this, 'select_products' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_SIZE_GUIDE' ) ) {
				define( 'WOOSTIFY_PRO_SIZE_GUIDE', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Init
		 */
		public function init_action() {
			// Register Size Guide post type.
			$args = array(
				'label'               => _x( 'Size Guide', 'post type label', 'woostify-pro' ),
				'singular_name'       => _x( 'Size Guide', 'post type singular name', 'woostify-pro' ),
				'supports'            => array( 'title', 'editor', 'thumbnail', 'elementor' ),
				'rewrite'             => array( 'slug' => 'size-guide' ),
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
			register_post_type( 'size_guide', $args );

			// Flush rewrite rules.
			if ( ! get_option( 'woostify_size_guide_builder_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				update_option( 'woostify_size_guide_builder_flush_rewrite_rules', true );
			}
		}

		/**
		 * Add size guide admin menu
		 */
		public function add_size_guide_admin_menu() {
			add_submenu_page( 'woostify-welcome', 'Size Guides', __( 'Size Guide', 'woostify-pro' ), 'manage_options', 'edit.php?post_type=size_guide' );
		}

		/**
		 * Size guide metabox
		 */
		public function size_guide_metabox() {
			add_action( 'add_meta_boxes', array( $this, 'setup_size_guide_metabox' ) );
			add_action( 'save_post', array( $this, 'save_size_guide_metabox' ) );

			self::$meta_option = array(
				'size-guide-for-category' => array(
					'default'  => '',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'size-guide-for-product'  => array(
					'default'  => '',
					'sanitize' => 'FILTER_DEFAULT',
				),
			);
		}

		/**
		 * Get metabox options
		 */
		public static function get_size_guide_metabox_option() {
			return self::$meta_option;
		}

		/**
		 *  Setup Metabox
		 */
		public function setup_size_guide_metabox() {
			add_meta_box(
				'woostify_metabox_settings_size_guide',
				__( 'Size Guide Settings', 'woostify-pro' ),
				array( $this, 'size_guide_markup' ),
				'size_guide',
				'side'
			);
		}

		/**
		 * Select categories
		 */
		public function select_categories() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			check_ajax_referer( 'woostify-select-categories', 'security_nonce' );
			$value = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';

			ob_start();

			$args = array(
				'hide_empty' => true,
				'search'     => $value,
			);
			$cats = get_terms( 'product_cat', $args );

			if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
				?>
					<span class="woostify-multi-select-id" data-id="all">
						<?php esc_html_e( 'All Categoties', 'woostify-pro' ); ?>
					</span>
				<?php
				foreach ( $cats as $k ) {
					?>
					<span class="woostify-multi-select-id" data-id="<?php echo esc_attr( $k->term_id ); ?>">
						<?php echo esc_html( $k->name ); ?>
					</span>
					<?php
				}
			} else {
				?>
				<span class="no-posts-found"><?php esc_html_e( 'No product category found', 'woostify-pro' ); ?></span>
				<?php
			}

			$res = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Select products
		 */
		public function select_products() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			check_ajax_referer( 'woostify-select-products', 'security_nonce' );
			$value = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';

			ob_start();

			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => -1,
				's'                   => $value,
			);

			$products = new WP_Query( $args );

			if ( $products->have_posts() ) {
				?>
					<span class="woostify-multi-select-id" data-id="all">
						<?php esc_html_e( 'All Products', 'woostify-pro' ); ?>
					</span>
				<?php
				while ( $products->have_posts() ) {
					$products->the_post();
					?>
					<span class="woostify-multi-select-id" data-id="<?php the_ID(); ?>">
						<?php the_title(); ?>
					</span>
					<?php
				}

				wp_reset_postdata();
			} else {
				?>
				<span class="no-posts-found woocommerce-info"><?php esc_html_e( 'No products found!', 'woostify-pro' ); ?></span>
				<?php
			}

			wp_send_json_success( ob_get_clean() );
		}

		/**
		 * Metabox Markup
		 *
		 * @param  object $post Post object.
		 * @return void
		 */
		public function size_guide_markup( $post ) {
			wp_nonce_field( basename( __FILE__ ), 'woostify_metabox_settings_size_guide' );
			$stored = get_post_meta( $post->ID );

			// Set stored and override defaults.
			foreach ( $stored as $key => $value ) {
				self::$meta_option[ $key ]['default'] = isset( $stored[ $key ][0] ) ? $stored[ $key ][0] : '';
			}

			// Get defaults.
			$meta = self::get_size_guide_metabox_option();

			// Get options.
			$size_guide_default = get_option( 'size_guide_default' );
			$size_guide_default = $size_guide_default === $post->ID ? true : false;

			// Get Product categories.
			$product_categories = get_terms( 'product_cat', array( 'hide_empty' => true ) );

			// For categories.
			$data           = woostify_get_metabox( $post->ID, 'size-guide-for-category' );
			$all_categories = strpos( $data, 'all' );
			$selected_id    = explode( '|', $data );
			$value          = false !== $all_categories ? 'all' : $data;
			$value          = 'default' === $data ? $data : $value;

			// For products.
			$product_data        = woostify_get_metabox( $post->ID, 'size-guide-for-product' );
			$all_product         = strpos( $product_data, 'all' );
			$selected_product_id = explode( '|', $product_data );
			$product_value       = false !== $all_product ? 'all' : $product_data;
			$product_value       = 'default' === $product_data ? $product_data : $product_value;
			?>

			<div class="woostify-metabox-setting">
				<div class="woostify-metabox-option">
					<div class="woostify-metabox-option-title">
						<span><?php esc_html_e( 'Apply For Categories', 'woostify-pro' ); ?>:</span>
					</div>

					<div class="woostify-metabox-option-content woostify-multi-selection">
						<input class="woostify-multi-select-value" name="size-guide-for-category" type="hidden" value="<?php echo esc_attr( $value ); ?>">

						<div class="woostify-multi-select-selection">
							<?php if ( false !== $all_categories ) { ?>
								<span class="woostify-multi-select-id" data-id="all">
									<?php esc_html_e( 'All Categoties', 'woostify-pro' ); ?>
									<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
								</span>
								<?php
							} elseif ( 'default' !== $value ) {

								// Print selected categories.
								$args = array(
									'include'    => $selected_id,
									'hide_empty' => true,
								);

								$selected_categories = get_terms( 'product_cat', $args );

								if ( ! empty( $selected_categories ) && ! is_wp_error( $selected_categories ) ) {
									foreach ( $selected_categories as $k ) {
										?>
										<span class="woostify-multi-select-id" data-id="<?php echo esc_attr( $k->term_id ); ?>">
											<?php echo esc_html( $k->name ); ?>
											<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
										</span>
										<?php
									}
								}
							}
							?>

							<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-select-categories' ) ); ?>" name="woostify_size_guide_select_categories">
						</div>

						<div class="woostify-multi-select-dropdown"></div>
					</div>
				</div>

				<div class="woostify-metabox-option">
					<div class="woostify-metabox-option-title">
						<span><?php esc_html_e( 'Apply For Products', 'woostify-pro' ); ?>:</span>
					</div>

					<div class="woostify-metabox-option-content woostify-multi-selection">
						<input class="woostify-multi-select-value" name="size-guide-for-product" type="hidden" value="<?php echo esc_attr( $product_value ); ?>">

						<div class="woostify-multi-select-selection">
							<?php if ( false !== $all_product ) { ?>
								<span class="woostify-multi-select-id" data-id="all">
									<?php esc_html_e( 'All Products', 'woostify-pro' ); ?>
									<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
								</span>
								<?php
							} elseif ( 'default' !== $product_value ) {

								$selected_args = array(
									'post_type'           => 'product',
									'posts_per_page'      => -1,
									'post__in'            => $selected_product_id,
									'ignore_sticky_posts' => 1,
								);

								$selected_products = new WP_Query( $selected_args );

								if ( $selected_products->have_posts() ) {
									while ( $selected_products->have_posts() ) {
										$selected_products->the_post();
										?>
										<span class="woostify-multi-select-id" data-id="<?php the_ID(); ?>">
											<?php the_title(); ?>
											<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
										</span>
										<?php
									}

									wp_reset_postdata();
								}
							}
							?>

							<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-select-products' ) ); ?>" name="woostify_size_guide_select_products">
						</div>

						<div class="woostify-multi-select-dropdown"></div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Metabox Save
		 *
		 * @param  number $post_id Post ID.
		 * @return void
		 */
		public function save_size_guide_metabox( $post_id ) {

			// Checks save status.
			$is_user_can_edit = current_user_can( 'edit_posts' );
			$is_autosave      = wp_is_post_autosave( $post_id );
			$is_revision      = wp_is_post_revision( $post_id );
			$is_valid_nonce   = ( isset( $_POST['woostify_metabox_settings_size_guide'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woostify_metabox_settings_size_guide'] ) ), basename( __FILE__ ) ) ) ? true : false;

			// Exits script depending on save status.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce || ! $is_user_can_edit ) {
				return;
			}

			/**
			 * Get meta options
			 */
			$post_meta = self::get_size_guide_metabox_option();

			foreach ( $post_meta as $key => $data ) {

				// Sanitize values.
				$sanitize_filter = isset( $data['sanitize'] ) ? $data['sanitize'] : 'FILTER_DEFAULT';

				switch ( $sanitize_filter ) {

					case 'FILTER_SANITIZE_STRING':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
						break;

					case 'FILTER_SANITIZE_URL':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
						break;

					case 'FILTER_SANITIZE_NUMBER_INT':
							$meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
						break;

					default:
							$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
						break;
				}

				// Update values.
				if ( $meta_value ) {
					update_post_meta( $post_id, $key, $meta_value );
				} else {
					delete_post_meta( $post_id, $key );
				}
			}

		}

		/**
		 * Single size_guide template
		 *
		 * @param string $template The path of the template to include.
		 */
		public function single_size_guide( $template ) {
			if ( is_singular( 'size_guide' ) && file_exists( WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php' ) ) {
				$template = WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php';
			}

			return $template;
		}

		/**
		 * Column head
		 *
		 * @param      array $defaults  The defaults.
		 */
		public function add_apply_for_column_head( $defaults ) {
			$order    = array();
			$checkbox = 'title';
			foreach ( $defaults as $key => $value ) {
				$order[ $key ] = $value;
				if ( $key === $checkbox ) {
					$order['size_guide_type'] = __( 'Apply', 'woostify-pro' );
				}
			}

			return $order;
		}

		/**
		 * Column content
		 *
		 * @param      string $column_name  The column name.
		 * @param      int    $post_ID      The post id.
		 */
		public function add_apply_for_column_content( $column_name, $post_ID ) {
			if ( 'size_guide_type' === $column_name ) {
				$categories     = woostify_get_metabox( $post_ID, 'size-guide-for-category' );
				$products       = woostify_get_metabox( $post_ID, 'size-guide-for-product' );
				$all_categories = strpos( $categories, 'all' );
				$all_products   = strpos( $products, 'all' );

				if ( false !== $all_categories || false !== $all_products ) {
					?>
					<span><?php esc_html_e( 'All categories of products', 'woostify-pro' ); ?></span>
					<?php
				} elseif ( 'default' !== $categories || 'default' !== $products ) {
					ob_start();
					if ( 'default' !== $products ) {
						$args = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'ignore_sticky_posts' => 1,
							'post__in'            => explode( '|', $products ),
						);

						$selected_products = new WP_Query( $args );

						if ( $selected_products->have_posts() ) {
							while ( $selected_products->have_posts() ) {
								$selected_products->the_post();
								$dot = ( $selected_products->current_post + 1 ) === ( $selected_products->post_count ) ? '' : ',';
								?>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php echo esc_attr( $dot ); ?>
								<?php
							}

							wp_reset_postdata();
						}
					}
					$products = ob_get_clean();

					ob_start();
					if ( 'default' !== $categories ) {
						$args = array(
							'include'    => explode( '|', $categories ),
							'hide_empty' => true,
						);

						$selected_categories = get_terms( 'product_cat', $args );

						if ( ! empty( $selected_categories ) && ! is_wp_error( $selected_categories ) ) {
							foreach ( $selected_categories as $k ) {
								$dot = end( $selected_categories ) === $k ? '' : ',';
								?>
								<a href="<?php echo esc_url( get_term_link( $k->term_id ) ); ?>"><?php echo esc_html( $k->name ); ?></a><?php echo esc_attr( $dot ); ?>
								<?php
							}
						}
					}
					$categories = ob_get_clean();

					if ( ! empty( $products ) && ! empty( $categories ) ) {
						echo wp_kses_post( sprintf( /* translators: 1, Categories. 2, Products */ __( 'Categories: %1$s - Products: %2$s', 'woostify-pro' ), $categories, $products ) );
					} elseif ( ! empty( $products ) ) {
						echo wp_kses_post( sprintf( /* translators: Product list */ __( 'Products: %s', 'woostify-pro' ), $products ) );
					} elseif ( ! empty( $categories ) ) {
						echo wp_kses_post( sprintf( /* translators: Categorie list */ __( 'Categories: %s', 'woostify-pro' ), $categories ) );
					}
				} else {
					echo '-';
				}
			}
		}

		/**
		 * Styles and Sripts.
		 */
		public function scripts() {
			$options = woostify_options( false );

			wp_enqueue_script(
				'woostify-size-guide',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/size-guide/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_enqueue_style(
				'woostify-size-guide',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/size-guide/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '.woostify-size-guide-button {
				color: ' . $options['heading_color'] . ';
			}';

			wp_add_inline_style( 'woostify-size-guide', $styles );
		}

		/**
		 * Render countdown on product single.
		 */
		public function single_content() {
			if ( class_exists( 'Woostify_Woo_Builder' ) ) {
				$woo_builder    = \Woostify_Woo_Builder::init();
				$single_builder = $woo_builder->template_exist( 'woostify_product_page' );

				if ( $single_builder ) {
					return;
				}
			}

			$this->size_guide_content();
		}

		/**
		 * Html markup.
		 */
		public function size_guide_content() {
			// Get product info.
			global $product;

			if ( class_exists( 'Woostify_Woo_Builder' ) && is_singular( 'product' ) ) {
				$product_id = \Woostify_Woo_Builder::init()->get_product_id();
				$product    = wc_get_product( $product_id );
			}

			if ( empty( $product ) ) {
				return;
			}

			$product_id      = $product->get_id();
			$product_cat_ids = $product->get_category_ids();

			$args = array(
				'post_type'           => 'size_guide',
				'post_status'         => 'publish',
				'posts_per_page'      => -1,
				'ignore_sticky_posts' => 1,
			);

			$query = new \WP_Query( $args );

			if ( ! $query->have_posts() ) {
				return;
			}

			while ( $query->have_posts() ) {
				$query->the_post();

				$for_categories  = woostify_get_metabox( get_the_ID(), 'size-guide-for-category' );
				$for_products    = woostify_get_metabox( get_the_ID(), 'size-guide-for-product' );
				$size_guide_icon = apply_filters( 'woostify_size_guide_icon', 'ti-ruler-alt' );
				if ( 'default' === $for_categories && 'default' === $for_products ) {
					continue;
				}

				ob_start();
				?>
				<div class="woostify-size-guide-table-wrapper size-guide-id-<?php echo get_the_ID(); ?>">
					<div class="woostify-size-guide-table-heading">
						<button class="woostify-size-guide-close-button ti-close"></button>
						<button class="woostify-size-guide-button <?php echo esc_attr( $size_guide_icon ); ?>"><?php the_title(); ?></button>
					</div>

					<div class="woostify-size-guide-table">
						<div class="woostify-size-guide-table-inner">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
				<?php
				$content = ob_get_clean();

				if ( false !== strpos( $for_categories, 'all' ) || false !== strpos( $for_products, 'all' ) ) {
					echo $content; // phpcs:ignore
				} else {
					$categories_id     = array_map( 'intval', explode( '|', $for_categories ) );
					$categories_merge  = array_merge( $categories_id, $product_cat_ids );
					$categories_unique = array_unique( $categories_merge );
					$products_id       = 'default' !== $for_products ? array_map( 'intval', explode( '|', $for_products ) ) : array();

					if ( count( $categories_merge ) !== count( $categories_unique ) || in_array( $product_id, $products_id, true ) ) {
						echo $content; // phpcs:ignore
					}
				}
			}
			wp_reset_postdata();
		}
	}

	Woostify_Size_Guide::get_instance();
}

