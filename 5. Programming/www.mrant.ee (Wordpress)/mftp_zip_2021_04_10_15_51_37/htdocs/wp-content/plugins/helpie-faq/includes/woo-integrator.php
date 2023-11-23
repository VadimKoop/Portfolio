<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Woo_Integrator')) {

    class Woo_Integrator
    {
        public function __construct($callback = '')
        {
            // error_log('woo_integrator...');
            $settings = new \HelpieFaq\Includes\Settings\Getters\Getter();
            $this->options = $settings->get_settings();

            if ($this->options['woo_integration_switcher']) {
                add_filter('woocommerce_product_tabs', [$this, 'woo_new_product_tab']);
            }
        }

        public function woo_new_product_tab($tabs)
        {
            // error_log('woo_new_product_tab...');
            // Adds the new tab
            $tabs['desc_tab'] = array(
                'title' => $this->options['tab_title'],
                'priority' => 50,
                'callback' => array($this, 'woo_new_product_tab_content'),
            );
            return $tabs;
        }

        public function woo_new_product_tab_content()
        {
            global $product;
            //get current product ID
            $product_id = $product->get_ID();

            $Faq = new \HelpieFaq\Features\Faq\Faq();
            $args = $this->options;

            // Except other fields
            unset($args['title']);

            $args['products'] = $product_id;
            // The new tab content
            $faq_view = $Faq->get_view($args);
            if (!empty($faq_view)) {
                echo $faq_view;
            } else {
                echo "<style>li.desc_tab_tab{ display:none !important; }</style>";
            }
        }

        public function is_woocommerce_activated()
        {
            if (class_exists('woocommerce')) {
                return true;
            } else {
                return false;
            }
        }

        public function show_woo_products_meta()
        {
            if ($this->is_woocommerce_activated()) {
                /* Registering Woocommerce Products Meta */

                $products_option = $this->get_products_option();

                require_once HELPIE_FAQ_PATH . 'lib/hd-wp-metabox-api/class-hd-wp-metabox-api.php';

                $options = array(
                    'metabox_title' => __('Woocommerce Products', 'helpie-faq'),
                    'metabox_id' => 'helpie_woo_metabox',
                    'post_type' => array('helpie_faq'),
                    'context' => 'side',
                    'priority' => 'high',
                );

                $fields = array(
                    'helpie_woo_metabox' => array(
                        'title' => '',
                        'type' => 'multicheck',
                        'choices' => $products_option,
                        'desc' => '',
                        'sanit' => 'nohtml',
                    ),

                );

                $woo_metabox = new \HD_WP_Metabox_API($options, $fields);
            }
        }

        public function get_products_option($show_all = false)
        {

            if (!$this->is_woocommerce_activated()) {
                return new WP_Error('Woocommerce Not Found', __("Woocommerce Not Active", "my_textdomain"));
            }

            $products = get_posts(
                array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                )
            );

            $products_option = array();
            foreach ($products as $product) {
                $product_id = $product->ID;
                $products_option[$product_id] = $product->post_title;
            }

            if ($show_all == true) {
                $products_option = array('all' => 'All') + $products_option;
            }

            return $products_option;
        }
    } // END CLASS
}