<?php
/**
 * Elementor Product Categories Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product categories widget.
 */
class Woostify_Elementor_Product_Categories_Widget extends Widget_Base {
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
		return 'woostify-product-categories';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Product Categories', 'woostify-pro' );
	}

	/**
	 * Icon
	 */
	public function get_icon() {
		return 'eicon-product-categories';
	}

	/**
	 * Controls
	 */
	protected function _register_controls() {
		$this->section_general();
		$this->section_style();
		$this->section_categories();
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
				'default' => __( 'Product Categories', 'woostify-pro' ),
			)
		);

		// ORDER BY.
		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'order'  => __( 'Category Order', 'woostify-pro' ),
					'name'   => __( 'Name', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'        => __( 'Show product counts', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'none',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'hierarchical',
			array(
				'label'        => __( 'Show hierarchy', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'none',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'show_children_only',
			array(
				'label'        => __( 'Only show children of the current', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'none',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => __( 'Hide empty categories', 'woostify-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'none',
				'label_on'     => __( 'Yes', 'woostify-pro' ),
				'label_off'    => __( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'max_depth',
			array(
				'label'        => __( 'Maximum depth', 'woostify-pro' ),
				'type'         => Controls_Manager::NUMBER,
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
				'tab'     => Controls_Manager::TAB_STYLE,
			)
		);

		// Color.
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-categories-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .product-categories-title',
			)
		);

		// Title Spacing.
		$this->add_control(
			'title_spacing',
			array(
				'label' => __( 'Spacing', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-categories-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Categories
	 */
	private function section_categories() {
		$this->start_controls_section(
			'products_categories',
			array(
				'label' => esc_html__( 'Categories', 'woostify-pro' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_list_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .product-categories li a',
			)
		);

		// List Spacing.
		$this->add_control(
			'categories_list_spacing',
			array(
				'label' => __( 'Spacing', 'woostify-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-categories li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// TAB START.
		$this->start_controls_tabs( 'categories_list_tabs' );

		// Normal.
		$this->start_controls_tab(
			'categories_list_normal',
			array(
				'label' => __( 'Normal', 'woostify-pro' ),
			)
		);

		// Color.
		$this->add_control(
			'categories_list_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-categories li a ' => 'color: {{VALUE}};',
				),
			)
		);

		// END NORMAL.
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'categories_list_hover',
			array(
				'label' => __( 'Hover', 'woostify-pro' ),
			)
		);

		// Hover color.
		$this->add_control(
			'categories_list_hover_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-categories li a:hover ' => 'color: {{VALUE}};',
				),
			)
		);

		// TAB END.
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Count.
		$this->add_control(
			'categories_count',
			array(
				'label' => __( 'Count', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'categories_count_typo',
				'label'    => esc_html__( 'Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .product-categories .count',
			)
		);

		// Color.
		$this->add_control(
			'categories_count_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .product-categories .count ' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title    = $settings['title'];
		$orderby  = $settings['orderby'];
		$count    = $settings['count'];
		$hierarchical  = $settings['hierarchical'];
		$show_children = $settings['show_children_only'];
		$hide_empty    = $settings['hide_empty'];
		$max_depth     = $settings['max_depth'];

		?>
			<div class="woostify-product-categories">
				<h3 class="product-categories-title"><?php echo esc_html( $title ); ?></h3>
				<?php
					$list_args          = array(
						'show_count'   => $count,
						'hierarchical' => $hierarchical,
						'taxonomy'     => 'product_cat',
						'hide_empty'   => $hide_empty,
					);

					$list_args['menu_order'] = false;
					$list_args['depth']      = $max_depth;

					if ( 'order' === $orderby ) {
						$list_args['orderby']  = 'meta_value_num';
						$list_args['meta_key'] = 'order';
					}

					$this->current_cat   = false;
					$this->cat_ancestors = array();

					if ( is_tax( 'product_cat' ) ) {
						$this->current_cat   = $wp_query->queried_object;
						$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

					} elseif ( is_singular( 'product' ) ) {
						$terms = wc_get_product_terms(
							$post->ID,
							'product_cat',
							apply_filters(
								'woocommerce_product_categories_widget_product_terms_args',
								array(
									'orderby' => 'parent',
									'order'   => 'DESC',
								)
							)
						);

						if ( $terms ) {
							$main_term           = apply_filters( 'woocommerce_product_categories_widget_main_term', $terms[0], $terms );
							$this->current_cat   = $main_term;
							$this->cat_ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
						}
					}

					// Show Siblings and Children Only.
					if ( $show_children && $this->current_cat ) {
						if ( $hierarchical ) {
							$include = array_merge(
								$this->cat_ancestors,
								array( $this->current_cat->term_id ),
								get_terms(
									'product_cat',
									array(
										'fields'       => 'ids',
										'parent'       => 0,
										'hierarchical' => true,
										'hide_empty'   => false,
									)
								),
								get_terms(
									'product_cat',
									array(
										'fields'       => 'ids',
										'parent'       => $this->current_cat->term_id,
										'hierarchical' => true,
										'hide_empty'   => false,
									)
								)
							);
							// Gather siblings of ancestors.
							if ( $this->cat_ancestors ) {
								foreach ( $this->cat_ancestors as $ancestor ) {
									$include = array_merge(
										$include,
										get_terms(
											'product_cat',
											array(
												'fields'       => 'ids',
												'parent'       => $ancestor,
												'hierarchical' => false,
												'hide_empty'   => false,
											)
										)
									);
								}
							}
						} else {
							// Direct children.
							$include = get_terms(
								'product_cat',
								array(
									'fields'       => 'ids',
									'parent'       => $this->current_cat->term_id,
									'hierarchical' => true,
									'hide_empty'   => false,
								)
							);
						}

						$list_args['include']     = implode( ',', $include );

						if ( empty( $include ) ) {
							return;
						}
					} elseif ( $show_children ) {
						$list_args['depth']        = 1;
						$list_args['child_of']     = 0;
						$list_args['hierarchical'] = 1;
					}

					include_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';

					$list_args['walker']                     = new \WC_Product_Cat_List_Walker();
					$list_args['title_li']                   = '';
					$list_args['pad_counts']                 = 1;
					$list_args['show_option_none']           = __( 'No product categories exist.', 'woostify-pro' );
					$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
					$list_args['current_category_ancestors'] = $this->cat_ancestors;
					$list_args['max_depth']                  = $max_depth;

					echo '<ul class="product-categories">';

					wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );

					echo '</ul>';
					?>
			</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Categories_Widget() );
