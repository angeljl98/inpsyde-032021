<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Theme Powerkit
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div class="booster-block booster-ratings-block">

    <h3 class="twp-average-title"><?php esc_html_e( 'Average Rating', 'booster-extension' ); ?></h3>

    <div class="booster-average-rating">

        <div class="booster-review-bar">
            <?php
            $comment_list = get_approved_comments( get_the_ID() );
            $rating_list = array();
            $review_count = 0;
            foreach( $comment_list as $comment ){
                $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
                if( isset( $rate ) && '' !== $rate ) {
                    $rating_list[] = $rate;
                    $review_count++;
                }
            }

            $rating_array = array();
            if( $rating_list ){
                $rating_array = array_count_values($rating_list);
            }
            $review_array = array(5,4,3,2,1);
            foreach ( $review_array as $review ) {
                echo '<div class="twp-bar-rating" >';
                    echo '<div class="twp-star-text" >'.$review.esc_html(' Star','booster-extension').'</div>';
                    if( isset( $rating_array[$review] ) ){
                        $percent = $rating_array[$review]/$review_count;
                        $percent_friendly = number_format( $percent * 100, 0 ).'%';
                        echo '<div class="individual-rating-bar"><div class="individual-bar-bg"><span style="width:'.esc_attr( $percent_friendly ).'" class="individual-bar-percent" >';
                        echo '</span></div></div>';
                        echo '<div class="individual-rating-percent" >';
                            echo esc_html( $percent_friendly );
                        echo '</div>';
                    }else{
                        echo '<div class="individual-rating-bar"><div class="individual-bar-bg"><span style="width:0%" class="individual-bar-percent" >';
                        echo '</span></div></div>';
                        echo '<div class="twp-rating-percent" >';
                            esc_html_e('0%','booster-extension');
                        echo '</div>';
                    }
                echo '</div>';
            } ?>
        </div>

        <div class="booster-review-info">

            <?php
            $average_rating = booster_extension_get_average_ratings( get_the_ID() );
            if ( $average_rating > 0 ){ ?>

                <div class="booster-average-percent"><?php echo number_format( $average_rating, 2 ); ?></div>

            <?php } ?>

            <div class="twp-post-review">

                <?php if( $average_rating ){

                    $new_width = ( $average_rating * 100 ) / 5; ?>

                    <div class="average-post-rating" role="img" ><span style="width:<?php echo esc_attr( $new_width ); ?>%"></span></div>

                <?php } ?>

                <a href="javascript:void(0)" class="twp-review-link" rel="nofollow"><?php esc_html_e( '(Add your review)', 'booster-extension' ); ?></a>

            </div>

        </div>
        
    </div>

</div>

<div id="comments" class="comments-area">
	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$booster_extension_comment_count = get_comments_number();
			if ( '1' === $booster_extension_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'booster-extension' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf( // WPCS: XSS OK.
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $booster_extension_comment_count, 'comments title', 'booster-extension' ) ),
					number_format_i18n( $booster_extension_comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>
		<?php the_comments_navigation(); ?>
		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'callback'   => 'booster_extension_comment_cb',
			) );
			?>
		</ol>
		<?php
		the_comments_navigation();
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'booster-extension' ); ?></p>
			<?php
		endif;
	endif; // Check for have_comments().
	comment_form();
	?>
</div>
