<?php

namespace HelpieFaq\Features\Faq;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('HelpieFaq\Features\Faq\Settings_Handlers')) {
    
    class Settings_Handlers
    {
        public function get_custom_settings($view_settings){
            
            // Get Toggle Style Settings Values Only
            $toggle_icon_style_settings = $this->get_toggle_icon_style_settings($view_settings);
            
            //Get Accordions Header & Body Styles
            $accordions_styles_settings = $this->get_accordion_style_settings($view_settings);           
            
            $args = array_merge($toggle_icon_style_settings,$accordions_styles_settings);
            return $args;
        }

        
        public function get_toggle_icon_style_settings($args){
            $toggle_icon_args = []; 
            $toggle_icon_props = array('toggle_icon_type','toggle_open','toggle_off');
            foreach($toggle_icon_props as $toggle_icon_prop){
                if(isset($args[$toggle_icon_prop])){
                    $toggle_icon_args[$toggle_icon_prop] = $args[$toggle_icon_prop];
                }
            }
            return $toggle_icon_args;
        }
        
        public function get_accordion_style_settings($args){
            $styles_args = []; 
            $accrodion_style_props = array('accordion_background','accordion_header_content_styles','accordion_body_content_styles');
            foreach($accrodion_style_props as $accrodion_style_prop){
                if(isset($args[$accrodion_style_prop])){
                    $styles_args[$accrodion_style_prop] = $args[$accrodion_style_prop];
                }
            }
            return $styles_args;
        }
        
    }
}