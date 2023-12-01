<?php

namespace Stylus;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Stylus\Stylus')) {

    class Stylus
    {

        public function __construct()
        {
            require_once 'components/search.php';
            $this->search = new \Stylus\Components\Search();

            require_once 'components/accordion.php';
            $this->accordion = new \Stylus\Components\Accordion();

            require_once 'components/form.php';
            $this->form = new \Stylus\Components\Form();

            require_once 'components/message.php';
            $this->message = new \Stylus\Components\Message();
        }

    } // END CLASS
}