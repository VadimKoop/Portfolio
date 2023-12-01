<?php

namespace HelpieFaq\Includes\Settings;


if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('\HelpieFaq\Includes\Settings\Option_Values')) {
    class Option_Values{
        public function get_allowed_title_tags(){
            return array(
                'h1' => __('Heading 1', 'helpie-faq'),
                'h2' => __('Heading 2', 'helpie-faq'),
                'h3' => __('Heading 3', 'helpie-faq'),
                'h4' => __('Heading 4', 'helpie-faq'),
                'h5' => __('Heading 5', 'helpie-faq'),
                'h6' => __('Heading 6', 'helpie-faq'),
                'p' => __('Paragraph', 'helpie-faq'),
            );
        }
    }
}