<?php
/*
	setup - registration of activation/deactivation hooks, actions and
	filters. The admin_post_azrcrv_tsh_save_settings handler is registered
	directly in functions-settings.php, alongside the functions it calls.
	The [toggle]/[TOGGLE] shortcodes are registered in
	functions-shortcodes.php, on 'init' via add_shortcode(), so they aren't
	repeated here either.
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

// Activation / deactivation.
register_activation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( PLUGIN_FILE, __NAMESPACE__ . '\\deactivate_plugin' );

// Admin menu.
add_action( 'admin_menu', __NAMESPACE__ . '\\create_admin_menu' );
add_filter( 'plugin_action_links_' . plugin_basename( PLUGIN_FILE ), __NAMESPACE__ . '\\add_plugin_action_link', 10, 2 );

// Update Manager: tell it where to find this plugin's own icon/banner
// images (assets/images) rather than falling back to a generic default.
// Uses the slug-scoped filter name (rather than the generic
// 'codepotent_update_manager_image_path'/'..._image_url' the pre-2.0.0
// plugin hooked) so this callback only ever runs for this plugin's own
// image lookups, not for every azurecurve plugin's.
$plugin_slug_for_um = plugin_basename( trim( PLUGIN_FILE ) );
add_filter( 'codepotent_update_manager_' . $plugin_slug_for_um . '_image_path', __NAMESPACE__ . '\\custom_image_path' );
add_filter( 'codepotent_update_manager_' . $plugin_slug_for_um . '_image_url', __NAMESPACE__ . '\\custom_image_url' );

// Admin assets.
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets' );

// Front-end assets.
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_front_end_assets' );

// Language.
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_languages' );
