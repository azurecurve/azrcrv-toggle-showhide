<?php
/*
	[toggle] / [TOGGLE] shortcode registration and renderers.

	Every value that ends up in an inline style="" attribute below - whether
	it came from the shortcode's own attributes or from the saved settings -
	is passed through the matching allow-list validator in functions-validation.php
	immediately before use. This is the layer that actually closes the
	injection issue described in PRD s5: validating only at settings-save
	time would leave every option value saved by the pre-2.0.0 plugin (which
	only ever ran sanitize_text_field(), which does not strip ";") still
	exploitable after upgrading, since no migration/re-save of existing
	settings happens automatically.
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

add_shortcode( 'toggle', __NAMESPACE__ . '\\display_toggle' );
add_shortcode( 'TOGGLE', __NAMESPACE__ . '\\display_toggle' );

/**
 * Shortcode dispatcher - picks Style 1 (show/hide box) or Style 2 (read
 * more) based on the 'style' attribute, falling back to the site default.
 */
function display_toggle( $atts, $content = null ) {

	// Ensures the front-end interaction script loads whenever [toggle]
	// genuinely renders - including when it's produced dynamically by
	// another plugin's own shortcode (e.g. a recipe card shortcode that
	// wraps its instructions in [toggle]...[/toggle]) rather than being
	// typed directly into the page. See enqueue_front_end_assets() in
	// functions-scripts.php for why this isn't done via a content pre-scan
	// instead.
	enqueue_toggle_script();

	$options = get_settings();

	$atts = is_array( $atts ) ? $atts : array();

	$args  = shortcode_atts(
		array(
			'style' => $options['default-style'],
		),
		$atts
	);
	$style = sanitize_enum( (string) $args['style'], array( '1', '2' ), (string) $options['default-style'] );

	if ( '1' === $style ) {
		return display_toggle_style1( $atts, $content, $options );
	}

	return display_toggle_style2( $atts, $content, $options );
}

/**
 * Render Style 1: a clickable title that shows/hides a block of content.
 */
function display_toggle_style1( $atts, $content, $options ) {

	$args = shortcode_atts(
		array(
			'title'             => $options['title'],
			'title_color'       => $options['title_color'],
			'title_font'        => $options['title_font'],
			'title_font_size'   => $options['title_font_size'],
			'title_font_weight' => $options['title_font_weight'],
			'title_tag'         => $options['title_tag'],
			'expand'            => 0,
			'border'            => $options['border'],
			'bgtitle'           => $options['bg_title'],
			'bgtext'            => $options['bg_text'],
			'text_color'        => $options['text_color'],
			'text_font'         => $options['text_font'],
			'text_font_size'    => $options['text_font_size'],
			'text_font_weight'  => $options['text_font_weight'],
			'disable_image'     => $options['disable_image'],
			'width'             => $options['width'],
			'image_location'    => $options['image_location'],
		),
		$atts
	);

	// --- Validate every value before it can reach a style="" attribute or
	// the literal tag name (see functions-validation.php for what each allows). ---
	$title_tag         = sanitize_html_tag_name( $args['title_tag'], 'h3' );
	$title_color       = sanitize_css_colour( $args['title_color'] );
	$title_font        = sanitize_css_font_family( $args['title_font'] );
	$title_font_size   = sanitize_css_length( $args['title_font_size'] );
	$title_font_weight = sanitize_css_font_weight( $args['title_font_weight'] );
	$expand            = sanitize_flag( $args['expand'] );
	$border            = sanitize_css_border( $args['border'] );
	$bgtitle           = sanitize_css_colour( $args['bgtitle'] );
	$bgtext            = sanitize_css_colour( $args['bgtext'] );
	$text_color        = sanitize_css_colour( $args['text_color'] );
	$text_font         = sanitize_css_font_family( $args['text_font'] );
	$text_font_size    = sanitize_css_length( $args['text_font_size'] );
	$text_font_weight  = sanitize_css_font_weight( $args['text_font_weight'] );
	$disable_image     = sanitize_flag( $args['disable_image'] );
	$width             = sanitize_css_length( $args['width'] );
	$image_location    = sanitize_enum( $args['image_location'], array( 'left', 'right' ), 'left' );

	// --- Title text. ---
	$title = trim( (string) $args['title'] );
	if ( '' === $title ) {
		$title = __( 'Click to show/hide', 'azrcrv-tsh' );
	}

	if ( 1 === (int) $options['allow_shortcodes'] ) {
		// Fixes a bug in the pre-2.0.0 plugin: it ran do_shortcode() on the
		// title but then still esc_html()'d the *result*, so any HTML a
		// nested shortcode produced was displayed as literal escaped text
		// rather than rendered - making "Allow Shortcodes" a no-op for the
		// title. wp_kses_post() is used instead of esc_html() here so the
		// shortcode's rendered markup actually displays, while still
		// blocking script tags/event-handler attributes.
		$title   = wp_kses_post( do_shortcode( $title ) );
		$content = do_shortcode( $content );
	} else {
		$title = esc_html( $title );
	}

	// --- Assemble style="" attributes from validated declarations only. ---
	$expand_suffix        = $expand ? '-open' : '';
	$expand_active_suffix = $expand ? '-open-active' : '';

	$image_location_css = ( 'right' === $image_location )
		? array(
			'background-position' => 'right 10px center',
			'padding-left'        => '10px',
		)
		: array();

	$disable_image_css = $disable_image
		? array(
			'background-image' => 'none !important',
			'padding-left'     => '10px',
		)
		: array();

	$title_wrapper_style = build_style_attribute(
		array_merge(
			array(
				'border'            => $border,
				'background-color'  => $bgtitle,
			),
			$disable_image_css,
			$image_location_css
		)
	);

	$title_link_style = build_style_attribute(
		array(
			'color'       => $title_color,
			'font-family' => $title_font,
			'font-size'   => $title_font_size,
			'font-weight' => $title_font_weight,
		)
	);

	$container_style = build_style_attribute(
		array(
			'border'           => $border,
			'background-color' => $bgtext,
			'color'            => $text_color,
			'font-family'      => $text_font,
			'font-size'        => $text_font_size,
			'font-weight'      => $text_font_weight,
		)
	);

	$output  = '<' . $title_tag . " class='azrcrv-tsh-toggle" . esc_attr( $expand_active_suffix ) . "'";
	$output .= '' !== $title_wrapper_style ? " style='" . $title_wrapper_style . "'" : '';
	$output .= '>';
	$output .= "<a href='#'" . ( '' !== $title_link_style ? " style='" . $title_link_style . "'" : '' ) . '>' . $title . '</a>';
	$output .= '</' . $title_tag . '>';

	$output .= "<div class='azrcrv-tsh-toggle-container" . esc_attr( $expand_suffix ) . "'";
	$output .= '' !== $container_style ? " style='" . $container_style . "'" : '';
	$output .= '>' . $content . '</div>';

	if ( '' !== $width ) {
		$width_style = build_style_attribute(
			array(
				'margin' => 'auto',
				'width'  => $width,
			)
		);
		$output      = '<div style="' . $width_style . '">' . $output . '</div>';
	}

	return $output;
}

