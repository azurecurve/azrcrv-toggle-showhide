<?php
/*
	activation / deactivation functions
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
 * Plugin activation. Settings are created lazily on first read via
 * wp_parse_args() against the built-in defaults (see get_settings() in
 * functions-settings.php), so there is nothing to set up here. Kept as a
 * real function so future setup steps have somewhere to go without a
 * structural change.
 */
function activate_plugin() {
	// Nothing to do yet.
}

/**
 * Plugin deactivation. Deliberately does NOT delete the settings option -
 * deactivating a plugin should not lose the admin's configuration; that only
 * happens on uninstall (see uninstall.php).
 */
function deactivate_plugin() {
	// Nothing to do yet.
}
