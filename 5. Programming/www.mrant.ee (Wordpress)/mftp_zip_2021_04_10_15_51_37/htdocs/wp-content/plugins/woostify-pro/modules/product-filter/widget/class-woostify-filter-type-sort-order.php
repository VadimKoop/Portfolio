<?php
/**
 * Elementor filter by sort order widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Class widget.
 */
class Woostify_Filter_Type_Sort_Order extends Woostify_Filter_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-filter' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-filter-sort-order';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Filter Sort Order', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-filter';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'filter', 'store', 'sort', 'order' );
	}

	/**
	 * Get sort order
	 */
	public function get_sort_order() {
		return apply_filters(
			'woostify_filter_sort_order_option',
			array(
				'menu_order' => __( 'Default sorting', 'woostify-pro' ),
				'popularity' => __( 'Sort by popularity', 'woostify-pro' ),
				'rating'     => __( 'Sort by average rating', 'woostify-pro' ),
				'date'       => __( 'Sort by latest', 'woostify-pro' ),
				'price'      => __( 'Sort by price: low to high', 'woostify-pro' ),
				'price-desc' => __( 'Sort by price: high to low', 'woostify-pro' ),
			)
		);
	}

	/**
	 * Get filter type
	 */
	protected function get_filter() {
		$args = array(
			'post_type'      => 'product_filter',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array( // phpcs:ignore
				array(
					'key'   => 'woostify_product_filter_type',
					'value' => 'sort_order',
				),
			),
		);

		$filter = new \WP_Query( $args );
		if ( ! $filter->have_posts() ) {
			return array();
		}

		return wp_list_pluck( $filter->posts, 'post_title', 'ID' );
	}

	/**
	 * General
	 */
	protected function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'filter_type',
			array(
				'label'       => __( 'Select Filter', 'woostify-pro' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_filter(),
				'default'     => array(),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'sort_title',
			array(
				'label'       => __( 'Title', 'woostify-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Default sorting', 'woostify-pro' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'sort_item',
			array(
				'label'   => __( 'Menu Item', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'menu_order',
				'options' => $this->get_sort_order(),
			)
		);

		$this->add_control(
			'sort_order',
			array(
				'label'       => __( 'Select Sort Order', 'woostify-pro' ),
				'title_field' => '{{{ sort_title }}}',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'sort_title' => __( 'Default sorting', 'woostify-pro' ),
						'sort_item'  => 'menu_order',
					),
					array(
						'sort_title' => __( 'Sort by popularity', 'woostify-pro' ),
						'sort_item'  => 'popularity',
					),
					array(
						'sort_title' => __( 'Sort by average rating', 'woostify-pro' ),
						'sort_item'  => 'rating',
					),
					array(
						'sort_title' => __( 'Sort by latest', 'woostify-pro' ),
						'sort_item'  => 'date',
					),
					array(
						'sort_title' => __( 'Sort by price: low to high', 'woostify-pro' ),
						'sort_item'  => 'price',
					),
					array(
						'sort_title' => __( 'Sort by price: high to low', 'woostify-pro' ),
						'sort_item'  => 'price-desc',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->general();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$no_posts = '<span class="woocommerce-info">' . esc_html__( 'No thing found!', 'woostify-pro' ) . '</span>';
		if ( empty( $settings['filter_type'] ) || empty( $settings['sort_order'] ) ) {
			echo wp_kses_post( $no_posts );
			return;
		}
		?>

		<div class="w-product-filter w-product-filter-type-sort-order" data-type="sort-order">
			<select name="" class="w-product-filter-select-field">
				<?php $this->render_output( 'sort-order', $settings ); ?>
			</select>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Filter_Type_Sort_Order() );
