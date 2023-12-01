<?php
/**
 * Woocommerce Helper Class
 *
 * @package  Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Woocommerce_Helper' ) ) {
	/**
	 * Main Woostify Pro Class
	 */
	class Woostify_Woocommerce_Helper {

		/**
		 * Instance
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function init() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Woostify Pro Constructor.
		 */
		public function __construct() {
		}

		/**
		 * Markup selection for multi select
		 *
		 * @param string  $data        The selection data.
		 * @param bollean $is_category The category detect.
		 */
		public function render_selection( $data, $is_category = true ) {
			if ( 'default' === $data || ! $data ) {
				return;
			}

			$all = false !== strpos( $data, 'all' );

			ob_start();

			if ( $is_category ) {
				if ( $all ) {
					?>
						<span class="woostify-multi-select-id" data-id="all">
							<?php esc_html_e( 'All Categories', 'woostify-pro' ); ?>
							<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
						</span>
					<?php

					return;
				}

				$args = array(
					'include'    => explode( '|', $data ),
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
			} else {
				if ( $all ) {
					?>
						<span class="woostify-multi-select-id" data-id="all">
							<?php esc_html_e( 'All Products', 'woostify-pro' ); ?>
							<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i>
						</span>
					<?php

					return;
				}

				$args = array(
					'post_type'           => 'product',
					'posts_per_page'      => -1,
					'post__in'            => explode( '|', $data ),
					'ignore_sticky_posts' => 1,
					'orderby'             => 'rand',
				);

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) {
					while ( $products->have_posts() ) {
						$products->the_post();
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

			echo ob_get_clean(); // phpcs:ignore
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
			$all   = false !== strpos( $value, 'all' );

			ob_start();

			$args = array(
				'hide_empty' => true,
				'search'     => $value,
			);
			$cats = get_terms( 'product_cat', $args );

			if ( $all ) {
				?>
				<span class="woostify-multi-select-id" data-id="all">
					<?php esc_html_e( 'All Categoties', 'woostify-pro' ); ?>
				</span>
				<?php
			} elseif ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
				foreach ( $cats as $k ) {
					?>
					<span class="woostify-multi-select-id" data-id="<?php echo esc_attr( $k->term_id ); ?>">
						<?php echo esc_html( $k->name ); ?>
					</span>
					<?php
				}
			} else {
				?>
				<span class="no-posts-found woocommerce-info"><?php esc_html_e( 'No product category found', 'woostify-pro' ); ?></span>
				<?php
			}

			$res = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Exclude categories
		 */
		public function exclude_categories() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			check_ajax_referer( 'woostify-exclude-categories', 'security_nonce' );
			$value = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';

			ob_start();

			$args = array(
				'hide_empty' => true,
				'search'     => $value,
			);
			$cats = get_terms( 'product_cat', $args );

			if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
				foreach ( $cats as $k ) {
					?>
					<span class="woostify-multi-select-id" data-id="<?php echo esc_attr( $k->term_id ); ?>">
						<?php echo esc_html( $k->name ); ?>
					</span>
					<?php
				}
			} else {
				?>
				<span class="no-posts-found woocommerce-info"><?php esc_html_e( 'No product category found', 'woostify-pro' ); ?></span>
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
			$all   = false !== strpos( $value, 'all' );

			ob_start();

			$args = array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => -1,
				's'                   => $value,
			);

			$products = new WP_Query( $args );

			if ( $all ) {
				?>
				<span class="woostify-multi-select-id" data-id="all">
					<?php esc_html_e( 'All Products', 'woostify-pro' ); ?>
				</span>
				<?php
			} elseif ( $products->have_posts() ) {
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

			$res = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Exclude products
		 */
		public function exclude_products() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			check_ajax_referer( 'woostify-exclude-products', 'security_nonce' );
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

			$res = ob_get_clean();

			wp_send_json_success( $res );
		}

		/**
		 * Save options
		 */
		public function save_options() {
			if ( ! current_user_can( 'edit_theme_options' ) ) {
				wp_send_json_error();
			}

			$setting_id = isset( $_POST['setting_id'] ) ? sanitize_text_field( wp_unslash( $_POST['setting_id'] ) ) : '';
			$nonce      = 'woostify-' . $setting_id . '-setting-nonce';
			check_ajax_referer( $nonce, 'security_nonce' );

			$options = isset( $_POST['options'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['options'] ) ), true ) : array();

			if ( ! empty( $options ) ) {
				$array = array();

				foreach ( $options as $k => $v ) {
					$value = sanitize_textarea_field( wp_unslash( $v ) );

					if ( false !== strpos( $k, '[]' ) ) {
						array_push( $array, $value );

						// Get option name.
						$name = strstr( $k, '[', true ) . '[]';

						update_option( $name, implode( '@_sn', $array ) );
					} else {
						update_option( $k, $value );
					}
				}
			}

			wp_send_json_success();
		}
	}

	Woostify_Woocommerce_Helper::init();
}
