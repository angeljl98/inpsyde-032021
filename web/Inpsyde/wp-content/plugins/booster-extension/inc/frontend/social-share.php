<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Social Share
*
* @package Booster Extension
*/

if( ! function_exists( 'booster_extension_social_share_display' ) ):

    function booster_extension_social_share_display($layout){

	    $twp_be_settings = get_option( 'twp_be_options_settings' );
		$twp_be_social_share = $twp_be_settings['social_share'];
		$twp_be_open_link_type = esc_html( $twp_be_settings['twp_be_open_link_type'] );
		$social_share_email_subject = esc_html( $twp_be_settings['social_share_email_subject'] );
		$social_share_email_body = esc_html( $twp_be_settings['social_share_email_body'] );
		$social_share_ed_socila_counter = esc_html( $twp_be_settings['social_share_ed_socila_counter'] );
		$social_share_title = esc_html( $twp_be_settings['social_share_title'] );
		$url = esc_url( get_permalink() );
		$title = esc_html( get_the_title() ); ?>


		<div class="<?php if( $layout == 'layout-2' ){ ?> twp-social-share-layout-2 <?php }else{ ?> twp-social-share <?php } ?> booster-clear">

			<?php
			if( $layout == 'layout-2' ){
		    	echo '<a class="twp-toggle-share" href="javascript:void(0)"><span>'.esc_html__('Share','booster-extension').'</span><i class="booster-icon twp-share"></i></a>';
		    }else{ ?>
			    <header class="twp-plugin-title twp-share-title">
			        <h2><?php echo esc_html( $social_share_title ); ?></h2>
			    </header>
			<?php } ?>

		    <div class="twp-share-container">
				<?php
				if( $twp_be_social_share ){

					foreach( $twp_be_social_share as $key => $value ){
						
						switch ( $key )
					    {
					        case 'facebook':
						        if( $value ){
						        	echo '<div class="twp-social-icons twp-social-facebook">';
						        		$url = preg_replace( '/https:/i', 'http:', $url );
							        	$link =  esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . esc_url( $url ) ); ?>
										<a class="twp-icon-holder" rel="nofollow" <?php if( $twp_be_open_link_type == 'new-tab' ){ ?>target="_blank" <?php } ?> <?php if( $twp_be_open_link_type == 'new-window' ){ ?>onclick="twp_be_popup_new_window( event,'<?php echo esc_url( $link ); ?>'); " <?php } ?> href="<?php echo esc_url( $link ); ?>" >
			                                <?php
			                                $count = 0;
			                                if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-facebook_fill"></i>';
			                                }else{
			                                	
				                                if( $social_share_ed_socila_counter ){
				                                	$social_share_fb_app_id = esc_html( $twp_be_settings['social_share_fb_app_id'] );
													$social_share_fb_secret_key = esc_html( $twp_be_settings['social_share_fb_secret_key'] );
				                                	
				                                	$catch = true;
				                                	if( $catch ){
					                                	$fb_transient = 'fb_' . md5( $url );
										                $fb_transient_count = get_transient( $fb_transient );
										                if ( false === $fb_transient_count ) {
						                                    $access_token = $social_share_fb_app_id.'|'.$social_share_fb_secret_key;
						                                    $response = wp_remote_get( add_query_arg( array(
						                                        'id' => urlencode( $url ),
						                                        'access_token' => $access_token,
						                                        'fields' => 'engagement'
						                                    ), 'https://graph.facebook.com/v3.0/' ) );
						                                    if( !is_wp_error( $response ) ){
							                                    $body = json_decode( $response['body'] );
							                                    if( !empty( $body->engagement->share_count ) ){
								                                    $count = intval( $body->engagement->share_count );
								                                }
								                            }
										                	set_transient( $fb_transient, $count, 10 * HOUR_IN_SECONDS );
										                }else{
										                	$count = $fb_transient_count;
										                }
										            }else{
					                                    $access_token = $social_share_fb_app_id.'|'.$social_share_fb_secret_key;
					                                    $response = wp_remote_get( add_query_arg( array(
					                                        'id' => urlencode( $url ),
					                                        'access_token' => $access_token,
					                                        'fields' => 'engagement'
					                                    ), 'https://graph.facebook.com/v3.0/' ) );
					                                    $body = json_decode( $response['body'] );
					                                    if( !empty( $body->engagement->share_count ) ){
						                                    $count = intval( $body->engagement->share_count );
						                                }
					                                }
					                                
				                                }
				                                echo '<span class="twp-social-count">';
			                                    if( $count <= 0 ){
				                                    echo '<i class="booster-icon twp-plus"></i>';
				                                }
			                                    if( $count >= 1 ){
			                                    	echo absint( $count );
			                                    }
			                                    echo "</span>";
				                                 ?>
												<span class="twp-share-media">
													<span class="twp-share-label">
				                                        <i class="booster-icon twp-facebook_fill"></i>
				                                        <span class="twp-label-title">
				                                            <?php esc_html_e('Facebook','booster-extension'); ?>
				                                        </span>
				                                    </span>
												</span>
											<?php } ?>
										</a>
									<?php
									echo "</div>";
						        }
					        
					        break;
					        case 'twitter':
					        
						        if( $value ){
						        	
						        	echo '<div class="twp-social-icons twp-social-twitter">';
						        		$url = preg_replace( '/https:/i', 'http:', $url );
							        	$link = 'https://twitter.com/intent/tweet?text='.esc_html( $title ).'&amp;url='.esc_url( $url ); ?>
										<a class="twp-icon-holder" rel="nofollow" <?php if( $twp_be_open_link_type == 'new-tab' ){ ?>target="_blank" <?php } ?> <?php if( $twp_be_open_link_type == 'new-window' ){ ?>onclick="twp_be_popup_new_window( event,'<?php echo esc_url( $link ); ?>'); " <?php } ?> href="<?php echo esc_url( $link ); ?>" >
			                                <?php
			                                $twitter_count = 0;
			                                if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-twitter"></i>';
			                                }else{
				                                if( $social_share_ed_socila_counter ){
				                                	$catch = true;
				                                	if( $catch ){
					                                	$twitter_transient = 'twitter_' . md5( $url );
										                $twitter_transient_count = get_transient( $twitter_transient );
										                if ( false === $twitter_transient_count ) {
						                                    $url = "http://opensharecount.com/count.json?url=".esc_url( $url );
						                                    $args = array( 'timeout' => 10 );
						                                    $response = wp_remote_get( $url, $args );
						                                    $json_response = wp_remote_retrieve_body( $response );
						                                    $json = json_decode( $json_response, true );
						                                    $twitter_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
										                	set_transient( $twitter_transient, $twitter_count, 10 * HOUR_IN_SECONDS );
										                }else{
										                	$twitter_count = $twitter_transient_count;
										                }
										            }else{
					                                    $url = "http://opensharecount.com/count.json?url=".esc_url( $url );
					                                    $args = array( 'timeout' => 10 );
					                                    $response = wp_remote_get( $url, $args );
					                                    $json_response = wp_remote_retrieve_body( $response );
					                                    $json = json_decode( $json_response, true );
					                                    $twitter_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
					                                }
				                                   
				                                }
					                            echo '<span class="twp-social-count">';
				                                if( $twitter_count <= 0 ){
				                                    echo '<i class="booster-icon twp-plus"></i>';
				                                }
				                                if( $twitter_count >= 1 ){
				                                	echo absint( $twitter_count );
				                                }
				                                echo "</span>"; ?>
												<span class="twp-share-media">
													<span class="twp-share-label">
				                                        <i class="booster-icon twp-twitter"></i>
				                                        <span class="twp-label-title">
				                                            <?php esc_html_e('Twitter','booster-extension'); ?>
				                                        </span>
												    </span>
												</span>
											<?php } ?>
										</a>
									<?php
							        echo "</div>";
						        }
					        
					        break;
					        case 'pinterest':
					        
						        if( $value ){
						        	echo '<div class="twp-social-icons twp-social-pinterest">'; ?>
										<a class="twp-icon-holder" rel="nofollow" href="javascript:twp_be_pinterest()">
											<?php
											
											$pinterest_count = 0;
											if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-pinterest"></i>';
			                                }else{
			                                	
												if( $social_share_ed_socila_counter ){
													$url = preg_replace( '/https:/i', 'http:', $url );
													$json_string = 'https://api.pinterest.com/v1/urls/count.json?&url='.esc_url( $url );
													$catch = true;
				                                	if( $catch ){
					                                	$pinterest_transient = 'pinterest_' . md5( $url );
										                $pinterest_transient_count = get_transient( $pinterest_transient );
										                if ( false === $pinterest_transient_count ) {
										                	
										                	$args = array( 'timeout' => 10 );
												            $response = wp_remote_get( $json_string, $args );
												            $json_response = wp_remote_retrieve_body( $response );
												            $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_response );
												            $json = json_decode( $json_string, true );
												            $pinterest_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
												            set_transient( $pinterest_transient, $pinterest_count, 1 * HOUR_IN_SECONDS );
										                }else{
															$pinterest_count = $pinterest_transient_count;
										                }
										            }else{
														$args = array( 'timeout' => 10 );
											            $response = wp_remote_get( $json_string, $args );
											            $json_response = wp_remote_retrieve_body( $response );
											            $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_response );
											            $json = json_decode( $json_string, true );
											            $pinterest_count = isset( $json[ 'count' ] ) ? intval( $json[ 'count' ] ) : 0;
											        }
										            
										        }
										        echo '<span class="twp-social-count">';
			                                    if( $pinterest_count <= 0 ){
				                                    echo '<i class="booster-icon twp-plus"></i>';
				                                }
			                                    if( $pinterest_count >= 1 ){
			                                    	echo absint( $pinterest_count );
			                                    }
			                                    echo "</span>";?>
				                                <span class="twp-share-media">
													<span class="twp-share-label">
				                                        <i class="booster-icon twp-pinterest"></i>
				                                        <span class="twp-label-title">
				                                            <?php esc_html_e('Pinterest','booster-extension'); ?>
				                                        </span>
				                                    </span>
												</span>
											<?php } ?>
										</a>
									<?php
							        echo "</div>";
						        }
					        
					        break;
					        case 'linkedin':
					        
						        if( $value ){
						        	
						        	echo '<div class="twp-social-icons twp-social-linkedin">';
							        	$link = "http://www.linkedin.com/shareArticle?mini=true&amp;title=" . esc_html( $title ) . "&amp;url=" . esc_url( $url ); ?>
										<a class="twp-icon-holder" rel="nofollow" <?php if( $twp_be_open_link_type == 'new-tab' ){ ?>target="_blank" <?php } ?> <?php if( $twp_be_open_link_type == 'new-window' ){ ?>onclick="twp_be_popup_new_window( event,'<?php echo esc_url( $link ); ?>'); " <?php } ?> href="<?php echo esc_url( $link ); ?>" >
											<?php 
											if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-linkedin"></i>';
			                                }else{ ?>
												<span class="twp-share-media">
				                                    <span class="share-media-nocount">
													    <i class="booster-icon twp-linkedin"></i>
				                                    </span>
													<span class="twp-share-label twp-label-title">
				                                        <?php esc_html_e('LinkedIn','booster-extension'); ?>
				                                    </span>
												</span>
											<?php } ?>
										</a>
										<?php
									echo "</div>";
						        }
					        
					        break;

					         case 'vk':
					        
						        if( $value ){
						        	
						        	echo '<div class="twp-social-icons twp-social-vk">';
							        	$link = 'http://vk.com/share.php?url=' . esc_url( $url ) . '&caption=' . esc_attr( $title ); ?>
										<a class="twp-icon-holder" rel="nofollow" <?php if( $twp_be_open_link_type == 'new-tab' ){ ?>target="_blank" <?php } ?> <?php if( $twp_be_open_link_type == 'new-window' ){ ?>onclick="twp_be_popup_new_window( event,'<?php echo esc_url( $link ); ?>'); " <?php } ?> href="<?php echo esc_url( $link ); ?>" >
											<?php 
											if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-vk"></i>';
			                                }else{ ?>
												<span class="twp-share-media">
				                                    <span class="share-media-nocount">
													    <i class="booster-icon twp-vk"></i>
				                                    </span>
													<span class="twp-share-label twp-label-title">
				                                        <?php esc_html_e('VK','booster-extension'); ?>
				                                    </span>
												</span>
											<?php } ?>
										</a>
										<?php
									echo "</div>";
						        }
					        
					        break;

					        case 'email':
					        
						        if( $value ){
						        	
						        	echo '<div class="twp-social-icons twp-social-email">';
							        	$link = 'mailto:?subject='.esc_html( $social_share_email_subject ).':'.'&amp;body=' . esc_html( $social_share_email_body.' '. esc_html( $title ).' '.esc_url( $url ) ); ?>
										<a class="twp-icon-holder" rel="nofollow" <?php if( $twp_be_open_link_type == 'new-tab' ){ ?>target="_blank" <?php } ?> href="<?php echo $link; ?>">
											<?php 
											if( $layout == 'layout-2' ){
			                                	echo '<i class="booster-icon twp-mail-envelope"></i>';
			                                }else{ ?>
												<span class="twp-share-media">
				                                    <span class="share-media-nocount">
													    <i class="booster-icon twp-mail-envelope"></i>
				                                    </span>
				                                    <span class="twp-share-label twp-label-title">
				                                        <?php esc_html_e('Email','booster-extension'); ?>
				                                    </span>
												</span>
											<?php } ?>
										</a>
										<?php
									echo "</div>";
						        }
					        
					        break;
					    }
					}

				}
				?>
			</div>
		</div>

	<?php
	}

endif;

$layout = '';

if( !empty( $_POST['layout'] ) ){
	$layout = esc_html( $_POST['layout'] );
}

booster_extension_social_share_display($layout);