/**
 * Render Style 2: a "read more" expandable block, split on the
 * <!--readmore--> marker in the shortcode content.
 */
function display_toggle_style2( $atts, $content, $options ) {

	if ( 1 === (int) $options['allow_shortcodes'] ) {
		$content = do_shortcode( $content );
	}

	$content_split = explode( '<!--readmore-->', (string) $content );

	// Fixes a bug in the pre-2.0.0 plugin: the Style 2 button's
	// background/text colours (and hover colours) were configurable on the
	// settings page but never actually applied anywhere at render time -
	// the setting existed but silently did nothing. They're now emitted as
	// a small scoped <style> block, built only from values that have
	// already passed sanitize_css_colour() (hex or a fixed keyword
	// list) - which matters more here than in a style="" attribute, since a
	// <style> block lets an unvalidated value close the rule early with "}"
	// and inject entirely new, unrelated selectors rather than just extra
	// declarations.
	if ( ! empty( $content_split[1] ) ) {

		$button_text = esc_html( $options['style2']['read-more'] );
		$content_one = $content_split[0];
		$content_two = $content_split[1];

		$output  = "<style>.azrcrv-tsh-readmore-button{background-color:" . sanitize_css_colour( $options['style2']['button']['background-color'], '#FFFFFF' )
					. ';color:' . sanitize_css_colour( $options['style2']['button']['color'], '#007FFF' )
					. '}.azrcrv-tsh-readmore-button:hover{background-color:' . sanitize_css_colour( $options['style2']['button-hover']['background-color'], '#007FFF' )
					. ';color:' . sanitize_css_colour( $options['style2']['button-hover']['color'], '#FFFFFF' )
					. '}</style>';
		$output .= "<p class='azrcrv-tsh-container'>" . $content_one . "<div class='azrcrv-tsh-readmore'>" . $content_two . '</div>';
		$output .= "<a class='azrcrv-tsh-readmore-button' href='#' data-read-more='" . esc_attr( $options['style2']['read-more'] ) . "' data-read-less='" . esc_attr( $options['style2']['read-less'] ) . "'>" . $button_text . '</a></p>';
		$output .= '<p style="clear: both;"></p>';
	} else {
		$output = $content;
	}

	return $output;
}
