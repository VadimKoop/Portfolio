<?php

namespace HelpieFaq\Features\Faq\Particles;

/**
 * FAQ REPO
 *
 */

if (!class_exists('\HelpieFaq\Features\Faq\Particles\Product_Faq_Relations')) {
    class Product_Faq_Relations 
    {

        public function get_product_faq_relation_terms($wp_query_args){

            // TODO Get Current Product Post Categories 
            $product_terms = get_the_terms ( $wp_query_args['products'], 'product_cat' );   
            
            // TODO Get all Category Ids by Current Product 
            $product_terms_ids = array();

            if(count($product_terms) > 0){
                foreach($product_terms as $product_terms){
                    $product_terms_ids[] = $product_terms->term_id;
                }
            }
            
            $product_faq_relations = isset($wp_query_args['product_faq_relations']) && !empty($wp_query_args['product_faq_relations']) ?$wp_query_args['product_faq_relations']: [];
            
            $is_product_faq_relation = false;
            $faq_terms = array();

            if(count($product_terms_ids) > 0){
                for($ii = 0; $ii < count($product_faq_relations); $ii++){
                    
                    // Check and Processing Specific Woo Categories
                    if(isset($product_faq_relations[$ii]['product_categories']) && $product_faq_relations[$ii]['link_type'] == 'specific_woo_category')
                    {
                        $product_categories = $product_faq_relations[$ii]['product_categories'];
                        $check_product_categories = $this->check_product_categories($product_categories,$product_terms_ids);
                        
                        if($check_product_categories && !in_array($product_faq_relations[$ii]['faq_category'],$faq_terms)){
                            $faq_terms[] = $product_faq_relations[$ii]['faq_category'];
                            $is_product_faq_relation = true;
                        }

                    }
                    
                    if($product_faq_relations[$ii]['link_type'] == 'all_woo_categories'){
                        // Config all Woo Categories for this Category 
                        if(!in_array($product_faq_relations[$ii]['faq_category'],$faq_terms)){
                            $faq_terms[] = $product_faq_relations[$ii]['faq_category'];
                        }
                    }
                }
            }
            
            return array(
                'faq_terms' => $faq_terms,
                'is_product_faq_relation' => $is_product_faq_relation
            );
        }


        public function check_product_categories($product_categories,$product_terms_ids){
            
            for($ii = 0; $ii < count($product_categories); $ii++){
                if(in_array($product_categories[$ii],$product_terms_ids)){
                    return true;
                }
            }

            return false;
        }

    }
}