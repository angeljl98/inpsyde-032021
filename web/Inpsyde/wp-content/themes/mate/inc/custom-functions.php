<?php
/**
 * Custom Functions.
 *
 * @package Mate
 */


if( !function_exists( 'mate_fonts_url' ) ) :

    //Google Fonts URL
    function mate_fonts_url(){

        $fonts_url = '';
        $fonts = array();

        $mate_font_1 = 'Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900';
        $mate_font_2 = 'Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap';
        
        $mate_fonts = array();
        $mate_fonts[] = $mate_font_1;
        $mate_fonts[] = $mate_font_2;

        $mate_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

        $i = 0;
        for ( $i = 0; $i < count( $mate_fonts ); $i++ ) {

            if ( 'off' !== sprintf( _x( 'on', '%s font: on or off', 'mate' ), $mate_fonts[$i] ) ) {
                $fonts[] = $mate_fonts[$i];
            }

        }

        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urldecode( implode( '|', $fonts ) ),
            ), 'https://fonts.googleapis.com/css' );
        }

        return esc_url_raw($fonts_url);
    }

endif;

if( !function_exists( 'mate_social_menu_icon' ) ) :

    function mate_social_menu_icon( $item_output, $item, $depth, $args ) {

        // Add Icon
        if ( isset( $args->theme_location ) && 'mate-social-menu' === $args->theme_location ) {

            $svg = Mate_SVG_Icons::get_theme_svg_name( $item->url );

            if ( empty( $svg ) ) {
                $svg = mate_the_theme_svg( 'link',$return = true );
            }

            $item_output = str_replace( $args->link_after, '</span>' . $svg, $item_output );
        }

        return $item_output;
    }
    
endif;

add_filter( 'walker_nav_menu_start_el', 'mate_social_menu_icon', 10, 4 );

function mate_add_sub_toggles_to_main_menu( $args, $item, $depth ) {

    // Add sub menu toggles to the Expanded Menu with toggles.
    if ( isset( $args->show_toggles ) && $args->show_toggles ) {
        // Wrap the menu item link contents in a div, used for positioning.
        $args->before = '<div class="submenu-wrapper">';
        $args->after  = '';
        // Add a toggle to items with children.
        if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
            $toggle_target_string = '.menu-item.menu-item-' . $item->ID . ' > .sub-menu';
            // Add the sub menu toggle.
            $args->after .= '<button class="toggle submenu-toggle" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="250" aria-expanded="false"><span class="btn__content" tabindex="-1"><span class="screen-reader-text">' . __( 'Show sub menu', 'mate' ) . '</span>' . mate_get_theme_svg( 'chevron-down' ) . '</span></button>';
        }
        // Close the wrapper.
        $args->after .= '</div><!-- .submenu-wrapper -->';
        // Add sub menu icons to the primary menu without toggles.
    } elseif ( 'mate-primary-menu' === $args->theme_location ) {
        if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
            $args->after = '<span class="icon">'.mate_the_theme_svg('chevron-down',true).'</span>';
        } else {
            $args->after = '';
        }
    }

    return $args;
}
add_filter( 'nav_menu_item_args', 'mate_add_sub_toggles_to_main_menu', 10, 3 );

if( !function_exists( 'mate_page_lists' ) ) :

    // Page List.
    function mate_page_lists(){

        $page_lists = array();
        $page_lists[''] = esc_html__( '-- Select Page --','mate' );
        $pages = get_pages(
            array (
                'parent'  => 0, // replaces 'depth' => 1,
            )
        );
        foreach( $pages as $page ){

            $page_lists[$page->ID] = $page->post_title;

        }
        return $page_lists;
    }

endif;

