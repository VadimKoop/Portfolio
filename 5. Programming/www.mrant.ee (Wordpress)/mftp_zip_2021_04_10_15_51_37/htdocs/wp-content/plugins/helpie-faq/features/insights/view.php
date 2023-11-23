<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\View')) {
    class View
    {

        public function __construct(){
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));    
        }

        public function enqueue_scripts(){
            wp_enqueue_script(HELPIE_FAQ_DOMAIN . '-chartist', HELPIE_FAQ_URL . 'lib/chartist/chartist.min.js', array('jquery'), HELPIE_FAQ_VERSION, 'all');
            wp_enqueue_style(HELPIE_FAQ_DOMAIN . '-chartist-style', HELPIE_FAQ_URL . 'lib/chartist/chartist.min.css', array(), HELPIE_FAQ_VERSION, 'all');
            wp_enqueue_style(HELPIE_FAQ_DOMAIN . '-chartist-style', HELPIE_FAQ_URL . 'lib/chartist/chartist.min.css', array(), HELPIE_FAQ_VERSION, 'all');
        }

        public function get_view($insights){
            $this->insights = $insights;
            $html = "<div class='helpie-faq dashboard'>";
            $html .= $this->controls();
            $html .= $this->tab_menu();
            $html .= $this->tab_body();

            $html .= "</div>";
            
            return $html;
        }

        public function controls(){
            $text =  __('Reset All Insights', 'helpie-faq');

            $html = '';

            
            $html .= get_submit_button( $text, 'delete', 'helpie_faq_delete' );
            return $html;
        }

        public function tab_menu(){
            $html = '
            <main>

            <input id="tab1" type="radio" name="tabs" checked>
            <label for="tab1">7 Days</label>

            <input id="tab2" type="radio" name="tabs">
            <label for="tab2">30 Days</label>

            <input id="tab3" type="radio" name="tabs">
            <label for="tab3">1 Year</label>

            <input id="tab4" type="radio" name="tabs">
            <label for="tab4">All Time</label>
            ';
            return $html;
        }



        public function tab_body(){
            $html  =     $this->get_section('7day', 1);
            $html .=      $this->get_section('30day', 2);
            $html .=     $this->get_section('year', 3);
            $html .=     $this->get_section('all-time', 4);
            return $html;
        }


        protected function get_section($time, $id){
            $html = '<section id="content'.$id.'">';
            $html .= $this->get_total_card($time);

            // TODO: implement event graph for 'all-time'
            if($id != 4)
            $html .= $this->get_graph($time);

            $html .= $this->get_most_frequent_list_card($time);
            $html .= '</section>';
            return $html;
        }

        protected function get_total_card($time){
            $html = "<h2>Total Events</h2>";
            $html .= '<div class="card-list"><div class="row flex-grid">';
            foreach($this->insights as $event_key => $insight){
                $html .= '<p>'.$this->get_stats_view($insight, $time, $event_key).'</p>';
            }
            $html .= ' </div></div>';
            return $html;
        }

        protected function get_graph($time){
            $html = "<h2>Events Graph</h2>";
            $html .= '<p><div class="ct-chart ct-chart-'.$time.' ct-perfect-fourth"></div></p>';
            return $html;
        }
        protected function get_most_frequent_list_card($time){
            $html = "<h2>Total Events</h2>";
            $html .= '<div class="card-list most-frequent"><div class="row flex-grid">';
           
            foreach($this->insights as $event_key => $insight){
               
                $html .= '<p>'.$this->get_most_events($insight, $time, $event_key).'</p>';
            }
            $html .= ' </div></div>';
            return $html;
        }




           

        public function get_most_events($insight, $key, $event_key){
            $html = '<div class="column"><div class="card '.$event_key.'">';
            $html .= "<h4 class='card-title'>".$event_key."</h4>";
            $most_events_list = $insight['most-'.$key];
            // error_log('$insight : '  . print_r($insight, true));
            for($ii = 0; $ii < sizeof($most_events_list); $ii++){
                $html .= '<div class="row">';
                $html .= '<span class="label">'.$most_events_list[$ii]['label'].'</span>';
                $html .=  '<span class="value"> - '.$most_events_list[$ii]['value'].'</span>';
                $html .=  '</div>';

            }

            $html .=  '</div></div>';

            return $html;
        }

        public function get_stats_view($insight, $key, $event_key){

            $icon_code = ($event_key == 'click')? "fa-hand-pointer-o" : "fa-search";
            $html = '                
                <div class="column">
                    <div class="card stat-card '.$event_key.'">
                        <span class="stat-icon">
                            <i class="fa '.$icon_code.'" aria-hidden="true"></i>
                        </span>
                        <div class="value">'.$insight[$key.'-total'].'</div>
                        <div class="title">'.$event_key.'</div>
                        
                    </div>
                </div>      
            ';

            // TODO: Implement increase code : <div class="stat increase"><b><i class="fa fa-angle-up"></i>13</b>% increase</div>
            return $html;
        }
    } // END CLASS
}