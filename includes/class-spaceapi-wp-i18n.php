<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link https://github.com/gcarreno/spaceapi-wp
 * @since 0.1
 *
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/includes
 * @author     Gustavo Carreno <guscarreno@gmail.com>
 */
class SpaceAPI_WP_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'spaceapi-wp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
