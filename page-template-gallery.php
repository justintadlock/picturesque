<?php
/**
 * Template Name: Gallery
 *
 * This is the photoblogging template.  It displays a section with the latest images and a section with the 
 * latest galleries ('image' and 'gallery' post formats).
 *
 * @package Picturesque
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link http://themehybrid.com/themes/picturesque
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

get_header(); // Loads the header.php template. ?>

	<?php do_atomic( 'before_content' ); // picturesque_before_content ?>

	<div id="content">

		<?php do_atomic( 'open_content' ); // picturesque_open_content ?>

		<div class="hfeed">

			<?php $loop = new WP_Query(
				array(
					'posts_per_page' => ( 'layout-1c' == theme_layouts_get_layout() ? 10 : 6 ),
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => array( 'post-format-image' )
						)
					)
				)
			);

			/* Set up some default variables to use in the gallery. */
			$gallery_columns = ( 'layout-1c' == theme_layouts_get_layout() ? 5 : 3 );
			$gallery_iterator = 0; ?>

			<?php if ( $loop->have_posts() ) : ?>

				<div class="gallery">

					<h2 class="gallery-title">
						<?php _e( 'Latest Images', 'picturesque' ); ?>
						<a class="post-format-link" href="<?php echo get_post_format_link( 'image' ); ?>"><?php _e( 'View Archive &rarr;', 'picturesque' ); ?></a>
					</h2>

					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

						<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns == 0 ) echo '<div class="gallery-row gallery-clear">'; ?>

						<div class="gallery-item col-<?php echo esc_attr( $gallery_columns ); ?>">
							<div class="gallery-icon">
								<?php get_the_image( array( 'size' => 'thumbnail', 'meta_key' => false ) ); ?>
							</div>
							<div class="gallery-caption">
								<?php the_title(); ?>
							</div>
						</div>

						<?php if ( $gallery_columns > 0 && ++$gallery_iterator % $gallery_columns == 0 ) echo '</div>'; ?>

					<?php endwhile; ?>

					<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns !== 0 ) echo '</div>'; ?>

				</div><!-- .gallery -->

			<?php endif; ?>

			<?php $loop = new WP_Query(
				array(
					'posts_per_page' => ( 'layout-1c' == theme_layouts_get_layout() ? 10 : 6 ),
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => array( 'post-format-gallery' )
						)
					)
				)
			);

			/* Set up some default variables to use in the gallery. */
			$gallery_columns = ( 'layout-1c' == theme_layouts_get_layout() ? 5 : 3 );
			$gallery_iterator = 0; ?>

			<?php if ( $loop->have_posts() ) : ?>

				<div class="gallery">

					<h2 class="gallery-title">
						<?php _e( 'Latest Galleries', 'picturesque' ); ?>
						<a class="post-format-link" href="<?php echo get_post_format_link( 'gallery' ); ?>"><?php _e( 'View Archive &rarr;', 'picturesque' ); ?></a>
					</h2>

					<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

						<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns == 0 ) echo '<div class="gallery-row gallery-clear">'; ?>

						<div class="gallery-item col-<?php echo esc_attr( $gallery_columns ); ?>">
							<div class="gallery-icon">
								<?php get_the_image( array( 'size' => 'thumbnail', 'meta_key' => false ) ); ?>
							</div>
							<div class="gallery-caption">
								<?php the_title(); ?>
							</div>
						</div>

						<?php if ( $gallery_columns > 0 && ++$gallery_iterator % $gallery_columns == 0 ) echo '</div>'; ?>

					<?php endwhile; ?>

					<?php if ( $gallery_columns > 0 && $gallery_iterator % $gallery_columns !== 0 ) echo '</div>'; ?>

				</div><!-- .gallery -->

			<?php endif; wp_reset_query(); ?>

			<?php do_atomic( 'after_singular' ); // picturesque_after_singular ?>

		</div><!-- .hfeed -->

		<?php do_atomic( 'close_content' ); // picturesque_close_content ?>

		<?php get_template_part( 'loop-nav' ); // Loads the loop-nav.php template. ?>

	</div><!-- #content -->

	<?php do_atomic( 'after_content' ); // picturesque_after_content ?>

<?php get_footer(); // Loads the footer.php template. ?>