<?php
/**
 * Woostify Mega Menu Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Mega_Menu' ) ) {

	/**
	 * Woostify Mega Menu Class
	 */
	class Woostify_Mega_Menu {

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
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );
			add_action( 'init', array( $this, 'init_action' ), 0 );
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_filter( 'template_include', array( $this, 'single_template' ) );
			add_filter( 'woostify_register_nav_menus', array( $this, 'register_nav_menu' ) );

			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_custom_nav_fields' ) );

			add_action( 'wp_ajax_woostify_render_popup', array( $this, 'render_mega_menu_popup' ) );
			add_action( 'wp_ajax_woostify_save_menu_options', array( $this, 'save_mega_menu_options' ) );
			add_action( 'admin_footer', array( $this, 'mega_menu_popup_wrapper' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_MEGA_MENU' ) ) {
				define( 'WOOSTIFY_PRO_MEGA_MENU', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Init
		 */
		public function init_action() {
			// Register a Mega Menu post type.
			$args = array(
				'label'               => _x( 'Mega Menu', 'post type label', 'woostify-pro' ),
				'singular_name'       => _x( 'Mega Menu', 'post type singular name', 'woostify-pro' ),
				'supports'            => array( 'title', 'editor', 'thumbnail', 'elementor' ),
				'rewrite'             => array( 'slug' => 'mega-menu' ),
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
			register_post_type( 'mega_menu', $args );

			// Flush rewrite rules.
			if ( ! get_option( 'woostify_mega_menu_builder_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				update_option( 'woostify_mega_menu_builder_flush_rewrite_rules', true );
			}
		}

		/**
		 * Add Mega Menu admin menu
		 */
		public function add_admin_menu() {
			add_submenu_page( 'woostify-welcome', 'Mega Menu', __( 'Mega Menu', 'woostify-pro' ), 'manage_options', 'edit.php?post_type=mega_menu' );
		}

		/**
		 * Single mega_menu template
		 *
		 * @param string $template The path of the template to include.
		 */
		public function single_template( $template ) {
			if ( is_singular( 'mega_menu' ) && file_exists( WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php' ) ) {
				$template = WOOSTIFY_THEME_DIR . 'inc/elementor/elementor-library.php';
			}

			return $template;
		}

		/**
		 * Register new nav menu
		 *
		 * @param array $nav Nav menu.
		 */
		public function register_nav_menu( $nav ) {
			$nav['vertical'] = __( 'Vertical Menu', 'woostify-pro' );

			return $nav;
		}

		/**
		 * Admin script and styles
		 */
		public function admin_scripts() {
			$screen      = get_current_screen();
			$is_nav_menu = false !== strpos( $screen->id, 'nav-menu' );

			if ( $is_nav_menu ) {
				/**
				 * Script
				 */
				wp_enqueue_script(
					'woostify-admin-mega-menu',
					WOOSTIFY_PRO_URI . 'modules/mega-menu/js/admin' . woostify_suffix() . '.js',
					array(),
					WOOSTIFY_PRO_VERSION,
					true
				);

				wp_localize_script(
					'woostify-admin-mega-menu',
					'woostify_admin_mega_menu',
					array(
						'button_label' => __( 'Woostify Menu Options', 'woostify-pro' ),
						'ajax_nonce'   => wp_create_nonce( 'woostify-mega-menu-nonce' ),
					)
				);

				/**
				 * Style
				 */
				wp_enqueue_style(
					'woostify-admin-mega-menu',
					WOOSTIFY_PRO_URI . 'modules/mega-menu/css/admin.css',
					array(),
					WOOSTIFY_PRO_VERSION
				);

				wp_enqueue_style(
					'woostify-admin-themify-icons',
					WOOSTIFY_PRO_URI . 'modules/mega-menu/css/themify-icons.css',
					array(),
					WOOSTIFY_PRO_VERSION
				);
			}
		}

		/**
		 * Frontend script and styles
		 */
		public function frontend_scripts() {
			/**
			 * Script
			 */
			wp_enqueue_script(
				'woostify-mega-menu',
				WOOSTIFY_PRO_URI . 'modules/mega-menu/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			/**
			 * Style
			 */
			wp_enqueue_style(
				'woostify-mega-menu',
				WOOSTIFY_PRO_URI . 'modules/mega-menu/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			/**
			 * Css frontend
			 */
			if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
				wp_enqueue_style(
					'elementor-pro-css',
					get_site_url() . '/wp-content/plugins/elementor-pro/assets/css/frontend.css',
					array(),
					WOOSTIFY_PRO_VERSION
				);
			}
		}

		/**
		 * Add custom fields nav menu
		 *
		 * @param      object $menu_item The menu item.
		 */
		public function add_custom_nav_fields( $menu_item ) {
			$menu_item->megamenu_width = get_post_meta( $menu_item->ID, 'woostify_mega_menu_item_width', true );
			$menu_item->megamenu_url   = get_post_meta( $menu_item->ID, 'woostify_mega_menu_item_url', true );
			$menu_item->megamenu_icon  = get_post_meta( $menu_item->ID, 'woostify_mega_menu_item_icon', true );

			return $menu_item;
		}

		/**
		 * Render mega menu popup
		 */
		public function render_mega_menu_popup() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			$menu_id = isset( $_POST['menu_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) ) : '';

			check_ajax_referer( 'woostify-mega-menu-nonce', 'security_nonce' );

			$icons = $this->themify_icons();

			ob_start();
			$width = get_post_meta( $menu_id, 'woostify_mega_menu_item_width', true );
			$url   = get_post_meta( $menu_id, 'woostify_mega_menu_item_url', true );
			$icon  = get_post_meta( $menu_id, 'woostify_mega_menu_item_icon', true );

			if ( ! $url ) {
				$url = '#';
			}

			?>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Width', 'woostify-pro' ); ?>:</th>
						<td>
							<select name="woostify_mega_menu_item_width">
								<option value ="content" <?php selected( $width, 'content' ); ?>><?php esc_html_e( 'Default', 'woostify-pro' ); ?></option>
								<option value ="container" <?php selected( $width, 'container' ); ?>><?php esc_html_e( 'Container Width', 'woostify-pro' ); ?></option>
								<option value ="full" <?php selected( $width, 'full' ); ?>><?php esc_html_e( 'Full', 'woostify-pro' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Url', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="woostify_mega_menu_item_url">
								<input type="text" value="<?php echo esc_attr( $url ); ?>" name="woostify_mega_menu_item_url">
							</div>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Icon', 'woostify-pro' ); ?>:</th>
						<td>
							<div class="woostify-icon-picker">
								<div class="woostify-icon-picker-inner">
									<?php
									foreach ( $icons as $k ) {
										$selected = $k === $icon ? 'selected' : '';
										?>
										<span class="<?php echo esc_attr( $k ); ?> <?php echo esc_attr( $selected ); ?>" title="<?php echo esc_attr( $k ); ?>"></span>
										<?php
									}
									?>
								</div>
								<input type="hidden" class="woostify-icon-picker-value" value="<?php echo esc_attr( $icon ); ?>" name="woostify_mega_menu_item_icon">
							</div>
						</td>
					</tr>
				</table>
			<?php
			$html = ob_get_clean();

			wp_send_json_success( $html );
		}

		/**
		 * Save mega menu options
		 */
		public function save_mega_menu_options() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			$menu_id = isset( $_POST['menu_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) ) : '';
			check_ajax_referer( 'woostify-mega-menu-nonce', 'security_nonce' );

			$options = isset( $_POST['options'] ) ? (array) json_decode( sanitize_text_field( wp_unslash( $_POST['options'] ) ), true ) : array();

			if ( ! empty( $options ) ) {
				foreach ( $options as $k => $v ) {
					$value = sanitize_text_field( wp_unslash( $v ) );
					update_post_meta( $menu_id, $k, $value );
				}
			}

			wp_send_json_success();
		}

		/**
		 * Print popup html markup
		 */
		public function mega_menu_popup_wrapper() {
			$screen   = get_current_screen();
			$nav_menu = $screen ? false === strpos( $screen->id, 'nav-menus' ) : false;
			if ( ! $screen || $nav_menu ) {
				return;
			}
			?>

			<div class="woostify-mega-menu-options-popup" data-id="0">
				<div class="woostify-mega-menu-popup-inner">
					<span class="spinner woostify-spinner"></span>

					<div class="woostify-mega-menu-popup-head">
						<h3 class="woostify-mega-menu-popup-title"><?php esc_html_e( 'Mega Menu Options', 'woostify-pro' ); ?></h3>
						<span class="woostify-mega-menu-editing-label" data-label="<?php esc_attr_e( 'Editing', 'woostify-pro' ); ?>"></span>
						<span class="woostify-mega-menu-popup-button-close dashicons dashicons-no-alt"></span>
					</div>

					<div class="woostify-mega-menu-popup-content"></div>

					<div class="woostify-mega-menu-popup-footer">
						<span class="save-options button button-primary"><?php esc_html_e( 'Save', 'woostify-pro' ); ?></span>
						<span class="spinner"></span>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Themify icons
		 */
		public function themify_icons() {
			$icons = array( 'ti-wand', 'ti-volume', 'ti-user', 'ti-unlock', 'ti-unlink', 'ti-trash', 'ti-thought', 'ti-target', 'ti-tag', 'ti-tablet', 'ti-star', 'ti-spray', 'ti-signal', 'ti-shopping-cart', 'ti-shopping-cart-full', 'ti-settings', 'ti-search', 'ti-zoom-in', 'ti-zoom-out', 'ti-cut', 'ti-ruler', 'ti-ruler-pencil', 'ti-ruler-alt', 'ti-bookmark', 'ti-bookmark-alt', 'ti-reload', 'ti-plus', 'ti-pin', 'ti-pencil', 'ti-pencil-alt', 'ti-paint-roller', 'ti-paint-bucket', 'ti-na', 'ti-mobile', 'ti-minus', 'ti-medall', 'ti-medall-alt', 'ti-marker', 'ti-marker-alt', 'ti-arrow-up', 'ti-arrow-right', 'ti-arrow-left', 'ti-arrow-down', 'ti-lock', 'ti-location-arrow', 'ti-link', 'ti-layout', 'ti-layers', 'ti-layers-alt', 'ti-key', 'ti-import', 'ti-image', 'ti-heart', 'ti-heart-broken', 'ti-hand-stop', 'ti-hand-open', 'ti-hand-drag', 'ti-folder', 'ti-flag', 'ti-flag-alt', 'ti-flag-alt-2', 'ti-eye', 'ti-export', 'ti-exchange-vertical', 'ti-desktop', 'ti-cup', 'ti-crown', 'ti-comments', 'ti-comment', 'ti-comment-alt', 'ti-close', 'ti-clip', 'ti-angle-up', 'ti-angle-right', 'ti-angle-left', 'ti-angle-down', 'ti-check', 'ti-check-box', 'ti-camera', 'ti-announcement', 'ti-brush', 'ti-briefcase', 'ti-bolt', 'ti-bolt-alt', 'ti-blackboard', 'ti-bag', 'ti-move', 'ti-arrows-vertical', 'ti-arrows-horizontal', 'ti-fullscreen', 'ti-arrow-top-right', 'ti-arrow-top-left', 'ti-arrow-circle-up', 'ti-arrow-circle-right', 'ti-arrow-circle-left', 'ti-arrow-circle-down', 'ti-angle-double-up', 'ti-angle-double-right', 'ti-angle-double-left', 'ti-angle-double-down', 'ti-zip', 'ti-world', 'ti-wheelchair', 'ti-view-list', 'ti-view-list-alt', 'ti-view-grid', 'ti-uppercase', 'ti-upload', 'ti-underline', 'ti-truck', 'ti-timer', 'ti-ticket', 'ti-thumb-up', 'ti-thumb-down', 'ti-text', 'ti-stats-up', 'ti-stats-down', 'ti-split-v', 'ti-split-h', 'ti-smallcap', 'ti-shine', 'ti-shift-right', 'ti-shift-left', 'ti-shield', 'ti-notepad', 'ti-server', 'ti-quote-right', 'ti-quote-left', 'ti-pulse', 'ti-printer', 'ti-power-off', 'ti-plug', 'ti-pie-chart', 'ti-paragraph', 'ti-panel', 'ti-package', 'ti-music', 'ti-music-alt', 'ti-mouse', 'ti-mouse-alt', 'ti-money', 'ti-microphone', 'ti-menu', 'ti-menu-alt', 'ti-map', 'ti-map-alt', 'ti-loop', 'ti-location-pin', 'ti-list', 'ti-light-bulb', 'ti-Italic', 'ti-info', 'ti-infinite', 'ti-id-badge', 'ti-hummer', 'ti-home', 'ti-help', 'ti-headphone', 'ti-harddrives', 'ti-harddrive', 'ti-gift', 'ti-game', 'ti-filter', 'ti-files', 'ti-file', 'ti-eraser', 'ti-envelope', 'ti-download', 'ti-direction', 'ti-direction-alt', 'ti-dashboard', 'ti-control-stop', 'ti-control-shuffle', 'ti-control-play', 'ti-control-pause', 'ti-control-forward', 'ti-control-backward', 'ti-cloud', 'ti-cloud-up', 'ti-cloud-down', 'ti-clipboard', 'ti-car', 'ti-calendar', 'ti-book', 'ti-bell', 'ti-basketball', 'ti-bar-chart', 'ti-bar-chart-alt', 'ti-back-right', 'ti-back-left', 'ti-arrows-corner', 'ti-archive', 'ti-anchor', 'ti-align-right', 'ti-align-left', 'ti-align-justify', 'ti-align-center', 'ti-alert', 'ti-alarm-clock', 'ti-agenda', 'ti-write', 'ti-window', 'ti-widgetized', 'ti-widget', 'ti-widget-alt', 'ti-wallet', 'ti-video-clapper', 'ti-video-camera', 'ti-vector', 'ti-themify-logo', 'ti-themify-favicon', 'ti-themify-favicon-alt', 'ti-support', 'ti-stamp', 'ti-split-v-alt', 'ti-slice', 'ti-shortcode', 'ti-shift-right-alt', 'ti-shift-left-alt', 'ti-ruler-alt-2', 'ti-receipt', 'ti-pin2', 'ti-pin-alt', 'ti-pencil-alt2', 'ti-palette', 'ti-more', 'ti-more-alt', 'ti-microphone-alt', 'ti-magnet', 'ti-line-double', 'ti-line-dotted', 'ti-line-dashed', 'ti-layout-width-full', 'ti-layout-width-default', 'ti-layout-width-default-alt', 'ti-layout-tab', 'ti-layout-tab-window', 'ti-layout-tab-v', 'ti-layout-tab-min', 'ti-layout-slider', 'ti-layout-slider-alt', 'ti-layout-sidebar-right', 'ti-layout-sidebar-none', 'ti-layout-sidebar-left', 'ti-layout-placeholder', 'ti-layout-menu', 'ti-layout-menu-v', 'ti-layout-menu-separated', 'ti-layout-menu-full', 'ti-layout-media-right-alt', 'ti-layout-media-right', 'ti-layout-media-overlay', 'ti-layout-media-overlay-alt', 'ti-layout-media-overlay-alt-2', 'ti-layout-media-left-alt', 'ti-layout-media-left', 'ti-layout-media-center-alt', 'ti-layout-media-center', 'ti-layout-list-thumb', 'ti-layout-list-thumb-alt', 'ti-layout-list-post', 'ti-layout-list-large-image', 'ti-layout-line-solid', 'ti-layout-grid4', 'ti-layout-grid3', 'ti-layout-grid2', 'ti-layout-grid2-thumb', 'ti-layout-cta-right', 'ti-layout-cta-left', 'ti-layout-cta-center', 'ti-layout-cta-btn-right', 'ti-layout-cta-btn-left', 'ti-layout-column4', 'ti-layout-column3', 'ti-layout-column2', 'ti-layout-accordion-separated', 'ti-layout-accordion-merged', 'ti-layout-accordion-list', 'ti-ink-pen', 'ti-info-alt', 'ti-help-alt', 'ti-headphone-alt', 'ti-hand-point-up', 'ti-hand-point-right', 'ti-hand-point-left', 'ti-hand-point-down', 'ti-gallery', 'ti-face-smile', 'ti-face-sad', 'ti-credit-card', 'ti-control-skip-forward', 'ti-control-skip-backward', 'ti-control-record', 'ti-control-eject', 'ti-comments-smiley', 'ti-brush-alt', 'ti-youtube', 'ti-vimeo', 'ti-twitter', 'ti-time', 'ti-tumblr', 'ti-skype', 'ti-share', 'ti-share-alt', 'ti-rocket', 'ti-pinterest', 'ti-new-window', 'ti-microsoft', 'ti-list-ol', 'ti-linkedin', 'ti-layout-sidebar-2', 'ti-layout-grid4-alt', 'ti-layout-grid3-alt', 'ti-layout-grid2-alt', 'ti-layout-column4-alt', 'ti-layout-column3-alt', 'ti-layout-column2-alt', 'ti-instagram', 'ti-google', 'ti-github', 'ti-flickr', 'ti-facebook', 'ti-dropbox', 'ti-dribbble', 'ti-apple', 'ti-android', 'ti-save', 'ti-save-alt', 'ti-yahoo', 'ti-wordpress', 'ti-vimeo-alt', 'ti-twitter-alt', 'ti-tumblr-alt', 'ti-trello', 'ti-stack-overflow', 'ti-soundcloud', 'ti-sharethis', 'ti-sharethis-alt', 'ti-reddit', 'ti-pinterest-alt', 'ti-microsoft-alt', 'ti-linux', 'ti-jsfiddle', 'ti-joomla', 'ti-html5', 'ti-flickr-alt', 'ti-email', 'ti-drupal', 'ti-dropbox-alt', 'ti-css3', 'ti-rss', 'ti-rss-alt' );

			return $icons;
		}
	}

	Woostify_Mega_Menu::get_instance();
}