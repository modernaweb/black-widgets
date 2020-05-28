<?php
/**
 * Plugins primary file, in charge of including all other dependencies.
 *
 * @package Black Widgets
 *
 * @wordpress-plugin
 * Plugin Name: 		Black Widgets
 * Plugin URI: 			http://modernaweb.net/black-widgets
 * Description: 		The black building widgets for Elementor
 * Author: 				Modernaweb
 * Version: 			1.0.1
 * Author URI: 			http://modernaweb.net/
 * Text Domain: 		bw
 * Domain Path:       /languages
 * lack Widgets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BLACK_WIDGETS_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-black-widgets-activator.php
 */
function activate_black_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-black-widgets-activator.php';
	Black_Widgets_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-black-widgets-deactivator.php
 */
function deactivate_black_widgets() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-black-widgets-deactivator.php';
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
register_activation_hook(__FILE__, 'bw_activate');
add_action('admin_init', 'bw_redirect');

function bw_activate() {

	add_option('my_plugin_do_activation_redirect', true);

}

function bw_redirect() {

    if (get_option('my_plugin_do_activation_redirect', false)) {

		delete_option('my_plugin_do_activation_redirect');

        if(!isset($_GET['activate-multi'])) {

			wp_redirect("admin.php?page=black-widgets");

		}

	}

}
