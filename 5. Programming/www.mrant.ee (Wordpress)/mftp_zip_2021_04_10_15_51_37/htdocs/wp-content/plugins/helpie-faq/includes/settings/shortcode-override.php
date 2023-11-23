<?php

namespace HelpieFaq\Includes\Settings;

if (!class_exists('\HelpieFaq\Includes\Settings\Shortcode_Override')) {
    class Shortcode_Override
    {
        public function __construct()
        {
            add_filter('hfps_shortcode_options', array($this, 'override'));
        }

        public function override()
        {
            $shortcodes[] = array(
                'title' => '',
                'shortcodes' => array(
                    array(
                        'name' => 'helpie_faq',
                        'title' => 'Helpie FAQ',
                        'fields' => $this->get_fields(),
                    ),
                ),
            );

            return $shortcodes;
        }

        private function get_fields()
        {
            $fields_model = new \HelpieFaq\Features\Faq\Fields_Model();
            $all_fields = $fields_model->get_fields();

            $fields = array();

            foreach ($all_fields as $key => $option) {

                $option = $this->change_key($option, 'name', 'id');
                $option = $this->change_key($option, 'label', 'title');

                if ($option['type'] == 'multi-select') {
                    $option['type'] = 'select';
                    $option['attributes'] = ['multiple' => 'multiple'];
                    $option['class'] = 'chosen';
                }

                array_push($fields, $option);
            }

            return $fields;
        }

        private function change_key($array, $old_key, $new_key)
        {
            if (!array_key_exists($old_key, $array)) {
                return $array;
            }

            $keys = array_keys($array);
            $keys[array_search($old_key, $keys)] = $new_key;

            return array_combine($keys, $array);
        }

    }
}
