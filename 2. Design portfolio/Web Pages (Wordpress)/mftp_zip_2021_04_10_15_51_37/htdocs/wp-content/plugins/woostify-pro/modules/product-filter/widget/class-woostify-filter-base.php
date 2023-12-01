<?php
/**
 * Elementor Filter Base
 *
 * @package Woostify Pro
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
abstract class Woostify_Filter_Base extends Widget_Base {
	/**
	 * Scripts
	 */
	public function get_script_depends() {
		return array( 'woostify-product-filter' );
	}

	/**
	 * Get attribute
	 */
	protected function get_attr() {
		$attr     = array();
		$attr_tax = wc_get_attribute_taxonomies();

		if ( ! empty( $attr_tax ) ) {
			foreach ( $attr_tax as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attr[ $tax->attribute_name ] = $tax->attribute_label;
				}
			}
		}

		return $attr;
	}

	/**
	 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
	 * placed under a 'children' member of their parent term.
	 *
	 * @param Array   $cats      taxonomy term objects to sort.
	 * @param integer $parent_id the current parent ID to put them in.
	 */
	public function sort_terms( $cats, $parent_id = 0 ) {
		$into = array();

		foreach ( $cats as $cat ) {
			if ( $cat->parent === $parent_id ) {
				$cat->children = $this->sort_terms( $cats, $cat->term_id );

				$into[ $cat->term_id ] = $cat;
			}
		}

		return $into;
	}

	/**
	 * Render output
	 *
	 * @param string $type     The input type.
	 * @param object $settings The widget settings.
	 */
	protected function render_output( $type = 'radio', $settings = null ) {
		$no_posts  = '<span class="woocommerce-info">' . esc_html__( 'No thing found!', 'woostify-pro' ) . '</span>';
		$filter_id = $settings['filter_type'];
		if ( empty( $filter_id ) ) {
			echo wp_kses_post( $no_posts );
			return;
		}

		$args   = array( 'hide_empty' => true );
		$source = get_post_meta( $filter_id, 'woostify_product_filter_data', true );

		switch ( $source ) {
			case 'product_cat':
				$exclude_ids = get_post_meta( $filter_id, 'woostify_product_filter_product_cat_exclude_ids', true );

				// Exclude ids.
				if ( ! empty( $exclude_ids ) ) {
					$args['exclude'] = explode( ',', $exclude_ids );
				}

				$terms = get_terms( 'product_cat', $args );
				break;
			case 'product_tag':
				$terms = get_terms( 'product_tag', $args );
				break;
			default:
				if ( is_numeric( $source ) ) {
					$attr = wc_get_attribute( $source );

					if ( is_object( $attr ) && ! is_wp_error( $attr ) ) {
						$terms = get_terms( $attr->slug, $args );
					}
				}
				break;
		}

		switch ( $type ) {
			case 'checkbox':
			case 'radio':
				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					echo wp_kses_post( $no_posts );
					return;
				}

				$class = 'w-filter-item-wrap';
				if ( 'product_cat' === $source ) {
					$class          = 'w-filter-item-cat';
					$terms          = $this->sort_terms( $terms );
					$include_child  = get_post_meta( $filter_id, 'woostify_product_filter_product_cat_include_child', true );
					$expand_default = get_post_meta( $filter_id, 'woostify_product_filter_product_cat_expand', true );
					$toggle         = $expand_default ? '<span class="toggle-child-cat" data-toggle="collapse">－</span>' : '<span class="toggle-child-cat" data-toggle="expand">＋</span>';
				}

				foreach ( $terms as $f1 ) {
					$f1_item_id = $f1->term_id;
					?>
					<div class="<?php echo esc_attr( empty( $f1->children ) ? $class : "$class has-children" ); ?>">
						<label class="w-filter-item" for="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f1_item_id ); ?>" data-id="<?php echo esc_attr( $f1_item_id ); ?>" data-slug="<?php echo esc_attr( $f1->slug ); ?>">
							<input class="w-filter-item-input" id="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f1_item_id ); ?>" type="<?php echo esc_attr( $type ); ?>" name="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $source ); ?>">
							<span class="w-filter-item-name"><?php echo esc_html( $f1->name ); ?></span>
							<span class="w-filter-item-count"><?php echo esc_html( $f1->count ); ?></span>
						</label>

						<?php if ( 'product_cat' === $source && $include_child && ! empty( $f1->children ) ) { ?>
							<?php echo wp_kses_post( $toggle ); ?>
							<div class="w-filter-item-inner<?php echo esc_attr( $expand_default ? ' active' : '' ); ?>">
								<?php
								foreach ( $f1->children as $f2 ) {
									$f2_item_id = $f2->term_id;
									?>
									<div class="<?php echo esc_attr( empty( $f2->children ) ? $class : "$class has-children" ); ?>">
										<label class="w-filter-item" for="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f2_item_id ); ?>" data-id="<?php echo esc_attr( $f2_item_id ); ?>" data-slug="<?php echo esc_attr( $f2->slug ); ?>">
											<input class="w-filter-item-input" id="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f2_item_id ); ?>" type="<?php echo esc_attr( $type ); ?>" name="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $source ); ?>">
											<span class="w-filter-item-name"><?php echo esc_html( $f2->name ); ?></span>
											<span class="w-filter-item-count"><?php echo esc_html( $f2->count ); ?></span>
										</label>

										<?php if ( ! empty( $f2->children ) ) { ?>
											<?php echo wp_kses_post( $toggle ); ?>
											<div class="w-filter-item-inner<?php echo esc_attr( $expand_default ? ' active' : '' ); ?>">
												<?php
												foreach ( $f2->children as $f3 ) {
													$f3_item_id = $f3->term_id;
													?>
													<div class="<?php echo esc_attr( empty( $f3->children ) ? $class : "$class has-children" ); ?>">
														<label class="w-filter-item" for="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f3_item_id ); ?>" data-id="<?php echo esc_attr( $f3_item_id ); ?>" data-slug="<?php echo esc_attr( $f3->slug ); ?>">
															<input class="w-filter-item-input" id="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f3_item_id ); ?>" type="<?php echo esc_attr( $type ); ?>" name="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $source ); ?>">
															<span class="w-filter-item-name"><?php echo esc_html( $f3->name ); ?></span>
															<span class="w-filter-item-count"><?php echo esc_html( $f3->count ); ?></span>
														</label>

														<?php if ( ! empty( $f3->children ) ) { ?>
															<?php echo wp_kses_post( $toggle ); ?>
															<div class="w-filter-item-inner<?php echo esc_attr( $expand_default ? ' active' : '' ); ?>">
																<?php
																foreach ( $f3->children as $f4 ) {
																	$f4_item_id = $f4->term_id;
																	?>
																	<div class="<?php echo esc_attr( $class ); ?>">
																		<label class="w-filter-item" for="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f4_item_id ); ?>" data-id="<?php echo esc_attr( $f4_item_id ); ?>" data-slug="<?php echo esc_attr( $f4->slug ); ?>">
																			<input class="w-filter-item-input" id="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $f4_item_id ); ?>" type="<?php echo esc_attr( $type ); ?>" name="w-filter-<?php echo esc_attr( $type ); ?>-<?php echo esc_attr( $source ); ?>">
																			<span class="w-filter-item-name"><?php echo esc_html( $f4->name ); ?></span>
																			<span class="w-filter-item-count"><?php echo esc_html( $f4->count ); ?></span>
																		</label>
																	</div>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
					<?php
				}
				break;
			case 'select':
				if ( empty( $terms ) || is_wp_error( $terms ) ) {
					echo wp_kses_post( $no_posts );
					return;
				}

				foreach ( $terms as $select ) {
					?>
					<option value="<?php echo esc_attr( $select->term_id ); ?>"><?php echo esc_html( $select->name ); ?></option>
					<?php
				}
				break;
			case 'range-slider':
				$slider_from  = get_post_meta( $filter_id, 'woostify_product_filter_range_slider_from', true );
				$slider_to    = get_post_meta( $filter_id, 'woostify_product_filter_range_slider_to', true );
				$slider_query = get_post_meta( $filter_id, 'woostify_product_filter_range_slider_query', true );
				?>
				<div
					class="w-product-filter w-product-filter-type-range-slider"
					data-type="range-slider"
					data-from="<?php echo esc_attr( $slider_from ); ?>"
					data-to="<?php echo esc_attr( $slider_to ); ?>"
					data-source="<?php echo esc_attr( $slider_query ); ?>"></div>
				<?php
				break;
			case 'check-range':
				$rang_min = get_post_meta( $filter_id, 'woostify_product_filter_check_range_min', true );
				$rang_max = get_post_meta( $filter_id, 'woostify_product_filter_check_range_max', true );
				if ( empty( $rang_min ) || empty( $rang_max ) ) {
					break;
				}

				$id = uniqid( 'check-range-' );

				foreach ( $rang_min as $k => $v ) {
					$value = sprintf( '[%s,%s]', $v, $rang_max[ $k ] );
					?>
					<label class="w-filter-item" for="<?php echo esc_attr( $id . $k ); ?>" data-value="<?php echo esc_attr( $value ); ?>">
						<input type="checkbox" id="<?php echo esc_attr( $id . $k ); ?>">
						<span class="w-filter-check-range-inner">
							<span class="w-filter-check-range-value"><?php echo wp_kses( wc_price( $v ), array() ); ?></span>
							<span class="w-filter-separator">-</span>
							<span class="w-filter-check-range-value"><?php echo wp_kses( wc_price( $rang_max[ $k ] ), array() ); ?></span>
						</span>
					</label>
					<?php
				}

				break;
			case 'date-range':
				?>
				<input class="w-filter-date-picker" data-from type="text" placeholder="<?php esc_attr_e( 'From', 'woostify-pro' ); ?>" readonly>
				<input class="w-filter-date-picker" data-to type="text" placeholder="<?php esc_attr_e( 'To', 'woostify-pro' ); ?>" readonly>
				<button class="w-filter-item-submit" type="button"><?php esc_html_e( 'Search', 'woostify-pro' ); ?></button>
				<?php
				break;
			case 'rating':
				?>
				<span class="w-filter-rating-star"></span>
				<span class="w-filter-rating-star"></span>
				<span class="w-filter-rating-star"></span>
				<span class="w-filter-rating-star"></span>
				<span class="w-filter-rating-star"></span>
				<?php
				break;
			case 'search':
				?>
				<input type="text" placeholder="<?php esc_attr_e( 'Type to search...', 'woostify-pro' ); ?>" class="w-product-filter-text-field">
				<?php
				break;
			case 'sort-order':
				foreach ( $settings['sort_order'] as $v ) {
					?>
					<option value="<?php echo esc_attr( $v['sort_item'] ); ?>"><?php echo esc_html( $v['sort_title'] ); ?></option>
					<?php
				}
				break;
		}
	}
}
