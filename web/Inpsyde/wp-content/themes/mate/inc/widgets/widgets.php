<?php
/**
* Widget FUnctions.
*
* @package Mate
*/

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function mate_widgets_init(){
    
    register_sidebar( array(
        'name' => esc_html__('Right Sidebar', 'mate'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'mate'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar( array(
        'name' => esc_html__('Left Sidebar', 'mate'),
        'id' => 'mate-left-sidebar',
        'description' => esc_html__('Add widgets here.', 'mate'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    $mate_default = mate_get_default_theme_options();
    $footer_column_layout = absint( get_theme_mod( 'footer_column_layout',$mate_default['footer_column_layout'] ) );

    for( $i = 0; $i < $footer_column_layout; $i++ ){
    	
    	if( $i == 0 ){ $count = esc_html__('One','mate'); }
    	if( $i == 1 ){ $count = esc_html__('Two','mate'); }
    	if( $i == 2 ){ $count = esc_html__('Three','mate'); }

	    register_sidebar( array(
	        'name' => esc_html__('Footer Widget ', 'mate').$count,
	        'id' => 'mate-footer-widget-'.$i,
	        'description' => esc_html__('Add widgets here.', 'mate'),
	        'before_widget' => '<div id="%1$s" class="widget %2$s">',
	        'after_widget' => '</div>',
	        'before_title' => '<h2 class="widget-title">',
	        'after_title' => '</h2>',
	    ));
	}

}

add_action('widgets_init', 'mate_widgets_init');

/**
 * Widget Base Class.
 */
require get_template_directory() . '/inc/widgets/widget-base-class.php';
/**
 * Recent Post Widget.
 */
require get_template_directory() . '/inc/widgets/recent-post.php';
/**
 * Social Link Widget.
 */
require get_template_directory() . '/inc/widgets/social-link.php';

/**
 * Author Widget.
 */
require get_template_directory() . '/inc/widgets/tab-posts.php';
/**
 * Category Widget.
 */
require get_template_directory() . '/inc/widgets/category.php';