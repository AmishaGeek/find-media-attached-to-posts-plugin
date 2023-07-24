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
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/admin
 * @author     Geek Code Lab <raj.kakadiya25@gmail.com>
 */
class Find_Media_Attached_To_Posts_Admin {

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

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Find_Media_Attached_To_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Find_Media_Attached_To_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/find-media-attached-to-posts-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Find_Media_Attached_To_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Find_Media_Attached_To_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/find-media-attached-to-posts-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'fma_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-find-media-attached-to-posts-admin-settings.php';
		
	}
	public function settings_link($links){
		$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __( 'Support',$this->plugin_name ) . '</a>'; 
		array_unshift( $links, $support_link );

		$settings_link = '<a href="upload.php?page=fma-options">' . __( 'Settings',$this->plugin_name) . '</a>'; 	
		array_unshift( $links, $settings_link );
		return $links;
	}
}