<?php

namespace HelpieFaq\Includes;

use \HelpieFaq\Includes\Settings\HRP_Getter;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly



if (!class_exists('\HelpieFaq\Includes\Settings')) {
    class Settings
    {
        public function __construct()
        {
            //  error_log('Settings __construct');
            // add_action('init', [$this, 'setup_options_init']);
            add_action('init', [$this, 'init']);
            // add_action('wp_loaded', [$this, 'wp_loaded']);
            // add_filter('csf_helpie-faq_sections', [$this, 'filter_args']);
            // $this->init();

            // $this->fields = new \HelpieFaq\Includes\Settings\Fields();
        }

        public function filter_args($content)
        {
            return $content;
        }

        public function setup_options_init()
        {
            // require_once HELPIE_FAQ_PATH . 'includes/settings/settings-config.php';
        }

        public function wp_loaded()
        {
        }
        public function init()
        {


            if (!function_exists('\CSF') && !class_exists('\CSF')) {
                require_once HELPIE_FAQ_PATH . 'lib/codestar-framework/codestar-framework.php';
            }

            // require_once 'settings-config.php';

            if (class_exists('\CSF')) {

                // Set a unique slug-like ID
                $prefix = 'helpie-faq';

                // Create options
                \CSF::createOptions($prefix, array(
                    'menu_title' => 'Settings',
                    'menu_parent' => 'edit.php?post_type=helpie_faq',
                    'menu_type' => 'submenu', // menu, submenu, options, theme, etc.
                    'menu_slug' => 'helpie-review-settings',
                    'framework_title' => 'Settings',
                    'theme' => 'light',
                    'show_search' => false, // TODO: Enable once autofill password is fixed
                ));

                // error_log('after creat options');

                $this->general_settings($prefix);
                $this->style_settings($prefix);
                $this->submission_settings($prefix);
                $this->integration_settings($prefix);
                $this->kb_integration_settings($prefix);
                $this->woo_integration_settings($prefix);
                $this->roadmap_settings($prefix);
                

                $faq_group_prefix = 'helpie_faq_group_items';
                $this->group_category_page($faq_group_prefix);
                // $this->faq_group_settings_in_posts();

                $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
                $faq_groups->call_csf_hooks_in_faq_group();
            }
        }


        public function submission_settings($prefix)
        {

            \CSF::createSection(
                $prefix,
                array(

                    'id' => 'usersubmisson',
                    'title' => __('User Submission', 'helpie-faq'),
                    'icon' => 'fa fa-sign-out',
                    'fields' => $this->submission_fields(),
                )

            );
        }


        public function integration_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    // 'parent' => 'user_access',
                    'id' => 'integrations',
                    'title' => __('Integrations', 'helpie-faq'),
                    'icon' => 'fa fa-plus',
                )

            );
        }

        public function kb_integration_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'parent' => 'integrations',
                    'id' => 'helpie_kb',
                    'title' => __('Helpie KB', 'helpie-faq'),
                    'icon' => 'fa fa-book',
                    'fields' => $this->kb_active_fields(),
                )

            );
        }

        public function woo_integration_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'parent' => 'integrations',
                    'id' => 'woocommerce',
                    'title' => __('WooCommerce', 'helpie-faq'),
                    'icon' => 'fa fa-cart-plus',
                    'fields' => $this->woo_active_fields(),
                )

            );
        }

        public function roadmap_settings($prefix)
        {
            \CSF::createSection(
                $prefix,
                array(
                    'id' => 'roadmap',
                    'title' => __('Roadmap', 'helpie-faq'),
                    'icon' => 'fa fa-map-signs',
                    'fields' => [
                        [
                            'type' => 'notice',
                            'style'   => 'info',
                            'content' => sprintf(__("You can vote on Helpie FAQ's next feature %s", 'helpie-faq'), '<a href="https://trello.com/b/5kFAtN80/faq-roadmap" target="_blank">' . __('here', 'helpie-faq') . '</a>'),
                        ]
                    ],
                )

            );
        }


        public function kb_active_fields()
        {
            if (!(\is_plugin_active('helpie/helpie.php'))) {
                $options[] = array(
                    'type' => 'notice',
                    'class' => 'danger',
                    'content' => __('In order use this feature you need to purchase and activate the <a href="https://checkout.freemius.com/mode/dialog/plugin/3014/plan/4858/?trial=paid" target="_blank">Helpie KB</a> plugin.', 'helpie-faq'),
                );
            }

            $options[] = array(
                'id' => 'kb_integration_switcher',
                'type' => 'switcher',
                'title' => __('Enable FAQ in Helpie KB', 'helpie-faq'),
                'label' => __('Show FAQ In Helpie KB Category Page', 'helpie-faq'),
                'default' => true,
            );

            $options[] = array(
                'id' => 'kb_cat_content_show',
                'type' => 'select',
                'title' => __('Show FAQ in Helpie KB Category Page', 'helpie-faq'),
                'options' => array(
                    'before' => __('Before Content', 'helpie-faq'),
                    'after' => __('After Content', 'helpie-faq'),
                ),
                'default' => 'before',
                'info' => __('Select show faq before or after content in kb category page', 'helpie-faq'),
                'dependency' => array('kb_integration_switcher', '==', 'true'),
            );

            // error_log('$options : ' . print_r($options, true));
            return $options;
        }



        public function woo_active_fields()
        {
            $pro_feature_sub_title = $this->pro_feature_sub_title();

            $faq_category_options = $this->get_faq_category_options();
            $product_category_options = array();
            if (\is_plugin_active('woocommerce/woocommerce.php')) {
                $product_category_options = $this->get_product_category_options();
            }
            
            $incr = 0;
            if (!(\is_plugin_active('woocommerce/woocommerce.php'))) {

                $options[$incr]['type']     = 'notice';
                $options[$incr]['class']    = 'danger';
                $options[$incr]['content']  = __('In order use this feature you need to activate the <a href="/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> plugin.', 'helpie-faq');

                $incr++;
            }


            $options[$incr]['id']    = 'woo_integration_switcher';
            $options[$incr]['type']  = 'switcher';
            $options[$incr]['title'] = __('Show FAQ in WooCommerce', 'helpie-faq');
            $options[$incr]['label']  = __('Show FAQ In WooCommerce product tab', 'helpie-faq');
            $options[$incr]['default'] = true;

            $incr++;

            $options[$incr]['id']    = 'woo_search_show';
            $options[$incr]['type']  = 'switcher';
            $options[$incr]['title'] = __('Show FAQ Search', 'helpie-faq');
            $options[$incr]['label'] = __('Show FAQ Search In WooCommerce product', 'helpie-faq');
            $options[$incr]['default'] = true;
            $incr++;

            $options[$incr]['id']    = 'tab_title';
            $options[$incr]['type']  = 'text';
            $options[$incr]['title'] = __('Tab Title', 'helpie-faq');
            $options[$incr]['default'] = __('FAQ', 'helpie-faq');
            $options[$incr]['dependency'] = array('woo_integration_switcher', '==', 'true');

            /***
             *  1. Check FAQ Categories is Found Or Not
             *  If Found then Show Repeater Option, else then show notices for categories not found.
             *  
             *  */ 
           
            if(count($faq_category_options) == 0){ 

                $incr++;
                $options[$incr]['type']     = 'subheading';
                $options[$incr]['content']  = __('Product Faq Relations', 'helpie-faq');

                $incr++;
                $options[$incr]['type']     = 'notice';
                $options[$incr]['style']    = 'default';
                $options[$incr]['content']  = __('In order to use this feature you need to add some <a href="/wp-admin/edit-tags.php?taxonomy=helpie_faq_category&post_type=helpie_faq">FAQs Categories</a>.', 'helpie-faq');

            }else{

                if(count($product_category_options) == 0){
                    
                    $incr++;
                    $options[$incr]['type']     = 'notice';
                    $options[$incr]['style']    = 'default';
                    $options[$incr]['content']  = __('In your site not found product categories, please add some categories for better customization of <b>Product FAQ Relations</b>.', 'helpie-faq');
    
                }

                $incr++;
                $options[$incr]['id']    = 'product_faq_relations';
                $options[$incr]['type']  = 'repeater';
                $options[$incr]['title'] = __('Product FAQ Relations', 'helpie-faq');
                $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
                $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
                

                $options[$incr]['fields'][0]['id']    = 'faq_category';
                $options[$incr]['fields'][0]['type']  = 'select';
                $options[$incr]['fields'][0]['title'] = __('FAQ Categories', 'helpie-faq');
                $options[$incr]['fields'][0]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
                $options[$incr]['fields'][0]['options'] = $faq_category_options;
                $options[$incr]['fields'][0]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
                $options[$incr]['fields'][0]['attributes'] = [];
                if (hf_fs()->is__premium_only() == false) {
                    $options[$incr]['fields'][0]['attributes']['disabled'] = false;
                    $options[$incr]['fields'][0]['attributes']['readonly'] = 'readonly';
                }

                $options[$incr]['fields'][1]['id']    = 'link_type';
                $options[$incr]['fields'][1]['type']  = 'select';
                $options[$incr]['fields'][1]['title'] = __('Link this Category to', 'helpie-faq');
                $options[$incr]['fields'][1]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
                $options[$incr]['fields'][1]['options']['all_woo_categories'] = __('All Woo Categories', 'helpie-faq');
                $options[$incr]['fields'][1]['options']['specific_woo_category'] = __('Specific Woo Categories', 'helpie-faq');
                $options[$incr]['fields'][1]['default'] = 'all_woo_categories';
                $options[$incr]['fields'][1]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
                $options[$incr]['fields'][1]['attributes'] = [];
                if (hf_fs()->is__premium_only() == false) {
                    $options[$incr]['fields'][1]['attributes']['disabled'] = false;
                    $options[$incr]['fields'][1]['attributes']['readonly'] = 'readonly';
                }

                $options[$incr]['fields'][2]['id']    = 'product_categories';
                $options[$incr]['fields'][2]['type']  = 'select';
                $options[$incr]['fields'][2]['title'] = __('Product of Woo Commerce Categories', 'helpie-faq');
                $options[$incr]['fields'][2]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
                $options[$incr]['fields'][2]['chosen']  = true;
                $options[$incr]['fields'][2]['multiple']  = true;
                $options[$incr]['fields'][2]['options'] = $product_category_options;
                $options[$incr]['fields'][2]['default'] = '';
                $options[$incr]['fields'][2]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
                $options[$incr]['fields'][2]['dependency'] = array('link_type', '==', 'specific_woo_category');
                $options[$incr]['fields'][2]['attributes'] = [];
                if (hf_fs()->is__premium_only() == false) {
                    $options[$incr]['fields'][2]['attributes']['disabled'] = true;
                    $options[$incr]['fields'][2]['attributes']['readonly'] = 'readonly';
                }
            }

            return $options;
        }


        public function style_settings($prefix)
        {
            $style_fields = $this->get_style_fields($prefix);
            
            \CSF::createSection(
                $prefix,
                array(
                    // 'parent' => 'user_access',
                    'id' => 'style',
                    'title' => __('Style', 'helpie-faq'),
                    'icon' => 'fa fa-paint-brush',
                    'fields' => $style_fields
                )

            );
        }
        public function general_settings($prefix)
        {

            // $fields = $this->get_general_settings();

            $fields = new \HelpieFaq\Includes\Settings\Fields();
            $general_settings_fields = $fields->get_general_settings();
            $faq_slug_settings_fields = $fields->get_faq_slug_settings();

            $general_settings_fields = array_merge($faq_slug_settings_fields,$general_settings_fields);

            \CSF::createSection(
                $prefix,
                array(
                    // 'parent' => 'user_access',
                    'id' => 'general',
                    'title' => __('General', 'helpie-faq'),
                    'icon' => 'fa fa-cogs',
                    'fields' => $general_settings_fields
                )

            );
        }

        

        public function submission_fields()
        {

            $pro_feature_sub_title = $this->pro_feature_sub_title();

            $incr = 0;

            $options[$incr]['id']    = 'show_submission';
            $options[$incr]['type']  = 'switcher';
            $options[$incr]['title'] = __('Submission', 'helpie-faq');
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['label'] = __('Enable / Disable User Submission form in FAQ', 'helpie-faq');
            $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $options[$incr]['default'] = true;
            $options[$incr]['attributes'] = [];
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['attributes']['disabled'] = true;
                $options[$incr]['attributes']['readonly'] = 'readonly';
            }
            $incr++;

            $options[$incr]['id']    = 'ask_question';
            $options[$incr]['type']  = 'checkbox';
            $options[$incr]['title'] = __('Ask Question With', 'helpie-faq');
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['options']['email'] = 'Email';
            $options[$incr]['options']['answer'] = 'Answer';
            $options[$incr]['default'] = array('email');
            $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $options[$incr]['attributes'] = [];
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['attributes']['disabled'] = true;
                $options[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;

            $options[$incr]['id']    = 'onsubmit';
            $options[$incr]['type']  = 'select';
            $options[$incr]['title'] = __('On Submission', 'helpie-faq');
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['options']['approval'] = __('Dont Require Approval', 'helpie-faq');
            $options[$incr]['options']['noapproval'] = __('Require Approval', 'helpie-faq');
            $options[$incr]['info']   = __('Approval Before Showing', 'helpie-faq');
            $options[$incr]['default'] = 'noapproval';
            $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $options[$incr]['attributes'] = [];
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['attributes']['disabled'] = true;
                $options[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;

            $options[$incr]['type']     = 'notice';
            $options[$incr]['class']    = 'info';
            $options[$incr]['content']    = 'Once Approved, Submitter will be notified through email';
            // $options[$incr]['dependency']  = array('ask_question|onsubmit', '==|==', 'email|noapproval');

            $incr++;

            $options[$incr]['id']   = 'submitter_email';
            $options[$incr]['type']    = 'fieldset';
            $options[$incr]['title']  = 'Submitter Notification';
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['class']  = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            // $options[$incr]['dependency']  = array('ask_question|onsubmit', '==|==', 'email|noapproval');
            $options[$incr]['fields'][0]['id']  = 'submitter_subject';
            $options[$incr]['fields'][0]['type']  = 'text';
            $options[$incr]['fields'][0]['title']  = __('Subject', 'helpie-faq');
            $options[$incr]['fields'][0]['validate']  = 'required';
            $options[$incr]['fields'][0]['attributes']  = [];
            $options[$incr]['fields'][0]['attributes']['placeholder']  = __('Subject title', 'helpie-faq');
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['fields'][0]['attributes']['disabled'] = true;
                $options[$incr]['fields'][0]['attributes']['readonly'] = 'readonly';
            }
            $options[$incr]['fields'][0]['default'] = __('The FAQ you submitted has been approved ', 'helpie-faq');

            $options[$incr]['fields'][1]['id']  = 'submitter_message';
            $options[$incr]['fields'][1]['type']  = 'textarea';
            $options[$incr]['fields'][1]['title']  = __('Message', 'helpie-faq');
            $options[$incr]['fields'][1]['validate']  = 'required';
            $options[$incr]['fields'][1]['attributes']  = [];
            $options[$incr]['fields'][1]['attributes']['placeholder']  = __('Subject title', 'helpie-faq');
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['fields'][1]['attributes']['disabled'] = true;
                $options[$incr]['fields'][1]['attributes']['readonly'] = 'readonly';
            }
            $options[$incr]['fields'][1]['default'] = __('A new FAQ you had submitted has been approved by the admin ', 'helpie-faq');

            $incr++;

            $options[$incr]['id']    = 'notify_admin';
            $options[$incr]['type']  = 'switcher';
            $options[$incr]['title'] = __('Notify Admin', 'helpie-faq');
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['default'] = true;
            $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $options[$incr]['attributes'] = [];
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['attributes']['disabled'] = true;
                $options[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;

            $options[$incr]['id']    = 'admin_email';
            $options[$incr]['type']  = 'text';
            $options[$incr]['title'] = __('Admin Mail', 'helpie-faq');
            $options[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $options[$incr]['default'] = get_option('admin_email');
            $options[$incr]['validate'] = 'required';
            $options[$incr]['dependency'] = array('notify_admin', '==', 'true');
            $options[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $options[$incr]['attributes'] = [];
            $options[$incr]['attributes']['placeholder'] = __('mail', 'helpie-faq');
            $options[$incr]['attributes']['type'] = 'email';
            $options[$incr]['attributes']['pattern'] = '[^@]+@[^@]+\.[a-zA-Z]{2,6}';
            if (hf_fs()->is__premium_only() == false) {
                $options[$incr]['attributes']['disabled'] = true;
                $options[$incr]['attributes']['readonly'] = 'readonly';
            }

            // error_log('[$options] : ' . print_r($options, true));

            return $options;
        }

        public function get_faq_category_options(){

            $faq_repo = new \HelpieFaq\Includes\Repos\Faq_Repo();
            
            $faq_categories = $faq_repo->get_faq_categories();

            $faq_category_options = array();
            
            if(count($faq_categories) > 0){
                foreach($faq_categories as $faq_category){
                    $faq_category_options[$faq_category->term_id] = $faq_category->name; 
                }
            }
            
            return $faq_category_options;

        }

        public function get_product_category_options(){

            $product_categories = get_categories(
                array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty'   => false
                )
            );

            $product_category_options = array();

            if(count($product_categories) > 0){
                foreach($product_categories as $product){
                    $product_category_options[$product->term_id] = $product->name; 
                }
            }
            
            return $product_category_options;
        }

        public function pro_feature_sub_title()
        {
            return '<span style="color: #5cb85c; font-weight: 600;">* Pro Feature</span>';
        }

        public function group_category_page($prefix){
             // Create taxonomy options
            
            $faq_group_item_fields = $this->get_faq_group_item_fields($prefix);

            \CSF::createTaxonomyOptions( $prefix, array(
                'taxonomy'  => 'helpie_faq_group',
                'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
                'class'     => 'hfaq-groups-container'
            ));

            // Create a section
            \CSF::createSection( $prefix, array(
                'title' => 'FAQ Group Items',
                'icon' => 'fa fa-list',
                'fields' => $faq_group_item_fields
            ));
        }

        public function get_faq_group_item_fields($prefix){

            $fields = array(
                array(
                    'id'        => 'faq_groups',
                    'type'      => 'repeater',
                    'class'     => 'hfaq-groups__repeaters',
                    'fields'    => array(
                        array(
                            'id'            => 'faq_item',
                            'type'          => 'accordion',
                            'class'         => 'hfaq-groups__accordion',
                            'accordions'    => array(
                                array(
                                    'title'     => 'FAQ Item',
                                    'icon'      => 'fa fa-quora',
                                    'fields'    => array(
                                        array(
                                            'id'    => 'post_id',  
                                            'type'  => 'text',
                                            'default' => '0', 
                                            'class'   => 'helpie-group-posts helpie-display-none',
                                            'attributes' => array(
                                                'style' => 'display:none;',
                                            )
                                        ),
                                        array(
                                            'id'    => 'title',
                                            'type'  => 'text',
                                            'class' => 'hfaq-groups__accordion--input-title',
                                            'before' => __('Title',HELPIE_FAQ_DOMAIN),
                                            'default' => 'Toggle Title',
                                        ),
                                        array(
                                            'id'    => 'content',
                                            'type'  => 'wp_editor',
                                            'before' => __('Content',HELPIE_FAQ_DOMAIN),
                                            'media_buttons' => false,
                                            'default' => 'Toggle Content',
                                            'height'   => '350px',
                                        ),
                                    )
                                ),
                              
                            )
                        )
                    )
                ),
            );

            return $fields;
        }

        public function get_style_fields($prefix){
            
            $fields = array();
            $pro_feature_sub_title = $this->pro_feature_sub_title();

            array_push($fields, 
                array(
                    'id' => 'theme',
                    'type' => 'select',
                    'title' => __('Theme', 'helpie-faq'),
                    'options' => array(
                        'light' => __('Light', 'helpie-faq'),
                        'dark' => __('Dark', 'helpie-faq'),
                    ),
                    'default' => 'light',
                    'info' => __('Select Theme of FAQ Layout Section', 'helpie-faq'),
                )
            );

            $incr  = 1;
            $fields[$incr]['type'] = 'subheading';
            $fields[$incr]['content'] = 'Toggle Icons';
            
            $incr++;
            $fields[$incr]['id']    = 'toggle_icon_type';
            $fields[$incr]['type']  = 'select';
            $fields[$incr]['title'] = __('Toggle Icon', 'helpie-faq');
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['options']['default'] = __('Default', 'helpie-faq');
            $fields[$incr]['options']['custom'] = __('Custom', 'helpie-faq');
            $fields[$incr]['default'] = 'default';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['attributes'] = [];
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;
            $fields[$incr]['id'] = 'toggle_open';
            $fields[$incr]['type'] = 'icon';
            $fields[$incr]['title'] = 'Toggle Open';
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['dependency'] = array('toggle_icon_type', '==', 'custom');
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;
            $fields[$incr]['id'] = 'toggle_off';
            $fields[$incr]['type'] = 'icon';
            $fields[$incr]['title'] = 'Toggle Off';
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['dependency'] = array('toggle_icon_type', '==', 'custom');
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }
            
            // Accordions Headers & Body Background, Content Styles.
            
            $incr++;
            $fields[$incr]['type'] = 'subheading';
            $fields[$incr]['content'] = 'Accordions Header & Body Styles';
            
            $incr++;
            $fields[$incr]['id'] = 'accordion_background';
            $fields[$incr]['type'] = 'color_group';
            $fields[$incr]['title'] = 'Accordion Background';
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['options']['header'] = __('Header Background', 'helpie-faq');
            $fields[$incr]['options']['body'] = __('Body Background', 'helpie-faq');
            $fields[$incr]['default']['header'] = __('#FFFFFF', 'helpie-faq');
            $fields[$incr]['default']['body'] = __('#fcfcfc', 'helpie-faq');
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }

            $incr++;
            $fields[$incr]['id'] = 'accordion_header_content_styles';
            $fields[$incr]['type'] = 'typography';
            $fields[$incr]['title'] = 'Accordion Header';
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['output'] = array('.helpie-faq .accordion .accordion__item .accordion__header .accordion__title');
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }


            $incr++;
            $fields[$incr]['id'] = 'accordion_body_content_styles';
            $fields[$incr]['type'] = 'typography';
            $fields[$incr]['title'] = 'Accordion Body';
            $fields[$incr]['subtitle']  = hf_fs()->is__premium_only() == false ? $pro_feature_sub_title : '';
            $fields[$incr]['class'] = hf_fs()->is__premium_only() == false ? 'helpie-disabled' : '';
            $fields[$incr]['output'] = array('.helpie-faq .accordion .accordion__item .accordion__body');
            if (hf_fs()->is__premium_only() == false) {
                $fields[$incr]['attributes']['disabled'] = true;
                $fields[$incr]['attributes']['readonly'] = 'readonly';
            }
            
            return $fields;

        }
    } // END CLASS
}