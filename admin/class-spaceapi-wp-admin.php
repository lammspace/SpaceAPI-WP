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
	 * The Settings Section array of options.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $settings_section The Settings Section array of options.
	 */
	private $settings_section_options;
	
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
			return esc_attr( get_option( $name ) );
		} else {
			return '';
		}
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings_page, $settings_section, $settings_section_options ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings_page = $settings_page;
		$this->settings_section = $settings_section;
		$this->settings_section_options = $settings_section_options;
		
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
		foreach ( $this->settings_section_options as $key => $option) {
			add_settings_field(
				$this->get_option_name($key),
				$option['label'],
				array($this, $option['function']),
				$this->settings_page,
				$this->settings_section,
				array(
					'label_for'=>$this->get_option_name($key)
				)
			);
			register_setting(
				$this->settings_section,
				$this->get_option_name($key)
			);
		}
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
	 * Using this magic methods to use an array with all the options
	 * 
	 * @since    0.1
	 * @param    string    $name         Name of the method
	 * @param    mixed     $arguments    Params for the method
	 */
	public function __call($name, $arguments) {
		foreach ( $this->settings_section_options as $key => $option) {
			if ( 'settings_'.$option['name'] == $name ) {
				$name = $this->get_option_name( $key );
				$option = $this->get_option($key);
				echo "<input type='text' name='$name' value='$option' />";
			}
		}
	}

	/**
	 * Settings Option SpaceAPI Version callback
	 * 
	 * @since     0.1
	 */
	public function settings_api() {
		$name = $this->get_option_name( 'api' );
		$option = $this->get_option('api');
		echo "<input type='text' name='$name' value='$option' />";
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
