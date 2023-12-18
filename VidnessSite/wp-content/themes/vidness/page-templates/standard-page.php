<?php
/**
 * Template Name: Standard Template
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="homecontent aboutalt">
		<a href="<?= esc_url(home_url('/')); ?>" class="logo std"></a>
		<?php include get_template_directory() . '/_mainnav.php'; ?>
		
		<div class="pagewrap light">
			<h1 class="side"><?php the_title(); ?></h1>
			<div class="inner">
	    		<?php the_content(); ?>
	    	</div>
	    	&nbsp;
		</div>

		
	</div>
	<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>