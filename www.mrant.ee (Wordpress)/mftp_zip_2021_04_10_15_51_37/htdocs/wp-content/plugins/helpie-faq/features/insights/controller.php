<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Controller')) {
    class Controller
    {

        public function __construct(){
             /* Dependencies */
            $this->click_insights = new \HelpieFaq\Features\Insights\Insights\Click_Insights();
            $this->search_insights = new \HelpieFaq\Features\Insights\Insights\Search_Insights();
            // $this->clear();
        }

        public function get_insights(){
            $insights = array(
                'click' => array(
                    '7day-total' => $this->click_insights->get_total_events(7),
                    '30day-total' => $this->click_insights->get_total_events(30),
                    'year-total' => $this->click_insights->get_total_events_last_year(),
                    'all-time-total' => $this->click_insights->get_total_events_all_time(),
                    'most-7day' => $this->click_insights->get_most_frequent_terms(7),
                    'most-30day' => $this->click_insights->get_most_frequent_terms(30),
                    'most-year' => $this->click_insights->get_most_frequent_terms_last_year(),
                    'most-all-time' => $this->click_insights->get_most_frequent_terms_all_time(),
                    'last_30days' => $this->click_insights->get_last_30days(),
                    'last_year' => $this->click_insights->get_last_year()
                ),
                'search' => array(
                    '7day-total' => $this->search_insights->get_total_events(7),
                    '30day-total' => $this->search_insights->get_total_events(30),
                    'year-total' => $this->search_insights->get_total_events_last_year(),
                    'all-time-total' => $this->search_insights->get_total_events_all_time(),
                    'most-7day' => $this->search_insights->get_most_frequent_terms(7),
                    'most-30day' => $this->search_insights->get_most_frequent_terms(30),
                    'most-year' => $this->search_insights->get_most_frequent_terms_last_year(),
                    'most-all-time' => $this->search_insights->get_most_frequent_terms_all_time(),
                    'last_30days' => $this->search_insights->get_last_30days(),
                    'last_year' => $this->search_insights->get_last_year()
                ),
            );

            return $insights;
        }

        public function clear(){
            $this->click_insights->clear();
            $this->search_insights->clear();
        }
    } // END CLASS
}