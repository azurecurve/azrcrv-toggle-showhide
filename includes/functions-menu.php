<?php
/*
	menu functions - admin menu registration. The admin-post save handler
	and admin page renderer live in functions-settings.php, alongside the
	functions they call.

	This plugin is reachable only via the shared azurecurve cross-plugin
	menu (registered in azurecurve-menu-display.php), not its own top-level
	ClassicPress admin menu page - matching the pre-2.0.0 plugin's
	navigation.
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
 * Add settings link on the Plugins list page.
 */
function add_plugin_action_link( $links, $file ) {

	$this_plugin = PLUGIN_SLUG . '/' . PLUGIN_SLUG . '.php';

	if ( $file === $this_plugin ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=' . PLUGIN_HYPHEN ) ) . '"><img src="' . esc_url( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />' . esc_html__( 'Settings', 'azrcrv-tsh' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

/**
 * Add this plugin's entry under the shared azurecurve cross-plugin menu.
 */
function create_admin_menu() {

	add_submenu_page(
		'azrcrv-plugin-menu',
		esc_html__( 'Toggle Show/Hide Settings', 'azrcrv-tsh' ),
		esc_html__( 'Toggle Show/Hide', 'azrcrv-tsh' ),
		'manage_options',
		PLUGIN_HYPHEN,
		__NAMESPACE__ . '\\display_admin_page'
	);
}
