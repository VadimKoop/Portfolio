<?php

namespace HelpieFaq\Features\Faq\Dynamic_Widget;

require_once HELPIE_FAQ_PATH . 'lib/widgetry/model.php';
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('HelpieFaq\Features\Faq\Dynamic_Widget\Faq_Model')) {
    class Faq_Model
    {
        public $top_level = '';

        public function __construct()
        {
            $this->faq_repo = new \HelpieFaq\Includes\Repos\Faq_Repo();
            $this->style_config = new \HelpieFaq\Features\Faq\Style_Config_Model();
            $this->fields_model = new \HelpieFaq\Features\Faq\Dynamic_Widget\Fields_Model();
        }

        public function get_viewProps($args)
        {

            // $args = $this->get_default_args($args);
            /* Set $this->top_level */

            /* Get top level item objs */
            if ($this->top_level == 'categories') {
                $items_wp_objs = $this->faq_repo->get_faq_categories($args);
            } else {
                $items_wp_objs = $this->faq_repo->get_faqs($args);
            }

            // FAQ Categories Props
            $items_props = $this->get_items_props($items_wp_objs, $args);

            if ($this->top_level == 'categories') {
                // Remove empty category faqs 
                $items_props = $this->get_non_empty_items_props($items_props);
            }

            $viewProps = array(
                'collection' => $this->get_collection_props($args),
                'items' => $items_props
            );

            // error_log('viewProps : ' . print_r($viewProps, true));

            return $viewProps;
        }

        public function get_style_config()
        {
            return $this->style_config->get_config();
        }

        public function get_fields()
        {
            return $this->fields_model->get_fields();
        }

        // public function get_settings()
        // {
        //     return $this->settings->main_page->get_article_listing_settings();
        // }



        public function get_default_args()
        {
            // First Layer: Defaults
            $default_args = $this->fields_model->get_default_args();
            // Second Layer: Helpie FAQ Settings Values
            $settings = new \HelpieFaq\Includes\Settings\Getters\Getter();
            $view_settings = $settings->get_settings();
            $custom_settings = $this->get_custom_settings($view_settings);

            $view_settings = array_intersect_key($view_settings, $default_args);
            $args = array_merge($default_args, $view_settings);

            // merging custom settings args
            $args = array_merge($args,$custom_settings);


            // error_log('$input_args : ' . print_r($input_args, true));
            // error_log('$view_settings : ' . print_r($view_settings, true));
            // error_log('$default_args : ' . print_r($default_args, true));
            // error_log('$args : ' . print_r($args, true));

            return $args;
        }

        public function get_field($field_name)
        {
            $fields = $this->get_fields();
            return $fields[$field_name];
        }

        /* PROTECTED METHODS */

        // protected function append_fallbacks($args)
        // {
        //     return $args;
        // }

        protected function get_items_props($faq_wp_objs, $args)
        {
            $itemsProps = array();
            $count = 0;

            foreach ($faq_wp_objs as $faq_wp_obj) {
                // $faq_wp_obj = new \Helpie\Includes\Models\Kb_Article($article);
                if ($this->top_level == 'categories') {
                    $itemsProps[$count] = $this->map_domain_props_category($faq_wp_obj);
                    $term_id = $itemsProps[$count]['term_id'];

                    $cat_faq_args = array_merge($args, array('categories' => $term_id));
                    $wp_faqs_children = $this->faq_repo->get_faqs($cat_faq_args);

                    $this->top_level = 'articles';
                    $itemsProps[$count]['children'] = $this->get_items_props($wp_faqs_children, $args);
                    $this->top_level = 'categories';
                } else {
                    $itemsProps[$count] = $this->map_domain_props_to_view_item_props($faq_wp_obj);
                }

                $count++;
            }

            return $itemsProps;
        }

        protected function map_domain_props_category($faq_wp_obj)
        {

            $itemProps = array(
                'title' => $faq_wp_obj->name,
                'content' => $faq_wp_obj->description,
                'term_id' => $faq_wp_obj->term_id,
                'count' => get_term_meta($faq_wp_obj->term_id, 'click_counter', false)
            );

            // error_log('$itemProps : ' . print_r($itemProps, true));

            return $itemProps;
        }

        protected function map_domain_props_to_view_item_props($faq_wp_obj)
        {

            $itemProps = array(
                'title' => $faq_wp_obj->post_title,
                'content' => $faq_wp_obj->post_content,
                'post_id' => $faq_wp_obj->ID,
                'count' => get_post_meta($faq_wp_obj->ID, 'click_counter', false)
            );

            // error_log('$itemProps : ' . print_r($itemProps, true));
            return $itemProps;
        }

        protected function get_collection_props($args)
        {

            $collectionProps = array(
                'context' => $this->get_context(),
                'title' => "FAQ Added Via Elementor",
                'display_mode' => 'simple_accordion'
            );

            $collectionProps = array_merge($collectionProps, $args);

            return $collectionProps;
        }

        protected function show_user_name()
        {
            return $this->settings->single_page->show_user_name();
        }

        protected function get_context()
        {
            $context = array();
            // Woo Product
            if (is_singular('product') && is_single(get_the_ID())) {
                $context['woo-product'] = get_queried_object()->ID;
            } elseif (is_tax('helpdesk_category')) {
                $context['kb-category'] = get_queried_object()->term_id;
            }
            // Wiki KB Category

            return $context;
        }

        public function get_non_empty_items_props($props)
        {

            foreach ($props as $key => $prop) {
                if (empty($prop['children'])) {
                    unset($props[$key]);
                }
            }
            $props = array_values($props); // 'reindex' array (edited) 

            return $props;
        }

        public function get_custom_settings($view_settings){
            $settings_handler = new \HelpieFaq\Features\Faq\Settings_Handlers();
            $args = $settings_handler->get_custom_settings($view_settings);
            return $args;
        }
    }
}