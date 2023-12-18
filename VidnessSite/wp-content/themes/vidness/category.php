<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content">
		<div class="banner narrow"></div>
		<div id="content" role="main" class="pagecontent newscontent">
			<header class="entry-header">
				<h1 class="entry-title"><?php printf( __( 'Category Archives: %s', 'twentytwelve' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
			</header>

			<div class="news-left">
				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $size='thumbnail', $icon=false); ?>
							<?php if ($feat_image[0] != '') { ?>
								<div class="featured-image">
									<img src="<?= $feat_image[0]; ?>" />
									<div class="date"><?php the_date('j M Y'); ?></div>
								</div>
								<div class="info">
									<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
									<div class="featured-post">
										<?php _e( 'Featured post', 'twentytwelve' ); ?>
									</div>
									<?php endif; ?>
								
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" class="entry-title"><?php the_title(); ?></a>
									
									<div class="responsive-featured-image">
										<img src="<?= $feat_image[0]; ?>" />
									</div>
									<?php the_excerpt(); ?>

									<a href="<?php the_permalink(); ?>" class="readmore">Read more</a>
								</div>

							<?php } else { ?>
							<div class="info no-featured">
								<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
								<div class="featured-post">
									<?php _e( 'Featured post', 'twentytwelve' ); ?>
								</div>
								<?php endif; ?>
							
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark" class="entry-title"><?php the_title(); ?></a>
									
								<?php the_excerpt(); ?>

								<a href="<?php the_permalink(); ?>" class="readmore">Read more</a>
							</div>
							<?php } ?>

							<div class="clear"></div>
						</article><!-- #post -->
						<div style="height: 40px;"></div>
					<?php endwhile; ?>

					<?php twentytwelve_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">

					<?php if ( current_user_can( 'edit_posts' ) ) :
						// Show a different message to a logged-in user who can add posts.
					?>
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'No posts to display', 'twentytwelve' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php printf( __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'twentytwelve' ), admin_url( 'post-new.php' ) ); ?></p>
						</div><!-- .entry-content -->

					<?php else :
						// Show the default message to everyone else.
					?>
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentytwelve' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'twentytwelve' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					<?php endif; // end current_user_can() check ?>

					</article><!-- #post-0 -->

				<?php endif; // end have_posts() check ?>
			</div> <!-- news content -->
			<?php get_sidebar(); ?>
		</div><!-- #content -->

		<div class="clear"></div>
	</div><!-- #primary -->

	
<?php get_footer(); ?>