if( !function_exists( 'mate_sanitize_post_layout_option_meta' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_post_layout_option_meta( $input ){

        $metabox_options = array( 'global-layout','layout-1','layout-2' );
        if( in_array( $input,$metabox_options ) ){

            return $input;

        }else{

            return '';

        }

    }

endif;

if( !function_exists( 'mate_sanitize_header_overlay_option_meta' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_header_overlay_option_meta( $input ){

        $metabox_options = array( 'global-layout','enable-overlay' );
        if( in_array( $input,$metabox_options ) ){

            return $input;

        }else{

            return '';

        }

    }

endif;

/**
 * Mate SVG Icon helper functions
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */
if ( ! function_exists( 'mate_the_theme_svg' ) ):
    /**
     * Output and Get Theme SVG.
     * Output and get the SVG markup for an icon in the Mate_SVG_Icons class.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function mate_the_theme_svg( $svg_name, $return = false ) {

        if( $return ){

            return mate_get_theme_svg( $svg_name ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in mate_get_theme_svg();.

        }else{

            echo mate_get_theme_svg( $svg_name ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in mate_get_theme_svg();.
            
        }
    }

endif;

if ( ! function_exists( 'mate_get_theme_svg' ) ):

    /**
     * Get information about the SVG icon.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function mate_get_theme_svg( $svg_name ) {

        // Make sure that only our allowed tags and attributes are included.
        $svg = wp_kses(
            Mate_SVG_Icons::get_svg( $svg_name ),
            array(
                'svg'     => array(
                    'class'       => true,
                    'xmlns'       => true,
                    'width'       => true,
                    'height'      => true,
                    'viewbox'     => true,
                    'aria-hidden' => true,
                    'role'        => true,
                    'focusable'   => true,
                ),
                'path'    => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'd'         => true,
                    'transform' => true,
                ),
                'polygon' => array(
                    'fill'      => true,
                    'fill-rule' => true,
                    'points'    => true,
                    'transform' => true,
                    'focusable' => true,
                ),
            )
        );
        if ( ! $svg ) {
            return false;
        }
        return $svg;

    }

endif;


if( !function_exists( 'mate_post_category_list' ) ) :

    // Post Category List.
    function mate_post_category_list( $select_cat = true ){

        $post_cat_lists = get_categories(
            array(
                'hide_empty' => '0',
                'exclude' => '1',
            )
        );

        $post_cat_cat_array = array();
        if( $select_cat ){

            $post_cat_cat_array[''] = esc_html__( '-- Select Category --','mate' );

        }

        foreach ( $post_cat_lists as $post_cat_list ) {

            $post_cat_cat_array[$post_cat_list->slug] = $post_cat_list->name;

        }

        return $post_cat_cat_array;
    }

endif;


if( !function_exists('mate_sanitize_meta_pagination') ):

    /** Sanitize Enable Disable Checkbox **/
    function mate_sanitize_meta_pagination( $input ) {

        $valid_keys = array('global-layout','no-navigation','norma-navigation','ajax-next-post-load');
        if ( in_array( $input , $valid_keys ) ) {
            return $input;
        }
        return '';

    }

endif;

if( !function_exists('mate_disable_post_views') ):

    /** Disable Post Views **/
    function mate_disable_post_views() {

        add_filter('booster_extension_filter_views_ed', function ( ) {
            return false;
        });

    }

endif;

if( !function_exists('mate_disable_post_read_time') ):

    /** Disable Read Time **/
    function mate_disable_post_read_time() {

        add_filter('booster_extension_filter_readtime_ed', function ( ) {
            return false;
        });

    }

endif;

if( !function_exists('mate_disable_post_like_dislike') ):

    /** Disable Like Dislike **/
    function mate_disable_post_like_dislike() {

        add_filter('booster_extension_filter_like_ed', function ( ) {
            return false;
        });

    }

endif;

if( !function_exists('mate_disable_post_author_box') ):

    /** Disable Author Box **/
    function mate_disable_post_author_box() {

        add_filter('booster_extension_filter_ab_ed', function ( ) {
            return false;
        });

    }

endif;


add_filter('booster_extension_filter_ss_ed', function ( ) {
    return false;
});

if( !function_exists('mate_disable_post_reaction') ):

    /** Disable Reaction **/
    function mate_disable_post_reaction() {

        add_filter('booster_extension_filter_reaction_ed', function ( ) {
            return false;
        });

    }

endif;

if ( ! function_exists( 'mate_header_toggle_search' ) ):

    /**
     * Header Search
     **/
    function mate_header_toggle_search() {

        $mate_default = mate_get_default_theme_options();
        $ed_header_search = get_theme_mod( 'ed_header_search', $mate_default['ed_header_search'] );
        $ed_header_search_top_category = get_theme_mod( 'ed_header_search_top_category', $mate_default['ed_header_search_top_category'] );
        $ed_header_search_recent_posts = absint( get_theme_mod( 'ed_header_search_recent_posts',$mate_default['ed_header_search_recent_posts'] ) );

        if( $ed_header_search ){ ?>

            <div class="header-searchbar">
                <div class="header-searchbar-inner">
                    <div class="wrapper">


                        <div class="header-searchbar-area">

                            <a href="javascript:void(0)" class="skip-link-search-start"></a>

                            <?php get_search_form(); ?>

                        </div>

                        <?php if( $ed_header_search_recent_posts || $ed_header_search_top_category ){ ?>

                            <div class="search-content-area">
                                  
                                <?php if( $ed_header_search_recent_posts ){ ?>

                                    <div class="search-recent-posts">
                                        <?php mate_recent_posts_search(); ?>
                                    </div>

                                <?php } ?>

                                <?php if( $ed_header_search_top_category ){ ?>

                                    <div class="search-popular-categories">
                                        <?php mate_header_search_top_cat_content(); ?>
                                    </div>

                                <?php } ?>

                            </div>

                        <?php } ?>

                        <button type="button" id="search-closer" class="close-popup">
                            <?php mate_the_theme_svg('cross'); ?>
                        </button>

                        <a href="javascript:void(0)" class="skip-link-search-end"></a>

                    </div>
                </div>
            </div>

        <?php
        }

    }

endif;

add_action( 'mate_before_footer_content_action','mate_header_toggle_search',10 );


if( !function_exists('mate_recent_posts_search') ):

    // Single Posts Related Posts.
    function mate_recent_posts_search(){

        $mate_default = mate_get_default_theme_options();
        $related_posts_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 5,'post__not_in' => get_option("sticky_posts") ) );

        if( $related_posts_query->have_posts() ): ?>

            <div class="theme-block related-search-posts">

                <div class="theme-block-heading">
                    <?php
                    $recent_post_title_search = esc_html( get_theme_mod( 'recent_post_title_search',$mate_default['recent_post_title_search'] ) );

                    if( $recent_post_title_search ){ ?>
                        <h2 class="theme-block-title">

                            <?php echo esc_html( $recent_post_title_search ); ?>

                        </h2>
                    <?php } ?>
                </div>

                <div class="theme-list-group recent-list-group">

                    <?php
                    while( $related_posts_query->have_posts() ):
                        $related_posts_query->the_post();

                        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'medium' ); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('theme-list-article'); ?>>
                            <header class="entry-header">
                                <h3 class="entry-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                            </header>
                        </article>

                    <?php 
                    endwhile; ?>

                </div>

            </div>

            <?php
            wp_reset_postdata();

        endif;

    }

endif;

if( !function_exists('mate_header_search_top_cat_content') ):

    function mate_header_search_top_cat_content(){

        $top_category = 3;

        $post_cat_lists = get_categories(
            array(
                'hide_empty' => '0',
                'exclude' => '1',
            )
        );

        $slug_counts = array();

        foreach( $post_cat_lists as $post_cat_list ){

            if( $post_cat_list->count >= 1 ){

                $slug_counts[] = array( 
                    'count'         => $post_cat_list->count,
                    'slug'          => $post_cat_list->slug,
                    'name'          => $post_cat_list->name,
                    'cat_ID'        => $post_cat_list->cat_ID,
                    'description'   => $post_cat_list->category_description, 
                );

            }

        }

        if( $slug_counts ){?>

            <div class="theme-block popular-search-categories">
                
                <div class="theme-block-heading">
                    <?php
                    $mate_default = mate_get_default_theme_options();
                    $top_category_title_search = esc_html( get_theme_mod( 'top_category_title_search',$mate_default['top_category_title_search'] ) );

                    if( $top_category_title_search ){ ?>
                        <h2 class="theme-block-title">

                            <?php echo esc_html( $top_category_title_search ); ?>

                        </h2>
                    <?php } ?>
                </div>

                <?php
                arsort( $slug_counts ); ?>

                <div class="theme-list-group categories-list-group">
                    <div class="wrapper-inner">

                        <?php
                        $i = 1;
                        foreach( $slug_counts as $key => $slug_count ){

                            if( $i > $top_category){ break; }
                            
                            $cat_link           = get_category_link( $slug_count['cat_ID'] );
                            $cat_name           = $slug_count['name'];
                            $cat_slug           = $slug_count['slug'];
                            $cat_count          = $slug_count['count'];
                            $twp_term_image = get_term_meta( $slug_count['cat_ID'], 'twp-term-featured-image', true ); ?>

                            <div class="column column-4 column-sm-12">
                                <article id="post-<?php the_ID(); ?>" <?php post_class('theme-grid-article'); ?>>
                                    <div class="article-area article-height-alt-medium article-heading-small aaaaaaaaa">
                                        <div class="article-wrapper">
                                            <div class="article-panel element-overlayed panel-scheme-<?php echo $i; ?>">
                                                <div class="entry-thumbnail">
                                                    <?php if ($twp_term_image) { ?>

                                                        <a href="<?php echo esc_url($cat_link); ?>" class="data-bg"
                                                           data-background="<?php echo esc_url($twp_term_image); ?>"></a>

                                                    <?php } ?>
                                                    <div class="background-tint"></div>
                                                    <div class="background-gradient"></div>
                                                </div>
                                                <div class="entry-details">
                                                    <header class="entry-header">


                                                        <h3 class="entry-title">
                                                            <a href="<?php echo esc_url($cat_link); ?>">
                                                                <?php echo esc_html($cat_name); ?>
                                                            </a>
                                                        </h3>
                                                    </header>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <?php
                            $i++;

                        } ?>

                    </div>
                </div>

            </div>
        <?php
        }

    }

endif;

if( !function_exists('mate_content_offcanvas') ):

    // Offcanvas Contents
    function mate_content_offcanvas(){ ?>

        <div id="offcanvas-menu">
            <div class="offcanvas-wraper">

                <div class="close-offcanvas-menu">
                    <div class="offcanvas-close">

                        <a href="javascript:void(0)" class="skip-link-menu-start"></a>

                        <button type="button" class="button-offcanvas-close">
                            <?php mate_the_theme_svg('cross'); ?>
                        </button>

                    </div>
                </div>

                <div id="primary-nav-offcanvas" class="offcanvas-item offcanvas-main-navigation">
                    <nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'mate'); ?>" role="navigation">
                        <ul class="primary-menu theme-menu">

                            <?php
                            if( has_nav_menu('mate-primary-menu') ){

                                wp_nav_menu(
                                    array(
                                        'container' => '',
                                        'items_wrap' => '%3$s',
                                        'theme_location' => 'mate-primary-menu',
                                        'show_toggles' => true,
                                    )
                                );

                            }else{
                                
                                wp_list_pages(
                                    array(
                                        'match_menu_classes' => true,
                                        'title_li' => false,
                                        'show_toggles' => true,
                                        'walker' => new Mate_Walker_Page(),
                                    )
                                );

                            } ?>

                        </ul>
                    </nav><!-- .primary-menu-wrapper -->
                </div>

                <?php if (has_nav_menu('mate-social-menu')) { ?>

                    <div id="social-nav-offcanvas" class="offcanvas-item offcanvas-social-navigation">

                        <?php wp_nav_menu(
                                array(
                                'theme_location' => 'mate-social-menu',
                                'link_before' => '<span class="screen-reader-text">',
                                'link_after' => '</span>',
                                'container' => 'div',
                                'container_class' => 'social-menu',
                                'depth' => 1,
                            )
                        ); ?>

                    </div>

                <?php } ?>

                <a href="javascript:void(0)" class="skip-link-menu-end"></a>
                
            </div>
        </div>

    <?php
    }

