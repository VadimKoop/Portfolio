<?php

namespace HelpieFaq\Features\Faq;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly

if ( !class_exists( '\\HelpieFaq\\Features\\Faq\\Faq_View' ) ) {
    class Faq_View
    {
        public function get( $viewProps )
        {
            require_once HELPIE_FAQ_PATH . 'lib/stylus/stylus.php';
            $stylus = new \Stylus\Stylus();
            $viewProps['collection'] = $this->boolean_conversion( $viewProps['collection'] );
            $additional_classes = $this->get_additional_classes( $viewProps );
            $id = '';
            if ( isset( $viewProps['collection']['id'] ) ) {
                $id .= $viewProps['collection']['id'];
            }
            $html = '<section id="' . $id . '" class="helpie-faq accordions ' . $additional_classes . '">';
            $title_tag = $this->get_title_tag( $viewProps );
            if ( isset( $viewProps['collection']['title'] ) && $viewProps['collection']['title'] != '' ) {
                $html .= '<' . $title_tag . ' class="collection-title">' . $viewProps['collection']['title'] . '</' . $title_tag . '>';
            }
            // TODO check FAQ searchbar is enable or not
            $is_faq_search_enabled = $this->is_faq_search_enabled( $viewProps );
            if ( $is_faq_search_enabled ) {
                $html .= $stylus->search->get_view( $viewProps['collection'] );
            }
            $html .= $stylus->accordion->get_view( $viewProps );
            $html .= '</section>';
            return $html;
        }
        
        protected function boolean_conversion( $args )
        {
            foreach ( $args as $key => $arg ) {
                
                if ( $arg == 'on' ) {
                    $args[$key] = true;
                } else {
                    if ( $arg == 'off' ) {
                        $args[$key] = false;
                    }
                }
            
            }
            return $args;
        }
        
        public function get_additional_classes( $viewProps )
        {
            $additional_classes = "";
            if ( isset( $viewProps['collection']['theme'] ) && $viewProps['collection']['theme'] == 'dark' ) {
                $additional_classes .= " dark";
            }
            if ( isset( $viewProps['collection']['toggle'] ) && $viewProps['collection']['toggle'] ) {
                $additional_classes .= " toggle";
            }
            $additional_classes .= $this->get_faq_open_by_default_class( $viewProps );
            return $additional_classes;
        }
        
        private function get_faq_open_by_default_class( $viewProps )
        {
            $faq_default_class = '';
            
            if ( isset( $viewProps['collection']['open_by_default'] ) ) {
                if ( $viewProps['collection']['open_by_default'] == 'open_all_faqs' ) {
                    $faq_default_class = ' open-all';
                }
                if ( $viewProps['collection']['open_by_default'] == 'open_first' ) {
                    $faq_default_class = ' open-first';
                }
            }
            
            return $faq_default_class;
        }
        
        private function is_faq_search_enabled( $viewProps )
        {
            
            if ( is_singular( 'product' ) ) {
                if ( isset( $viewProps['collection']['woo_search_show'] ) && $viewProps['collection']['woo_search_show'] ) {
                    return true;
                }
            } else {
                if ( isset( $viewProps['collection']['show_search'] ) && $viewProps['collection']['show_search'] ) {
                    return true;
                }
            }
            
            return false;
        }
        
        private function get_title_tag( $viewProps )
        {
            $title_tag = '';
            $default_tag = 'h3';
            $setting_defaults = new \HelpieFaq\Includes\Settings\Option_Values();
            $allowed_title_tags = $setting_defaults->get_allowed_title_tags();
            $title_tag = ( isset( $viewProps['collection']['title_tag'] ) ? $viewProps['collection']['title_tag'] : '' );
            if ( empty($title_tag) ) {
                return $default_tag;
            }
            if ( isset( $allowed_title_tags[$title_tag] ) ) {
                return $title_tag;
            }
            return $default_tag;
        }
    
    }
    // END CLASS
}
