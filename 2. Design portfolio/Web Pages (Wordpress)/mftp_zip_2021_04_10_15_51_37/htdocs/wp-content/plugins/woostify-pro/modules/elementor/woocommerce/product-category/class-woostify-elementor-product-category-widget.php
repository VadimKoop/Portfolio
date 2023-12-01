<?php
/**
 * Elementor Product Category Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

/**
 * Class woostify elementor product category widget.
 */
class Woostify_Elementor_Product_Category_Widget extends Widget_Base {
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
		return 'woostify-product-category';
	}

	/**
	 * Title
	 */
	public function get_title() {
		return esc_html__( 'Woostify - Product Category', 'woostify-pro' );
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
		$this->section_content();
		$this->section_query();
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

		// Columns.
		$this->add_responsive_control(
			'columns',
			array(
				'type'           => Controls_Manager::SELECT,
				'label'          => esc_html__( 'Columns', 'woostify-pro' ),
				'default'        => 4,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options'        => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
				),
			)
		);

		// Image size.
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'medium_large',
			)
		);

		// Grid space.
		$this->add_responsive_control(
			'space',
			array(
				'type'               => Controls_Manager::DIMENSIONS,
				'label'              => esc_html__( 'Columns Space', 'woostify-pro' ),
				'size_units'         => array( 'px', 'em' ),
				'default'            => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'allowed_dimensions' => array(
					'top',
					'bottom',
				),
				'selectors'          => array(
					'{{WRAPPER}} .ht-grid-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Columns gap.
		$this->add_responsive_control(
			'columns_gap',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Columns Gap', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ht-grid'      => 'margin: 0px -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ht-grid-item' => 'padding: 0px {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Overlay background.
		$this->add_control(
			'overlay_bg',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Overlay', 'woostify-pro' ),
				'default'   => 'rgba(255,255,255,0)',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .pcw-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content
	 */
	private function section_content() {
		$this->start_controls_section(
			'product_content',
			array(
				'label' => esc_html__( 'Content', 'woostify-pro' ),
			)
		);

		// Content position.
		$this->add_control(
			'content_pos',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_html__( 'Content', 'woostify-pro' ),
				'default' => 'inside',
				'options' => array(
					'inside'  => __( 'Inside', 'woostify-pro' ),
					'outside' => __( 'Outside', 'woostify-pro' ),
				),
			)
		);

		// Horizontal Position.
		$this->add_control(
			'horizontal_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Horizontal', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pcw-info-inner' => 'align-items: {{VALUE}};',
				),
			)
		);

		// Vertical Position.
		$this->add_control(
			'vertical_pos',
			array(
				'type'        => Controls_Manager::CHOOSE,
				'label'       => esc_html__( 'Vertical', 'woostify-pro' ),
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Middle', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Bottom', 'woostify-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pcw-info' => 'justify-content: {{VALUE}};',
				),
				'condition'   => array(
					'content_pos' => 'inside',
				),
			)
		);

		// Padding.
		$this->add_responsive_control(
			'padding',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcw-info-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Margin.
		$this->add_responsive_control(
			'margin',
			array(
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'woostify-pro' ),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .pcw-info-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Content background color.
		$this->add_control(
			'content_bg_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Color', 'woostify-pro' ),
				'default'   => '',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .pcw-info-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		// Category name.
		$this->add_control(
			'category_name',
			array(
				'label'     => __( 'Category Name', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Title color.
		$this->add_control(
			'title_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'woostify-pro' ),
				'default'   => '#3c3c3c',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .pcw-title' => 'color: {{VALUE}}',
				),
			)
		);

		// Typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title',
				'label'    => __( 'Typography', 'woostify-pro' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .pcw-title',
			)
		);

		// Category name.
		$this->add_control(
			'category_count',
			array(
				'label'     => __( 'Category Count', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Category count.
		$this->add_control(
			'count',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Category count color.
		$this->add_control(
			'count_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'woostify-pro' ),
				'default'   => '#bdbdbd',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .pcw-count' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'count' => 'yes',
				),
			)
		);

		// Custom button.
		$this->add_control(
			'custom_button_heading',
			array(
				'label'     => __( 'Button', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Button.
		$this->add_control(
			'button',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display', 'woostify-pro' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Custom button.
		$this->add_control(
			'button_text',
			array(
				'type'      => Controls_Manager::TEXT,
				'label'     => esc_html__( 'Text', 'woostify-pro' ),
				'default'   => __( 'Shop now', 'woostify-pro' ),
				'condition' => array(
					'button' => 'yes',
				),
			)
		);

		// Button color.
		$this->add_control(
			'button_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Text Color', 'woostify-pro' ),
				'default'   => '#e71717',
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .pcw-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'button' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Query
	 */
	private function section_query() {
		$this->start_controls_section(
			'product_query',
			array(
				'label' => esc_html__( 'Query', 'woostify-pro' ),
			)
		);

		// Category ids.
		$this->add_control(
			'category_ids',
			array(
				'label' => esc_html__( 'Category', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
			)
		);

		// Exclude category ids.
		$this->add_control(
			'exclude_category_ids',
			array(
				'label' => esc_html__( 'Exclude Category', 'woostify-pro' ),
				'type'  => 'autocomplete',
				'query' => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
			)
		);

		// Orderby.
		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'id'    => esc_html__( 'ID', 'woostify-pro' ),
					'name'  => esc_html__( 'Name', 'woostify-pro' ),
					'date'  => esc_html__( 'Date', 'woostify-pro' ),
					'rand'  => esc_html__( 'Random', 'woostify-pro' ),
					'count' => esc_html__( 'Count', 'woostify-pro' ),
				),
			)
		);

		// Order.
		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => esc_html__( 'ASC', 'woostify-pro' ),
					'DESC' => esc_html__( 'DESC', 'woostify-pro' ),
				),
			)
		);

		// Subcategory.
		$this->add_control(
			'subcategory',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Display Subcategories', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		// Hide empty.
		$this->add_control(
			'hide_empty',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Hide Empty', 'woostify-pro' ),
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woostify-pro' ),
				'label_off'    => esc_html__( 'No', 'woostify-pro' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$args     = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => $settings['hide_empty'],
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
		);

		if ( 'yes' !== $settings['subcategory'] ) {
			$args['parent'] = 0;
		}

		if ( ! empty( $settings['category_ids'] ) ) {
			$args['include'] = $settings['category_ids'];
		}

		if ( ! empty( $settings['exclude_category_ids'] ) ) {
			if ( ! empty( $settings['category_ids'] ) ) {
				$diff            = array_diff( $settings['category_ids'], $settings['exclude_category_ids'] );
				$args['include'] = $diff;
			} else {
				$args['exclude'] = $settings['exclude_category_ids'];
			}
		}

		$product_cat = get_terms( $args );

		if ( empty( $product_cat ) ) {
			return;
		}

		// Grid.
		$classes   = array();
		$classes[] = 'ht-grid'; // Defined grid.
		$classes[] = 'ht-grid-' . $settings['columns']; // On desktop.
		$classes[] = 'ht-grid-tablet-' . $settings['columns_tablet']; // On tablet.
		$classes[] = 'ht-grid-mobile-' . $settings['columns_mobile']; // On mobile.
		$classes[] = 'content-' . $settings['content_pos'];

		// Generate classes.
		$classes = implode( ' ', $classes );
		?>

		<div class="woostify-product-category-widget">
			<div class="<?php echo esc_attr( $classes ); ?>">
				<?php
				foreach ( $product_cat as $k ) {
					$img_id    = get_term_meta( $k->term_id, 'thumbnail_id', true );
					$img_alt   = woostify_image_alt( $img_id, __( 'Product category image', 'woostify-pro' ), true );
					$img_src   = wp_get_attachment_image_src( $img_id, $settings['image_size'] );
					$img_src   = $img_id ? $img_src[0] : wc_placeholder_img_src();
					$term_link = get_term_link( $k->term_id, 'product_cat' );

					// Content.
					$product_count = sprintf(
						/* translators: 1: number of comments, 2: post title */
						_nx( '%1$s Product', '%1$s Products', $k->count, 'product count', 'woostify-pro' ),
						$k->count
					);

					$content  = '<span class="pcw-info">';
					$content .= '<span class="pcw-info-inner">';
					// Add permalink.
					if ( 'outside' === $settings['content_pos'] ) {
						$content .= '<a class="pcw-link" href="' . esc_url( $term_link ) . '"></a>';
					}
					$content .= '<span class="pcw-title">' . esc_html( $k->name ) . '</span>';
					// Category count.
					if ( 'yes' === $settings['count'] ) {
						$content .= '<span class="pcw-count">' . esc_html( $product_count ) . '</span>';
					}
					// Custom button.
					if ( 'yes' === $settings['button'] ) {
						$content .= '<span class="pcw-button">' . esc_html( $settings['button_text'] ) . '</span>';
					}
					$content .= '</span>';
					$content .= '</span>';
					?>

					<div class="ht-grid-item">
						<div class="pcw-item">
							<a class="pcw-image" href="<?php echo esc_url( $term_link ); ?>">
								<img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">

								<?php
								if ( 'inside' === $settings['content_pos'] ) {
									echo $content; // phpcs:ignore
								}

								if ( '' !== $settings['overlay_bg'] && 'inside' === $settings['content_pos'] ) {
									?>
									<span class="pcw-overlay"></span>
									<?php
								}
								?>
							</a>

							<?php
							if ( 'outside' === $settings['content_pos'] ) {
								echo $content; // phpcs:ignore
							}
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Product_Category_Widget() );
