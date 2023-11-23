<?php

namespace HelpieFaq\Includes\Utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Utils\Helpers')) {
    class Helpers
    {

        public function content_setup()
        {
            $taxonomy = 'helpie_faq_category';
            $post_info = array();
            $post_info['term-1'] = $this->insert_term_with_post('helpie_faq', 'a-term-1', 'helpie_faq_category', 'Term1 Post Title');
            $post_info['term-2'] = $this->insert_term_with_post('helpie_faq', 'd-term-2', 'helpie_faq_category', 'Term2 Post Title');
            $post_info['term-3'] = $this->insert_term_with_post('helpie_faq', 'c-term-3', 'helpie_faq_category', 'Term3 Post Title');
            $post_info['term-4'] = $this->insert_term_with_post('helpie_faq', 'b-term-4', 'helpie_faq_category', 'Term4 Post Title');
            $term_info_5 = wp_insert_term('f-term-5', $taxonomy);
            $post_info['term-5'] = array(
                0 => '',
                1 => $term_info_5['term_id'],
            );

            return $post_info;
        }

        public function create_new_user($role = 'subscriber', $username = 'subman', $password = 'subpass', $email = 'submail@pauple.com')
        {
            $user_id = wp_create_user($username, $password, $email);
            $userdata = array('ID' => $user_id, 'role' => $role);
            wp_update_user($userdata);
            // error_log('create_new_user: ' . $role);

            return $user_id;
        }

        public function insert_term_with_post($post_type, $term_value, $taxonomy, $post_title = 'random', $post_content = 'demo text', $parent_term_id = 0)
        {
            if (!term_exists($term_value, $taxonomy, $parent_term_id)) {
                // echo "parent_term_id: " . $parent_term_id;
                $term_info = wp_insert_term($term_value, $taxonomy, array('parent' => $parent_term_id));
                $term_id = $term_info['term_id'];
            } else {
                $term = get_term_by('slug', $term_value, $taxonomy);
                $term_id = $term->term_id;
            }

            $post_id = wp_insert_post(array('post_title' => $post_title, 'post_type' => $post_type, 'post_content' => $post_content, 'post_status' => 'publish'));

            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);
            return [$post_id, $term_id];
        }

        public function insert_post_to_child_term($post_type, $term_value, $taxonomy, $parent_term)
        {
            $term_info = wp_insert_term($term_value, $taxonomy, array('parent' => $parent_term));
            $term_id = $term_info['term_id'];
            $post_id = wp_insert_post(array('post_title' => 'random', 'post_type' => $post_type, 'post_content' => 'demo text', 'post_status' => 'publish'));

            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);

            return [$post_id, $term_id];
        }

        public function insert_post_with_term($post_type, $term_id, $taxonomy, $post_title = 'random', $post_content = 'demo text')
        {
            $post_id = wp_insert_post(array('post_title' => $post_title, 'post_type' => $post_type, 'post_content' => $post_content, 'post_status' => 'publish'));
            $cat_ids = array_map('intval', (array) $term_id);
            $cat_ids = array_unique($cat_ids);
            wp_set_object_terms($post_id, $cat_ids, $taxonomy);
            return $post_id;
        }

        public function css_to_string($css)
        {
            $inline = '';
            foreach ($css as $key => $value) {
                $inline .= $key . ":" . $value . ";";
            }
            return $inline;
        }

        public function insert_faq_group_metadata($post, $term_id){
            $faq_groups = array(
                array(
                    'faq_item' => array(
                        'post_id'   => $post->ID,
                        'title'     => $post->post_title,
                        'content'   => $post->post_content
                    )
                )
            );
            update_term_meta($term_id, 'helpie_faq_group_items',['faq_groups' => $faq_groups]);
            return $term_id;
        }


    } // END CLASS
}
