<?php
/**
 * Header and Footer builder
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Header_Footer_Builder' ) ) {
	/**
	 * Class for woostify Header Footer builder.
	 */
	class Woostify_Header_Footer_Builder {
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
			add_action( 'wp', array( $this, 'wp_action' ) );
			add_action( 'admin_menu', array( $this, 'add_header_footer_builder_admin_menu' ), 5 );
			add_action( 'load-post.php', array( $this, 'header_footer_builder_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'header_footer_builder_metabox' ) );
			add_action( 'save_post', array( $this, 'save_post_meta' ), 10, 3 );
			add_filter( 'template_include', array( $this, 'single_template' ) );

			// Add Template Type column on 'hf_builder' list in admin screen.
			add_filter( 'manage_hf_builder_posts_columns', array( $this, 'add_header_footer_builder_column_head' ), 10 );
			add_action( 'manage_hf_builder_posts_custom_column', array( $this, 'add_header_footer_builder_column_content' ), 10, 2 );

			// Scripts and styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'elementor_enqueue_scripts' ), 999 );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_HEADER_FOOTER_BUILDER' ) ) {
				define( 'WOOSTIFY_PRO_HEADER_FOOTER_BUILDER', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Init
		 */
		public function init_action() {
			// Register a Theme Builder post type.
			$args = array(
				'label'               => __( 'Header Footer Template', 'woostify-pro' ),
				'supports'            => array( 'title', 'editor', 'thumbnail', 'elementor' ),
				'rewrite'             => array( 'slug' => 'header-footer-builder' ),
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
			register_post_type( 'hf_builder', $args );

			// Flush rewrite rules.
			if ( ! get_option( 'woostify_hf_builder_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				update_option( 'woostify_hf_builder_flush_rewrite_rules', true );
			}
		}

		/**
		 * Init
		 */
		public function wp_action() {
			$header_template_id = $this->template_exist( 'header' );
			$footer_template_id = $this->template_exist( 'footer' );

			if ( $header_template_id && $footer_template_id ) {
				if ( ! woostify_elementor_has_location( 'header' ) ) {
					// Header.
					remove_action( 'woostify_theme_header', 'woostify_template_header' );
					add_action( 'woostify_theme_header', 'woostify_view_open', 0 );
					add_action( 'woostify_theme_header', array( $this, 'print_header_template' ), 20 );
				}

				if ( ! woostify_elementor_has_location( 'footer' ) ) {
					// Footer.
					remove_action( 'woostify_theme_footer', 'woostify_template_footer' );
					add_action( 'woostify_theme_footer', array( $this, 'print_footer_template' ), 20 );
					add_action( 'woostify_after_footer', 'woostify_view_close', 0 );
				}
			} elseif ( $header_template_id && ! $footer_template_id ) {
				if ( ! woostify_elementor_has_location( 'header' ) ) {
					// Header.
					remove_action( 'woostify_theme_header', 'woostify_template_header' );
					add_action( 'woostify_theme_header', 'woostify_view_open', 0 );
					add_action( 'woostify_theme_header', array( $this, 'print_header_template' ), 20 );
				}
			} elseif ( ! $header_template_id && $footer_template_id ) {
				if ( ! woostify_elementor_has_location( 'footer' ) ) {
					// Footer.
					remove_action( 'woostify_theme_footer', 'woostify_template_footer' );
					add_action( 'woostify_theme_footer', array( $this, 'print_footer_template' ), 40 );
					add_action( 'woostify_after_footer', 'woostify_view_close', 0 );
				}
			}
		}

		/**
		 * Column head
		 *
		 * @param      array $defaults  The defaults.
		 */
		public function add_header_footer_builder_column_head( $defaults ) {
			$order    = array();
			$checkbox = 'title';
			foreach ( $defaults as $key => $value ) {
				$order[ $key ] = $value;
				if ( $key === $checkbox ) {
					$order['hf_builder_type'] = __( 'Type', 'woostify-pro' );
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
		public function add_header_footer_builder_column_content( $column_name, $post_id ) {
			if ( 'hf_builder_type' === $column_name ) {
				$type = woostify_get_metabox( $post_id, 'woostify-header-footer-builder-template' );
				?>
					<span><?php echo esc_html( ucfirst( $type ) ); ?></span>
				<?php
			}
		}

		/**
		 * Add Theme Builder admin menu
		 */
		public function add_header_footer_builder_admin_menu() {
			add_submenu_page( 'woostify-welcome', 'Header Footer Builder', __( 'Header Footer Builder', 'woostify-pro' ), 'manage_options', 'edit.php?post_type=hf_builder' );
		}

		/**
		 * Theme Builder metabox
		 */
		public function header_footer_builder_metabox() {
			add_action( 'add_meta_boxes', array( $this, 'setup_header_footer_builder_metabox' ) );
			add_action( 'save_post', array( $this, 'save_header_footer_builder_metabox' ) );

			self::$meta_option = array(
				'woostify-header-footer-builder-template' => array(
					'default'  => 'default',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'woostify-header-template-sticky'         => array(
					'default'  => 'default',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'woostify-header-template-shrink'         => array(
					'default'  => 'default',
					'sanitize' => 'FILTER_DEFAULT',
				),
				'woostify-header-template-sticky-on'      => array(
					'default'  => 'all-device',
					'sanitize' => 'FILTER_DEFAULT',
				),
			);
		}


		/**
		 * Detect meta value change
		 *
		 * @param      int $post_id The post ID.
		 */
		public function save_post_meta( $post_id ) {
			$post_type = get_post_type( $post_id );
			if ( 'hf_builder' !== $post_type ) {
				return;
			}

			$post_status        = get_post_status( $post_id );
			$header_template_id = intval( get_option( 'woostify_header_template_id' ) );
			$footer_template_id = intval( get_option( 'woostify_footer_template_id' ) );

			if ( 'publish' === $post_status ) {
				if ( 'header' === woostify_get_metabox( $post_id, 'woostify-header-footer-builder-template' ) ) {
					update_option( 'woostify_header_template_id', $post_id );
				} elseif ( 'footer' === woostify_get_metabox( $post_id, 'woostify-header-footer-builder-template' ) ) {
					update_option( 'woostify_footer_template_id', $post_id );
				}
			} else {
				if ( $header_template_id === $post_id ) {
					delete_option( 'woostify_header_template_id' );
				} elseif ( $footer_template_id === $post_id ) {
					delete_option( 'woostify_footer_template_id' );
				}
			}
		}

		/**
		 * Get metabox options
		 */
		public static function get_header_footer_builder_metabox_option() {
			return self::$meta_option;
		}

		/**
		 *  Setup Metabox
		 */
		public function setup_header_footer_builder_metabox() {
			add_meta_box(
				'woostify_metabox_settings_header_footer_builder',
				__( 'Header Footer Template Settings', 'woostify-pro' ),
				array( $this, 'header_footer_builder_markup' ),
				'hf_builder',
				'side'
			);
		}

		/**
		 * Metabox Markup
		 *
		 * @param  object $post Post object.
		 * @return void
		 */
		public function header_footer_builder_markup( $post ) {

			wp_nonce_field( basename( __FILE__ ), 'woostify_metabox_settings_header_footer_builder' );
			$stored = get_post_meta( $post->ID );

			// Set stored and override defaults.
			foreach ( $stored as $key => $value ) {
				self::$meta_option[ $key ]['default'] = isset( $stored[ $key ][0] ) ? $stored[ $key ][0] : '';
			}

			// Get defaults.
			$meta = self::get_header_footer_builder_metabox_option();

			$header_template_id = intval( get_option( 'woostify_header_template_id' ) );
			$footer_template_id = intval( get_option( 'woostify_footer_template_id' ) );

			/**
			 * Get options
			 */
			$template  = isset( $meta['woostify-header-footer-builder-template']['default'] ) ? $meta['woostify-header-footer-builder-template']['default'] : 'default';
			$sticky    = isset( $meta['woostify-header-template-sticky']['default'] ) ? $meta['woostify-header-template-sticky']['default'] : 'default';
			$shrink    = isset( $meta['woostify-header-template-shrink']['default'] ) ? $meta['woostify-header-template-shrink']['default'] : 'default';
			$sticky_on = isset( $meta['woostify-header-template-sticky-on']['default'] ) ? $meta['woostify-header-template-sticky-on']['default'] : 'default';
			?>

			<div class="woostify-metabox-setting woostify-multi-dependency">
				<div class="woostify-metabox-option woostify-multi-dependency-item active" data-dependency="start">
					<div class="woostify-metabox-option-title">
						<span><?php esc_html_e( 'Template', 'woostify-pro' ); ?>:</span>
					</div>

					<div class="woostify-metabox-option-content">
						<select name="woostify-header-footer-builder-template" id="woostify-header-footer-builder-template">
							<option value="default" <?php selected( $template, 'default' ); ?>>
								<?php esc_html_e( 'Select Option', 'woostify-pro' ); ?>
							</option>

							<option value="header" <?php selected( $template, 'header' ); ?> <?php disabled( ( $header_template_id !== $post->ID && $header_template_id ), true ); ?>>
								<?php esc_html_e( 'Header', 'woostify-pro' ); ?>
							</option>

							<option value="footer" <?php selected( $template, 'footer' ); ?> <?php disabled( ( $footer_template_id !== $post->ID && $footer_template_id ), true ); ?>>
								<?php esc_html_e( 'Footer', 'woostify-pro' ); ?>
							</option>
						</select>
					</div>
				</div>

				<div class="woostify-metabox-option woostify-multi-dependency-item <?php echo 'header' === $template ? 'active' : ''; ?>" data-required="header" data-dependency="start">
					<div class="woostify-metabox-option-content">
						<label for="woostify-header-template-sticky">
							<input type="checkbox" id="woostify-header-template-sticky" name="woostify-header-template-sticky" value="sticky" <?php checked( $sticky, 'sticky' ); ?> />
							<?php esc_html_e( 'Sticky', 'woostify-pro' ); ?>
						</label>
					</div>
				</div>

				<div class="woostify-metabox-option woostify-multi-dependency-item <?php echo 'sticky' === $sticky ? 'active' : ''; ?>" data-required="sticky">
					<div class="woostify-metabox-option-content">
						<label for="woostify-header-template-shrink">
							<input type="checkbox" id="woostify-header-template-shrink" name="woostify-header-template-shrink" value="shrink" <?php checked( $shrink, 'shrink' ); ?> />
							<?php esc_html_e( 'Shrink On Scroll', 'woostify-pro' ); ?>
						</label>
					</div>
				</div>

				<div class="woostify-metabox-option woostify-multi-dependency-item <?php echo 'sticky' === $sticky ? 'active' : ''; ?>" data-required="sticky">
					<div class="woostify-metabox-option-title">
						<span><?php esc_html_e( 'Sticky On', 'woostify-pro' ); ?>:</span>
					</div>

					<div class="woostify-metabox-option-content">
						<select name="woostify-header-template-sticky-on" id="woostify-header-template-sticky-on">
							<option value="all-device" <?php selected( $sticky_on, 'all-device' ); ?>>
								<?php esc_html_e( 'Desktop + Mobile', 'woostify-pro' ); ?>
							</option>

							<option value="desktop" <?php selected( $sticky_on, 'desktop' ); ?>>
								<?php esc_html_e( 'Desktop', 'woostify-pro' ); ?>
							</option>

							<option value="mobile" <?php selected( $sticky_on, 'mobile' ); ?>>
								<?php esc_html_e( 'Mobile', 'woostify-pro' ); ?>
							</option>
						</select>
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
		public function save_header_footer_builder_metabox( $post_id ) {

			// Checks save status.
			$is_user_can_edit = current_user_can( 'edit_posts' );
			$is_autosave      = wp_is_post_autosave( $post_id );
			$is_revision      = wp_is_post_revision( $post_id );
			$is_valid_nonce   = ( isset( $_POST['woostify_metabox_settings_header_footer_builder'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['woostify_metabox_settings_header_footer_builder'] ) ), basename( __FILE__ ) ) ) ? true : false;

			// Exits script depending on save status.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce || ! $is_user_can_edit ) {
				return;
			}

			/**
			 * Get meta options
			 */
			$post_meta = self::get_header_footer_builder_metabox_option();

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

				// Store values.
				if ( $meta_value ) {
					update_post_meta( $post_id, $key, $meta_value );
				} else {
					delete_post_meta( $post_id, $key );
				}
			}

		}

		/**
		 * Check header footer teamplte exist
		 *
		 * @param      string $template The template.
		 */
		public function template_exist( $template = 'header' ) {
			$args = array(
				'post_type'      => 'hf_builder',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_query'     => array( // phpcs:ignore
					array(
						'key'   => 'woostify-header-footer-builder-template',
						'value' => $template,
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
		 * Single hf_builder template
		 *
		 * @param string $template The path of the template to include.
		 */
		public function single_template( $template ) {
			if ( is_singular( 'hf_builder' ) && file_exists( WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php' ) ) {
				$template = WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php';
			}

			return $template;
		}

		/**
		 * Render Header Template
		 */
		public function print_header_template() {
			$id = $this->template_exist( 'header' );

			if ( ! $id ) {
				return;
			}

			$sticky    = get_post_meta( $id, 'woostify-header-template-sticky', true );
			$shrink    = get_post_meta( $id, 'woostify-header-template-shrink', true );
			$sticky_on = get_post_meta( $id, 'woostify-header-template-sticky-on', true );

			$classes[] = 'woostify-header-template-builder';
			$classes[] = 'sticky' === $sticky ? 'has-sticky' : '';
			$classes[] = 'sticky' === $sticky && 'shrink' === $shrink ? 'has-shrink' : '';
			$classes[] = 'sticky' === $sticky ? 'sticky-on-' . $sticky_on : '';
			$classes   = implode( ' ', array_filter( $classes ) );
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="woostify-header-template-builder-inner">
			<?php
				$frontend = new \Elementor\Frontend();
				echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
				wp_reset_postdata();
			?>
				</div>
			</div>
			<?php
		}

		/**
		 * Render Footer Template
		 */
		public function print_footer_template() {
			$id = $this->template_exist( 'footer' );

			if ( ! $id ) {
				return;
			}

			$frontend = new \Elementor\Frontend();
			echo $frontend->get_builder_content_for_display( $id, true ); // phpcs:ignore
			wp_reset_postdata();
		}

		/**
		 * Enqueue styles and scripts.
		 */
		public function enqueue_scripts() {
			$header_id = $this->template_exist( 'header' );
			$footer_id = $this->template_exist( 'footer' );

			if ( $header_id ) {
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $header_id );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $header_id );
				}

				$css_file->enqueue();
			}

			if ( $footer_id ) {
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $footer_id );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $footer_id );
				}

				$css_file->enqueue();
			}
		}

		/**
		 * Elementor enqueue styles and scripts.
		 */
		public function elementor_enqueue_scripts() {
			$header_id = $this->template_exist( 'header' );
			$footer_id = $this->template_exist( 'footer' );

			if ( ! $header_id && ! $footer_id ) {
				return;
			}

			// Add elementor frontend script.
			if ( ! woostify_is_elementor_page() ) {
				$elementor_frontend = new \Elementor\Frontend();
				$elementor_frontend->enqueue_scripts();
			}

			// Pro detect.
			if ( ! did_action( 'elementor_pro/init' ) ) {
				return false;
			}

			// Add elementor pro frontend script.
			if ( ! woostify_is_elementor_page() ) {
				$elementor_pro = \ElementorPro\Plugin::instance();
				$elementor_pro->enqueue_frontend_scripts();
				$elementor_pro->enqueue_styles();
			}
		}
	}

	Woostify_Header_Footer_Builder::get_instance();
}