endif;

add_action( 'mate_before_footer_content_action','mate_content_offcanvas',30 );

if( !function_exists('mate_footer_content_widget') ):

    function mate_footer_content_widget(){

        $mate_default = mate_get_default_theme_options();
        if (is_active_sidebar('mate-footer-widget-0') || 
            is_active_sidebar('mate-footer-widget-1') || 
            is_active_sidebar('mate-footer-widget-2')):
            $x = 1;
            $footer_sidebar = 0;
            do {
                if ($x == 3 && is_active_sidebar('mate-footer-widget-2')) {
                    $footer_sidebar++;
                }
                if ($x == 2 && is_active_sidebar('mate-footer-widget-1')) {
                    $footer_sidebar++;
                }
                if ($x == 1 && is_active_sidebar('mate-footer-widget-0')) {
                    $footer_sidebar++;
                }
                $x++;
            } while ($x <= 3);
            if ($footer_sidebar == 1) {
                $footer_sidebar_class = 12;
            } elseif ($footer_sidebar == 2) {
                $footer_sidebar_class = 6;
            } else {
                $footer_sidebar_class = 4;
            } ?>

            <div class="footer-widgetarea">
                <div class="wrapper">
                    <div class="wrapper-inner">

                        <?php if( is_active_sidebar('mate-footer-widget-0') ): ?>
                            <div class="column <?php echo 'column-' . absint( $footer_sidebar_class ); ?> column-sm-12">
                                <?php dynamic_sidebar('mate-footer-widget-0'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if( is_active_sidebar('mate-footer-widget-1') ): ?>
                            <div class="column <?php echo 'column-' . absint( $footer_sidebar_class ); ?> column-sm-12">
                                <?php dynamic_sidebar('mate-footer-widget-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if( is_active_sidebar('mate-footer-widget-2') ): ?>
                            <div class="column <?php echo 'column-' . absint( $footer_sidebar_class ); ?> column-sm-12">
                                <?php dynamic_sidebar('mate-footer-widget-2'); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        <?php
        endif;

    }

endif;

add_action( 'mate_footer_content_action','mate_footer_content_widget',10 );

if( !function_exists('mate_footer_content_info') ):

    /**
     * Footer Copyright Area
    **/
    function mate_footer_content_info(){

        $mate_default = mate_get_default_theme_options(); ?>
        <div class="theme-footer-spacer">
            <div class="wrapper">
                <div class="wrapper-inner">
                    <div class="column column-12">
                        <div class="footer-spacer"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-info">
            <div class="wrapper">
                <div class="wrapper-inner">

                    <div class="column column-12">
                        <div class="footer-credits">

                            <div class="footer-copyright">
                                <?php
                                $footer_copyright_text = wp_kses_post( get_theme_mod( 'footer_copyright_text', $mate_default['footer_copyright_text'] ) );
                                echo esc_html__('Copyright ', 'mate') . '&copy ' . absint(date('Y')) . ' <a href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name', 'display')) . '" ><span>' . esc_html( get_bloginfo( 'name', 'display' ) ) . '. </span></a> ' . esc_html( $footer_copyright_text );

                                echo esc_html__('Theme: ', 'mate') . 'Mate ' . esc_html__('By ', 'mate') . '<a href="' . esc_url('https://www.themeinwp.com/theme/mate') . '"  title="' . esc_attr__('Themeinwp', 'mate') . '" target="_blank" rel="author"><span>' . esc_html__('Themeinwp. ', 'mate') . '</span></a>';
                                echo esc_html__('Powered by ', 'mate') . '<a href="' . esc_url('https://wordpress.org') . '" title="' . esc_attr__('WordPress', 'mate') . '" target="_blank"><span>' . esc_html__('WordPress.', 'mate') . '</span></a>';
                                
                                ?>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php
    }

