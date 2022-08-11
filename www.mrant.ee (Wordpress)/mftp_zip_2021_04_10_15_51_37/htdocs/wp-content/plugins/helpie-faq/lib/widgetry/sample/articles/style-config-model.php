<?php
namespace Helpie\Features\Kb\Articles;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\Helpie\Features\Kb\Articles\Style_Config_Model')) {
    class Style_Config_Model
    {
        public function get_config()
        {
            $style_config = array(
                'collection' => array(
                    'name' => 'helpie_articles_listing',
                    'selector' => '.helpie-articles-listing',
                    'label' => __('Article Listing', 'pauple-helpie'),
                    'styleProps' => array( 'background', 'border', 'padding', 'margin')
                ),
                'title' => array(
                    'name' => 'article_listing_title',
                    'selector' => '.helpie-articles-listing .collection-title',
                    'label' => __('Title', 'pauple-helpie'),
                    'styleProps' => array( 'color', 'typography', 'text-align', 'border', 'padding', 'margin')
                ),
                'title_icon' => array(
                    'name' => 'article_listing_title_icon',
                    'selector' => '.helpie-articles-listing .collection-title i',
                    'label' => __('Title Icon', 'pauple-helpie'),
                    'styleProps' => array( 'icon', 'position', 'color')
                ),
                'element' => array(
                    'name' => 'helpie_element',
                    'selector' => '.helpie-articles-listing .helpie-element',
                    'label' => __('Single Item', 'elementor'),
                    'styleProps' => array( 'background', 'border', 'padding', 'margin'),
                    'children' => array(
                        'title' => array(
                            'name' => 'helpie_element_title',
                            'selector' => '.helpie-articles-listing .helpie-element .header',
                            'label' => __('Single Item Title', 'pauple-helpie'),
                            'styleProps' => array( 'color', 'typography', 'text-align', 'border')
                        ),
                        'content' => array(
                            'name' => 'helpie_element_content',
                            'selector' => '.helpie-articles-listing .helpie-element .item-content',
                            'label' => __('Single Item Content', 'pauple-helpie'),
                            'styleProps' => array( 'text-align'),
                        ),
                        'icon' => array(
                            'name' => 'helpie_element_icon',
                            'selector' => '.helpie-articles-listing .helpie-element i',
                            'label' => __('Single Item Icon', 'pauple-helpie'),
                            'styleProps' => array( 'color'),
                        ),

                    )
                )
            );

            return $style_config;
        }
    }
}
