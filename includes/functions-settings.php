<?php
/*
	settings functions.

	NOTE on the multisite "Network Settings" feature present in the
	pre-2.0.0 plugin: it has been removed. Auditing the old plugin found it
	was entirely dead code - azrcrv_tsh_create_network_admin_menu() was
	never hooked to 'network_admin_menu', and azrcrv_tsh_save_network_options()
	was never hooked to any 'admin_post_*' action, so the Network Settings
	page could never actually be reached or saved through the admin UI. Had
	a super-admin somehow reached get_site_option('azrcrv-tsh') with it
	enabled anyway, the result bypassed the defaults-merging every other
	settings read goes through, producing PHP warnings on every front-end
	render of a [toggle] shortcode. Rather than wire up ~300 lines of
	never-reachable, already-buggy duplicate settings UI, this mirrors the
	same call made in Shortcodes in Comments 2.0.0 for the equivalent
	(and, there, confirmed non-functional) feature: removed, with each site
	always using its own settings.
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
 * The plugin's built-in default values. Keys are unchanged from the
 * pre-2.0.0 plugin (other than the removed 'use_multisite' key - see note
 * above) so upgrading keeps existing settings with no migration step.
 */
function get_builtin_settings() {
	return array(
		'default-style'     => 1,
		'title_tag'         => '',
		'title'             => '',
		'title_color'       => '',
		'title_font'        => '',
		'title_font_size'   => '',
		'title_font_weight' => '',
		'allow_shortcodes'  => 0,
		'border'            => '',
		'bg_title'          => '',
		'bg_text'           => '',
		'text_color'        => '',
		'text_font'         => '',
		'text_font_size'    => '',
		'text_font_weight'  => '',
		'disable_image'     => 0,
		'width'             => '',
		'image_open'        => 'blue_control_play_down.png',
		'image_close'       => 'blue_control_play_up.png',
		'image_location'    => 'left',
		'style2'            => array(
			'read-more'    => __( 'Read more...', 'azrcrv-tsh' ),
			'read-less'    => __( 'Read less...', 'azrcrv-tsh' ),
			'button'       => array(
				'background-color' => '#FFFFFF',
				'color'             => '#007FFF',
			),
			'button-hover' => array(
				'background-color' => '#007FFF',
				'color'             => '#FFFFFF',
			),
		),
	);
}

/**
 * Get the saved settings, merged over the built-in defaults so every key is
 * always present even for a fresh (or partially-saved, pre-upgrade)
 * install. Fixes a bug in the pre-2.0.0 plugin's own merge helper, which
 * only worked correctly if get_option()'s own $default argument happened to
 * be used (i.e. only on a site that had genuinely never saved the option) -
 * wp_parse_args() here is applied unconditionally to whatever was stored.
 */
function get_settings() {

	$stored = get_option( SETTINGS_OPTION_NAME, array() );

	if ( ! is_array( $stored ) ) {
		$stored = array();
	}

	$settings = wp_parse_args( $stored, get_builtin_settings() );

	// Merge the nested style2 array too, so a pre-2.0.0 install that saved
	// only some of the style2 sub-keys still gets defaults for the rest.
	$settings['style2'] = wp_parse_args(
		is_array( $settings['style2'] ) ? $settings['style2'] : array(),
		get_builtin_settings()['style2']
	);
	$settings['style2']['button']       = wp_parse_args(
		is_array( $settings['style2']['button'] ) ? $settings['style2']['button'] : array(),
		get_builtin_settings()['style2']['button']
	);
	$settings['style2']['button-hover'] = wp_parse_args(
		is_array( $settings['style2']['button-hover'] ) ? $settings['style2']['button-hover'] : array(),
		get_builtin_settings()['style2']['button-hover']
	);

	return $settings;
}

/**
 * Persist the settings.
 */
function save_settings( $settings ) {
	update_option( SETTINGS_OPTION_NAME, $settings );
}

