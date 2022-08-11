<?php

namespace HelpieFaq\Includes\Settings;

if (!class_exists('\HelpieFaq\Includes\Settings\Settings_Override')) {
    class Settings_Override
    {
        public function __construct()
        {
            add_filter('hfps_framework_settings', array($this, 'override'));
        }

        public function override()
        {
            return array(
                'menu_title' => __('FAQ Settings', 'helpie-faq'),
                'menu_parent' => 'edit.php?post_type=helpie_faq',
                'menu_type' => 'submenu', // menu, submenu, options, theme, etc.
                'menu_slug' => 'helpie-faq-settings',
                'ajax_save' => false,
                'show_reset_all' => false,
                'framework_title' => "Helpie ".__('FAQ Settings', 'helpie-faq'),
            );
        }
    }
}
