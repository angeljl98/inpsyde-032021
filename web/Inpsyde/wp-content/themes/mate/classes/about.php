<?php

/**
 * Mate About Page
 * @package Mate
 *
*/

if( !class_exists('Mate_About_page') ):

	class Mate_About_page{

		function __construct(){

			add_action('admin_menu', array($this, 'mate_backend_menu'),999);

		}

		// Add Backend Menu
        function mate_backend_menu(){

            add_theme_page(esc_html__( 'Mate Options','mate' ), esc_html__( 'Mate Options','mate' ), 'activate_plugins', 'mate-about', array($this, 'mate_main_page'));

        }

        // Settings Form
        function mate_main_page(){

            require get_template_directory() . '/classes/about-render.php';

        }

	}

	new Mate_About_page();

endif;