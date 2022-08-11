<?php

namespace HelpieFaq\Includes\Widgets;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Widgets\Register_Elementor_Widgets')) {
    class Register_Elementor_Widgets
    {
        public function load()
        {
            add_action('elementor/widgets/widgets_registered', [ $this, 'register' ]);
        }

        public function register()
        {
            $faq_widget_args_dynamic_add = array(
                'name' => 'helpie-faq-dynamic-add',
                'title' => 'Helpie FAQ - Dynamic Add',
                'icon' => 'fa fa-th-list',
                'categories' => [ 'general-elements' ],
                'model' =>  new \HelpieFaq\Features\Faq\Dynamic_Widget\Faq_Model(),
                'view' => new \HelpieFaq\Features\Faq\Dynamic_Widget\Faq(),
             );


            $faq_widget_args = array(
                'name' => 'helpie-faq',
                'title' => 'Helpie FAQ',
                'icon' => 'fa fa-th-list',
                'categories' => [ 'general-elements' ],
                'model' =>  new \HelpieFaq\Features\Faq\Faq_Model(),
                'view' => new \HelpieFaq\Features\Faq\Faq(),
             );


            require_once HELPIE_FAQ_PATH . '/lib/widgetry/elementor-widget-factory.php';
          

            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $faq_widget_args_dynamic_add));

            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $faq_widget_args));
        }
    }
}
