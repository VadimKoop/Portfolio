<?php

namespace HelpieFaq\Languages;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Languages\FAQ_Strings')) {
    class FAQ_Strings
    {
        public function get_strings()
        {
            $strings = array(
                'hide' => __("Hide", "helpie-faq"),
                'addFAQ' => __("Add FAQ", "helpie-faq"),
            );

            return $strings;
        }
    }
}
