<?php
/**
 * Current Products Renderer
 *
 * @package Woostify Pro
 */

namespace Elementor;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class
 */
class Woostify_Current_Query_Renderer extends Woostify_Base_Products_Renderer {
	/**
	 * Settings
	 *
	 * @var $settings
	 */
	private $settings = array();

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
		$query = $GLOBALS['wp_query'];

		$paginated = ! $query->get( 'no_found_rows' );

		// Check is_object to indicate it's called the first time.
		if ( ! empty( $query->posts ) && is_object( $query->posts[0] ) ) {
			$query->posts = array_map(
				function ( $post ) {
					return $post->ID;
				},
				$query->posts
			);
		}

		$results = (object) array(
			'ids'          => wp_parse_id_list( $query->posts ),
			'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
			'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
			'per_page'     => (int) $query->get( 'posts_per_page' ),
			'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
		);

		return $results;
	}

	/**
	 * Parse query
	 */
	protected function parse_query_args() {
		$settings = &$this->settings;

		if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
			$query_args = $GLOBALS['wp_query']->query_vars;
		}

		add_action(
			"woocommerce_shortcode_before_{$this->type}_loop",
			function () {
				wc_set_loop_prop( 'is_shortcode', false );
			}
		);

		if ( 'yes' === $settings['pro_pagi'] ) {
			$page = get_query_var( 'paged', 1 );

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			if ( 'yes' !== $settings['ordering'] ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

			if ( 'yes' !== $settings['result_count'] ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}
		}

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		// Products columns.
		add_filter( 'woostify_product_catalog_columns', array( $this, 'set_product_columns' ) );

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
}
