<?php
namespace HelpieFaq\Features\Faq;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Faq\Style_Config_Model')) {
    class Style_Config_Model
    {
        public function get_config()
        {
            $style_config = array(
                // 'collection' => array(
                //     'name' => 'helpie_faq',
                //     'selector' => '.helpie-faq.accordions .accordion',
                //     'label' => __('FAQ', 'helpie-faq'),
                //     'styleProps' => array( 'background', 'border', 'padding', 'margin')
                // ),
                                
                // 'title_icon' => array(
                //     'name' => 'helpie_faq_title_icon',
                //     'selector' => '.helpie-faq.accordions .collection-title i',
                //     'label' => __('Title Icon', 'helpie-faq'),
                //     'styleProps' => array( 'icon', 'position', 'color')
                // ),

                'title' => array(
                    'name' => 'helpie_faq_title',
                    'selector' => '.helpie-faq.accordions .collection-title',
                    'label' => __('Title', 'helpie-faq'),
                    'styleProps' => array( 'color', 'typography', 'text-align', 'border', 'background', 'padding', 'margin')
                ),
                
                'element' => array(
                    'name' => 'helpie_element',
                    'selector' => '.helpie-faq.accordions .accordion__item',
                    'label' => __('Single Item', 'helpie-faq'),
                    // 'styleProps' => array( 'background', 'border', 'padding', 'margin'),
                    'children' => array(
                        'header' => array(
                            'name' => 'helpie_element_header',
                            'selector' => '.helpie-faq.accordions .accordion__header',
                            'label' => __('Single Item - Header', 'helpie-faq'),
                            'styleProps' => array( 'background', 'color', 'typography', 'text-align', 'border')
                        ),
                        'content' => array(
                            'name' => 'helpie_element_content',
                            'selector' => '.helpie-faq.accordions .accordion__body',
                            'label' => __('Single Item - Body', 'helpie-faq'),
                            'styleProps' => array( 'background', 'color', 'typography','text-align'),
                        )

                    )
                )
            );

            return $style_config;
        }
    }
}
