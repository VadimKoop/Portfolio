<?php

namespace HelpieFaq\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Utils\Create_Pages')) {
    class Create_Pages
    {
        /**
         * Create a page and store the ID in an option.
         *
         * @param mixed $slug Slug for the new page
         * @param string $option Option name to store the page's ID
         * @param string $page_title (default: '') Title for the new page
         * @param string $page_content (default: '') Content for the new page
         * @param int $post_parent (default: 0) Parent for the new page
         * @return int page ID
         */

        public static function create($slug, $page_option_name, $page_title, $page_content = '')
        {
            $page_id = get_option($page_option_name);
            if (empty($page_id)) {
                self::do_create_page($slug, $page_option_name, $page_title, $page_content);
            }
        }

        public static function do_create_page($slug, $option = '', $page_title = '', $page_content = '', $post_parent = 0)
        {
            global $wpdb;

            $option_value = get_option($option);

            if ($option_value > 0) {
                $page_object = get_post($option_value);
                if (isset($page_object) && !empty($page_object)) {
                    if ('page' === $page_object->post_type && !in_array($page_object->post_status, array('pending', 'trash', 'future', 'auto-draft'))) {
                        // Valid page is already in place
                        return $page_object->ID;
                    }
                }
            }

            if (strlen($page_content) > 0) {
                // Search for an existing page with the specified page content (typically a shortcode)
                $valid_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%"));
            } else {
                // Search for an existing page with the specified page slug
                $valid_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug));
            }

            // Search for a matching valid trashed page
            if (strlen($page_content) > 0) {
                // Search for an existing page with the specified page content (typically a shortcode)
                $trashed_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%"));
            } else {
                // Search for an existing page with the specified page slug
                $trashed_page_found = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug));
            }

            if ($trashed_page_found) {
                $page_id = $trashed_page_found;
                $page_data = array(
                    'ID' => $page_id,
                    'post_status' => 'publish',
                );
                wp_update_post($page_data);
            } else {
                $page_data = array(
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_author' => 1,
                    'post_name' => $slug,
                    'post_title' => $page_title,
                    'post_content' => $page_content,
                    'post_parent' => $post_parent,
                    'comment_status' => 'closed',
                );
                $page_id = wp_insert_post($page_data);
            }

            if ($option) {
                update_option($option, $page_id);
            }

            return $page_id;
        }
    }
}
