<?php

namespace HelpieFaq\Features\Insights\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// TODO: Pre-load post for all methods in the correct loading point ( hook )

if (!class_exists('\HelpieFaq\Features\Insights\Insights\Click_Insights')) {
    class Click_Insights extends \HelpieFaq\Features\Insights\Insights\Abstract_Insight
    {
        private $meta_key = 'click_counter';
        

        public function __construct(){
            parent::__construct();
            $this->repo = new \HelpieFaq\Features\Insights\Click_Counter_Repo();

            // data-format: $counter_data[$date][$post_id] = $clicks;
            $this->counter_data = $this->repo->get_all_counter_data();
            // error_log('$this->counter_data : ' . print_r($this->counter_data, true));
        }

        public function most_event_label($post_id){
            return get_the_title($post_id);
        }

        public function clear(){
            delete_post_meta_by_key($this->meta_key);
            // \delete_term_meta_by_key($this->meta_key);
            \delete_metadata( 'term', 0, $this->meta_key, '', true );
        }




    } // END CLASS

}