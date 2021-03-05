<?php
if ( !class_exists('Mate_Dashboard_Notice') ):

    class Mate_Dashboard_Notice
    {
        function __construct()
        {	
            global $pagenow;

        	if( $this->mate_show_hide_notice() ){

	            if( is_multisite() ){

                  add_action( 'network_admin_notices',array( $this,'mate_admin_notiece' ) );

                } else {

                  add_action( 'admin_notices',array( $this,'mate_admin_notiece' ) );
                }
	        }
	        add_action( 'wp_ajax_mate_notice_dismiss', array( $this, 'mate_notice_dismiss' ) );
			add_action( 'switch_theme', array( $this, 'mate_notice_clear_cache' ) );

            if( isset( $_GET['page'] ) && $_GET['page'] == 'mate-about' ){

                add_action('in_admin_header', array( $this,'mate_hide_all_admin_notice' ),1000 );

            }
        }

        public function mate_hide_all_admin_notice(){

            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');

        }
        
        public static function mate_show_hide_notice( $status = false ){

            if( $status ){

                if( (class_exists( 'Booster_Extension_Class' ) ) || get_option('mate_admin_notice') ){

                    return false;

                }else{

                    return true;

                }

            }

            // Check If current Page 
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'mate-about'  ) {
                return false;
            }

        	// Hide if dismiss notice
        	if( get_option('mate_admin_notice') ){
				return false;
			}
        	// Hide if all plugin active
        	if ( class_exists( 'Booster_Extension_Class' ) && class_exists( 'Demo_Import_Kit_Class' ) && class_exists( 'Themeinwp_Import_Companion' ) ) {
				return false;
			}
			// Hide On TGMPA pages
			if ( ! empty( $_GET['tgmpa-nonce'] ) ) {
				return false;
			}
			// Hide if user can't access
        	if ( current_user_can( 'manage_options' ) ) {
				return true;
			}
			
        }

        // Define Global Value
        public static function mate_admin_notiece(){

            ?>
            <div class="updated notice is-dismissible welcome-panel twp-mate-notice">

                <h3><?php esc_html_e('Quick Setup','mate'); ?></h3>

                <strong><p><?php esc_html_e('Install recommended plugins just by click button.','mate'); ?></p></strong>

                <p>
                    
                    <a class="button button-primary twp-install-active" href="javascript:void(0)"><?php esc_html_e('Install and Active all recommended plugins.','mate'); ?></a>

                    <a class="button button-primary twp-getting-started" href="<?php echo esc_url( get_home_url(null, '/').'wp-admin/themes.php?page=mate-about' ); ?>"><?php esc_html_e('Getting Started','mate'); ?></a>

                    <span class="quick-loader-wrapper"><span class="quick-loader"></span></span>

                    <a class="btn-dismiss twp-custom-setup" href="javascript:void(0)"><?php esc_html_e('Dismiss this notice','mate'); ?></a>

                </p>

            </div>

        <?php
        }

        public function mate_notice_dismiss(){

        	if ( isset( $_POST[ '_wpnonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ '_wpnonce' ] ) ), 'mate_ajax_nonce' ) ) {

	        	update_option('mate_admin_notice','hide');

	        }

            die();

        }

        public function mate_notice_clear_cache(){

        	update_option('mate_admin_notice','');

        }

    }
    new Mate_Dashboard_Notice();
endif;