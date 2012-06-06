<?php
/**
 * The functions file is used to initialize everything in the theme.  It controls how the theme is loaded and 
 * sets up the supported features, default actions, and default filters.  If making customizations, users 
 * should create a child theme and make changes to its functions.php file (not this one).  Friends don't let 
 * friends modify parent theme files. ;)
 *
 * Child themes should do their setup on the 'after_setup_theme' hook with a priority of 11 if they want to
 * override parent theme features.  Use a priority of 9 if wanting to run before the parent theme.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package MyLife
 * @subpackage Functions
 * @version 0.1.0
 * @since 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2011, Justin Tadlock
 * @link http://themehybrid.com/themes/my-life
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'picturesque_theme_setup' );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 0.1.0
 */
function picturesque_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();

	/* Add theme support for core framework features. */
	add_theme_support( 'hybrid-core-menus', array( 'primary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-sidebars', array( 'primary', 'subsidiary' ) );
	add_theme_support( 'hybrid-core-widgets' );
	add_theme_support( 'hybrid-core-shortcodes' );
	add_theme_support( 'hybrid-core-theme-settings', array( 'about', 'footer' ) );
	add_theme_support( 'hybrid-core-drop-downs' );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	//add_theme_support( 'hybrid-core-seo' );

	/* Add theme support for framework extensions. */
	add_theme_support( 'theme-layouts', array( '1c', '2c-l', '2c-r' ) );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'dev-stylesheet' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'get-the-image' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'cleaner-gallery' );
	add_theme_support( 'cleaner-caption' );

	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-formats', array( 'image', 'gallery' ) );
	add_custom_background( 'picturesque_custom_background_callback' );

	/**
	 * Add support for WordPress custom header image.  On that note, this is horrible and messy.  Expect a 
	 * rewrite of this entire code into something more beautiful in future versions.
	 */
	add_custom_image_header( '__return_false', '__return_false' );
	define( 'NO_HEADER_TEXT', true );
	define( 'HEADER_IMAGE', 'remove-header' ); // Setting the default to 'remove-header' instead of empty string.
	define( 'HEADER_IMAGE_WIDTH', 1050 );
	define( 'HEADER_IMAGE_HEIGHT', 200 );

	/* Embed width/height defaults. */
	add_filter( 'embed_defaults', 'picturesque_embed_defaults' );

	/* Set content width. */
	hybrid_set_content_width( 600 );

	/* Filter the [wp-caption] width. */
	add_filter( 'cleaner_caption_args', 'picturesque_caption_args' );

	/* Filter the sidebar widgets. */
	add_filter( 'sidebars_widgets', 'picturesque_disable_sidebars' );
	add_action( 'template_redirect', 'picturesque_one_column' );

	/* Add classes to the comments pagination. */
	add_filter( 'previous_comments_link_attributes', 'picturesque_previous_comments_link_attributes' );
	add_filter( 'next_comments_link_attributes', 'picturesque_next_comments_link_attributes' );

	/* Add custom image sizes. */
	add_action( 'init', 'picturesque_add_image_sizes' );

	/* Wraps <blockquote> around quote posts. */
	add_filter( 'the_content', 'picturesque_quote_content' );

	/* Adds the featured image to image posts if no content is found. */
	add_filter( 'the_content', 'picturesque_image_content' );

	/* Add custom <body> classes. */
	add_filter( 'body_class', 'picturesque_body_class' );

	/* Filter the header image on singular views. */
	add_filter( 'theme_mod_header_image', 'picturesque_header_image' );

	/* Registers custom shortcodes. */
	add_action( 'init', 'picturesque_register_shortcodes' );

	/* Simplifies the taxonomy template name for post formats. */
	add_filter( 'taxonomy_template', 'picturesque_taxonomy_template' );

	/* Filters the image/gallery post format archive galleries. */
	add_filter( "{$prefix}_post_format_archive_gallery_columns", 'picturesque_archive_gallery_columns' );
}

function picturesque_caption_args( $args ) {

	$args['width'] = intval( $args['width'] ) + 10;

	return $args;
}

/**
 * Sets the number of columns to show on image and gallery post format archives pages based on the 
 * layout that is currently being used.
 *
 * @since 0.1.0
 * @param int $columns Number of gallery columns to display.
 * @return int $columns
 */
function picturesque_archive_gallery_columns( $columns ) {

	/* Only run the code if the theme supports the 'theme-layouts' feature. */
	if ( current_theme_supports( 'theme-layouts' ) ) {

		/* Get the current theme layout. */
		$layout = theme_layouts_get_layout();

		if ( 'layout-1c' == $layout )
			$columns = 4;

		elseif ( in_array( $layout, array( 'layout-3c-l', 'layout-3c-r', 'layout-3c-c' ) ) )
			$columns = 2;
	}

	return $columns;
}

