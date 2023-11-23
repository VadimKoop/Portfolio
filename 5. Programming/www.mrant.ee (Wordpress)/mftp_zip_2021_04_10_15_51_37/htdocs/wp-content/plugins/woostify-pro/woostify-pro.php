<?php
/**
 * Plugin Name: Woostify Pro
 * Plugin URI: https://woostify.com/
 * Description: This plugin is a modules for the Woostify WordPress Theme.
 * Version: 1.4.5
 * Author: Woostify
 * Author URI: https://woostify.com/about/
 * Text Domain: woostify-pro
 * Domain Path: /languages
 *
 * @package Woostify Pro
 */

defined( 'ABSPATH' ) || exit;

// Define Constants.
define( 'WOOSTIFY_PRO_VERSION', '1.4.5' );
define( 'WOOSTIFY_PRO_FILE', __FILE__ );
define( 'WOOSTIFY_PRO_PLUGIN_BASE', plugin_basename( WOOSTIFY_PRO_FILE ) );
define( 'WOOSTIFY_PRO_PATH', plugin_dir_path( WOOSTIFY_PRO_FILE ) );
define( 'WOOSTIFY_PRO_URI', plugins_url( '/', WOOSTIFY_PRO_FILE ) );

// Require Woostify Theme Min Version.
define( 'WOOSTIFY_THEME_MIN_VERSION', '1.7.8' );

// Main Woostify Pro Class.
require_once WOOSTIFY_PRO_PATH . 'inc/class-woostify-pro.php';
