<?php
/**
 * Woostify Countdown Urgency Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Countdown_Urgency' ) ) {

	/**
	 * Woostify Countdown Urgency Class
	 */
	class Woostify_Countdown_Urgency {

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

			// Save settings.
			add_action( 'wp_ajax_woostify_save_countdown_urgency_options', array( $woocommerce_helper, 'save_options' ) );

			// Add countdown on product loop.
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_content' ), 85 );

			// Add countdown on product single.
			add_action( 'woocommerce_single_product_summary', array( $this, 'single_content' ), 25 );

			// Add Setting url.
			add_action( 'admin_menu', array( $this, 'add_setting_url' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Ajax select data.
			add_action( 'wp_ajax_woostify_countdown_urgency_select_categories', array( $woocommerce_helper, 'select_categories' ) );
			add_action( 'wp_ajax_woostify_countdown_urgency_exclude_categories', array( $woocommerce_helper, 'exclude_categories' ) );
			add_action( 'wp_ajax_woostify_countdown_urgency_select_products', array( $woocommerce_helper, 'select_products' ) );
			add_action( 'wp_ajax_woostify_countdown_urgency_exclude_products', array( $woocommerce_helper, 'exclude_products' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_COUNTDOWN_URGENCY' ) ) {
				define( 'WOOSTIFY_PRO_COUNTDOWN_URGENCY', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Script and style file.
		 */
		public function scripts() {
			$options = woostify_options( false );

			wp_enqueue_script(
				'woostify-countdown-urgency',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/countdown-urgency/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			// Style.
			wp_enqueue_style(
				'woostify-countdown-urgency',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/countdown-urgency/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.single-product .woostify-countdown-urgency.default .woostify-countdown-urgency-message .woostify-countdown-urgency-message-text {
					background-color: ' . $options['shop_single_content_background'] . ';
				}

				.single-product .woostify-countdown-urgency.default .woostify-countdown-urgency-timer .woostify-cc-timer,
				.single-product .woostify-countdown-urgency.default .woostify-countdown-urgency-message .woostify-countdown-urgency-message-text {
					color: ' . $options['heading_color'] . ';
				}
			';
			wp_add_inline_style( 'woostify-countdown-urgency', $styles );
		}

		/**
		 * Add submenu
		 *
		 * @see  add_submenu_page()
		 */
		public function add_setting_url() {
			$sub_menu = add_submenu_page( 'woostify-welcome', 'Settings', __( 'Countdown Urgency', 'woostify-pro' ), 'manage_options', 'countdown-urgency-settings', array( $this, 'add_settings_page' ) );
		}

		/**
		 * Register settings
		 */
		public function register_settings() {
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_apply_for' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_categories_selected' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_products_selected' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_categories_exclude' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_products_exclude' );

			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_time_duration' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_time_type' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_message' );

			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_days_label' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_hours_label' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_minutes_label' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_seconds_label' );
			register_setting( 'countdown-urgency-settings', 'woostify_countdown_urgency_hide_after_time_up' );
		}

		/**
		 * Get options
		 */
		public function get_options() {
			$options                         = array();
			$options['style']                = get_option( 'woostify_countdown_urgency_style', 'default' );
			$options['type']                 = get_option( 'woostify_countdown_urgency_apply_for', 'all' );
			$options['selected_categories']  = get_option( 'woostify_countdown_urgency_categories_selected', false );
			$options['selected_products']    = get_option( 'woostify_countdown_urgency_products_selected', false );
			$options['exclude_categories']   = get_option( 'woostify_countdown_urgency_categories_exclude', false );
			$options['exclude_products']     = get_option( 'woostify_countdown_urgency_products_exclude', false );
			$options['duration']             = get_option( 'woostify_countdown_urgency_time_duration', '1' );
			$options['time']                 = get_option( 'woostify_countdown_urgency_time_type', 'days' );
			$options['message']              = get_option( 'woostify_countdown_urgency_message', __( 'Hurry up! Flash Sale Ends Soon!', 'woostify-pro' ) );
			$options['days_label']           = get_option( 'woostify_countdown_urgency_days_label', __( 'DAYS', 'woostify-pro' ) );
			$options['hours_label']          = get_option( 'woostify_countdown_urgency_hours_label', __( 'HOURS', 'woostify-pro' ) );
			$options['minutes_label']        = get_option( 'woostify_countdown_urgency_minutes_label', __( 'MINS', 'woostify-pro' ) );
			$options['seconds_label']        = get_option( 'woostify_countdown_urgency_seconds_label', __( 'SECS', 'woostify-pro' ) );
			$options['display_on_thumbnail'] = get_option( 'woostify_countdown_urgency_display_on_thumbnail', '0' ); // Checkbox default value 0 and 1.
			$options['hide']                 = get_option( 'woostify_countdown_urgency_hide_after_time_up', '1' ); // Checkbox default value 0 and 1.

			return $options;
		}

		/**
		 * Create Settings page
		 */
		public function add_settings_page() {
			$woocommerce_helper = Woostify_Woocommerce_Helper::init();
			$options            = $this->get_options();
			?>
			<div class="woostify-options-wrap woostify-featured-setting woostify-countdown-urgency-setting" data-id="countdown-urgency" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-countdown-urgency-setting-nonce' ) ); ?>">

				<?php Woostify_Admin::get_instance()->woostify_welcome_screen_header(); ?>

				<div class="woostify-settings-box">
					<div class="woostify-welcome-container">
						<div class="woostify-settings-content">
							<h4 class="woostify-settings-section-title"><?php esc_html_e( 'Countdown Urgency', 'woostify-pro' ); ?></h4>

							<div class="woostify-settings-section-content">
								<?php
								// Options.
								$style               = $options['style'];
								$type                = $options['type'];
								$selected_categories = $options['selected_categories'];
								$selected_products   = $options['selected_products'];
								$exclude_categories  = $options['exclude_categories'];
								$exclude_products    = $options['exclude_products'];
								$duration            = $options['duration'];
								$time                = $options['time'];
								$message             = $options['message'];
								$days_label          = $options['days_label'];
								$hours_label         = $options['hours_label'];
								$minutes_label       = $options['minutes_label'];
								$seconds_label       = $options['seconds_label'];

								// Get all product categories.
								$product_categories = get_terms( 'product_cat', array( 'hide_empty' => true ) );

								// Get all products.
								$args = array(
									'post_type'           => 'product',
									'posts_per_page'      => -1,
									'ignore_sticky_posts' => 1,
								);

								$products = new WP_Query( $args );
								?>

								<table class="form-table woostify-filter-settings">
									<tr>
										<th scope="row"><?php esc_html_e( 'Style', 'woostify-pro' ); ?>:</th>
										<td>
											<select name="woostify_countdown_urgency_style">
												<option value ="default" <?php selected( $style, 'default' ); ?>><?php esc_html_e( 'Default', 'woostify-pro' ); ?></option>
												<option value ="style-1" <?php selected( $style, 'style-1' ); ?>><?php esc_html_e( 'Style 1', 'woostify-pro' ); ?></option>
											</select>
										</td>
									</tr>

									<tr class="woostify-filter-item">
										<th scope="row"><?php esc_html_e( 'Apply For', 'woostify-pro' ); ?>:</th>
										<td>
											<select name="woostify_countdown_urgency_apply_for" class="woostify-filter-value">
												<option value ="all" <?php selected( $type, 'all' ); ?>><?php esc_html_e( 'All Product', 'woostify-pro' ); ?></option>
												<option value ="categories" <?php selected( $type, 'categories' ); ?>><?php esc_html_e( 'Categories', 'woostify-pro' ); ?></option>
												<option value ="products" <?php selected( $type, 'products' ); ?>><?php esc_html_e( 'Products', 'woostify-pro' ); ?></option>
											</select>
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo 'categories' === $type ? '' : 'hidden'; ?>" data-type="categories">
										<th scope="row"><?php esc_html_e( 'Select Categories', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="woostify-multi-selection">
												<input class="woostify-multi-select-value" name="woostify_countdown_urgency_categories_selected" type="hidden" value="<?php echo esc_attr( $selected_categories ); ?>">

												<div class="woostify-multi-select-selection">
													<?php $woocommerce_helper->render_selection( $selected_categories ); ?>

													<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-select-categories' ) ); ?>" name="woostify_countdown_urgency_select_categories">
												</div>

												<div class="woostify-multi-select-dropdown"></div>
											</div>

											<p class="woostify-setting-description"><?php esc_html_e( 'Type \'all\' to select all categories.', 'woostify-pro' ); ?></p>
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo 'products' === $type ? '' : 'hidden'; ?>" data-type="products">
										<th scope="row"><?php esc_html_e( 'Select Products', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="woostify-multi-selection">
												<input class="woostify-multi-select-value" name="woostify_countdown_urgency_products_selected" type="hidden" value="<?php echo esc_attr( $selected_products ); ?>">

												<div class="woostify-multi-select-selection">
													<?php $woocommerce_helper->render_selection( $selected_products, false ); ?>

													<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-select-products' ) ); ?>" name="woostify_countdown_urgency_select_products">
												</div>

												<div class="woostify-multi-select-dropdown"></div>
											</div>

											<p class="woostify-setting-description"><?php esc_html_e( 'Type \'all\' to select all products.', 'woostify-pro' ); ?></p>
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo 'all' === $type ? '' : 'hidden'; ?>" data-type="all">
										<th scope="row"><?php esc_html_e( 'Exclude Categories', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="woostify-multi-selection">
												<input class="woostify-multi-select-value" name="woostify_countdown_urgency_categories_exclude" type="hidden" value="<?php echo esc_attr( $exclude_categories ); ?>">

												<div class="woostify-multi-select-selection">
													<?php $woocommerce_helper->render_selection( $exclude_categories ); ?>

													<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-exclude-categories' ) ); ?>" name="woostify_countdown_urgency_exclude_categories">
												</div>

												<div class="woostify-multi-select-dropdown"></div>
											</div>
										</td>
									</tr>

									<tr class="woostify-filter-item <?php echo in_array( $type, array( 'all', 'categories' ), true ) ? '' : 'hidden'; ?>" data-type="all|categories">
										<th scope="row"><?php esc_html_e( 'Exclude Products', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="woostify-multi-selection">
												<input class="woostify-multi-select-value" name="woostify_countdown_urgency_products_exclude" type="hidden" value="<?php echo esc_attr( $exclude_products ); ?>">

												<div class="woostify-multi-select-selection">
													<?php $woocommerce_helper->render_selection( $exclude_products, false ); ?>

													<input type="text" class="woostify-multi-select-search" placeholder="<?php esc_attr_e( 'Please enter 1 or more characters', 'woostify-pro' ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'woostify-exclude-products' ) ); ?>" name="woostify_countdown_urgency_exclude_products">
												</div>

												<div class="woostify-multi-select-dropdown"></div>
											</div>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Message', 'woostify-pro' ); ?>:</th>
										<td>
											<textarea name="woostify_countdown_urgency_message" placeholder="<?php esc_attr_e( 'Hurry up! Flash Sale Ends Soon!', 'woostify-pro' ); ?>"><?php echo esc_html( $message ); ?></textarea>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Label', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="countdown-urgency-label">
												<label>
													<span><?php esc_html_e( 'Days', 'woostify-pro' ); ?>:</span>
													<input name="woostify_countdown_urgency_days_label" type="text" placeholder="<?php esc_attr_e( 'DAYS', 'woostify-pro' ); ?>" value="<?php echo esc_attr( $days_label ); ?>">
												</label>
												<label>
													<span><?php esc_html_e( 'Hours', 'woostify-pro' ); ?>:</span>
													<input name="woostify_countdown_urgency_hours_label" type="text" placeholder="<?php esc_attr_e( 'HOURS', 'woostify-pro' ); ?>" value="<?php echo esc_attr( $hours_label ); ?>">
												</label>
												<label>
													<span><?php esc_html_e( 'Minutes', 'woostify-pro' ); ?>:</span>
													<input name="woostify_countdown_urgency_minutes_label" type="text" placeholder="<?php esc_attr_e( 'MINS', 'woostify-pro' ); ?>" value="<?php echo esc_attr( $minutes_label ); ?>">
												</label>
												<label>
													<span><?php esc_html_e( 'Seconds', 'woostify-pro' ); ?>:</span>
													<input name="woostify_countdown_urgency_seconds_label" type="text" placeholder="<?php esc_attr_e( 'SECS', 'woostify-pro' ); ?>" value="<?php echo esc_attr( $seconds_label ); ?>">
												</label>
											</div>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Duration', 'woostify-pro' ); ?>:</th>
										<td>
											<div class="countdown-urgency-time-duration">
												<input name="woostify_countdown_urgency_time_duration" type="number" value="<?php echo esc_attr( $duration ); ?>" required="required">
												<select name="woostify_countdown_urgency_time_type">
													<option value ="days" <?php selected( $time, 'days' ); ?>><?php esc_html_e( 'Days', 'woostify-pro' ); ?></option>
													<option value ="hours" <?php selected( $time, 'hours' ); ?>><?php esc_html_e( 'Hours', 'woostify-pro' ); ?></option>
													<option value ="minutes" <?php selected( $time, 'minutes' ); ?>><?php esc_html_e( 'Minutes', 'woostify-pro' ); ?></option>
												</select>
											</div>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Display On Thumbnail', 'woostify-pro' ); ?></th>
										<td>
											<label for="woostify_countdown_urgency_display_on_thumbnail">
												<input name="woostify_countdown_urgency_display_on_thumbnail" type="checkbox" id="woostify_countdown_urgency_display_on_thumbnail" value="<?php echo esc_attr( $options['display_on_thumbnail'] ); ?>" <?php checked( $options['display_on_thumbnail'], '1' ); ?> >
												<?php esc_html_e( 'Display on thumbnail shop page.', 'woostify-pro' ); ?>
											</label>
										</td>
									</tr>

									<tr>
										<th scope="row"><?php esc_html_e( 'Hide After Time Up', 'woostify-pro' ); ?></th>
										<td>
											<label for="woostify_countdown_urgency_hide_after_time_up">
												<input name="woostify_countdown_urgency_hide_after_time_up" type="checkbox" id="woostify_countdown_urgency_hide_after_time_up" value="<?php echo esc_attr( $options['hide'] ); ?>" <?php checked( $options['hide'], '1' ); ?> >
												<?php esc_html_e( 'Hide Countdown Urgency section after time up.', 'woostify-pro' ); ?>
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

		/**
		 * Render countdown on product loop.
		 */
		public function loop_content() {
			$options = $this->get_options();

			if ( ! $options['display_on_thumbnail'] ) {
				return;
			}

			$this->countdown_urgency_content();
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

			$this->countdown_urgency_content();
		}

		/**
		 * Html markup.
		 */
		public function countdown_urgency_content() {
			// Get product info.
			global $product;

			if ( class_exists( 'Woostify_Woo_Builder' ) && is_singular( 'product' ) ) {
				$product_id = \Woostify_Woo_Builder::init()->get_product_id();
				$product    = wc_get_product( $product_id );
			}
			if ( empty( $product ) ) {
				return;
			}

			$product_id      = (string) $product->get_id();
			$product_cat_ids = $product->get_category_ids();

			// Options.
			$options          = $this->get_options();
			$type             = $options['type'];
			$exclude_products = 'default' !== $options['exclude_products'] ? $options['exclude_products'] : false;
			$render           = false;

			// Condition.
			switch ( $type ) {
				default:
				case 'all':
					// Exclude from categories.
					$exclude_categories = 'default' !== $options['exclude_categories'] ? array_map( 'intval', explode( '|', $options['exclude_categories'] ) ) : false;
					if ( $exclude_categories ) {
						$all_merge  = array_merge( $product_cat_ids, $exclude_categories );
						$all_unique = array_unique( $all_merge );

						if ( count( $all_merge ) !== count( $all_unique ) ) {
							return;
						}
					}

					// Exclude from products.
					if ( $exclude_products && false !== strpos( $exclude_products, $product_id ) ) {
						return;
					}

					$render = true;
					break;
				case 'categories':
					// Select from categories.
					if ( 'default' === $options['selected_categories'] ) {
						return;
					}

					$selected_categories = false !== strpos( $options['selected_categories'], 'all' ) ? 'all' : array_map( 'intval', explode( '|', $options['selected_categories'] ) );

					if ( 'all' !== $selected_categories ) {
						$cat_merge  = array_merge( $product_cat_ids, $selected_categories );
						$cat_unique = array_unique( $cat_merge );

						if ( count( $cat_merge ) === count( $cat_unique ) ) {
							return;
						}
					}

					// Exclude from products.
					if ( $exclude_products && false !== strpos( $exclude_products, $product_id ) ) {
						return;
					}

					$render = true;
					break;
				case 'products':
					// Select from products.
					if ( 'default' === $options['selected_products'] || ( 'all' !== $options['selected_products'] && false === strpos( $options['selected_products'], $product_id ) ) ) {
						return;
					}

					$render = true;
					break;
			}

			// Options time.
			$duration = $options['duration'] ? (int) $options['duration'] : 1;
			$time_up  = $options['hide'] ? 'hide' : '';
			$label    = array(
				'days'    => $options['days_label'],
				'hours'   => $options['hours_label'],
				'minutes' => $options['minutes_label'],
				'seconds' => $options['seconds_label'],
			);

			switch ( $options['time'] ) {
				case 'days':
					$duration = $duration * 86400000;
					break;
				case 'hours':
					$duration = $duration * 3600000;
					break;
				default:
				case 'minutes':
					$duration = $duration * 60000;
					break;
			}

			// For special product schedule sale.
			$sale_from = gmdate( 'Y-m-d 00:00:00' );
			$sale_to   = (int) get_post_meta( $product_id, '_sale_price_dates_to', true );

			if ( $sale_to > time() ) {
				$sale_from = new DateTime( $sale_from );
				$sale_to   = new DateTime( gmdate( 'Y-m-d 23:59:59', $sale_to ) );
				$days      = $sale_to->diff( $sale_from )->format( '%a' );
				$duration  = $days * 86400000;
			}
			?>

			<div class="woostify-countdown-urgency <?php echo esc_attr( is_singular() ? $options['style'] : '' ); ?>" data-duration="<?php echo esc_attr( $duration ); ?>" data-time-up="<?php echo esc_attr( $time_up ); ?>">
				<?php if ( $options['message'] && is_singular( 'product' ) ) { ?>
					<div class="woostify-countdown-urgency-message">
						<div class="woostify-countdown-urgency-message-text">
							<?php echo wp_kses_post( $options['message'] ); ?>
						</div>
					</div>
				<?php } ?>

				<?php if ( $render ) { ?>
					<div class="woostify-countdown-urgency-timer">
						<?php foreach ( $label as $k => $v ) { ?>
							<div class="woostify-cc-timer-item">
								<div class="woostify-cc-timer" data-time="<?php echo esc_attr( $k ); ?>">00</div>

								<?php if ( $v ) { ?>
									<div class="woostify-cc-timer-label"><?php echo esc_html( $v ); ?></div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}

	Woostify_Countdown_Urgency::get_instance();
}
