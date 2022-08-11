<?php
/**
 * Checkout page template
 *
 * @package Woostify Pro
 */

// Header.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/header.php';
?>

<div class="woocommerce">
	<div class="woostify-container">
		<?php
		// Show non-cart errors.
		do_action( 'woocommerce_before_checkout_form_cart_notices' );

		// Get checkout object.
		$checkout = WC()->checkout();

		if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) { // phpcs:ignore
			wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
			wc_clear_notices();
		} else {
			$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ); // phpcs:ignore

			if ( 0 === wc_notice_count( 'error' ) && $non_js_checkout ) {
				wc_add_notice( __( 'The order totals have been updated. Please confirm your order by pressing the "Place order" button at the bottom of the page.', 'woostify-pro' ) );
			}
		}

		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woostify-pro' ) ) );
			return;
		}
		?>
	</div>

	<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

	<form name="checkout" method="post" class="checkout woocommerce-checkout woostify-checkout-form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
		<?php do_action( 'woostify_checkout_page_content' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>

<?php
// Footer.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/footer.php';
