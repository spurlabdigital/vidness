<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" class="pagecontent" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content" style="padding: 80px 20px; text-align: center;">
					<h1 class="main-title">Page not found</h1>
					The page you are looking for cannot be found.<br />Try navigating to your page from the menu or go back to the <a href="<?= esc_url(home_url('/')); ?>">home page</a>?
				</div><!-- .entry-content -->
			</article><!-- #post -->
		</div><!-- #content -->

	</div><!-- #primary -->

<?php get_footer(); ?>