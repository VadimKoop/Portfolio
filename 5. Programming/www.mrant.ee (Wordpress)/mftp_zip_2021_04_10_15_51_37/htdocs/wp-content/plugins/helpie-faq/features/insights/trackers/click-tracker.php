<?php

namespace HelpieFaq\Features\Insights\Trackers;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Trackers\Click_Tracker')) {
    class Click_Tracker extends \HelpieFaq\Features\Insights\Trackers\Event_Tracker
    {
        public function __construct(){
            $this->meta_key = 'click_counter';
            $this->event_type = 'post_meta';

            parent::__construct();
            $this->repo = new \HelpieFaq\Features\Insights\Click_Counter_Repo();
        }
        

        public function get_event_data($postData){
            return $postData;
        }
        public function get_new_count($counter_data){
            
            // Add to today's count
            $counter_data = $this->update_todays_count($counter_data);

            // Add to monthly count
            $counter_data = $this->update_current_month_count($counter_data);


            // Add to All Time count
            $counter_data = $this->update_all_time_count($counter_data);
            // error_log('new_counter_data : ' . print_r($counter_data, true));
            return $counter_data;
        }

        public function update_todays_count($counter_data){
            if( !isset($counter_data[$this->current_date]) ){
                $counter_data[$this->current_date] = 1;
            } else{
                $counter_data[$this->current_date]++;
            }
            return $counter_data;
        }

        public function update_current_month_count($counter_data){
            $current_month = $this->insights_helper->get_current_month($this->current_timestamp);
            $counter_data[$current_month] = isset($counter_data[$current_month] ) ? ($counter_data[$current_month] + 1) : 1;
            return $counter_data;
        }

        public function update_all_time_count($counter_data){
            $counter_data['all-time'] = isset($counter_data['all-time'] ) ? ($counter_data['all-time'] + 1) : 1;
            return $counter_data;
        }

        public function update_count($new_counter_data, $event_data){

            // Extracts array keys as variables
            extract($event_data);

            if( $data_type == 'post'){
                update_post_meta( $id, $this->meta_key, $new_counter_data ); 
            } else{
                update_term_meta( $id, $this->meta_key, $new_counter_data ); 
            }
        }
        
        public function get_current_count($info){
            // Extracts array keys as variables
            extract($info);
            
            $count = array();
            
            if( $data_type == 'post'){
                // $count = get_post_meta( $id, 'click_counter', false ); // 'single' parameter is false  
                $count = $this->repo->get_post_meta($id);
            } else{
                // $count = get_term_meta($id, 'click_counter', false); // 'single' parameter is false  
                $count = $this->repo->get_term_meta($id);
            }            

            return $count;
        }

        public function process_data()
        {
            $postData = array();

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $postData['id'] = sanitize_text_field($_POST['id']);
            }

            $info_indexed = explode("-", $postData['id']);

            $info = array();
            $info['data_type'] = $info_indexed[0]; // 'term' or 'post'
            $info['id'] = $info_indexed[1]; // value of term_id or post_id

            return $info;
        }
    } // END CLASS 
}