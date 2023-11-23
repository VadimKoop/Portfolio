<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Shortcodes')) {
    class Shortcodes
    {
        public function __construct()
        {
        }

        public static function basic($atts, $content = null)
        {
            
            $faq = new \HelpieFaq\Features\Faq\Faq();
            $faq_model = new \HelpieFaq\Features\Faq\Faq_Model();
            $defaults = $faq_model->get_default_args();
            $args = shortcode_atts($defaults, $atts);

            /**
             * Check the shorcode is faq_group shortcode or not. 
             * If it's faq_shortcode then set default props value in $args.
             */
            if( isset($atts['group_id']) && !empty($atts['group_id']) && intval($atts['group_id'])){
                
                $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
                $faq_groups_args = $faq_groups->get_default_args($atts);
                $args = array_merge($args,$faq_groups_args);
            }
            return $faq->get_view($args);
        }

    }
}

$helpie_faq_shortcodes = new \HelpieFaq\Includes\Shortcodes();

add_shortcode('helpie_faq', array($helpie_faq_shortcodes, 'basic'));
