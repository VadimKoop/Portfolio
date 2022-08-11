<?php
/**
 * Product Archive Widget
 *
 * @package Woostify Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woostify_Widget_Product_Archive' ) ) {
	/**
	 * Featured Product class
	 */
	class Woostify_Widget_Product_Archive extends WC_Widget {
		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce advanced-product-archive';
			$this->widget_description = __( 'A monthly archive of your site’s Product.', 'woostify-pro' );
			$this->widget_id          = 'advanced-product-archive';
			$this->widget_name        = __( 'Woostify Product Archive', 'woostify-pro' );
			$this->settings           = array(
				'title'              => array(
					'type'  => 'text',
					'std'   => __( 'Product Archive', 'woostify-pro' ),
					'label' => __( 'Title', 'woostify-pro' ),
				),
				'count'              => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => __( 'Show product counts', 'woostify-pro' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 * @param array $args     Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];

			$this->widget_start( $args, $instance );

			$product_archive_query = array(
				'post_type'       => 'product',
				'type'            => 'monthly',
				'echo'            => 0,
				'show_post_count' => $count,
			);
			?>
				<ul class="adv-product-archive">
					<?php echo wp_get_archives( $product_archive_query ); ?>
				</ul>
			<?php
			$this->widget_end( $args );
		}
	}
}

