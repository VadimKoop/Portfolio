<?php

namespace Widgetry;

require_once  'helpie-widget.php';

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Widgetry\Widget_Factory')) {
    class Widget_Factory extends \Widgetry\Helpie_Widget
    {
        public function __construct($widget_options)
        {
            // error_log('widget_options: ' . print_r($widget_options, true));
            parent::__construct($widget_options);

            $this->widget_model = $widget_options['model'];
            $this->widget_view = $widget_options['view'];
        }

        public function widget($args, $instance)
        {

            // Array of Fields => default Values
            $defaults = $this->widget_model->get_default_args();
            $input = $this->setInputFromInstance($defaults, $instance);

            // error_log('view output: ');
            // error_log($this->widget_view->get_view($input));

            // Widget output
            echo  $this->widget_view->get_view($input);
        }

        public function update($new_instance, $old_instance)
        {
            // Save widget options
            $instance = $old_instance;
            $default_args = $this->widget_model->get_default_args();
            $instance = $this->updateInstanceFromNewInstance($instance, $new_instance, $default_args);

            return $instance;
        }

        public function form($instance)
        {
            // Output admin widget options form

            $html = '';

            $fields = $this->widget_model->get_fields();
            foreach ($fields as $field) {
                $html .= $this->get_field_html($instance, $field);
            }


            echo $html;
        }
    }
}
