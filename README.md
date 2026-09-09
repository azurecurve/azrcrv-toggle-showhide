# [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/)
![Plugin Banner](/assets/images/banner-1544x500.png)

Adds a `[toggle]` shortcode which displays a clickable title that shows/hides a block of content, or an expandable "read more" block.

## Description

Adds a `[toggle]` shortcode which displays a clickable title that shows/hides a block of content (Style 1), or an expandable "read more" block (Style 2).

Usage:
```
[toggle title="Click here to show/hide"]Content goes here.[/toggle]
```

```
[toggle style="2"]Visible summary text.<!--readmore-->The rest of the content.[/toggle]
```

See the Instructions tab on the plugin's settings page for the full list of per-shortcode attributes.

## Installation

* Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/).
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure the toggle's appearance via the configuration page in the admin control panel (azurecurve menu).

## Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugin's languages folder; if you do translate this plugin, please send the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

## Changelog

### [Version 2.0.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v2.0.0)

* Rebuilt to match the current azurecurve plugin architecture (namespaced code, settings split across includes files, tabbed admin UI, admin-post save handler).
* **Security:** fixed a CSS-injection vulnerability where shortcode attributes (and saved settings) such as `title_color`, `border`, `title_font_size` etc. were concatenated directly into `style="..."` attributes and only escaped as a whole string, which does not stop a value like `red;position:fixed;...` from injecting arbitrary extra CSS declarations. Every such value is now validated against a strict allow-list for what it's actually supposed to contain (colours, lengths, font names, etc.), both when settings are saved and at shortcode render time.
* **Security:** fixed a more serious issue in the same area - the `title_tag` shortcode attribute was inserted as the literal HTML tag name with no validation at all, so a value such as `h3 onmouseover=alert(1)` was output completely unescaped, injecting an arbitrary HTML attribute (including event handlers). `title_tag` is now restricted to a fixed list of real tag names.
* **Security:** fixed an unescaped admin-option-to-`<style>`-block injection in the old CSS loader, which built a global inline stylesheet from several settings values with no escaping of any kind. The one genuine feature it provided (custom open/close icon images) is preserved, built only from filenames already validated against the plugin's own shipped icon files.
* Fixed a bug where "Allow Shortcodes?" had no effect on the toggle title - shortcode output was expanded and then still HTML-escaped, so it never actually rendered as HTML.
* Fixed the Style 2 button/button-hover colour settings being saved but never actually applied to the rendered button.
* Fixed the default open/close icon images not displaying, due to a wrong file path and a filename typo (hyphen vs underscore) in the bundled CSS.
* Removed the multisite "Network Settings" feature - auditing found it was entirely dead code (the admin page was never hooked to `network_admin_menu`, and its save handler was never hooked to any `admin_post_*` action), so it could never actually be reached or saved through the admin UI.
* Replaced jQuery and jQuery UI (front end and admin) with small dependency-free vanilla-JS files; jQuery UI was previously loaded on every admin page for this plugin despite never actually being used (the tabs were hand-rolled with plain jQuery, not a jQuery UI widget).
* Fixed a bug where the settings save handler called `settings_fields()` (which echoes output) ahead of a later redirect, risking a "headers already sent" failure.
* Fixed a bug where the "Settings saved" redirect URL packed two query arguments into one, so the confirmation notice could never actually display.
* Fixed `$_GET['page']` being read without `isset()`/sanitisation in the admin-asset enqueue functions.
* Fixed an inefficient `plugin_action_links` hook and inefficient generic Update Manager image hooks, both of which ran for every plugin rather than just this one.
* Replaced the hard-coded, now-stale copy of the azurecurve plugin directory with the one shared across the current plugin family.

### [Version 1.6.5](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.5)

* Update plugin header for compatibility with ClasssicPress v2.

### [Version 1.6.4](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.4)

* Update plugin header and readme for compatibility with ClassicPress Directory v2.
* Update Update Manager to version 2.5.0.

### [Version 1.6.3](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.3)

* Update readme file for compatibility with ClassicPress Directory.

### [Version 1.6.2](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.2)

* Update readme files.
* Update language template.
* Fix bug with azurecurve menu.

### [Version 1.6.1](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.1)

* Update azurecurve menu.
* Update readme files.

### [Version 1.6.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.6.0)

* Tidy up some code on settings page.
* Remove jQuery UI tabs and add tabs using aria.
* Update translations to escape strings.

### [Version 1.5.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.5.0)

* Refactor settings page to be accessible using jQuery UI Tabs.

### [Version 1.4.1](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.4.1)

* Fix bug with read more toggle when no read more tag specified.
* Compress banners and icons.

### [Version 1.4.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.4.0)

* Add *Read More* toggle as second style.
* Add option to select default toggle style.
* Refactor settings page.

### [Version 1.3.1](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.3.1)

* Fix bug with missing text_font and text_font_size default options.
* Update azurecurve menu.

### [Version 1.3.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.3.0)

* Undo amendment of loading css and javascript only if shortcodes on the page as this causes issues as toggle is integrated into other plugins which use the shortcode within a shortcode.

### [Version 1.2.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.2.0)

* Fix plugin action link to use admin_url() function.
* Rewrite option handling so defaults not stored in database on plugin initialisation.
* Add plugin icon and banner.
* Update azurecurve plugin menu.
* Amend to only load css when shortcode on page.

### [Version 1.1.5](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.5)

* Fix bug with unassigned $background_title variable.

### [Version 1.1.4](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.4)

* Fix bug with setting of default options.
* Fix bug with plugin menu.
* Update plugin menu css.

### [Version 1.1.3](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.3)

* Fix bug with default title not being set correctly.
* Rewrite default option creation function to resolve several bugs.
* Upgrade azurecurve plugin to store available plugins in options.

### [Version 1.1.2](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.2)

* Update Update Manager class to v2.0.0.
* Update action link.
* Update azurecurve menu icon with compressed image.

### [Version 1.1.1](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.1)

* Fix bug with incorrect language load text domain.

### [Version 1.1.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.1.0)

* Add integration with Update Manager for automatic updates.
* Fix issue with display of azurecurve menu.
* Change settings page heading.
* Add load_plugin_textdomain to handle translations.

### [Version 1.0.1](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.0.1)

* Update azurecurve menu for easier maintenance.
* Move require of azurecurve menu below security check.
* Localization fixes and translation into Russian (contributed by @Norskes).

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/tag/v1.0.0)

* Initial release for ClassicPress forked from azurecurve Toggle Show/Hide WordPress Plugin.

## Other Notes

### About azurecurve

**azurecurve** was one of the first plugin developers to start developing for
Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/)
and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager)
for fully integrated, no hassle, updates.
