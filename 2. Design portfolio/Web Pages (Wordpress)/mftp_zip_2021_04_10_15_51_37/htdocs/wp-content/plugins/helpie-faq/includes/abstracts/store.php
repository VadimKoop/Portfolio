<?php

namespace HelpieFaq\Includes\Abstracts;

/**
 * Abstract Class: Store
 *
 */

if (!class_exists('\HelpieFaq\Includes\Abstracts\Store')) {
    class Store
    {
        protected $wp_query_args;

        public function __construct($query_vars = array())
        {
            $this->wp_query_args = $query_vars;
        }

        public function get()
        {
            return $this->wp_query_args;
        }

        public function interprete($args)
        {
            $this->filter($args);
            $this->sort($args);

            return $this;
        }



    } // END CLASS
}
