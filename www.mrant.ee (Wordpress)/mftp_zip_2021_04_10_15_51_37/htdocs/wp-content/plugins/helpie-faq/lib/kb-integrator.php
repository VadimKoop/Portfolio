<?php

namespace HelpieFaq\Lib;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Lib\Kb_Integrator')) {

    class Kb_Integrator
    {
        public function is_helpie_kb_activated()
        {
            if (class_exists('\Helpie\PAUPLE_HELPIE_MAIN_CLS')) {
                return true;
            } else {
                return false;
            }
        }

        public function get_kb_categories_option()
        {

            if (class_exists('\Helpie\Includes\Repositories\Category_Repository')) {
                // For Helpie KB - v1.8.1 and below
                $cat_repo = new \Helpie\Includes\Repositories\Category_Repository();
            } else {
                // For Helpie KB - v1.9 and above
                $cat_repo = new \Helpie\Features\Domain\Repositories\Category_Repository();
            }
            // $cat_repo = new \Helpie\Features\Domain\Repositories\Category_Repository();
            $categories_option = $cat_repo->get_category_options(true); // show_all = true;

            return $categories_option;
        }
    } // END CLASS
}