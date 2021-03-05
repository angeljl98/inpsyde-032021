<?php
/**
* Related Posts Functions.
*
* @package Mate
*/
if( !function_exists('mate_related_posts') ):

    // Single Posts Related Posts.
    function mate_related_posts( $recent = false ){

        $mate_default = mate_get_default_theme_options();
        
        if( is_single() && 'post' === get_post_type() || is_404() || $recent ){

            if( is_404() ){

                $related_posts_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 3,'post__not_in' => get_option("sticky_posts") ) );

            }else{

                $current_id = '';
                $article_wrap_class = '';
                global $post;
                $current_id = $post->ID;
                $cats = get_the_category( $post->ID );
                $category = array();

                if( $cats ){

                    foreach( $cats as $cat ){

                        $category[] = $cat->term_id;

                    }

                }

                $related_posts_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 6, 'post__not_in' => array( $post->ID ), 'category__in' => $category ) );

            }
            
            $ed_related_post = absint( get_theme_mod( 'ed_related_post',$mate_default['ed_related_post'] ) );

            if( ( $ed_related_post || is_404() ) && $related_posts_query->have_posts() ): ?>

                <div class="theme-block related-posts-area">
                    <div class="theme-block-heading">
                        <?php
                        $related_post_title = esc_html( get_theme_mod( 'related_post_title',$mate_default['related_post_title'] ) );

                        if( $related_post_title ){ ?>
                            <h2 class="theme-block-title">

                                <?php echo esc_html( $related_post_title ); ?>

                            </h2>
                        <?php } ?>
                    </div>
                    <div class="theme-list-group related-posts-group">

                        <?php
                        $i = 1;
                        while( $related_posts_query->have_posts() ):
                            $related_posts_query->the_post();

                            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'medium' ); ?>

                            <article id="post-<?php the_ID(); ?>" <?php post_class('theme-related-article theme-list-article'); ?>>
                                <div class="article-area article-height-small article-heading-small">
                                    <div class="article-wrapper">
                                        <div class="article-panel panel-scheme-<?php echo $i; ?>">
                                            <div class="wrapper-inner">

                                                <div class="column column-4">
                                                    <div class="entry-thumbnail">

                                                        <?php if( has_post_thumbnail() ){

                                                            $image_size = mate_image_size();
                                                            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),$image_size ); ?>

                                                            <a href="<?php the_permalink(); ?>" class="data-bg" data-background="<?php echo esc_url(  $featured_image[0] ); ?>"></a>

                                                        <?php } ?>

                                                        <div class="background-tint"></div>
                                                        <div class="background-gradient"></div>

                                                    </div>
                                                </div>

                                                <div class="column column-8">
                                                    <div class="entry-details">

                                                        <div class="entry-meta">
                                                            <?php mate_entry_footer($cats = true, $tags = false, $edits = false); ?>
                                                        </div>
                                                       
                                                        <header class="entry-header">
                                                            <h3 class="entry-title">
                                                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                                            </h3>
                                                        </header>

                                                        <div class="entry-content">

                                                            <?php
                                                            if( has_excerpt() ){

                                                                the_excerpt();

                                                            }else{
                                                                
                                                                echo esc_html( wp_trim_words(get_the_content(), 20, '...') );

                                                            } ?>

                                                        </div>

                                                        <div class="entry-footer">
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
                            </article>

                            <?php
                            $i++;
                            if( $i == 5 ){
                                $i = 1;
                            }

                        endwhile; ?>

                    </div>

                </div>

            <?php
            wp_reset_postdata();

            endif;
        }

    }

endif;

add_action( 'mate_navigation_action','mate_related_posts',20 );