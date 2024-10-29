<?php
/**
 * Plugin Name: Black Widgets
 * Plugin URI: https://modernaweb.net/black-widgets
 * Description: Build web pages with black widgets.
 * Author: Modernaweb Studio
 * Version: 1.3.8
 * Author URI: https://modernaweb.net/
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

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLACK_WIDGETS_PLUGIN_BASENAME', plugin_basename(__FILE__));
define( 'BLACK_WIDGETS_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define( 'BLACK_WIDGETS_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define( 'BLACK_WIDGETS_VERSION', '1.3.8' );
define( 'BLACK_WIDGETS_ASSET_PATH', wp_upload_dir()['basedir'] . '/black-widgets');
define( 'BLACK_WIDGETS_ASSET_URL', wp_upload_dir()['baseurl'] . '/black-widgets');

/**
 * Including autoloader.
 *
 * @since 1.0.1
 */
require_once BLACK_WIDGETS_PLUGIN_PATH . 'autoload.php';

/**
 * Composer
 */
require_once BLACK_WIDGETS_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-black-widgets-activator.php
 */
function activate_black_widgets() {
	require_once BLACK_WIDGETS_PLUGIN_PATH . 'includes/class-black-widgets-activator.php';
	Black_Widgets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-black-widgets-deactivator.php
 */
function deactivate_black_widgets() {
	require_once BLACK_WIDGETS_PLUGIN_PATH . 'includes/class-black-widgets-deactivator.php';
	Black_Widgets_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_black_widgets' );
register_deactivation_hook( __FILE__, 'deactivate_black_widgets' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-black-widgets.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-bw.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_black_widgets() {

	$plugin = new Black_Widgets();
	$plugin->run();

}
run_black_widgets();

/** Redirect after activate plugin */
register_activation_hook(__FILE__, 'black_widgets_activate');
add_action('admin_init', 'black_widgets_redirect');

function black_widgets_activate() {

	add_option('my_plugin_do_activation_redirect', true);

}

function black_widgets_redirect() {

    if (get_option('my_plugin_do_activation_redirect', false)) {

		delete_option('my_plugin_do_activation_redirect');

        if(!isset($_GET['activate-multi'])) {

			wp_redirect("admin.php?page=black-widgets");

		}

	}

}
