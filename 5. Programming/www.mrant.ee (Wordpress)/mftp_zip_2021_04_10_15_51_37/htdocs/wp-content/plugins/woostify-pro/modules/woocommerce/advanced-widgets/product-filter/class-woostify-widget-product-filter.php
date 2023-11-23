<?php
/**
 * Product Filter Widget
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woostify_Widget_Product_Filter' ) ) {
	/**
	 * Class for woostify widget product filter.
	 */
	class Woostify_Widget_Product_Filter extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce advanced-product-filter';
			$this->widget_description = esc_html__( 'Display a list of attributes to filter products in your store.', 'woostify-pro' );
			$this->widget_id          = 'advanced-product-filter';
			$this->widget_name        = esc_html__( 'Woostify Product Filter', 'woostify-pro' );
			parent::__construct();
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see WP_Widget->update
		 *
		 * @param array $new_instance New Instance.
		 * @param array $old_instance Old Instance.
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$this->init_settings();
			return parent::update( $new_instance, $old_instance );
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see WP_Widget->form
		 *
		 * @param array $instance Instance.
		 */
		public function form( $instance ) {
			$this->init_settings();
			parent::form( $instance );
		}

		/**
		 * Init settings after post types are registered.
		 */
		public function init_settings() {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
					}
				}
			}

			$this->settings = array(
				'title'      => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter By', 'woostify-pro' ),
					'label' => esc_html__( 'Title', 'woostify-pro' ),
				),
				'attribute'  => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute', 'woostify-pro' ),
					'options' => $attribute_array,
				),
				'query_type' => array(
					'type'    => 'select',
					'std'     => 'and',
					'label'   => esc_html__( 'Query type', 'woostify-pro' ),
					'options' => array(
						'and' => esc_html__( 'AND', 'woostify-pro' ),
						'or'  => esc_html__( 'OR', 'woostify-pro' ),
					),
				),
			);
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args Arguments.
		 * @param array $instance Instance.
		 */
		public function widget( $args, $instance ) {
			if ( ! is_shop() && ! is_product_taxonomy() ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name':
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
					break;
				case 'id':
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
					break;
				case 'menu_order':
					$get_terms_args['menu_order'] = 'ASC';
					break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === count( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num':
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
					break;
				case 'parent':
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
					break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			$found = $this->layered_nav_list( $terms, $taxonomy, $query_type );

			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes.
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean(); // @codingStandardsIgnoreLine
			}
		}

		/**
		 * Return the currently viewed taxonomy name.
		 *
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 *
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 *
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}

		/**
		 * Count products within certain terms, taking the main WP query into consideration.
		 *
		 * This query allows counts to be generated based on the viewed products, not all products.
		 *
		 * @param  array  $term_ids Term IDs.
		 * @param  string $taxonomy Taxonomy.
		 * @param  string $query_type Query Type.
		 * @return array
		 */
		protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
			global $wpdb;

			$tax_query  = WC_Query::get_main_tax_query();
			$meta_query = WC_Query::get_main_meta_query();

			if ( 'or' === $query_type ) {
				foreach ( $tax_query as $key => $query ) {
					if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
						unset( $tax_query[ $key ] );
					}
				}
			}

			$meta_query     = new WP_Meta_Query( $meta_query );
			$tax_query      = new WP_Tax_Query( $tax_query );
			$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

			// Generate query.
			$query           = array();
			$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
			$query['from']   = "FROM {$wpdb->posts}";
			$query['join']   = "
				INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
				INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
				INNER JOIN {$wpdb->terms} AS terms USING( term_id )
				" . $tax_query_sql['join'] . $meta_query_sql['join'];

			$query['where'] = "
				WHERE {$wpdb->posts}.post_type IN ( 'product' )
				AND {$wpdb->posts}.post_status = 'publish'"
				. $tax_query_sql['where'] . $meta_query_sql['where'] .
				'AND terms.term_id IN (' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

			$search = WC_Query::get_main_search_query_sql();
			if ( $search ) {
				$query['where'] .= ' AND ' . $search;
			}

			$query['group_by'] = 'GROUP BY terms.term_id';
			$query             = apply_filters( 'woostify_pro_get_filtered_term_product_counts_query', $query );
			$query             = implode( ' ', $query );

			// We have a query - let's see if cached results of this query already exist.
			$query_hash = md5( $query );

			// Maybe store a transient of the count values.
			$cache = apply_filters( 'woostify_pro_layered_nav_count_maybe_cache', true );
			if ( true === $cache ) {
				$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
			} else {
				$cached_counts = array();
			}

			if ( ! isset( $cached_counts[ $query_hash ] ) ) {
				$results = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore
				$counts  = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );

				$cached_counts[ $query_hash ] = $counts;
				if ( true === $cache ) {
					set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
				}
			}

			return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
		}

		/**
		 * Show list based layered nav.
		 *
		 * @param  array  $terms Terms.
		 * @param  string $taxonomy Taxonomy.
		 * @param  string $query_type Query Type.
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type ) {
			// Attribute type: color || image || label.
			$source         = Woostify_Variation_Swatches::get_instance();
			$attribute_type = $source->get_tax_attribute( $taxonomy )->attribute_type;
			$options        = $source->get_options();
			$filter_name    = 'filter_' . $source->get_tax_attribute( $taxonomy )->attribute_name;

			// List display.
			echo '<ul class="adv-products-filter filter-by-' . esc_attr( $attribute_type ) . ' variation-' . esc_attr( $options['style'] ) . '">';

			$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			foreach ( $terms as $term ) {
				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values, true );
				$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Skip the term for the current archive.
				if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}

				// Only show options with count > 0.
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // @codingStandardsIgnoreLine
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter, true ) ) {
					$current_filter[] = $term->slug;
				}

				$link = remove_query_arg( $filter_name, $this->get_current_page_url() );

				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term.
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}

					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}
				}

				if ( ! empty( $current_filter ) ) {
					asort( $current_filter );
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL.
					if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
					$link = str_replace( '%2C', ',', $link );
				}

				// Term meta.
				$term_meta = get_term_meta( $term->term_id, $attribute_type, true );
				$output    = '';
				$image     = '';

				if ( 'color' === $attribute_type ) {
					$output = 'style="background-color: ' . esc_attr( $term_meta ) . '"';
				} elseif ( 'image' === $attribute_type ) {
					$image = $term_meta ? wp_get_attachment_image_src( $term_meta ) : '';
					$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
				}

				if ( $count > 0 || $option_is_set ) {
					$link      = esc_url( apply_filters( 'woostify_pro_layered_nav_link', $link, $term, $taxonomy ) );
					$term_html = '<a class="pf-link swatch" rel="nofollow" href="' . $link . '" ' . $output . '>';

					switch ( $attribute_type ) {
						case 'image':
							$term_html .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $term->name ) . '">';
							break;
						case 'label':
							$term_html .= '<span class="pf-label">' . esc_html( $term_meta ) . '</span>';
							break;
						case 'select':
							$term_html .= '<span class="pf-label">' . esc_html( $term->name ) . '</span>';
					}

					$term_html .= $options['tooltip'] ? '<span class="swatch-tooltip">' . esc_html( $term->name ) . '</span>' : '';

					$term_html .= '</a>';
				} else {
					$link      = false;
					$term_html = '<span>' . esc_html( $term->name ) . '</span>';
				}

				echo '<li class="pf-item ' . ( $option_is_set ? 'selected' : '' ) . '">';
				echo wp_kses_post( apply_filters( 'woostify_pro_layered_nav_term_html', $term_html, $term, $link, $count ) );
				echo '</li>';
			}

			echo '</ul>';

			return $found;
		}
	}
}
