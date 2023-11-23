<?php

namespace HelpieFaq\Features\Faq\Faq_Groups;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups')) {

    class Faq_Groups{

        public function load_hooks(){
            $helpie_faq_group_tax = 'helpie_faq_group';

            add_filter("manage_edit-{$helpie_faq_group_tax}_columns", array($this, 'add_faq_group_shortcode_column'),10,2);
            add_filter("manage_{$helpie_faq_group_tax}_custom_column", array($this, 'add_faq_group_shortcode_column_data'), 10, 3 );

            add_action("{$helpie_faq_group_tax}_edit_form", array($this,'edit_faq_group_form'),10,2);
            add_action("{$helpie_faq_group_tax}_add_form", array($this,'hide_slug_and_description_rows'),10,2);
            
            add_action("delete_{$helpie_faq_group_tax}",array($this,'delete_faq_group_posts'),10,4);

        }
        
        public function add_faq_group_shortcode_column($columns){
            $addedcolumns = array_slice($columns, 0, 4, true) +
            array("faq_group_shortcode" => __("Shortcode", "helpie-faq")) +
            array_slice($columns, 3, count($columns) - 1, true);
            // Remove Description Table Column
            unset($addedcolumns['description']);
            return $addedcolumns;
        }

        public function add_faq_group_shortcode_column_data($content,$column_name,$term_id){
            $html = '';

            if(empty($term_id)){
                return $html;
            }

            if($column_name != 'faq_group_shortcode'){
                return $html;
            }
        
            $html = '<span class="helpie-faq-group">';
            $html .= '<span class="shorcode-content">';
            $html .= "[helpie_faq group_id='".$term_id."'/]";
            $html .= '</span>';
            $html .= '<span class="clipboard dashicons dashicons-admin-page" title="Copy to Shortcode Clipboard" id="clipboard-'.$term_id.'"></span>';
            $html .= '</span>';
            echo $html;   
        }

        public function hide_slug_and_description_rows(){
            echo "<style>.term-slug-wrap { display:none; } .term-description-wrap { display:none; } #edittag{ max-width: 100%;}</style>";
        }

        public function edit_faq_group_form($taxonomy){
            echo "<style>.term-slug-wrap { display:none; } .term-description-wrap { display:none; } #edittag{ max-width: 100%;}</style>";
            
            $term_id = $taxonomy->term_id;

            $html = '';
            $html = $this->get_term_shortcode_clipboard_field($term_id);
            
            echo $html;

        }

        public function get_term_shortcode_clipboard_field($term_id){

            $shortcode_text = "[helpie_faq group_id='".$term_id."'/]";
            
            $html = '';
            $html .= '<table class="form-table helpie-faq-groups-table" role="presentation">';
            $html .= '<tbody>';
            $html .= '<tr class="form-field term-shortcode-wrap">';
            $html .= '<td>';
            $html .= '<div class="shortcode-clipboard-field"><input type="text" readonly id="faq-group-shortcode" value="'.$shortcode_text.'">';
            $html .= '<span class="clipboard-text" title="Copy Shortcode Clipboard">Copy Shortcode</span>';
            $html .= '</div>';
            $html .= '<p class="description">Paste this shortcode in any page to display this FAQ Group.</p>';
            $html .= '</td></tr>';
            $html .= '</tbody>';
            $html .= '</table>';

            return $html; 
        }

        

        public function delete_faq_group_posts($term, $tt_id, $deleted_term, $object_ids){
            
            if(count($object_ids) == 0){
                return;
            }
            
            // Removed posts links with faq group
            foreach ( $object_ids as $post_id ) {
                wp_delete_post( $post_id, false); 
            }
        
        }   

        public function init(){
            add_action('admin_menu',array($this,'add_submenu_for_creating_faq_group'));
        }

        public function get_post_ids_from_faq_group($args){

            $term_id = isset($args['group_id']) ?$args['group_id']: '';
            // get helpie_faq_group termmeta 
            $term_meta = get_term_meta($term_id,'helpie_faq_group_items');
            $faq_groups = isset($term_meta[0]['faq_groups'])? $term_meta[0]['faq_groups']: [];
            $post_ids = array();
            
            if(isset($faq_groups) && empty($faq_groups) && count($faq_groups) == 0){
                return $post_ids; 
            }
            
            for($ii = 0; $ii < count($faq_groups); $ii++){
                $faq = isset($faq_groups[$ii]['faq_item']) ? $faq_groups[$ii]['faq_item'] : '';
                if(isset($faq) && !empty($faq)){
                    $post_id = ( isset($faq['post_id']) && !empty($faq['post_id']) ) ? $faq['post_id'] : '';
                    $post_ids[] = $post_id;
                }   
            }
        
            return $post_ids;
        }

        public function add_submenu_for_creating_faq_group(){
            
            $create_faq_group_menu_label = __('Add New FAQ Group', 'pauple-helpie');
            
            add_submenu_page(
                'edit.php?post_type=helpie_faq', $create_faq_group_menu_label,$create_faq_group_menu_label,
                'manage_categories', 
                'edit-tags.php?taxonomy=helpie_faq_group&post_type=helpie_faq&helpie_faq_page_action=create'
            );
        }

        public function update_faq_group_items_using_faq_posts($postId){

            $post = get_post($postId);
            $terms = get_the_terms($post->ID, 'helpie_faq_group');
            
            if(isset($terms) && empty($terms)){
                return;
            }

            foreach($terms as $faq_group_term){
                // Post Found In Faq Groups then, update the current post content in faq group items.
                $faq_group_items = $this->get_faq_group_items($faq_group_term->term_id);
                    
                if(isset($faq_group_items) && !empty($faq_group_items)){
                    for($ii = 0; $ii < count($faq_group_items); $ii++){
                        $faq_item = isset($faq_group_items[$ii]['faq_item']) ? $faq_group_items[$ii]['faq_item'] : '';
                        if(isset($faq_item) && $post->ID == $faq_item['post_id']){
                            $faq_group_items[$ii]['faq_item'] = $this->get_post_content($post);
                        }
                    }
                }

                if(empty($faq_group_items)){
                    $faq_group_items[]['faq_item'] = $this->get_post_content($post);
                }
                update_term_meta($faq_group_term->term_id, 'helpie_faq_group_items',['faq_groups' => $faq_group_items]);
            }
        
        }

        public function get_faq_group_items($term_id){
            $term_meta = get_term_meta($term_id,'helpie_faq_group_items');
            $faq_group_items = isset($term_meta[0]['faq_groups'])? $term_meta[0]['faq_groups']: [];
            return $faq_group_items;
        }

        public function call_csf_hooks_in_faq_group(){
            $faq_group_prefix = 'helpie_faq_group_items';
        
            if(! function_exists( 'faq_group_published' ) ) {
                add_action("csf_{$faq_group_prefix}_saved",[$this,'faq_group_published'],10, 2 );
            }
            
            if(! function_exists( 'faq_group_process_is_done' ) ) {
                add_action("csf_{$faq_group_prefix}_save_after",[$this,'faq_group_process_is_done'],10, 2 );
            }
        }

        public function faq_group_process_is_done($request, $term_id){
            $faq_group_edit_url = admin_url("term.php?taxonomy=helpie_faq_group&tag_ID={$term_id}&post_type=helpie_faq");
            ?>
                <script>
                    var faq_group_edit_url = <?php echo json_encode($faq_group_edit_url); ?>;
                    if(faq_group_edit_url != ''){
                        location.replace(faq_group_edit_url);
                    }
                </script>
             <?php
            exit();
        }

        public function faq_group_published($request, $term_id){

            $post_faq_ids = array();
            $faq_groups = isset($request['faq_groups']) ? $request['faq_groups']: [];
            
            if(isset($faq_groups) && !empty($faq_groups) && count($faq_groups) > 0){

                for($ii = 0; $ii < count($faq_groups); $ii++){

                    $faq = isset($faq_groups[$ii]['faq_item']) ? $faq_groups[$ii]['faq_item'] : '';

                    if(isset($faq) && !empty($faq)){
                        
                        $post_title     = isset($faq['title']) ? $faq['title'] : '';
                        $post_content   = isset($faq['content']) ? $faq['content'] : '';

                        $post_id = ( isset($faq['post_id']) && !empty($faq['post_id']) ) ? $faq['post_id'] : '';
                        $post   = get_post($post_id);

                        $post_data = array(
                            'ID'            => $post_id,
                            'post_title'    => $post_title,
                            'post_content'  => $post_content
                        );

                        if( !empty($post_id) && isset($post) ){
                            /** Update post  */
                            wp_update_post($post_data);
                        } 

                        if( empty($post_id) && empty($post)){
                            $post_data['term_id'] = $term_id;
                            $post_id = $this->store_faq_post($post_data);
                            /**
                             *  Create a New helpie_faq post after update the post_id in faq_groups term metadata 
                             */
                             $faq_groups[$ii]['faq_item']['post_id'] = $post_id;
                        }

                        if($post_id){
                            $post_faq_ids[] = $post_id;
                        }
                    }

                }
            }

           
            update_term_meta($term_id, 'helpie_faq_group_items',['faq_groups' => $faq_groups]);

            $faq_group_updated_args = array(
                'term_id'   => $term_id,
                'post_ids'  => $post_faq_ids
            );
            
            $this->update_into_faq_group_items_to_posts($faq_group_updated_args);

        }

        public function update_into_faq_group_items_to_posts($faq_group_updated_args){

            /**
             * @param mixed $post_ids
             * current term post-ids
             */
            
            $post_ids = isset($faq_group_updated_args['post_ids']) ? $faq_group_updated_args['post_ids']: [];
            $term_id = $faq_group_updated_args['term_id'];
           
            // Get All Posts for that taxonomy and check with current active posts.
            $posts = $this->get_posts_by_faq_group_term_id($term_id);
           
            if(empty($posts) && count($posts) == 0){
                return;
            }
           

            foreach($posts as $post){
                // Get postId from the post
                if(isset($post)){
                    $postId = $post->ID;
                    /**
                     * Check the postId in $post_ids array , 
                     * If found then don't do anything, else then remove the post 
                     */
                    if(!in_array($postId,$post_ids)){
                        // wp_remove_object_terms($postId,$term_id,'helpie_faq_group');
                        
                        // Remove FAQ Posts 
                        wp_delete_post( $postId, true); 
                    }
                }
            }
        
        }

        public function remove_post_from_faq_group_item($postId){
            $post = get_post($postId);
            
            $terms = get_the_terms($post->ID, 'helpie_faq_group');

            if(isset($terms) && empty($terms) || count($terms) == 0){
                return;
            }

            foreach($terms as $faq_group_term){
                // Post Found In Faq Groups then, update the current post content in faq group items.
                $faq_group_items = $this->get_faq_group_items($faq_group_term->term_id);
                    
                if(isset($faq_group_items) && !empty($faq_group_items)){
                    for($ii = 0; $ii < count($faq_group_items); $ii++){
                        $faq_item = isset($faq_group_items[$ii]['faq_item']) ? $faq_group_items[$ii]['faq_item'] : '';
                        if(isset($faq_item) && $post->ID == $faq_item['post_id']){
                            unset($faq_group_items[$ii]);
                            // reindexing FAQ Groups 
                            $faq_group_items = array_values($faq_group_items);
                        }
                    }
                }

                update_term_meta($faq_group_term->term_id, 'helpie_faq_group_items',['faq_groups' => $faq_group_items]);
            }
        
        } 

        public function get_posts_by_faq_group_term_id($term_id){
            $post_args = array(
                'post_type' => 'helpie_faq',
                'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'helpie_faq_group',
                        'field' => 'term_id', 
                        'terms' => $term_id,
                        'include_children' => false
                    )
                )
            );
    
            $posts = get_posts($post_args);
            return $posts;
        }

        public function store_faq_post($args){
            $utils_helper = new \HelpieFaq\Includes\Utils\Helpers();
            $post_id = $utils_helper->insert_post_with_term(HELPIE_FAQ_POST_TYPE, $args['term_id'], 'helpie_faq_group', $args['post_title'], $args['post_content']);
            return $post_id;
        }

        public function get_post_content($post){
            return array(
                'post_id'   => $post->ID,
                'title'     => $post->post_title,
                'content'   => $post->post_content
            );
        }

        /**
         * FAQ Groups default arguments
         */
        public function get_default_args($args){

            $fields = $this->get_default_fields();
            $faq_group_term = get_term($args['group_id']);
            
            $faq_group_args = array(
                'group_id'  => $args['group_id'],
                'title'     => isset($faq_group_term->name) ? $faq_group_term->name : ''
            );

            $args = array_merge($fields,$faq_group_args);
            return $args;
        }

        private function get_default_fields(){
            $fields = [
                'group_id' => 0,
                'display_mode' => 'simple_accordion',
                'product_only' => false,
                'categories' => '',
                'sortby'    => 'post__in',
                'title'     => ''
            ];

            return $fields;
        }
    }

}