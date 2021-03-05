<?php
/**
 * Header Layout 1
 *
 * @package Mate
 */
$mate_default = mate_get_default_theme_options();
$ed_responsive_menu = get_theme_mod( 'ed_responsive_menu', $mate_default['ed_responsive_menu'] );
if( $ed_responsive_menu ){

    $mobile_menu_class = 'has-hamburger-icon';

}else{

    $mobile_menu_class = 'hide-hamburger-icon';

}
?>

<header id="site-header" class="site-header-main <?php echo $mobile_menu_class; ?>" role="banner">
    <div class="header-navbar">
        <div class="wrapper">

            <div class="navbar-item navbar-item-left">
                <div class="header-titles">
                    <?php
                    // Site title or logo.
                    mate_site_logo();
                    // Site description.
                    mate_site_description();
                    ?>
                </div>
            </div>

            <div class="navbar-item navbar-item-center">
                <div class="site-navigation">
                    <nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e('Horizontal', 'mate'); ?>" role="navigation">
                        <ul class="primary-menu theme-menu">

                            <?php
                            if( has_nav_menu('mate-primary-menu') ){

                                wp_nav_menu(
                                    array(
                                        'container' => '',
                                        'items_wrap' => '%3$s',
                                        'theme_location' => 'mate-primary-menu',
                                        'walker' => new mate\Mate_Walkernav(),
                                    )
                                );

                            }else{

                                wp_list_pages(
                                    array(
                                        'match_menu_classes' => true,
                                        'show_sub_menu_icons' => true,
                                        'title_li' => false,
                                        'walker' => new Mate_Walker_Page(),
                                    )
                                );

                            } ?>

                        </ul>
                    </nav>
                </div>
            </div>

            <?php
            $ed_day_night_mode_switch = get_theme_mod( 'ed_day_night_mode_switch', $mate_default['ed_day_night_mode_switch'] );
            $ed_header_search = get_theme_mod( 'ed_header_search', $mate_default['ed_header_search'] ); ?>

            <div class="navbar-item navbar-item-right hide-no-js">
                <div class="navbar-controls">

                    <?php
                    if( $ed_day_night_mode_switch ){ ?>

                        <button type="button" class="navbar-control navbar-day-night navbar-day-on">
                            <span class="navbar-control-trigger day-night-toggle-icon" tabindex="-1">

                                <span class="moon-toggle-icon">
                                    <i class="moon-icon">
                                        <?php mate_the_theme_svg('moon'); ?>
                                    </i>
                                </span>

                                <span class="sun-toggle-icon">
                                    <i class="sun-icon">
                                        <?php mate_the_theme_svg('sun'); ?>
                                    </i>
                                </span>

                            </span>
                        </button>

                    <?php }
                    
                    if( $ed_header_search ){ ?>

                        <button type="button" class="navbar-control navbar-control-search">
                            <span class="navbar-control-trigger" tabindex="-1">
                                <?php mate_the_theme_svg('search'); ?>
                            </span>
                        </button>

                    <?php } ?>

                    <button type="button" class="navbar-control navbar-control-offcanvas">
                        <span class="navbar-control-trigger" tabindex="-1">
                            <?php mate_the_theme_svg('menu'); ?>
                        </span>
                    </button>
                    
                </div>

            </div>
        </div>
    </div>
</header>
