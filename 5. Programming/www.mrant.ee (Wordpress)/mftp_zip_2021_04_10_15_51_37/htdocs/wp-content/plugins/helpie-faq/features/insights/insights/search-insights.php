<?php

namespace HelpieFaq\Features\Insights\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// TODO: Pre-load post for all methods in the correct loading point ( hook )

if (!class_exists('\HelpieFaq\Features\Insights\Search\Search_Insights')) {
    class Search_Insights extends \HelpieFaq\Features\Insights\Insights\Abstract_Insight
    {
        private $option_name = 'helpie_faq_searches';
        

        public function __construct(){
            parent::__construct();

            // $counter_data[$searchTerm][$date]
            $counter_data = get_option( $this->option_name );

             // $counter_data[$searchTerm][$date]
            $this->counter_data = array();

            if(!isset($counter_data) || empty($counter_data)) return;
            foreach($counter_data as $searchTerm => $values){

                foreach($values as $datekey => $value){
                    
                    // Defaults
                    if(!isset($this->counter_data[$datekey])) $this->counter_data[$datekey] = array();
                    if(!isset($this->counter_data[$datekey][$searchTerm])) $this->counter_data[$datekey][$searchTerm] = 0;

                    // Increment
                    $this->counter_data[$datekey][$searchTerm] += $value;
                }
            }
            // error_log('Search_Insights $this->counter_data : ' . print_r($this->counter_data , true));
        }

        public function most_event_label($searchTerm){
            return $searchTerm;
        }

        public function clear(){
            delete_option($this->option_name);
        }

    } // END CLASS

}