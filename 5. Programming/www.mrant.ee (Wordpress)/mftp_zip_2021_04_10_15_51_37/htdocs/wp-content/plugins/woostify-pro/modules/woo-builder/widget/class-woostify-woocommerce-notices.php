<?php
/**
 * Elementor Woocommerce Notices Widget
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
class Woostify_Woocommerce_Notices extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-woocommerce-notices';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Woocommerce Notices', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'woocommerce', 'shop', 'notice', 'store' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
	}

	/**
	 * Render
	 */
	public function render() {
		woocommerce_output_all_notices();
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Woocommerce_Notices() );
