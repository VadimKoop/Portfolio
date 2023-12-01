<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Insights_Tease_Page')) {
    class Insights_Tease_Page extends \HelpieFaq\Features\Insights\Admin_Page
    {
        private $options;
        private $page_name = 'insights_model_page';
        private $opts_grp = 'pauple_insights_model';

        public function __construct()
        {
            parent::__construct();

            add_action('admin_menu', array($this, 'add_plugin_insigts_tease_page'));
            add_action('admin_init', array($this, 'page_init'));
        }

        public function add_plugin_insigts_tease_page()
        {
            $insights = __('Insights', 'pauple-helpie');
            // This page will be under "Settings"
            add_submenu_page(
                'edit.php?post_type=helpie_faq',
                $insights,
                $insights,
                'manage_options',
                'helpie-faq-insights',
                array($this, 'create_admin_insights_tease_page')
            );
        }

        public function create_admin_insights_tease_page()
        {
            echo $this->get_view();
        }

        public function get_view()
        {
            $html = '';
            $html = "<div class='helpie-faq dashboard'>";
            $html .= $this->content();
            $html .= '</div>';

            return $html;
        }

        public function content()
        {

            $insight_image = HELPIE_FAQ_URL . '/assets/img/insights.png';

            $html = '';
            $html = '<section id="content-tease">';
            $html .= $this->faq_pro_buy_notice_info();
            $html .= '<img src="' . $insight_image . '" alt="FAQ Insights" title="FAQ Insights">';
            $html .= '</section>';

            return $html;
        }

        public function faq_pro_buy_notice_info()
        {

            $html = '';
            $html = "<div class='helpie-notice notice notice-success'>";
            $html .= '<p style="font-weight:bold;">';
            $html .= __('In order use this feature you need to purchase and activate the <a href="' . admin_url('edit.php?post_type=helpie_faq&page=helpie_faq-pricing') . '">Buy Helpie FAQ Pro</a> plugin.', 'helpie-faq');
            $html .= '</p>';
            $html .= '</div>';

            return $html;
        }



        /**
         * Register and add settings.
         */
        public function page_init()
        {
            add_settings_section(
                'helpie_core_settings', // ID
                __('Helpie Insights', 'pauple-shelpie'), // Title
                array($this, 'print_core_settings'), // Callback
                $this->page_name // Page
            );
        }


        public function print_core_settings()
        {
            echo "<span class='sub-title1'>" . __('Insights to a better Knowledge base.', 'pauple-helpie') . "</span>";
        }
    }
}
