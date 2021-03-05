<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Create the rating interface.
add_action( 'comment_form_logged_in_after', 'booster_extension_rating_field' );
add_action( 'comment_form_before_fields', 'booster_extension_rating_field' );
function booster_extension_rating_field () { ?>

	<div class="comment-form-ratings">
		<label class="twp-comment-rating-label" for="rating"><?php esc_html_e( 'Rating', 'booster-extension' ); ?><span class="required">*</span></label>

		<span class="comments-rating">
			<span class="rating-container">
				<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
					<input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><label for="rating-<?php echo esc_attr( $i ); ?>"><?php echo absint( $i ); ?></label>
				<?php endfor; ?>
				<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
			</span>
		</span>
	</div>
	<?php
}

//Save the rating submitted by the user.
add_action( 'comment_post', 'booster_extension_save_comment_rating' );
function booster_extension_save_comment_rating( $comment_id ) {

	global $post;
	if ( ( isset( $_POST['rating'] ) ) && $_POST['rating'] ){
		$rating = intval( $_POST['rating'] );
		add_comment_meta( $comment_id, 'rating', $rating );

	    $comment_content = get_comment( $comment_id ); 
	    $comment_post_id = $comment_content->comment_post_ID ;

	    $array_reviews = array();
		$array_reviews = get_option('twp_post_rating');
		if( array_key_exists( $comment_post_id,$array_reviews) ){
			$post_review = absint( $array_reviews[$comment_post_id] )+absint( $_POST['rating'] );
			$array_reviews[ $comment_post_id ] = absint( $post_review );
		}else{
			$array_reviews[ $comment_post_id ] = absint( $_POST['rating'] );
		}
		arsort($array_reviews);
		$array_reviews = array_slice($array_reviews, 0, 100, true);
		update_option('twp_post_rating',$array_reviews);
	}
}

function booster_extension_rating_count_list(){

	return get_option('twp_post_rating');
	
}

function booster_extension_display_rating(){

	if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$rating = ( $rating * 100 ) / 5 ?>
		<div class="twp-star-rating">
			<span style="width:<?php echo absint($rating); ?>%"></span>
		</div>
	<?php
	}
	
}

//Get the average rating of a post.
function booster_extension_get_average_ratings( $id ) {

	$comments = get_approved_comments( $id );

	if ( $comments ) {
		$i = 0;
		$total = 0;
		foreach( $comments as $comment ){
			$rate = get_comment_meta( $comment->comment_ID, 'rating', true );
			if( isset( $rate ) && '' !== $rate ) {
				$i++;
				$total += absint( $rate );
			}
		}

		if ( 0 === $i ) {
			return false;
		} else {
			return round( $total / $i, 1 );
		}
	} else {
		return false;
	}

}

function booster_extension_comment_cb( $comment, $args, $depth ){

	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body">
			<div id="comment-<?php comment_ID(); ?>">

				<footer class="comment-meta">

					<div class="comment-author vcard">
						<?php echo get_avatar($comment,$size='32',$default='<path_to_url>' ); ?>
						<?php booster_extension_display_rating(); ?>
						<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
					</div>

					<?php if ($comment->comment_approved == '0') : ?>
						<em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.','booster-extension'); ?></em>
						<br />
					<?php endif; ?>

					<div class="comment-metadata commentmetadata">
						<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
							<?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?>
						</a>
						<?php edit_comment_link(__('(Edit)'),'  ','') ?>
					</div>

				</footer>

				<div class="comment-content">
					<?php comment_text() ?>
				</div>

				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>

			</div>
		</article>
	</li>

<?php
}

add_action( 'add_meta_boxes_comment', 'booster_extension_comment_rating_meta' );
function booster_extension_comment_rating_meta() {
    add_meta_box( 
    	'title',
    	esc_html__( 'Rating','booster-extension' ),
    	'booster_extension_comment_rating_meta_add',
    	'comment',
    	'normal',
    	'high'
    );
}

function booster_extension_comment_rating_meta_add ( $comment ) {
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    wp_nonce_field( 'booster_extension_comment_rating_nonce', 'booster_extension_comment_rating_nonce', false );
    ?>
    <div class="comment-form-ratings">
	    <label class="twp-comment-rating-label" for="rating"><?php esc_html_e( 'Rating', 'booster-extension' ); ?><span class="required">*</span></label>

		<span class="comments-rating">
			<span class="rating-container">
				<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
					<input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><label for="rating-<?php echo esc_attr( $i ); ?>"><?php echo absint( $i ); ?></label>
				<?php endfor; ?>
				<input type="radio" id="rating-0" class="star-cb-clear" name="rating" value="0" /><label for="rating-0">0</label>
			</span>
		</span>
	</div>
    <?php
}

add_action( 'edit_comment', 'booster_extension_comment_rating_meta_update' );

function booster_extension_comment_rating_meta_update( $comment_id ) {

    if( ! isset( $_POST['booster_extension_comment_rating_nonce'] ) || ! wp_verify_nonce( $_POST['booster_extension_comment_rating_nonce'], 'booster_extension_comment_rating_nonce' ) ) return;

	  if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != ’) ) :
	  $rating = absint($_POST['rating']);
	  update_comment_meta( $comment_id, 'rating', $rating );
	  else :
	  delete_comment_meta( $comment_id, 'phone');
	  endif;

}