<?php
/**
 * Featured Product Widget
 *
 * @package Woostify Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woostify_Widget_Featured_Product' ) ) {
	/**
	 * Featured Product class
	 */
	class Woostify_Widget_Featured_Product extends WP_Widget {
		/**
		 * Setup
		 */
		public function __construct() {
			$options = array(
				'classname'   => 'advanced-featured-product',
				'description' => __( 'Display a slider featured products.', 'woostify-pro' ),
			);

			parent::__construct(
				'advanced-featured-product',
				__( 'Woostify Featured Products', 'woostify-pro' ),
				$options
			);
		}

		/**
		 * Form
		 *
		 * @param      array $instance The instance.
		 */
		public function form( $instance ) {
			$default = array(
				'title' => __( 'Featured Products', 'woostify-pro' ),
				'number'  => 3,
				'max'     => 9,
				'orderby' => 'DESC',
				'order'   => 'id',
			);

			$instance = wp_parse_args( (array) $instance, $default );

			$title   = $instance['title'];
			$number  = $instance['number'];
			$max     = $instance['max'];
			$orderby = $instance['orderby'];
			$order   = $instance['order'];
			?>

			<p>
				<label for='<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>'>
					<?php esc_html_e( 'Title:', 'woostify-pro' ); ?>
				</label>
				<input
					class="widefat"
					type='text'
					value='<?php echo esc_attr( $title ); ?>'
					name='<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>'
					id='<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>' />
			</p>

			<p>
				<label for='<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>'>
					<?php esc_html_e( 'Products per slide', 'woostify-pro' ); ?>
				</label>
				<input
					class="widefat"
					type="number"
					value="<?php echo esc_attr( $number ); ?>"
					name='<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>'
					id='<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>' />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>">
					<?php esc_html_e( 'Limit:', 'woostify-pro' ); ?>
				</label>

				<input
					type="number"
					name="<?php echo esc_attr( $this->get_field_name( 'max' ) ); ?>"
					value="<?php echo esc_attr( $instance['max'] ); ?>" class="widefat"
					id="<?php echo esc_attr( $this->get_field_id( 'max' ) ); ?>"/>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>">
					<?php esc_html_e( 'Orderby:', 'woostify-pro' ); ?>
				</label>
				<select
					class="widefat"
					name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" >
					<option <?php echo 'id' == $orderby ? 'selected' : ''; ?> value="id"><?php esc_html_e( 'ID', 'woostify-pro' ); ?></option>
					<option <?php echo 'title' == $orderby ? 'selected' : ''; ?> value="title"><?php esc_html_e( 'Title', 'woostify-pro' ); ?></option>
					<option <?php echo 'date' == $orderby ? 'selected' : ''; ?> value="date"><?php esc_html_e( 'Date', 'woostify-pro' ); ?></option>
					<option <?php echo 'modified' == $orderby ? 'selected' : ''; ?> value="modified"><?php esc_html_e( 'Modified', 'woostify-pro' ); ?></option>
					<option <?php echo 'author' == $orderby ? 'selected' : ''; ?> value="author"><?php esc_html_e( 'Author', 'woostify-pro' ); ?></option>
					<option <?php echo 'rand' == $orderby ? 'selected' : ''; ?> value="rand"><?php esc_html_e( 'Random', 'woostify-pro' ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
					<?php esc_html_e( 'Order:', 'woostify-pro' ); ?>
				</label>
				<select
					class="widefat"
					name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
					id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" >
					<option <?php echo 'DESC' == $order ? 'selected' : ''; ?> value="DESC"><?php esc_html_e( 'DESC', 'woostify-pro' ); ?></option>
					<option <?php echo 'ASC' == $order ? 'selected' : ''; ?> value="ASC"><?php esc_html_e( 'ASC', 'woostify-pro' ); ?></option>
				</select>
			</p>
			<?php
		}

		/**
		 * Update content
		 *
		 * @param      array $new_instance  The new instance.
		 * @param      array $old_instance  The old instance.
		 *
		 * @return     array  New instance
		 */
		public function update( $new_instance, $old_instance ) {

			parent::update( $new_instance, $old_instance );

			$instance = $old_instance;

			$instance['title']   = strip_tags( $new_instance['title'] );
			$instance['number']  = strip_tags( $new_instance['number'] );
			$instance['max']     = strip_tags( $new_instance['max'] );
			$instance['orderby'] = strip_tags( $new_instance['orderby'] );
			$instance['order']   = strip_tags( $new_instance['order'] );

			return $instance;
		}

		/**
		 * View widget on front end
		 *
		 * @param      array $args      The arguments.
		 * @param      array $instance  The instance.
		 */
		public function widget( $args, $instance ) {
			$title              = isset( $instance['title'] ) ? $instance['title'] : 'Featured Products';
			$products_per_slide = isset( $instance['number'] ) ? $instance['number'] : '3';
			$max_of_product     = isset( $instance['max'] ) ? $instance['max'] : '9';
			$orderby            = isset( $instance['orderby'] ) ? $instance['orderby'] : 'DESC';
			$order              = isset( $instance['order'] ) ? $instance['order'] : 'id';

			$tax_query = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);

			$featured_product_query = array(
				'post_type'           => 'product',
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
				'posts_per_page'      => $max_of_product,
				'orderby'             => $orderby,
				'order'               => $order,
				'tax_query'           => $tax_query,
			);

			$query = new \WP_Query( $featured_product_query );

			if ( ! $query->have_posts() ) {
				return;
			}

			// If total post > posts per slide.
			$condition = $query->post_count > intval( $products_per_slide );

			echo wp_kses_post( $args['before_widget'] );

			if ( $title ) {
				echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
			}

			if ( $condition ) {
				$adv_arrow_left  = apply_filters( 'woostify_pro_advanced_featured_product_arrow_left', 'ti-angle-left' );
				$adv_arrow_right = apply_filters( 'woostify_pro_advanced_featured_product_arrow_right', 'ti-angle-right' );
				?>
				<div class="adv-featured-product-arrow">
					<span class="prev-arrow <?php echo esc_attr( $adv_arrow_left ); ?>"></span>
					<span class="next-arrow <?php echo esc_attr( $adv_arrow_right ); ?>"></span>
				</div>
			<?php } ?>

			<div class="adv-featured-product <?php echo true == $condition ? 'adv-product-slider' : ''; ?>" data-items="<?php echo esc_attr( $products_per_slide ); ?>">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					global $product;
					$rate  = wc_get_rating_html( $product->get_average_rating() );
					$price = $product->get_price_html();
					?>

					<div class="adv-featured-product-item">
						<div class="fcp-image">
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ); ?>" alt="<?php esc_attr_e( 'Product Image', 'woostify-pro' ); ?>">
							</a>
						</div>

						<div class="fcp-content">
							<h2 class="fcp-title">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
							</h2>
							<span class="fcp-rate"><?php echo wp_kses_post( $rate ); ?></span>
							<span class="fcp-price price"><?php echo wp_kses_post( $price ); ?></span>
						</div>
					</div>
					<?php
				endwhile;

				wp_reset_postdata();
				wc_reset_loop();
				?>
			</div>
			<?php
			echo wp_kses_post( $args['after_widget'] );

			// Enqueue slick static.
			if ( $condition ) {
				wp_enqueue_script( 'woostify-featured-product' );
				wp_enqueue_style( 'slick' );
			}
		}
	}
}