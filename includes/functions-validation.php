<?php
/*
	CSS / attribute value validators.

	PRD s5: the pre-2.0.0 plugin built inline style="..." attributes by
	concatenating shortcode-attribute (and admin-option) values directly into
	a single string, escaping only the *whole* assembled string with
	esc_html(). esc_html() encodes quotes but not ";", "(", ")", "{", "}" -
	so a value such as:

		title_color="red;position:fixed;top:0;left:0;...background:#fff;"

	closes the current CSS declaration and injects arbitrary new ones,
	without ever needing to break out of the style="" attribute itself.

	The fix here is an allow-list, not a blacklist: every value that ends up
	in an inline style="" attribute (or a <style> block, for the Style 2
	button colours) is validated against a strict pattern for what it is
	actually supposed to contain. Anything that doesn't match is discarded
	and the caller's default is used instead - values are never "cleaned up"
	or partially kept, since silently repairing bad input is how these
	bypasses reappear later.

	These same validators are applied both when settings are saved
	(functions-settings.php) AND at shortcode render time
	(functions-shortcodes.php). Render-time validation is the one that
	actually matters for security - it also covers option values saved by
	the pre-2.0.0 plugin before this upgrade, which only ever passed through
	sanitize_text_field() (which does not strip ";") and so may already
	contain an injection payload.
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ToggleShowHide;

/**
 * Prevent direct access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * A small allow-list of CSS keyword colours, on top of hex colours. Kept
 * deliberately short - covers what the settings page's colour picker and
 * reasonable manual entry would produce; anything else should be a hex
 * value.
 */
function get_allowed_css_colour_keywords() {
	return array(
		'black',
		'white',
		'red',
		'green',
		'blue',
		'yellow',
		'orange',
		'purple',
		'pink',
		'brown',
		'grey',
		'gray',
		'silver',
		'gold',
		'navy',
		'teal',
		'transparent',
		'inherit',
		'currentcolor',
	);
}

/**
 * Validate a CSS colour: 3/6-digit hex (#abc / #aabbcc) or a keyword from
 * get_allowed_css_colour_keywords(). Returns $default if invalid or empty.
 */
function sanitize_css_colour( $value, $default = '' ) {

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return $default;
	}

	if ( preg_match( '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $value ) ) {
		return $value;
	}

	if ( in_array( strtolower( $value ), get_allowed_css_colour_keywords(), true ) ) {
		return strtolower( $value );
	}

	return $default;
}

/**
 * Validate a CSS font-family value: letters, digits, spaces, commas,
 * hyphens and apostrophes only (a font stack such as "Georgia, 'Times New
 * Roman', serif"). Returns $default if invalid or empty.
 */
