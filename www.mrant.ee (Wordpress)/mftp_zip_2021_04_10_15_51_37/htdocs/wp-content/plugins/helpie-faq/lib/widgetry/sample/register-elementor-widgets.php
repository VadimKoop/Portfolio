<?php

namespace Widgetry;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Widgetry\Register_Elementor_Widgets')) {
    class Register_Elementor_Widgets
    {
        public function load()
        {
            add_action('elementor/widgets/widgets_registered', [ $this, 'register' ]);
        }

        public function register()
        {
            $article_widget_args = array(
                'name' => 'helpie-kb-article-listing',
                'title' => 'Helpie Article Listing',
                'icon' => 'fa fa-th-list',
                'categories' => [ 'general-elements' ],
                'model' =>  new \Helpie\Features\Kb\Articles\Article_Listing_Model(),
                'view' => new \Helpie\Features\Kb\Articles\Article_Listing(),
             );

            $category_widget_args = array(
                'name' => 'helpie-kb-category-listing',
                'title' => 'Helpie Category Listing',
                'icon' => 'fa fa-th-list',
                'categories' => [ 'general-elements' ],
                'model' =>  new \Helpie\Features\Kb\Category_Listing\Category_Listing_Model(),
                'view' => new \Helpie\Features\Kb\Category_Listing\Category_Listing(),
            );

            $hero_widget_args = array(
               'name' => 'helpie-kb-hero',
               'title' => 'Helpie Hero Section',
               'icon' => 'fa fa-th-list',
               'categories' => [ 'general-elements' ],
               'model' =>  new \Helpie\Features\Kb\Hero\Hero_Area_Model(),
               'view' => new \Helpie\Features\Kb\Hero\Hero_Area(),
            );

            $stats_widget_args = array(
               'name' => 'helpie-kb-frontend-stats',
               'title' => 'Helpie Stats Section',
               'icon' => 'fa fa-th-list',
               'categories' => [ 'general-elements' ],
               'model' =>  new \Helpie\Features\Kb\Stats\Stats_Model(),
               'view' => new \Helpie\Features\Kb\Stats\Frontend_Stats(),
            );

            $toc_widget_args = array(
               'name' => 'helpie-kb-toc',
               'title' => 'Helpie Table of Contents',
               'icon' => 'fa fa-th-list',
               'categories' => [ 'general-elements' ],
               'model' =>  new \Helpie\Features\Kb\Toc\Model\Toc_Model(),
               'view' => new \Helpie\Features\Kb\Toc\Toc_Controller(),
            );


            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $article_widget_args));
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $category_widget_args));
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $hero_widget_args));
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $stats_widget_args));
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Widgetry\Elementor_Widget_Factory([], $toc_widget_args));
        }
    }
}