/**
 * Filter for the "theme_mod_header_image" hook, which returns the header image URL.  This allows the user 
 * to change the header image on a per-post basis by uploading a feature image large enough to display as a
 * header image.
 *
 * @since 0.1.0
 * @param string $url The URL of the current header image.
 * @return string $url
 */
function picturesque_header_image( $url ) {

	if ( is_singular() && 'remove-header' !== $url ) {

		$post_id = get_queried_object_id();

		if ( is_attachment() && wp_attachment_is_image( $post_id ) )
			$thumbnail_id = $post_id;

		elseif ( has_post_thumbnail( $post_id ) )
			$thumbnail_id = get_post_thumbnail_id( $post_id );

		if ( !empty( $thumbnail_id ) ) {

			$image = wp_get_attachment_image_src( $thumbnail_id, 'header' );

			if ( $image[1] >= HEADER_IMAGE_WIDTH && $image[2] >= HEADER_IMAGE_HEIGHT )
				$url = $image[0];
		}
	}

	return $url;
}

/**
 * Wraps the output of the quote post format content in a <blockquote> element if the user hasn't added a 
 * <blockquote> in the post editor.
 *
 * @since 0.1.0
 * @param string $content The post content.
 * @return string $content
 */
function picturesque_quote_content( $content ) {

	if ( has_post_format( 'quote' ) ) {
		preg_match( '/<blockquote.*?>/', $content, $matches );

		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;
}

/**
 * Returns the featured image for the image post format if the user didn't add any content to the post.
 *
 * @since 0.1.0
 * @param string $content The post content.
 * @return string $content
 */
function picturesque_image_content( $content ) {

	if ( has_post_format( 'image' ) && '' == $content ) {
		if ( is_singular() )
			$content = get_the_image( array( 'size' => 'full', 'meta_key' => false, 'link_to_post' => false ) );
		else
			$content = get_the_image( array( 'size' => 'full', 'meta_key' => false ) );
	}

	return $content;
}

/**
 * Grabs the first URL from the post content of the current post.  This is meant to be used with the link post 
 * format to easily find the link for the post. 
 *
 * @since 0.1.0
 * @return string The link if found.  Otherwise, the permalink to the post.
 *
 * @note This is a modified version of the twentyeleven_url_grabber() function in the TwentyEleven theme.
 * @author wordpressdotorg
 * @copyright Copyright (c) 2011, wordpressdotorg
 * @link http://wordpress.org/extend/themes/twentyeleven
 * @license http://wordpress.org/about/license
 */
function picturesque_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return get_permalink( get_the_ID() );

	return esc_url_raw( $matches[1] );
}

/**
 * Adds custom image sizes for featured images.  The 'feature' image size is used for sticky posts.
 *
 * @since 0.1.0
 */
function picturesque_add_image_sizes() {
	add_image_size( 'header', 1000, 200, true );
}

/**
 * Function for deciding which pages should have a one-column layout.
 *
 * @since 0.1.0
 */
function picturesque_one_column() {

	if ( !is_active_sidebar( 'primary' ) && !is_active_sidebar( 'secondary' ) )
		add_filter( 'get_theme_layout', 'picturesque_theme_layout_one_column' );

	elseif ( is_attachment() && 'layout-default' == theme_layouts_get_layout() )
		add_filter( 'get_theme_layout', 'picturesque_theme_layout_one_column' );
}

/**
 * Filters 'get_theme_layout' by returning 'layout-1c'.
 *
 * @since 0.1.0
 * @param string $layout The layout of the current page.
 * @return string
 */
function picturesque_theme_layout_one_column( $layout ) {
	return 'layout-1c';
}

/**
 * Disables sidebars if viewing a one-column page.
 *
 * @since 0.1.0
 * @param array $sidebars_widgets A multidimensional array of sidebars and widgets.
 * @return array $sidebars_widgets
 */
function picturesque_disable_sidebars( $sidebars_widgets ) {
	global $wp_query;

	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {

		if ( 'layout-1c' == theme_layouts_get_layout() ) {
			$sidebars_widgets['primary'] = false;
			$sidebars_widgets['secondary'] = false;
		}
	}

	return $sidebars_widgets;
}

/**
 * Overwrites the default widths for embeds.  This is especially useful for making sure videos properly
 * expand the full width on video pages.  This function overwrites what the $content_width variable handles
 * with context-based widths.
 *
 * @since 0.1.0
 */
