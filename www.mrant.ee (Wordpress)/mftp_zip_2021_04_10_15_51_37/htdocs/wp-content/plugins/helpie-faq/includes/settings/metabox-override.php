<?php

namespace HelpieFaq\Includes\Settings;

if (!class_exists('\HelpieFaq\Includes\Settings\Metabox_Override')) {
    class Metabox_Override
    {
        public function __construct()
        {
            $this->override();
        }

        public function override()
        {
            // error_log("MetaBox Override");
        }

    }
}
