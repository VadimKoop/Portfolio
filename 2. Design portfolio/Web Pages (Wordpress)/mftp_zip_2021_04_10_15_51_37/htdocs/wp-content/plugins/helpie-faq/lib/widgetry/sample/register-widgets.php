<?php

namespace Helpie\Includes;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Helpie\Includes\Register_Widgets')) {
    class Register_Widgets
    {
        public function load()
        {
            add_action('widgets_init', function () {
                $article_widget_args = array(
                    'id' => 'helpie-kb-article-listing',
                    'name' => 'Helpie Article Listing',
                    'description' => 'Helpie KB Articles Listing Widget',
                    'model' =>  new \Helpie\Features\Kb\Articles\Article_Listing_Model(),
                    'view' => new \Helpie\Features\Kb\Articles\Article_Listing(),
                 );
                $article_widget = new \Widgetry\Widget_Factory($article_widget_args);
                register_widget($article_widget);
            });
           
        }
    } // END CLASS
}
