<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://geekcodelab.com/
 * @since      1.0.0
 *
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Find_Media_Attached_To_Posts
 * @subpackage Find_Media_Attached_To_Posts/includes
 * @author     Geek Code Lab <raj.kakadiya25@gmail.com>
 */
class Find_Media_Attached_To_Posts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'find-media-attached-to-posts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
