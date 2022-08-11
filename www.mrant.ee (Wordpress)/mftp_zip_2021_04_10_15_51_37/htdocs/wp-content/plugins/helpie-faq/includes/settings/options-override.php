<?php

namespace HelpieFaq\Includes\Settings;

if ( !class_exists( '\\HelpieFaq\\Includes\\Settings\\Options_Override' ) ) {
    class Options_Override
    {
        public function __construct()
        {
            add_filter( "hfps_framework_options", array( $this, "override" ) );
        }
        
        public function override()
        {
            $general = new \HelpieFaq\Includes\Settings\Sections\General_Section();
            $options[] = $general->get_section();
            $style = new \HelpieFaq\Includes\Settings\Sections\Style_Section();
            $options[] = $style->get_section();
            $integration = new \HelpieFaq\Includes\Settings\Sections\Integration_Section();
            $options[] = $integration->get_section();
            return $options;
        }
    
    }
}