/**
 * Build a sanitized settings array from a submitted settings form ($_POST).
 *
 * The pre-2.0.0 plugin had a single settings form covering every tab, saved
 * with one submit button. This plugin instead gives each tab (Settings /
 * Style 1 / Style 2) its own form and save button - so each submission only
 * contains that one tab's fields. To avoid a partial save wiping out the
 * other tabs' previously-saved values (or, for checkboxes specifically,
 * treating "not present because it's on a different tab" the same as
 * "present but unchecked"), submissions are merged onto the *current*
 * saved settings ($current), and only the fields belonging to the
 * submitted tab (identified by the 'tsh-tab' hidden field each tab's form
 * includes) are updated - explicitly, so an unchecked checkbox on the
 * submitted tab is correctly saved as off, while checkboxes on other tabs
 * are left untouched.
 *
 * Every value that can end up in an inline style="" or <style> block is run
 * through the matching allow-list validator from functions-validation.php - see
 * PRD s5. This closes the injection hole at save time; it is also enforced
 * again at shortcode render time (functions-shortcodes.php) since existing
 * saved settings from before this upgrade were never validated this way.
 */
function sanitize_settings_from_post( $post_data ) {

	$current  = get_settings();
	$defaults = get_builtin_settings();
	$tab      = isset( $post_data['tsh-tab'] ) ? sanitize_key( $post_data['tsh-tab'] ) : '';

	$get = static function ( $key, $existing ) use ( $post_data ) {
		return array_key_exists( $key, $post_data ) ? sanitize_text_field( $post_data[ $key ] ) : $existing;
	};

	$settings = $current;

	if ( 'settings' === $tab ) {
		$settings['default-style']    = in_array( $get( 'default-style', (string) $current['default-style'] ), array( '1', '2' ), true ) ? (int) $get( 'default-style', (string) $current['default-style'] ) : $current['default-style'];
		$settings['allow_shortcodes'] = sanitize_flag( $post_data['allow_shortcodes'] ?? 0 );
	} elseif ( 'style1' === $tab ) {
		$settings['title_tag']         = sanitize_html_tag_name( $get( 'title_tag', $current['title_tag'] ), $defaults['title_tag'] ?: 'h3' );
		$settings['title']             = wp_strip_all_tags( $get( 'title', $current['title'] ) );
		$settings['title_color']       = sanitize_css_colour( $get( 'title_color', $current['title_color'] ) );
		$settings['title_font']        = sanitize_css_font_family( $get( 'title_font', $current['title_font'] ) );
		$settings['title_font_size']   = sanitize_css_length( $get( 'title_font_size', $current['title_font_size'] ) );
		$settings['title_font_weight'] = sanitize_css_font_weight( $get( 'title_font_weight', $current['title_font_weight'] ) );
		$settings['border']            = sanitize_css_border( $get( 'border', $current['border'] ) );
		$settings['bg_title']          = sanitize_css_colour( $get( 'bg_title', $current['bg_title'] ) );
		$settings['bg_text']           = sanitize_css_colour( $get( 'bg_text', $current['bg_text'] ) );
		$settings['text_color']        = sanitize_css_colour( $get( 'text_color', $current['text_color'] ) );
		$settings['text_font']         = sanitize_css_font_family( $get( 'text_font', $current['text_font'] ) );
		$settings['text_font_size']    = sanitize_css_length( $get( 'text_font_size', $current['text_font_size'] ) );
		$settings['text_font_weight']  = sanitize_css_font_weight( $get( 'text_font_weight', $current['text_font_weight'] ) );
		$settings['disable_image']     = sanitize_flag( $post_data['disable_image'] ?? 0 );
		$settings['width']             = sanitize_css_length( $get( 'width', $current['width'] ) );
		$settings['image_open']        = sanitize_image_filename( $get( 'image_open', $current['image_open'] ), $defaults['image_open'] );
		$settings['image_close']       = sanitize_image_filename( $get( 'image_close', $current['image_close'] ), $defaults['image_close'] );
		$settings['image_location']    = sanitize_enum( $get( 'image_location', $current['image_location'] ), array( 'left', 'right' ), 'left' );
	} elseif ( 'style2' === $tab ) {
		$settings['style2'] = array(
			'read-more'    => wp_strip_all_tags( $get( 'style2_read_more', $current['style2']['read-more'] ) ),
			'read-less'    => wp_strip_all_tags( $get( 'style2_read_less', $current['style2']['read-less'] ) ),
			'button'       => array(
				'background-color' => sanitize_css_colour( $get( 'style2_button_background_color', $current['style2']['button']['background-color'] ), $defaults['style2']['button']['background-color'] ),
				'color'             => sanitize_css_colour( $get( 'style2_button_color', $current['style2']['button']['color'] ), $defaults['style2']['button']['color'] ),
			),
			'button-hover' => array(
				'background-color' => sanitize_css_colour( $get( 'style2_button_hover_background_color', $current['style2']['button-hover']['background-color'] ), $defaults['style2']['button-hover']['background-color'] ),
				'color'             => sanitize_css_colour( $get( 'style2_button_hover_color', $current['style2']['button-hover']['color'] ), $defaults['style2']['button-hover']['color'] ),
			),
		);
	}

	return $settings;
}