endif;

add_action( 'mate_footer_content_action','mate_footer_content_info',20 );


if( !function_exists('mate_footer_go_to_top') ):

    // Scroll to Top render content
    function mate_footer_go_to_top(){
        ?>
        <button type="button" class="scroll-up">
            <?php mate_the_theme_svg('arrow-up'); ?>
        </button>
        <?php
    }

endif;

add_action( 'mate_footer_content_action','mate_footer_go_to_top',30 );

if( !function_exists('mate_color_schema_color') ):

    function mate_color_schema_color( $current_color ){

        $colors_schema = array(

            'simple' => array(

                'background_color' => '#ffffff',
                'mate_primary_color' => '#F44336',
                'mate_secondary_color' => '#FF9800',
                'mate_general_text_color' => '#000000',

            ),

            'fancy' => array(

                'background_color' => '#faf7f2',
                'mate_primary_color' => '#017eff',
                'mate_secondary_color' => '#fc9285',
                'mate_general_text_color' => '#455d58',

            ),

            'dark' => array(

                'background_color' => '#222222',
                'mate_primary_color' => '#007CED',
                'mate_secondary_color' => '#fb7268',
                'mate_general_text_color' => '#ffffff',

            ),

        );

        if( isset( $colors_schema[$current_color] ) ){
            
            return $colors_schema[$current_color];

        }

        return;

    }

