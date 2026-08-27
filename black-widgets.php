<?php
/**
 * Plugin Name: Black Widgets
 * Plugin URI: https://modernaweb.net/black-widgets
 * Description: Build web pages with black widgets.
 * Author: Modernaweb Studio
 * Version: 1.4.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author URI: https://modernaweb.net/
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: blackwidgets
 * Domain Path: /languages
 * Black Widgets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define( 'BLACK_WIDGETS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define( 'BLACK_WIDGETS_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define( 'BLACK_WIDGETS_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define( 'BLACK_WIDGETS_VERSION', '1.4.0' );

require_once BLACK_WIDGETS_PLUGIN_PATH . 'vendor/autoload.php';

require plugin_dir_path( __FILE__ ) . 'src/class-plugin-options.php';
require plugin_dir_path( __FILE__ ) . 'src/main.php';
require plugin_dir_path( __FILE__ ) . 'src/helpers.php';

new \Modernaweb\BlackWidgets\Main();

/** Redirect after activate plugin */
register_activation_hook(__FILE__, 'black_widgets_activate');
add_action('admin_init', 'black_widgets_redirect');


function black_widgets_activate() {
	add_option('black_widgets_do_activation_redirect', true);
}


function black_widgets_redirect() {
	if ( ! get_option( 'black_widgets_do_activation_redirect', false ) ) {
		return;
	}

	delete_option( 'black_widgets_do_activation_redirect' );

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- WP core activate-multi flag.
	if ( isset( $_GET['activate-multi'] ) ) {
		return;
	}

	wp_safe_redirect( admin_url( 'admin.php?page=black-widgets' ) );
	exit;
}


