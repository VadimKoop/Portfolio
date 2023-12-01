<?php

namespace Widgetry;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

require_once  'elementor-widget-base.php';

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Typography;

if (!class_exists('\Widgetry\Elementor_Widget_Factory')) {
    class Elementor_Widget_Factory extends \Widgetry\Elementor_Widget_Base
    {
        protected $name;
        protected $title;
        protected $icon;
        protected $categories;
        protected $widget_model;
        protected $widget_view;


        public function __construct($data = [], $args = null)
        {
     
            $this->name = $args['name'];
            $this->title = $args['title'];
            $this->icon = $args['icon'];

            $this->categories = $args['categories'];
            $this->widget_model = $args['model'];
            $this->widget_view = $args['view'];

            parent::__construct($data, $args);
        }

        public function get_name()
        {
            return $this->name;
        }

        public function get_title()
        {
            return $this->title;
        }

        public function get_icon()
        {
            return $this->icon;
        }

        public function get_categories()
        {
            return $this->categories;
        }


        protected function _register_controls()
        {
            $fields = $this->widget_model->get_fields();

            $this->register_content_controls_from_fields($fields);

            /* STYLE CONTROL */
            $this->style_controls($this->widget_model);
        }


        /** * Render the widget output on the frontend. */
        protected function render()
        {
            $settings = $this->get_settings();

            $input = $this->widget_model->get_default_args();

            foreach ($settings as $key => $value) {
                $input[$key] = ! empty($input[$key]) ? $input[$key] : '';
                $input[$key] = ! empty($settings[$key]) ? $settings[$key] : $input[$key];
            }

            // error_log('$input : ' . print_r($input, true));

            $this->helpie_render_template($input, $this->widget_view);
        }

        // Used Methods - Not Part of Interface

        public function style_controls($widget_model)
        {
            $style_config  = $widget_model->get_style_config();

            if (isset($style_config['collection']) && !empty($style_config['collection'])) {
                if (isset($style_config['collection']['styleProps']) && !empty($style_config['collection']['styleProps'])) {
                    $this->collection_style_controls($style_config);
                }
            }

            if (isset($style_config['title']) && !empty($style_config['title'])) {
                $this->collection_title_style_controls($style_config['title']);
            }

            if (isset($style_config['element']) && !empty($style_config['element'])) {
                $this->single_element_controls($style_config['element']);
            }
        }
    }
}
