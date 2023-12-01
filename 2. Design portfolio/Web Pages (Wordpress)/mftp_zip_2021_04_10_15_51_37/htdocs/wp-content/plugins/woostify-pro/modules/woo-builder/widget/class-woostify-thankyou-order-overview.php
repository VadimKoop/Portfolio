<?php
/**
 * Elementor Thankyou Order Overview Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class widget.
 */
class Woostify_Thankyou_Order_Overview extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-thankyou-page' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-thankyou-order-overview';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Thankyou Order Overview', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-navigator';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'thankyou', 'user', 'store' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->start_controls_section(
			'start',
			array(
				'label' => __( 'General', 'woostify-pro' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-overview li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Value Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-order-overview strong' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sepa_color',
			array(
				'label'     => __( 'Separator Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-thankyou-order-details li + li' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .woocommerce-order-overview li',
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'     => __( 'Direction', 'woostify-pro' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => 'row',
				'options'   => array(
					'column' => __( 'Column', 'woostify-pro' ),
					'row'    => __( 'Row', 'woostify-pro' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'woostify-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'woostify-pro' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-thankyou-order-details' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-thankyou-order-details li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		global $wp;

		$wc_order = isset( $wp->query_vars['order-received'] ) ? wc_get_order( $wp->query_vars['order-received'] ) : false;

		if ( woostify_is_elementor_editor() ) {
			$wc_order = \Woostify_Woo_Builder::init()->get_wc_order();
		}

		if ( ! $wc_order || $wc_order->has_status( 'failed' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		?>
		<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details flex-direction-<?php echo esc_attr( $settings['direction'] ); ?>">
			<li class="woocommerce-order-overview__order order">
				<?php esc_html_e( 'Order number:', 'woostify-pro' ); ?>
				<strong><?php echo esc_html( $wc_order->get_order_number() ); ?></strong>
			</li>

			<li class="woocommerce-order-overview__date date">
				<?php esc_html_e( 'Date:', 'woostify-pro' ); ?>
				<strong><?php echo esc_html( wc_format_datetime( $wc_order->get_date_created() ) ); ?></strong>
			</li>

			<?php if ( is_user_logged_in() && $wc_order->get_user_id() === get_current_user_id() && $wc_order->get_billing_email() ) { ?>
				<li class="woocommerce-order-overview__email email">
					<?php esc_html_e( 'Email:', 'woostify-pro' ); ?>
					<strong><?php echo esc_html( $wc_order->get_billing_email() ); ?></strong>
				</li>
			<?php } ?>

			<li class="woocommerce-order-overview__total total">
				<?php esc_html_e( 'Total:', 'woostify-pro' ); ?>
				<strong><?php echo wp_kses( $wc_order->get_formatted_order_total(), array() ); ?></strong>
			</li>

			<?php if ( $wc_order->get_payment_method_title() ) { ?>
				<li class="woocommerce-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'woostify-pro' ); ?>
					<strong><?php echo wp_kses_post( $wc_order->get_payment_method_title() ); ?></strong>
				</li>
			<?php } ?>
		</ul>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Thankyou_Order_Overview() );
