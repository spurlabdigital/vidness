<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<div class="clear"></div>
	
</div><!-- #page -->
<script>
function customScrollTo(item) {
	$('html, body').animate({
		scrollTop: $("#" + item).offset().top
	}, 700);
}
</script>
<?php wp_footer(); ?>
</body>
</html>