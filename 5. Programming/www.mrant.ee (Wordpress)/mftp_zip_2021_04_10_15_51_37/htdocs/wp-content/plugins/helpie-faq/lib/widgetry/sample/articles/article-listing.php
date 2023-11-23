<?php

namespace Helpie\Features\Kb\Articles;

if (!class_exists('\Helpie\Features\Kb\Articles\Article_Listing')) {
    class Article_Listing
    {
        public function __construct()
        {
            // Models
            $this->article_listing_model = new \Helpie\Features\Kb\Articles\Article_Listing_Model();

            // Views
            $this->article_listing_view = new \Helpie\Features\Kb\Articles\Article_Listing_View();
        }

        // Check with User Access and Password Protected Modules
        public function get_view($args)
        {
            $viewProps = $this->article_listing_model->get_viewProps($args);
            $components = new \Helpie\Includes\Utils\Builders\Components();

            $html = $this->article_listing_view->get($viewProps, $components);

            return $html;
        }
    }
}
