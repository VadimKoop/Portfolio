<?php

namespace HelpieFaq\Includes\Settings\Sections;

if (!class_exists('\HelpieFaq\Includes\Settings\Sections\Style_Section')) {

    class Style_Section
    {
        public function get_section()
        {
            return array(
                'name' => 'style',
                'title' => __('Style', 'helpie-faq'),
                'icon' => 'fa fa-paint-brush',

                'fields' => array(
                    array(
                        'id' => 'theme',
                        'type' => 'select',
                        'title' => __('Theme', 'helpie-faq'),
                        'options' => array(
                            'light' => __('Light', 'helpie-faq'),
                            'dark' => __('Dark', 'helpie-faq'),
                        ),
                        'default' => 'light',
                        'info' => __('Select Theme of FAQ Layout Section', 'helpie-faq'),
                    ),
                ),
            );
        }
    }
}
