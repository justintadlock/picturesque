<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package MyLife
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2011, Justin Tadlock
 * @link http://themehybrid.com/themes/my-life
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // picturesque_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // picturesque_open_content ?>

		<div class="hfeed">

			<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>

			<?php if ( have_posts() ) : $columns = 3; $i = 0; ?>

				<div class="hentry">
					<div class="entry-content">

					<ul class="xoxo links">

					<?php while ( have_posts() ) : the_post(); ?>

<li>
	<a class="permalink" href="<?php echo esc_url( picturesque_url_grabber() ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> 
	<?php echo do_shortcode( '[entry-comments-link before="(" after=")" one="1" more="%s" zero="0"]' ); ?>
</li>

					<?php endwhile; ?>
					</ul>
					</div>
				</div>

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // picturesque_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // picturesque_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>