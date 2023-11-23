<?php
/**
 * Woostify Sticky Header Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Sticky_Header' ) ) :

	/**
	 * Woostify Sticky Header Class
	 */
	class Woostify_Sticky_Header {

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
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'customize_register', array( $this, 'register_customizer' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );
			add_action( 'body_class', array( $this, 'add_sticky_header' ), 99 );
		}

		/**
		 * Init
		 */
		public function init() {
			add_filter( 'woostify_customizer_layout_sections', array( $this, 'add_sticky_header_section' ) );
		}

		/**
		 * Add sticky header section
		 *
		 * @param array $arr Layout sections.
		 */
		public function add_sticky_header_section( $arr ) {
			// Set id and title for Sticky header.
			$sticky_header = array( 'woostify_sticky_header' => __( 'Sticky Header', 'woostify-pro' ) );

			// Insert after 'woostify_header_transparent' key.
			$arr           = woostify_array_insert( $arr, $sticky_header, 'woostify_header_transparent' );

			return $arr;
		}

		/**
		 * Detect sticky header on current page
		 */
		public function sticky_header() {
			$options               = Woostify_Pro::get_instance()->woostify_pro_options();
			$sticky_header         = $options['sticky_header_display'];
			$archive_sticky_header = $options['sticky_header_disable_archive'];
			$index_sticky_header   = $options['sticky_header_disable_index'];
			$page_sticky_header    = $options['sticky_header_disable_page'];
			$post_sticky_header    = $options['sticky_header_disable_post'];
			$shop_sticky_header    = $options['sticky_header_disable_shop'];

			// Disable sticky header on Shop page.
			if ( class_exists( 'woocommerce' ) && is_shop() && $shop_sticky_header ) {
				$sticky_header = false;
			} elseif ( ( is_post_type_archive( 'post' ) || is_404() || is_search() ) && $archive_sticky_header ) {
				// Disable sticky header on Archive, 404 and Search page.
				$sticky_header = false;
			} elseif ( is_home() && $index_sticky_header ) {
				// Disable sticky header on Blog page.
				$sticky_header = false;
			} elseif ( is_page() && $page_sticky_header ) {
				// Disable sticky header on Pages.
				$sticky_header = false;
			} elseif ( is_singular( 'post' ) && $post_sticky_header ) {
				// Disable sticky header on Posts.
				$sticky_header = false;
			}

			return $sticky_header;
		}

		/**
		 * Define constant
		 */
		public function define_constants() {
			if ( ! defined( 'WOOSTIFY_PRO_STICKY_HEADER' ) ) {
				define( 'WOOSTIFY_PRO_STICKY_HEADER', WOOSTIFY_PRO_VERSION );
			}

			define( 'WOOSTIFY_PRO_STICKY_HEADER_URI', WOOSTIFY_PRO_URI . 'modules/sticky-header/' );
		}

		/**
		 * Header class
		 *
		 * @param      array $classes  The classes.
		 */
		public function add_sticky_header( $classes ) {
			$options = Woostify_Pro::get_instance()->woostify_pro_options();

			if ( $this->sticky_header() ) {
				$classes[] = 'has-sticky-header sticky-header-for-' . $options['sticky_header_enable_on'];
			}

			return $classes;
		}

		/**
		 * Sets up.
		 */
		public function scripts() {
			$options = Woostify_Pro::get_instance()->woostify_pro_options();

			/**
			 * Script
			 */
			wp_enqueue_script(
				'woostify-sticky-header',
				WOOSTIFY_PRO_STICKY_HEADER_URI . 'js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			/**
			 * Style
			 */
			wp_enqueue_style(
				'woostify-sticky-header',
				WOOSTIFY_PRO_STICKY_HEADER_URI . 'css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);

			// Add style inline.
			$styles = '
				.has-sticky-header .site-header-inner.fixed {
					background-color: ' . esc_attr( $options['sticky_header_background_color'] ) . ';
					border-bottom-color: ' . esc_attr( $options['sticky_header_border_color'] ) . ';
					border-bottom-width: ' . esc_attr( $options['sticky_header_border_width'] ) . 'px;
				}

				@media ( min-width: 992px ) {
					.has-sticky-header .site-header.has-navigation-box .navigation-box-inner.fixed {
						background-color: ' . esc_attr( $options['sticky_header_background_color'] ) . ';
						border-bottom-color: ' . esc_attr( $options['sticky_header_border_color'] ) . ';
						border-bottom-width: ' . esc_attr( $options['sticky_header_border_width'] ) . 'px;
					}
				}
			';
			wp_add_inline_style(
				'woostify-sticky-header',
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
			$defaults = Woostify_Pro::get_instance()->default_options_value();

			// Sticky header display.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_display]',
				array(
					'type'              => 'option',
					'default'           => $defaults['sticky_header_display'],
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new Woostify_Switch_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_display]',
					array(
						'type'     => 'checkbox',
						'label'    => __( 'Sticky Header Display', 'woostify-pro' ),
						'section'  => 'woostify_sticky_header',
						'settings' => 'woostify_pro_options[sticky_header_display]',
					)
				)
			);

			// Disable on 404, Search and Archive.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_disable_archive]',
				array(
					'default'           => $defaults['sticky_header_disable_archive'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_disable_archive]',
					array(
						'label'    => __( 'Disable on 404, Search & Archives', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_disable_archive]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'checkbox',
					)
				)
			);

			// Disable on Index.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_disable_index]',
				array(
					'default'           => $defaults['sticky_header_disable_index'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_disable_index]',
					array(
						'label'    => __( 'Disable on Blog page', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_disable_index]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'checkbox',
					)
				)
			);

			// Disable on Pages.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_disable_page]',
				array(
					'default'           => $defaults['sticky_header_disable_page'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_disable_page]',
					array(
						'label'    => __( 'Disable on Pages', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_disable_page]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'checkbox',
					)
				)
			);

			// Disable on Posts.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_disable_post]',
				array(
					'default'           => $defaults['sticky_header_disable_post'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_disable_post]',
					array(
						'label'    => __( 'Disable on Posts', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_disable_post]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'checkbox',
					)
				)
			);

			// Disable on Shop page.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_disable_shop]',
				array(
					'default'           => $defaults['sticky_header_disable_shop'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_checkbox',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_disable_shop]',
					array(
						'label'    => __( 'Disable on Shop page', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_disable_shop]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'checkbox',
					)
				)
			);

			// Background color divider.
			$wp_customize->add_setting(
				'sticky_header_background_color_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'sticky_header_background_color_divider',
					array(
						'section'  => 'woostify_sticky_header',
						'settings' => 'sticky_header_background_color_divider',
						'type'     => 'divider',
					)
				)
			);

			// Background color.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_background_color]',
				array(
					'default'           => $defaults['sticky_header_background_color'],
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'type'              => 'option',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_background_color]',
					array(
						'label'    => __( 'Background Color', 'woostify-pro' ),
						'section'  => 'woostify_sticky_header',
						'settings' => 'woostify_pro_options[sticky_header_background_color]',
					)
				)
			);

			// Enable on devices.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_enable_on]',
				array(
					'default'           => $defaults['sticky_header_enable_on'],
					'type'              => 'option',
					'sanitize_callback' => 'woostify_sanitize_choices',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_enable_on]',
					array(
						'label'    => __( 'Enable On', 'woostify-pro' ),
						'settings' => 'woostify_pro_options[sticky_header_enable_on]',
						'section'  => 'woostify_sticky_header',
						'type'     => 'select',
						'choices'  => array(
							'desktop'     => __( 'Desktop', 'woostify-pro' ),
							'mobile'      => __( 'Mobile', 'woostify-pro' ),
							'all-devices' => __( 'Desktop + Mobile', 'woostify-pro' ),
						),
					)
				)
			);

			// Border divider.
			$wp_customize->add_setting(
				'sticky_header_border_divider',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				new Woostify_Divider_Control(
					$wp_customize,
					'sticky_header_border_divider',
					array(
						'section'  => 'woostify_sticky_header',
						'settings' => 'sticky_header_border_divider',
						'type'     => 'divider',
					)
				)
			);

			// Border width.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_border_width]',
				array(
					'default'           => $defaults['sticky_header_border_width'],
					'sanitize_callback' => 'absint',
					'type'              => 'option',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new Woostify_Range_Slider_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_border_width]',
					array(
						'label'    => __( 'Bottom Border Width', 'woostify-pro' ),
						'section'  => 'woostify_sticky_header',
						'settings' => array(
							'desktop' => 'woostify_pro_options[sticky_header_border_width]',
						),
						'choices' => array(
							'desktop' => array(
								'min'  => apply_filters( 'woostify_sticky_header_border_width_min_step', 0 ),
								'max'  => apply_filters( 'woostify_sticky_header_border_width_max_step', 20 ),
								'step' => 1,
								'edit' => true,
								'unit' => 'px',
							),
						),
					)
				)
			);

			// Border color.
			$wp_customize->add_setting(
				'woostify_pro_options[sticky_header_border_color]',
				array(
					'default'           => $defaults['sticky_header_border_color'],
					'sanitize_callback' => 'woostify_sanitize_rgba_color',
					'type'              => 'option',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new Woostify_Color_Control(
					$wp_customize,
					'woostify_pro_options[sticky_header_border_color]',
					array(
						'label'    => __( 'Border Color', 'woostify-pro' ),
						'section'  => 'woostify_sticky_header',
						'settings' => 'woostify_pro_options[sticky_header_border_color]',
					)
				)
			);
		}
	}

	Woostify_Sticky_Header::get_instance();
endif;
