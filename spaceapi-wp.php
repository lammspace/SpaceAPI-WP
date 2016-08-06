<?php
/**
 * SpaceAPI for WordPress
 * 
 * @package SpaceAPI_WordPress
 * @author Gustavo Carreno <guscarreno@gmail.com>
 * @license MIT
 * @link https://github.com/gcarreno/spaceapi-wp
 * @copyright 2016 Gustavo Carreno
 * @since 0.1
 * 
 * @wordpress-plugin
 * Plugin Name: SpaceAPI for WordPress
 * Plugin URI: https://github.com/gcarreno/spaceapi-wp
 * Description: A WordPress plugin to manage a hackerspace's <a href="http://spaceapi.net/">SpaceAPI</a> data
 * Version: 0.1
 * Author: Gustavo Carreno
 * Author URI: https://guscarreno.blogspot.co.uk
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT 
 * GitHub Plugin URI: https://github.com/gcarreno/spaceapi-wp
 * GitHub Branch: master
 * Text Domain: spaceapi-wp
 * Domain Path: /languages/
 * 
 */ 

defined( 'WPINC' ) or die( 'No script kiddies please!' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spaceapi-wp-activator.php';
	SpaceAPI_WP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spaceapi-wp-deactivator.php';
	SpaceAPI_WP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spaceapi-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1
 */
function run_spaceapi_wp() {

	$plugin = new SpaceAPI_WP();
	$plugin->run();

}
run_spaceapi_wp();
