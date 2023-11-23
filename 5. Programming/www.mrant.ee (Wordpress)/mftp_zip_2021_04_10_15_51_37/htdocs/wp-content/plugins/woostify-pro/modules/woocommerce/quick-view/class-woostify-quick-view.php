<?php
/**
 * Woostify Quick View Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Quick_View' ) ) :

	/**
	 * Woostify Quick View Class
	 */
	class Woostify_Quick_View {

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
			add_action( 'customize_register', array( $this, 'register_customizer' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );

			add_action( 'woostify_product_loop_item_action_item', array( $this, 'add_quick_view_button' ), 20 );
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'add_quick_view_button_with_text' ), 85 );

			// Add quick view popup markup.
			add_action( 'woostify_after_footer', array( $this, 'add_quick_view_panel' ), 50 );
			add_action( 'elementor/page_templates/canvas/after_content', array( $this, 'add_quick_view_panel' ), 40 );

			add_action( 'wp_ajax_shop_quick_view', array( $this, 'shop_quick_view' ) );
			add_action( 'wp_ajax_nopriv_shop_quick_view', array( $this, 'shop_quick_view' ) );

			add_filter( 'woostify_additional_class_loop_product_image', array( $this, 'additional_class' ) );
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_QUICK_VIEW' ) ) {
				define( 'WOOSTIFY_PRO_QUICK_VIEW', WOOSTIFY_PRO_VERSION );
			}
		}

		/**
		 * Gets default options.
		 *
		 * @return     Woostify  The default options.
		 */
		public function get_default_options() {
			$defaults = Woostify_Pro::get_instance()->default_options_value();

			return $defaults;
		}

		/**
		 * Gets the options.
		 *
		 * @return     Woostify  The options.
		 */
		public function get_options() {
			$options = Woostify_Pro::get_instance()->woostify_pro_options();

			return $options;
		}

		/**
		 * Script and style file.
		 */
		public function scripts() {

			$options          = $this->get_options();
			$woostify_options = woostify_options( false );

			if ( 'none' === $options['shop_page_quick_view_position'] ) {
				return;
			}

			// Tiny-slider script.
			wp_enqueue_script( 'tiny-slider' );

			// Quick View script.
			wp_enqueue_script(
				'woostify-quick-view',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/js/script' . woostify_suffix() . '.js',
				array( 'tiny-slider' ),
				WOOSTIFY_PRO_VERSION,
				true
			);

			wp_localize_script(
				'woostify-quick-view',
				'woostify_quick_view_data',
				array(
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'ajax_error' => __( 'Sorry, something went wrong. Please refresh this page and try again!', 'woostify-pro' ),
					'ajax_nonce' => wp_create_nonce( 'shop_quick_view' ),
				)
			);

			// Quick View style.
			wp_enqueue_style(
				'woostify-quick-view',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			$styles = '
				.product-loop-action .product-quick-view-btn:hover {
					background-color: ' . esc_attr( $woostify_options['button_hover_background_color'] ) . ';
				}

				.quick-view-with-text {
					border-radius: ' . esc_attr( $woostify_options['buttons_border_radius'] ) . 'px;
					color: ' . esc_attr( $woostify_options['button_text_color'] ) . ';
					background-color: ' . esc_attr( $woostify_options['button_background_color'] ) . ';
				}

				.quick-view-with-text:hover {
					color: ' . esc_attr( $woostify_options['button_hover_text_color'] ) . ';
					background-color: ' . esc_attr( $woostify_options['button_hover_background_color'] ) . ';
				}

				.quick-view-with-text.product-quick-view-btn, .product-loop-action .quick-view-with-icon {
					border-radius: ' . esc_attr( $options['shop_product_quick_view_radius'] ) . 'px;
				}
				.quick-view-with-text.product-quick-view-btn:hover,.product-loop-action .quick-view-with-icon:hover {
					background-color: ' . esc_attr( $options['shop_product_quick_view_bg_hover'] ) . ';
					color: ' . esc_attr( $options['shop_product_quick_view_c_hover'] ) . ';
				}
				.quick-view-with-text.product-quick-view-btn, .product-loop-action .quick-view-with-icon {
					color: ' . esc_attr( $options['shop_product_quick_view_color'] ) . ';
					background-color: ' . esc_attr( $options['shop_product_quick_view_background'] ) . ';
				}
			';

			wp_add_inline_style(
				'woostify-quick-view',
				$styles
			);

		}

		/**
		 * Register customizer
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function register_customizer( $wp_customize ) {

			// Defaults value.
			$defaults = $this->get_default_options();

			$wp_customize->add_setting(
				'shop_page_quick_view_section',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Section_Control(
					$wp_customize,
					'shop_page_quick_view_section',
					array(
						'label'      => __( 'Quick View Button', 'woostify-pro' ),
						'section'    => 'woostify_shop_page',
						'dependency' => array(
							'woostify_pro_options[shop_page_quick_view_position]',
							'woostify_pro_options[shop_product_quick_view_icon]',
							'woostify_pro_options[shop_product_quick_view_background]',
							'woostify_pro_options[shop_product_quick_view_color]',
							'woostify_pro_options[shop_product_quick_view_bg_hover]',
							'woostify_pro_options[shop_product_quick_view_c_hover]',
							'woostify_pro_options[shop_product_quick_view_radius]',

						),
					)
				)
			);

			// Position.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_page_quick_view_position]',
				array(
					'default'           => $defaults['shop_page_quick_view_position'],
					'sanitize_callback' => 'woostify_sanitize_choices',
					'type'              => 'option',
				)
			);
			$wp_customize->add_control(
				new Woostify_Radio_Image_Control(
					$wp_customize,
					'woostify_pro_options[shop_page_quick_view_position]',
					array(
						'label'    => __( 'Position', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_page_quick_view_position]',
						'choices'  => apply_filters(
							'woostify_pro_options_shop_page_quick_view_position_choices',
							array(
								'none'         => WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/images/quick-view-1.jpg',
								'top-right'    => WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/images/quick-view-2.jpg',
								'center-image' => WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/images/quick-view-3.jpg',
								'bottom-image' => WOOSTIFY_PRO_MODULES_URI . 'woocommerce/quick-view/images/quick-view-4.jpg',
							)
						),
					)
				)
			);

			// Quickview icon.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_icon]',
				array(
					'type'              => 'option',
					'default'           => $defaults['shop_product_quick_view_icon'],
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new Woostify_Switch_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_icon]',
					array(
						'label'    => __( 'Quick View Icon', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_product_quick_view_icon]',
					)
				)
			);

			// Quickview Background.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_background]',
				array(
					'default'           => $defaults['shop_product_quick_view_background'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_background]',
					array(
						'label'    => __( 'Background', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_product_quick_view_background]',
					)
				)
			);

			// Quickview Color.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_color]',
				array(
					'default'           => $defaults['shop_product_quick_view_color'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_color]',
					array(
						'label'    => __( 'Color', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_product_quick_view_color]',
					)
				)
			);

			// Quickview Hover Background.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_bg_hover]',
				array(
					'default'           => $defaults['shop_product_quick_view_bg_hover'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_bg_hover]',
					array(
						'label'    => __( 'Hover Background', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_product_quick_view_bg_hover]',
					)
				)
			);

			// Quickview Hover Color.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_c_hover]',
				array(
					'default'           => $defaults['shop_product_quick_view_c_hover'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_c_hover]',
					array(
						'label'    => __( 'Hover Color', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => 'woostify_pro_options[shop_product_quick_view_c_hover]',
					)
				)
			);

			// Border Radius.
			$wp_customize->add_setting(
				'woostify_pro_options[shop_product_quick_view_radius]',
				array(
					'default'           => $defaults['shop_product_quick_view_radius'],
					'type'              => 'option',
					'sanitize_callback' => 'esc_html',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new Woostify_Range_Slider_Control(
					$wp_customize,
					'woostify_pro_options[shop_product_quick_view_radius]',
					array(
						'label'    => __( 'Border Radius', 'woostify-pro' ),
						'section'  => 'woostify_shop_page',
						'settings' => array(
							'desktop' => 'woostify_pro_options[shop_product_quick_view_radius]',
						),
						'choices'  => array(
							'desktop' => array(
								'min'  => apply_filters( 'woostify_shop_product_quick_view_radius_min_step', 0 ),
								'max'  => apply_filters( 'woostify_shop_product_quick_view_radius_max_step', 50 ),
								'step' => 1,
								'edit' => true,
								'unit' => 'px',
							),
						),
					)
				)
			);
		}

		/**
		 * Quick view button Icon
		 */
		public function add_quick_view_button() {
			$options = $this->get_options();
			if ( 'top-right' !== $options['shop_page_quick_view_position'] ) {
				return;
			}

			global $product;
			$icon = apply_filters( 'woostify_pro_quick_view_button_icon', 'ti-eye' );
			?>

			<span title="<?php esc_attr_e( 'Quick View', 'woostify-pro' ); ?>" data-pid="<?php echo esc_attr( $product->get_id() ); ?>" class="<?php echo esc_attr( $icon ); ?> product-quick-view-btn quick-view-with-icon"></span>
			<?php
		}

		/**
		 * Quick view button Text
		 */
		public function add_quick_view_button_with_text() {
			$options = $this->get_options();
			if ( in_array( $options['shop_page_quick_view_position'], array( 'none', 'top-right' ), true ) ) {
				return;
			}

			global $product;
			$class[] = $options['shop_product_quick_view_icon'] ? apply_filters( 'woostify_pro_quick_view_button_icon', 'ti-eye' ) : '';
			$class[] = 'quick-view-with-text quick-view-on-' . $options['shop_page_quick_view_position'];
			$class   = implode( ' ', $class );
			?>

			<span title="<?php esc_attr_e( 'Quick View', 'woostify-pro' ); ?>" data-pid="<?php echo esc_attr( $product->get_id() ); ?>" class="<?php echo esc_attr( $class ); ?> product-quick-view-btn"><?php esc_html_e( 'Quick View', 'woostify-pro' ); ?></span>
			<?php
		}

		/**
		 * Additional class.
		 */
		public function additional_class() {
			$options          = $this->get_options();
			$woostify_options = woostify_options( false );

			if ( 'center-image' === $options['shop_page_quick_view_position'] && 'image' === $woostify_options['shop_page_add_to_cart_button_position'] ) {
				return 'flex-button';
			}

			return '';
		}

		/**
		 * Adds a quick view panel.
		 */
		public function add_quick_view_panel() {
			$options = $this->get_options();

			if ( 'none' === $options['shop_page_quick_view_position'] ) {
				return;
			}
			$icon = apply_filters( 'woostify_pro_quick_view_close_icon', 'ti-close' );
			?>
			<div id="woostify-quick-view-panel" data-view_id="0">
				<div class="shop-quick-view">
					<button class="quick-view-close-btn <?php echo esc_attr( $icon ); ?>"></button>
					<div class="quick-view-content"></div>
				</div>
			</div>
			<?php
		}

		/**
		 * Ajax Quick View
		 */
		public function shop_quick_view() {
			check_ajax_referer( 'shop_quick_view', 'ajax_nonce', false );

			$response = array(
				'status'  => 500,
				'message' => esc_html__( 'Something is wrong, please try again later...', 'woostify-pro' ),
				'content' => false,
			);

			if ( ! isset( $_POST['product_id'] ) ) {
				echo wp_json_encode( $response );
				exit();
			}

			$product_id = intval( $_POST['product_id'] );

			// For cross-sells on Cart page.
			$get_product = wc_get_product( $product_id );
			$parent_id   = $get_product->get_parent_id();

			if ( $parent_id ) {
				$product_id = $parent_id;
			}

			wp( 'p=' . $product_id . '&post_type=product' );

			ob_start();

			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					?>
					<div <?php wc_product_class(); ?>>
						<?php
						$product   = wc_get_product( $product_id );
						$image_id  = $product->get_image_id();
						$image_alt = woostify_image_alt( $image_id, esc_attr__( 'Product image', 'woostify-pro' ) );

						if ( $image_id ) {
							$image_medium_src = wp_get_attachment_image_src( $image_id, 'woocommerce_single' );
						} else {
							$image_medium_src[0] = wc_placeholder_img_src();
						}

						$gallery_id = $product->get_gallery_image_ids();
						$attr       = '';

						if ( ! empty( $gallery_id ) ) {
							$attr = 'class="quick-view-slider"';
						}
						?>
						<div class="quick-view-images">
							<div id="quick-view-gallery" <?php echo wp_kses_post( $attr ); ?>>
								<div class="image-item">
									<img src="<?php echo esc_url( $image_medium_src[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
								</div>

								<?php
								if ( ! empty( $gallery_id ) ) {
									foreach ( $gallery_id as $key ) {
										$g_full_img_src   = wp_get_attachment_image_src( $key, 'full' );
										$g_medium_img_src = wp_get_attachment_image_src( $key, 'medium_large' );
										$g_img_alt        = woostify_image_alt( $key, esc_attr__( 'Product image', 'woostify-pro' ) );
										?>
									<div class="image-item">
										<img src="<?php echo esc_url( $g_medium_img_src[0] ); ?>" alt="<?php echo esc_attr( $g_img_alt ); ?>">
									</div>
										<?php
									}
								}
								?>
							</div>
						</div>

						<div class="quick-view-summary">
							<?php do_action( 'woocommerce_single_product_summary' ); ?>
						</div>
					</div>

					<?php
					$response = array(
						'status' => 200,
					);
				}
			} else {
				$response = array(
					'status'  => 201,
					'message' => esc_html__( 'Sorry, nothing found', 'woostify-pro' ),
				);
			}

			$response['review_link'] = get_permalink( $product_id ) . '/#reviews';
			$response['content']     = ob_get_clean();

			wp_send_json( $response );
		}
	}

	Woostify_Quick_View::get_instance();
endif;
