<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://geekcodelab.com/
 * @since      1.0.0
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Find_Media_Attached_To_Posts_Admin_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();

	}
    private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/find-media-attached-to-posts-admin-display.php';

	}
	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'WPPB Demo' menu.
	 */
	public function setup_plugin_options_menu() {
        add_submenu_page(
            'upload.php',
            __( 'Find_Media_Attached_To_Posts', $this->plugin_name ),
            __( 'Options', $this->plugin_name ),
            'manage_options',
            'fma-options',
            array( $this, 'render_settings_page_content')
        );

	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {
        $plugin = new Find_Media_Attached_To_Posts_Admin_Display($this->plugin_name,$this->version);
		$plugin->run();
	}



	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_display_options() {
		$plugin = new Find_Media_Attached_To_Posts_Admin_Display($this->plugin_name,$this->version);
		$plugin->register_admin_settings();
	} // end wppb-demo_initialize_theme_options






}