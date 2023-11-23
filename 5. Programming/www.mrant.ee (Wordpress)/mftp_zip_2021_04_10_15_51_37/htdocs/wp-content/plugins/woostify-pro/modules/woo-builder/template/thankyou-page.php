<?php
/**
 * Thank you template
 *
 * @package Woostify Pro
 */

$wc_order = isset( $wp->query_vars['order-received'] ) ? wc_get_order( $wp->query_vars['order-received'] ) : false;

if ( woostify_is_elementor_editor() ) {
	$wc_order = \Woostify_Woo_Builder::init()->get_wc_order();
}

get_header();

if ( $wc_order ) {
	if ( $wc_order->has_status( 'failed' ) ) {
		?>
		<div class="checkout-with-order-failed">
			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
				<?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woostify-pro' ); ?>
			</p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $wc_order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woostify-pro' ); ?></a>

				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woostify-pro' ); ?></a>
				<?php endif; ?>
			</p>
		</div>
		<?php
	} else {
		do_action( 'woostify_thankyou_page_content' );
	}
} else {
	?>
	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
		<?php esc_html_e( 'Thank you. Your order has been received.', 'woostify-pro' ); ?>
	</p>
	<?php
}

get_footer();
