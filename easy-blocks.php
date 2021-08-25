<?php
/**
 * Plugin Name: Easy Blocks
 * Plugin URI: http://wpeasyblocks.com
 * Description: Collection of Gutenberg Blocks for WordPress Block Editor
 * Text Domain: easy-blocks
 * Author: Aamer Shahzad
 * Author URI: http://wpthemecraft.com
 * Version: 1.0.1
 * License: GPL-v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright 2019 Easy Blocks.
 */

/*
 |-------------------------------------------------
 | EXIT IF ACCESSED DIRECTLY
 |-------------------------------------------------
 |
 | exit if file is accessed directly.
 |
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 |-------------------------------------------------
 | IS GUTENBERG PLUGIN ACTIVE
 |-------------------------------------------------
 |
 | only proceed if gutenberg plugin is active
 |
 */
if ( ! function_exists( 'register_block_type' ) ) {
	return;
}

/*
 |-------------------------------------------------
 | PLUGIN DATA
 |-------------------------------------------------
 |
 | get plugin data to assign to constants dynamically
 |
 */
if( ! function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( __FILE__, false );

/*
 |-------------------------------------------------
 | PLUGIN DIRECTORY PATH & URLS
 |-------------------------------------------------
 |
 | define plugin constants for the following
 | directory path, directory uri, assets directory uri,
 | slug, name, version, min wp version, wp support url etc
 |
 */
if ( ! defined( 'EB_DIR_PATH' ) )
	define( 'EB_DIR_PATH', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'EB_DIR_URI' ) )
	define( 'EB_DIR_URI', plugin_dir_url( __FILE__ ) );

define( 'EB_ASSETS_URI', trailingslashit( EB_DIR_URI . 'assets' ) );

define( 'EB_PLUGIN_SLUG', 'easy-blocks');

define(
    'EB_PLUGIN_NAME',
    ($plugin_data && $plugin_data['Name']) ? $plugin_data['Name'] : 'Easy Blocks'
);

define(
    'EB_PLUGIN_VERSION',
    ($plugin_data && $plugin_data['Version']) ? $plugin_data['Version'] : '1.0.0'
);

define( 'EB_MIN_WP_VERSION', '5.0');

define(
	'EB_WPORG_SUPPORT_URL',
	trailingslashit( 'https://wordpress.org/support/plugin' )
);

/*
 |-------------------------------------------------
 | PLUGIN MAIN CLASS
 |-------------------------------------------------
 |
 | plugins main class to initialize the plugin,
 | register hooks and filters and other functions
 |
 */
require_once( EB_DIR_PATH . 'class-easy-blocks.php' );
// require_once( EB_DIR_PATH . 'inc/init.php' );

/*
 |-------------------------------------------------
 | PLUGIN INIT
 |-------------------------------------------------
 |
 | initialize the plugin
 |
 */
add_action( 'init', array( 'Class_Easy_Blocks', 'init' ) );

/*
 |-------------------------------------------------
 | PLUGIN ACTIVATION/DEACTIVATION
 |-------------------------------------------------
 |
 | register plugin activation and deactivation hooks
 |
 */
register_activation_hook( __FILE__, array( 'Class_Easy_Blocks', 'eb_plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Class_Easy_Blocks', 'eb_plugin_deactivation' ) );
