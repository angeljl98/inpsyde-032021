<?php
/**
 * Receommended Posts
 *
 * @package Mate
 */
if( !function_exists('mate_receommended_posts') ):

    function mate_receommended_posts($twp_mate_home_section){

        $category = isset( $twp_mate_home_section->section_post_category ) ? $twp_mate_home_section->section_post_category : '';
        $section_title = isset( $twp_mate_home_section->section_title ) ? $twp_mate_home_section->section_title : '';
        $section_sub_title = isset( $twp_mate_home_section->section_sub_title ) ? $twp_mate_home_section->section_sub_title : '';
        $recommended_posts_query = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 4, 'post__not_in' => get_option("sticky_posts"), 'category_name' => $category));
        $ed_category_meta = isset( $twp_mate_home_section->ed_category_meta ) ? $twp_mate_home_section->ed_category_meta : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
        $ed_excerpt_content = isset( $twp_mate_home_section->ed_excerpt_content ) ? $twp_mate_home_section->ed_excerpt_content : '' ;

        if ($recommended_posts_query->have_posts()): ?>
            <section class="theme-section theme-recommendation-section">

                <div class="recommendation-stories-featured">
                    <div class="wrapper">
                        <div class="wrapper-inner">
                            <div class="column column-12">
                                <div class="recommendation-stories">

                                    <?php
                                    while( $recommended_posts_query->have_posts() ):
                                        $recommended_posts_query->the_post(); ?>

                                        <article id="post-<?php the_ID(); ?>" <?php post_class('theme-recommendation-article'); ?>>
                                            <div class="article-area article-height-large article-heading-medium">
                                                <div class="article-wrapper">
                                                    <div class="article-panel element-overlayed panel-scheme-3">

                                                        <div class="entry-thumbnail">

                                                            <?php if( has_post_thumbnail() ){

                                                                $image_size = mate_image_size();
                                                                $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),$image_size ); ?>
                                                                <a href="<?php the_permalink(); ?>" class="data-bg" data-background="<?php echo esc_url(  $featured_image[0] ); ?>"></a>

                                                            <?php } ?>

                                                            <div class="background-tint"></div>
                                                            <div class="background-gradient"></div>

                                                        </div>

                                                        <div class="entry-details">

                                                            <?php if( $section_title || $section_sub_title ){ ?>

                                                                <div class="theme-section-heading">

                                                                    <?php if( $section_title ){ ?>
                                                                        <h2 class="theme-section-title"><?php echo esc_html( $section_title ); ?>
                                                                            <span><?php mate_the_theme_svg('arrow-right'); ?></span>
                                                                        </h2>
                                                                    <?php }

                                                                    if ($section_sub_title) { ?>
                                                                        <p class="theme-section-description"><?php echo esc_html( $section_sub_title ); ?></p>
                                                                    <?php } ?>

                                                                </div>

                                                            <?php } ?>

                                                           <?php if( $ed_category_meta != 'hide-cat' ){ ?>

                                                                <div class="entry-meta">
                                                                    <?php mate_entry_footer( $cats = true, $tags = false, $edits = false, $ed_category_meta, $category ); ?>
                                                                </div>

                                                            <?php } ?>

                                                            <header class="entry-header">
                                                                <h3 class="entry-title">
                                                                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                                                </h3>
                                                            </header>

                                                            <?php if( $ed_excerpt_content != 'no' ){ ?>

                                                                <div class="entry-content">

                                                                    <?php
                                                                    if( has_excerpt() ){

                                                                        the_excerpt();

                                                                    }else{

                                                                        echo esc_html( wp_trim_words( get_the_content(), 25, '...' ) );

                                                                    } ?>
                                                                    
                                                                </div>

                                                            <?php } ?>



                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>

                                        <?php
                                        break;
                                    endwhile; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="recommendation-stories-others">
                    <div class="wrapper">
                        <div class="wrapper-inner">

                            <?php
                            $i = 2;
                            while ($recommended_posts_query->have_posts()):
                                $recommended_posts_query->the_post(); ?>

                                <div class="column column-4 column-sm-12">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class('theme-recommendation-article'); ?>>
                                        <div class="article-area article-height-medium">
                                            <div class="article-wrapper">
                                                <div class="article-panel panel-scheme-<?php echo $i; ?>">

                                                    <?php mate_latest_posts_content( $ed_category_meta, $ed_author_meta, $ed_date_meta, $ed_excerpt_content ); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>

                                <?php
                                $i++;

                            endwhile; ?>

                        </div>
                    </div>
                </div>
                
            </section>

            <?php
            wp_reset_postdata();

        endif;
    }
endif;