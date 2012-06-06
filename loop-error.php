<?php
/**
 * Loop Error Template
 *
 * Displays an error message when no posts are found.
 *
 * @package MyLife
 * @subpackage Template
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2011, Justin Tadlock
 * @link http://themehybrid.com/themes/my-life
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
?>
	<div id="post-0" class="<?php hybrid_entry_class(); ?>">

		<div class="entry-content">

			<p><?php _e( 'Apologies, but no entries were found.', 'my-life' ); ?></p>

		</div><!-- .entry-content -->

	</div><!-- .hentry .error -->