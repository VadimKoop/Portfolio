<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Insights_Page')) {
    class Insights_Page extends \HelpieFaq\Features\Insights\Admin_Page
    {

        private $options;
        private $page_name = 'insights_model_page';
        private $opts_grp = 'pauple_insights_model';
    
        public function __construct()
        {
            parent::__construct();
            $this->view = new \HelpieFaq\Features\Insights\View();
            

            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            add_action('helpie_faq_admin_localize_script', array($this, 'get_insights_localize_data'));
        }

        public function get_insights_localize_data(){
            $this->insights_controller = new \HelpieFaq\Features\Insights\Controller;
            $insights = $this->insights_controller->get_insights();

            $insights_for_js = array(
                'click' => array(
                    'last_30days' => $insights['click']['last_30days'],
                    'last_year' => $insights['click']['last_year']
                ),
                'search' => array(
                    'last_30days' => $insights['search']['last_30days'],
                    'last_year' => $insights['search']['last_year']
                ),
            );
            // error_log('$insights_for_js : ' . print_r($insights_for_js, true));

            wp_localize_script(HELPIE_FAQ_DOMAIN . '-bundle-admin-scripts', 'HelpieFaqInsights', $insights_for_js);



        }

        public function add_plugin_page()
        {
            $insights = __('Insights', 'pauple-helpie');
            // This page will be under "Settings"
            add_submenu_page('edit.php?post_type=helpie_faq', $insights, $insights,
                'manage_options', 'helpie-faq-insights', array($this, 'create_admin_page')
            );
        }

        /**
         * Options page callback.
         */
        public function create_admin_page()
        {
            $insights = $this->insights_controller->get_insights();
            echo $this->view->get_view($insights);
        }


        /**
         * Register and add settings.
         */
        public function page_init()
        {
            add_settings_section(
                'helpie_core_settings', // ID
                __('Helpie Insights', 'pauple-helpie'), // Title
                array($this, 'print_core_settings'), // Callback
                $this->page_name // Page
            );
        }


        public function print_core_settings()
        {
            echo "<span class='sub-title1'>".__('Insights to a better Knowledge base.', 'pauple-helpie')."</span>";
        }


    } // END CLASS
}