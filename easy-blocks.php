<?php
/**
 * Plugin Name: Easy Blocks
 * Plugin URI: https://github.com/wpeasyblocks/easy-blocks
 * Description: Collection of Gutenberg Blocks for WordPress
 * Text Domain: easy-blocks
 * Author: Aamer Shahzad
 * Author URI: https://github.com/wpeasyblocks
 * Version: 1.0.0
 * License: GPL-v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * only proceed if gutenberg is available
 */
if ( ! function_exists( 'register_block_type' ) ) {
	return;
}