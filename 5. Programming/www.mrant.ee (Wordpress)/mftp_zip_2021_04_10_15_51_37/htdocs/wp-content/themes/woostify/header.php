<?php
/**
 * The header for our theme.
 *
 * @package woostify
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
	<meta name="verify-paysera" content="d8bba69116e8f92d8d0987a7420c33ac">
	<?php wp_head(); ?></head>

	<body <?php body_class(); ?>>
		<?php
			wp_body_open();
			do_action( 'woostify_theme_header' );
