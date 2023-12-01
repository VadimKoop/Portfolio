<?php
/**
 * Woostify Variation Swatches Admin
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Variation_Swatches_Admin' ) ) {

	/**
	 * Class Woostify Variation Swatches Admin
	 */
	class Woostify_Variation_Swatches_Admin {
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
			add_action( 'admin_init', array( $this, 'includes' ) );
			add_action( 'admin_init', array( $this, 'init_attribute_hooks' ) );
			add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

			// Display attribute fields.
			add_action( 'woostify_product_attribute_field', array( $this, 'attribute_fields' ), 10, 3 );
		}

		/**
		 * Include any classes we need within admin.
		 */
		public function includes() {
			require_once WOOSTIFY_PRO_MODULES_PATH . 'woocommerce/variation-swatches/inc/class-woostify-variation-swatches-admin-product.php';
		}

		/**
		 * Init hooks for adding fields to attribute screen
		 * Save new term meta
		 * Add thumbnail column for attribute term
		 */
		public function init_attribute_hooks() {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( empty( $attribute_taxonomies ) ) {
				return;
			}

			foreach ( $attribute_taxonomies as $tax ) {
				add_action( 'pa_' . $tax->attribute_name . '_add_form_fields', array( $this, 'add_attribute_fields' ) );
				add_action( 'pa_' . $tax->attribute_name . '_edit_form_fields', array( $this, 'edit_attribute_fields' ), 10, 2 );

				add_filter( 'manage_edit-pa_' . $tax->attribute_name . '_columns', array( $this, 'add_attribute_columns' ) );
				add_filter( 'manage_pa_' . $tax->attribute_name . '_custom_column', array( $this, 'add_attribute_column_content' ), 10, 3 );
			}

			add_action( 'created_term', array( $this, 'save_term_meta' ), 10, 2 );
			add_action( 'edit_term', array( $this, 'save_term_meta' ), 10, 2 );
		}

		/**
		 * Load stylesheet and scripts in edit product attribute screen
		 */
		public function enqueue_scripts() {
			// For product edit.
			$screen = get_current_screen();
			if (
				! $screen ||
				( false === strpos( $screen->id, 'edit-pa_' ) && false === strpos( $screen->id, 'product' ) )
			) {
				return;
			}

			wp_enqueue_media();
			wp_enqueue_style(
				'woostify-variation-swatches-admin',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/variation-swatches/css/admin.css',
				array( 'wp-color-picker' ),
				WOOSTIFY_PRO_VERSION
			);

			wp_enqueue_script(
				'woostify-variation-swatches-admin',
				WOOSTIFY_PRO_MODULES_URI . 'woocommerce/variation-swatches/js/admin' . woostify_suffix() . '.js',
				array( 'jquery', 'wp-color-picker', 'wp-util' ),
				WOOSTIFY_PRO_VERSION,
				true
			);
			wp_localize_script(
				'woostify-variation-swatches-admin',
				'woostify_variation_swatches_admin',
				array(
					'i18n'        => array(
						'mediaTitle'  => esc_html__( 'Choose an image', 'woostify-pro' ),
						'mediaButton' => esc_html__( 'Use image', 'woostify-pro' ),
					),
					'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png',
				)
			);
		}

		/**
		 * Create hook to add fields to add attribute term screen
		 *
		 * @param string $taxonomy Taxonomy.
		 */
		public function add_attribute_fields( $taxonomy ) {
			$attr = Woostify_Variation_Swatches::get_instance()->get_tax_attribute( $taxonomy );

			do_action( 'woostify_product_attribute_field', $attr->attribute_type, '', 'add' );
		}

		/**
		 * Create hook to fields to edit attribute term screen
		 *
		 * @param object $term     Term.
		 * @param string $taxonomy Taxonomy.
		 */
		public function edit_attribute_fields( $term, $taxonomy ) {
			$attr  = Woostify_Variation_Swatches::get_instance()->get_tax_attribute( $taxonomy );
			$value = get_term_meta( $term->term_id, $attr->attribute_type, true );

			do_action( 'woostify_product_attribute_field', $attr->attribute_type, $value, 'edit' );
		}

		/**
		 * Print HTML of custom fields on attribute term screens
		 *
		 * @param string $type  Type.
		 * @param string $value Value.
		 * @param string $form  Form.
		 */
		public function attribute_fields( $type, $value, $form ) {
			// Return if this is a default attribute type.
			if ( in_array( $type, array( 'select', 'text' ), true ) ) {
				return;
			}

			// Print the open tag of field container.
			printf(
				'<%s class="form-field">%s<label for="term-%s">%s</label>%s',
				( 'edit' === $form ) ? 'tr' : 'div',
				wp_kses_post( 'edit' === $form ? '<th>' : '' ),
				esc_attr( $type ),
				esc_attr( Woostify_Variation_Swatches::get_instance()->types[ $type ] ),
				wp_kses_post( 'edit' === $form ? '</th><td>' : '' )
			);

			switch ( $type ) {
				case 'image':
					$image = $value ? wp_get_attachment_image_src( $value ) : '';
					$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
					?>
					<div class="woostify-variation-swatches-term-image-thumbnail" style="float:left;margin-right:10px;">
						<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" />
					</div>
					<div style="line-height: 60px;">
						<input type="hidden" class="woostify-variation-swatches-term-image" name="image" value="<?php echo esc_attr( $value ); ?>" />
						<button type="button" class="woostify-variation-swatches-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'woostify-pro' ); ?></button>
						<button type="button" class="woostify-variation-swatches-remove-image-button button <?php echo esc_attr( $value ? '' : 'hidden' ); ?>"><?php esc_html_e( 'Remove image', 'woostify-pro' ); ?></button>
					</div>
					<?php
					break;

				default:
					?>
					<input type="text" id="term-<?php echo esc_attr( $type ); ?>" name="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<?php
					break;
			}

			// Print the close tag of field container.
			echo 'edit' === $form ? '</td></tr>' : '</div>';
		}

		/**
		 * Save term meta
		 *
		 * @param int $term_id Term id.
		 * @param int $tt_id   Id.
		 */
		public function save_term_meta( $term_id, $tt_id ) {
			foreach ( Woostify_Variation_Swatches::get_instance()->types as $type => $label ) {
				if ( isset( $_POST[ $type ] ) ) { // phpcs:ignore
					update_term_meta( $term_id, $type, sanitize_text_field( wp_unslash( $_POST[ $type ] ) ) ); // phpcs:ignore
				}
			}
		}

		/**
		 * Add thumbnail column to column list
		 *
		 * @param array $columns Columns.
		 *
		 * @return array
		 */
		public function add_attribute_columns( $columns ) {
			$new_columns          = array();
			$new_columns['cb']    = $columns['cb'];
			$new_columns['thumb'] = '';
			unset( $columns['cb'] );

			return array_merge( $new_columns, $columns );
		}

		/**
		 * Render thumbnail HTML depend on attribute type
		 *
		 * @param array $columns Columns.
		 * @param int   $column  Column.
		 * @param int   $term_id Term id.
		 */
		public function add_attribute_column_content( $columns, $column, $term_id ) {
			$tax   = isset( $_REQUEST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['taxonomy'] ) ) : ''; // phpcs:ignore
			$attr  = Woostify_Variation_Swatches::get_instance()->get_tax_attribute( $tax );
			$value = get_term_meta( $term_id, $attr->attribute_type, true );

			switch ( $attr->attribute_type ) {
				case 'color':
					printf( '<div class="woostify-swatch-preview swatch-color" style="background-color:%s;"></div>', esc_attr( $value ) );
					break;

				case 'image':
					$image = $value ? wp_get_attachment_image_src( $value ) : '';
					$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
					printf( '<img class="woostify-swatch-preview swatch-image" src="%s" width="44px" height="44px">', esc_url( $image ) );
					break;

				case 'label':
					printf( '<div class="woostify-swatch-preview swatch-label">%s</div>', esc_html( $value ) );
					break;
			}
		}
	}

	Woostify_Variation_Swatches_Admin::get_instance();
}
