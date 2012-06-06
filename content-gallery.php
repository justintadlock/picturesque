<?php
/**
 * Gallery Content Template
 *
 * Template used to show posts with the 'gallery' post format.
 *
 * @package MyLife
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2011, Justin Tadlock
 * @link http://themehybrid.com/themes/my-life
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

do_atomic( 'before_entry' ); // picturesque_before_entry ?>

<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">

	<?php do_atomic( 'open_entry' ); // picturesque_open_entry ?>

	<?php if ( is_singular() ) { ?>

		<?php echo apply_atomic_shortcode( 'entry_title', the_title( '<h1 class="entry-title">', '</h1>', false ) ); ?>

		<?php echo apply_atomic_shortcode( 'byline', '<div class="byline">' . __( '[post-format-link] published on [entry-published] [entry-comments-link before=" | "] [entry-edit-link before=" | "]', 'my-life' ) . '</div>' ); ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'my-life' ), 'after' => '</p>' ) ); ?>
		</div><!-- .entry-content -->

		<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[entry-terms taxonomy="category" before="Posted in "] [entry-terms before="Tagged "]', 'my-life' ) . '</div>' ); ?>

	<?php } else { ?>

		<?php if ( current_theme_supports( 'get-the-image' ) ) get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail' ) ); ?>

		<?php echo apply_atomic_shortcode( 'entry_title', '[entry-title]' ); ?>

		<div class="entry-summary">
			<?php if ( has_excerpt() ) {
				the_excerpt();
				wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', 'my-life' ), 'after' => '</p>' ) );
			} ?>

			<?php $count = picturesque_get_image_attachment_count(); ?>
			<p class="image-count"><?php printf( _n( 'This gallery contains %s image.', 'This gallery contains %s images.', $count, 'my-life' ), $count ); ?></p>

		</div><!-- .entry-summary -->

		<?php echo apply_atomic_shortcode( 'entry_meta', '<div class="entry-meta">' . __( '[post-format-link] published on [entry-published] [entry-permalink before="| "] [entry-comments-link before="| "] [entry-edit-link before="| "]', 'my-life' ) . '</div>' ); ?>

	<?php } ?>

	<?php do_atomic( 'close_entry' ); // picturesque_close_entry ?>

</div><!-- .hentry -->

<?php do_atomic( 'after_entry' ); // picturesque_after_entry ?>