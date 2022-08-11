<?php

namespace HelpieFaq\Includes\Settings;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( '\\HelpieFaq\\Includes\\Settings\\Fields' ) ) {
    class Fields
    {
        public function get_general_settings()
        {
            $setting_defaults = new \HelpieFaq\Includes\Settings\Option_Values();
            $allowed_title_tags = $setting_defaults->get_allowed_title_tags();
            $fields = array(
                array(
                    'id'         => 'title',
                    'type'       => 'text',
                    'title'      => __( 'Title', 'helpie-faq' ),
                    'attributes' => array(
                    'placeholder' => __( 'FAQ Title', 'helpie-faq' ),
                ),
                    'default'    => __( 'Helpie FAQ', 'helpie-faq' ),
                ),
                array(
                    'id'      => 'title_tag',
                    'type'    => 'select',
                    'title'   => __( 'Select FAQ Title Tag', 'helpie-faq' ),
                    'options' => $allowed_title_tags,
                    'default' => 'h3',
                ),
                array(
                    'id'      => 'show_search',
                    'type'    => 'switcher',
                    'title'   => __( 'Show Search in FAQ', 'helpie-faq' ),
                    'label'   => __( 'You can search through all FAQ items', 'helpie-faq' ),
                    'default' => true,
                ),
                array(
                    'id'         => 'search_placeholder',
                    'type'       => 'text',
                    'title'      => __( 'Search Placeholder text', 'helpie-faq' ),
                    'attributes' => array(
                    'placeholder' => __( 'FAQ Search Placeholder text', 'helpie-faq' ),
                ),
                    'dependency' => array( 'show_search', '==', 'true' ),
                    'default'    => __( 'Search FAQ', 'helpie-faq' ),
                ),
                array(
                    'id'         => 'toggle',
                    'type'       => 'switcher',
                    'title'      => __( 'Toggle', 'helpie-faq' ),
                    'label'      => __( 'Toggle Open / closed Previous Item', 'helpie-faq' ),
                    'default'    => true,
                    'dependency' => array( 'display_mode', '!=', 'faq_list' ),
                ),
                // array(
                //     'id' => 'open_first',
                //     'type' => 'switcher',
                //     'title' => __('Open First FAQ Item', 'helpie-faq'),
                //     'label' => __('First item open by default', 'helpie-faq'),
                //     'default' => true,
                // ),
                array(
                    'id'         => 'open_by_default',
                    'type'       => 'select',
                    'title'      => __( 'FAQ Open By Default', 'helpie-faq' ),
                    'options'    => array(
                    'none'          => __( 'None', 'helpie-faq' ),
                    'open_first'    => __( 'Open First FAQ', 'helpie-faq' ),
                    'open_all_faqs' => __( 'All FAQs', 'helpie-faq' ),
                ),
                    'default'    => 'open_first',
                    'dependency' => array( 'display_mode', '!=', 'faq_list' ),
                ),
                array(
                    'id'      => 'faq_url_attribute',
                    'type'    => 'switcher',
                    'title'   => __( 'Add FAQ Url Attribute', 'helpie-faq' ),
                    'label'   => __( 'Faq item added in url', 'helpie-faq' ),
                    'default' => true,
                ),
                array(
                    'id'      => 'display_mode',
                    'title'   => __( 'Display Mode', 'helpie-faq' ),
                    'default' => 'simple_accordion',
                    'options' => array(
                    'simple_accordion'          => __( 'Simple Accordion', 'helpie-faq' ),
                    'simple_accordion_category' => __( 'Simple Accordion by Category', 'helpie-faq' ),
                    'category_accordion'        => __( 'Category Accordion', 'helpie-faq' ),
                    'faq_list'                  => __( 'FAQ List', 'helpie-faq' ),
                ),
                    'type'    => 'select',
                ),
                array(
                    'id'      => 'sortby',
                    'title'   => __( 'Sort By', 'helpie-faq' ),
                    'type'    => 'select',
                    'options' => array(
                    'publish'      => __( 'Publish Date', 'helpie-faq' ),
                    'updated'      => __( 'Updated Date', 'helpie-faq' ),
                    'alphabetical' => __( 'Alphabetical', 'helpie-faq' ),
                    'menu_order'   => __( 'Menu Order', 'helpie-faq' ),
                ),
                    'default' => 'publish',
                ),
                array(
                    'id'      => 'order',
                    'title'   => __( 'Order', 'helpie-faq' ),
                    'default' => 'desc',
                    'options' => array(
                    'asc'  => __( 'Ascending', 'helpie-faq' ),
                    'desc' => __( 'Descending', 'helpie-faq' ),
                ),
                    'type'    => 'select',
                ),
                array(
                    'id'         => 'limit',
                    'type'       => 'text',
                    'title'      => __( 'Limit ( number of items )', 'helpie-faq' ),
                    'default'    => 5,
                    'attributes' => array(
                    'min' => 1,
                ),
                    'info'       => __( 'Limit of the FAQ items', 'helpie-faq' ),
                ),
                array(
                    'id'      => 'enable_wpautop',
                    'type'    => 'switcher',
                    'title'   => __( 'Enable wpautop', 'helpie-faq' ),
                    'label'   => __( 'Enable / Disable wpautop', 'helpie-faq' ),
                    'default' => false,
                ),
                array(
                    'id'      => 'product_only',
                    'type'    => 'switcher',
                    'title'   => __( 'FAQs Shows Products Only', 'helpie-faq' ),
                    'label'   => __( 'True / False ', 'helpie-faq' ),
                    'default' => false,
                ),
            );
            return $fields;
        }
        
        public function get_faq_slug_settings()
        {
            $fields = array( array(
                'id'         => 'helpie_faq_slug',
                'type'       => 'text',
                'title'      => __( 'Helpie FAQ Slug', 'helpie-faq' ),
                'attributes' => array(
                'placeholder' => __( 'Helpie FAQ Slug', 'helpie-faq' ),
            ),
                'default'    => 'helpie_faq',
            ) );
            return $fields;
        }
        
        public function get_categories_field()
        {
            $faq_repo = new \HelpieFaq\Includes\Repos\Faq_Repo();
            $options = $faq_repo->get_options( 'categories' );
            $field = array( array(
                'id'       => 'categories',
                'type'     => 'select',
                'title'    => __( 'Categories', 'helpie-faq' ),
                'options'  => $options,
                'chosen'   => true,
                'multiple' => true,
                'default'  => 'all',
            ) );
            return $field;
        }
    
    }
}