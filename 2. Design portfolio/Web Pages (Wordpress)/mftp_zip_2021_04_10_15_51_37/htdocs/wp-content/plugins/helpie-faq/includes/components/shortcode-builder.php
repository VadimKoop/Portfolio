<?php

namespace HelpieFaq\Includes\Components;


if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('\HelpieFaq\Includes\Components\Shortcode_Builder')) {

    class Shortcode_Builder{

        
        public function __construct (){
            add_action('init', [$this, 'shortcode_builder']);
        }

        public function shortcode_builder(){
           

            if (!function_exists('\CSF') && !class_exists('\CSF')) {
                require_once HELPIE_FAQ_PATH . 'lib/codestar-framework/codestar-framework.php';
               
            }

            if (class_exists('\CSF')) {

                //TODO: Set unique ID to helpie-faq shortcode builder
                $prefix = 'helpie-faq-shordcode';

                //TODO: Get Helpie FAQ general fields  
                
                $fields = new \HelpieFaq\Includes\Settings\Fields();
                $general_settings_fields = $fields->get_general_settings();
                $categories_field = $fields->get_categories_field();
                $shortcode_fields = array_merge($general_settings_fields,$categories_field);

                //TODO: helpie faq shortcode builder button shows only into the code editor
                \CSF::createShortcoder( $prefix, array(
                    'button_title'   => 'Add Helpie FAQ Shortcode',
                    'select_title'   => 'Select a shortcode',
                    'insert_title'   => 'Insert Shortcode',
                    'show_in_editor' => true,
                    'gutenberg'      => []
                ));

                \CSF::createSection( $prefix, array(
                    'title'     => 'Helpie FAQ',
                    'view'      => 'normal',
                    'shortcode' => 'helpie_faq',
                    'fields'    => $shortcode_fields
                ));
            }
        }

    }

}