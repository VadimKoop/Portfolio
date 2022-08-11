<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Cpt')) {
    class Cpt
    {
        private $post_type_name = HELPIE_FAQ_POST_TYPE;

        /* Register post type in init Hook */
        public function register()
        {
            
            add_action('init', array($this, 'register_post_type_with_taxonomy'));
            add_action('init', array($this, 'show_other_cpt_and_tax'));

            $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
            add_action('init', array($faq_groups, 'load_hooks'));
        }

        /* Register post type on activation hook cause can't call other filter and actions */
        public function register_helpie_faq_cpt()
        {
            $this->register_post_type_with_taxonomy();
        }

        public function register_post_type_with_taxonomy()
        {

            $helpie_faq_model = new \HelpieFaq\Includes\Core\Helpie_Faq_Model();


            $labels = array(
                'name' => _x('FAQs', 'post type general name', 'helpie-faq'),
                'singular_name' => _x('FAQ', 'post type singular name', 'helpie-faq'),
                'menu_name' => _x('Helpie FAQ', 'admin menu', 'helpie-faq'),
                'name_admin_bar' => _x('FAQ', 'add new on admin bar', 'helpie-faq'),
                'add_new' => _x('Add New', 'FAQ', 'helpie-faq'),
                'add_new_item' => __('Add New FAQ', 'helpie-faq'),
                'new_item' => __('New FAQ', 'helpie-faq'),
                'edit_item' => __('Edit FAQ', 'helpie-faq'),
                'update_item' => __('Update FAQ', 'helpie-faq'),
                'view_item' => __('View FAQ', 'helpie-faq'),
                'all_items' => __('All FAQs', 'helpie-faq'),
                'search_items' => __('Search FAQs', 'helpie-faq'),
                'not_found' => __('No FAQs found', 'helpie-faq'),
                'parent_item_colon' => __('Parent FAQs:', 'helpie-faq'),
                'not_found' => __('No FAQs found.', 'helpie-faq'),
                'not_found_in_trash' => __('No FAQs found in Trash.', 'helpie-faq'),
                'items_list' => __('FAQ Items list', 'helpie-faq'),
                'items_list_navigation' => __('FAQ Items list Navigation', 'helpie-faq'),
                'filter_items_list' => __('Filter FAQ Items list', 'helpie-faq'),
            );

            $cpt_slug = $helpie_faq_model->get_cpt_slug();
            
            $args = array(
                'labels' => $labels,
                'public' => true,
                'menu_position' => 26,
                'menu_icon' => 'dashicons-feedback',
                'show_in_nav_menus' => false,
                'show_in_rest' => true,
                'map_meta_cap' => true,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats', 'thumbnail', 'author'),
                'rewrite' => array('slug' => $cpt_slug, 'with_front' => false),
            );

            register_post_type($this->post_type_name, $args);
            $this->register_category();
            // $this->register_tag();
            $this->register_faq_group();

            flush_rewrite_rules();
        }

        public function register_category()
        {
            $labels = array(
                'name' => _x('FAQ Categories', 'taxonomy general name', 'helpie-faq'),
                'singular_name' => _x('FAQ Category', 'taxonomy singular name', 'helpie-faq'),
                'search_items' => __('Search FAQ Categories', 'helpie-faq'),
                'all_items' => __('All FAQ Categories', 'helpie-faq'),
                'parent_item' => __('Parent FAQ Category', 'helpie-faq'),
                'parent_item_colon' => __('Parent FAQ Category:', 'helpie-faq'),
                'edit_item' => __('Edit FAQ Category', 'helpie-faq'),
                'update_item' => __('Update FAQ Category', 'helpie-faq'),
                'add_new_item' => __('Add New FAQ Category', 'helpie-faq'),
                'new_item_name' => __('New FAQ Category Name', 'helpie-faq'),
                'menu_name' => __('FAQ Category', 'helpie-faq'),
            );

            $args = array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'helpie_faq_category', 'with_front' => false),
            );

            register_taxonomy('helpie_faq_category', array($this->post_type_name), $args);
        }

        public function register_tag()
        {
            $labels = array(
                'name' => _x('FAQ Tags', 'taxonomy general name', 'helpie-faq'),
                'singular_name' => _x('FAQ Tag', 'taxonomy singular name', 'helpie-faq'),
                'search_items' => __('Search FAQ Tags', 'helpie-faq'),
                'all_items' => __('All FAQ Tags', 'helpie-faq'),
                'parent_item' => __('Parent FAQ Tag', 'helpie-faq'),
                'parent_item_colon' => __('Parent FAQ Tag:', 'helpie-faq'),
                'edit_item' => __('Edit FAQ Tag', 'helpie-faq'),
                'update_item' => __('Update FAQ Tag', 'helpie-faq'),
                'add_new_item' => __('Add New FAQ Tag', 'helpie-faq'),
                'new_item_name' => __('New FAQ Tag Name', 'helpie-faq'),
                'menu_name' => __('FAQ Tag', 'helpie-faq'),
            );

            $args = array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => array('slug' => 'helpie_faq_tag', 'with_front' => false),
            );

            register_taxonomy('helpie_faq_tag', array($this->post_type_name), $args);
        }

        public function register_faq_group(){
            $labels = array(
                'name' => _x('FAQ Groups', 'taxonomy general name', HELPIE_FAQ_DOMAIN),
                'singular_name' => _x('FAQ Group', 'taxonomy singular name', HELPIE_FAQ_DOMAIN),
                'search_items' => __('Search FAQ Groups', HELPIE_FAQ_DOMAIN),
                'all_items' => __('All FAQ Groups', HELPIE_FAQ_DOMAIN),
                'parent_item' => __('Parent FAQ Group', HELPIE_FAQ_DOMAIN),
                'parent_item_colon' => __('Parent FAQ Group:', HELPIE_FAQ_DOMAIN),
                'edit_item' => __('Edit FAQ Group', HELPIE_FAQ_DOMAIN),
                'update_item' => __('Update FAQ Group', HELPIE_FAQ_DOMAIN),
                'add_new_item' => __('Save', HELPIE_FAQ_DOMAIN),
                'new_item_name' => __('New FAQ Group Name', HELPIE_FAQ_DOMAIN),
                'menu_name' => __('All FAQ Groups', HELPIE_FAQ_DOMAIN),
            );

            $args = array(
                'hierarchical' => false,
                'labels' => $labels,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'parent_item' => null,
                'parent_item_colon' => null,
                'meta_box_cb'   => false,
                'rewrite' => array('slug' => 'helpie_faq_group', 'with_front' => false),
            );

            register_taxonomy('helpie_faq_group', array($this->post_type_name), $args);
        }

        public function show_other_cpt_and_tax()
        {
            if (taxonomy_exists('helpdesk_category')) {
                register_taxonomy_for_object_type('helpdesk_category', $this->post_type_name);
            }

            /* Registering Woocommerce Products Meta */
            $woo_integrator = new \HelpieFaq\Includes\Woo_Integrator();
            $woo_integrator->show_woo_products_meta();

            if ($woo_integrator->is_woocommerce_activated()) {
                $this->show_woo_columns_filters();
            }
        }

        public function show_woo_columns_filters()
        {
            // Add the custom column to the post type -- replace helpie_faq with your CPT slug
            add_filter('manage_helpie_faq_posts_columns', array($this, 'add_custom_column'), 10, 2);
            // Add the data to the custom column -- replace helpie_faq with your CPT slug
            add_action('manage_helpie_faq_posts_custom_column', array($this, 'add_custom_column_data'), 10, 2);
        }

        public function add_custom_column($columns)
        {
            $addedcolumns = array_slice($columns, 0, 4, true) +
            array("wooproducts" => __("Woo Products", "helpie-faq")) +
            array_slice($columns, 3, count($columns) - 1, true);

            return $addedcolumns;
        }

        public function add_custom_column_data($column, $post_id)
        {
            if ($column == 'wooproducts') {
                $my_var = get_post_meta($post_id, 'helpie_woo_metabox', true);

                if (!empty($my_var) && isset($my_var)) {
                    foreach ($my_var as $item) {
                        echo get_the_title($item) . ", ";
                    }
                } else {
                    echo "__";
                }
            }
        }
        
    } // END CLASS
}
