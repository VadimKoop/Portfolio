<?php

namespace HelpieFaq\Includes\Services;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Services\Schema_Snippet')) {
    class Schema_Snippet{


        public function load_helpie_faq_schema_snippet(){
            global $post;
            $schema_snippet = '';
            
            /** Check and generate the schema snippet for single helpie_faq post  */
            if (isset($post) && is_single() && get_post_type() == 'helpie_faq') {
                $schema_snippet = $this->get_single_helpie_faq_schema_snippet();
                echo $schema_snippet;
                return;
            }

            $schema_generator = new \HelpieFaq\Includes\Services\Schema_Generator();
            $helpie_faq_schema_props = $schema_generator->get();
            if(empty($helpie_faq_schema_props)){
                return;
            }
            
            $faq_items  = $schema_generator->get_faq_items($helpie_faq_schema_props);
            $schema_snippet = $this->get_schema_snippet($faq_items);
            echo $schema_snippet;
        }

        public function get_single_helpie_faq_schema_snippet(){
            global $post;
            $helpie_faq_schema_props = array();
            $post_content = $post->post_content;
            
            $schema_generator = new \HelpieFaq\Includes\Services\Schema_Generator();
            
            /** Check the shortcode tags from the helpie post content. If found the get schema probs values. */
            if(has_shortcode($post_content ,'helpie_faq')){
                $helpie_faq_schema_props = $schema_generator->get();
            }
            
            /** Remove all shortcode tags from the helpie post content. */
            $post_content = strip_shortcodes($post_content);

            // Get Default Setting and Set display_mode 
            $settings = new \HelpieFaq\Includes\Settings\Getters\Getter();
            $options = $settings->get_settings();
        
            $options['display_mode'] = 'simple_accordion';
           
            /** build the single helpie post schema props */
            $post_schema_props = [];
            $post_schema_props['collection']  = $options;
            $post_schema_props['items'][] = array(
                'title'     => $post->post_title,
                'content'   => $post_content,
                'post_id'   => $post->ID
            );
            
            array_push($helpie_faq_schema_props,$post_schema_props);

            $faq_items  = $schema_generator->get_faq_items($helpie_faq_schema_props);
            $schema_snippet = $this->get_schema_snippet($faq_items);
            return $schema_snippet;
        }

        private function get_schema_snippet($faq_items){
            $schema = new \HelpieFaq\Includes\Services\Schema_Service();
            $schema_snippet = $schema->get_faq_schema_snippet($faq_items);
            return $schema_snippet;
        }
    }
}