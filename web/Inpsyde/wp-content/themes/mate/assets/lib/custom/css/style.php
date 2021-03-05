<?php
/**
 * Mate Dynamic Styles
 *
 * @package Mate
 */

function mate_dynamic_css()
{

    $mate_default = mate_get_default_theme_options();
    $footer_background_color = mate_sanitize_hex_color(get_theme_mod('footer_background_color', $mate_default['footer_background_color']));
    $footer_text_color = mate_sanitize_hex_color(get_theme_mod('footer_text_color', $mate_default['footer_text_color']));

    $mate_color_schema = get_theme_mod('mate_color_schema', $mate_default['mate_color_schema']);

    if( $mate_color_schema == 'dark' ){

        $background_color = '#222222';
        $mate_primary_color = '#007CED';
        $mate_secondary_color = '#fb7268';
        $mate_general_text_color = '#ffffff';

    }elseif( $mate_color_schema == 'fancy' ){

        $background_color = '#faf7f2';
        $mate_primary_color = '#017eff';
        $mate_secondary_color = '#fc9285';
        $mate_general_text_color = '#455d58';

    }else{

        $background_color = '#ffffff';
        $mate_primary_color = '#F44336';
        $mate_secondary_color = '#FF9800';
        $mate_general_text_color = '#000000';

    }

    $twp_mate_home_sections_1 = get_theme_mod('twp_mate_home_sections_1', json_encode($mate_default['twp_mate_home_sections_1']));
    $twp_mate_home_sections_1 = json_decode($twp_mate_home_sections_1);

    $category_section_background_color = '#f9e3d2';
    if ($twp_mate_home_sections_1) {

        foreach ($twp_mate_home_sections_1 as $twp_mate_home_section) {

            $home_section_type = isset($twp_mate_home_section->home_section_type) ? $twp_mate_home_section->home_section_type : '';

            switch ($home_section_type) {

                case 'category':

                    $category_section_background_color = isset($twp_mate_home_section->background_color) ? $twp_mate_home_section->background_color : '';

                    break;

            }

        }

    }

    echo "<style type='text/css' media='all'>"; ?>

    body.theme-color-schema,
    .preloader,
    .floating-post-navigation .floating-navigation-label,
    .header-searchbar-inner,
    .offcanvas-wraper{
    background-color: <?php echo esc_attr($background_color); ?>;
    }

    .theme-categories-section{
        background-color: <?php echo esc_attr($category_section_background_color); ?>;
    }

    body.theme-color-schema,
    .floating-post-navigation .floating-navigation-label,
    .header-searchbar-inner,
    .offcanvas-wraper{
    color: <?php echo esc_attr($mate_general_text_color); ?>;
    }

    .preloader .loader span,
    .recommendation-stories-featured{
    background: <?php echo esc_attr($mate_general_text_color); ?>;
    }

    .offcanvas-main-navigation li,
    .offcanvas-main-navigation .sub-menu,
    .offcanvas-main-navigation .submenu-wrapper .submenu-toggle,
    .related-post-item,
    .post-navigation,
    .widget .widget-title,
    .site .theme-block .theme-block-heading .theme-block-title,
    #comments .comment-list li{
    border-color: <?php echo mate_hex_2_rgba($mate_general_text_color,0.12); ?>;
    }

    #site-footer{
    background: <?php echo esc_attr($footer_background_color); ?>;
    }

    #site-footer,
    #site-footer a,
    #site-footer button,
    #site-footer .button,
    #site-footer .wp-block-button__link,
    #site-footer .wp-block-file .wp-block-file__button,
    #site-footer input[type="button"],
    #site-footer input[type="reset"],
    #site-footer input[type="submit"]{
    color: <?php echo esc_attr($footer_text_color); ?>;
    }

    #site-footer .widget .widget-title{
    border-color: <?php echo mate_hex_2_rgba($footer_text_color,0.12); ?>;
    }

    #site-footer .footer-spacer{
    background-color: <?php echo mate_hex_2_rgba($footer_text_color,0.12); ?>;
    }

    #site-footer .widget .widget-title:after{
    background-color: <?php echo esc_attr($footer_text_color); ?>;
    }


    .scroll-up,
    .menu-description{
    background: <?php echo esc_attr($mate_primary_color); ?>;
    }

    a {
    color: <?php echo esc_attr($mate_primary_color); ?>;
    }

    .menu-description:before {
    border-left-color: <?php echo esc_attr($mate_primary_color); ?>;
    }

    .navbar-day-night .day-night-toggle-icon i,
    .entry-meta-categories a .category-pointer{
    background: <?php echo esc_attr($mate_secondary_color); ?>;
    }

    <?php echo "</style>";
}

add_action('wp_head', 'mate_dynamic_css', 100);

/**
 * Sanitizing Hex color function.
 */
function mate_sanitize_hex_color($color)
{

    if ('' === $color)
        return '';
    if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color))
        return $color;

}