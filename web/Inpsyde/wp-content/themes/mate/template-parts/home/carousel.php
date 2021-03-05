<?php
/**
 * Carousel Posts
 *
 * @package Mate
 */
if( !function_exists('mate_carousel_posts') ):
	
    function mate_carousel_posts( $twp_mate_home_section ){
    	
    	$category = isset( $twp_mate_home_section->section_post_category ) ? $twp_mate_home_section->section_post_category : '' ;
    	$section_title = isset( $twp_mate_home_section->section_title ) ? $twp_mate_home_section->section_title : '' ;
    	$section_sub_title = isset( $twp_mate_home_section->section_sub_title ) ? $twp_mate_home_section->section_sub_title : '' ;
    	$ed_slider_navigation = isset( $twp_mate_home_section->ed_slider_navigation ) ? $twp_mate_home_section->ed_slider_navigation : '' ;
    	$ed_slider_pagination = isset( $twp_mate_home_section->ed_slider_pagination ) ? $twp_mate_home_section->ed_slider_pagination : '' ;
    	$ed_slider_autoplay = isset( $twp_mate_home_section->ed_slider_autoplay ) ? $twp_mate_home_section->ed_slider_autoplay : '' ;
    	$ed_category_meta = isset( $twp_mate_home_section->ed_category_meta ) ? $twp_mate_home_section->ed_category_meta : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
        $ed_excerpt_content = isset( $twp_mate_home_section->ed_excerpt_content ) ? $twp_mate_home_section->ed_excerpt_content : '' ;

    	if ( $ed_slider_autoplay != 'no' ) {
            $autoplay = 'true';
        }else{
            $autoplay = 'false';
        }
        if( $ed_slider_pagination != 'no' ) {
            $dots = 'true';
        }else {
            $dots = 'false';
        }
        if( $ed_slider_navigation != 'no' ) {
            $arrows = 'true';
        }else {
            $arrows = 'false';
        }
        if( is_rtl() ) {
            $rtl = 'true';
        }else{
            $rtl = 'false';
        }
		$carousel_posts_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 8,'post__not_in' => get_option("sticky_posts"), 'category_name' => $category ) );
	    if( $carousel_posts_query->have_posts() ): ?>
	        
	        <section class="theme-section theme-carousel-section">
	            <div class="wrapper">

	                <?php if( $section_title || $section_sub_title ){ ?>

			            <div class="wrapper-inner">
			                <div class="column column-12">
			                    <div class="theme-section-heading">

			                    	<?php if( $section_title ){ ?>
				                        <h2 class="theme-section-title">
                                            <?php echo esc_html( $section_title ); ?>
                                        </h2>
				                    <?php }

				                    if( $section_sub_title ){ ?>
				                        <p class="theme-section-description">
                                            <?php echo esc_html ( $section_sub_title ); ?>
                                        </p>
				                    <?php } ?>

			                    </div>
			                </div>
			            </div>

			        <?php } ?>

	                <div class="wrapper-inner">
	                    <div class="column column-12">

	                       <div class="theme-carousel" data-slick='{"autoplay": <?php echo esc_attr( $autoplay ); ?>, "dots": <?php echo esc_attr( $dots ); ?>, "arrows": <?php echo esc_attr( $arrows ); ?>, "rtl": <?php echo esc_attr( $rtl ); ?>}'>
	                        
	                        <?php
	                        $i = 1;
	                        while( $carousel_posts_query->have_posts() ):
	                            $carousel_posts_query->the_post(); ?>
	                            
                               <div class="theme-carousel-item">

                                    <article <?php post_class('theme-carousel-article'); ?>>
                                        <div class="article-area article-heading-medium article-height-big">
                                            <div class="article-wrapper">
                                                <div class="article-panel element-overlayed panel-scheme-<?php echo $i; ?>">

                                                    <?php mate_latest_posts_content( $ed_category_meta, $ed_author_meta, $ed_date_meta, $ed_excerpt_content,$category ); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                
                                    <?php
                                    $i++;
                                    if( $i == 5 ){
                                        $i = 1;
                                    } ?>

                               </div>

	                        <?php endwhile; ?>

	                       </div>

	                    </div>
	                </div>

	            </div>
	        </section>

	        <?php
	        wp_reset_postdata();
	    endif;
	}
endif;