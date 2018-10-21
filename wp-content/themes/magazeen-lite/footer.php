<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package magazeen_lite
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="grid grid-pad cleared">
	<div class="col-4-12">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</div>

	<div class="col-4-12">
		<?php dynamic_sidebar( 'footer-2' ); ?>
	</div>

	<div class="col-4-12 cleared">
		<?php dynamic_sidebar( 'footer-3' ); ?>
	</div>
		<div class="site-info col-1-1 cleared">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'magazeen-lite' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'magazeen-lite' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'magazeen-lite' ), 'magazeen-lite', '<a href="http://thepixeltribe.com" rel="designer">Denis Bosire</a>' ); ?>
		</div><!-- .site-info -->
	</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
