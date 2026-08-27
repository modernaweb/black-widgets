<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();

echo '<div class="wrap bw-box-backend">';

    echo '<h1 class="bw-backend-heading">' . esc_html( sprintf(
        /* translators: %s: user nickname */
        __( 'Hello %s,', 'blackwidgets' ),
        $current_user->nickname
    ) ) . '</h1>';
    echo '<div class="card">';
        echo '<h2 class="title">' . esc_html__( 'DSGN + BW', 'blackwidgets' ) . '</h2>';
        echo '<p>' . esc_html__( 'Dsgn is a simple way to start a WordPress site. More Elementor add-ons and compatible themes are on the way.', 'blackwidgets' ) . '</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/dsgn/" target="_blank" class="bw-btn bw-uq">' . esc_html__( 'New releases', 'blackwidgets' ) . '</a><a href="https://modernaweb.net/dsgn/user-account/register/" target="_blank" class="bw-btn">' . esc_html__( 'Create your free account', 'blackwidgets' ) . '</a></p>';
        echo '<a href="https://modernaweb.net/dsgn/" target="_blank" class="bw-img"><img class="bw-banner" src="'. esc_url( BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/img/dsgn-in-black-widgets.jpg' ) .'" alt="DSGN" /></a>'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
    echo '</div>';
    echo '<div class="card">';
        echo '<h2 class="title" style="color: #2848FF;">' . esc_html__( 'Onward WordPress Theme', 'blackwidgets' ) . '</h2>';
        echo '<p class="box-2">' . esc_html__( 'Onward is a light WordPress theme for Elementor. Simple layout, clean design, and the usual theme options for background, container size, and colors.', 'blackwidgets' ) . '</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/onward/" target="_blank" class="bw-btn">' . esc_html__( 'Free Download', 'blackwidgets' ) . '</a></p>';
    echo '</div>';
    echo '<div class="card">';
        echo '<h2 class="title">' . esc_html__( 'Support', 'blackwidgets' ) . '</h2>';
        echo '<p class="box-2">' . esc_html__( 'Need help? Contact our support team and we will get back to you.', 'blackwidgets' ) . '</p>';
        echo '<p class="bw-btn-box"><a href="https://modernaweb.net/contact/" target="_blank" class="bw-btn">' . esc_html__( 'Get Support', 'blackwidgets' ) . '</a></p>';
    echo '</div>';
    echo '<p class="bw-backend-heading"><strong>' . esc_html__( 'Follow us on:', 'blackwidgets' ) . '</strong> <a class="bw-social-backend" href="https://www.facebook.com/modernaweb.net" target="_blank">FACEBOOK</a> | <a class="bw-social-backend" href="https://twitter.com/modernaweb_net" target="_blank">TWITTER</a> | <a class="bw-social-backend" href="https://www.instagram.com/modernaweb/" target="_blank">INSTAGRAM</a> | <a class="bw-social-backend" href="https://www.behance.net/modernaweb" target="_blank">BEHANCE</a> | <a class="bw-social-backend" href="https://dribbble.com/modernaweb" target="_blank">DRIBBBLE</a></p>';

echo '</div>';
