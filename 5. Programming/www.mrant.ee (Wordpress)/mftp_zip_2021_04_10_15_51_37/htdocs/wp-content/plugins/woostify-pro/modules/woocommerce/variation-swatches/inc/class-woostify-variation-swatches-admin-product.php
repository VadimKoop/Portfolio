<?php
/**
 * Woostify Variation Swatches Admin Product
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Variation_Swatches_Admin_Product' ) ) {
	/**
	 * Woostify Variation Swatches Admin Product
	 */
	class Woostify_Variation_Swatches_Admin_Product {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 10, 2 );

			add_action( 'wp_ajax_variation_swatches_add_new_attribute', array( $this, 'add_new_attribute_ajax' ) );
			add_action( 'admin_footer', array( $this, 'add_attribute_term_template' ) );
		}

		/**
		 * Add selector for extra attribute types
		 *
		 * @param string $taxonomy Taxonomy.
		 * @param int    $index    Index.
		 */
		public function product_option_terms( $taxonomy, $index ) {
			if ( ! array_key_exists( $taxonomy->attribute_type, Woostify_Variation_Swatches::get_instance()->types ) ) {
				return;
			}

			$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
			global $thepostid;

			$product_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $thepostid; // phpcs:ignore
			?>

			<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woostify-pro' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $index ); ?>][]">
				<?php
				$arr = array(
					'orderby'    => 'name',
					'hide_empty' => false,
				);

				$all_terms = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', $arr ) );
				if ( $all_terms ) {
					foreach ( $all_terms as $term ) {
						echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy_name, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					}
				}
				?>
			</select>
			<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'woostify-pro' ); ?></button>
			<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'woostify-pro' ); ?></button>
			<button class="button fr plus variation_swatches_add_new_attribute" data-type="<?php echo esc_attr( $taxonomy->attribute_type ); ?>"><?php esc_html_e( 'Add new', 'woostify-pro' ); ?></button>

			<?php
		}

		/**
		 * Ajax function handles adding new attribute term
		 */
		public function add_new_attribute_ajax() {
			$nonce    = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$taxonomy = isset( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';
			$tax      = isset( $_POST['tax'] ) ? sanitize_text_field( wp_unslash( $_POST['tax'] ) ) : '';
			$type     = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
			$name     = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$slug     = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
			$swatch   = isset( $_POST['swatch'] ) ? sanitize_text_field( wp_unslash( $_POST['swatch'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, '_woostify_variation_swatches_create_attribute' ) ) {
				wp_send_json_error( esc_html__( 'Wrong request', 'woostify-pro' ) );
			}

			if ( empty( $name ) || empty( $swatch ) || empty( $taxonomy ) || empty( $type ) ) {
				wp_send_json_error( esc_html__( 'Not enough data', 'woostify-pro' ) );
			}

			if ( ! taxonomy_exists( $taxonomy ) ) {
				wp_send_json_error( esc_html__( 'Taxonomy is not exists', 'woostify-pro' ) );
			}

			if ( term_exists( $name, $tax ) ) {
				wp_send_json_error( esc_html__( 'This term is exists', 'woostify-pro' ) );
			}

			$term = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );

			if ( is_wp_error( $term ) ) {
				wp_send_json_error( $term->get_error_message() );
			} else {
				$term = get_term_by( 'id', $term['term_id'], $taxonomy );
				update_term_meta( $term->term_id, $type, $swatch );
			}

			wp_send_json_success(
				array(
					'msg'  => esc_html__( 'Added successfully', 'woostify-pro' ),
					'id'   => $term->term_id,
					'slug' => $term->slug,
					'name' => $term->name,
				)
			);
		}

		/**
		 * Print HTML of modal at admin footer and add js templates
		 */
		public function add_attribute_term_template() {
			$screen = get_current_screen();

			if ( ! $screen || 'product' !== $screen->id ) {
				return;
			}
			?>

			<div id="woostify-variation-swatches-modal-container" class="woostify-variation-swatches-modal-container">
				<div class="woostify-variation-swatches-modal">
					<button type="button" class="button-link media-modal-close woostify-variation-swatches-modal-close">
						<span class="media-modal-icon"></span></button>
					<div class="woostify-variation-swatches-modal-header"><h2><?php esc_html_e( 'Add new term', 'woostify-pro' ); ?></h2></div>
					<div class="woostify-variation-swatches-modal-content">
						<p class="woostify-variation-swatches-term-name">
							<label>
								<?php esc_html_e( 'Name', 'woostify-pro' ); ?>
								<input type="text" class="widefat woostify-variation-swatches-input" name="name">
							</label>
						</p>
						<p class="woostify-variation-swatches-term-slug">
							<label>
								<?php esc_html_e( 'Slug', 'woostify-pro' ); ?>
								<input type="text" class="widefat woostify-variation-swatches-input" name="slug">
							</label>
						</p>
						<div class="woostify-variation-swatches-term-swatch">

						</div>
						<div class="hidden woostify-variation-swatches-term-tax"></div>

						<input type="hidden" class="woostify-variation-swatches-input" name="nonce" value="<?php echo esc_attr( wp_create_nonce( '_woostify_variation_swatches_create_attribute' ) ); ?>">
					</div>
					<div class="woostify-variation-swatches-modal-footer">
						<button class="button button-secondary woostify-variation-swatches-modal-close"><?php esc_html_e( 'Cancel', 'woostify-pro' ); ?></button>
						<button class="button button-primary woostify-variation-swatches-new-attribute-submit"><?php esc_html_e( 'Add New', 'woostify-pro' ); ?></button>
						<span class="message"></span>
						<span class="spinner"></span>
					</div>
				</div>
				<div class="woostify-variation-swatches-modal-backdrop media-modal-backdrop"></div>
			</div>

			<script type="text/template" id="tmpl-woostify-variation-swatches-input-color">

				<label><?php esc_html_e( 'Color', 'woostify-pro' ); ?></label><br>
				<input type="text" class="woostify-variation-swatches-input woostify-variation-swatches-input-color" name="swatch">

			</script>

			<script type="text/template" id="tmpl-woostify-variation-swatches-input-image">

				<label><?php esc_html_e( 'Image', 'woostify-pro' ); ?></label><br>
				<div class="woostify-variation-swatches-term-image-thumbnail" style="float:left;margin-right:10px;">
					<img src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/placeholder.png' ); ?>" width="60px" height="60px" />
				</div>
				<div style="line-height:60px;">
					<input type="hidden" class="woostify-variation-swatches-input woostify-variation-swatches-input-image woostify-variation-swatches-term-image" name="swatch" value="" />
					<button type="button" class="woostify-variation-swatches-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'woostify-pro' ); ?></button>
					<button type="button" class="woostify-variation-swatches-remove-image-button button hidden"><?php esc_html_e( 'Remove image', 'woostify-pro' ); ?></button>
				</div>

			</script>

			<script type="text/template" id="tmpl-woostify-variation-swatches-input-label">

				<label>
					<?php esc_html_e( 'Label', 'woostify-pro' ); ?>
					<input type="text" class="widefat woostify-variation-swatches-input woostify-variation-swatches-input-label" name="swatch">
				</label>

			</script>

			<script type="text/template" id="tmpl-woostify-variation-swatches-input-tax">

				<input type="hidden" class="woostify-variation-swatches-input" name="taxonomy" value="{{data.tax}}">
				<input type="hidden" class="woostify-variation-swatches-input" name="type" value="{{data.type}}">

			</script>
			<?php
		}
	}

	new Woostify_Variation_Swatches_Admin_Product();
}
