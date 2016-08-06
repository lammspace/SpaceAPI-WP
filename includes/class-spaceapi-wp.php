<?php
/**
 * SpaceAPI for WordPress core class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link https://github.com/gcarreno/spaceapi-wp
 * @since 0.1
 *
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/includes
 * @author     Gustavo Carreno <guscarreno@gmail.com>
 */

class SpaceAPI_WP {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      SpaceAPI_WP_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1
	 */
	public function __construct() {

		$this->plugin_name = 'spaceapi-wp';
		$this->version = '0.1';
		$this->settings_page = $this->plugin_name.'-settings';
		$this->settings_section = $this->settings_page.'-section';
		$this->settings_section_options = array(
			'api' => array(
				'name' => 'api',
				'label' => 'SpaceAPI Api Version',
				'function' => 'settings_api'
			),
			'space' => array(
				'name' => 'space',
				'label' => 'Name of HackSpace',
				'function' => 'settings_space'
			),
			'logo' => array(
				'name' => 'logo',
				'label' => 'Image for the HackSpace',
				'function' => 'settings_logo'
			),
			'url' => array(
				'name' => 'url',
				'label' => 'Web Address for the HackSpace',
				'function' => 'settings_url'
			),
			'address' => array(
				'name' => 'address',
				'label' => 'Address for the HackSpace',
				'function' => 'settings_address'
			),
			'lat' => array(
				'name' => 'lat',
				'label' => 'Latitude for the HackSpace',
				'function' => 'settings_lat'
			),
			'lon' => array(
				'name' => 'lon',
				'label' => 'Longitude for the HackSpace',
				'function' => 'settings_lon'
			),
			'issue_report_channels' => array(
				'name' => 'issue_report_channels',
				'label' => 'List of channels to report issues, comma separated',
				'function' => 'settings_issue_report_channels'
			),
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - SpaceAPI_WP_Loader. Orchestrates the hooks of the plugin.
	 * - SpaceAPI_WP_i18n. Defines internationalization functionality.
	 * - SpaceAPI_WP_Admin. Defines all hooks for the admin area.
	 * - SpaceAPI_WP_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-spaceapi-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-spaceapi-wp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-spaceapi-wp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-spaceapi-wp-public.php';

		$this->loader = new SpaceAPI_WP_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SpaceAPI_WP_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SpaceAPI_WP_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SpaceAPI_WP_Admin( 
			$this->get_plugin_name(), 
			$this->get_version(),
			$this->settings_page,
			$this->settings_section,
			$this->settings_section_options 
		);

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new SpaceAPI_WP_Public( 
			$this->get_plugin_name(), 
			$this->get_version(),
			$this->settings_page,
			$this->settings_section,
			$this->settings_section_options 
		);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'init' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'parse_request' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1
	 * @return    SpaceAPI_WP_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
