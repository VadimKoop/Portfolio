<?php
/**
 * Elementor Featured Product Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor featured widget.
 */
class Woostify_Elementor_Product_List_Widget extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-product-list';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Product List', 'woostify-pro' );
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return array( 'woostify-product-list' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->section_general();
		$this->query();
		$this->section_image();
		$this->section_style();
		$this->section_product();
		$this->section_arrows();
	}

	/**
	 * General
	 */
	private function section_general() {
		$this->start_controls_section(
			'product_general',
			array(
				'label' => esc_html__( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Featured Products', 'woostify-pro' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Query
	 */
	private function query() {
		$this->start_controls_section(
			'product_query',
			array(
				'label' => esc_html__( 'Query', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'by_id',
				'options' => array(
					'current_query' => esc_html__( 'Current Query', 'woostify-pro' ),
					'sale'          => esc_html__( 'Sale', 'woostify-pro' ),
					'featured'      => esc_html__( 'Featured', 'woostify-pro' ),
					'latest'        => esc_html__( 'Latest Products', 'woostify-pro' ),
					'by_id'         => esc_html__( 'Manual Selection', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'product_cat_ids',
			array(
				'label'     => esc_html__( 'Categories', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
				'condition' => array(
					'source' => 'by_id',
				),
			)
		);

		$this->add_control(
			'product_ids',
			array(
				'label'     => esc_html__( 'Products', 'woostify-pro' ),
				'type'      => 'autocomplete',
				'query'     => array(
					'type' => 'post_type',
					'name' => 'product',
				),
				'condition' => array(
					'source' => 'by_id',
				),
			)
		);

		$this->add_control(
			'exclude_cat_ids',
			array(
				'label'      => esc_html__( 'Exclude Categories', 'woostify-pro' ),
				'type'       => 'autocomplete',
				'query'      => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'current_query',
						),
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'latest',
						),
					),
				),
			)
		);

		$this->add_control(
			'exclude_product_ids',
			array(
				'label'      => esc_html__( 'Exclude Products', 'woostify-pro' ),
				'type'       => 'autocomplete',
				'query'      => array(
					'type' => 'post_type',
					'name' => 'product',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'current_query',
						),
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'latest',
						),
					),
				),
			)
		);

		$this->add_control(
			'product_slide',
			array(
				'label'   => __( 'Product Per Slide', 'woostify-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,

			)
		);

		$this->add_control(
			'count',
			array(
				'label'     => esc_html__( 'Total Products', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 100,
				'step'      => 1,
				'separator' => 'before',
				'condition' => array(
					'source!' => 'current_query',
				),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'      => esc_html__( 'Order By', 'woostify-pro' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'id',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'current_query',
						),
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'latest',
						),
					),
				),
				'options'    => array(
					'id'         => esc_html__( 'ID', 'woostify-pro' ),
					'title'      => esc_html__( 'Title', 'woostify-pro' ),
					'price'      => esc_html__( 'Price', 'woostify-pro' ),
					'rating'     => esc_html__( 'Rating', 'woostify-pro' ),
					'popularity' => esc_html__( 'Popularity', 'woostify-pro' ),
					'date'       => esc_html__( 'Date', 'woostify-pro' ),
					'menu_order' => esc_html__( 'Menu Order', 'woostify-pro' ),
					'rand'       => esc_html__( 'Random', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'      => esc_html__( 'Order', 'woostify-pro' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'ASC',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'current_query',
						),
						array(
							'name'     => 'source',
							'operator' => '!==',
							'value'    => 'latest',
						),
					),
				),
				'options'    => array(
					'ASC'  => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC' => esc_html__( 'DESC', 'woostify-pro' ),
				),
			)
		);

		// Autoplay.
		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Autoplay timeout.
		$this->add_control(
			'timeout',
			array(
				'label'     => __( 'Autoplay Timeout (ms)', 'woostify-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 500,
				'max'       => 50000,
				'step'      => 100,
				'default'   => 5000,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Image
	 */
	private function section_image() {
		$this->start_controls_section(
			'product_image',
			array(
				'label' => esc_html__( 'Image', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_position',
			array(
				'label'   => esc_html__( 'Image Position', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'woostify-pro' ),
					'right' => esc_html__( 'Right', 'woostify-pro' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style
	 */
	private function section_style() {
		$this->start_controls_section(
			'product_style',
			array(
				'label' => esc_html__( 'Title', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Color.
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-featured-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .product-featured-title',
			)
		);

		// Title Spacing.
		$this->add_control(
			'title_spacing',
			array(
				'label'     => __( 'Spacing', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-featured-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Product
	 */
	private function section_product() {
		$this->start_controls_section(
			'products_style',
			array(
				'label' => esc_html__( 'Products', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Column Gap.
		$this->add_control(
			'product_gap',
			array(
				'label'     => __( 'Gap', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-featured .adv-featured-product-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'product_title',
			array(
				'label'     => __( 'Title', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-product-featured .fcp-title',
			)
		);

		// Title space.
		$this->add_responsive_control(
			'space',
			array(
				'type'               => Controls_Manager::DIMENSIONS,
				'label'              => esc_html__( 'Space', 'woostify-pro' ),
				'size_units'         => array( 'px', 'em' ),
				'default'            => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '10',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'allowed_dimensions' => array(
					'top',
					'bottom',
				),
				'selectors'          => array(
					'{{WRAPPER}} .woostify-product-featured .fcp-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_title_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_title_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_style_title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-featured .fcp-title ' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_style_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_style_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-featured .fcp-title:hover a ' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Price.
		$this->add_control(
			'product_price',
			array(
				'label'     => __( 'Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Price.
		$this->add_control(
			'product_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-featured .price ins span, {{WRAPPER}} .woostify-product-featured .price > .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		// Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-product-featured .price ins span, {{WRAPPER}} .woostify-product-featured .price > .woocommerce-Price-amount',
			)
		);

		// Sale Price.
		$this->add_control(
			'product_sale_price',
			array(
				'label'     => __( 'Sale Price', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Color Regular Price.
		$this->add_control(
			'product_sale_price_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woostify-product-featured .price del span ' => 'color: {{VALUE}};',
				),
			)
		);

		// Regular Price Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_sale_price_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .woostify-product-featured .price del span',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style
	 */
	private function section_arrows() {
		$this->start_controls_section(
			'product_arrows',
			array(
				'label' => esc_html__( 'Arrows', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrows_size',
			array(
				'label'     => __( 'Size', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .adv-featured-product-arrow .slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'arrows_spacing',
			array(
				'label'     => __( 'Spacing', 'woostify-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .adv-featured-product-arrow .ti-angle-left.slick-arrow:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'product_arrows_tabs' );

		// Normal.
		$this->start_controls_tab(
			'product_arrows_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'product_arrows_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .adv-featured-product-arrow .slick-arrow:before' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'product_arrows_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'product_arrows_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .adv-featured-product-arrow span:hover.slick-arrow:before' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	/**
	 * Render
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$title          = $settings['title'];
		$slide          = $settings['product_slide'];
		$autoplay       = $settings['autoplay'];
		$timeout        = $settings['timeout'];
		$position_image = $settings['image_position'];

		if ( 'yes' === $autoplay ) {
			$autoplay = 'true';
		} else {
			$autoplay = 'false';
		}

		// Category ids.
		$cat_ids = $settings['product_cat_ids'];

		// Product ids.
		$product_ids         = $settings['product_ids'];
		$exclude_product_ids = $settings['exclude_product_ids'];

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['count'],
			'order'          => $settings['order'],
			'orderby'        => $settings['order_by'],
		);

		if ( 'current_query' === $settings['source'] ) {
			return;
		}

		if ( 'sale' === $settings['source'] ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => $settings['count'],
				'order'          => $settings['order'],
				'orderby'        => $settings['order_by'],
				'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
			);
		}

		if ( ! empty( $cat_ids ) ) {
			$args['tax_query'] = array( // phpcs:ignore
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cat_ids,
				),
			);
		}

		if ( 'featured' === $settings['source'] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$args['tax_query'] = array( // phpcs:ignore
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => array( $product_visibility_term_ids['featured'] ),
			);
		}

		if ( ! empty( $product_ids ) ) {
			$args['post__in'] = $product_ids;
		}

		if ( ! empty( $exclude_product_ids ) ) {
			$args['post__not_in'] = $exclude_product_ids;
		}

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return;
		}

		?>
		<div class="woostify-product-featured">
			<h4 class="product-featured-title"><?php echo esc_html( $title ); ?></h4>
			<?php
			// If total post > posts per slide.
			$condition = $query->post_count > intval( $slide );

			if ( $condition ) {
				$adv_arrow_left  = apply_filters( 'woostify_pro_advanced_featured_product_arrow_left', 'ti-angle-left' );
				$adv_arrow_right = apply_filters( 'woostify_pro_advanced_featured_product_arrow_right', 'ti-angle-right' );
				?>
				<div class="adv-featured-product-arrow">
					<span class="prev-arrow <?php echo esc_attr( $adv_arrow_left ); ?>"></span>
					<span class="next-arrow <?php echo esc_attr( $adv_arrow_right ); ?>"></span>
				</div>
			<?php } ?>

			<div class="adv-featured-product <?php echo true === $condition ? 'adv-product-slider' : ''; ?>" data-items="<?php echo esc_attr( $slide ); ?>" data-auto="<?php echo esc_attr( $autoplay ); ?>" data-products="<?php echo esc_attr( $query->post_count ); ?>" data-time="<?php echo esc_attr( $timeout ); ?>">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					global $product;
					$rate  = wc_get_rating_html( $product->get_average_rating() );
					$price = $product->get_price_html();
					?>

					<div class="adv-featured-product-item featured-product-position-<?php echo esc_attr( $position_image ); ?>">
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
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_List_Widget() );
