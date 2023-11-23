<?php
/**
 * Woostify Products Renderer
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * This class describes a woostify products renderer.
 */
class Woostify_Products_Renderer extends Woostify_Base_Products_Renderer {
	/**
	 * Settings
	 *
	 * @var $settings
	 */
	private $settings = array();

	/**
	 * Filter
	 *
	 * @var $is_added_product_filter
	 */
	private $is_added_product_filter = false;

	/**
	 * Constructs a new instance.
	 *
	 * @param      array  $settings The settings.
	 * @param      string $type     The type.
	 */
	public function __construct( $settings = array(), $type = 'products' ) {
		$this->settings   = $settings;
		$this->type       = $type;
		$this->attributes = $this->parse_attributes(
			array(
				'columns'  => $settings['col'],
				'paginate' => $settings['pro_pagi'],
				'cache'    => false,
			)
		);
		$this->query_args = $this->parse_query_args();
	}

	/**
	 * Override the original `get_query_results`
	 * with modifications that:
	 * 1. Remove `pre_get_posts` action if `is_added_product_filter`.
	 *
	 * @return bool|mixed|object
	 */
	protected function get_query_results() {
		$results = parent::get_query_results();

		// Start edit.
		if ( $this->is_added_product_filter ) {
			remove_action( 'pre_get_posts', array( wc()->query, 'product_query' ) );
		}
		// End edit.

		return $results;
	}

	/**
	 * Parse query
	 */
	protected function parse_query_args() {
		$settings = &$this->settings;

		$query_args = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'cache'       => false,
			'orderby'     => $settings['order_by'],
			'order'       => $settings['order'],
		);

		// Query latest product.
		if ( 'latest' === $settings['source'] ) {
			$query_args['orderby'] = 'date';
			$query_args['order']   = 'DESC';
		}

		$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore
		$query_args['tax_query']  = array(); // phpcs:ignore

		$front_page = is_front_page();
		if ( 'yes' === $settings['pro_pagi'] && 'yes' === $settings['ordering'] && ! $front_page ) {
			$ordering_args = WC()->query->get_catalog_ordering_args();
		} else {
			$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
		}

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order']   = $ordering_args['order'];
		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore
		}

		// Visibility.
		$this->set_visibility_query_args( $query_args );

		// Featured.
		$this->set_featured_query_args( $query_args );

		// Sale.
		$this->set_sale_products_query_args( $query_args );

		// IDs.
		$this->set_ids_query_args( $query_args );

		// Set specific types query args.
		if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
			$this->{"set_{$this->type}_query_args"}( $query_args );
		}

		// Exclude.
		$this->set_exclude_query_args( $query_args );

		// Remove default notices.
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );

		// Pagination.
		if ( 'yes' === $settings['pro_pagi'] ) {
			$page = isset( $_GET['product-page'] ) ? intval( wp_unslash( $_GET['product-page'] ) ) : 1; // phpcs:ignore

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination' );

			// Ordering.
			if ( 'yes' === $settings['ordering'] ) {
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			} else {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

			// Result count.
			if ( 'yes' === $settings['result_count'] ) {
				add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			} else {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}
		}

		// Products columns.
		add_filter( 'woostify_product_catalog_columns', array( $this, 'set_product_columns' ) );

		// Fallback to the widget's default settings in case settings was left empty.
		$columns                      = empty( $settings['col'] ) ? 4 : $settings['col'];
		$query_args['posts_per_page'] = $settings['count'];

		// Update orderby.
		if ( isset( $query_args['orderby'] ) && in_array( $query_args['orderby'], array( 'rating', 'popularity', 'price' ), true ) ) {
			switch ( $query_args['orderby'] ) {
				case 'rating':
					$query_args['meta_key'] = '_wc_average_rating'; // phpcs:ignore
					$query_args['order']    = 'DESC';
					break;
				case 'popularity':
					$query_args['meta_key'] = 'total_sales'; // phpcs:ignore
					$query_args['order']    = 'DESC';
					break;
				default:
					$query_args['meta_key'] = '_price'; // phpcs:ignore
					break;
			}

			$query_args['orderby'] = 'meta_value_num';
		}

		$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		return $query_args;
	}

	/**
	 * Product columns
	 *
	 * @param string $class The product class.
	 */
	public function set_product_columns( $class ) {
		$settings = &$this->settings;
		$classs[] = 'products';
		$classs[] = 'columns-' . $settings['col'];
		$classs[] = 'tablet-columns-' . $settings['col_tablet'];
		$classs[] = 'mobile-columns-' . $settings['col_mobile'];

		return esc_attr( implode( ' ', $classs ) );
	}


	/**
	 * Query by manual select.
	 *
	 * @param      array $query_args The query arguments.
	 */
	protected function set_ids_query_args( &$query_args ) {
		$settings = &$this->settings;

		if ( 'featured' === $settings['source'] ) {
			return;
		}

		switch ( $settings['source'] ) {
			case 'by_id':
				$post__in = $settings['product_ids'];
				break;
			case 'sale':
				$post__in = wc_get_product_ids_on_sale();
				break;
		}

		if ( ! empty( $post__in ) ) {
			$query_args['post__in'] = $post__in;
			remove_action( 'pre_get_posts', array( wc()->query, 'product_query' ) );
		}
	}

	/**
	 * Featured products.
	 *
	 * @param      array $query_args  The query arguments.
	 */
	protected function set_featured_query_args( &$query_args ) {
		$settings = &$this->settings;

		if ( 'featured' === $settings['source'] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => array( $product_visibility_term_ids['featured'] ),
			);
		}
	}

	/**
	 * Sale products.
	 *
	 * @param      array $query_args  The query arguments.
	 */
	protected function set_sale_products_query_args( &$query_args ) {
		$settings = &$this->settings;

		if ( 'sale' === $settings['source'] ) {
			parent::set_sale_products_query_args( $query_args );
		}
	}

	/**
	 * Exclude products
	 *
	 * @param      array $query_args  The query arguments.
	 */
	protected function set_exclude_query_args( &$query_args ) {
		$settings = &$this->settings;
		if ( 'current_query' === $settings['source'] ) {
			return;
		}

		$exclude_product = $settings['exclude_product_ids'];
		$include_cat     = $settings['product_cat_ids'];
		$exclude_cat     = $settings['exclude_cat_ids'];

		// Excludes products.
		if ( ! empty( $exclude_product ) ) {
			if ( empty( $query_args['post__in'] ) ) {
				$query_args['post__not_in'] = $exclude_product;
			} else {
				$query_args['post__in'] = array_diff( $query_args['post__in'], $exclude_product );
			}
		}

		// Include categories.
		if ( ! empty( $include_cat ) && 'by_id' === $settings['source'] ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $include_cat,
			);
		}

		// Excludes categories.
		if ( ! empty( $exclude_cat ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $exclude_cat,
				'operator' => 'NOT IN',
			);
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = array_merge( $query_args['tax_query'], $tax_query ); // phpcs:ignore
		}
	}
}
