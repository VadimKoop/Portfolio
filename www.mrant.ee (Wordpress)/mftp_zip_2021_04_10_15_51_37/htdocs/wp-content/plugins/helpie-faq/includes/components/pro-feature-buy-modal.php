<?php

namespace HelpieFaq\Includes\Components;


if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('\HelpieFaq\Includes\Components\Pro_Feature_Buy_Modal')) {

    class Pro_Feature_Buy_Modal
    {

        public function get_magnific_modal_content($args = array())
        {

            $html = '';
            $html .= '<div class="hfaq-popup-modal mfp-hide" id="hfaq-pro-popup-notice">';

            $html .= '<div class="popup-title">Buy Helpie Faq Pro</div>';
            $html .= '<div class="popup-content">';
            $html .= '<a href="#" target="_blank">';
            $html .= '<img class="popup-image" src="https://ps.w.org/helpie-faq/assets/icon-128x128.png"/>';
            $html .= '</a>';
            $html .= '<div class="popup-content-wrapper">';
            $html .= '<p style="padding-bottom:10px;">Upgrade to Pro version to access this feature.</p>';
            $html .= '<a class="hfaq button-primary" href="' . admin_url('edit.php?post_type=helpie_faq&page=helpie_faq-pricing') . '">Buy Helpie FAQ Pro</a>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            return $html;
        }


        public function get_buy_modal($args = array())
        {

            
            $html = '';
            $html .= '<div class="ui modal">';
            $html .= '<i class="close icon"></i>';

            $html .= '<div class="header">';
            $html .= 'Header Content';
            $html .= '</div>';

            $html .= '<div class="image content">';
            $html .= '<div class="description">';
            $html .= '<div class="ui header">';
            $html .= '<p>Is it okay to use this photo</p>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="ui small image">';
            $html .= '<img src="/images/avatar/large/chris.jpg">';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="actions">';
            $html .= '<div class="ui black deny button">';
            $html .= 'Nope';
            $html .= '</div>';
            $html .= '<div class="ui positive right labeled icon button">';
            $html .= 'Click';
            $html .= '<i class="checkmark icon"></i>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';

            return $html;
        }
    }
}
