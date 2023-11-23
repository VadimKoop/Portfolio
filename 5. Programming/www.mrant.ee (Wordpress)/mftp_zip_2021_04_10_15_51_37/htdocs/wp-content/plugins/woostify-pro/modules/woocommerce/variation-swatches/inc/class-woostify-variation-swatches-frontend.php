<?php
/**
 * Woostify Variation Swatches Front End
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Variation_Swatches_Frontend' ) ) {
	/**
	 * Class Woostify Variation Swatches Front End
	 */
	class Woostify_Variation_Swatches_Frontend {
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
		 * Class constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );

			add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'get_swatch_html' ), 101, 2 );
			add_filter( 'woostify_vartiation_swatch_html', array( $this, 'swatch_html' ), 5, 4 );

			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'swatch_list' ), 20 );
		}

		/**
		 * Enqueue scripts and stylesheets
		 */
		public function enqueue_scripts() {
			$options = Woostify_Variation_Swatches::get_instance()->get_options();

			// Style.
			$styles = '
				.swatch-tooltip {
					background-color: ' . $options['tooltip_bg'] . ';
					color: ' . $options['tooltip_color'] . ';
				}
				.swatch-tooltip:before {
					border-color: ' . $options['tooltip_bg'] . ' transparent transparent transparent;
				}
				.woostify-variation-swatches .swatch {
					min-width: ' . $options['size'] . 'px;
					min-height: ' . $options['size'] . 'px;
				}
				.swatch-list .swatch-image, .woostify-variation-swatches .swatch-image{
					width: ' . $options['size'] . 'px;
					height: ' . $options['size'] . 'px;
				}
			';
			wp_enqueue_style(
				'woostify-variation-swatches',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/variation-swatches/css/style.css',
				array(),
				WOOSTIFY_PRO_VERSION
			);
			wp_add_inline_style( 'woostify-variation-swatches', $styles );

			wp_enqueue_script(
				'woostify-variation-swatches',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/variation-swatches/js/script' . woostify_suffix() . '.js',
				array(),
				WOOSTIFY_PRO_VERSION,
				true
			);

			// Remove style and sript of tawcvs plugin.
			if ( defined( 'TAWC_VS_PLUGIN_FILE' ) ) {
				wp_dequeue_style( 'tawcvs-frontend' );
				wp_deregister_style( 'tawcvs-frontend' );
				wp_dequeue_script( 'tawcvs-frontend' );
				wp_deregister_script( 'tawcvs-frontend' );
			}
		}

		/**
		 * Filter function to add swatches bellow the default selector
		 *
		 * @param string       $html Html.
		 * @param array|object $args Args.
		 *
		 * @return string
		 */
		public function get_swatch_html( $html, $args ) {
			$settings     = Woostify_Variation_Swatches::get_instance()->get_options();
			$swatch_types = Woostify_Variation_Swatches::get_instance()->types;
			$attr         = Woostify_Variation_Swatches::get_instance()->get_tax_attribute( $args['attribute'] );

			// Return if this is normal attribute.
			if ( empty( $attr ) ) {
				return $html;
			}

			if ( ! array_key_exists( $attr->attribute_type, $swatch_types ) ) {
				return $html;
			}

			$options   = $args['options'];
			$product   = $args['product'];
			$attribute = $args['attribute'];
			$class     = "variation-selector variation-select-{$attr->attribute_type}";
			$swatches  = '';
			$quickview = '1' === $settings['quickview'] ? 'quickview-support' : '';

			if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}

			if ( array_key_exists( $attr->attribute_type, $swatch_types ) ) {
				if ( ! empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options, true ) ) {
							$swatches .= apply_filters( 'woostify_vartiation_swatch_html', '', $term, $attr->attribute_type, $args );
						}
					}
				}

				if ( ! empty( $swatches ) ) {
					$class .= ' hidden ' . esc_attr( $quickview );

					$swatches = '<div class="woostify-variation-swatches ' . esc_attr( $quickview ) . ' variation-' . $settings['style'] . '" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
					$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
				}
			}

			return $html;
		}

		/**
		 * Print HTML of a single swatch
		 *
		 * @param string       $html Html.
		 * @param array|object $term Term.
		 * @param string       $type Type.
		 * @param array|object $args Args.
		 *
		 * @return string
		 */
		public function swatch_html( $html, $term, $type, $args ) {
			$options  = Woostify_Variation_Swatches::get_instance()->get_options();
			$selected = sanitize_title( $args['selected'] ) === $term->slug ? 'selected' : '';
			$name     = apply_filters( 'woocommerce_variation_option_name', $term->name );
			$tooltip  = $options['tooltip'] ? '<span class="swatch-tooltip">' . esc_html( $name ) . '</span>' : '';

			switch ( $type ) {
				case 'color':
					$color = get_term_meta( $term->term_id, 'color', true );

					list( $r, $g, $b ) = sscanf( $color, '#%02x%02x%02x' );

					$html = sprintf(
						'<span class="swatch swatch-color swatch-%s %s" style="background-color: %s;" data-value="%s">%s</span>',
						esc_attr( $term->slug ),
						$selected,
						esc_attr( $color ),
						esc_attr( $term->slug ),
						$tooltip
					);
					break;

				case 'image':
					$image = get_term_meta( $term->term_id, 'image', true );
					$image = $image ? wp_get_attachment_image_src( $image ) : '';
					$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
					$html  = sprintf(
						'<span class="swatch swatch-image swatch-%s %s" data-value="%s"><img src="%s" alt="%s">%s</span>',
						esc_attr( $term->slug ),
						$selected,
						esc_attr( $term->slug ),
						esc_url( $image ),
						esc_attr( $name ),
						$tooltip
					);
					break;

				case 'label':
					$label = get_term_meta( $term->term_id, 'label', true );
					$label = $label ? $label : $name;
					$html  = sprintf(
						'<span class="swatch swatch-label swatch-%s %s" data-value="%s">%s %s</span>',
						esc_attr( $term->slug ),
						$selected,
						esc_attr( $term->slug ),
						esc_html( $label ),
						$tooltip
					);
					break;
			}

			return $html;
		}


		/**
		 * Swatch list on Archive Product
		 */
		public function swatch_list() {
			$options = Woostify_Variation_Swatches::get_instance()->get_options();
			if ( '0' === $options['shop_page'] ) {
				return;
			}

			global $product;

			$pid          = $product->get_id();
			$output       = '';
			$color_output = '';
			$image_output = '';
			$label_output = '';

			if ( ! $pid || ! $product->is_type( 'variable' ) ) {
				return $output;
			}

			$settings     = Woostify_Variation_Swatches::get_instance()->get_options();
			$default_attr = method_exists( $product, 'get_default_attributes' ) ? $product->get_default_attributes() : array();
			$vars         = $product->get_available_variations();
			$attributes   = $product->get_attributes();

			if ( ! $attributes ) {
				return $output;
			}

			foreach ( $attributes as $key ) {
				// Swatch type, ex: 'pa_size', 'pa_color', 'pa_image'.
				$attr_name = $key['name'];
				$terms     = wc_get_product_terms( $pid, $attr_name, array( 'fields' => 'all' ) );

				// Get type of product attribute by ID.
				$attr_type = wc_get_attribute( $key['id'] );

				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					return $output;
				}

				$id_slug = array();
				$id_name = array();

				foreach ( $terms as $val ) {
					$id_slug[ $val->term_id ] = $val->slug;
					$id_name[ $val->name ]    = $val->slug;
				}

				$color     = '';
				$img_id    = '';
				$label     = '';
				$empty_arr = array();

				foreach ( $vars as $key ) {
					$slug = isset( $key['attributes'][ 'attribute_' . $attr_name ] ) ? $key['attributes'][ 'attribute_' . $attr_name ] : '';

					if ( ! in_array( $slug, $empty_arr, true ) ) {
						array_push( $empty_arr, $slug );
					} else {
						continue;
					}

					if ( empty( $slug ) ) {
						continue;
					}

					$_id     = array_search( $slug, $id_slug, true );
					$name    = array_search( $slug, $id_name, true );
					$src     = wp_get_attachment_image_src( $key['image_id'], 'woocommerce_thumbnail' );
					$_class  = ( isset( $default_attr[ $attr_name ] ) && $slug === $default_attr[ $attr_name ] ) ? 'selected swatch-' . $slug : 'swatch-' . $slug;
					$tooltip = '1' === $settings['tooltip'] ? '<span class="swatch-tooltip">' . esc_attr( $name ) . '</span>' : '';

					switch ( $attr_type->type ) {
						case 'color':
							$color         = get_term_meta( $_id, 'color', true );
							$color_output .= '<span class="swatch swatch-color ' . esc_attr( $_class ) . '" data-src="' . esc_attr( $src[0] ) . '" style="background-color: ' . esc_attr( $color ) . '">' . $tooltip . '</span>';
							break;

						case 'image':
							$img_id        = get_term_meta( $_id, 'image', true );
							$img_alt       = woostify_image_alt( $img_id, esc_attr__( 'Swatch image', 'woostify-pro' ) );
							$image_output .= '<span class="swatch swatch-image ' . esc_attr( $_class ) . '" data-src="' . esc_attr( $src[0] ) . '"><img src="' . wp_get_attachment_url( $img_id ) . '" alt="' . esc_attr( $img_alt ) . '">' . $tooltip . '</span>';
							break;

						case 'label':
							$label         = get_term_meta( $_id, 'label', true );
							$label_output .= '<span class="swatch swatch-label ' . esc_attr( $_class ) . '" data-src="' . esc_attr( $src[0] ) . '">' . esc_html( $label ) . $tooltip . '</span>';
							break;
					}
				}
			}

			if ( ! empty( $color_output ) ) {
				$output .= $color_output;
			} elseif ( ! empty( $image_output ) ) {
				$output .= $image_output;
			} else {
				$output .= $label_output;
			}

			if ( ! $output ) {
				return;
			}
			?>
			<div class="swatch-list variation-<?php echo esc_attr( $settings['style'] ); ?>"><?php echo wp_kses_post( $output ); ?></div>
			<?php
		}
	}

	Woostify_Variation_Swatches_Frontend::get_instance();
}
