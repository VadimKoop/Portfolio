<?php

/*
Plugin Name: Helpie FAQ
Plugin URI: http://helpiewp.com/helpie-faq/
Description: Awesome WordPress FAQ plugin
Author: HelpieWP
Version: 1.5.1
Author URI: http://helpiewp.com
Network: True
Text Domain: helpie-faq
Domain Path: /languages


 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


if (function_exists('hf_fs')) {
    hf_fs()->set_basename(true, __FILE__);
} else {
    if (!class_exists('Helpie_FAQ_Plugin')) {

        define('HELPIE_FAQ_VERSION', '1.5.1');
        define('HELPIE_FAQ_DOMAIN', 'helpie-faq');
        define('HELPIE_FAQ_POST_TYPE', 'helpie_faq');
        define('HELPIE_FAQ__FILE__', __FILE__);
        define('HELPIE_FAQ_PLUGIN_BASE', plugin_basename(HELPIE_FAQ__FILE__));
        define('HELPIE_FAQ_PATH', plugin_dir_path(HELPIE_FAQ__FILE__));
        define('HELPIE_FAQ_URL', plugins_url('/', HELPIE_FAQ__FILE__));

        /** Storing Settings Options in Database tables feilds using CS_Framework*/

        define('HELPIE_FAQ_OPTIONS', 'helpie_faq_options');
        define('HELPIE_FAQ_CUSTOMIZE_OPTIONS', 'helpie_faq_customize_options');

        require_once plugin_dir_path(__FILE__) . "/lib/freemius-integrator.php";

        class Helpie_FAQ_Plugin
        {
            private static $instance;
            public static function get_instance()
            {
                if (!isset(self::$instance) && !self::$instance instanceof Helpie_FAQ_Plugin) {
                    self::$instance = new Helpie_FAQ_Plugin();
                }
                return self::$instance;
            }

            private function __construct()
            {
                $this->helpie_faq_activation();
            }

            public function helpie_faq_activation()
            {
                if (!version_compare(PHP_VERSION, '5.4', '>=')) {
                    add_action('admin_notices', [$this, 'helpie_faq_fail_php_version']);
                } elseif (!version_compare(get_bloginfo('version'), '4.5', '>=')) {
                    add_action('admin_notices', [$this, 'helpie_faq_fail_wp_version']);
                } else {
                    require HELPIE_FAQ_PATH . 'includes/plugin.php';
                }
            }

            /**
             * Show in WP Dashboard notice about the plugin is not activated (PHP version).
             * @since 1.0.0
             * @return void
             */
            public function helpie_faq_fail_php_version()
            {
                /* translators: %s: PHP version */
                $message = sprintf(esc_html__('Helpie FAQ requires PHP version %s+, plugin is currently NOT ACTIVE.', 'helpie-faq'), '5.4');
                $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
                echo wp_kses_post($html_message);
            }

            /**
             * Show in WP Dashboard notice about the plugin is not activated (WP version).
             * @since 1.5.0
             * @return void
             */
            public function helpie_faq_fail_wp_version()
            {
                /* translators: %s: WP version */
                $message = sprintf(esc_html__('Helpie FAQ requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT ACTIVE.', 'helpie-faq'), '4.5');
                $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
                echo wp_kses_post($html_message);
            }
            /**
             * Helpie FAQ Internalization
             */
        }
    }

    Helpie_FAQ_Plugin::get_instance();
}