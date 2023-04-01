<?php

namespace Black_Widgets\admin;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();

echo '<div class="wrap bw-box-backend">';

    echo '<h1 class="bw-backend-heading">Hello '.$current_user->nickname.',</h1>';
    echo '<div class="card">';
        echo '<h2 class="title">DSGN + BW</h2>';
        echo '<p>Dsgn is a simple way to start a WordPress website that is easy to start, quick to use, and accessible to everyone. You will soon see many adaptations, such as other Elementor Add-Ons and compatible WordPress Themes.</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/dsgn/" target="_blank" class="bw-btn bw-uq">New releases</a><a href="https://modernaweb.net/dsgn/user-account/register/" target="_blank" class="bw-btn">Create your free account</a></p>';
        // echo '<p class="bw-btn-box">Codedsgn → Inspiration & Dummy Demos, it\'s trendy and creative. You will see many adaptations soon, like other Elementor Add-Ons and compatible WordPress Themes.</p>';
        echo '<a href="https://modernaweb.net/dsgn/" target="_blank" class="bw-img"><img class="bw-banner" src="'.plugin_dir_url(__FILE__ ) . 'img/dsgn-in-black-widgets.jpg'.'" /></a>';
    echo '</div>';
    echo '<div class="card">';
        echo '<h2 class="title" style="color: #2848FF;">Onward WordPress Theme</h2>';
        echo '<p class="box-2">Onward is a WordPress Theme compatible with Elementor and Header Builder — Simple & Clean. Onward is a simple & light WordPress theme with a clean and neat design. Some of its features are compatibility with block editor and Elementor + Elementor pro, background, container size, and custom color. But yeah, it’s very simple.</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/onward/" target="_blank" class="bw-btn">Free — Download</a></p>';
    echo '</div>';
    echo '<div class="card">';
        echo '<h2 class="title">Support</h2>';
        echo '<p class="box-2">We have a Support Desk for our users and a well-experienced team to answer your questions quickly. Please feel free to contact us if you have any questions.</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/contact/" target="_blank" class="bw-btn">Get Support</a></p>';
    echo '</div>';
    echo '<p class="bw-backend-heading"><strong> Follow us on: — </strong> <a class="bw-social-backend" href="https://www.facebook.com/modernaweb.net" target="_blank">FACEBOOK</a> | <a class="bw-social-backend" href="https://twitter.com/modernaweb_net" target="_blank">TWITTER</a> | <a class="bw-social-backend" href="https://www.instagram.com/modernaweb/" target="_blank">INSTAGRAM</a> | <a class="bw-social-backend" href="https://www.behance.net/modernaweb" target="_blank">BEHANCE</a> | <a class="bw-social-backend" href="https://dribbble.com/modernaweb" target="_blank">DRIBBBLE</a></p>';

echo '</div>';
