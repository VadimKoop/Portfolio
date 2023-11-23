<?php

namespace Stylus\Components;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Stylus\Components\Accordion')) {

    class Accordion
    {
        public function __construct()
        {}

        public function get_view($viewProps)
        {
            // error_log('$viewProps : ' . print_r($viewProps, true));
            $html = isset($viewProps['collection']['title'])?$viewProps['collection']['title']:"";
            $collectionProps = $viewProps['collection'];
            $top_level = $viewProps['collection']['display_mode'];
            if( $top_level == 'simple_accordion_category' || $top_level == 'faq_list'){
                $html = $this->get_titled_view($viewProps['items'], $collectionProps );
            } else{
                $html = $this->get_accordion($viewProps['items'], $collectionProps );
            }
            

            return $html;
        }

        public function get_titled_view($props, $collectionProps ){
            $html = '';

            for ($ii = 0; $ii < sizeof($props); $ii++) {
                $html .= "<h3>" . $props[$ii]['title'] . "</h3>";
                $html .= $this->get_accordion($props[$ii]['children'],$collectionProps);
            }

            return $html;
        }


        public function get_accordion($props,$collectionProps){

            $faq_list_class = '';
            if(isset($collectionProps['display_mode']) &&  $collectionProps['display_mode'] == 'faq_list'){
                $faq_list_class = 'faq_list';
            }
         
           
            $html = '<article class="accordion '.$faq_list_class.'">';

            for ($ii = 0; $ii < sizeof($props); $ii++) {
                $html .= $this->get_single_item($props[$ii],$collectionProps);
            }

            $html .= '</article>';

            return $html;
        }

        public function get_single_item($props,$collectionProps)
        {
            $id = isset($props['post_id']) ? "post-".$props['post_id'] : "term-".$props['term_id'];
            
            $faq_url_data_item = '';
            if(isset($collectionProps['faq_url_attribute']) && $collectionProps['faq_url_attribute'] == 1){
                $faq_url_data_item = 'hfaq-'.$id;
            }

            $accordion__header_classes = '';

            $show_accordion_body = '';
            if(isset($collectionProps['open_by_default']) && $collectionProps['open_by_default'] == 'open_all_faqs'){
                $show_accordion_body = 'display: block;';
                $accordion__header_classes .= ' active'; 
            }

            $custom_toggle_icon_content = $this->get_custom_toggle_icon($collectionProps);

            if(!empty($custom_toggle_icon_content)){
                $accordion__header_classes .= ' custom-icon';
            }

            $accordion_styles = $this->get_accordion_styles($collectionProps);
            $accordion_body_styles = $show_accordion_body.$accordion_styles['body_background'];
            
            
            $html = '<li class="accordion__item">';
            $html .= '<div class="accordion__header '.$accordion__header_classes.'" data-id="'.$id.'" data-item="'.$faq_url_data_item.'" style="'.$accordion_styles['header_background'].'">';
            $html .= '<div class="accordion__title">'.$props['title'].'</div>';
            // $html .= '<a href="#hfaq-'.esc_attr($id).'">'.$props['title'].'</a>';
            $html .= $custom_toggle_icon_content;
            $html .= '</div>';
            $html .= '<div class="accordion__body" style="'.$accordion_body_styles.'">';
           
            if(is_plugin_active('elementor/elementor.php')){
                $html .= '<p>' . apply_filters('elementor/frontend/the_content',$props['content']) . '</p>';
            }else{
                $html .= '<p>' . apply_filters('the_content',$props['content']) . '</p>';
            }

            if( isset($props['children'])){
                $html .= $this->get_accordion($props['children'],$collectionProps);
            }
            
            $html .= '</div>';
            $html .= '</li>';

            return $html;
        }

        public function get_custom_toggle_icon($collectionProps){

            $html = '';
           
            $open_all_faq_class = '';
            if(isset($collectionProps['open_by_default']) && $collectionProps['open_by_default'] == 'open_all_faqs'){
                $open_all_faq_class = ' open-all';
            }


            if(
                isset($collectionProps['toggle_icon_type']) && $collectionProps['toggle_icon_type'] == 'custom'
                && isset($collectionProps['toggle_open']) && !empty($collectionProps['toggle_open'])
                && isset($collectionProps['toggle_off']) && !empty($collectionProps['toggle_off'])
                && $collectionProps['display_mode'] != 'faq_list'
            ){
                $html .= '<span class="accordion__toggle '.$open_all_faq_class.'">';
                $html .= '<span class="accordion__toggle--open"><i class="accordion__toggle-icons '.$collectionProps['toggle_open'].'"></i></span>';
                $html .= '<span class="accordion__toggle--close"><i class="accordion__toggle-icons '.$collectionProps['toggle_off'].'"></i></span>';
                $html .= '</span>';
            }
            return $html;
        } 

        public function get_accordion_styles($collectionProps){
            
            $header_background = '';
            $body_background = '';

            if(isset($collectionProps['accordion_background']) && !empty($collectionProps['accordion_background'])){
                $header_background = 'background:'.$collectionProps['accordion_background']['header'].';';
                $body_background = 'background:'.$collectionProps['accordion_background']['body'].';';
            }
            
            return [
                'header_background' => $header_background,
                'body_background'  => $body_background
            ];
        }
        //     public function get_single_item_multilevel($props){
        //         $html = '<li>';
        //         $html .= '<div class="accordion__header">';
        //         $html .= '<div class="accordion__title">'.$props['title'].'</div>';
        //         $html .= '</div>';
        //         $html .= '<div class="accordion__body">';
        //         $html .= '<p>'.$props['content'].'</p>';
        //         $html .= $this->get_faq_dummy();
        //         $html .= '</div>';
        //         $html .= '</li>';

        //         return $html;
        //     }

        //     public function get_faq_dummy(){
        //         $html = '<div class="helpie-faq__container dark">';
        //         $html .= '<ul class="accordion">';
        //         for($ii = 1; $ii <= 2; $ii++){
        //             $html .= $this->get_single_item_dummy();
        //         }

        //         $html .= '</ul>';
        //         $html .= '</div>';

        //         return $html;
        //     }

        //    public function get_single_item_dummy(){
        //         $html = '<li>';
        //         $html .= '<div class="accordion__header">';
        //         $html .= '<div class="accordion__title">Title</div>';
        //         $html .= '</div>';
        //         $html .= '<div class="accordion__body">';
        //         $html .= '<p>Some Long Content here.</p>';
        //         $html .= '</div>';
        //         $html .= '</li>';
        //         return $html;
        //    }

    } // END CLASS
}