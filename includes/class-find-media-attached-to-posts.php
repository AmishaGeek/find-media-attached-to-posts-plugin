<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://geekcodelab.com/
 * @since      1.0.0
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/includes
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
 * @since      1.0.0
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/includes
 * @author     Geek Code Lab <raj.kakadiya25@gmail.com>
 */
class Find_Media_Attached_To_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Find_Media_Attached_To_Posts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	protected $plugin_basename;
	public function __construct() {
		if ( defined( 'FIND_MEDIA_ATTACHED_TO_POSTS_VERSION' ) ) {
			$this->version = FIND_MEDIA_ATTACHED_TO_POSTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'find-media-attached-to-posts';
		$this->plugin_basename = FIND_MEDIA_ATTACHED_TO_POSTS_PLUGIN_BASENAME;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Find_Media_Attached_To_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Find_Media_Attached_To_Posts_i18n. Defines internationalization functionality.
	 * - Find_Media_Attached_To_Posts_Admin. Defines all hooks for the admin area.
	 * - Find_Media_Attached_To_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-find-media-attached-to-posts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-find-media-attached-to-posts-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-find-media-attached-to-posts-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area for media column.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-find-media-attached-to-posts-admin-media-column.php';		

		$this->loader = new Find_Media_Attached_To_Posts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Find_Media_Attached_To_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Find_Media_Attached_To_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Find_Media_Attached_To_Posts_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings = new Find_Media_Attached_To_Posts_Admin_Settings( $this->get_plugin_name(), $this->get_version() );
		$plugin_media_column = new Find_Media_Attached_To_Posts_Admin_Media_Column($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( "plugin_action_links_$this->plugin_basename",$plugin_admin, 'settings_link');

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );

		/** List Mode */
		$this->loader->add_action('add_meta_boxes', $plugin_media_column,'add_attachment_metaboxes');
		$this->loader->add_filter( 'manage_media_columns',$plugin_media_column ,'manage_media_columns');
		$this->loader->add_action('manage_media_custom_column', $plugin_media_column,'manage_media_custom_column');
		/** Grid Mode */
		$this->loader->add_filter( 'attachment_fields_to_edit',  $plugin_media_column,'attachment_fields_to_edit',10,2);
		/** Prevent From Deleting */
		$this->loader->add_action( 'delete_attachment', $plugin_media_column,'prevent_media_delete', 10, 3 );
		$this->loader->add_action( 'wp_ajax_fma_prevent_delete_attachment',$plugin_media_column, 'fma_prevent_delete_attachment_func', 1 );
		$this->loader->add_action( 'wp_ajax_nopriv_fma_prevent_delete_attachment',$plugin_media_column, 'fma_prevent_delete_attachment_func', 1 );

		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Find_Media_Attached_To_Posts_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
