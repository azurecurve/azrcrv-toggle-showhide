<?php
/*
	admin + front-end script/style enqueue functions.

	Neither jQuery nor jQuery UI is enqueued anywhere in this plugin. The
	pre-2.0.0 plugin depended on WP core's 'jquery' handle for both its
	front-end toggle interaction and its admin tab-switching, and separately
	enqueued the full jQuery UI Tabs library (assets/css/jquery-ui*.css,
	libraries/jquery-ui/*.js) despite never actually calling a jQuery UI
	widget anywhere (the tabs were hand-rolled with plain jQuery click
	handlers) - so the jQuery UI load was dead weight even before this
	rewrite. Both are replaced with small dependency-free vanilla-JS files:
	assets/js/toggle.js (front end) and assets/js/admin-standard.js (admin
	tabs), matching the same approach already used in Shortcodes in
	Comments 2.0.0.
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
 * Enqueue admin CSS/JS, only on this plugin's own admin page or the shared
 * azurecurve cross-plugin menu page.
 *
 * Fixes a bug in the pre-2.0.0 plugin, which read $_GET['page'] without an
 * isset() check (a PHP notice on any admin screen with no 'page' query var)
 * and without sanitisation.
 */
function enqueue_admin_assets( $hook ) {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only page identifier, not a state-changing action.
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

	if ( PLUGIN_HYPHEN !== $page && 'azrcrv-plugin-menu' !== $page ) {
		return;
	}

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin-standard',
		plugins_url( 'assets/css/admin-standard.css', PLUGIN_FILE ),
		array(),
		'2.0.0'
	);

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin-pluginmenu',
		plugins_url( 'assets/css/admin-pluginmenu.css', PLUGIN_FILE ),
		array(),
		'2.0.0'
	);

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-admin',
		plugins_url( 'assets/css/admin.css', PLUGIN_FILE ),
		array( PLUGIN_HYPHEN . '-admin-standard' ),
		'2.0.0'
	);

	wp_enqueue_script(
		PLUGIN_HYPHEN . '-admin-standard',
		plugins_url( 'assets/js/admin-standard.js', PLUGIN_FILE ),
		array(),
		'2.0.0',
		true
	);

	wp_enqueue_script(
		PLUGIN_HYPHEN . '-admin',
		plugins_url( 'assets/js/admin.js', PLUGIN_FILE ),
		array( PLUGIN_HYPHEN . '-admin-standard' ),
		'2.0.0',
		true
	);
}

/**
 * Enqueue front-end CSS, and *register* (but do not yet enqueue) the
 * front-end JS.
 *
 * The JS is deliberately not enqueued here. An earlier version of this
 * function tried to detect whether the current page was likely to render a
 * [toggle] shortcode by scanning $post->post_content with has_shortcode()
 * before deciding whether to load toggle.js. That only works when [toggle]
 * is literally typed into the stored post content - it misses every case
 * where a *different* plugin's own shortcode (e.g. a recipe card, a
 * product tab block, etc.) generates [toggle] markup dynamically at render
 * time, since the literal text "[toggle]" is never present in post_content
 * for has_shortcode() to find. On such pages the CSS (enqueued
 * unconditionally, correctly) loaded, but the JS silently didn't, leaving
 * a dead, unclickable toggle.
 *
 * Instead, the script is registered here (available, but not printed
 * unless enqueued) and actually enqueued from inside display_toggle()
 * itself (functions-shortcodes.php), i.e. only once the shortcode has
 * genuinely executed - regardless of whether it was typed directly into
 * the page or produced by another plugin's shortcode. Because the script
 * is registered to load in the footer, enqueuing it during content
 * rendering (which happens before wp_footer fires) still works correctly.
 */
function enqueue_front_end_assets() {

	wp_enqueue_style(
		PLUGIN_HYPHEN . '-style',
		plugins_url( 'assets/css/style.css', PLUGIN_FILE ),
		array(),
		'2.0.0'
	);

	$custom_css = get_custom_icon_css();
	if ( '' !== $custom_css ) {
		wp_add_inline_style( PLUGIN_HYPHEN . '-style', $custom_css );
	}

	wp_register_script(
		PLUGIN_HYPHEN . '-toggle',
		plugins_url( 'assets/js/toggle.js', PLUGIN_FILE ),
		array(),
		'2.0.0',
		true
	);
}

/**
 * Enqueue the front-end toggle interaction script. Called from
 * display_toggle() (functions-shortcodes.php) the moment a [toggle]
 * shortcode actually renders, rather than being guessed at in advance -
 * see enqueue_front_end_assets() above for why. Safe to call more than
 * once per page (e.g. multiple toggles): wp_enqueue_script() is a no-op on
 * a handle that's already enqueued.
 */
function enqueue_toggle_script() {
	wp_enqueue_script( PLUGIN_HYPHEN . '-toggle' );
}

/**
 * Build the CSS overriding the default open/close toggle icons with the
 * admin's chosen images, if any. Both values are re-validated here (in
 * addition to at settings-save time) against the actual list of shipped
 * icon files, since existing installs upgrading from the pre-2.0.0 plugin
 * will have saved values that were never validated this way.
 */
function get_custom_icon_css() {

	$settings = get_settings();

	$image_open  = sanitize_image_filename( $settings['image_open'], '' );
	$image_close = sanitize_image_filename( $settings['image_close'], '' );

	$css = '';

	if ( '' !== $image_close ) {
		$url  = esc_url_raw( plugins_url( 'assets/images/' . $image_close, PLUGIN_FILE ) );
		$css .= '.azrcrv-tsh-toggle-active{background-image:url(' . $url . ') !important;}';
		$css .= '.azrcrv-tsh-toggle-open-active{background-image:url(' . $url . ');}';
	}

	if ( '' !== $image_open ) {
		$url  = esc_url_raw( plugins_url( 'assets/images/' . $image_open, PLUGIN_FILE ) );
		$css .= '.azrcrv-tsh-toggle{background-image:url(' . $url . ');}';
		$css .= '.azrcrv-tsh-toggle-open{background-image:url(' . $url . ') !important;}';
	}

	return $css;
}
