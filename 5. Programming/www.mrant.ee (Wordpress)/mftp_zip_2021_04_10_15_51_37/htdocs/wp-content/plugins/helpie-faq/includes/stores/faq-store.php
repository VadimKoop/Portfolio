<?php

namespace HelpieFaq\Includes\Stores;

/**
 * Abstract Class: Store
 *
 */

if (!class_exists('\HelpieFaq\Includes\Store\Faq_Store')) {
    class Faq_Store extends \HelpieFaq\Includes\Abstracts\Store
    {

        /* Must Have filter() and sort() methods */
        public function filter($args)
        {
            $this->filter_faq_categories($args);
            $this->filter_faq_tags($args);
            $this->filter_limit($args);
            $this->filter_kb_categories($args);
            $this->filter_woo_products($args);
            $this->filter_product_only_faqs($args);
            $this->filter_faq_group_categories($args);
            
        } // END filter()

        public function sort($args)
        {

            if (isset($args['sortby'])) {

                // error_log(' sortby : ' . $args['sortby']);

                switch ($args['sortby']) {
                    case "alphabetical":
                        $this->wp_query_args['orderby'] = 'title';
                        break;
                    case "updated":
                        $this->wp_query_args['orderby'] = 'modified';
                        break;
                    case "user_engagement":
                        $this->wp_query_args['meta_key'] = 'click_counter';
                        $this->wp_query_args['orderby'] = 'click_counter';
                        $this->wp_query_args['order'] = 'DESC';
                        break;
                    case "menu_order":
                        $this->wp_query_args['orderby'] = 'menu_order';
                        $this->wp_query_args['order'] = 'DESC';
                        break;
                    case "post__in":
                        $this->wp_query_args['orderby'] = 'post__in';
                        $this->wp_query_args['order'] = 'ASC';
                        break;
                    default:
                        $this->wp_query_args['orderby'] = 'include';
                        break;
                }
            }
        } // end sort()

        /* PROTECTED METHODS */

        protected function filter_faq_categories($args)
        {
            if (isset($args['categories']) && $args['categories'] != 'all' 
                && (!isset($args['group_id']) && empty($args['group_id'])) ) {
                // Because Elementor gives array but shortcode and widgets give string
                $terms_array = $this->to_array($args['categories']);
                $this->add_tax_query('helpie_faq_category', $terms_array);
            }
        }

        protected function filter_faq_tags($args)
        {
            if (isset($args['tags']) && $args['tags'] != '') {
                // Because Elementor gives array but shortcode and widgets give string
                $terms_array = $this->to_array($args['tags']);
                $this->add_tax_query('helpie_faq_tag', $terms_array);
            }
        }

        protected function filter_limit($args)
        {
            if (isset($args['limit']) && $args['limit'] != '' && $args['limit'] != '-1') {
                $this->wp_query_args['posts_per_page'] = $args['limit'];
            }
        }

        protected function filter_woo_products($args)
        {
            $woo_integrator = new \HelpieFaq\Includes\Woo_Integrator();
            if ($woo_integrator->is_woocommerce_activated()) {
                if (isset($args['products']) && $args['products'] != 'all') {
                    $this->add_meta_query('helpie_woo_metabox', $args['products']);
                }
            }
        }
        protected function filter_kb_categories($args)
        {

            /* NOTE: Check for Helpie KB Plugin */
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            if (\is_plugin_active('helpie/helpie.php')) {
                if (isset($args['kb_categories']) && $args['kb_categories'] != 'all') {

                    // Because Elementor gives array but shortcode and widgets give string
                    $terms_array = $this->to_array($args['kb_categories']);

                    $this->add_tax_query('helpdesk_category', $terms_array);
                }
            }
        }

        protected function filter_product_only_faqs($args){
            

            if (empty(is_singular('product')) && 
                ( isset($args['product_only']) && ($args['product_only'] === 'on' || $args['product_only']  === true || $args['product_only'] == 1)))
                {

                $this->add_product_only_query('helpie_woo_metabox');
            }

        }

        protected function add_tax_query($key, $terms_array)
        {

            $this->wp_query_args['tax_query'][] = array(
                'taxonomy' => $key,
                'field' => 'term_id',
                'terms' => $terms_array,
                'include_children' => false,
            );
        }

        protected function add_meta_query($key, $value)
        {

            $this->wp_query_args['meta_query'] = array(
                array(
                    'key' => $key,
                    'value' => $value,
                    'compare' => 'LIKE',
                ),
            );
        }
        

        protected function add_product_only_query($key)
        {

            $this->wp_query_args['meta_query'] = array(
                array(
                    'key' => $key,
                    'value' => array(''),
                    'compare' => 'NOT IN',
                ),
            );
        }

        protected function filter_faq_group_categories($args){
            if(isset($args['group_id']) && !empty($args['group_id']) ) {
                // Get PostsIds from faq group
                $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
                $post_ids = $faq_groups->get_post_ids_from_faq_group($args);
                
                // Recevied Empty post_ids , then set array value as 0
                if(empty($post_ids)){
                    $post_ids = array(0);
                }
                
                $terms_array = $this->to_array($args['group_id']);
                // add tax query and post_ids
                $this->add_tax_query('helpie_faq_group', $terms_array);
                $this->wp_query_args['post__in'] = $post_ids;
            }
        }
        /**
         *
         * Converts to array if string is given with ","
         * Needed Because Elementor gives array but shortcode and widgets give string
         *
         */

        protected function to_array($value)
        {

            if (!is_array($value)) {
                $array = explode(",", $value);
            } else {
                $array = $value;
            }

            return $array;
        }
    } // END CLASS
}
