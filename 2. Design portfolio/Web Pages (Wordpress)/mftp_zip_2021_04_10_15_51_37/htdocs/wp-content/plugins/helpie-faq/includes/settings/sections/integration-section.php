<?php

namespace HelpieFaq\Includes\Settings\Sections;

if ( !class_exists( '\\HelpieFaq\\Includes\\Settings\\Sections\\Integration_Section' ) ) {
    class Integration_Section
    {
        public function get_section()
        {
            return array(
                'name'     => 'integrations',
                'title'    => __( 'Integrations', 'helpie-faq' ),
                'icon'     => 'fa fa-plus',
                'sections' => array( $this->get_kb_fields(), $this->get_woo_fields() ),
            );
        }
        
        public function get_kb_fields()
        {
            return array(
                'name'   => 'helpie_kb',
                'title'  => __( 'Helpie KB', 'helpie-faq' ),
                'icon'   => 'fa fa-book',
                'fields' => $this->kb_active_fields(),
            );
        }
        
        public function kb_active_fields()
        {
            if ( !\is_plugin_active( 'helpie/helpie.php' ) ) {
                $options[] = array(
                    'type'    => 'notice',
                    'class'   => 'danger',
                    'content' => __( 'In order use this feature you need to purchase and activate the <a href="https://codecanyon.net/checkout/from_item/18882940?license=regular&size=source&support=bundle_6month&ref=pauple" target="_blank">Helpie KB</a> plugin.', 'helpie-faq' ),
                );
            }
            $options[] = array(
                'id'      => 'kb_integration_switcher',
                'type'    => 'switcher',
                'title'   => __( 'Enable FAQ in Helpie KB', 'helpie-faq' ),
                'label'   => __( 'Show FAQ In Helpie KB Category Page', 'helpie-faq' ),
                'default' => true,
            );
            $options[] = array(
                'id'         => 'kb_cat_content_show',
                'type'       => 'select',
                'title'      => __( 'Show FAQ in Helpie KB Category Page', 'helpie-faq' ),
                'options'    => array(
                'before' => __( 'Before Content', 'helpie-faq' ),
                'after'  => __( 'After Content', 'helpie-faq' ),
            ),
                'default'    => 'before',
                'info'       => __( 'Select show faq before or after content in kb category page', 'helpie-faq' ),
                'dependency' => array( 'kb_integration_switcher', '==', 'true' ),
            );
            return $options;
        }
        
        public function get_woo_fields()
        {
            return array(
                'name'   => 'woocommerce',
                'title'  => __( 'WooCommerce', 'helpie-faq' ),
                'icon'   => 'fa fa-cart-plus',
                'fields' => $this->woo_active_fields(),
            );
        }
        
        public function woo_active_fields()
        {
            if ( !\is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                $options[] = array(
                    'type'    => 'notice',
                    'class'   => 'danger',
                    'content' => __( 'In order use this feature you need to activate the <a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> plugin.', 'helpie-faq' ),
                );
            }
            $options[] = array(
                'id'      => 'woo_integration_switcher',
                'type'    => 'switcher',
                'title'   => __( 'Show FAQ in WooCommerce', 'helpie-faq' ),
                'label'   => __( 'Show FAQ In WooCommerce product tab', 'helpie-faq' ),
                'default' => true,
            );
            $options[] = array(
                'id'         => 'tab_title',
                'type'       => 'text',
                'title'      => __( 'Tab Title', 'helpie-faq' ),
                'default'    => __( 'FAQ', 'helpie-faq' ),
                'dependency' => array( 'woo_integration_switcher', '==', 'true' ),
            );
            return $options;
        }
    
    }
}