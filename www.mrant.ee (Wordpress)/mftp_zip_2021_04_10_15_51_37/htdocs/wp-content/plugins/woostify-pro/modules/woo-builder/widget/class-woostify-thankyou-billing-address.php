<?php
/**
 * Elementor Billing Address Widget
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
class Woostify_Thankyou_Billing_Address extends Widget_Base {
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
		return 'woostify-thankyou-billing-address';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Thankyou Billing Address', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'thankyou', 'address', 'user', 'store' );
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
			'color',
			array(
				'label'     => __( 'Text Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} address' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} address',
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
					'{{WRAPPER}} address' => 'text-align: {{VALUE}};',
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
		<address>
			<?php echo wp_kses_post( $wc_order->get_formatted_billing_address( esc_html__( 'N/A', 'woostify-pro' ) ) ); ?>

			<?php if ( $wc_order->get_billing_phone() ) : ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( $wc_order->get_billing_phone() ); ?></p>
			<?php endif; ?>

			<?php if ( $wc_order->get_billing_email() ) : ?>
				<p class="woocommerce-customer-details--email"><?php echo esc_html( $wc_order->get_billing_email() ); ?></p>
			<?php endif; ?>
		</address>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Thankyou_Billing_Address() );
