<?php

namespace HelpieFaq\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Helpie upgrades.
 *
 * Helpie upgrades handler class is responsible for updating different
 * Helpie versions.
 *
 * @since 1.0.0
 */
if (!class_exists('\HelpieFaq\Includes\Upgrades')) {
	class Upgrades {
		/**
		 * Add actions.
		 *
		 * Hook into WordPress actions and launch Helpie upgrades.
		 *
		 * @static
		 * @since 1.0.0
		 * @access public
		 */
		public static function add_actions() {
			// error_log('Upgrades add_actions...');
			add_action( 'init', [ __CLASS__, 'init' ], 20 );
		}
		/**
		 * Init.
		 *
		 * Initialize Helpie upgrades.
		 *
		 * Fired by `init` action.
		 *
		 * @static
		 * @since 1.0.0
		 * @access public
		 */
		public static function init() {
			$helpie_version = get_option( 'helpie_version' );
			// Normal init.
			if ( HELPIE_FAQ_VERSION === $helpie_version ) {
				return;
			}
			self::check_upgrades( $helpie_version );
			// Plugin::$instance->files_manager->clear_cache();
			update_option( 'helpie_version', HELPIE_FAQ_VERSION );
		}
		/**
		 * Check upgrades.
		 *
		 * Checks whether a given Helpie version needs to be upgraded.
		 *
		 * If an upgrade required for a specific Helpie version, it will update
		 * the `helpie_upgrades` option in the database.
		 *
		 * @static
		 * @since 1.0.10
		 * @access private
		 *
		 * @param string $helpie_version
		 */
		public static function check_upgrades( $helpie_version ) {
			// It's a new install.
			if ( ! $helpie_version ) {
				// return;
				$helpie_version = 0.5;
			}
			$helpie_upgrades = get_option( 'helpie_upgrades', [] );
			$upgrades = [
				'0.6' => 'upgrade_v06',
				'0.7' => 'upgrade_v07',
				'1.0' => 'upgrade_v10'
			];

			foreach ( $upgrades as $version => $function ) {
				if ( version_compare( $helpie_version, $version, '<' ) && ! isset( $helpie_upgrades[ $version ] ) ) {
					self::$function();
					$helpie_upgrades[ $version ] = true;
					update_option( 'helpie_upgrades', $helpie_upgrades );
				}
			}
		}


		private static function upgrade_v07() {

			$faq_wp_posts = get_posts(
				array(
					'post_type' => HELPIE_FAQ_POST_TYPE
				)
			);

			$meta_key = 'click_counter';

			foreach($faq_wp_posts as $post){
				// 1. Get current click_counter 
				$count = get_post_meta( $post->ID, $meta_key, true );

				// 2. Update click_counter with new format
				$new_click_counter = array(
					'30days' => $count,
					'1year' => $count
				);
				update_post_meta( $post->ID, $meta_key, $new_click_counter );
				// error_log('$new_click_counter : ' . print_r($new_click_counter, true));
			}
			
		}

		private static function upgrade_v06() {
			
			$faq_wp_posts = get_posts(
				array(
					'post_type' => HELPIE_FAQ_POST_TYPE
				)
			);

			foreach($faq_wp_posts as $posts){
				// update_post_meta();
				add_post_meta($posts->ID, 'click_counter', 0, true);
			}

			$terms = get_terms( array(
				'taxonomy' => 'helpie_faq_category',
				'hide_empty' => false,
			) );

			foreach($terms as $term){
				// update_post_meta();
				add_term_meta($term->term_id, 'click_counter', 0, true);
			}
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
		
	} // END CLASS
}