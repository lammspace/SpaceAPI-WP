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
	 * The Settings Section array of options.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $settings_section The Settings Section array of options.
	 */
	private $settings_section_options;

	/**
	 * The namespace of this plugin for the rest api.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $rest_namespace    The namespace of this plugin for the rest api.
	 */
	private $rest_namespace;

	/**
	 * Gets the WP Option name
	 * 
	 * @since    0.1
	 * @param    string    $option    The name of the option
	 */
	private function get_option_name($option) {
		if ( isset( $this->settings_section_options[$option] ) ) {
			return $this->settings_section.'-'.$this->settings_section_options[$option]['name'];
		} else {
			return '';
		}
	}

	/**
	 * Gets the WP Option value
	 * 
	 * @since    0.1
	 * @param    string    $option    The name of the option
	 */
	private function get_option($option) {
		if ( isset( $this->settings_section_options[$option] ) ) {
			$name = $this->settings_section.'-'.$this->settings_section_options[$option]['name'];
			//return esc_attr( get_option( $name ) );
			return get_option( $name );
		} else {
			return '';
		}
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings_page, $settings_section, $settings_section_options ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_page = $settings_page;
		$this->settings_section = $settings_section;
		$this->settings_section_options = $settings_section_options;
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
	 * @since    0.2
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
			
			$result = array();
			switch ($spaceapi_action) {
				case 'index':
					$location = array(
						'address',
						'lat',
						'lon'
					);
					foreach ( $this->settings_section_options as $key => $option ) {
						if ( in_array( $option['name'], $location ) ) {
							$result['location'][$option['name']] = $this->get_option($key);
						} elseif ( 'issue_report_channels' == $key ) {
							$result['issue_report_channels'] = explode( ',', $this->get_option($key) );
						}else {
							$result[$option['name']] = $this->get_option($key);
						}
					}
					break;
				/*
				case 'version':
					$o = $this->settings_section.'-spaceapi-version';
					$result['api'] = esc_attr( get_option( $o ) );

					break;
				case 'name':
					$result = array();
					$o = $this->settings_section.'-name';
					$result['space'] = esc_attr( get_option( $o ) );

					break;
				*/
				default:
					wp_redirect( home_url( '/' ) );
					exit;
					break;
			}
			
			wp_send_json( $result );
			
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
