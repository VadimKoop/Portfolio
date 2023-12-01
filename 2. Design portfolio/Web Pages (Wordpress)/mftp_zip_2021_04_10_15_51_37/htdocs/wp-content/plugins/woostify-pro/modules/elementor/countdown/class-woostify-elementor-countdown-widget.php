<?php
/**
 * Elementor Countdown Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for woostify elementor Countdown widget.
 */
class Woostify_Elementor_Countdown_Widget extends Widget_Base {
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
		return 'woostify-countdown';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Countdown', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return [ 'woostify', 'number', 'countdown' ];
	}

	/**
	 * Add a script.
	 */
	public function get_script_depends() {
		return [ 'woostify-countdown', 'woostify-elementor-widget' ];
	}

	/**
	 * Settings
	 */
	public function setting() {
		$this->start_controls_section(
			'setting',
			[
				'label' => __( 'Settings', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'date',
			[
				'label' => __( 'Due Date', 'woostify-pro' ),
				'type'  => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'enableTime' => false,
					'dateFormat' => 'm/d/Y',
				],
				'default' => '10/23/2020',
			]
		);

		$this->add_control(
			'days_text',
			[
				'label'   => __( 'Text Days', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'days', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'hours_text',
			[
				'label'   => __( 'Text Hours', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'hours', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'mins_text',
			[
				'label'   => __( 'Text Mins', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'mins', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'seconds_text',
			[
				'label'   => __( 'Text Seconds', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'seconds', 'woostify-pro' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Option
	 */
	public function style() {
		$this->start_controls_section(
			'style',
			[
				'label' => esc_html__( 'Style', 'woostify-pro' ),
			]
		);

		$this->add_control(
			'general',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'General', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'item_background',
			[
				'type'       => Controls_Manager::COLOR,
				'label'      => esc_html__( 'Background', 'woostify-pro' ),
				'selectors'  => [
					'{{WRAPPER}} .woostify-countdown-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Margin', 'woostify-pro' ),
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .woostify-countdown-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .woostify-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .woostify-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Alignment', 'woostify-pro' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'woostify-pro' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'woostify-pro' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'woostify-pro' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .woostify-countdown-widget' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_digits',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Digits', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'digits_padding',
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wdcd-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'digits_color',
			[
				'label'     => esc_html__( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wdcd-time' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'digits_typography',
				'selector' => '{{WRAPPER}} .wdcd-time',
			]
		);

		$this->add_control(
			'heading_label',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Label', 'woostify-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'type'       => Controls_Manager::DIMENSIONS,
				'label'      => esc_html__( 'Padding', 'woostify-pro' ),
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .wdcd-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wdcd-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .wdcd-text',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Controls
	 */
	protected function _register_controls() {
		$this->setting();
		$this->style();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="woostify-countdown-widget" data-date="<?php echo esc_attr( $settings['date'] ); ?>">
			<div class="woostify-countdown-item">
				<span id="<?php echo esc_attr( uniqid( 'wdcd-days-' ) ); ?>" class="wdcd-time woostify-countdown-days"></span>
				<span class="wdcd-text"><?php echo esc_html( $settings['days_text'] ); ?></span>
			</div>

			<div class="woostify-countdown-item">
				<span id="<?php echo esc_attr( uniqid( 'wdcd-hours-' ) ); ?>" class="wdcd-time woostify-countdown-hours"></span>
				<span class="wdcd-text"><?php echo esc_html( $settings['hours_text'] ); ?></span>
			</div>

			<div class="woostify-countdown-item">
				<span id="<?php echo esc_attr( uniqid( 'wdcd-mins-' ) ); ?>" class="wdcd-time woostify-countdown-mins"></span>
				<span class="wdcd-text"><?php echo esc_html( $settings['mins_text'] ); ?></span>
			</div>

			<div class="woostify-countdown-item">
				<span id="<?php echo esc_attr( uniqid( 'wdcd-seconds-' ) ); ?>" class="wdcd-time woostify-countdown-seconds"></span>
				<span class="wdcd-text"><?php echo esc_html( $settings['seconds_text'] ); ?></span>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Elementor_Countdown_Widget() );
