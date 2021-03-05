<?php
/**
 *
 * Pagination Functions
 *
 * @package Mate
 */

if( !function_exists('mate_archive_pagination_x') ):

	// Archive Page Navigation
	function mate_archive_pagination_x(){

		// Global Query
	    if( is_front_page() ){

	    	$posts_per_page = absint( get_option('posts_per_page') );
	        $paged_c = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
	        $posts_args = array(
	            'posts_per_page'        => $posts_per_page,
	            'paged'                 => $paged_c,
	        );
	        $posts_qry = new WP_Query( $posts_args );
	        $max = $posts_qry->max_num_pages;

	    }else{

	        global $wp_query;
	        $max = $wp_query->max_num_pages;
	        $paged_c = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;

	    }

		$mate_default = mate_get_default_theme_options();
		$mate_pagination_layout = esc_html( get_theme_mod( 'mate_pagination_layout',$mate_default['mate_pagination_layout'] ) );
		$mate_pagination_load_more = esc_html__('Load More Posts','mate');
		$mate_pagination_no_more_posts = esc_html__('No More Posts','mate');

		if( $mate_pagination_layout == 'next-prev' ){

			if( is_front_page() && is_page() ){

	            $paged_c = ( get_query_var( 'page' ) ) ? absint( get_query_var( 'page' ) ) : 1;
	            $latest_post_query = new WP_Query( array( 'post_type'=>'post', 'paged'=>$paged_c ) );?>
	            
	            <nav class="navigation posts-navigation" role="navigation" aria-label="Posts">
	                <div class="nav-links">
	                    
	                    <div class="nav-previous">
	                    	<?php echo wp_kses_post( get_next_posts_link( esc_html__( 'Older posts', 'mate' ), $latest_post_query->max_num_pages ) ); ?>
	                    </div>
	                    
	                    <div class="nav-next">
	                    	<?php echo wp_kses_post( get_previous_posts_link( esc_html__( 'Newer posts', 'mate' ) ) ); ?>
	                    </div>

	                </div>
	            </nav>

	        <?php
	        }else{

	            the_posts_navigation();

	        }

		}elseif( $mate_pagination_layout == 'load-more' || $mate_pagination_layout == 'auto-load' ){ ?>

			<div class="theme-ajax-post-load hide-no-js">

				<div  style="display: none;" class="theme-loaded-content"></div>
				

				<?php
				if( $max > 1 ){ ?>

					<button class="theme-loading-button theme-loading-style" href="javascript:void(0)">
						<span style="display: none;" class="theme-loading-status"></span>
						<span class="loading-text"><?php echo esc_html( $mate_pagination_load_more ); ?></span>
					</button>

				<?php
				}else{ ?>

					<button class="theme-loading-button theme-loading-style theme-no-posts" href="javascript:void(0)">
						<span style="display: none;" class="theme-loading-status"></span>
						<span class="loading-text"><?php echo esc_html( $mate_pagination_load_more ); ?></span>
					</button>

				<?php } ?>

			</div>

		<?php
		}else{

			the_posts_pagination();

		}
			
	}

endif;
add_action('mate_archive_pagination','mate_archive_pagination_x',20);


add_action('wp_ajax_mate_single_infinity', 'mate_single_infinity_callback');
add_action('wp_ajax_nopriv_mate_single_infinity', 'mate_single_infinity_callback');

// Recommendec Post Ajax Call Function.
function mate_single_infinity_callback() {

    if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mate_ajax_nonce' ) ) {

        $postid = sanitize_text_field( wp_unslash( $_POST['postid'] ) );
        $mate_default = mate_get_default_theme_options();
        $post_single_next_posts = new WP_Query( array( 'post_type' => 'post','post_status' => 'publish','posts_per_page' => 1, 'post__in' => array( absint( $postid ) ) ) );

        if ( $post_single_next_posts->have_posts() ) :
            while ( $post_single_next_posts->have_posts() ) :
                $post_single_next_posts->the_post();
                ob_start(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('after-load-ajax-'.get_the_ID() ); ?>> 

                	<?php if( has_post_thumbnail() ){ ?>

						<div class="entry-thumbnail">

							<?php
                            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' ); ?>
                            <img src="<?php echo esc_url( $featured_image[0] ); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>">

						</div>

					<?php } ?>

					<header class="entry-header">

						<h2 class="entry-title">

				            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>

				        </h2>

					</header>

					<div class="entry-meta">

						<?php
						mate_posted_by();
						mate_posted_on();
						mate_entry_footer( $cats = true, $tags = false, $edits = false );
						?>

					</div>
					
					<div class="entry-details">

						<div class="entry-content">

							<?php

							the_content( sprintf(
								/* translators: %s: Name of current post. */
								wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'mate' ), array( 'span' => array( 'class' => array() ) ) ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							) );


							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'mate' ),
								'after'  => '</div>',
							) ); ?>

						</div>

						<?php if ( is_singular() && 'post' === get_post_type() ) { ?>

							<div class="entry-footer">

								<?php mate_entry_footer( $cats = false, $tags = true, $edits = true ); ?>

							</div>

						<?php } ?>

					</div>

				</article>

                <?php
                $next_post_id = '';
                $next_post = get_next_post();
                
                if( isset( $next_post->ID ) ){ 
                    $next_post_id = $next_post->ID;
                }

                $output['postid'][] = $next_post_id;
                $output['content'][] = ob_get_clean();

            endwhile;

            wp_send_json_success($output);
            wp_reset_postdata();

        endif;
    }
    wp_die();
}



