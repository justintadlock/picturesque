<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
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
				<?php get_sidebar( 'primary' ); // Loads the sidebar-primary.php template. ?>

				<?php do_atomic( 'close_main' ); // picturesque_close_main ?>

			</div><!-- .wrap -->

		</div><!-- #main -->

		<?php do_atomic( 'after_main' ); // picturesque_after_main ?>

		<?php do_atomic( 'before_footer' ); // picturesque_before_footer ?>

		<footer id="footer">

			<?php do_atomic( 'open_footer' ); // picturesque_open_footer ?>

			<div class="wrap">

				<div class="footer-content">
					<?php hybrid_footer_content(); ?>
				</div>

				<?php do_atomic( 'footer' ); // picturesque_footer ?>

			</div><!-- .wrap -->

			<?php do_atomic( 'close_footer' ); // picturesque_close_footer ?>

		</footer><!-- #footer -->

		<?php do_atomic( 'after_footer' ); // picturesque_after_footer ?>

		<?php get_template_part( 'menu', 'subsidiary' ); // Loads the menu-subsidiary.php template. ?>

	</div><!-- #container -->

	<?php do_atomic( 'close_body' ); // picturesque_close_body ?>

	<?php wp_footer(); // wp_footer ?>

</body>
</html>