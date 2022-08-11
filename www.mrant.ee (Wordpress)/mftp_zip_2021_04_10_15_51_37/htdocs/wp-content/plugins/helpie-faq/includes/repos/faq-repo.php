<?php

namespace HelpieFaq\Includes\Repos;

/**
 * FAQ REPO
 *
 */

if (!class_exists('\HelpieFaq\Includes\Repos\Faq_Repo')) {
    class Faq_Repo
    {
        private $query;

        public function __construct()
        {
            $this->query = new \HelpieFaq\Includes\Query\Faq_Query();
        }

        public function get_faqs($args = array())
        {

            if (isset($args) && !empty($args)) {
                add_filter('helpie_faq_object_query_args', function ($query_vars) use (&$args) {
                    return wp_parse_args($args, $query_vars);
                });
            }

            return $this->query->get_faqs();
        }

        public function get_all_faqs()
        {
            return $this->get_faqs();
        }

        public function get_faq_by_category($category_id = 0)
        {

            $args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => 'helpie_faq_category',
                        'field' => 'term_id',
                        'terms' => $category_id,
                    ),
                ),
            );
            return $this->get_faqs($args);
        }

        public function get_faq_by_wiki_category()
        {
            $args = array(
                'tax_query' => array(
                    array(
                        'taxonomy' => 'helpdesk_category',
                        'field' => 'term_id',
                        'terms' => 6,
                    ),
                ),
            );
            return $this->get_faqs($args);
        }

        /* OPTIONS */

        public function get_faq_categories($args = array())
        {
            $term_args = array(
                'parent' => 0,
                'hide_empty' => false,
                'order' => isset($args['order'])? $args['order']: 'desc' 
            );


            if (isset($args['categories']) && !empty($args['categories'])) {
                $category_is_all = is_array($args['categories']) && in_array('all', $args['categories']);

                if (!$category_is_all) {
                    $term_args['include'] = $args['categories'];
                }
            }

            /* TODO: Refactor */
            $wp_query_args = $this->sort($args);
            $term_args = array_merge($wp_query_args, $term_args);
            $faq_categories = get_terms('helpie_faq_category', $term_args);
            return $faq_categories;
        }

        public function sort($args)
        {
            $wp_query_args = array();

            if (isset($args['sortby'])) {
                switch ($args['sortby']) {
                    case "alphabetical":
                        $wp_query_args['orderby'] = 'title';
                        break;
                    case "updated":
                        $wp_query_args['orderby'] = 'modified';
                        break;
                    case "user_engagement":
                        $wp_query_args['meta_key'] = 'click_counter';
                        $wp_query_args['orderby'] = 'click_counter';
                        $wp_query_args['order'] = 'DESC';
                        break;
                    case "menu_order":
                        $wp_query_args['orderby'] = 'menu_order';
                        // $wp_query_args['order'] = 'DESC';
                    case "post__in":
                        $wp_query_args['orderby'] = 'post__in';
                        $wp_query_args['order'] = 'ASC';
                    default:
                        $wp_query_args['orderby'] = 'include';
                        break;
                }
            }


            return $wp_query_args;
        } // end sort()


        public function get_faq_categories_option($show_all = false)
        {
            $faq_categories = $this->get_faq_categories();

            $faq_categories_option = array();
            foreach ($faq_categories as $category) {
                $term_id = $category->term_id;
                $faq_categories_option[$term_id] = $category->name;
            }

            if ($show_all == true) {
                $faq_categories_option = array('all' => 'All') + $faq_categories_option;
            }

            return $faq_categories_option;
        }

        public function get_options($option_field)
        {

            switch ($option_field) {

                case 'woo-products':
                    $woo_integrator = new \HelpieFaq\Includes\Woo_Integrator();
                    return $woo_integrator->get_products_option(true); // 'show_all' = false;
                    break;

                case 'kb-categories':
                    $kb_integrator = new \HelpieFaq\Lib\Kb_Integrator();
                    return $kb_integrator->get_kb_categories_option(); // 'show_all' = false;
                    break;

                case 'categories':
                    return $this->get_faq_categories_option(true); // 'show_all' = false;
                    break;
            }
        }

        public function get_faqs_by_category($args){
            
            $category_id = isset($args['term_id']) && !empty($args['term_id']) ? $args['term_id']: 0;
            

            $order = isset($args['order']) ? $args['order']: 'desc';

            $wp_query_args = $this->sort($args);
            $term_args = array_merge($wp_query_args, $args);
            
            $post_args = array(
                'post_type'		=> 'helpie_faq',
                'numberposts'   => -1,
                'order'         => $order,
                'tax_query' => array(
                    array(
                        'taxonomy'  => 'helpie_faq_category',
                        'field'     => 'term_id',
                        'terms'     => $category_id,
                        'include_children' => false 
                    )
                )
            );

            $post_args = array_merge($term_args,$post_args);
            
            $result = get_posts( $post_args );

            return $result;
        }
    } // END CLASS

}