<?php
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
 | CLASS EASY BLOCKS
 |-------------------------------------------------
 |
 | plugin main class
 |
 */
class Class_Easy_Blocks {

	/**
	 * is plugin initiated
	 * @var bool default to false
	 */
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * initialize plugin hooks
	 */
	private static function init_hooks() {
		// set initialization status
		self::$initiated = true;

		// plugin action hooks
		add_action( 'enqueue_block_assets', [ 'Class_Easy_Blocks', 'eb_block_scripts'] );
		add_action( 'enqueue_block_editor_assets', [ 'Class_Easy_Blocks', 'eb_editor_scripts' ] );
		add_filter( 'block_categories_all', [ 'Class_Easy_Blocks', 'eb_block_categories' ] );
        add_action( 'init', [ 'Class_Easy_Blocks', 'eb_make_blocks_translatable' ] );
	}

	/**
	 * plugin activation hook
	 */
	public static function eb_plugin_activation() {}

	/**
	 * plugin deactivation hook
	 */
	public static function eb_plugin_deactivation() {}

	/**
	 * enqueue blocks scripts
	 */
	public static function eb_block_scripts() {
		$block_css_file = 'assets/blocks.css';
		wp_enqueue_style(
			'eb-blocks-css',
			EB_DIR_URI . $block_css_file,
			null,
			filemtime( EB_DIR_PATH . $block_css_file )
		);
	}

	/**
     * enqueue editor scripts
     */
    public static function eb_editor_scripts() {
        $editor_block_js_file = 'assets/blocks.js';
        wp_enqueue_script(
            'easy-blocks-js',
            EB_DIR_URI . $editor_block_js_file,
            [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-editor', 'wp-components', 'wp-polyfill' ],
            filemtime( EB_DIR_PATH . $editor_block_js_file )
        );
    }

	/**
	 * Add new block category for 'Easy Blocks'
	 *
	 * @param $categories array list of available categories
	 *
	 * @return array filtered categories
	 */
	public static function eb_block_categories( $block_categories ) {

		/**
		 * add new block category
		 * in the beginning of array
		 */
		return array_merge(
			array(
				array(
					'slug' => EB_PLUGIN_SLUG,
					'title' => EB_PLUGIN_NAME,
					'icon'  => 'wordpress', // replace with logo in js
				),
			),
            $block_categories
		);
	}

	public static function eb_make_blocks_translatable() {
        wp_set_script_translations( 'easy-blocks-js', 'easyBlocks' );
    }

}