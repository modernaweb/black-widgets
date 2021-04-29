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
        echo '<h2 class="title">Codedsgn → Inspiration & Dummy Demos</h2>';
        echo '<p>Dsgn is a simple way to start a WordPress website that you can start easily, quickly, and free. You will see many adaptations soon, like other Elementor Add-Ons and compatible WordPress Themes.</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/dsgn/" target="_blank" class="bw-btn bw-uq">New releases</a><a href="https://modernaweb.net/dsgn/user-account/register/" target="_blank" class="bw-btn">Create your free account</a></p>';
        echo '<img class="bw-banner" src="'.plugin_dir_url(__FILE__ ) . 'img/dsgn-in-black-widgets.jpg'.'" />';
    echo '</div>';
    echo '<div class="card">';
        echo '<h2 class="title">Support</h2>';
        echo '<p class="box-2">We have a Support Desk for our users and a well-experienced team to quickly answer your questions. Please feel free to contact us if you have any questions.</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/contact/" target="_blank" class="bw-btn">Get Support</a></p>';
    echo '</div>';
    echo '<p class="bw-backend-heading"><strong> Follow us on: — </strong> <a class="bw-social-backend" href="https://www.facebook.com/modernaweb.net" target="_blank">FACEBOOK</a> | <a class="bw-social-backend" href="https://twitter.com/modernaweb_net" target="_blank">TWITTER</a> | <a class="bw-social-backend" href="https://www.instagram.com/modernaweb/" target="_blank">INSTAGRAM</a> | <a class="bw-social-backend" href="https://www.behance.net/modernaweb" target="_blank">BEHANCE</a> | <a class="bw-social-backend" href="https://dribbble.com/modernaweb" target="_blank">DRIBBBLE</a></p>';

echo '</div>';
