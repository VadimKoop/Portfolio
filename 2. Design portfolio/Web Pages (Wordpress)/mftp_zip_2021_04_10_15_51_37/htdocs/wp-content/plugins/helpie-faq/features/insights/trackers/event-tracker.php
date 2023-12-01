<?php

namespace HelpieFaq\Features\Insights\Trackers;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Trackers\Event_Tracker')) {
    class Event_Tracker
    {
        public function __construct(){

            // Special line to populate previous date to test.
            // DO NOT USE in Production. Comment out
            // $current_date = date($this->date_format, strtotime(' -1 day'));
            // $test_timestamp = strtotime(' -320 day');
            // $this->current_timestamp = $test_timestamp;

            $this->date_format = 'd-m-Y';
            $this->current_timestamp = \current_time('timestamp');
            $this->current_date = date($this->date_format, $this->current_timestamp);

            /* Dependencies */
            $this->insights_helper = new \HelpieFaq\Features\Insights\Insights_Helper($this->current_date, $this->date_format);
        }
        

        public function action(){
            // 1. Get data from $_POST
            $postData = $this->process_data();

            // 2. Execute counter algo
            $count = $this->update($postData);
            
            // 3. Return ajax value
            print_r($count);

            wp_die(); // this is required to terminate immediately and return a proper response
            wp_reset_postdata();
        }

        
        /* Main Update Methods */
        public function update($postData)
        {
           

            // 1. Get Previous Search Count Data for Today
            $counter_data = $this->get_current_count($postData );
            // error_log('TRACKER: counter_data: ' . print_r($counter_data, true));

            // 2. Event Data
            $event_data = $this->get_event_data($postData);

            // 3. New Count
            $new_counter_data = $this->get_new_count($counter_data, $event_data);
            // error_log('TRACKER: new_counter_data: ' . print_r($new_counter_data, true));

            // 4. Update Count
            $this->update_count($new_counter_data, $event_data );

            // 5. Return New Search Insights
            return $new_counter_data;
        }


    } // END CLASS 
}