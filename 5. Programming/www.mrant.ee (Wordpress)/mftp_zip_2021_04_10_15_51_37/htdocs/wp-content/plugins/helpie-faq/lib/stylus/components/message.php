<?php

namespace Stylus\Components;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\Stylus\Components\Message')) {

    class Message
    {

        public function get_message()
        {
            $html = '<div class="success-message active" style="display:none;">';
            $html .= $this->get_icon();
            $html .= $this->get_title();
            $html .= $this->get_content();
            $html .= '</div>';

            return $html;
        }

        protected function get_icon()
        {
            $html = '<svg viewBox="0 0 76 76" class="success-message__icon icon-checkmark">';
            $html .= '<circle cx="38" cy="38" r="36"/>';
            $html .= '<path fill="none" stroke="#FFFFFF" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M17.7,40.9l10.9,10.9l28.7-28.7"/>';
            $html .= '</svg>';

            return $html;
        }

        protected function get_title($title = "Submitted Successfully")
        {
            $html = '<p class="success-message__title"> ' . $title . '</p>';
            return $html;
        }

        protected function get_content($content = "We will respond soon")
        {
            $html = '<div class="success-message__content">';
            $html .= '<p>' . $content . '</p>';
            $html .= '</div>';

            return $html;
        }
    }
}
