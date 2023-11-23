<?php

namespace Helpie\Features\Kb\Articles;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\Helpie\Features\Kb\Articles\Fields_Model')) {
    class Fields_Model
    {
        public function __construct()
        {            
            $this->category_repository = new \Helpie\Includes\Repositories\Category_Repository();
        }


        public function get_fields()
        {
            $fields = array(
                'title' => $this->get_title_field(),
                'sortby' => $this->get_sortby_field(),
                'topics' => $this->get_topics_field(),
                'num_of_cols' => $this->get_num_of_cols_field(),
                'style' => $this->get_style_field(),
                'limit' => $this->get_limit_field(),
                'show_image' => $this->get_show_image_field(),
                'show_extra' => $this->get_show_extra_field(),
            );

            return $fields;
        }

        public function get_default_args()
        {
            $args = array();

            // Get Default Values from GET - FIELDS
            $fields = $this->get_fields();
            foreach ($fields as $key => $field) {
                $args[$key] = $field['default'];
            }

            return $args;
        }

        // FIELDS
        protected function get_title_field()
        {
            return array(
                'name' => 'title',
                'label' => __('Title', 'pauple-helpie'),
                'default' => __('KB Article Listing', 'pauple-helpie'),
                'type' => 'text'
            );
        }

        protected function get_topics_field()
        {
            $categories_options = $this->category_repository->get_category_options(true);  // $show_all = true

            return array(
                'name' => 'topics',
                'label' => __('Topics', 'pauple-helpie'),
                'default' => __('all', 'pauple-helpie'),
                'options' => $categories_options,
                'type' => 'multi-select'
            );
        }

        protected function get_sortby_field()
        {
            return array(
                'name' => 'sortby',
                'label' => __('Sort By', 'pauple-helpie'),
                'default' => __('recent', 'pauple-helpie'),
                'options' => array(
                    'recent' => __('Recent', 'pauple-helpie'),
                    'updated' => __('Recently Updated', 'pauple-helpie'),
                    'popular' => __('Popular', 'pauple-helpie'),
                    'alphabetical' => __('Alphabetical', 'pauple-helpie')
                ),
                'type' => 'select'
            );
        }



        protected function get_num_of_cols_field()
        {
            return array(
                'name' => 'num_of_cols',
                'label' => __('Num Of Columns', 'pauple-helpie'),
                'default' => __('three', 'pauple-helpie'),
                'options' => array(
                    'one' => __(1, 'pauple-helpie'),
                    'two' => __(2, 'pauple-helpie'),
                    'three' => __(3, 'pauple-helpie'),
                    'four' => __(4, 'pauple-helpie'),
                ),
                'type' => 'select'
            );
        }

        protected function get_style_field()
        {
            return array(
                'name' => 'style',
                'label' => __('Style', 'pauple-helpie'),
                'default' => __('list', 'pauple-helpie'),
                'options' => array(
                    'list'  => __('List', 'pauple-helpie'),
                    'card' => __('Card', 'pauple-helpie')
                ),
                'type' => 'select'
            );
        }

        protected function get_limit_field()
        {
            return array(
                'name' => 'limit',
                'label' => __('Limit', 'pauple-helpie'),
                'default' => __(5, 'pauple-helpie'),
                'type' => 'number'
            );
        }

        protected function get_show_image_field()
        {
            return array(
                'name' => 'show_image',
                'label' => __('Show Image', 'pauple-helpie'),
                'default' => __('true', 'pauple-helpie'),
                'options' => array(
                    'true' => __('True', 'pauple-helpie'),
                    'false' => __('False', 'pauple-helpie')
                ),
                'type' => 'select'
            );
        }

        protected function get_show_extra_field()
        {
            return array(
                'name' => 'show_extra',
                'label' => __('Show Extra Info', 'pauple-helpie'),
                'default' => __('true', 'pauple-helpie'),
                'options' => array(
                    'true' => __('True', 'pauple-helpie'),
                    'false' => __('False', 'pauple-helpie')
                ),
                'type' => 'select'
            );
        }

        // OTHER
    } // END CLASS
}
