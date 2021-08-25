<?php
/**
 * Recent Posts Block
 */

/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * register block with attributes and render callback
 */
function eb_register_block_recent_posts() {

	/**
	 * return if gutenberg is not available
	 */
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	/**
	 * register block type and attributes
	 */
	register_block_type( EB_SLUG . '/eb-posts', array(
		'attributes' => array(
			'category' => array(
				'type' => 'string',
			),
			'postsToShow' => array(
				'type' => 'number',
				'default' => 5,
			),
			'order' => array(
				'type' => 'string',
				'default' => 'desc',
			),
			'orderBy' => array(
				'type' => 'string',
				'default' => 'date',
			),
			'postsLayout' => array(
				'type' => 'string',
				'default' => 'eb-list',
			),
			'postsGridColumns' => array(
				'type' => 'number',
				'default' => 2
			),
			'displayPostAuthor' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'displayReadMore' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'displayPostCategories' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'displayPostDate' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'displayPostContent' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'displayPostThumbnail' => array(
				'type' => 'boolean',
				'default' => true,
			),
			'postThumbnailSize' => array(
				'type' => 'string',
				'default' => 'medium',
			),
			'postContentLength' => array(
				'type' => 'number',
				'default' => 100,
			),
			'postReadMoreText' => array(
				'type' => 'string',
				'default' => 'Continue Reading'
			),
			'postTitleLength' => array(
				'type' => 'number',
				'default' => 50,
			),
		),
		'render_callback' => 'eb_render_block_posts_cb',
	) );

}
add_action( 'init', 'eb_register_block_recent_posts' );

/**
 * Render block on the front-end
 *
 * @param $attributes array block saved values & settings
 *
 * @return string
 */