endif;



if ( ! function_exists( 'mate_color_schema_color_action' ) ) :

    function mate_color_schema_color_action() {

        if( isset( $_POST['currentColor'] ) && sanitize_text_field( wp_unslash( $_POST['currentColor'] ) ) ){
         
            $current_color = sanitize_text_field( wp_unslash( $_POST['currentColor'] ) );

            $color_schemes = mate_color_schema_color( $current_color );

            if ( $color_schemes ) {
                echo json_encode( $color_schemes );
            }
        }
    
        wp_die();

    }

endif;

add_action( 'wp_ajax_nopriv_mate_color_schema_color', 'mate_color_schema_color_action' );
add_action( 'wp_ajax_mate_color_schema_color', 'mate_color_schema_color_action' );

if( !function_exists('mate_header_banner_single') ):

    function mate_header_banner_single(){

        global $post;
        $mate_post_layout = '';
        $mate_default = mate_get_default_theme_options();
        if( is_singular() ){

            $mate_post_layout = esc_html( get_post_meta( $post->ID, 'mate_post_layout', true ) );
            if( $mate_post_layout == '' || $mate_post_layout == 'global-layout' ){
                
                $mate_post_layout = get_theme_mod( 'mate_single_post_layout',$mate_default['mate_single_post_layout'] );
            }

        }

        if( $mate_post_layout == 'layout-2' && is_singular('post') ) {

            $twp_gradientcolor_type = esc_html( get_post_meta( get_the_ID(), 'twp_gradientcolor_type', true ) );

            if( empty( $twp_gradientcolor_type ) || $twp_gradientcolor_type == 'global' ){
                $color_type = get_theme_mod('global_single_gradient_overlay_color', $mate_default['global_single_gradient_overlay_color']);
            }else{
                $color_type = $twp_gradientcolor_type;
            } 

            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();

                    $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
                    $mate_ed_feature_image = esc_html( get_post_meta( get_the_ID(), 'mate_ed_feature_image', true ) );
                    ?>

                    <section class="inner-featured-banner  <?php if( empty( $mate_ed_feature_image ) && $featured_image[0] ){ echo 'banner-has-image'; } ?>">
                        <div class="featured-banner-media">
                             <div class="article-panel element-overlayed panel-scheme-<?php echo esc_attr( $color_type ); ?>">
                                 <div class="entry-thumbnail">

                                    <?php if( empty( $mate_ed_feature_image ) && $featured_image[0] ){ ?>
                                        
                                        <div class="data-bg data-bg-banner" data-background="<?php echo esc_url($featured_image[0]); ?>"></div>

                                     <?php } ?>

                                     <div class="background-tint"></div>
                                     <div class="background-gradient"></div>
                                 </div>

                                 <div class="entry-details">

                                     <div class="featured-banner-content">
                                         <div class="wrapper">

                                             <div class="wrapper-inner">
                                                 <div class="column column-9 column-sm-12">
                                                     <div class="article-heading-large">

                                                         <div class="entry-meta">
                                                             <?php
                                                             mate_entry_footer( $cats = true, $tags = false, $edits = false );
                                                             ?>
                                                         </div>

                                                         <header class="entry-header">
                                                             <h1 class="entry-title">
                                                                 <?php the_title(); ?>
                                                             </h1>
                                                         </header>

                                                         <div class="entry-meta">
                                                             <?php
                                                             mate_posted_by();
                                                             mate_posted_on();
                                                             ?>
                                                         </div>
                                                     </div>

                                                 </div>
                                             </div>

                                         </div>
                                     </div>

                                 </div>

                             </div>
                        </div>

                    </section>

                <?php
                endwhile;
            endif;
               
        }

    }

