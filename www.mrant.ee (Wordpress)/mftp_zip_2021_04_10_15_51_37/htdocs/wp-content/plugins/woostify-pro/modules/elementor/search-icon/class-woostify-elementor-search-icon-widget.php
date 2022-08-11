<?php
/**
 * Elementor Search Icon Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Search icon widget.
 */
class Woostify_Elementor_Search_Icon_Widget extends Widget_Base {
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
		return 'woostify-search-icon';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Search Icon', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'search' );
	}

	/**
	 * General
	 */
	public function general() {
		$this->start_controls_section(
			'general',
			array(
				'label' => __( 'General', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Autocomplete.
		$this->add_control(
			'demo_auto',
			array(
				'type'  => 'autocomplete',
				'label' => esc_html__( 'Product', 'woostify-pro' ),
				'query' => array(
					'type' => 'post_type',
					'name' => 'product',
				),
			)
		);

		$this->add_control(
			'demo_auto_term',
			array(
				'type'  => 'autocomplete',
				'label' => esc_html__( 'Product Category', 'woostify-pro' ),
				'query' => array(
					'type' => 'term',
					'name' => 'product_cat',
				),
			)
		);

		// Alignment.
		$this->add_responsive_control(
			'alignment',
			array(
				'type'      => Controls_Manager::CHOOSE,
				'label'     => esc_html__( 'Alignment', 'woostify-pro' ),
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woostify-search-icon-widget' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .header-search-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Icon
	 */
	public function search_icon() {
		$this->start_controls_section(
			'cart',
			array(
				'label' => __( 'Icon', 'woostify-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => __( 'Icon', 'woostify-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'theme',
				'options' => array(
					'theme' => __( 'Use Theme Icon', 'woostify-pro' ),
					'icon'  => __( 'Use Custom Icon', 'woostify-pro' ),
					'image' => __( 'Use Image', 'woostify-pro' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'Choose Icon', 'woostify-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-search',
					'library' => 'solid',
				),
				'condition' => array(
					'type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .header-search-icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'type' => array( 'icon', 'theme' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'woostify-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .header-search-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .custom-svg-icon'    => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type' => array( 'icon', 'theme' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'woostify-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'type' => 'image',
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
		$this->search_icon();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$icon     = ( 'theme' === $settings['type'] ) ? apply_filters( 'woostify_header_search_icon', 'ti-search' ) : '';
		if ( 'icon' === $settings['type'] && ! empty( $settings['icon']['value'] ) ) {
			if ( is_array( $settings['icon']['value'] ) ) {
				$icon = 'custom-svg-icon';
			} else {
				$icon = $settings['icon']['value'];
			}
		}
		?>
		<div class="woostify-search-icon-widget">
			<span class="header-search-icon <?php echo esc_attr( $icon ); ?>">
				<?php
				if ( 'image' === $settings['type'] || ( 'icon' === $settings['type'] && is_array( $settings['icon']['value'] ) && ! empty( $settings['icon']['value'] ) ) ) {
					$img_id  = 'image' === $settings['type'] ? $settings['image']['id'] : $settings['icon']['value']['id'];
					$img_url = 'image' === $settings['type'] ? $settings['image']['url'] : $settings['icon']['value']['url'];
					$img_alt = woostify_image_alt( $img_id, __( 'Account Icon', 'woostify-pro' ) );
					?>
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
				<?php } ?>
			</span>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Search_Icon_Widget() );
