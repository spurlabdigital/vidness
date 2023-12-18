<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div id="primary" class="site-content">
		<div id="content" class="pagecontent narrow" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="content-header">
					<h1 class="entry-title"><?php printf( __( 'Search: %s', 'twentytwelve' ), get_search_query() ); ?>
						<a class="return" href="<?= esc_url(home_url('/news')); ?>">&lt;</a>
					</h1>
					<div class="content-banner" style="background-image: url('<?= get_template_directory_uri(); ?>/images/newsheader.png');"></div>

				</div>
				
				<div class="entry-content">
					<form class="searchform" method="get" action="<?= esc_url(home_url('/')); ?>">
						<h2>Search</h2>
						<input name="s" id="s" class="s" type="text" placeholder="Enter your search" value="<?= $_GET['s']; ?>" />
					</form>
					<?php if ( have_posts() ) : ?>
						<ul class="newslist">
						<?php while ( have_posts() ) : the_post(); ?>
							<?if (get_post_type(get_the_ID()) != 'post') continue; ?>
							<li>
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?>
									<span><?= get_the_date(); ?></span>
								</a>
							</li>
						<?php endwhile; ?>
						</ul>
					<?php endif; // end have_posts() check ?>
				</div><!-- .entry-content -->
			</article><!-- #post -->
		</div><!-- #content -->

	</div><!-- #primary -->
	
<?php get_footer(); ?>