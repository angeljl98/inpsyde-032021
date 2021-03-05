<?php
/**
 * Plugin Name: Code Widget
 * Plugin URI: https://wordpress.org/plugins/code-widget/
 * Description: Code Widget help you to run <code>Code</code> and simple text in  widget which have different type <code>Short Code</code> <code>PHP Code</code>. Yes, you can also add <code>TEXT</code> and  <code>HTML</code>.
 * Version: 1.0.12
 * Author: Solution Box
 * Author URI: https://solbox.dev/
 * Text Domain: code-widget
 * Domain Path: /languages/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package code widget
 */

/** If this file is called directly, abort. */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'CODE_WIDGET_PATH' ) ) {

	/**
	 * Absolute path of this plugin
	 *
	 * @since 1.0
	 */
	define( 'CODE_WIDGET_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

define( 'CODE_WIDGET_VERSION', '1.0.12' );
define( 'CODE_WIDGET_TEXT_DOMAIN', 'code-widget' );

if ( ! class_exists( 'Code_Widget' ) ) {

	/**
	 * Adds Code_Widget.
	 */
	class Code_Widget extends WP_Widget {

		/**
		 * Instance of the class
		 *
		 * @var instance
		 */
		public static $instance;

		/**
		 * Register widget with WordPress.
		 */
		public function __construct() {
			load_plugin_textdomain( CODE_WIDGET_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) );
			add_action( 'admin_init', [ $this, 'dismiss_review_notice' ] );
			add_action( 'admin_init', [ $this, 'show_review_notice' ] );
			add_action( 'wp_ajax_cw_deactivation_feedback', [ $this, 'deactivation_feedback' ] );
			add_action( 'admin_notices', [ $this, 'sb_promote_plugins' ] );

			parent::__construct(
				'codewidget', // Base ID.
				esc_html__( 'Code Widget', CODE_WIDGET_TEXT_DOMAIN ), // Name.
				array( 'description' => esc_html__( 'Any Text,Short Code,HTML,PHP Code .', CODE_WIDGET_TEXT_DOMAIN ) ) // Args.
			);
			
			require CODE_WIDGET_PATH .'/lib/solbox-plugin-deactivation-survey/deactivate-feedback-form.php';
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args Widget arguments.
		 *
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			$cw_type    = $instance['cw_type'];
			$cw_filter  = $instance['cw_filter'];
			$cw_content = apply_filters( 'cw_content', $instance['cw_content'], $this );

			if ( 'php_code' == $cw_type ) {
				$cw_final_content = $this->php_exe( $cw_content );
			}

			if ( 'short_code' == $cw_type ) {
				$cw_final_content = do_shortcode( $cw_content );
			}

			if ( 'html_code' == $cw_type ) {
				$cw_final_content = convert_smilies( balanceTags( $cw_content ) );
			}

			if ( 'text_code' == $cw_type ) {
				$cw_final_content = wptexturize( esc_html( $cw_content ) );
			}

			$cw_final_content = apply_filters( 'cw_final_content', $cw_final_content );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . esc_html( apply_filters( 'widget_title', $instance['title'] ) ) . $args['after_title'];
			}
			echo '<div class="code-widget">' . ( $cw_filter ? wpautop( $cw_final_content ) : $cw_final_content ) . '</div>';

			echo $args['after_widget'];
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			if ( 0 == count( $instance ) ) {
				$instance['cw_type']    = ! empty( $instance['cw_type'] ) ? $instance['cw_type'] : 'short_code';
				$instance['cw_content'] = ! empty( $instance['cw_content'] ) ? $instance['cw_content'] : esc_html__( 'your code ....', CODE_WIDGET_TEXT_DOMAIN );
				$instance['cw_filter']  = ! empty( $instance['cw_filter'] ) ? $instance['cw_filter'] : 0;
				$title                  = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', CODE_WIDGET_TEXT_DOMAIN );
			} else {
				$instance['cw_type']    = $instance['cw_type'];
				$instance['cw_content'] = $instance['cw_content'];
				$instance['cw_filter']  = $instance['cw_filter'];
				$title                  = $instance['title'];
			}
			?>
				<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', CODE_WIDGET_TEXT_DOMAIN ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'cw_type' ) ); ?>">
					<?php esc_attr_e( 'Widget Type:', CODE_WIDGET_TEXT_DOMAIN ); ?>
				</label>
				</p>
				<select  name="<?php echo esc_html( $this->get_field_name( 'cw_type' ) ); ?>" class="widefat" id="<?php esc_html_e( $this->get_field_id( 'cw_type' ) ); ?>">
				<option value="short_code" <?php selected( $instance['cw_type'], 'short_code' ); ?>>
					<?php esc_attr_e( 'Short Code', CODE_WIDGET_TEXT_DOMAIN ); ?>
				</option>
				<option value="php_code"   <?php selected( $instance['cw_type'], 'php_code' ); ?>> 
					<?php esc_attr_e( 'PHP Code', CODE_WIDGET_TEXT_DOMAIN ); ?>
				</option>
				<option value="html_code"  <?php selected( $instance['cw_type'], 'html_code' ); ?>>
					<?php esc_attr_e( 'HTML', CODE_WIDGET_TEXT_DOMAIN ); ?>
				</option>
				<option value="text_code"  <?php selected( $instance['cw_type'], 'text_code' ); ?>>
					<?php esc_attr_e( 'Text', CODE_WIDGET_TEXT_DOMAIN ); ?>
				</option>
				</select>
				<p>
				<textarea class="widefat" rows="12" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'cw_content' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'cw_content' ) ); ?>"><?php echo $instance['cw_content']; ?></textarea>
				</p>
				<p><input id="<?php echo esc_attr( $this->get_field_id( 'cw_filter' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'cw_filter' ) ); ?>"
				type="checkbox" <?php checked( $instance['cw_filter'], 'on' ); ?>/>&nbsp;<label
				for="<?php echo esc_html( $this->get_field_id( 'cw_filter' ) ); ?>"><?php esc_html_e( 'Automatically add paragraphs.', CODE_WIDGET_TEXT_DOMAIN ); ?></label>
				<a href="https://buy.paddle.com/product/640837" target="_blank"><?php _e( 'Donate this plugin', CODE_WIDGET_TEXT_DOMAIN )?></a>
				</p>
			<?php
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 *
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance               = array();
			$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['cw_type']    = ( ! empty( $new_instance['cw_type'] ) ) ? strip_tags( $new_instance['cw_type'] ) : '';
			$instance['cw_content'] = ( ! empty( $new_instance['cw_content'] ) ) ? strip_tags( $new_instance['cw_content'] ) : '';
			$instance['cw_filter']  = ( ! empty( $new_instance['cw_filter'] ) ) ? strip_tags( $new_instance['cw_filter'] ) : 0;

			/*
			Unfiltered_html
			Since 2.0
			Allows user to post HTML markup or even JavaScript code in pages, posts, comments and widgets.
			Note: Enabling this   option for untrusted users may result in their posting malicious or poorly formatted code.
			Note: In WordPress Multisite, only Super Admins have the unfiltered_html capability.
			*/
			if ( current_user_can( 'unfiltered_html' ) ) {
				$instance['cw_content'] = $new_instance['cw_content'];
			} else {
				$instance['cw_content'] = stripslashes( wp_filter_post_kses( $new_instance['cw_content'] ) );
			}
			return $instance;
		}

		/**
		 * Php exe use to excute php code in string
		 *
		 * @param string $content string of content.
		 */
		private function php_exe( $content ) {
			apply_filters( 'before_cw_php_exe', $content );
			ob_start();
			eval( '?>' . $content );
			$text = ob_get_contents();
			ob_end_clean();
			return apply_filters( 'after_cw_php_exe', $text );
		}

		/**
		 * Returns the current instance of the class, in case some other
		 * plugin needs to use its public methods.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 * @return Code_Widget Returns the current instance of the class
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
					self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Check installation time set admin notice.
		 *
		 * @since 1.0.5
		 * @return void
		 */
		public function show_review_notice() {

			$install_date    = get_option( 'cw_activation_time' );
			$already_done    = get_option( 'cwrn_dismiss' );
			$show_later_date = get_option( 'cwrn_show_later' );
			$now             = strtotime( 'now' );

			// If already done don't show.
			if ( $already_done ) {
				return;
			}
			if ( ! $install_date ) {
				update_option( 'cw_activation_time', strtotime( '-7 days' ) );
				$install_date = strtotime( '-7 days' );
			}
			$past_date = strtotime( '-7 days' );
			// If show later exist then must me greater than set time.
			if ( ( $show_later_date && $now < $show_later_date ) ) {
				return;
			}
			if ( $past_date >= $install_date ) {
				add_action( 'admin_notices', array( $this, 'review_admin_notice' ) );
			}
		}

		/**
		 * Review Show admin notice.
		 *
		 * @since 1.0.5
		 * @return void
		 */
		public function review_admin_notice() {
			// WordPress global variable.
			global $pagenow;
			if ( $pagenow == 'index.php' ) {

					$show_later = add_query_arg(
						array(
							'cw_show_later' => '1',
							'_wpnonce'      => wp_create_nonce( 'show-later' ),
						),
						get_admin_url()
					);

					$already_done = add_query_arg(
						array(
							'cw_already_done' => '1',
							'_wpnonce'        => wp_create_nonce( 'already-done' ),
						),
						get_admin_url()
					);
					$review_url   = esc_url( 'https://wordpress.org/support/plugin/code-widget/reviews/#new-post' );

					printf(__('<div class="notice notice-info"><p>You have been using <b> Code Widget </b> for a while. We hope you liked it ! Please give us a quick rating, it works as a boost for us to keep working on the plugin !</p><p class="action">
					<a href="%s" class="button button-primary" target="_blank">Rate Now!</a>
					<a href="%s" class="button button-secondary "> Show Later </a>
					<a href="%s" class="void-grid-review-done"> Already Done !</a>
							</p></div>', CODE_WIDGET_TEXT_DOMAIN ), $review_url, $show_later, $already_done );
			}
		}

		/**
		 * Handel notice action.
		 *
		 * @since 1.0.5
		 * @return void
		 */
		public function dismiss_review_notice() {

			if ( isset( $_GET['cw_show_later'] ) && isset( $_GET['_wpnonce'] ) ) {
				if ( wp_verify_nonce( $_GET['_wpnonce'], 'show-later' ) ) {
					update_option( 'cwrn_show_later', strtotime( '+2 days' ) );
				}
			}

			if ( isset( $_GET['cw_already_done'] ) && isset( $_GET['_wpnonce'] ) ) {
				if ( wp_verify_nonce( $_GET['_wpnonce'], 'already-done' ) ) {
					update_option( 'cwrn_dismiss', true );
				}
			}
			if ( isset( $_GET['satc_dismiss'] ) && isset( $_GET['_wpnonce'] ) ) {
				if ( wp_verify_nonce( $_GET['_wpnonce'], 'satc_dismiss' ) ) {
					update_option( 'satc_dismiss', true );
				}
			}

		}

		/**
		 * Submission deactivation feedback.
		 *
		 * @since 1.0.7
		 * @return void
		 */
		public function deactivation_feedback() {

		check_ajax_referer( 'solbox-plugin-deactivate-nonce', 'security' );

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( 'Jack;@123// 1...' );
			}

			$_data         = json_decode( wp_unslash( $_POST['plugin'] ), true );
			$email         = get_option( 'admin_email' );
			$reason        = sanitize_text_field( wp_unslash( $_data['reasons'][ $_POST['reason'] ] ) );
			$reason_detail = '';

			if ( 'other' == $_POST['reason'] ) {
				$reason_detail = sanitize_text_field( wp_unslash( $_POST['comments'] ) );
			}

			if ( 'found-better-plugin' == $_POST['reason'] ) {
				$reason_detail = sanitize_text_field( wp_unslash( $_POST['plugin-name'] ) );
			}

			$fields = [
				'email'             => $email,
				'website'           => get_site_url(),
				'action'            => 'deactivate',
				'reason'            => $reason,
				'reason_detail'     => $reason_detail,
				'blog_language'     => get_bloginfo( 'language' ),
				'wordpress_version' => get_bloginfo( 'version' ),
				'php_version'       => PHP_VERSION,
				'plugin_version'    => CODE_WIDGET_VERSION,
				'plugin_name'       => 'Code Widget',
			];

			$response = wp_remote_post(
				'https://solbox.dev/',
				[
					'method'      => 'POST',
					'timeout'     => 5,
					'httpversion' => '1.0',
					'blocking'    => false,
					'headers'     => [],
					'body'        => $fields,
				]
			);

			wp_die();
		}

			/**
			 * Review Show admin notice.
			 *
			 * @since 1.0.10
			 * @return void
			 */
		public function sb_promote_plugins() {
			// update_option( 'satc_dismiss', '' );
			$already_dismiss = get_option( 'satc_dismiss' );
			if ( $already_dismiss ) {
				return;
			}
			// WordPress global variable.
			global $pagenow;

			$action = 'install-plugin';
			$slug   = 'sticky-add-to-cart-woo';
			$satc_url = wp_nonce_url(
				add_query_arg(
					array(
						'action' => $action,
						'plugin' => $slug,
					),
					admin_url( 'update.php' )
				),
				$action . '_' . $slug
			);
			$dismiss_action = add_query_arg(
				array(
					'satc_dismiss' => '1',
					'_wpnonce'        => wp_create_nonce( 'satc_dismiss' ),
				),
				get_admin_url()
			);

			if ( class_exists( 'WooCommerce' ) ) { 
				printf( __( '<div class="notice notice-info is-dismissible"><p>Worried about conversion rates?😟 No worries,🥳 <a href="%s" style="text-decoration:none" class="button button-secondary"><b>Install</b></a> Simple Sticky Add To Cart For WooCommerce to increase the conversion rate of product page. </p> <a href="%s"><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></a></div>' ), $satc_url, $dismiss_action );
			}
		}

	} // class Code_Widget.


	/**
	 * Widget Register hook callback.
	 *
	 * @since 1.0.0
	 * @version 1.0.5
	 * @return void
	 */
	function register_code_widget() {
		/** Initialises an object of this class */
		register_widget( 'Code_Widget' );
	}
	add_action( 'widgets_init', 'register_code_widget' );

}

register_activation_hook( __FILE__, 'cw_activation_time' );

/**
 * Plugin Activation hook callback.
 *
 * @since 1.0.0
 * @return void
 */
function cw_activation_time() {
	$get_activation_time = strtotime( 'now' );
	update_option( 'cw_activation_time', $get_activation_time );  // replace your_plugin with Your plugin name
}

?>
