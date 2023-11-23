<?php

namespace HelpieFaq\Lib\Pointers;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('HelpieFaq\Lib\Pointers')) {

    class Pointers
    {

        public function return_pointers()
        {
            error_log("return_pointers");
            $pointers = array();

            $pointers['tutorial-seven'] = array(
                'title' => "<h3>" . 'Helpie FAQ Intro' . "</h3>",
                'content' => "<div><p>Here’s how you can create a FAQ section in any page:</p><p>1. Create a new FAQ group here using our simple Drag and Drop Interface -> Save it -> And then copy the Shortcode that is generated.</p></div><div class='hfaq-pointer-count'><p>1 of 2</p></div>",
                'anchor_id' => '#menu-posts-helpie_faq ul.wp-submenu li:nth-child(3)',
                'edge' => 'left',
                'align' => 'left',
                'nextTab' => 'Add New',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-eight'] = array(
                'title' => "<h3>" . 'Done!' . "</h3>",
                'content' => "<div><p>2. Paste the copied shortcode in any Page, Post or Custom Post type where you want to display your FAQ's.</p><p><i>Done!</i></p><p align='center'><img src='" . HELPIE_FAQ_URL . "/assets/img/walk-through-screenshot.png' width='100%'/></p></div><div class='ufaq-pointer-count'><p>2 of 2</p></div>",
                'anchor_id' => '#menu-pages',
                'edge' => 'top',
                'align' => 'left',
                'nextTab' => 'FAQ Categories',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            return $pointers;
        }

        public function old_pointers()
        {
            $pointers = array();

            $pointers['tutorial-one'] = array(
                'title' => "<h3>" . 'Helpie FAQ Intro' . "</h3>",
                'content' => "<div><p>Thanks for installing Helpie FAQ! These 6 slides will help get you started using the plugin.</p></div><div class='hfaq-pointer-count'><p>1 of 6</p></div>",
                'anchor_id' => '#menu-posts-helpie_faq .wp-menu-name',
                'edge' => 'left',
                'align' => 'left',
                'nextTab' => 'Add New',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-two'] = array(
                'title' => "<h3>" . 'Create FAQs' . "</h3>",
                'content' => "<div><p>Click 'Add New ' to create new FAQs. Enter the FAQ question in the Title area and FAQ answer in the main post content area. Create or select FAQ Categories and add FAQ Tags in the right-side menu.</p></div><div class='ufaq-pointer-count'><p>2 of 6</p></div>",
                'anchor_id' => '#menu-posts-helpie_faq ul.wp-submenu li:nth-child(5)',
                'edge' => 'left',
                'align' => 'left',
                'nextTab' => 'FAQ Categories',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-three'] = array(
                'title' => "<h3>" . 'Set Up Categories' . "</h3>",
                'content' => "<div><p>'The FAQ Categories' help to organize FAQs. You can assign Categories to FAQs and use them to group your FAQs to display on your FAQ page.</p></div><div class='ufaq-pointer-count'><p>3 of 6</p></div>",
                'anchor_id' => '#menu-posts-helpie_faq ul.wp-submenu li:nth-child(6)',
                'edge' => 'left',
                'align' => 'left',
                'nextTab' => 'Page',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-four'] = array(
                'title' => "<h3>" . 'Display FAQs' . "</h3>",
                'content' => "<div><p>Place the [helpie_faq] shortcode in the content area of any <b>page, post, etc..</b> to display all your FAQs or use Helpie Gutenberg Block / Shortcode Builder to customize your FAQs. </p><p align='center'> <img src='" . HELPIE_FAQ_URL . "/assets/img/insert-faq.jpg'/><p></div><div class='ufaq-pointer-count'><p>4 of 6</p></div>",
                'anchor_id' => '#menu-pages',
                'edge' => 'left',
                'align' => 'top',
                'nextTab' => 'FAQ Settings',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-five'] = array(
                'title' => "<h3>" . 'Customize Options' . "</h3>",
                'content' => "<div><p>The 'FAQ settings' area has options to customize the plugin perfectly for your site, which includes,<ul style='list-style-type:disc; padding-left: 3em;'><li>FAQ search On/Off</li><li>Ordering FAQs and Toggle</li><li>Styling and Integrations and more!</li></ul></p></div><div class='ufaq-pointer-count'><p>5 of 6</p></div>",
                'anchor_id' => '#menu-posts-helpie_faq ul.wp-submenu li:nth-child(8)',
                'edge' => 'left',
                'align' => 'left',
                'nextTab' => 'Dashboard',
                'width' => '320',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            $pointers['tutorial-six'] = array(
                'title' => "<h3>" . 'Need More Help?' . "</h3>",
                'content' => "<div><p><a href='https://wordpress.org/support/plugin/helpie-faq/reviews/#new-post'>Help us spread the word with a 5 star rating!</a><br><br>Here is a video on how to install and get started with Helpie FAQ:<br /><iframe width='560' height='315' src='https://www.youtube.com/embed/oN-e6Fmdolk' frameborder='0' allowfullscreen></iframe></p></div><div class='hfaq-pointer-count'><p>6 of 6</p></div>",
                'anchor_id' => '#wp-admin-bar-site-name',
                'edge' => 'top',
                'align' => 'left',
                'nextTab' => 'Dashboard',
                'width' => '600',
                'where' => array('edit.php', 'post-new.php', 'edit-tags.php', 'helpie_faq_page_helpie-faq-settings'),
            );

            return $pointers;
        }
    }
}