function sanitize_css_font_family( $value, $default = '' ) {

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return $default;
	}

	if ( preg_match( "/^[A-Za-z0-9 ,'\\-]{1,100}$/", $value ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate a CSS length (font-size, width): a number followed by an allowed
 * unit, or bare "0". Returns $default if invalid or empty.
 */
function sanitize_css_length( $value, $default = '' ) {

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return $default;
	}

	if ( '0' === $value ) {
		return $value;
	}

	if ( preg_match( '/^\d{1,4}(\.\d{1,3})?(px|em|rem|%|pt|vh|vw)$/', $value ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate a CSS font-weight: a keyword or the numeric 100-900 steps.
 * Returns $default if invalid or empty.
 */
function sanitize_css_font_weight( $value, $default = '' ) {

	$value = strtolower( trim( (string) $value ) );

	if ( '' === $value ) {
		return $default;
	}

	$allowed = array( 'normal', 'bold', 'bolder', 'lighter', '100', '200', '300', '400', '500', '600', '700', '800', '900' );

	if ( in_array( $value, $allowed, true ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate a CSS border shorthand: "<length><unit> <style> <colour>", or
 * "none". Returns $default if invalid or empty.
 */
function sanitize_css_border( $value, $default = '' ) {

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return $default;
	}

	if ( 'none' === strtolower( $value ) ) {
		return 'none';
	}

	$styles = 'solid|dashed|dotted|double|groove|ridge|inset|outset';
	$colour = '#[A-Fa-f0-9]{3}|#[A-Fa-f0-9]{6}|' . implode( '|', get_allowed_css_colour_keywords() );

	$pattern = '/^\d{1,3}(\.\d{1,2})?(px|em|rem)\s+(' . $styles . ')\s+(' . $colour . ')$/i';

	if ( preg_match( $pattern, $value ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate a value against a fixed set of allowed strings (enums such as
 * image_location). Returns $default if not an exact match.
 */
function sanitize_enum( $value, array $allowed, $default = '' ) {

	$value = trim( (string) $value );

	if ( in_array( $value, $allowed, true ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate a value as a boolean-ish flag (accepts 1/0, "1"/"0", true/false).
 * Always returns int 1 or 0.
 */
function sanitize_flag( $value ) {
	return ! empty( $value ) && '0' !== $value ? 1 : 0;
}

/**
 * Validate the HTML tag used to wrap the toggle title.
 *
 * Fixes a more serious bug than the CSS-injection issue above: the
 * pre-2.0.0 plugin inserted the title_tag shortcode attribute directly as
 * the literal HTML tag name (`"<".esc_html($title_tag)." class=...`)
 * without any allow-list. esc_html() does not encode spaces or "=", so a
 * value such as:
 *
 *   title_tag="h3 onmouseover=alert(document.cookie)"
 *
 * was inserted completely unescaped as-is, injecting an arbitrary HTML
 * attribute - including event-handler attributes - directly into the
 * rendered tag. This is a straightforward stored XSS vector via a
 * shortcode attribute, with no HTML tags required in the input at all, and
 * is fixed here with a strict allow-list of real tag names rather than any
 * attempt at escaping.
 */
function sanitize_html_tag_name( $value, $default = 'h3' ) {

	$value = strtolower( trim( (string) $value ) );

	$allowed = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p', 'strong' );

	if ( in_array( $value, $allowed, true ) ) {
		return $value;
	}

	return $default;
}

/**
 * Validate an image filename against the actual .png files shipped in
 * assets/images (excluding the icon and banner images used for the
 * plugin's own listing, not toggle icons), the same allow-list the
 * settings page radio-button picker is built from. Returns $default if the
 * value isn't one of those files.
 */
function sanitize_image_filename( $value, $default = '' ) {

	$value = trim( (string) $value );

	if ( '' === $value ) {
		return $default;
	}

	if ( in_array( $value, get_available_toggle_images(), true ) ) {
		return $value;
	}

	return $default;
}

/**
 * List the .png files in assets/images available for use as the toggle's
 * open/close icon (excluding the icon and banner files, which are the
 * plugin's own listing images, not toggle icons).
 */
function get_available_toggle_images() {

	static $images = null;

	if ( null !== $images ) {
		return $images;
	}

	$images = array();
	$dir    = plugin_dir_path( \azurecurve\ToggleShowHide\PLUGIN_FILE ) . 'assets/images';

	if ( is_dir( $dir ) ) {
		foreach ( scandir( $dir ) as $file ) {
			if ( 'png' !== strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ) ) {
				continue;
			}
			if ( str_starts_with( $file, 'icon' ) || str_starts_with( $file, 'banner' ) ) {
				continue;
			}
			$images[] = $file;
		}
	}

	return $images;
}

/**
 * Build a "prop: value; prop2: value2;" inline style string from an
 * associative array of already-validated property => value pairs (empty
 * values are skipped). The result is additionally passed through
 * esc_attr() as a defence-in-depth final pass before being placed in a
 * style="" attribute.
 */
function build_style_attribute( array $declarations ) {

	$parts = array();

	foreach ( $declarations as $property => $value ) {
		if ( '' === $value || null === $value ) {
			continue;
		}
		$parts[] = $property . ': ' . $value;
	}

	if ( empty( $parts ) ) {
		return '';
	}

	return esc_attr( implode( '; ', $parts ) . ';' );
}
