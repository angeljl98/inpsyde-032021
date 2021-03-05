<?php
/**
 * Category
 *
 * @package Mate
 */

if( !function_exists('mate_category_section') ):

    function mate_category_section( $twp_mate_home_section ){

    	$twp_mate_home_section_1 = array();

    	foreach( $twp_mate_home_section as $key => $value ){ $twp_mate_home_section_1[$key] =  $value; }

	    $section_title = isset( $twp_mate_home_section->section_title ) ? $twp_mate_home_section->section_title : '' ;
	    $section_sub_title = isset( $twp_mate_home_section->section_sub_title ) ? $twp_mate_home_section->section_sub_title : '' ;
	    $category_article_title = isset( $twp_mate_home_section->category_article_title ) ? $twp_mate_home_section->category_article_title : '' ;
	    $ed_category_meta = isset( $twp_mate_home_section->ed_category_meta ) ? $twp_mate_home_section->ed_category_meta : '' ;
        $ed_author_meta = isset( $twp_mate_home_section->ed_author_meta ) ? $twp_mate_home_section->ed_author_meta : '' ;
        $ed_date_meta = isset( $twp_mate_home_section->ed_date_meta ) ? $twp_mate_home_section->ed_date_meta : '' ;
        $background_color = isset( $twp_mate_home_section->background_color ) ? $twp_mate_home_section->background_color : '' ;
        $section_category_1 = isset( $twp_mate_home_section->section_category_1 ) ? $twp_mate_home_section->section_category_1 : '' ;
        $section_category_2 = isset( $twp_mate_home_section->section_category_2 ) ? $twp_mate_home_section->section_category_2 : '' ;
        $section_category_3 = isset( $twp_mate_home_section->section_category_3 ) ? $twp_mate_home_section->section_category_3 : '' ;
        $section_category_4 = isset( $twp_mate_home_section->section_category_4 ) ? $twp_mate_home_section->section_category_4 : '' ;

        if( $section_category_1 || $section_category_2 || $section_category_3 || $section_category_4 ){ ?>

			<section class="theme-section theme-categories-section">
			    <div class="wrapper">

			    	<?php if( $section_title ||  $section_sub_title ){ ?>

				        <div class="wrapper-inner">
				            <div class="column column-12">
				                <div class="theme-section-heading">

				                	<?php if( $section_title ){ ?>
				                        <h2 class="theme-section-title"><?php echo esc_html ( $section_title ); ?></h2>
				                    <?php } ?>

			                        <?php if( $section_sub_title ){ ?>
				                        <p class="theme-section-description"><?php echo esc_html ( $section_sub_title ); ?></p>
				                    <?php } ?>

				                </div>
				            </div>
				        </div>

				    <?php } ?>


			        <div class="wrapper-inner">

			        	<?php for( $i =1; $i <= 4; $i++ ){

			        		$section_category = isset( $twp_mate_home_section_1['section_category_'.$i] ) ? $twp_mate_home_section_1['section_category_'.$i] : '' ;
			        		

				        	if( $section_category ){

				        		$catObj = get_category_by_slug( $section_category );
				        		$cat_name = $catObj->name;
				        		$cat_link = get_category_link( $catObj->term_id );
					        	$featured_image = get_term_meta( $catObj->term_id, 'twp-term-featured-image', true );
					        	$cat_posts_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => 1,'post__not_in' => get_option("sticky_posts"), 'category_name' => $section_category ) ); ?>

					            <div class="column column-3 column-sm-6 column-xs-12">
					                <article id="post-<?php the_ID(); ?>" <?php post_class('theme-category-article'); ?>>
					                    <div class="article-area article-height-small article-heading-small">
					                        <div class="article-wrapper">
					                            <div class="article-panel panel-scheme-<?php echo $i; ?>">

					                                <div class="entry-thumbnail">
					                                	
					                                    <?php if( $featured_image ){ ?>
					                                        <a href="<?php the_permalink(); ?>" class="data-bg" data-background="<?php echo esc_url(  $featured_image ); ?>"></a>
					                                    <?php } ?>

					                                    <div class="background-tint"></div>
					                                    <div class="background-gradient"></div>

					                                </div>

					                                <div class="entry-details">

					                                    <header class="entry-header">
					                                        <h3 class="entry-title">
					                                            <a href="<?php echo esc_url( $cat_link ); ?>" rel="bookmark">
					                                                <?php echo esc_html( $cat_name ); ?>
					                                            </a>
					                                        </h3>
					                                    </header>

					                                    <?php
					                                    if( $cat_posts_query->have_posts() ):

					                                    	while( $cat_posts_query->have_posts() ){
					                                    		$cat_posts_query->the_post(); ?>

							                                    <div class="entry-content">

							                                    	<?php if( $category_article_title ){ ?>
								                                        <span><?php echo esc_html( $category_article_title ); ?></span>
								                                    <?php } ?>

							                                        <h4 class="article-title">
							                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							                                        </h4>

							                                    </div>

						                                        <?php if( $ed_author_meta != 'no' || $ed_date_meta != 'no' ){ ?>

	                                                                <div class="entry-footer">
	                                                                    <div class="entry-meta">
	                                                                        <?php
	                                                                        if( $ed_author_meta != 'no' ){ mate_posted_by(); }
	                                                                        if( $ed_date_meta != 'no' ){ mate_posted_on(); }
	                                                                        ?>
	                                                                    </div>
	                                                                </div>

	                                                            <?php
	                                                        	}

					                                    	}
					                                    	
					                                    	wp_reset_postdata();

						                                endif; ?>

					                                </div>
					                        </div>
					                    </div>
					                </article>
					            </div>

				        	<?php }

				        } ?>

			        </div>
			    </div>
			</section>

		<?php
		}

	}

endif;