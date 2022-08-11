<?php

namespace HelpieFaq\Features\Faq\Dynamic_Widget;

if (!class_exists('\HelpieFaq\Features\Dynamic_Widget\Faq')) {
    class Faq
    {
        public function __construct()
        {
            // Models
            $this->model = new \HelpieFaq\Features\Faq\Dynamic_Widget\Faq_Model();

            // Views
            $this->view = new \HelpieFaq\Features\Faq\Faq_View();
        }




        // For using only with Elementor Widget: Helpie FAQ - Dynamically Added FAQ
        public function get_view($args)
        {
            $html = '';

            $style = array();

            if (isset($args['style'])) {
                $style = $args['style'];
            }

            
            $html = $this->get_viewProps_elementor($args, $style);
            
            // error_log('html: ' . $html);

            return $html;
        }



        public function get_viewProps_elementor($args, $style = [])
        {
            $viewProps = array();

            $viewProps['collection'] = [
                'title' => "FAQ Added Via Elementor",
                'display_mode' => 'simple_accordion'
            ];

            $viewProps = $this->model->get_viewProps($args);

            $viewProps['items'] = [];

            foreach ($args['faqs'] as $key => $field) {
                $single_field = [
                    'title' => $field['tab_title'],
                    'content' => $field['tab_content'],
                    'post_id' => 0,
                    'count' => []
                ];

                $viewProps['items'][] = $single_field;
            }

            // error_log('get_viewProps_elementor $viewProps : ' . print_r($viewProps, true));

            if (isset($viewProps['items']) && !empty($viewProps['items'])) {
                $html = $this->view->get($viewProps, $style);
                // error_log('get_viewProps_elementor $html: ' . $html);
            }
            
            apply_filters('helpie_faq_schema_generator', $viewProps);
            
            return $html;
        }
    }
}
