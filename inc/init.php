<?php
/**
 * Define directory paths for /inc and inner directories
 */

/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * inc directory PATH & URI
 */
define(
	'EB_INC_DIR',
	trailingslashit( EB_DIR_PATH . 'inc' )
);
define(
	'EB_INC_DIR_URI',
	trailingslashit( EB_DIR_URI . 'inc' )
);

/**
 * blocks directory PATH
 */
define(
	'EB_BLOCKS_DIR',
	trailingslashit( EB_INC_DIR . 'blocks' )
);

/**
 * API directory PATH
 */
define(
	'EB_API_DIR',
	trailingslashit( EB_INC_DIR . 'api' )
);

/**
 * utilities directory PATH
 */
define(
	'EB_UTILS_DIR',
	trailingslashit( EB_INC_DIR . 'utils' )
);

/**
 * about directory PATH & URI
 */
define(
	'EB_ABOUT_DIR',
	trailingslashit( EB_INC_DIR . 'about' )
);
define(
	'EB_ABOUT_DIR_URI',
	trailingslashit( EB_INC_DIR_URI . 'about' )
);

/**
 * dashboard directory PATH
 */
define(
	'EB_DASHBOARD_DIR',
	trailingslashit( EB_INC_DIR . 'dashboard' )
);

/**
 * load block apis
 */
include EB_API_DIR . 'api-recent-posts.php';

/**
 * load blocks
 */
include EB_BLOCKS_DIR . 'block-posts.php';

/**
 * load utilities classes
 */
include EB_UTILS_DIR . 'class-eb-icon-loader.php';

/**
 * load abbout page
 */
include EB_ABOUT_DIR . 'class-eb-about-page.php';

/**
 * load dashboard widgets
 */
include EB_DASHBOARD_DIR . 'class-eb-dashboard-widgets.php';

/**
 * plugin base data
 */
$plugin_data = array(
	'name' => EB_NAME, // plugin name
	'slug' => EB_SLUG, // plugin slug
	'version' => EB_VERSION // plugin version
);

/**
 * initialize About Page Class
 */
new EB_About_Page( $plugin_data );

/**
 * initialize dashboard widgets class
 */
new EB_Dashboard_Widgets( $plugin_data );