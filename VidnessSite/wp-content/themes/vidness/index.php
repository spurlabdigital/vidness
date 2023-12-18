<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div class="homecontent insights">
		<a href="<?= esc_url(home_url('/')); ?>" class="logo std"></a>
		<?php include get_template_directory() . '/_mainnav.php'; ?>
		
		<div class="pagewrap featured">
			<h1 class="side">Insights</h1>
			<div class="inner">
				<p>We love to share stories about the PAWAO team, our investments and portfolio companies, as well as our work in advisory.</p>
			</div>
		</div>
		<div class="pagewrap dark">
			<h1 class="side" style="display: none;">Hot Topics</h1>
			<div class="topicslider">
    		<?php 
			$args = array(
			    'posts_per_page' => -1,
				'post__in' => get_option( 'sticky_posts' ),
			);
			$the_query = new WP_Query( $args );
			if ($the_query->have_posts()) {
				while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<?php $featuredImage = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $size='full', $icon=false); ?>
				<div class="featuredsingle">
					<div class="inner">
						<div class="s">
							<a href="<?php the_permalink(); ?>" class="preview" style="background-image: url(<?= $featuredImage[0]; ?>);"></a>
							<div class="info">
								<a class="title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								<?php the_excerpt(); ?>
								<a href="<?php the_permalink(); ?>">Read more</a>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile; 
			}?>
			</div>
	    	&nbsp;
		</div>
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/slick.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.topicslider').slick({
					centerMode: true,
					slidesToShow: 1,
					responsive: [
						{
							breakpoint: 768,
							settings: {
								arrows: false,
								centerPadding: '0',
								slidesToShow: 1
							}
						}
					]
				});
			});
		</script>
		<div class="pagewrap white">
			<h1 class="side" style="display: none;">Our Blog</h1>
			<div class="inner wider">
				<ul class="insightsitems">
					<?php rewind_posts(); ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php $featuredImage = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), $size='full', $icon=false); ?>
						<li class="single sitem">
							<a href="<?php the_permalink(); ?>" class="content">
								<div class="bgcontainer" style="background-image: url(<?= $featuredImage[0]; ?>);"></div>
								<h2><?php the_title(); ?></h2>
								<span>Read more</span>
							</a>
						</li>
					<?php endwhile; ?>
					<div class="clear"></div>
				</ul>
			</div>
		</div>
		<div class="pagewrap light">
			<div class="centered">
				<p><a href="<?= esc_url(home_url('/contact-us')); ?>" class="styled">Contact us</a></p>
				<div class="dtext">
					<p><a href="mailto:hello@pawao.ch" target="”_blank”">hello@pawao.ch</a><br>
					+41 41 561 00 11</p>
					<p><a href="https://www.linkedin.com/company/pawao" target="”_blank”">LinkedIn</a></p>
				</div>
			</div>
		</div>
	</div>
	

<?php get_footer(); ?>