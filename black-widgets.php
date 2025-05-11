<?php
/**
 * Plugin Name: Black Widgets
 * Plugin URI: https://modernaweb.net/black-widgets
 * Description: Build web pages with black widgets.
 * Author: Modernaweb Studio
 * Version: 1.4.0 DEV
 * Author URI: https://modernaweb.net/
 * Text Domain: black-widgets
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
define( 'BLACK_WIDGETS_VERSION', '1.3.9' );
define( 'BLACK_WIDGETS_ASSET_PATH', wp_upload_dir()['basedir'] . '/black-widgets');
define( 'BLACK_WIDGETS_ASSET_URL', wp_upload_dir()['baseurl'] . '/black-widgets');

require_once BLACK_WIDGETS_PLUGIN_PATH . 'vendor/autoload.php';

require plugin_dir_path( __FILE__ ) . 'src/main.php';
require plugin_dir_path( __FILE__ ) . 'functions.php';

new \Modernaweb\BlackWidgets\Main();

/** Redirect after activate plugin */
register_activation_hook(__FILE__, 'black_widgets_activate');
add_action('admin_init', 'black_widgets_redirect');


function black_widgets_activate() {
	add_option('black_widgets_do_activation_redirect', true);
}


function black_widgets_redirect() {
    if (get_option('black_widgets_do_activation_redirect', false)) {
		delete_option('black_widgets_do_activation_redirect');
        if(!isset($_GET['activate-multi'])) {
			wp_redirect("admin.php?page=black-widgets");
		}
	}

}