endif;

if( !function_exists('mate_header_banner_archive') ):

    function mate_header_banner_archive(){


        if( is_category() ) {

            $mate_default = mate_get_default_theme_options();
            $color_type = get_theme_mod('global_archive_gradient_overlay_color', $mate_default['global_archive_gradient_overlay_color']);
            $category = get_queried_object();
            $featured_image = get_term_meta( $category->term_id, 'twp-term-featured-image', true ); ?>

            <section class="inner-featured-banner  <?php if( $featured_image ){ echo 'banner-has-image'; } ?>">
                <div class="featured-banner-media">
                     <div class="article-panel element-overlayed panel-scheme-<?php echo esc_attr( $color_type ); ?>">
                         
                         <div class="entry-thumbnail">
                             <div class="data-bg data-bg-banner" data-background="<?php echo esc_url($featured_image); ?>"></div>
                             <div class="background-tint"></div>
                             <div class="background-gradient"></div>
                         </div>

                         <div class="entry-details">

                             <div class="featured-banner-content">
                                 <div class="wrapper">

                                     <div class="wrapper-inner">
                                         <div class="column column-9 column-sm-12">
                                             <div class="article-heading-large">

                                                 <?php if( isset( $category->name ) ){ ?>

                                                    <header class="entry-header">
                                                        <h1 class="entry-title">
                                                            <?php echo esc_html( $category->name ); ?>
                                                        </h1>
                                                    </header>

                                                <?php } ?>

                                                <?php if( isset( $category->description ) ){ ?>

                                                    <p>
                                                        <?php echo esc_html( $category->description ); ?>
                                                    </p>

                                                <?php } ?>
                                                 
                                             </div>

                                         </div>
                                     </div>

                                 </div>
                             </div>

                         </div>
                     </div>
                </div>
            </section>

        <?php
        }

    }

endif;

if( class_exists( 'Booster_Extension_Class') ){

    add_filter('booster_extension_filter_ss_ed','mate_booster_social_share_disable');

    if( !function_exists('mate_booster_social_share_disable') ):

        function mate_booster_social_share_disable(){
            return false;
        }

    endif;

}

if( !function_exists('mate_sidebar_layout') ):

    // Sidebar Option 
    function mate_sidebar_layout( ){
        
        $sidebar_layout = array(
            'right-sidebar' => esc_html__('Content & Right Sidebar','mate'),
            'left-sidebar' => esc_html__('Left Sidebar & Content','mate'),
            'both-sidebar' => esc_html__('Left Sidebar, Content & Right Sidebar','mate'),
            'content-left-right' => esc_html__('Content, Left Sidebar & Right Sidebar','mate'),
            'left-right-content' => esc_html__('Left Sidebar, Right Sidebar & Content','mate'),
            'no-sidebar' => esc_html__('No Sidebar','mate'),
        );

        return $sidebar_layout;

    }

endif;

if( !function_exists('mate_gradient_overlay_color_option') ):

    // Sidebar Option 
    function mate_gradient_overlay_color_option( ){
        
        $sidebar_layout = array(
            '1' => esc_html__('Color Type 1','mate'),
            '2' => esc_html__('Color Type 2','mate'),
            '3' => esc_html__('Color Type 3','mate'),
            '4' => esc_html__('Color Type 4','mate'),
        );

        return $sidebar_layout;

    }

endif;

