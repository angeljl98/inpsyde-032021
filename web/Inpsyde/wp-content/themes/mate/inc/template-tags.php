<?php
/**
 * Custom Functions
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */
if( !function_exists('mate_site_logo') ):

    /**
     * Logo & Description
     */
    /**
     * Displays the site logo, either text or image.
     *
     * @param array $args Arguments for displaying the site logo either as an image or text.
     * @param boolean $echo Echo or return the HTML.
     *
     * @return string $html Compiled HTML based on our arguments.
     */
    function mate_site_logo( $args = array(), $echo = true ){

        $logo = get_custom_logo();
        $site_title = get_bloginfo('name');
        $contents = '';
        $classname = '';
        $defaults = array(
            'logo' => '%1$s<span class="screen-reader-text">%2$s</span>',
            'logo_class' => 'site-logo',
            'title' => '<a href="%1$s">%2$s</a>',
            'title_class' => 'site-title',
            'home_wrap' => '<h1 class="%1$s">%2$s</h1>',
            'single_wrap' => '<div class="%1$s site-branding">%2$s</div>',
            'condition' => (is_front_page() || is_home()) && !is_page(),
        );
        $args = wp_parse_args($args, $defaults);
        /**
         * Filters the arguments for `mate_site_logo()`.
         *
         * @param array $args Parsed arguments.
         * @param array $defaults Function's default arguments.
         */
        $args = apply_filters('mate_site_logo_args', $args, $defaults);
        if ( has_custom_logo() ) {
            $contents = sprintf($args['logo'], $logo, esc_html($site_title));
            $classname = $args['logo_class'];
        } else {
            $contents = sprintf($args['title'], esc_url(get_home_url(null, '/')), esc_html($site_title));
            $classname = $args['title_class'];
        }
        $wrap = $args['condition'] ? 'home_wrap' : 'single_wrap';
        $html = sprintf($args[$wrap], $classname, $contents);
        /**
         * Filters the arguments for `mate_site_logo()`.
         *
         * @param string $html Compiled html based on our arguments.
         * @param array $args Parsed arguments.
         * @param string $classname Class name based on current view, home or single.
         * @param string $contents HTML for site title or logo.
         */
        $html = apply_filters('mate_site_logo', $html, $args, $classname, $contents);
        if (!$echo) {
            return $html;
        }
        echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

endif;

if( !function_exists('mate_site_description') ):

    /**
     * Displays the site description.
     *
     * @param boolean $echo Echo or return the html.
     *
     * @return string $html The HTML to display.
     */
    function mate_site_description($echo = true){

        $description = get_bloginfo('description');
        if (!$description) {
            return;
        }
        $wrapper = '<div class="site-description">%s</div>';
        $html = sprintf($wrapper, esc_html($description));
        /**
         * Filters the html for the site description.
         *
         * @param string $html The HTML to display.
         * @param string $description Site description via `bloginfo()`.
         * @param string $wrapper The format used in case you want to reuse it in a `sprintf()`.
         * @since 1.0.0
         *
         */
        $html = apply_filters('mate_site_description', $html, $description, $wrapper);
        if (!$echo) {
            return $html;
        }
        echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

endif;

if( !function_exists('mate_posted_on') ):

    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function mate_posted_on(){

        $mate_default = mate_get_default_theme_options();
        $ed_post_date = absint( get_theme_mod( 'ed_post_date',$mate_default['ed_post_date'] ) );

        if( $ed_post_date ){

            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
            if (get_the_time('U') !== get_the_modified_time('U')) {
                $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
            }

            $time_string = sprintf($time_string,
                esc_attr(get_the_date(DATE_W3C)),
                esc_html(get_the_date()),
                esc_attr(get_the_modified_date(DATE_W3C)),
                esc_html(get_the_modified_date())
            );

            $year = get_the_date('Y');
            $month = get_the_date('m');
            $day = get_the_date('d');
            $link = get_day_link($year, $month, $day);
            $posted_on = '<a href="' . esc_url($link) . '" rel="bookmark">' . $time_string . '</a>';

            echo '<div class="entry-meta-item entry-meta-date">';
            echo '<div class="entry-meta-wrapper">';
            echo '<span class="entry-meta-icon calendar-icon"> ';
            mate_the_theme_svg('calendar');
            echo '</span>';

            echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
            echo '</div>';
            echo '</div>';

        }

    }

endif;

if( !function_exists('mate_posted_by') ) :

    /**
     * Prints HTML with meta information for the current author.
     */
    function mate_posted_by(){   

        $mate_default = mate_get_default_theme_options();
        $ed_post_author = absint( get_theme_mod( 'ed_post_author',$mate_default['ed_post_author'] ) );

        if( $ed_post_author ){

            echo '<div class="entry-meta-item entry-meta-author">';
            echo '<div class="entry-meta-wrapper">';
            echo '<span class="entry-meta-icon author-icon"> ';
            mate_the_theme_svg('user');
            echo '</span>';
            $byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
            echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
            echo '</div>';
            echo '</div>';

        }

    }

endif;

if( !function_exists('mate_entry_footer') ):

    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function mate_entry_footer( $cats = true, $tags = true, $edits = true, $ed_category_meta = 'all-cat', $c_cat = false ){   

        $mate_default = mate_get_default_theme_options();
        $ed_post_category = absint( get_theme_mod( 'ed_post_category',$mate_default['ed_post_category'] ) );
        $ed_post_tags = absint( get_theme_mod( 'ed_post_tags',$mate_default['ed_post_tags'] ) );

        // Hide category and tag text for pages.
        if ('post' === get_post_type() && $cats && $ed_post_category ) {

            if( $ed_category_meta == 'current-cat' ){


                /* translators: used between list items, there is a space after the comma */
                $categories = get_the_category();
                echo '<div class="entry-meta-item entry-meta-categories">';
                echo '<div class="entry-meta-wrapper">';
                /* translators: 1: list of categories. */
            
                echo '<span class="cat-links">';

                    foreach( $categories as $category ){
                        
                        $cat_color = get_option( 'mate_category_color_'.$category->term_id );
                        $cat_color = isset( $cat_color['color_class_term_meta'] ) ? $cat_color['color_class_term_meta'] : 'category-color-1';
                        $cat_name = $category->name;

                        if( $category->slug == $c_cat ){

                            $cat_url = get_category_link( $category->term_id ); ?>

                            <a class="<?php echo esc_attr( $cat_color ); ?>" href="<?php echo esc_url( $cat_url ); ?>" rel="category tag"><span class="category-pointer"></span><?php echo esc_html( $cat_name ); ?></a>

                        <?php
                        }

                    }

                echo '</span>';

                echo '</div>';
                echo '</div>';

            }elseif( $ed_category_meta == 'hide-current-cat' ){

                /* translators: used between list items, there is a space after the comma */
                $categories = get_the_category();
                echo '<div class="entry-meta-item entry-meta-categories">';
                echo '<div class="entry-meta-wrapper">';
                /* translators: 1: list of categories. */
            
                echo '<span class="cat-links">';

                    foreach( $categories as $category ){
                        
                        $cat_color = get_option( 'mate_category_color_'.$category->term_id );
                        $cat_color = isset( $cat_color['color_class_term_meta'] ) ? $cat_color['color_class_term_meta'] : 'category-color-1';
                        $cat_name = $category->name;

                        if( $category->slug != $c_cat ){

                            $cat_url = get_category_link( $category->term_id ); ?>

                            <a class="<?php echo esc_attr( $cat_color ); ?>" href="<?php echo esc_url( $cat_url ); ?>" rel="category tag"><span class="category-pointer"></span><?php echo esc_html( $cat_name ); ?></a>

                        <?php
                        }
                        
                    }

                echo '</span>';

                echo '</div>';
                echo '</div>';

            }else{

                if( $ed_category_meta  != 'no' ){

                    /* translators: used between list items, there is a space after the comma */
                    $categories = get_the_category();
                    echo '<div class="entry-meta-item entry-meta-categories">';
                    echo '<div class="entry-meta-wrapper">';
                    /* translators: 1: list of categories. */
                
                    echo '<span class="cat-links">';

                        foreach( $categories as $category ){
                            
                            $cat_color = get_option( 'mate_category_color_'.$category->term_id );
                            $cat_color = isset( $cat_color['color_class_term_meta'] ) ? $cat_color['color_class_term_meta'] : 'category-color-1';
                            $cat_name = $category->name;
                            $cat_url = get_category_link( $category->term_id );
                            ?>

                            <a class="<?php echo esc_attr( $cat_color ); ?>" href="<?php echo esc_url( $cat_url ); ?>" rel="category tag"><span class="category-pointer"></span><?php echo esc_html( $cat_name ); ?></a>

                        <?php }

                    echo '</span>';

                    echo '</div>';
                    echo '</div>';

                }

            }
            

            if( $tags && $ed_post_tags ){
                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'mate' ) );
                if( $tags_list ){

                    echo '<div class="entry-meta-item entry-meta-tags">';
                    echo '<div class="entry-meta-wrapper">';
                    echo '<span class="entry-meta-icon tags-icon"> ';
                    mate_the_theme_svg('tag');
                    echo '</span>';

                    echo '<span class="entry-meta-label tags-label">';
                    esc_html_e('In', 'mate');
                    echo '</span>';

                    /* translators: 1: list of tags. */
                    echo '<span class="tags-links">';
                    echo wp_kses_post($tags_list) . '</span>'; // WPCS: XSS OK.
                    echo '</div>';
                    echo '</div>';

                }

            }

            if( $edits ){

                edit_post_link(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Edit <span class="screen-reader-text">%s</span>', 'mate'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            }

        }
    }

endif;

if( !function_exists('mate_is_comment_by_post_author') ):

    /**
     * Comments
     */
    /**
     * Check if the specified comment is written by the author of the post commented on.
     *
     * @param object $comment Comment data.
     *
     * @return bool
     */
    function mate_is_comment_by_post_author( $comment = null ){

        if( is_object( $comment ) && $comment->user_id > 0 ){

            $user = get_userdata( $comment->user_id );
            $post = get_post( $comment->comment_post_ID );

            if( !empty( $user ) && !empty( $post ) ){

                return $comment->user_id === $post->post_author;

            }

        }
        return false;
    }

endif;