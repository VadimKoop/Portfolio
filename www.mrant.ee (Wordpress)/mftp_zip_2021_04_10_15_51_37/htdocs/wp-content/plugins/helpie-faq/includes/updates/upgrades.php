<?php

namespace HelpieFaq\Includes\Updates;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Includes\Updates\Upgrades')) {
    class Upgrades{

        public static function add_actions()
        {
            add_action('init', [__CLASS__, 'init'], 20);
        }
        /**
         * Init.
         *
         * Initialize Helpie upgrades.
         *
         * Fired by `init` action.
         *
         * @static
         * @since 1.0
         * @access public
         */
        public static function init(){
            
            $helpie_faq_plugin_version = get_option('helpie_faq_plugin_version');
            
            // Normal init.
            if (HELPIE_FAQ_VERSION === $helpie_faq_plugin_version) {
               return;
            }

            
            $upgrade_success = self::check_upgrades($helpie_faq_plugin_version);

            if ($upgrade_success) {
                self::update_current_plugin_version();
            }
        }
        /**
         * Check upgrades.
         *
         * Checks whether a given Helpie version needs to be upgraded.
         *
         * If an upgrade required for a specific Helpie version, it will update
         * the `helpie_faq_plugin_version` option in the database.
         *
         * @static
         * @since 1.0
         * @access private
         *
         * @param string $helpie_faq_version
         */
        public static function check_upgrades($helpie_faq_plugin_version)
        {
            $upgrade_success = false;
            $helpie_faq_upgrades = get_option('helpie_faq_upgrades', []);
            
            $upgrades = [
                '1.0' => 'upgrade_v10'
            ];
          
            $allow_fresh_install_action = true;
          
            // $is_fresh_installation = (!$helpie_faq_plugin_version && $allow_fresh_install_action);
            
            $version_checking_operator = '<';

            // 1. Action for 1.0 and below 1.0 
            if (!$helpie_faq_plugin_version && self::is_less_then_equal_to_faq_v10()) {
                $helpie_faq_plugin_version = '1.0';    
                $allow_fresh_install_action = false;
                $version_checking_operator = '<=';
            }

            // 2. It's a new install.
            if( !$helpie_faq_plugin_version && $allow_fresh_install_action){

                self::fresh_install_action($upgrades);
                return true;
            }
            
            // 3. Upgrades    
            // TODO: Uncomment after testing
            foreach ($upgrades as $version => $function) {
                
                if (version_compare($helpie_faq_plugin_version, $version, $version_checking_operator) && !isset($helpie_faq_upgrades[$version])) {
                    
                    $result = self::$function(); // fire sequencial upgrade from given array value

                    if ($result == false) {
                        break;
                    } else {
                        $upgrade_success = true;
                    }
                    
                    $helpie_faq_upgrades[$version] = true;
                    \update_option('helpie_faq_upgrades', $helpie_faq_upgrades);
                }
            }
            
            return $upgrade_success;
        }


        private static function fresh_install_action($upgrades)
        {
            
            foreach ($upgrades as $version => $method_name) {
                $helpie_faq_upgrades[$version] = true;
            }
            
            update_option('helpie_faq_upgrades', $helpie_faq_upgrades);
            self::update_current_plugin_version();
        }


        private static function update_current_plugin_version(){
            update_option('helpie_faq_plugin_version', HELPIE_FAQ_VERSION);
        }

        private static function is_less_then_equal_to_faq_v10(){
            
            $settings = get_option('helpie-faq');

            /* Check Setting Option in Below 1.0 */
            // 1. Open by Default option not found in below faq version 1.0
            
            if (!isset($settings['open_by_default'])) {
                return true;
            }

            return false;
        }

        private static function upgrade_v10(){
          
          
            $settings = get_option('helpie-faq');

            $settings['open_by_default'] = 'none';
            if(isset($settings['open_first']) && $settings['open_first'] == true){
                $settings['open_by_default'] = 'open_first';
            }

            /* Set new version */
            $settings['last_version'] = '1.0';
            
            $result = \update_option('helpie-faq', $settings);
            $updated_option = get_option('helpie-faq');

            if (isset($updated_option['last_version']) && $updated_option['last_version'] == '1.0') {
                $result = true;
            }

            return $result;
        }
    }
}
