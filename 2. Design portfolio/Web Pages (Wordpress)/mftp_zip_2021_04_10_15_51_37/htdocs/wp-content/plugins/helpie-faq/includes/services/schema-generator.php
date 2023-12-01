<?php

namespace HelpieFaq\Includes\Services;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Services\Schema_Generator')) {
    class Schema_Generator{
        
        public $props = array();

        public function __contruct(){}

        /**
         * Set $data values in global $helpie_faq_schema
         *
         * @param [array] $data
         * 
         */
        public function set($data){            
            global $helpie_faq_schema;
            if(!empty($data)){
                array_push($this->props, $data);
                $helpie_faq_schema = $this->props;
            }
            return true;
        }

        /** Get all collections from the global $helpie_faq_schema */
        public function get(){
            global $helpie_faq_schema;
            if(empty($helpie_faq_schema)){
                return [];
            }
            return $helpie_faq_schema;
        }

        public function get_faq_items($helpie_faq_schema){
           
            if(empty($helpie_faq_schema)){
                return [];
            }

            $faq_schema_entities = array();
            $schema = new \HelpieFaq\Includes\Services\Schema_Service();
                
            for($ii = 0; $ii < count($helpie_faq_schema); $ii++){
                $faq_schema_entities[] = $schema->get_faq_schema_entity_data($helpie_faq_schema[$ii]);
            }

            /** removing duplicate faqs */
            $faq_schema_entities = $this->remove_duplicates($faq_schema_entities);

            return $faq_schema_entities;
        }

        public function remove_duplicates($faq_schema_entities){
            $filtered_faqs = [];
            for($ii = 0; $ii < count($faq_schema_entities); $ii++){
                foreach($faq_schema_entities[$ii] as $id => $faq){
                    if(!isset($filtered_faqs[$id])){
                        $filtered_faqs[$id] = $faq;
                    } 
                }
            }
            
            $filtered_faqs = array_values($filtered_faqs);
            return $filtered_faqs;
        }
        
    }
}