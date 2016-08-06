<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link https://github.com/gcarreno/spaceapi-wp
 * @since 0.1
 *
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @since      0.1
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/public
 * @author     Gustavo Carreno <guscarreno@gmail.com>
 */
class SpaceAPI_WP_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Settings Page ID.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $settings_page The Settings Page ID.
	 */
	private $settings_page;

	/**
	 * The Settings Section ID.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $settings_section The Settings Section ID.
	 */
	private $settings_section;

	/**
	 * The namespace of this plugin for the rest api.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $rest_namespace    The namespace of this plugin for the rest api.
	 */
	private $rest_namespace;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_page = $this->plugin_name.'-settings';
		$this->settings_section = $this->settings_page.'-section';
		$this->rest_namespace = 'spaceapi/v1';

	}

	/**
	 * WP init action for public-facing query.
	 *
	 * @since    0.1
	 */
	public function init() {
		add_rewrite_endpoint('spaceapi', EP_ROOT);
	}

	/**
	 * Serve the SpaceAPI JSON
	 * 
	 * @since    0.1
	 */
	public function parse_request($wp) {
		$spaceapi = get_query_var( 'spaceapi', null );

		if ( is_null( $spaceapi ) ) {
			return;
		}
		
		if ( empty( $spaceapi ) ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}

		$spaceapi_parts = explode( '/', $spaceapi );
		$spaceapi_version = $spaceapi_parts[0];
		if ( empty( $spaceapi_version ) ) {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		if ( 'v1' == $spaceapi_version ) {
			if ( !isset( $spaceapi_parts[1] ) ) {
				$spaceapi_parts[1] = 'index';
			}
			$spaceapi_action = $spaceapi_parts[1];
			switch ($spaceapi_action) {
				case 'index':
					$result = array();
					$o = $this->settings_section.'-spaceapi-version';
					$result['api'] = esc_attr( get_option( $o ) );
					$o = $this->settings_section.'-name';
					$result['space'] = esc_attr( get_option( $o ) );
					
					echo json_encode( $result );
					die;
					break;
				case 'version':
					$result = array();
					$o = $this->settings_section.'-spaceapi-version';
					$result['api'] = esc_attr( get_option( $o ) );

					echo json_encode( $result );
					die;
					break;
				case 'name':
					$result = array();
					$o = $this->settings_section.'-name';
					$result['space'] = esc_attr( get_option( $o ) );

					echo json_encode( $result );
					die;
					break;
				default:
					wp_redirect( home_url( '/' ) );
					break;
			}
		} else {
			wp_redirect( home_url( '/' ) );
			exit;
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SpaceAPI_WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SpaceAPI_WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spaceapi-wp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in SpaceAPI_WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The SpaceAPI_WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spaceapi-wp-public.js', array( 'jquery' ), $this->version, false );

	}

}