if( !function_exists('mate_post_floating_nav') ):

    function mate_post_floating_nav(){

        $mate_default = mate_get_default_theme_options();
        $ed_floating_next_previous_nav = get_theme_mod( 'ed_floating_next_previous_nav',$mate_default['ed_floating_next_previous_nav'] );

        if( 'post' === get_post_type() && $ed_floating_next_previous_nav ){

            $next_post = get_next_post();
            $prev_post = get_previous_post();

            if( isset( $prev_post->ID ) ){

                $prev_link = get_permalink( $prev_post->ID );?>

                <div class="floating-post-navigation floating-navigation-prev">
                    <?php if( get_the_post_thumbnail( $prev_post->ID,'medium' ) ){ ?>
                            <?php echo wp_kses_post( get_the_post_thumbnail( $prev_post->ID,'medium' ) ); ?>
                    <?php } ?>
                    <a href="<?php echo esc_url( $prev_link ); ?>">
                        <span class="floating-navigation-label"><?php echo esc_html__('Previous post', 'mate'); ?></span>
                        <span class="floating-navigation-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                    </a>
                </div>

            <?php }

            if( isset( $next_post->ID ) ){

                $next_link = get_permalink( $next_post->ID );?>

                <div class="floating-post-navigation floating-navigation-next">
                    <?php if( get_the_post_thumbnail( $next_post->ID,'medium' ) ){ ?>
                        <?php echo wp_kses_post( get_the_post_thumbnail( $next_post->ID,'medium' ) ); ?>
                    <?php } ?>
                    <a href="<?php echo esc_url( $next_link ); ?>">
                        <span class="floating-navigation-label"><?php echo esc_html__('Next post', 'mate'); ?></span>
                        <span class="floating-navigation-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                    </a>
                </div>

            <?php
            }

        }

    }

endif;

add_action( 'mate_navigation_action','mate_post_floating_nav',10 );

if( !function_exists('mate_single_post_navigation') ):

    function mate_single_post_navigation(){

        $mate_default = mate_get_default_theme_options();
        $twp_navigation_type = esc_attr( get_post_meta( get_the_ID(), 'twp_disable_ajax_load_next_post', true ) );
        $current_id = '';
        $article_wrap_class = '';
        global $post;
        $current_id = $post->ID;
        if( $twp_navigation_type == '' || $twp_navigation_type == 'global-layout' ){
            $twp_navigation_type = get_theme_mod('twp_navigation_type', $mate_default['twp_navigation_type']);
        }

        if( $twp_navigation_type != 'no-navigation' && 'post' === get_post_type() ){

            if( $twp_navigation_type == 'norma-navigation' ){ ?>

                <div class="navigation-wrapper">
                    <?php
                    // Previous/next post navigation.
                    the_post_navigation(array(
                        'prev_text' => '<span class="arrow" aria-hidden="true">' . mate_the_theme_svg('arrow-left',$return = true ) . '</span><span class="screen-reader-text">' . __('Previous post:', 'mate') . '</span><span class="post-title">%title</span>',
                        'next_text' => '<span class="arrow" aria-hidden="true">' . mate_the_theme_svg('arrow-right',$return = true ) . '</span><span class="screen-reader-text">' . __('Next post:', 'mate') . '</span><span class="post-title">%title</span>',
                    )); ?>
                </div>
                <?php

            }else{

                $next_post = get_next_post();
                if( isset( $next_post->ID ) ){

                    $next_post_id = $next_post->ID;
                    echo '<div loop-count="1" next-post="' . absint( $next_post_id ) . '" class="twp-single-infinity"></div>';

                }
            }

        }

    }

endif;

add_action( 'mate_navigation_action','mate_single_post_navigation',30 );