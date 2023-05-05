<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://geekcodelab.com/
 * @since             1.0.0
 * @package           Find_Media_Attached_To_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Find Media Attached To Posts
 * Plugin URI:        https://https://wordpress.org/plugins/find-media-attached-to-posts
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Geek Code Lab
 * Author URI:        https://https://geekcodelab.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       find-media-attached-to-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
$plugin = plugin_basename( __FILE__ );
define( 'FIND_MEDIA_ATTACHED_TO_POSTS_VERSION', '1.0.0' );
define( 'FIND_MEDIA_ATTACHED_TO_POSTS_PLUGIN_BASENAME', $plugin );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-find-media-attached-to-posts-activator.php
 */
function activate_find_media_attached_to_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-find-media-attached-to-posts-activator.php';
	Find_Media_Attached_To_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-find-media-attached-to-posts-deactivator.php
 */
function deactivate_find_media_attached_to_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-find-media-attached-to-posts-deactivator.php';
	Find_Media_Attached_To_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_find_media_attached_to_posts' );
register_deactivation_hook( __FILE__, 'deactivate_find_media_attached_to_posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-find-media-attached-to-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_find_media_attached_to_posts() {

	$plugin = new Find_Media_Attached_To_Posts;
	$plugin->run();

}
run_find_media_attached_to_posts();