if( !function_exists('mate_get_sidebar') ):

    // Get Sidebars
    function mate_get_sidebar( ){

        global $post;
        $mate_default = mate_get_default_theme_options();
        $sidebar_1_class = '';
        $sidebar_2_class = '';

        if( is_single() || is_page() ){

            $sidebar = esc_attr( get_post_meta( $post->ID, 'mate_post_sidebar_option', true ) );
            if( empty( $sidebar ) || $sidebar == 'global-sidebar' ){

                $sidebar = get_theme_mod( 'global_single_sidebar_layout',$mate_default['global_single_sidebar_layout'] );

            }

        }elseif( is_front_page() ){

            $mate_default = mate_get_default_theme_options();
            $twp_mate_home_sections_1 = get_theme_mod( 'twp_mate_home_sections_1',json_encode( $mate_default['twp_mate_home_sections_1'] ) );
            $twp_mate_home_sections_1 = json_decode( $twp_mate_home_sections_1 );

            $paged_active = false;
            if ( !is_paged() ) {

                $paged_active = true;

            }

            if( $twp_mate_home_sections_1 ){

                foreach( $twp_mate_home_sections_1 as $twp_mate_home_section ){

                    $home_section_type = isset( $twp_mate_home_section->home_section_type ) ? $twp_mate_home_section->home_section_type : '' ;

                    switch( $home_section_type ){

                        case 'latest':

                            $sidebar = isset( $twp_mate_home_section->latest_post_sidebar ) ? $twp_mate_home_section->latest_post_sidebar : '' ;

                        break;

                    }

                }

            }

        }else{
            
            $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

        }

        if( $sidebar == 'both-sidebar' ){

            $sidebar_1_class = 'column-order-3';
            $sidebar_2_class = 'column-order-1';

        }elseif( $sidebar == 'right-sidebar' ){

            $sidebar_1_class = 'column-order-2';
            $sidebar_2_class = '';

        }elseif( $sidebar == 'left-sidebar' ){

            $sidebar_1_class = '';
            $sidebar_2_class = 'column-order-1';

        }elseif( $sidebar == 'content-left-right' ){

            $sidebar_1_class = 'column-order-3';
            $sidebar_2_class = 'column-order-2';

        }elseif( $sidebar == 'left-right-content' ){

            $sidebar_1_class = 'column-order-2';
            $sidebar_2_class = 'column-order-1';

        }elseif( $sidebar == 'no-sidebar' ){

            $sidebar_1_class = '';
            $sidebar_2_class = '';

        }

        if( $sidebar_1_class && is_active_sidebar('sidebar-1') ){ ?>

            <div id="secondary" class="widget-area widget-area-1 <?php echo esc_attr( $sidebar_1_class ); ?>">
                <?php get_sidebar(); ?>
            </div>

        <?php }
        
        if( $sidebar_2_class  && is_active_sidebar('mate-left-sidebar') ){ ?>

            <div id="tertiary" class="widget-area widget-area-2 <?php echo esc_attr( $sidebar_2_class ); ?>">
                <?php get_sidebar('left'); ?>
            </div>

        <?php } ?>

    <?php
    }

endif;

if( !function_exists('mate_get_sidebar_primary_class') ):

    // Get Sidebars
    function mate_get_sidebar_primary_class( ){

        global $post;
        $mate_default = mate_get_default_theme_options();
        $primary_class = 'column-order-2';

        if( is_single() || is_page() ){

            $sidebar = esc_attr( get_post_meta( $post->ID, 'mate_post_sidebar_option', true ) );
            if( empty( $sidebar ) || $sidebar == 'global-sidebar' ){

                $sidebar = get_theme_mod( 'global_single_sidebar_layout',$mate_default['global_single_sidebar_layout'] );

            }

        }elseif( is_front_page() ){

            $twp_mate_home_sections_1 = get_theme_mod( 'twp_mate_home_sections_1',json_encode( $mate_default['twp_mate_home_sections_1'] ) );
            $twp_mate_home_sections_1 = json_decode( $twp_mate_home_sections_1 );

            $paged_active = false;
            if ( !is_paged() ) {

                $paged_active = true;

            }

            if( $twp_mate_home_sections_1 ){

                foreach( $twp_mate_home_sections_1 as $twp_mate_home_section ){

                    $home_section_type = isset( $twp_mate_home_section->home_section_type ) ? $twp_mate_home_section->home_section_type : '' ;

                    switch( $home_section_type ){

                        case 'latest':

                            $sidebar = isset( $twp_mate_home_section->latest_post_sidebar ) ? $twp_mate_home_section->latest_post_sidebar : '' ;
                            
                        break;

                    }

                }

            }

        }else{
            
            $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

        }

        if( $sidebar == 'both-sidebar' ){

            $primary_class = 'column-order-2';

        }elseif( $sidebar == 'right-sidebar' ){

            $primary_class = 'column-order-1';

        }elseif( $sidebar == 'left-sidebar' ){

            $primary_class = 'column-order-2';

        }elseif( $sidebar == 'content-left-right' ){

            $primary_class = 'column-order-1';

        }elseif( $sidebar == 'left-right-content' ){

            $primary_class = 'column-order-3';

        }elseif( $sidebar == 'no-sidebar' ){

            $primary_class = '';

        }

        return $primary_class;
        
    }

