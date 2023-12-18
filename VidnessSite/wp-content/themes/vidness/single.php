<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?><?php endwhile; ?>
	<div class="homecontent">
		<div class="logo">SPUR.lab<br />Vidness</div>
		<?php wp_nav_menu( array( 'menu' => 'Main menu', 'menu_class' => 'nav-menu' ) ); ?>
		<div class="about">
			<?php the_content(); ?>
		</div>
	</div>
	<div class="clear"></div>
<?php get_footer(); ?>