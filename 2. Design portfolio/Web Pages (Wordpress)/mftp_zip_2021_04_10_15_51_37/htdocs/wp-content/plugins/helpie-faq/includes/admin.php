<?php

namespace HelpieFaq\Includes;

//
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Helpie-faq admin.
 *
 * Helpie-FAQ admin handler class is responsible for initializing Helpie-FAQ in
 * WordPress admin.
 *
 * @since 1.0.0
 */

class Admin
{
    public function __construct($plugin_domain, $version)
    {
        $this->plugin_domain = $plugin_domain;
        $this->version = $version;

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        if (isset($_GET['post_type']) && $_GET['post_type'] == "helpie_faq") {
            error_log('set_admin_pointers');
            add_action('admin_enqueue_scripts', array($this, 'set_admin_pointers'), 10, 1);

            if (isset($_GET['page']) && $_GET['page'] == 'helpie-review-settings') {
                // Helpie-FAQ Pro feature popup Modal only rendering for helpie-faq admin setting page.
                add_action('admin_footer', array($this, 'load_faq_pro_feature_buy_modal'));
            }
        }
    }

    public function add_management_page()
    {
        $title = __('Helpie FAQ', $this->plugin_domain);

        $hook_suffix = add_management_page($title, $title, 'export', $this->plugin_domain, array(
            $this,
            'load_admin_view',
        ));

        add_action('load-' . $hook_suffix, array($this, 'load_assets'));
    }

    public function enqueue_scripts()
    {

        wp_enqueue_style($this->plugin_domain . '-bundle-styles', HELPIE_FAQ_URL . 'assets/admin.bundle.css', array(), $this->version, 'all');
        wp_enqueue_script($this->plugin_domain . '-bundle-admin-scripts', HELPIE_FAQ_URL . 'assets/admin.bundle.js', array('jquery'), $this->version, 'all');

        $nonce = wp_create_nonce('helpie_faq_nonce');

        wp_localize_script($this->plugin_domain . '-bundle-admin-scripts', 'helpie_faq_nonce', $nonce);
        wp_localize_script($this->plugin_domain . '-bundle-admin-scripts', 'my_faq_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

        wp_enqueue_style($this->plugin_domain . '-magnific-popup-styles', HELPIE_FAQ_URL . 'assets/libs/magnific-popup/magnific-popup.css', array(), $this->version, 'all');
        wp_enqueue_script($this->plugin_domain . '-magnific-popup-scripts', HELPIE_FAQ_URL . 'assets/libs/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), $this->version, 'all');

        do_action('helpie_faq_admin_localize_script');

        global $current_screen;
        // check current page is faq-group page or not. if true then, get the current page.
        $helpie_faq_group_page = false;
        $helpie_faq_page_action = 'show_faq_groups';
        $helpie_faq_group_create_link = esc_url(admin_url('edit-tags.php?taxonomy=helpie_faq_group&post_type=helpie_faq&helpie_faq_page_action=create'));
        if (isset($current_screen) && (isset($current_screen->post_type) && $current_screen->post_type == HELPIE_FAQ_POST_TYPE)) {
            if (isset($current_screen->id) && $current_screen->id == 'edit-helpie_faq_group') {
                $helpie_faq_group_page = true;
                $helpie_faq_page_action = $this->get_faq_group_current_page();
            }
        }

        $helpie_faq_group_js_args = array(
            'is_page' => $helpie_faq_group_page,
            'page_action' => $helpie_faq_page_action,
            'create_link' => $helpie_faq_group_create_link,
        );

        wp_localize_script($this->plugin_domain . '-bundle-admin-scripts', 'helpie_faq_group', $helpie_faq_group_js_args);
    }

    public function get_faq_group_current_page()
    {
        $page = 'show_faq_groups';
        if (isset($_GET['helpie_faq_page_action']) && !empty($_GET['helpie_faq_page_action']) && $_GET['helpie_faq_page_action'] == 'create') {
            $page = "create_faq_group";
        }
        return $page;
    }

    public function remove_kb_category_submenu()
    {
        remove_submenu_page('edit.php?post_type=helpie_faq', 'edit-tags.php?taxonomy=helpdesk_category&amp;post_type=helpie_faq');
    }

    public function set_admin_pointers($page)
    {
        error_log('set_admin_pointers');
        $pointer = new \HelpieFaq\Lib\Pointers\Pointers();
        $pointers = $pointer->return_pointers();

        //Arguments: pointers php file, version (dots will be replaced), prefix
        $manager = new \HelpieFaq\Lib\Pointers\Pointers_Manager($pointers, '1.0', 'hfaq_admin_pointers');
        $manager->parse();
        $pointers = $manager->filter($page);

        if (empty($pointers)) { // nothing to do if no pointers pass the filter
            return;
        }
        wp_enqueue_style('wp-pointer');
        $js_url = HELPIE_FAQ_URL . 'lib/pointers/pointers.js';

        wp_enqueue_script('hfaq_admin_pointers', $js_url, array('wp-pointer'), null, true);
        //data to pass to javascript
        $data = array(
            'next_label' => __('Next'),
            'close_label' => __('Close'),
            'pointers' => $pointers,
        );
        wp_localize_script('hfaq_admin_pointers', 'MyAdminPointers', $data);
    }

    public function load_faq_pro_feature_buy_modal()
    {

        $pro_feature_modal = new \HelpieFaq\Includes\Components\Pro_Feature_Buy_Modal;
        $faq_buy_modal = $pro_feature_modal->get_magnific_modal_content();

        echo $faq_buy_modal;
    }
}