function eb_render_block_posts_cb( $attributes ) {
	// attributes values
	$posts_layout = isset( $attributes['postsLayout'] ) ? $attributes['postsLayout'] : 'eb-list';
	$posts_grid_col = $attributes['postsGridColumns'];
	$display_content = isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent'];
	$display_read_more = isset( $attributes['displayReadMore'] ) && $attributes['displayReadMore'];
	$read_more_text = isset( $attributes['postReadMoreText'] ) ? $attributes['postReadMoreText'] : '';
	$display_thumbnail = isset( $attributes['displayPostThumbnail'] ) && $attributes['displayPostThumbnail'];
	$thumbnail_size = isset( $attributes['postThumbnailSize'] ) ? $attributes['postThumbnailSize'] : 'medium';
	$display_author = isset( $attributes['displayPostAuthor'] ) && $attributes['displayPostAuthor'];
	$display_categories = isset( $attributes['displayPostCategories'] ) && $attributes['displayPostCategories'];
	$display_date = isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'];

	// query args
	$args = array(
		'post_status' => 'publish',
		'posts_per_page' => $attributes['postsToShow'],
		'order' => $attributes['order'],
		'orderby' => $attributes['orderBy'],
		'ignore_sticky_posts' => true
	);

	// if category
	if ( isset( $attributes['category'] ) ) {
		$args['category__in'] = $attributes['category'];
	}

	$query = new WP_Query( $args );

	/**
	 * entry content classes
	 */
	$entry_classes = 'eb-recent-posts eb-clearfix';
	if ( isset( $display_content ) && $display_content ) {
		$entry_classes .= ' eb-has-content';
	}
	if ( isset( $display_thumbnail ) && $display_thumbnail ) {
		$entry_classes .= ' eb-has-thumbnail';
	}

	/**
	 * entry meta classes
	 */
	$meta_classes = 'eb-post-meta';
	if ( $display_author ) {
		$meta_classes .= ' eb-has-author';
	}
	if ( $display_categories ) {
		$meta_classes .= ' eb-has-categories';
	}
	if ( $display_date ) {
		$meta_classes .= ' eb-has-date';
	}

	$eb_item_output = '';
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();

			// start article
			$eb_item_output .= sprintf(
				'<article class="%1$s">',
				esc_attr( $entry_classes )
			);

			// post thumbnail
			if ( has_post_thumbnail() && $display_thumbnail ) {
				$eb_item_output .= sprintf(
					'<div class="eb-featured-image eb-inline-block">%1$s</div>',
					get_the_post_thumbnail( get_the_ID(), $thumbnail_size )
				);
			}

			// start eb-content
			$eb_item_output .= sprintf(
				'<div class="eb-content eb-inline-block">'
			);

			// start eb-content-meta
			$eb_item_output .= sprintf(
				'<div class="eb-content-meta">'
			);

			// post title
			$title = esc_html( ( strlen( get_the_title() ) !== 0 ) ? get_the_title() : __( '(Untitled)' ) );
			$title_length = isset( $attributes['postTitleLength'] ) ? $attributes['postTitleLength'] : 50;
			$eb_item_output .= sprintf(
				'<h3 class="eb-post-title"><a href="%1$s">%2$s</a></h3>',
				esc_url( get_the_permalink( get_the_ID() ) ),
				eb_trim_content( $title, $title_length, '...' )
			);

			//$title = eb_trim_content( $title, $title_length, '...' );
			//echo "<pre>";
			//print_r( $title );
			//echo "</pre>";


			// post meta ( author, categories, date )
			if ( $display_author || $display_categories || $display_date ) {
				// start meta wrap
				$eb_item_output .= sprintf(
					'<div class="%1$s">',
					esc_attr( $meta_classes )
				);

				// post author link
				$eb_item_output .= sprintf(
					'<div class="eb-posted-by eb-inline-block"><a href="%1$s">%2$s</a></div>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_html( get_the_author() )
				);

				// post categories list
				$post_categories = get_the_category_list( __( ', ' ) );
				if ( $post_categories ) {
					$eb_item_output .= sprintf(
						'<div class="eb-posted-in eb-inline-block">%1$s</div>',
						$post_categories
					);
				}

				// post date
				if ( $display_date ) {
					$eb_item_output .= sprintf(
						'<div class="eb-posted-on eb-inline-block"><time datetime="%1$s">%2$s</time></div>',
						esc_attr( get_the_date( 'c', get_the_ID() ) ),
						esc_html( get_the_date( '', get_the_ID() ) )
					);
				}

				// close meta wrap
				$eb_item_output .= sprintf(
					'</div>'
				);
			} // end if author, categories or date

			// post content
			if ( $display_content ) {
				$content_length = isset( $attributes['postContentLength'] ) ? $attributes['postContentLength'] : 100;
				$eb_item_output .= sprintf(
					'<div class="eb-excerpt"><p>%1$s</p></div>',
					esc_html( eb_trim_content( get_the_content(), $content_length, '...' ) )
				);
			}

			// read more link
			if ( $display_read_more && $read_more_text !== '' ) {
				$eb_item_output .= sprintf(
					'<div class="eb-read-more"><div class="eb-readmore-button eb-inline-block eb-float-left"><a href="%1$s" class="eb-inline-block eb-btn">%2$s</a></div></div>',
					esc_url( get_the_permalink( get_the_ID() ) ),
					esc_html( $read_more_text )
				);
			}

			// close eb-content-meta
			$eb_item_output .= sprintf(
				'</div>'
			);

			// close eb-content
			$eb_item_output .= sprintf(
				'</div>'
			);

			// clear floats
			$eb_item_output .= sprintf(
				'<div class="eb-clearboth"></div>'
			);

			// close article
			$eb_item_output .= sprintf(
				'</article>'
			);

		endwhile;
	endif;

	$classes = 'eb-recent-posts-list';
	// if author is displayed
	if ( isset( $attributes['displayPostAuthor'] ) && $attributes['displayPostAuthor'] ) {
		$classes .= ' eb-has-author';
	}
	// if categories are displayed
	if ( isset( $attributes['displayPostCategories'] ) && $attributes['displayPostCategories'] ) {
		$classes .= ' eb-has-categories';
	}
	// if category is selected
	if ( isset( $attributes['category'] ) && $attributes['category'] ) {
		$classes .= ' eb-category-' . $attributes['category'];
	}
	// if date is displayed
	if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
		$classes .= ' eb-has-dates';
	}
	// if post content is displayed
	if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent'] ) {
		$classes .= ' eb-has-content';
	}
	// if featured image is displayed
	if ( isset( $attributes['displayPostContent'] ) && $attributes['displayPostContent'] ) {
		$classes .= ' eb-has-thumbnail';
	}

	// $classes use later

	/**
	 * wrapper classes
	 */
	$wrapper_classes = 'eb-recent-posts-wrap';
	if ( isset( $posts_layout ) ) {
		$wrapper_classes .= $posts_layout !== 'eb-list' ? ' eb-is-grid' : ' eb-is-list';
		if ( $posts_layout === 'eb-grid' && isset( $posts_grid_col ) ) {
			$wrapper_classes .= ' eb-grid-columns-' . $posts_grid_col;
		}
	}

	$eb_recent_posts_output = sprintf(
		'<div class="%1$s">%2$s</div>',
		esc_attr( $wrapper_classes ),
		$eb_item_output
	);

	return $eb_recent_posts_output;
}

/**
 * Return trimmed text to certain character length
 *
 * @param $content string text to trim
 * @param $num_char int number of characters
 * @param $more string more text '...' is default
 *
 * @return bool|string
 */
function eb_trim_content( $content, $num_char = 50, $more = null ) {

	/**
	 * set default more text '...'
	 */
	if ( null === $more ) {
		$more = __( '&hellip;' );
	}

	// remove all html characters
	$content = wp_strip_all_tags( $content );

	if ( strlen( $content ) < $num_char ) {
		$content = $content . $more;
	} else {
		$offset  = ( $num_char - strlen( $more ) ) - strlen( $content );
		$content = substr( $content, 0, strrpos( $content, ' ', $offset ) );
		$content = $content . $more;
	}

	return $content;
}
