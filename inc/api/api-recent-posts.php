<?php
/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * register rest api router for image-sizes
 */
function eb_register_rest_route_image_sizes() {
	// api namespace
	$namespace = 'easy-blocks/v1';

	/**
	 * register route
	 */
	register_rest_route( $namespace, '/image-sizes', array(
		'methods' => 'GET',
		'callback' => 'eb_get_thumbnail_sizes_cb',
	) );

}
add_action( 'rest_api_init', 'eb_register_rest_route_image_sizes' );

/**
 * Get all registered image sizes.
 *
 * json decoded array of image sizes.
 */
function eb_get_thumbnail_sizes_cb() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width' => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop' => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	die( json_encode( $sizes ) );
}

/**
 * Register rest api fields for 'posts' route
 */
function eb_register_posts_rest_api_fields() {
	/**
	 * register featured_image field in 'posts' api response
	 */
	register_rest_field( 'post', 'featured_image', array(
		/**
		 * return image src or null
		 */
		'get_callback' => function ( $post_arr ) {
			return get_the_post_thumbnail( $post_arr['id'], 'medium' );
		},
		'update_callback' => null,
		'schema' => null
	) );

	/**
	 * register author_info field in 'posts' api response
	 */
	register_rest_field( 'post', 'author_info', array(
		/**
		 * return array of author name and link
		 */
		'get_callback' => function ( $post_arr ) {
			$author_info = new stdClass();
			$author_info->name = get_the_author_meta( 'display_name', $post_arr['author'] );
			$author_info->link = get_author_posts_url( $post_arr['author'] );

			return $author_info;
		},
		'update_callback' => null,
		'schema' => null
	) );

	/**
	 * register terms field in 'posts' api response
	 */
	register_rest_field( 'post', 'terms', array(
		/**
		 * return array of author name and link
		 */
		'get_callback' => function ( $post_arr ) {
			$terms = new stdClass();
			$terms->categories = get_the_term_list( $post_arr['id'], 'category', '', ', ' );
			$terms->tags = get_the_term_list( $post_arr['id'], 'post_tag', '', ', ' );

			return $terms;
		},
		'update_callback' => null,
		'schema' => null
	) );

}
add_action( 'rest_api_init', 'eb_register_posts_rest_api_fields' );
