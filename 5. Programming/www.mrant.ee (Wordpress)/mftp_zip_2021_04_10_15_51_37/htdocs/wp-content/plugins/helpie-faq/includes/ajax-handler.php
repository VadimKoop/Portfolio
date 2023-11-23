<?php

namespace HelpieFaq\Includes;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly

if ( !class_exists( '\\HelpieFaq\\Includes\\Ajax_Handler' ) ) {
    class Ajax_Handler
    {
        public function __construct()
        {
        }
        
        public function action()
        {
            $this->insights_controller = new \HelpieFaq\Features\Insights\Controller();
            $this->insights_controller->clear();
        }
    
    }
    // END CLASS
}

$ajax_hanlder = new \HelpieFaq\Includes\Ajax_Handler();
$click_tracker = new \HelpieFaq\Features\Insights\Trackers\Click_Tracker();
$search_tracker = new \HelpieFaq\Features\Insights\Trackers\Search_Tracker();
add_action( 'wp_ajax_helpie_faq_click_counter', array( $click_tracker, 'action' ) );
add_action( 'wp_ajax_nopriv_helpie_faq_click_counter', array( $click_tracker, 'action' ) );
add_action( 'wp_ajax_helpie_faq_search_counter', array( $search_tracker, 'action' ) );
add_action( 'wp_ajax_nopriv_helpie_faq_search_counter', array( $search_tracker, 'action' ) );
add_action( 'wp_ajax_helpie_faq_reset_insights', array( $ajax_hanlder, 'action' ) );
add_action( 'wp_ajax_nopriv_helpie_faq_reset_insights', array( $ajax_hanlder, 'action' ) );