function picturesque_embed_defaults( $args ) {

	$args['width'] = 600;

	if ( current_theme_supports( 'theme-layouts' ) ) {

		$layout = theme_layouts_get_layout();

		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout )
			$args['width'] = 470;
		elseif ( 'layout-1c' == $layout )
			$args['width'] = 808;
	}

	return $args;
}

/**
 * Adds 'class="prev" to the previous comments link.
 *
 * @since 0.1.0
 * @param string $attributes The previous comments link attributes.
 * @return string
 */
function picturesque_previous_comments_link_attributes( $attributes ) {
	return $attributes . ' class="prev"';
}

/**
 * Adds 'class="next" to the next comments link.
 *
 * @since 0.1.0
 * @param string $attributes The next comments link attributes.
 * @return string
 */
function picturesque_next_comments_link_attributes( $attributes ) {
	return $attributes . ' class="next"';
}

/**
 * Returns the number of images attached to the current post in the loop.
 *
 * @since 0.1.0
 * @return int
 */
function picturesque_get_image_attachment_count() {
	$images = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => -1 ) );
	return count( $images );
}

/**
 * Returns a set of image attachment links based on size.
 *
 * @since 0.1.0
 * @return string Links to various image sizes for the image attachment.
 */
function picturesque_get_image_size_links() {

	/* If not viewing an image attachment page, return. */
	if ( !wp_attachment_is_image( get_the_ID() ) )
		return;

	/* Set up an empty array for the links. */
	$links = array();

	/* Get the intermediate image sizes and add the full size to the array. */
	$sizes = get_intermediate_image_sizes();
	$sizes[] = 'full';

	/* Loop through each of the image sizes. */
	foreach ( $sizes as $size ) {

		/* Get the image source, width, height, and whether it's intermediate. */
		$image = wp_get_attachment_image_src( get_the_ID(), $size );

		/* Add the link to the array if there's an image and if $is_intermediate (4th array value) is true or full size. */
		if ( !empty( $image ) && ( true === $image[3] || 'full' == $size ) )
			$links[] = "<a class='image-size-link' href='" . esc_url( $image[0] ) . "'>{$image[1]} &times; {$image[2]}</a>";
	}

	/* Join the links in a string and return. */
	return join( ' <span class="sep">/</span> ', $links );
}

/**
 * This is a fix for when a user sets a custom background color with no custom background image.  What 
 * happens is the theme's background image hides the user-selected background color.  If a user selects a 
 * background image, we'll just use the WordPress custom background callback.
 *
 * @since 0.1.0
 * @link http://core.trac.wordpress.org/ticket/16919
 */
function picturesque_custom_background_callback() {

	/* Get the background image. */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color. */
	$color = get_background_color();

	/* If no background color, return. */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";

?>
<style type="text/css">body.custom-background { <?php echo trim( $style ); ?> }</style>
<?php

}

/** ====== Hybrid Core 1.3.0 functionality. ====== **/

/**
 * Fix for Hybrid Core until version 1.3.0 is released.  This adds the '.custom-background' class to the <body> 
 * element for the WordPress custom background feature.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 * @link http://core.trac.wordpress.org/ticket/18698
 */
function picturesque_body_class( $classes ) {

	if ( get_background_image() || get_background_color() )
		$classes[] = 'custom-background';

	if ( is_tax( 'post_format' ) )
		$classes = array_map( 'picturesque_clean_post_format_slug', $classes );

	return $classes;
}

/**
 * Removes 'post-format-' from the taxonomy template name for post formats.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 */
function picturesque_taxonomy_template( $template ) {

	$term = get_queried_object();

	if ( 'post_format' == $term->taxonomy ) {

		$slug = picturesque_clean_post_format_slug( $term->slug );

		$has_template = locate_template( array( "taxonomy-{$term->taxonomy}-{$slug}.php" ) );

		if ( $has_template )
			$template = $has_template;
	}

	return $template;
}

/**
 * Add functionality to Hybrid Core 1.3.0.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 */
function picturesque_clean_post_format_slug( $slug ) {
	return str_replace( 'post-format-', '', $slug );
}

/**
 * Registers the [post-format-link] and [entry-permalink] shortcodes.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 */
function picturesque_register_shortcodes() {
	add_shortcode( 'post-format-link', 'picturesque_post_format_link_shortcode' );
	add_shortcode( 'entry-permalink', 'picturesque_entry_permalink_shortcode' );
}

/**
 * Returns the output of the [post-format-link] shortcode.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 * @param array $attr The shortcode arguments.
 * @return string A link to the post format archive.
 */
