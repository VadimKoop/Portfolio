<?php

namespace Helpie\Features\Kb\Articles;

if (!class_exists('\Helpie\Features\Kb\Articles\Article_Listing_View')) {
    class Article_Listing_View
    {
        public function __construct()
        {
            // Views
            $this->card_view = new \Helpie\Includes\Views\Card_View();
            $this->list_view = new \Helpie\Includes\Views\List_View();
        }

        public function get($viewProps, $components)
        {
            $class = $viewProps['collection']['style'];

            $html = "<div class='helpie-articles-listing ".$class."'>";

            $html .= $this->get_heading($viewProps['collection']);

            $html .= "<div class='row-wrapper'>";
            $html .= $this->get_body_html($viewProps);
            $html .= "<div class='clear'></div>";
            $html .= "</div>"; // .row-wrapper

            $html .= "</div>";

            $components->get_password_modal();

            return $html;
        }

        /* PROTECTED METHODS */

        protected function get_heading($collectionProps)
        {
            $icon_class = $collectionProps['article_listing_title_icon'];
            $icon = "<i class='".$icon_class."'></i>";
            $title_content = __($collectionProps['title'], 'pauple-helpie');

            if ($collectionProps['article_listing_title_icon_position'] == 'before') {
                $inner_html = $icon . " " . $title_content;
            } else {
                $inner_html = $title_content  . " " . $icon;
            }


            $html = "<h3 class='collection-title'>".$inner_html."</h3>";

            return $html;
        }

        protected function get_body_html($viewProps)
        {
            $style = $viewProps['collection']['style'];

            if ($style == 'card') {
                $body_html = $this->card_view->get_view($viewProps);
            } elseif ($style == 'list') {
                $body_html = $this->list_view->get_view($viewProps);
            } else {
                $body_html = $this->list_view->get_view($viewProps);
            }

            return $body_html;
        }
    } // END class
}
