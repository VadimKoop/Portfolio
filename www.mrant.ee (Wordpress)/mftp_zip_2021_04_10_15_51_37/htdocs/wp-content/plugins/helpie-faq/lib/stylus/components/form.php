<?php

namespace Stylus\Components;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Stylus\Components\Form')) {

    class Form
    {

        public function get_form($viewProps)
        {
            $collection = $viewProps['collection'];

            $attr1 = '';
            $attr2 = '';
            $additional_class = "";

            if (isset($collection['context']['kb-category']) || isset($collection['context']['kb-category'])) {
                $attr1 = "data-kb-category = '" . $collection['context']['kb-category'] . "'";
            }

            if (isset($collection['context']['woo-product']) || isset($collection['context']['woo-product'])) {
                $attr1 = "data-woo-product = '" . $collection['context']['woo-product'] . "'";
            }

            if (isset($collection['theme']) && $collection['theme'] == "dark") {
                $additional_class = " dark";
            }

            $form = $this->get_toggle_button_field();
            $form .= "<form class='form__section " . $additional_class . "' " . $attr1 . " " . $attr2 . ">";
            $form .= $this->get_text_field();

            if (isset($collection['ask_question']) && !empty($collection['ask_question'])) {

                foreach ($collection['ask_question'] as $field) {
                    if ($field == 'email') {
                        $form .= $this->get_email_field();
                    }
                    if ($field == 'answer') {
                        $form .= $this->get_textarea_field();
                    }
                }
            }

            $form .= $this->get_submit_button_field();

            $form .= "</form>";

            return $form;
        }

        public function get_toggle_button_field()
        {
            $value = __('Add FAQ', 'helpie-faq');
            
            $html = "<p align='center'>";
            $html .= "<input class='form__toggle' type='button' value='" . $value . "'/>";
            $html .= "</p>";

            return $html;
        }

        public function get_submit_button_field($value = "Submit")
        {
            $html = "<p>";
            $html .= "<input class='form__submit' type='submit' value='" . $value . "'/>";
            $html .= "</p>";

            return $html;
        }

        public function get_text_field($label = 'Question ? ( required )')
        {
            $html = "<p>";
            $html .= "<label> " . $label . " <br> ";
            $html .= "<span>";
            $html .= " <input name='faq_question' class='form__text' type='text' required /> ";
            $html .= "</span>";
            $html .= "</label>";
            $html .= "</p>";

            return $html;
        }

        public function get_email_field($label = 'Your Email ( required )')
        {
            $html = "<p>";
            $html .= "<label> " . $label . " <br> ";
            $html .= "<span>";
            $html .= " <input name='faq_email' class='form__email' type='email' required pattern='[^@]+@[^@]+\.[a-zA-Z]{2,6}'/> ";
            $html .= "</span>";
            $html .= "</label>";
            $html .= "</p>";

            return $html;
        }

        public function get_textarea_field($label = 'Answer')
        {
            $html = "<p>";
            $html .= "<label> " . $label . " <br> ";
            $html .= "<span>";
            $html .= "<textarea name='faq_answer' class='form__textarea'></textarea>";
            $html .= "</span>";
            $html .= "</label>";
            $html .= "</p>";

            return $html;
        }
    }
}
