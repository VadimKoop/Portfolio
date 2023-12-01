<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Settings')) {
    class Settings
    {
        public function __construct()
        {
            /* Check the Already Cs Framework Exists */

            if (!function_exists('hfps_framework_init') && !class_exists('HFPSFramework')) {
                require_once HELPIE_FAQ_PATH . '/lib/codestar-framework/hfps-framework.php';
            }

            /*  defining defaults of framework features */
            $this->set_defaults();

            /* Overrides to framework defult options*/
            $this->framework_overrides();
        }

        public function set_defaults()
        {
            define('HFPS_ACTIVE_FRAMEWORK', true);
            define('HFPS_ACTIVE_METABOX', false);
            define('HFPS_ACTIVE_TAXONOMY', false);
            define('HFPS_ACTIVE_SHORTCODE', true);
            define('HFPS_ACTIVE_CUSTOMIZE', false);
            define('HFPS_ACTIVE_LIGHT_THEME', true);
        }

        /**
         * Overrides the defualts of cs framework options, settings, shortcodes and metaboxs
         * by adding filter hook of cs framework
         */

        public function framework_overrides()
        {
            new \HelpieFaq\Includes\Settings\Settings_Override();
            new \HelpieFaq\Includes\Settings\Metabox_Override();
            new \HelpieFaq\Includes\Settings\Shortcode_Override();
            new \HelpieFaq\Includes\Settings\Options_Override();
        }
    }
}
