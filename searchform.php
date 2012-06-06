<?php
/**
 * Search Form Template
 *
 * The search form template displays the search form.
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
			<div class="search">

				<form method="get" class="search-form" action="<?php echo trailingslashit( home_url() ); ?>">
				<div>
					<input class="search-text" type="text" name="s" value="<?php if ( is_search() ) echo esc_attr( get_search_query() ); else esc_attr_e( 'Search', 'my-life' ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />
					<input class="search-submit button" name="submit" type="submit" value="<?php esc_attr_e( 'Search', 'my-life' ); ?>" />
				</div>
				</form><!-- .search-form -->

			</div><!-- .search -->