function picturesque_post_format_link_shortcode( $attr ) {

	$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );
	$format = get_post_format();
	$url = ( empty( $format ) ? get_permalink() : get_post_format_link( $format ) );

	return $attr['before'] . '<a href="' . esc_url( $url ) . '" class="post-format-link">' . get_post_format_string( $format ) . '</a>' . $attr['after'];
}

/**
 * Returns the output of the [entry-permalink] shortcode.
 *
 * @since 0.1.0
 * @todo Remove once theme is upgraded to Hybrid Core 1.3.0.
 * @param array $attr The shortcode arguments.
 * @return string A permalink back to the post.
 */
function picturesque_entry_permalink_shortcode( $attr ) {

	$attr = shortcode_atts( array( 'before' => '', 'after' => '' ), $attr );

	return $attr['before'] . '<a href="' . esc_url( get_permalink() ) . '" class="permalink">' . __( 'Permalink', 'my-life' ) . '</a>' . $attr['after'];
}

/**
 * Displays an attachment image's metadata and exif data while viewing a singular attachment page.
 *
 * Note: This function will most likely be restructured completely in the future.  The eventual plan is to 
 * separate each of the elements into an attachment API that can be used across multiple themes.  Keep 
 * this in mind if you plan on using the current filter hooks in this function.
 *
 * @since 0.1.0
 */
function retro_fitted_image_info() {

	/* Set up some default variables and get the image metadata. */
	$meta = wp_get_attachment_metadata( get_the_ID() );
	$items = array();
	$list = '';

	/* Add the width/height to the $items array. */
	$items['dimensions'] = sprintf( __( '<span class="prep">Dimensions:</span> %s', hybrid_get_textdomain() ), '<span class="image-data"><a href="' . esc_url( wp_get_attachment_url() ) . '">' . sprintf( __( '%1$s &#215; %2$s pixels', hybrid_get_textdomain() ), $meta['width'], $meta['height'] ) . '</a></span>' );

	/* If a timestamp exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['created_timestamp'] ) )
		$items['created_timestamp'] = sprintf( __( '<span class="prep">Date:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . date( get_option( 'date_format' ), $meta['image_meta']['created_timestamp'] ) . '</span>' );

	/* If a camera exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['camera'] ) )
		$items['camera'] = sprintf( __( '<span class="prep">Camera:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . $meta['image_meta']['camera'] . '</span>' );

	/* If an aperture exists, add it to the $items array. */
	if ( !empty( $meta['image_meta']['aperture'] ) )
		$items['aperture'] = sprintf( __( '<span class="prep">Aperture:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( 'f/%s', hybrid_get_textdomain() ), $meta['image_meta']['aperture'] ) . '</span>' );

	/* If a focal length is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['focal_length'] ) )
		$items['focal_length'] = sprintf( __( '<span class="prep">Focal Length:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( '%s mm', hybrid_get_textdomain() ), $meta['image_meta']['focal_length'] ) . '</span>' );

	/* If an ISO is set, add it to the $items array. */
	if ( !empty( $meta['image_meta']['iso'] ) )
		$items['iso'] = sprintf( __( '<span class="prep">ISO:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . $meta['image_meta']['iso'] . '</span>' );

	/* If a shutter speed is given, format the float into a fraction and add it to the $items array. */
	if ( !empty( $meta['image_meta']['shutter_speed'] ) ) {

		if ( ( 1 / $meta['image_meta']['shutter_speed'] ) > 1 ) {
			$shutter_speed = '1/';

			if ( number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1 ) ==  number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0 ) )
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 0, '.', '' );
			else
				$shutter_speed .= number_format( ( 1 / $meta['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = $meta['image_meta']['shutter_speed'];
		}

		$items['shutter_speed'] = sprintf( __( '<span class="prep">Shutter Speed:</span> %s', hybrid_get_textdomain() ), '<span class="image-data">' . sprintf( __( '%s sec', hybrid_get_textdomain() ), $shutter_speed ) . '</span>' );
	}

	/* Allow devs to overwrite the array of items. */
	$items = apply_atomic( 'image_info_items', $items );

	/* Loop through the items, wrapping each in an <li> element. */
	foreach ( $items as $item )
		$list .= "<li>{$item}</li>";

	/* Format the HTML output of the function. */
	$output = '<div class="image-info"><h3>' . __( 'Image Info', hybrid_get_textdomain() ) . '</h3><ul>' . $list . '</ul></div>';

	/* Display the image info and allow devs to overwrite the final output. */
	echo apply_atomic( 'image_info', $output );
}

?>