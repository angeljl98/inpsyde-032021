<?php
/**
 * Mix Grid Posts
 *
 * @package Mate
 */

if( !function_exists('mate_latest_posts') ):

    function mate_latest_posts( $twp_mate_home_section ){

    	$ed_category_meta_1 = isset( $twp_mate_home_section->ed_category_meta_1 ) ? $twp_mate_home_section->ed_category_meta_1 : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
        $ed_excerpt_content = isset( $twp_mate_home_section->ed_excerpt_content ) ? $twp_mate_home_section->ed_excerpt_content : '' ;

    	$class_div_gen = mate_image_size();
    	$primary_class = mate_get_sidebar_primary_class(); ?>

	    <section class="theme-section theme-blog-section">
	        <div class="wrapper">
	            <div class="wrapper-inner">

	                <div id="primary" class="content-area <?php echo esc_attr( $primary_class ); ?>">
	                    <main id="site-content" role="main">
	                        
	                        <?php

	                        if( have_posts() ): ?>

	                            <div class="article-wraper">
	                                <?php
	                                $i = 1;
	                                while (have_posts()) :
	                                    the_post();

	                                    $image_size = 'full';
	                                    if( $i == 1 || $i == 2 ){

	                                        $pannel_class = 1;

	                                    }elseif( $i == 3 || $i == 4 ){

	                                        $pannel_class = 2;

	                                    }elseif( $i == 5 || $i == 6 ){

	                                        $pannel_class = 3;

	                                    }else{

	                                        $pannel_class = 4;

	                                    }
	                                    if( $class_div_gen == 'full' || $class_div_gen == 'large' ){
	                                    	
	                                    	$class_div = 'large';

	                                    }else{
	                                    	
	                                    	$class_div = 'big';

	                                    } ?>

	                                    <article id="post-<?php the_ID(); ?>" <?php post_class('theme-article-panel'); ?>>
	                                        <div class="article-area article-heading-big article-height-<?php echo $class_div; ?>">
	                                            <div class="article-wrapper">
	                                                <div class="article-panel element-overlayed panel-scheme-<?php echo $pannel_class; ?>">

	                                                    <?php
	                                                    if( is_archive() || is_search() || ( !is_front_page() && is_home() ) ){

	                                                    	mate_latest_posts_content();

	                                                    }else{

	                                                    	mate_latest_posts_content( $ed_category_meta_1, $ed_author_meta, $ed_date_meta, $ed_excerpt_content );

	                                                    } ?>

	                                                </div>
	                                            </div>
	                                        </div>
	                                    </article>

	                                
	                                    <?php
	                                    
	                                    $i++;
	                                    if( $i == 9 ){
	                                        $i = 1;
	                                    }

	                                endwhile; ?>
	                            </div>

	                            <?php
	                            if( is_search() ){
	                            	
	                            	the_posts_pagination();

	                            }else{
	                            	
	                            	do_action('mate_archive_pagination');

	                            }

	                            wp_reset_postdata();
	                            
	                        else :

	                            get_template_part('template-parts/content', 'none');

	                        endif; ?>
	                    </main><!-- #main -->
	                </div>

	                <?php mate_get_sidebar(); ?>

	            </div>
	        </div>
	    </section>

    <?php
	}

endif;