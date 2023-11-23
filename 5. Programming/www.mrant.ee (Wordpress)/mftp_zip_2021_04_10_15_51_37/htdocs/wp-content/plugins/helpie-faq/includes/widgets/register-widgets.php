<?php

namespace HelpieFaq\Includes\Widgets;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Widgets\Register_Widgets')) {
    class Register_Widgets
    {
        public function load()
        {
            add_action('widgets_init', function () {
                $faq_widget_args = array(
                    'id' => 'helpie-faq-listing',
                    'name' => 'Helpie FAQ',
                    'description' => 'Helpie FAQ Widget',
                    'model' =>  new \HelpieFaq\Features\Faq\Faq_Model(),
                    'view' => new \HelpieFaq\Features\Faq\Faq(),
                 );

                require_once HELPIE_FAQ_PATH . '/lib/widgetry/widget-factory.php';
                $faq_widget = new \Widgetry\Widget_Factory($faq_widget_args);
                register_widget($faq_widget);
            });

        }
    } // END CLASS
}