/**
 * Handle the "Save Settings" form.
 *
 * Fixes several bugs present in the pre-2.0.0 save handler:
 *  - it called settings_fields() (which echoes output) from inside this
 *    admin-post handler, ahead of the later wp_redirect()/exit - dead code
 *    at best, a "headers already sent" failure at worst, since this handler
 *    doesn't use the Settings API that settings_fields() pairs with anyway;
 *  - its redirect URL passed "azrcrv-tsh&settings-updated" as the *value* of
 *    a single 'page' query arg, which add_query_arg() encodes as one query
 *    string value rather than two separate arguments, so the "Settings
 *    saved" notice could never actually display;
 *  - it used wp_redirect() rather than wp_safe_redirect().
 */
function handle_save_settings() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action.', 'azrcrv-tsh' ) );
	}

	if ( ! isset( $_POST[ PLUGIN_HYPHEN . '-nonce' ] ) || ! check_admin_referer( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ) ) {
		redirect_with_message( 'invalid-nonce' );
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce already verified above.
	$settings = sanitize_settings_from_post( wp_unslash( $_POST ) );

	save_settings( $settings );

	redirect_with_message( 'settings-saved' );
}
add_action( 'admin_post_' . PLUGIN_UNDERSCORE . '_save_settings', __NAMESPACE__ . '\\handle_save_settings' );

/**
 * Build a redirect URL back to the admin page with a status message.
 */
function redirect_with_message( $message_key, $extra_args = array() ) {
	$args = array_merge(
		array(
			'page'                      => PLUGIN_HYPHEN,
			PLUGIN_HYPHEN . '-message' => $message_key,
		),
		$extra_args
	);
	wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
	exit;
}

/**
 * Show a dismissible admin notice for a given message key, set via a
 * redirect query arg after a save action.
 */
function render_admin_notice( $message_key ) {

	$messages = array(
		'settings-saved' => array( 'success', __( 'Settings saved.', 'azrcrv-tsh' ) ),
		'invalid-nonce'  => array( 'error', __( 'Security check failed - please try again.', 'azrcrv-tsh' ) ),
	);

	if ( ! isset( $messages[ $message_key ] ) ) {
		return;
	}

	list( $type, $text ) = $messages[ $message_key ];
	$css_class            = 'success' === $type ? 'notice-success' : 'notice-error';

	echo '<div class="notice ' . esc_attr( $css_class ) . ' is-dismissible"><p>' . esc_html( $text ) . '</p></div>';
}

/**
 * Render the admin page (Settings/Style 1/Style 2/Instructions/Other
 * Plugins, in tabs).
 */
function display_admin_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-tsh' ) );
	}

	echo '<div class="wrap ' . esc_attr( PLUGIN_HYPHEN ) . '-wrap">';
	echo '<h1>';
		echo '<a href="' . esc_url_raw( DEVELOPER_RAW_LINK ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="' . esc_attr( DEVELOPER_NAME ) . '" /></a>';
		echo esc_html( get_admin_page_title() );
	echo '</h1>';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only status flag, not a state-changing action.
	if ( isset( $_GET[ PLUGIN_HYPHEN . '-message' ] ) ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$message_key = sanitize_key( wp_unslash( $_GET[ PLUGIN_HYPHEN . '-message' ] ) );
		render_admin_notice( $message_key );
	}

	require_once __DIR__ . '/tabs-output.php';

	echo '</div>';
}
