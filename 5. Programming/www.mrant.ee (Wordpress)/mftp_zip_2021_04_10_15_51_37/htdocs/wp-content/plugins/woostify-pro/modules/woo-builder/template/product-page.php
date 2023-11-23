<?php
/**
 * Single product template
 *
 * @package Woostify Pro
 */

$woo_builder   = new Woostify_Woo_Builder();
$is_woobuilder = $woo_builder->product_page_woobuilder();

// Header.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/header.php';

if ( $is_woobuilder ) {
	$frontend = new \Elementor\Frontend();
	echo $frontend->get_builder_content_for_display( $is_woobuilder, true ); // phpcs:ignore
	wp_reset_postdata();
} else {
	wc_get_template( 'content-single-product.php' );
}

// Footer.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/footer.php';
