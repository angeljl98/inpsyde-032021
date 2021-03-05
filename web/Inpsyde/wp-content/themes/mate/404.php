<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Mate
 */

get_header();
?>

    <div class="wrapper">
    	<div class="theme-panelarea">

        	<div id="site-contentarea" class="theme-panelarea-secondary">
            	<main id="main" class="site-main" role="main">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('theme-error-article'); ?>>
                        <div class="theme-block error-block error-block-heading">
                            <header class="entry-header">
                                <h1 class="entry-title">
                                    <?php esc_html_e('Oops! That page can&rsquo;t be found.', 'mate'); ?>
                                </h1>
                            </header>

                        </div>
                        <div class="theme-block error-block error-block-search">

                                <div class="wrapper-inner">
                                    <div class="column column-8">
                                        <?php get_search_form(); ?>
                                    </div>
                                </div>

                        </div>
                        <div class="theme-block error-block error-block-top">

                            <div class="wrapper-inner">
                                <div class="column column-12">
                                    <h2><?php esc_html_e('Maybe it’s out there, somewhere...', 'mate'); ?></h2>
                                    <p><?php esc_html_e('You can always find insightful stories on our', 'mate'); ?>
                                    <a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e('Homepage','mate'); ?></a></p>
                                </div>
                            </div>

                        </div>
                        <div class="theme-block error-block error-block-middle">

                            <div class="wrapper-inner">
                                <div class="column column-12">
                                    <h2><?php esc_html_e('Still feeling lost? You’re not alone.', 'mate'); ?></h2>
                                    <p><?php esc_html_e('Enjoy these stories about getting lost, losing things, and finding what you never knew you were looking for.', 'mate'); ?></p>
                                </div>
                            </div>

                        </div>
                        <div class="theme-block error-block error-block-bottom">
                            <?php mate_related_posts(); ?>
                        </div>
                    </article>
            	</main>
            </div>

        </div>
    </div>

<?php
get_footer();
