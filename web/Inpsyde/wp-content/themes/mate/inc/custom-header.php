<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * @package Mate
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses mate_header_style()
 */
function mate_custom_header_setup() {

	add_theme_support( 'custom-header', 
		apply_filters( 'mate_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/assets/images/header-media.jpg',
		'video' 				 => true,
		'default-text-color'     => '000000',
		'width'                  => 1920,
		'height'                 => 1080,
		'flex-height'            => true,
		'flex-width'             => true,
		'wp-head-callback'       => 'mate_header_style',
	) ) );

	register_default_headers(
		array(
			'default-image' => array(
				'url'           => '%s/assets/images/header-media.jpg',
				'thumbnail_url' => '%s/assets/images/header-media.jpg',
				'description'   => __( 'Default Header Image', 'mate' ),
			),
		)
	);

}

add_action( 'after_setup_theme', 'mate_custom_header_setup' );

if ( ! function_exists( 'mate_header_style' ) ) :

	/**
	 * Styles the header image and text displayed on the blog
	 *
	 * @see mate_custom_header_setup().
	 */

	function mate_header_style() {
		$header_text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail
		// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value.
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( 'blank' == $header_text_color ) :
		?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}

endif; // mate_header_style