<?php
/**
 * Cart page template
 *
 * @package Woostify Pro
 */

// Header.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/header.php';
?>

<div class="woocommerce">
	<?php
	// Start.
	do_action( 'woocommerce_before_cart' );

	do_action( 'woostify_cart_page_content' );

	// End.
	do_action( 'woocommerce_after_cart' );
	?>
</div>

<?php
// Footer.
require_once WOOSTIFY_PRO_PATH . 'modules/woo-builder/template/footer.php';
