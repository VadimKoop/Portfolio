<?php
/**
 * Elementor Recently Viewed Products Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product recently viewed widget.
 */
class Woostify_Elementor_Product_Recently_Viewed_Widget extends Woostify_Elementor_Slider_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return [ 'woostify-theme' ];
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-product-recently-viewed';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Recently Viewed Products', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-history';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return [ 'woostify', 'woocommerce', 'shop', 'product', 'recently view', 'store' ];
	}

	/**
	 * Controls
	 */
	protected function _register_controls() {
		$this->general();
	}

	/**
	 * General
	 */
	private function general() {
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'col',
			[
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Columns', 'woostify-pro' ),
				'default' => 4,
				'options' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				],
			]
		);

		$this->add_control(
			'count',
			[
				'label'     => esc_html__( 'Total Products', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
			]
		);

		$this->add_control(
			'order_by',
			[
				'label'     => esc_html__( 'Order By', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'id',
				'options'   => [
					'id'   => esc_html__( 'ID', 'woostify-pro' ),
					'name' => esc_html__( 'Name', 'woostify-pro' ),
					'date' => esc_html__( 'Date', 'woostify-pro' ),
					'rand' => esc_html__( 'Random', 'woostify-pro' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'ASC',
				'options'   => [
					'ASC'   => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC'  => esc_html__( 'DESC', 'woostify-pro' ),
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		$cookies = isset( $_COOKIE['woostify_product_recently_viewed'] ) ? $_COOKIE['woostify_product_recently_viewed'] : false;
		if ( ! $cookies ) {
			return;
		}

		$settings  = $this->get_settings_for_display();
		$ids       = explode( '|', $cookies );
		$container = woostify_site_container();
		$args      = [
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['count'],
			'orderby'        => $settings['order_by'],
			'order'          => $settings['order'],
			'post__in'       => $ids,
		];

		$products_query = new \WP_Query( $args );
		if ( ! $products_query->have_posts() ) {
			?>
			<p class="text-center"><?php esc_html_e( 'No posts found!', 'woostify-pro' ); ?></p>
			<?php
			return;
		}

		?>

		<div class="woostify-products-recently-viewed-widget">
			<?php
			global $woocommerce_loop;
			$woocommerce_loop['columns'] = (int) $settings['col'];

			woocommerce_product_loop_start();

			while ( $products_query->have_posts() ) :
				$products_query->the_post();

				wc_get_template_part( 'content', 'product' );
			endwhile;

			woocommerce_product_loop_end();

			// Reset loop.
			woocommerce_reset_loop();
			wp_reset_postdata();
			?>
		</div>
		
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Recently_Viewed_Widget() );
