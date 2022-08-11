<?php
/**
 * Woostify Variation Swatches
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Variation_Swatches' ) ) :

	/**
	 * Woostify Variation Swatches
	 */
	class Woostify_Variation_Swatches {
		/**
		 * Instance Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Extra attribute types
		 *
		 * @var array
		 */
		public $types = array();

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
			$this->types = array(
				'color' => esc_html__( 'Color', 'woostify-pro' ),
				'image' => esc_html__( 'Image', 'woostify-pro' ),
				'label' => esc_html__( 'Label', 'woostify-pro' ),
			);

			$this->includes();
			$this->init_hooks();

			// Save settings.
			$woocommerce_helper = Woostify_Woocommerce_Helper::init();
			add_action( 'wp_ajax_woostify_save_variation_swatches_options', array( $woocommerce_helper, 'save_options' ) );

			// Add Setting url.
			add_action( 'admin_menu', array( $this, 'add_setting_url' ) );
			// Register settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_VARIATION_SWATCHES' ) ) {
				define( 'WOOSTIFY_PRO_VARIATION_SWATCHES', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {
			require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/variation-swatches/inc/class-woostify-variation-swatches-frontend.php';

			if ( is_admin() ) {
				require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/variation-swatches/inc/class-woostify-variation-swatches-admin.php';
			}
		}

		/**
		 * Initialize hooks
		 */
		public function init_hooks() {
			add_filter( 'product_attributes_type_selector', array( $this, 'add_attribute_types' ) );
		}

		/**
		 * Add extra attribute types
		 * Add color, image and label type
		 *
		 * @param array $types Types.
		 *
		 * @return array
		 */
		public function add_attribute_types( $types ) {
			$types = array_merge( $types, $this->types );

			return $types;
		}

		/**
		 * Add submenu
		 *
		 * @see  add_submenu_page()
		 */
		public function add_setting_url() {
			$sub_menu = add_submenu_page( 'woostify-welcome', 'Settings', __( 'Variation Swatches', 'woostify-pro' ), 'manage_options', 'variation-swatches-settings', array( $this, 'add_settings_page' ) );
		}

		/**
		 * Register settings
		 */
		public function register_settings() {
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_style' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_shop_page' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_quickview' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_size' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_tooltip' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_tooltip_background' );
			register_setting( 'variation-swatches-settings', 'woostify_variation_swatches_tooltip_color' );
		}

		/**
		 * Get options
		 */
		public function get_options() {
			$options                  = array();
			$options['style']         = get_option( 'woostify_variation_swatches_style', 'circle' );
			$options['shop_page']     = get_option( 'woostify_variation_swatches_shop_page', '0' );
			$options['quickview']     = get_option( 'woostify_variation_swatches_quickview', '1' );
			$options['size']          = get_option( 'woostify_variation_swatches_size', '34' );
			$options['tooltip']       = get_option( 'woostify_variation_swatches_tooltip', '1' );
			$options['tooltip_bg']    = get_option( 'woostify_variation_swatches_tooltip_background', '#333333' );
			$options['tooltip_color'] = get_option( 'woostify_variation_swatches_tooltip_color', '#ffffff' );

			return $options;
		}

		/**
		 * Create Settings page
		 */
		public function add_settings_page() {
			$options = $this->get_options();
			?>
			<div class="woostify-options-wrap woostify-featured-setting woostify-variation-swatches-product-setting" data-id="variation-swatches" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-variation-swatches-setting-nonce' ) ); ?>">

				<?php Woostify_Admin::get_instance()->woostify_welcome_screen_header(); ?>

				<div class="woostify-settings-box">
					<div class="woostify-welcome-container">
						<div class="woostify-settings-content">
							<h4 class="woostify-settings-section-title"><?php esc_html_e( 'Variation Swatches', 'woostify-pro' ); ?></h4>

							<div class="woostify-settings-section-content">
								<table class="form-table woostify-filter-settings">
									<tr>
										<th scope="row"><?php esc_html_e( 'Style', 'woostify-pro' ); ?>:</th>
										<td>
											<select name="woostify_variation_swatches_style">
												<option value ="squares" <?php selected( $options['style'], 'squares' ); ?>><?php esc_html_e( 'Squares', 'woostify-pro' ); ?></option>
												<option value ="circle" <?php selected( $options['style'], 'circle' ); ?>><?php esc_html_e( 'Circle', 'woostify-pro' ); ?></option>
											</select>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Shop Page', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_variation_swatches_shop_page">
												<input name="woostify_variation_swatches_shop_page" type="checkbox" id="woostify_variation_swatches_shop_page" value="<?php echo esc_attr( $options['shop_page'] ); ?>" <?php checked( $options['shop_page'], '1' ); ?> >
												<?php esc_html_e( 'Display swatches under product item on shop page.', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Quick View', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_variation_swatches_quickview">
												<input name="woostify_variation_swatches_quickview" type="checkbox" id="woostify_variation_swatches_quickview" value="<?php echo esc_attr( $options['quickview'] ); ?>"  <?php checked( $options['quickview'], '1' ); ?> >
												<?php esc_html_e( 'Display swatches on quick view popup.', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Size', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_variation_swatches_size">
												<input name="woostify_variation_swatches_size" type="number" id="woostify_variation_swatches_size" value="<?php echo esc_attr( $options['size'] ); ?>">
												<code>px</code>
											</label>
											<p class="woostify-setting-description"><?php esc_html_e( 'Size of swatches on product page. Unit pixel.', 'woostify-pro' ); ?></p>
										</td>
									</tr>

									<tr class="woostify-filter-item">
										<th scope="row"><?php esc_html_e( 'Tooltip', 'woostify-pro' ); ?>:</th>
										<td>
											<label for="woostify_variation_swatches_tooltip">
												<input class="woostify-filter-value" name="woostify_variation_swatches_tooltip" type="checkbox" id="woostify_variation_swatches_tooltip" value="<?php echo esc_attr( $options['tooltip'] ); ?>"  <?php checked( $options['tooltip'], '1' ); ?> >
												<?php esc_html_e( 'Display swatches tooltip.', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo esc_attr( '1' === $options['tooltip'] ? '' : 'hidden' ); ?>" data-type="0">
										<th scope="row"><?php esc_html_e( 'Tooltip Background', 'woostify-pro' ); ?>:</th>
										<td>
											<input class="woostify-admin-color-picker" name="woostify_variation_swatches_tooltip_background" type="text" id="woostify_variation_swatches_tooltip_background" value="<?php echo esc_attr( $options['tooltip_bg'] ); ?>">
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo esc_attr( '1' === $options['tooltip'] ? '' : 'hidden' ); ?>" data-type="0">
										<th scope="row"><?php esc_html_e( 'Tooltip Text Color', 'woostify-pro' ); ?>:</th>
										<td>
											<input class="woostify-admin-color-picker" name="woostify_variation_swatches_tooltip_color" type="text" id="woostify_variation_swatches_tooltip_color" value="<?php echo esc_attr( $options['tooltip_color'] ); ?>">
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

		/**
		 * Get attribute's properties
		 *
		 * @param string $taxonomy Taxonomy.
		 *
		 * @return object
		 */
		public function get_tax_attribute( $taxonomy ) {
			global $wpdb;

			$attr = substr( $taxonomy, 3 );
			$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" ); // phpcs:ignore

			return $attr;
		}
	}

	Woostify_Variation_Swatches::get_instance();
endif;
