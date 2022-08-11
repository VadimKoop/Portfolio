<?php

namespace Widgetry;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Widgetry\Helpie_Widget')) {
    class Helpie_Widget extends \WP_Widget
    {
        public function __construct($helpie_widget_options)
        {
            // Instantiate the parent object
            // parent::__construct( false, 'Categories Listing' );


            $widget_options = array(
               'classname' => $helpie_widget_options['id'],
               'description' => $helpie_widget_options['description'],
             );

            parent::__construct($helpie_widget_options['id'], $helpie_widget_options['name'], $widget_options);
        }




        /* PROTECTED METHODS */

        protected function setInputFromInstance($defaults, $instance)
        {
            foreach ($defaults as $key => $default_value) {
                $input[$key] = (! empty($instance[$key])) ? strip_tags($instance[$key]) : $default_value;
            }
            return $input;
        }

        protected function updateInstanceFromNewInstance($instance, $new_instance, $default_args)
        {
            foreach ($default_args as $key => $default_value) {
                $instance[$key] = (! empty($new_instance[$key])) ? strip_tags($new_instance[$key]) : $default_value;
            }

            return $instance;
        }



        /* FIELD METHODS */

        // A Switch Method for Fields
        protected function get_field_html($instance, $field)
        {
            $html = '';

            if ($field['type'] == 'text') {
                $html .= $this->get_text_field($instance, $field['name'], $field['label']);
            } elseif ($field['type'] == 'number') {
                $html .= $this->get_numeric_field($instance, $field['name'], $field['label'], $field['default']);
            } elseif ($field['type'] == 'multi-select') {
                $html .= $this->get_multi_dropdown_base($instance, $field['name'], $field['options'], $field['label']);
            } elseif ($field['type'] == 'select') {
                $html .= $this->get_helpie_select($instance, $field['name'], $field['options'], $field['label']);
            }

            return $html;
        }

        protected function get_text_field($instance, $field_name, $label)
        {
            $value = ! empty($instance[$field_name]) ? $instance[$field_name] : '';
            $field_id = $this->get_field_id($field_name);
            $field_gen_name = $this->get_field_name($field_name);

            $html = "<p>";
            $html .= "<label for=".$field_id.">".$label."</label>";
            $html .= " <input class='widefat' type='text' id='".$field_id."' name='".$field_gen_name."' value='".esc_attr($value)."' />";
            $html .= "</p>";

            return $html;
        }

        protected function get_numeric_field($instance, $field_name, $label, $default_value = 5)
        {
            $field = ! empty($instance[$field_name]) ? $instance[$field_name] : '';
            $field_id = $this->get_field_id($field_name);
            $field_gen_name = $this->get_field_name($field_name);

            $value  = ! empty($field) ? $field : $default_value ;
            $value = esc_attr($value);

            $html = "<p>";
            $html .= "<label for=".$field_id.">".$label.":</label>";
            $html .= " <input class='tiny-text' type='number' id='".$field_id."' step='1' min='1' size='3' name='".$field_gen_name."' value='".$value."' />";
            $html .= "</p>";

            return $html;
        }



        protected function get_helpie_select($instance, $field, $options, $label = '')
        {
            $value = ! empty($instance[$field]) ? $instance[$field] : '';
            $field_id = $this->get_field_id($field);
            $field_name = $this->get_field_name($field);

            $args = array(
                'value' => $value,
                'id' => $field_id,
                'name' => $field_name,
                'options' => $options,
                'label' => $label
            );

            $html = "<p>";
            $html .= " <label for=".$args['id'].">".$args['label'].": ";
            $html .= "<select class='widefat' id=".$args['id']." name=".$args['name']." type='text'>";

            foreach ($args['options'] as $key => $value) {
                $selected = ($args['value'] == ($key) ? 'selected': '');
                $html .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }

            $html .= "</select>";
            $html .= "</label>";
            $html .= "</p>";

            return $html;
        }

        protected function get_multi_dropdown_base($instance, $field, $options, $label = '')
        {
            $option_value = ! empty($instance[$field]) ? $instance[$field] : '';
            $id = $this->get_field_id($field);
            $name = $this->get_field_name($field);

            $args = array(
                'value' => $option_value,
                'id' => $id,
                'name' => $name,
                'options' => $options
            );

            $html = "<p class='helpie-field-row'>";
            $html .= " <label for=".$args['id'].">".$label.": ";
            $html .= "<input id='".$args['id']."-hidden' type='hidden' value='".$args['value']."' name='".$args['name']."'>";

            $html .= "<select id='".$args['id']."'  multiple='' class='ui fluid dropdown multi_dropdown helpie-field'>";
            $html .= "<option value=''>".'</option>';
            foreach ($args['options'] as $key => $value) {
                $html .= "<option value='".$key."'>".$value.'</option>';
            }

            $html .= '</select>';

            $html .= "</label>";
            $html .= "</p>";

            return $html;
        }
    }
}
