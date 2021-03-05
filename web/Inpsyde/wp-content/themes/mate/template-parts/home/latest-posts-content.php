<?php
/**
 * Latest Posts Content
 *
 * @package Mate
 */

if( !function_exists('mate_latest_posts_content') ):

    function mate_latest_posts_content( $ed_category_meta = 'yes', $author = 'yes', $date = 'yes', $excerpt = 'yes',$c_cat = false ){ ?>

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

		    <?php if( $ed_category_meta != 'hide-cat' ){ ?>

                <div class="entry-meta">
                    <?php mate_entry_footer( $cats = true, $tags = false, $edits = false, $ed_category_meta, $c_cat ); ?>
                </div>

            <?php } ?>

		    <header class="entry-header">
		        <h3 class="entry-title">
		            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		        </h3>
		    </header>

		    <?php if( $excerpt != 'no' ){ ?>

			    <div class="entry-content theme-entry-content entry-content-medium">

			        <?php
			        if( has_excerpt() ){

			            the_excerpt();

			        }else{

			            echo esc_html( wp_trim_words( get_the_content(), 35, '...' ) );

			        } ?>
			        
			    </div>

			<?php } ?>
		
		     <?php if( 'post' === get_post_type() && ( $author != 'no' || $date != 'no' ) ){ ?>

                <div class="entry-footer">
                    <div class="entry-meta">

                        <?php
                        if( $author != 'no' ){ mate_posted_by(); }
                        if( $date != 'no' ){ mate_posted_on(); }
                        ?>
                        
                    </div>
                </div>

            <?php } ?>

		</div>

    <?php
	}

endif;