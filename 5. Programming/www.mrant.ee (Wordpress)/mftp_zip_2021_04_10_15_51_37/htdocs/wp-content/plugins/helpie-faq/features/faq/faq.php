<?php

namespace HelpieFaq\Features\Faq;

if (!class_exists('\HelpieFaq\Features\Faq')) {
    class Faq
    {
        public function __construct()
        {
            // Models
            $this->model = new \HelpieFaq\Features\Faq\Faq_Model();

            // Views
            $this->view = new \HelpieFaq\Features\Faq\Faq_View();
           
        }

        // Check with User Access and Password Protected Modules
        public function get_view($args)
        {
            // error_log('[get_view_args] : ' . print_r($args, true));
            
            $html = '';

            $style = array();

            if( isset($args['style']) ){
                $style = $args['style']; 
            }
            
            $viewProps = $this->model->get_viewProps($args);
            
            if (isset($viewProps['items']) && !empty($viewProps['items'])) {
                $html = $this->view->get($viewProps, $style);
            }

            /** use this below filter for generating faq-schema snippet */
            apply_filters( 'helpie_faq_schema_generator', $viewProps);
            
            return $html;
        }

    }
}
