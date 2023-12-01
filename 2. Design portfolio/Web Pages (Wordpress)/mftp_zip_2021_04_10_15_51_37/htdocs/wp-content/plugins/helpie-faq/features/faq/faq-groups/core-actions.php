<?php

namespace HelpieFaq\Features\Faq\Faq_Groups;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Faq\Faq_Groups\Core_Actions')) {
    class Core_Actions{

        public function __construct(){

            $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
            
            add_action('edit_post', function($postId) use($faq_groups){
                global $post;
                if (isset($post) && $post->post_type != HELPIE_FAQ_POST_TYPE) {
                    return;
                }
                $faq_groups->update_faq_group_items_using_faq_posts($postId); 
            });

            add_action('wp_trash_post', function($postId)  use($faq_groups){
                global $post;
                if (isset($post) && $post->post_type != HELPIE_FAQ_POST_TYPE) {
                    return;
                }
                $faq_groups->remove_post_from_faq_group_item($postId); 
            });

        }
        
    }
}