endif;

if( !function_exists( 'mate_sanitize_sidebar_option_meta' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_sidebar_option_meta( $mate_input ){

        $mate_metabox_options = array( 'global-sidebar','left-sidebar','right-sidebar','no-sidebar','both-sidebar','content-left-right','left-right-content' );
        if( in_array( $mate_input,$mate_metabox_options ) ){

            return $mate_input;

        }

        return;

    }

endif;

if( !function_exists( 'mate_sanitize_home_type' ) ) :

    // Sidebar Option Sanitize.
    function mate_sanitize_home_type(){

        return $home_sections = array(
            'banner' => esc_html__('Banner Section','mate'),
            'grid' => esc_html__('Grid Section','mate'),
            'carousel' => esc_html__('Carousel Section','mate'),
            'mix-grid' => esc_html__('Mix Grid Section','mate'),
            'you-may-also-like' => esc_html__('You May Also Like Section','mate'),
            'latest' => esc_html__('Latest Posts Section','mate'),
            'category' => esc_html__('Category Section','mate'),
        );

    }

endif;

if( !function_exists( 'mate_image_size' ) ) :

    // Image Size
    function mate_image_size(){

        $mate_default = mate_get_default_theme_options();

        if( is_single() || is_page() ){

            global $post;
            $sidebar = esc_attr( get_post_meta( $post->ID, 'mate_post_sidebar_option', true ) );
            if( empty( $sidebar ) || $sidebar == 'global-sidebar' ){

                $sidebar = get_theme_mod( 'global_single_sidebar_layout',$mate_default['global_single_sidebar_layout'] );

            }

        }elseif( is_front_page() ){
            
            $twp_mate_home_sections_1 = get_theme_mod( 'twp_mate_home_sections_1',json_encode( $mate_default['twp_mate_home_sections_1'] ) );
            $twp_mate_home_sections_1 = json_decode( $twp_mate_home_sections_1 );

            if( $twp_mate_home_sections_1 ){

                foreach( $twp_mate_home_sections_1 as $twp_mate_home_section ){

                    $home_section_type = isset( $twp_mate_home_section->home_section_type ) ? $twp_mate_home_section->home_section_type : '' ;

                    switch( $home_section_type ){

                        case 'latest':

                            $sidebar = isset( $twp_mate_home_section->latest_post_sidebar ) ? $twp_mate_home_section->latest_post_sidebar : '' ;
                            
                        break;

                    }

                }

            }else{

                $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

            }

        }else{
            
            $sidebar = get_theme_mod( 'global_sidebar_layout',$mate_default['global_sidebar_layout'] );

        }
        
        $image_size = 'full';

        if( $sidebar == 'both-sidebar' || $sidebar == 'content-left-right' || $sidebar == 'left-right-content' ){

            if( is_active_sidebar('sidebar-1') && is_active_sidebar('mate-left-sidebar') ){

                $image_size = 'medium_large';

            }elseif( is_active_sidebar('sidebar-1') || is_active_sidebar('mate-left-sidebar') ){
                
                $image_size = 'large';

            }else{

                $image_size = 'full';

            }

        }elseif( $sidebar == 'right-sidebar' ){

            if( is_active_sidebar('sidebar-1') ){

                $image_size = 'large';

            }else{

                $image_size = 'full';

            }

        }elseif( $sidebar == 'left-sidebar' ){

            if( is_active_sidebar('mate-left-sidebar') ){

                $image_size = 'large';

            }else{

                $image_size = 'full';

            }

        }elseif( $sidebar == 'no-sidebar' ){

            $image_size = 'full';

        }else{

            if( is_active_sidebar('sidebar-1') ){

                $image_size = 'large';

            }else{

                $image_size = 'full';

            }

        }

        return $image_size;

    }

endif;

if( !function_exists( 'mate_escape_anchor' ) ) :

    function mate_escape_anchor( $input ){

        $all_tags = array(
            'a' => array(
                'href' => array()
            )
        );
        return wp_kses($input, $all_tags);

    }

endif;

function mate_hex_2_rgba($color, $opacity = false) {
 
    $default = 'rgb(0,0,0)';
 
    //Return default if no color provided
    if(empty($color))
          return $default; 
 
    //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
            $color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
            if(abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}