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
class Woostify_Thankyou_Order_Details extends Widget_Base {
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
		return 'woostify-thankyou-order-details';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Thankyou Order Details', 'woostify-pro' );
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
		return array( 'woostify', 'woocommerce', 'shop', 'thankyou', 'details', 'store' );
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
				'label'     => __( 'Separator Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .order_details tr' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding Items', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .order_details th, {{WRAPPER}} .order_details td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'label_start',
			array(
				'label'     => __( 'Label', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .order_details tr th:first-child, {{WRAPPER}} .order_details tr th:first-child a, {{WRAPPER}} .order_details tr td:first-child, {{WRAPPER}} .order_details tr td:first-child a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'selector' => '{{WRAPPER}} .order_details tr th:first-child, {{WRAPPER}} .order_details tr td:first-child',
			)
		);

		$this->add_control(
			'value_start',
			array(
				'label'     => __( 'Value', 'woostify-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .order_details tr th:last-child, {{WRAPPER}} .order_details tr th:last-child span, {{WRAPPER}} .order_details tr td:last-child, {{WRAPPER}} .order_details tr td:last-child span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typo',
				'selector' => '{{WRAPPER}} .order_details tr th:last-child, {{WRAPPER}} .order_details tr td:last-child',
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

		$settings              = $this->get_settings_for_display();
		$order_items           = $wc_order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
		$show_purchase_note    = $wc_order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
		$show_customer_details = is_user_logged_in() && $wc_order->get_user_id() === get_current_user_id();
		$downloads             = $wc_order->get_downloadable_items();
		$show_downloads        = $wc_order->has_downloadable_item() && $wc_order->is_download_permitted();

		if ( $show_downloads ) {
			wc_get_template(
				'order/order-downloads.php',
				array(
					'downloads'  => $downloads,
					'show_title' => true,
				)
			);
		}
		do_action( 'woocommerce_order_details_before_order_table', $wc_order );
		?>

		<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

			<thead>
				<tr>
					<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woostify-pro' ); ?></th>
					<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woostify-pro' ); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				do_action( 'woocommerce_order_details_before_order_table_items', $wc_order );

				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();

					wc_get_template(
						'order/order-details-item.php',
						array(
							'order'              => $wc_order,
							'item_id'            => $item_id,
							'item'               => $item,
							'show_purchase_note' => $show_purchase_note,
							'purchase_note'      => $product ? $product->get_purchase_note() : '',
							'product'            => $product,
						)
					);
				}

				do_action( 'woocommerce_order_details_after_order_table_items', $wc_order );
				?>
			</tbody>

			<tfoot>
				<?php
				foreach ( $wc_order->get_order_item_totals() as $key => $total ) {
					?>
						<tr>
							<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
							<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
				}
				?>
				<?php if ( $wc_order->get_customer_note() ) : ?>
					<tr>
						<th><?php esc_html_e( 'Note:', 'woostify-pro' ); ?></th>
						<td><?php echo wp_kses_post( nl2br( wptexturize( $wc_order->get_customer_note() ) ) ); ?></td>
					</tr>
				<?php endif; ?>
			</tfoot>
		</table>

		<?php
		do_action( 'woocommerce_order_details_after_order_table', $wc_order );
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Thankyou_Order_Details() );
