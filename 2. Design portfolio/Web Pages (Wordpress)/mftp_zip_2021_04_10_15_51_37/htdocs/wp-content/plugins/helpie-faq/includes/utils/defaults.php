<?php

namespace HelpieFaq\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Includes\Utils\Defaults')) {
    
    class Defaults{
        
        public function load_default_contents(){

            $args = array('post_type' => 'helpie_faq', 'post_status' => array('publish', 'pending', 'trash'));
            $the_query = new \WP_Query($args);
            $faq_group_id = '';
            
            // Create Post only if it does not already exists
            if ($the_query->post_count < 1) {
                /* Setup Demo FAQ Question And Answer */
                $utils_helper = new \HelpieFaq\Includes\Utils\Helpers();
                /* Insert FAQ Group Term with Post */
                $post = $utils_helper->insert_term_with_post("helpie_faq", "Getting Started", "helpie_faq_group", "Yours First FAQ Question", "Yours relevent questions answer.");
                $faq_group_id = isset($post[1]) ? $post[1] : '';   
                if(isset($post[0]) && !empty($post[0])){
                    $post = get_post($post[0]);
                    /* Inserting FAQ Groups Term-Metadata */
                    $utils_helper->insert_faq_group_metadata($post,$faq_group_id);   
                }   
            }
            $this->create_page_on_activate($faq_group_id);
            wp_reset_postdata();
        }

        public function create_page_on_activate($faq_group_id)
        {
            $create_page = new \HelpieFaq\Includes\Utils\Create_Pages();
            $content = "[helpie_faq]";
            if(!empty($faq_group_id)){
                $content = "[helpie_faq group_id='".$faq_group_id."'/]";
            }
            $create_page::create('helpie_faq_page', 'helpie_faq_page_id', 'Helpie FAQ', $content);
        }
    }
}