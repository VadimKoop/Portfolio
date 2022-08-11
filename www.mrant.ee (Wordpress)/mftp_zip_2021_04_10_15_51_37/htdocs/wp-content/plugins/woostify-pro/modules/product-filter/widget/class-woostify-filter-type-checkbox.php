<?php
/**
 * Elementor filter by checkbox widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Class widget.
 */
class Woostify_Filter_Type_Checkbox extends Woostify_Filter_Base {
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
		return 'woostify-filter-checkbox';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Filter Checkbox', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'product', 'filter', 'store', 'checkbox', 'list' );
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
					'value' => 'checkbox',
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
				'label'   => __( 'Select Filter', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => $this->get_filter(),
				'default' => array(),
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
		$type_id  = $settings['filter_type'];
		if ( empty( $type_id ) ) {
			echo wp_kses_post( $no_posts );
			return;
		}

		$data = get_post_meta( $type_id, 'woostify_product_filter_data', true );
		if ( is_numeric( $data ) ) {
			$attr = wc_get_attribute( $data );

			if ( is_object( $attr ) && ! is_wp_error( $attr ) ) {
				$data = $attr->slug;
			}
		}
		?>

		<div class="w-product-filter w-product-filter-type-checkbox" data-type="checkbox" data-source="<?php echo esc_attr( $data ); ?>">
			<?php $this->render_output( 'checkbox', $settings ); ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Filter_Type_Checkbox() );
