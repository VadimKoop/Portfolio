<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Notifications')) {
    class Notifications
    {
        public function __construct()
        {
            $namespace = 'helpie_faq_rating_';
            $this->maybe_param = $namespace . 'maybe_later';
            $this->dismiss_param = $namespace . 'notice_dismissed';

            
            if ( $this->notice_display_conditions() ) {
                add_action('admin_notices', array($this, 'sample_admin_notice__success'));
            }
        }

        public function get_required_faq_count(){
            $maybe_option_saved = get_option($this->maybe_param);

            $required_faq_count = 10;

            if( isset($maybe_option_saved) && 0 < $maybe_option_saved){
                $required_faq_count = $maybe_option_saved + 20;
            }
            // error_log('required_faq_count:  ' . $required_faq_count );
            return $required_faq_count;
        }


        public function notice_display_conditions(){

            /* CONDITION 1 */
            $option_saved = get_option($this->dismiss_param);
            if (isset($option_saved) && $option_saved == true) {
                return false;
            }

            /* CONDITION 2 */
            $required_faq_count = $this->get_required_faq_count();
            $number_of_faqs = $this->get_faq_count();
            if ( $number_of_faqs <= $required_faq_count) {
                return false;
            }

            return true;
        }

        public function get_faq_count(){
            $results = get_posts(array('post_type' => 'helpie_faq', 'post_status' => 'publish', 'posts_per_page' => -1));
            $number_of_faqs = count($results);
            return $number_of_faqs;
        }

        public function notice_click_handler()
        {
            /* 1. If button clicked is 'Dismiss' button */
            if (isset($_GET[$this->dismiss_param ])) {
                update_option($this->dismiss_param , true);
                $escape_uri = remove_query_arg($this->dismiss_param);
                $this->restore_refresh_page($escape_uri);
            }

            /* 2. If button clicked is 'Maybe Later' button */
            if (isset($_GET[$this->maybe_param ])) {
                $number_of_faqs = $this->get_faq_count();
                update_option($this->maybe_param , $number_of_faqs);
                $escape_uri = remove_query_arg($this->maybe_param);
                $this->restore_refresh_page($escape_uri);
            }
        }

        protected function restore_refresh_page($escape_uri)
        {

            echo "<script type='text/javascript'>
               window.location=document.location.href='" . $escape_uri . "';
            </script>";
        }

        public function sample_admin_notice__success()
        {
            $this->notice_click_handler();
            $required_faq_count = $this->get_required_faq_count();
            $html = "<div class='helpie-notice notice notice-success is-dismissible'>";
            $rounded_faqs = round($required_faq_count, -1);;
            $html .= "<p>" . "<b>".__("Congrats!")." ".$rounded_faqs."+" .__("FAQs Created: ")."</b>" . __("Hey, I noticed you have created more than") . " " .$rounded_faqs." " . __("FAQs using Helpie FAQ - that's awesome! ", 'helpie-faq') . "</p>";
            $html .= "<p>" . __("Could you do me a favor and rate us with 5-stars. It would be such a motivation for us to keep improving the plugin.", 'helpie-faq') . "</p>";
            $html .= "<p><a class='success-button' target='_blank' href='https://wordpress.org/support/plugin/helpie-faq/reviews/#new-post'>OK. You deserve it</a>";
            $html .= "<p><a href='" . add_query_arg(array($this->maybe_param => 'true')) . "'>Nope. Maybe later</a>";
            $html .= "<p><a href='" . add_query_arg(array($this->dismiss_param => 'true')) . "'>I already did</a>";
            $html .= "</div>";

            echo $html;
        }

    }
}
