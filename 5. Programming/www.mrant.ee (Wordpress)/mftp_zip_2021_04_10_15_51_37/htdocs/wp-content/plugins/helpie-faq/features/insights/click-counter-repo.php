<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Click_Counter_Repo')) {
    class Click_Counter_Repo{

        public function __construct(){
            $this->meta_key = 'click_counter';
        }
        
        public function get_term_meta($id){
            $term_meta = get_term_meta( $id, $this->meta_key, true ); // 'single' parameter is true
            $term_meta = $this->empty_meta($term_meta);
            return $term_meta;
        }

        public function get_post_meta($id){
            $post_meta = get_post_meta( $id, $this->meta_key, true ); // 'single' parameter is true

            // Empty Condition
            $post_meta = $this->empty_meta($post_meta);

            return $post_meta;
        }

        // public function get_all_counter_data(){
        //     $posts = $this->get_posts();
        //     foreach($posts as $post){
        //         $counter_data[$post->ID] = $this->get_post_meta($post->ID);
        //     }

        //     error_log('counter_data: ' . print_r($counter_data, true));
        //     return $counter_data;
        // }

        public function get_all_counter_data(){
            $counter_data = array();
            $posts = $this->get_posts();
            $total_posts = 0;
			foreach($posts as $post){
                $single_counter = $this->get_post_meta($post->ID);
                $total_posts++;
                // error_log("ID: " . $post->ID);
                // error_log('$single_counter : ' . print_r($single_counter, true));
                if( !isset($single_counter) || !is_array($single_counter)) break;
                
                foreach($single_counter as $date => $value){
                    if( !isset( $counter_data[$date]) ){
                        $counter_data[$date] = array();
                    }

                   
                    $counter_data[$date][$post->ID] = $value;
                }
            }
            // error_log("total_posts: " . $total_posts);
            // error_log('$counter_data : ' . print_r($counter_data, true));
            return $counter_data;
        }



        /* PROTECTED METHODS */
        protected function empty_meta($meta){
            // Empty Condition
            if(!isset($meta) || empty($meta) || $meta  == ''){
                $meta = array();
            } 
            
            return $meta;
        }

        protected function get_posts(){
            $faq_wp_posts = get_posts(
				array(
                    'post_type' => HELPIE_FAQ_POST_TYPE,
                    'post_status' => 'publish',
                    'numberposts' => -1,
				)
            );
            
            return $faq_wp_posts;
        }

    } // END CLASS
}