<?php
/**
 * The template for displaying the footer.
 *
 * @package woostify
 */

$woo_builder   = new Woostify_Woo_Builder();
$template_type = 'woostify_product_page';

if ( woostify_is_product_archive() ) {
	$template_type = 'woostify_shop_page';
} elseif ( is_account_page() ) {
	$template_type = 'woostify_my_account_page';
} elseif ( is_cart() && WC()->cart->is_empty() ) {
	$template_type = 'woostify_cart_empty';
} elseif ( is_cart() && ! WC()->cart->is_empty() ) {
	$template_type = 'woostify_cart_page';
} elseif ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
	$template_type = 'woostify_checkout_page';
} elseif ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
	$template_type = 'woostify_thankyou_page';
}

$page_id       = is_singular( 'woo_builder' ) ? woostify_get_page_id() : $woo_builder->template_exist( $template_type );
$page_template = get_post_meta( $page_id, '_wp_page_template' );

// Footer.
if ( in_array( 'elementor_canvas', $page_template, true ) ) {
	wp_footer();
	?>

		</body>
	</html>
	<?php
} else {
	get_footer();
}
