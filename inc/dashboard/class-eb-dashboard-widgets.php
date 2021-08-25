<?php

/**
 * Class EB_Dashboard_Widgets
 */
class EB_Dashboard_Widgets {

	/**
	 * plugin name
	 * @var string $name;
	 */
	private $name;

	/**
	 * plugin slug
	 * @var string $slug
	 */
	private $slug;

	/**
	 * plugin version
	 * @var int $version
	 */
	private $version;

	/**
	 * EB_Dashboard_Widgets constructor.
	 *
	 * @param $args array of arguments
	 */
	public function __construct( $args ) {
		// set plugin name
		$this->name = $args['name'];
		// set plugin slug
		$this->slug = $args['slug'];
		// set plugin version
		$this->version = $args['version'];

		// dashboard action hook
		add_action('wp_dashboard_setup', array( $this, 'eb_add_dashboard_widgets' ) );
	}

	/**
	 * add dashboard widget
	 */
	public function eb_add_dashboard_widgets() {
		// if request if from admin area and current user has permissions
		if ( is_blog_admin() && current_user_can( 'edit_posts' ) ) {
			// add dashboard widget
			wp_add_dashboard_widget(
				$this->slug . '_dashboard_widget',
				$this->name . ' Feedback',
				array( $this, 'eb_dashboard_review_callback' )
			);
		}
	}

	/**
	 * dashboard widget leave review callback
	 */
	public function eb_dashboard_review_callback() {
		$msg = sprintf(
			'<p>Thank you for using %1$s %2$s</p>',
			$this->name,
			$this->version
		);
		$msg .= '<p>Hopefully you enjoy using the plugin. Please consider leaving a quick review to let us know your thoughts.</p>';
		$msg .= sprintf(
			'<p><a target="_blank" href="%1$s" class="button button-primary">Leave Review</a></p>',
			EB_WPORG_SUPPORT_URL . EB_SLUG . '/reviews/#new-post'
		);

		echo $msg;
	}
}