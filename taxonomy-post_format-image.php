<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 *
 * @package Picturesque
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link http://themehybrid.com/themes/picturesque
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up some default variables to use in the gallery. */
$gallery_columns = apply_atomic( 'post_format_archive_gallery_columns', 3 );
$gallery_iterator = 0;

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // picturesque_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // picturesque_open_content ?>

		<div class="hfeed">

			<?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. ?>

			<?php if ( have_posts() ) : ?>

				<div class="gallery">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns == 0 ) echo '<div class="gallery-row">'; ?>

						<div class="gallery-item col-<?php echo esc_attr( $gallery_columns ); ?>">
							<div class="gallery-icon">
								<?php get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail', 'image_scan' => true ) ); ?>
							</div>
							<div class="gallery-caption">
								<?php the_title(); ?>
							</div>
						</div>

						<?php if ( $gallery_columns > 0 && ++$gallery_iterator % $gallery_columns == 0 ) echo '</div>'; ?>

					<?php endwhile; ?>

					<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns !== 0 ) echo '</div>'; ?>

				</div><!-- .gallery -->

			<?php else : ?>

				<?php get_template_part( 'loop-error' ); // Loads the loop-error.php template. ?>

			<?php endif; ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // picturesque_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // picturesque_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>