<?php
/**
 * exit if file is accessed directly
 */
defined('ABSPATH') || exit;

/**
 * Class EB_About_Page
 */
class EB_About_Page {


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
	 * EB_About_Page constructor.
	 *
	 * @param $args array constructor arguments
	 */
	public function __construct( $args ) {
	    // set plugin name
		$this->name = $args['name'];
		// set plugin slug
		$this->slug = $args['slug'];
		// set plugin version
		$this->version = $args['version'];

		// about page action hook
		add_action( 'admin_menu', array( $this, 'eb_add_about_page_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'eb_about_page_admin_scripts') );
	}

	/**
     * load about page scripts and styles
     *
	 * @param $page string current page
	 */
	public function eb_about_page_admin_scripts( $page ) {
	    // enqueue on about page only
		if ( ! ( $page == 'toplevel_page_easy-blocks' ) ) {
			return;
		}

		wp_enqueue_style(
			'eb-about-page-styles',
			EB_ABOUT_DIR_URI . 'assets/css/about-page-styles.css',
			array(),
			$this->version
		);
	}

	/**
	 * add admin menu for easy blocks
	 */
	public function eb_add_about_page_menu() {
        // add menu page 'Easy Blocks'
		add_menu_page(
			$this->name,
			$this->name,
			'manage_options',
			$this->slug,
			array( $this, 'eb_about_page_cb'),
			'data:image/svg+xml;base64,' . base64_encode("<svg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
	            <g id='easy-blocks-logo' transform='translate(1)'>
	                <path fill='#ffffff' d='M8.67857143,19.6774194 L-1.77635684e-15,14.516129 L-1.77635684e-15,12.2580645 L8.67857143,7.09677419 L8.67857143,9.35483871 L1.8984375,13.3870968 L8.67857143,17.4193548 L15.4285714,13.4050179 L15.4285714,6.27240143 L8.67857143,2.25806452 L-1.77635684e-15,7.41935484 L-1.77635684e-15,5.16129032 L8.67857143,0 L17.3571429,5.16129032 L17.3571429,5.31100052 L17.3771286,5.32258065 L17.3771286,14.3169955 L17.3571429,14.3285756 L17.3571429,14.516129 L8.67857143,19.6774194 Z M0,8.70967742 L8.67857143,3.5483871 L8.67857143,5.80645161 L0,10.9677419 L0,8.70967742 Z'/>
	            </g>
	        </svg>")
		);
	}

	/**
	 * about page content
	 */
	public function eb_about_page_cb() {
	    // list of available blocks
	    $blocks = array(
            'eb_block_alert' => array(
                'name' => __( 'EB Alert', EB_SLUG ),
                'icon' => 'eb_icon_alert',
                'desc' => 'Add different type of Alerts to the site, with customizable styles and colors.'
            ),
            'eb_block_button' => array(
	            'name' => __( 'EB Button', EB_SLUG ),
	            'icon' => 'eb_icon_button',
	            'desc' => 'Add different type and color of Buttons to the site, with customizable options.'
            ),
            'eb_block_posts' => array(
	            'name' => __( 'EB Posts', EB_SLUG ),
	            'icon' => 'eb_icon_posts',
	            'desc' => 'Add List or Grid styled Recent Posts with thumbnails, category & customizable settings.'
            ),
        );
		?>

		<div class="wrap eb-about-wrap">
			<!-- header -->
			<div class="eb-about-header">
				<div class="eb-logo eb-clearfix">
                    <?php
                    $version = sprintf(
	                    '<span class="eb-version eb-float-left eb-inline-block"><span>%1$s</span></span>',
	                    EB_VERSION
                    );

                    $logo = sprintf(
                        '<div class="eb-logo eb-clearfix">%1$s %2$s</div>',
	                    $this->get_icon_svg( 'eb_logo_white', 173, 50 ),
	                    $version
                    );
                    echo $logo;
                    ?>
				</div>
				<div class="eb-clearboth"></div>
			</div>
			<!-- end:header -->

			<!-- blocks -->
			<section class="eb-section eb-bg-light">

                <?php
                $about_output = '<div class="sec-header">';
                $about_output .= sprintf(
	                '<h2 class="sec-heading">%1$s</h2>',
	                __( 'Available Blocks', EB_SLUG )
                );
                $about_output .= sprintf(
	                '<p class="sec-subheading">%1$s</p>',
	                __( 'Following stable block are available in Easy Bloack collection. New Blocks are under development and will be released soon in nex releases.', EB_SLUG )
                );
                $about_output .= '</div>';

				$blocks_list = '';
				if ( ! empty( $blocks ) ) {
					$blocks_list .= '<div class="eb-row">';
					foreach ( $blocks as $block ) {

						$blocks_icont = sprintf(
							'<div class="eb-card-image"><span class="eb-inline-block">%1$s</span></div>',
							$this->get_icon_svg( $block['icon'], 40, 40 )
						);

						$blocks_text = sprintf(
							'<div class="eb-card-body"><h3 class="eb-card-title">%1$s</h3><p>%2$s</p></div>',
							$block['name'],
							$block['desc']
						);

						$blocks_item = sprintf(
							'<div class="eb-card-wrap"><div class="eb-card eb-text-center">%1$s %2$s</div></div>',
							$blocks_icont,
							$blocks_text
						);

						$blocks_list .= sprintf(
							'<div class="eb-col-4">%1$s</div>',
							$blocks_item
						);
					}
					$blocks_list .= '</div>';
				}

				$about_output .= $blocks_list;
				echo $about_output;
				?>
			</section>
			<!-- end:blocks -->

		</div>
		<?php
	}

	/**
	 * Gets the SVG code for a given icon.
	 *
	 * @param string $icon name of the icon
     * @param int $height icon height
     * @param int $width icon width
	 *
	 * @return null|string icon or null if wrong name is passed
	 */
	public function get_icon_svg( $icon, $width = 24, $height = 24 ) {
		return EB_Icon_Loader::get_svg( $icon, $width, $height );
	}

}