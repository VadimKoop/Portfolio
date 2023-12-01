<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Kb_Integrator')) {

    class Kb_Integrator
    {

        public function __construct()
        {
            $settings = new \HelpieFaq\Includes\Settings\Getters\Getter();
            $this->options = $settings->get_settings();
            $this->show_faq_in_category_page();
        }

        public function kb_category_faq()
        {
            $term = get_queried_object();
            $args = $this->options;
            $args['kb_categories'] = $term->term_id;

            $faq_controller = new \HelpieFaq\Features\Faq\Faq();
            echo $faq_controller->get_view($args);
        }

        protected function show_faq_in_category_page()
        {
            $options = $this->options;

            if ($options['kb_integration_switcher']) {

                switch ($options['kb_cat_content_show']) {

                    case 'before':
                        add_action('helpie_kb_before_category_content', [$this, 'kb_category_faq']);
                        break;

                    case 'after':
                        add_action('helpie_kb_after_category_content', [$this, 'kb_category_faq']);
                        break;

                    default;
                }
            }
        }
    } // END CLASS
}
