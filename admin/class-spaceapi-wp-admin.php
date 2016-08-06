<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link https://github.com/gcarreno/spaceapi-wp
 * @since 0.1
 *
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @since      0.1
 * @package    SpaceAPI_WP
 * @subpackage SpaceAPI_WP/admin
 * @author     Gustavo Carreno <guscarreno@gmail.com>
 */
class SpaceAPI_WP_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_page = $this->plugin_name.'-settings';
		$this->settings_section = $this->settings_page.'-section';
		
	}

	/**
	 * Admin init
	 * 
	 * @since     0.1
	 */
	public function admin_init() {
		// Ad a Settings section that goes with the above page
		add_settings_section(
			$this->settings_section,
			'SpaceAPI for WordPress Settings',
			array($this, 'settings_section'),
			$this->settings_page
		);
		// Add the settings needed
		// Space API version
		add_settings_field(
			$this->settings_section.'-spaceapi-version',
			'SpaceAPI Version',
			array($this, 'settings_spaceapi_version'),
			$this->settings_page,
			$this->settings_section,
			array(
				'label_for'=>$this->settings_section.'-spaceapi-version'
			)
		);
		register_setting(
			$this->settings_section,
			$this->settings_section.'-spaceapi-version'
		);
		
		// Hacker Space Name
		add_settings_field(
			$this->settings_section.'-name',
			'Name of the Space',
			array($this, 'settings_name'),
			$this->settings_page,
			$this->settings_section,
			array(
				'label_for'=>$this->settings_section.'-name'
			)
		);
		register_setting(
			$this->settings_section,
			$this->settings_section.'-name'
		);
	}


	/**
	 * Admin menu
	 * 
	 * @since     0.1
	 */
	public function admin_menu() {
		// Add a custom Menu and Page to the top level menu
		add_menu_page(
			'Space API Settings',
			'Space API',
			'manage_options',
			$this->settings_page,
			array($this,'render_menu_page'),
			'dashicons-admin-plugins',
			'67'
		);
	}
	
	/**
	 * Settings Section callback
	 * 
	 * @since     0.1
	 */
	public function settings_section() {
		echo "<p>SpaceAPI Settings Section</p>";
	}

	/**
	 * Settings Option SpaceAPI Version callback
	 * 
	 * @since     0.1
	 */
	public function settings_spaceapi_version() {
		$name = $this->settings_section.'-spaceapi-version';
		$setting = esc_attr( get_option( $name ) );
		echo "<input type='text' name='$name' value='$setting' />";
	}

	/**
	 * Settings Option Name callback
	 * 
	 * @since     0.1
	 */
	public function settings_name() {
		$name = $this->settings_section.'-name';
		$setting = esc_attr( get_option( $name ) );
		echo "<input type='text' name='$name' value='$setting' />";
	}

	/**
	 * Menu rendering method
	 * 
	 * @since     0.1
	 */
	public function render_menu_page() {
     if ( ! isset( $_REQUEST['settings-updated'] ) )
          $_REQUEST['settings-updated'] = false;
?>
    <div class="wrap">
	<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
	   <div class="updated fade"><p><strong><?php _e( 'Space API Settings saved!', $this->plugin_name ); ?></strong></p></div>
	<?php endif; ?>
        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
        <div id="spaceapi-settings" name="spaceapi-settings">
			<div id="post-body" name="post-body">
				<div id="post-body-content" name="post-body-content">
					<form method="post" action="options.php">
						<?php settings_fields( $this->settings_section ); ?>
						<table class="form-table">
						<?php do_settings_fields( $this->settings_page, $this->settings_section );	?>
						</table>
						<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes" /></p>
					</form>
				</div>
			</div>
        </div>
    </div>
<?php
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spaceapi-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spaceapi-wp-admin.js', array( 'jquery' ), $this->version, false );

	}

}
