<?php

namespace HelpieFaq\Features\Insights\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Insights\Abstract_Insight')) {
    class Abstract_Insight
    {
        public function __construct(){
            $this->date_format = 'd-m-Y';
            $this->current_timestamp = \current_time('timestamp');
            $this->current_date = date($this->date_format, $this->current_timestamp);

             /* Dependencies */
             $this->insights_helper = new \HelpieFaq\Features\Insights\Insights_Helper($this->current_date, $this->date_format);
        }

        public function get_last_30days(){
            $insights = $this->get_count_by_day();
            
            $last_30days = $this->insights_helper->get_last_30days($insights);

            return $last_30days;
        }

        public function get_last_year(){
            $insights = $this->get_count_by_day();
            
            $last_year = array();
            $labels = array();
            $values = array();
            $ii = 0;
            foreach ($insights as $date_key => $value) {
                # code...
                if( !$this->is_month_within_12_months($date_key) ) continue;
                $dateObject = date_create_from_format("Y-m", $date_key); 
                $date_label = date_format($dateObject,"M Y");
                // $date_label = date("d M",  $timestamp);

                $labels[$ii] = $date_label;
                $values[$ii] = $value;
                $ii++;
            }

            // Array order from old to newest
            $labels = array_reverse($labels);
            $values = array_reverse($values );

            $last_year['labels'] = $labels;
            $last_year['values'] = $values;
           
            // error_log('last_year: ' . print_r($last_year, true));
            return $last_year;
        }


        public function get_total_events($days){
            $total = 0;
            $total += $this->insights_helper->get_events_for_last_n_days_new($this->counter_data, $days, $this->current_timestamp, $this->date_format);
            // error_log('get_total_events $this->counter_data : ' . print_r($this->counter_data , true));
            // error_log('total: ' . $total);
            return $total;
        }

        /* Data Format: $count_by_day[13-02-19] => 8 */
        public function get_count_by_day(){
            $count_by_day = array();

            foreach($this->counter_data as $date_key => $values){
                $count_by_day[$date_key] = $this->insights_helper->get_day_count($values);
            }
            
            // error_log('$count_by_day : ' . print_r($count_by_day, true));
            return $count_by_day;
        }

        public function get_most_frequent_terms_all_time(){
            $most_clicked_posts = array();
            
            
            if( !isset($this->counter_data['all-time']) || empty($this->counter_data['all-time']))
            return $most_clicked_posts;

            $all_time = $this->counter_data['all-time'];
            $most_clicked_posts = $this->get_updated_term_frequency($most_clicked_posts, $all_time );

            $most_clicked_posts = $this->sort_descending($most_clicked_posts);
            
            // error_log(' get_most_frequent_terms_all_time $most_clicked_posts : ' . print_r($most_clicked_posts, true));
            
            return $most_clicked_posts;
        }

        protected function get_updated_term_frequency($frequency_counter, $term_values){
            foreach($term_values as $term => $value){

                // 1. Default 'value'
                if( !isset($frequency_counter[$term]) ){
                    $frequency_counter[$term] = array();
                    $frequency_counter[$term]['value'] = 0;
                }
                // error_log('allowed value : '  . $value);
                // 2. Set 'value' and 'label'
                $frequency_counter[$term]['value'] += $value;

                if(!isset($frequency_counter[$term]['label']) )
                $frequency_counter[$term]['label'] = $this->most_event_label($term);

            }

            return $frequency_counter;
        }

        protected function sort_descending($most_clicked_posts){
                // Sort Descending
            usort($most_clicked_posts, function($a, $b) {
                return $b['value'] - $a['value'];
            });

            return $most_clicked_posts; 
        }

        protected function get_most_frequent_terms_implementor($info){
            $most_clicked_posts = array();
            // error_log('$this->counter_data : '  . print_r($this->counter_data, true));
            foreach($this->counter_data as $date_key => $term_values){
                $info['date_key'] = $date_key;
                if( $this->skip_condition($info) ) continue;
                $most_clicked_posts = $this->get_updated_term_frequency($most_clicked_posts, $term_values);
            }

            $most_clicked_posts = $this->sort_descending($most_clicked_posts);


            // error_log('$most_clicked_posts : '  . print_r($most_clicked_posts, true));
            return $most_clicked_posts;
        }

        protected function skip_condition($info){
                extract($info);
                if( $count_by == 'days'){
                    $condition1 = $this->is_day_date($date_key);
                    $condition2 = $this->insights_helper->is_last_n_days($this->current_timestamp, $date_key, $days);

                    return !($condition1 && $condition2);
                }

                if( $count_by == 'months'){
                    return !$this->is_month_within_12_months($date_key) ;
                }

                return false;

        }
        public function get_most_frequent_terms_last_year(){
            $info = array('count_by' => 'months' );
            $most_clicked_posts = $this->get_most_frequent_terms_implementor($info);
            return $most_clicked_posts;
        }

        public function get_most_frequent_terms($days){
            $info = array('count_by' => 'days', 'days' => $days );
            // error_log('$info : '  . print_r($info, true));
            $most_clicked_posts = $this->get_most_frequent_terms_implementor($info);
            return $most_clicked_posts;
        }

        public function get_total_events_last_year(){

            $total = 0;
             foreach ($this->counter_data as $datekey => $values) {
                 
                 // 1. Continue condition
                 $is_month_within_12_months = $this->is_month_within_12_months($datekey);
                
                 if(!$is_month_within_12_months) continue;
 
                 foreach ($values as $key => $value) {
                     # code...
                     $total +=  $value;
                 }
             }
 
 
             return $total;
         }
         
         public function get_total_events_all_time(){
            $total = 0;

            if( !isset($this->counter_data['all-time']) || empty($this->counter_data['all-time']))
            return $total;

            $all_time = $this->counter_data['all-time'];
            foreach ($all_time  as $key => $value) $total +=  $value;

            return $total;
        }

        protected function is_day_date($date){
            $is_day_date = (strlen($date) > 8) ? true : false;

            // error_log('date: ' . $date );
            // error_log('is_day_date: ' . $is_day_date );

            if($is_day_date) return true;

            return false;
        }

         protected function is_month_within_12_months($month){
 
             if(strlen($month) > 8)   return false;
 
             if (strpos($month, '-') == false) {
                 return false;
             }
             $timestamp_12_months_ago = strtotime("-12 months");
             $month_timestamp = strtotime($month);
 
             return ($timestamp_12_months_ago  < $month_timestamp) ? true : false;
         }

    } // END CLASS
}