<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php if(!is_page_template( 'blank-page.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
            <div class="site-info">
                <a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'wp-bootstrap-starter' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'wp-bootstrap-starter' ), 'WordPress' ); ?></a>
                <span class="sep"> | </span>
                <a class="credits" href="https://afterimagedesigns.com/wordpress-bootstrap-starter-theme/" target="_blank" title="Wordpress Bootstrap Starter Theme" alt="Wordpress Bootstrap Starter Theme"><?php echo esc_html__('Wordpress Bootstrap Starter Theme','wp-bootstrap-starter') ?>
            </div><!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
