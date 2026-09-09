<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name:		Toggle Show/Hide
 * Description:		Adds a [toggle] shortcode which displays a clickable title that shows/hides content, or a "read more" expandable block.
 * Version:			2.0.0
 * Requires CP:		1.0
 * Requires PHP:	8.2
 * Author:			azurecurve
 * Author URI:		https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/
 * Donate link:		https://development.azurecurve.co.uk/support-development/
 * Text Domain:		azrcrv-tsh
 * Domain Path:		/assets/languages
 * License:			GPLv2 or later
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.html
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
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
 * Define constants.
 */
const DEVELOPER_SHORTNAME = 'azurecurve';
const DEVELOPER_NAME      = DEVELOPER_SHORTNAME . ' | Development';
const DEVELOPER_RAW_LINK  = 'https://development.azurecurve.co.uk/classicpress-plugins/';
const DEVELOPER_LINK      = '<a href="' . DEVELOPER_RAW_LINK . '">' . DEVELOPER_NAME . '</a>';

const PLUGIN_NAME       = 'Toggle Show/Hide';
const PLUGIN_SHORT_SLUG = 'toggle-showhide';
const PLUGIN_SLUG       = 'azrcrv-' . PLUGIN_SHORT_SLUG;
const PLUGIN_HYPHEN     = 'azrcrv-tsh';
const PLUGIN_UNDERSCORE = 'azrcrv_tsh';
const PLUGIN_FILE       = __FILE__;

// Option name under which settings are stored (see includes/functions-settings.php).
// Left as the same literal value ('azrcrv-tsh') used by the pre-2.0.0 plugin so
// upgrading keeps existing settings without any migration step.
const SETTINGS_OPTION_NAME = PLUGIN_HYPHEN;

/**
 * Load the remote update client, which integrates with azurecurve's own
 * Update Manager server so this plugin updates the same way as the rest of
 * the azurecurve plugin family.
 *
 * NOTE: per the upgrade PRD, this file is left completely untouched.
 */
require_once dirname( PLUGIN_FILE ) . '/libraries/updateclient/UpdateClient.class.php';

/**
 * Load activation/deactivation functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-activation.php';

/**
 * Load the shared value validators (CSS colours/lengths/fonts, HTML tag
 * names, enums) used to sanitize both the settings form and shortcode
 * attributes - see PRD s5, the CSS/attribute injection fix.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-validation.php';

/**
 * Load settings functions (get/save, admin-post save handler, admin page).
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-settings.php';

/**
 * Load the [toggle] / [TOGGLE] shortcode registration and renderers.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-shortcodes.php';

/**
 * Load admin menu functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-menu.php';

/**
 * Load custom plugin icon/banner path functions, used by Update Manager.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-plugin-images.php';

/**
 * Load the shared azurecurve cross-plugin menu: populates this plugin's
 * entry into the shared directory of azurecurve plugins, and (if not
 * already added by another azurecurve plugin on this site) registers the
 * shared top-level "azurecurve" admin menu page that lists them all.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-populate.php';
require_once dirname( PLUGIN_FILE ) . '/includes/azurecurve-menu-display.php';

/**
 * Load admin + front-end script/style enqueue functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-scripts.php';

/**
 * Load language functions.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/functions-language.php';

/**
 * Load setup of activation/deactivation hooks, actions and filters. This is
 * required last, since it references functions declared in the files above.
 */
require_once dirname( PLUGIN_FILE ) . '/includes/setup.php';
