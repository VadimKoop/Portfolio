<?php

namespace HelpieFaq\Includes\Settings\Getters;

if ( !class_exists( '\\HelpieFaq\\Includes\\Settings\\Getters\\Getter' ) ) {
    class Getter
    {
        public function get_settings()
        {
            $options = get_option( 'helpie-faq' );
            /* In case option is not set, set option as empty array */
            // echo $options;
            if ( !isset( $options ) ) {
                $options = array();
            }
            $defaults_settings = $this->get_default_settings();
            $settings = [];
            foreach ( $defaults_settings as $key => $value ) {
                
                if ( !empty($options) && array_key_exists( $key, $options ) ) {
                    $settings[$key] = $options[$key];
                } else {
                    $settings[$key] = $value;
                }
            
            }
            return $settings;
        }
        
        public function get_default_settings()
        {
            $defaults = array(
                'title'                           => 'Helpie FAQ',
                'title_tag'                       => 'h3',
                'show_search'                     => false,
                'search_placeholder'              => 'Search FAQ',
                'toggle'                          => true,
                'open_by_default'                 => 'open_first',
                'faq_url_attribute'               => true,
                'display_mode'                    => 'simple_accordion',
                'sortby'                          => 'publish',
                'order'                           => 'desc',
                'limit'                           => 5,
                'enable_wpautop'                  => false,
                'theme'                           => 'light',
                'kb_integration_switcher'         => true,
                'kb_cat_content_show'             => [ 'before' ],
                'woo_integration_switcher'        => true,
                'woo_search_show'                 => true,
                'tab_title'                       => 'FAQ',
                'product_only'                    => false,
                'product_faq_relations'           => [],
                'helpie_faq_slug'                 => 'helpie_faq',
                'toggle_icon_type'                => 'default',
                'toggle_open'                     => 'fa fa-minus',
                'toggle_off'                      => 'fa fa-plus',
                'accordion_background'            => [],
                'accordion_header_content_styles' => [],
                'accordion_body_content_styles'   => [],
            );
            return $defaults;
        }
        
        public function get_premium_default_settings( $defaults )
        {
            $premium_defaults = array(
                'show_submission' => true,
                'ask_question'    => [ 'email' ],
                'onsubmit'        => 'noapproval',
                'submitter_email' => [
                'submitter_subject' => 'The FAQ you submitted has been approved',
                'submitter_message' => 'A new FAQ you had submitted has been approved by the admin ',
            ],
                'notify_admin'    => true,
                'admin_email'     => get_option( 'admin_email' ),
            );
            return array_merge( $defaults, $premium_defaults );
        }
    
    }
}