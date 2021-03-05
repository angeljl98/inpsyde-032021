<?php
/**
 * Grid Posts
 *
 * @package Mate
 */

if( !function_exists('mate_grid_posts') ):

    function mate_grid_posts( $twp_mate_home_section ){

    	$post_per_page = isset( $twp_mate_home_section->column_number ) ? $twp_mate_home_section->column_number : '' ;
    	$category = isset( $twp_mate_home_section->section_post_category ) ? $twp_mate_home_section->section_post_category : '' ;
    	$section_title = isset( $twp_mate_home_section->section_title ) ? $twp_mate_home_section->section_title : '' ;
    	$section_sub_title = isset( $twp_mate_home_section->section_sub_title ) ? $twp_mate_home_section->section_sub_title : '' ;
    	$ed_category_meta = isset( $twp_mate_home_section->ed_category_meta ) ? $twp_mate_home_section->ed_category_meta : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
		$grid_posts_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => absint( $post_per_page ),'post__not_in' => get_option("sticky_posts"), 'category_name' => $category ) );
		$ed_excerpt_content = isset( $twp_mate_home_section->ed_excerpt_content ) ? $twp_mate_home_section->ed_excerpt_content : '' ;

		if( $post_per_page == 3 ){

		    $column_class = 'column-4';

		}elseif( $post_per_page == 2 ){

		    $column_class = 'column-6';

		}else{

		    $column_class = 'column-3';

		}

		if( $grid_posts_query->have_posts() ): ?>


		    <section class="theme-section theme-grid-section">
		        <div class="wrapper">

		        	<?php if( $section_title || $section_sub_title ){ ?>

			            <div class="wrapper-inner">
			                <div class="column column-12">
			                    <div class="theme-section-heading">

			                    	<?php if( $section_title ){ ?>

				                        <h2 class="theme-section-title"><?php echo esc_html( $section_title ); ?></h2>

				                    <?php }

				                    if( $section_sub_title ){ ?>

				                        <p class="theme-section-description"><?php echo esc_html ( $section_sub_title ); ?></p>
				                        
				                    <?php } ?>

			                    </div>
			                </div>
			            </div>

			        <?php } ?>

		            <div class="wrapper-inner">

		                <?php
		                $i = 1;
		                while( $grid_posts_query->have_posts() ):
		                    $grid_posts_query->the_post(); ?>

	                        <div class="column column-sm-6 column-xs-12 <?php echo $column_class; ?>">
	                            <article id="post-<?php the_ID(); ?>" <?php post_class('theme-grid-article'); ?>>
	                                <div class="article-area article-height-medium article-heading-small">
	                                    <div class="article-wrapper">
	                                        <div class="article-panel panel-scheme-<?php echo $i; ?>">
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
		    </section>


		    <?php
		    wp_reset_postdata();

		endif;

	}

endif;