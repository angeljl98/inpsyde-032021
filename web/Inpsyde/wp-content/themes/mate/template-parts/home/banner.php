<?php
/**
 * Main Banner
 *
 * @package Mate
 */

if( !function_exists('mate_fullwidth_slider') ):

    function mate_fullwidth_slider( $twp_mate_home_section ){

        $category = isset( $twp_mate_home_section->section_post_category ) ? $twp_mate_home_section->section_post_category : '' ;
        $ed_category_meta = isset( $twp_mate_home_section->ed_category_meta ) ? $twp_mate_home_section->ed_category_meta : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
        $ed_excerpt_content = isset( $twp_mate_home_section->ed_excerpt_content ) ? $twp_mate_home_section->ed_excerpt_content : '' ;
        ?>

        <section class="theme-section theme-slider-section">
            <?php

            $rtl_class_c = 'false';
            if( is_rtl() ){ 
                $rtl_class_c = 'true';
            } ?>

            <div class="mainbanner-jumbotron" data-slick='{"rtl": <?php echo $rtl_class_c; ?>}'>
                
                <?php
                $mate_fullwidth_slider_post_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 10, 'category_name' => $category ) );

                if( $mate_fullwidth_slider_post_query->have_posts() ):

                    $i = 1;
                    while( $mate_fullwidth_slider_post_query->have_posts() ):
                        $mate_fullwidth_slider_post_query->the_post();

                        if( has_post_thumbnail() ){

                            $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                            $url = $thumb['0'];

                        } else {

                            $url = '';

                        } ?>

                        <div class="slick-item">
                            <div class="article-area article-height-full article-heading-large">
                                <div class="article-wrapper">
                                    <div class="article-panel element-overlayed panel-scheme-<?php echo $i; ?>">

                                        <div class="entry-thumbnail">

                                            <?php if( has_post_thumbnail() ){

                                                $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' ); ?>
                                                <a href="<?php the_permalink(); ?>" class="data-bg" data-background="<?php echo esc_url(  $featured_image[0] ); ?>"></a>


                                            <?php } ?>

                                            <div class="background-tint"></div>
                                            <div class="background-gradient"></div>

                                        </div>

                                        <div class="slider-figcaption slider-figcaption-full">
                                            <div class="wrapper">
                                                <div class="wrapper-inner">
                                                    <div class="column column-8 column-sm-12">

                                                        <article id="post-<?php the_ID(); ?>" <?php post_class('theme-slider-article'); ?>>

                                                            <?php if( $ed_category_meta != 'hide-cat' ){ ?>

                                                                <div class="entry-meta">
                                                                    <?php mate_entry_footer( $cats = true, $tags = false, $edits = false, $ed_category_meta, $category ); ?>
                                                                </div>

                                                            <?php } ?>

                                                            <header class="entry-header">
                                                                <h3 class="entry-title">
                                                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                                                        <?php the_title(); ?>
                                                                    </a>
                                                                </h3>
                                                            </header>

                                                            <?php if( $ed_excerpt_content != 'no' ){ ?>

                                                                <div class="entry-content theme-entry-content entry-content-medium">

                                                                    <?php
                                                                    if( has_excerpt() ){

                                                                        the_excerpt();

                                                                    }else{

                                                                        echo esc_html( wp_trim_words( get_the_content(), 35, '...' ) );

                                                                    } ?>
                                                                    
                                                                </div>

                                                            <?php } ?>
                                                                
                                                            <?php if( $ed_author_meta != 'no' || $ed_date_meta != 'no' ){ ?>

                                                                <div class="entry-footer">
                                                                    <div class="entry-meta">

                                                                        <?php
                                                                        if( $ed_author_meta != 'no' ){ mate_posted_by(); }
                                                                        if( $ed_date_meta != 'no' ){ mate_posted_on(); }
                                                                        ?>

                                                                    </div>
                                                                </div>

                                                            <?php } ?>

                                                        </article>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $i++;
                        if( $i == 5 ){
                            $i = 1;
                        }

                    endwhile;

                endif;
                wp_reset_postdata();
                ?>
            </div>

            <div class="slider-pagenav hidden-xs-element hide-no-js">
                <div class="wrapper">
                    <div class="jumbotron-pagenav" data-slick='{"rtl": <?php echo $rtl_class_c; ?>}'>
                        
                        <?php
                        while ($mate_fullwidth_slider_post_query->have_posts()) :
                            $mate_fullwidth_slider_post_query->the_post(); 

                            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' ); ?>

                            <div class="slider-nav-item">
                                <div class="slider-nav-wrapper">
                                        
                                    <?php if( isset( $featured_image[0] ) && $featured_image[0] ){ ?>

                                        <div class="entry-thumbnail">
                                            
                                            <img src="<?php echo esc_url( $featured_image[0] ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>">

                                        </div>

                                    <?php } ?>

                                    <div class="entry-details">
                                        <h4 class="entry-title">
                                            <?php the_title(); ?>
                                        </h4>

                                        <?php if( $ed_date_meta != 'no' ){ ?>

                                            <div class="entry-footer">
                                                <div class="entry-meta">
                                                    <?php mate_posted_on(); ?>
                                                </div>
                                            </div>

                                        <?php } ?>

                                    </div>

                                </div>
                            </div>

                        <?php
                        endwhile; ?>

                    </div>
                </div>
            </div>

        </section>
        <?php
    }
endif;