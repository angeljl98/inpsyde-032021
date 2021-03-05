<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Mate
 * @since 1.0.0
 */

/**
 * Toogle Contents
 * @hooked mate_header_toggle_search - 10
 * @hooked mate_content_offcanvas - 30
*/

do_action('mate_before_footer_content_action'); ?>

</div>

<footer id="site-footer" role="contentinfo">
    <?php
    /**
     * Footer Content
     * @hooked mate_footer_content_widget - 10
     * @hooked mate_footer_content_info - 20
    */

    do_action('mate_footer_content_action'); ?>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
