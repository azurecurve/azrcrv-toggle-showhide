=== Toggle Show/Hide ===

Description:	Toggle shortcode can be used to show/hide content.
Version:		1.6.2
Tags:			toggle,show/hide
Author:			azurecurve
Contributors:	azurecurve,Norskes
Author URI:		https://development.azurecurve.co.uk/
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/
Download link:	https://github.com/azurecurve/azrcrv-toggle-showhide/releases/download/v1.6.2/azrcrv-toggle-showhide.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	toggle-showhide
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Toggle shortcode can be used to show/hide content.

== Description ==

# Description

Toggle shortcode can be used to show/hide content.

`[toggle style=1]content[/toggle]` to use toggle in basic format; parameters are read from settings.

Two toggle styles are supported:
 * `Style 1` is the traditional toggle show/hide.
 * `Style 2` is a read more toggle.

Apply a parameter of `style=1/2` to select the the type of toggle (e.g. `[toggle style=2]content[/toggle]`); a default parameter can be set so a style only needs to be defined if the othe style toggle is required.

The following parameters can be used for the standard toggle:
 * `title` -  default title text of the toggle.
 * `expand` - set to 1 to display toggle open; 0 to display toggle closed. e.g. `[toggle expand=1]content[/toggle]`
 * `width` - override width from settings. e.g. `[toggle width=75%]content[/toggle]`
 * `border` - override border from settings. e.g. `[toggle border='none']content[/toggle] or `[toggle border='1px dashed #FF0000']content[/toggle]`
 * `title_color` - override title color from settings. e.g. `[toggle title_color='#000']content[/toggle]`
 * `title_font` - override title font family from settings. e.g. `[toggle title_font='Arial, Calibri']content[/toggle]`
 * `title_font_size` - override title font size from settings. e.g. `[toggle title_font_size='14px']content[/toggle]`
 * `title_font_weight` - override title font weight from settings. e.g. `[toggle title_font_weight=600]content[/toggle]`
 * `bgtitle` - override text background colour from settings. e.g. `[toggle bgtitle='#007FFF']content[/toggle]`
 * `text_color` - override text colour from settings. e.g. `[toggle bgtext='#000']content[/toggle]`
 * `text_font` - override text font family from settings. e.g. `[toggle text_font='Arial, Calibri']content[/toggle]`
 * `text_font_size ` - override text font size from settings. e.g. `[toggle text_font_size='14px']content[/toggle]`
 * `text_font_weight ` - override text font weight from settings. e.g. `[toggle text_font_weight=600]content[/toggle]`
 * `bgtext` - override text background colour from settings. e.g. `[toggle bgtext='#000']content[/toggle]`
 * `disable_image` - set to 1 to disable toggle image. e.g. `[toggle disable_image=1]content[/toggle]`
 * `image_location` - set to left or right to override default. e.g. `[toggle image_location='right']content[/toggle]`

Select toggle image in options or network options; allows different sites in a network to use different images.

Shortcodes can now be used inside the content or title of the toggle if the relevant option is set.

When using a read more toggle, apply the `<!--readmore-->` tag where the readmore button should be placed. e.g. `[toggle style=2]content<!--readmore-->content[/toggle]`

This plugin is multisite compatible; each site can be set to use network settings or locally defined ones.

== Installation ==

# Installation Instructions

* Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/).
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog
 
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

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for ClassicPress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

The other plugins available from **azurecurve** are:
 * Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
 * Add Twitter Cards - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/) / [download](https://github.com/azurecurve/azrcrv-add-twitter-cards/releases/latest/)
 * Avatars - [details](https://development.azurecurve.co.uk/classicpress-plugins/avatars/) / [download](https://github.com/azurecurve/azrcrv-avatars/releases/latest/)
 * BBCode - [details](https://development.azurecurve.co.uk/classicpress-plugins/bbcode/) / [download](https://github.com/azurecurve/azrcrv-bbcode/releases/latest/)
 * Breadcrumbs - [details](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/) / [download](https://github.com/azurecurve/azrcrv-breadcrumbs/releases/latest/)
 * Call-out Boxes - [details](https://development.azurecurve.co.uk/classicpress-plugins/call-out-boxes/) / [download](https://github.com/azurecurve/azrcrv-call-out-boxes/releases/latest/)
 * Check Plugin Status - [details](https://development.azurecurve.co.uk/classicpress-plugins/check-plugin-status/) / [download](https://github.com/azurecurve/azrcrv-check-plugin-status/releases/latest/)
 * Code - [details](https://development.azurecurve.co.uk/classicpress-plugins/code/) / [download](https://github.com/azurecurve/azrcrv-code/releases/latest/)
 * Comment Validator - [details](https://development.azurecurve.co.uk/classicpress-plugins/comment-validator/) / [download](https://github.com/azurecurve/azrcrv-comment-validator/releases/latest/)
 * Conditional Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/conditional-links/) / [download](https://github.com/azurecurve/azrcrv-conditional-links/releases/latest/)
 * Contact Forms - [details](https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/) / [download](https://github.com/azurecurve/azrcrv-contact-forms/releases/latest/)
 * Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/) / [download](https://github.com/azurecurve/azrcrv-disable-floc/releases/latest/)
 * Display After Post Content - [details](https://development.azurecurve.co.uk/classicpress-plugins/display-after-post-content/) / [download](https://github.com/azurecurve/azrcrv-display-after-post-content/releases/latest/)
 * Estimated Read Time - [details](https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/) / [download](https://github.com/azurecurve/azrcrv-estimated-read-time/releases/latest/)
 * Events - [details](https://development.azurecurve.co.uk/classicpress-plugins/events/) / [download](https://github.com/azurecurve/azrcrv-events/releases/latest/)
 * Filtered Categories - [details](https://development.azurecurve.co.uk/classicpress-plugins/filtered-categories/) / [download](https://github.com/azurecurve/azrcrv-filtered-categories/releases/latest/)
 * Flags - [details](https://development.azurecurve.co.uk/classicpress-plugins/flags/) / [download](https://github.com/azurecurve/azrcrv-flags/releases/latest/)
 * Floating Featured Image - [details](https://development.azurecurve.co.uk/classicpress-plugins/floating-featured-image/) / [download](https://github.com/azurecurve/azrcrv-floating-featured-image/releases/latest/)
 * From Twitter - [details](https://development.azurecurve.co.uk/classicpress-plugins/from-twitter/) / [download](https://github.com/azurecurve/azrcrv-from-twitter/releases/latest/)
 * Gallery From Folder - [details](https://development.azurecurve.co.uk/classicpress-plugins/gallery-from-folder/) / [download](https://github.com/azurecurve/azrcrv-gallery-from-folder/releases/latest/)
 * Get GitHub File - [details](https://development.azurecurve.co.uk/classicpress-plugins/get-github-file/) / [download](https://github.com/azurecurve/azrcrv-get-github-file/releases/latest/)
 * Icons - [details](https://development.azurecurve.co.uk/classicpress-plugins/icons/) / [download](https://github.com/azurecurve/azrcrv-icons/releases/latest/)
 * Images - [details](https://development.azurecurve.co.uk/classicpress-plugins/images/) / [download](https://github.com/azurecurve/azrcrv-images/releases/latest/)
 * Insult Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/insult-generator/) / [download](https://github.com/azurecurve/azrcrv-insult-generator/releases/latest/)
 * Load Admin CSS - [details](https://development.azurecurve.co.uk/classicpress-plugins/load-admin-css/) / [download](https://github.com/azurecurve/azrcrv-load-admin-css/releases/latest/)
 * Loop Injection - [details](https://development.azurecurve.co.uk/classicpress-plugins/loop-injection/) / [download](https://github.com/azurecurve/azrcrv-loop-injection/releases/latest/)
 * Maintenance Mode - [details](https://development.azurecurve.co.uk/classicpress-plugins/maintenance-mode/) / [download](https://github.com/azurecurve/azrcrv-maintenance-mode/releases/latest/)
 * Markdown - [details](https://development.azurecurve.co.uk/classicpress-plugins/markdown/) / [download](https://github.com/azurecurve/azrcrv-markdown/releases/latest/)
 * Mobile Detection - [details](https://development.azurecurve.co.uk/classicpress-plugins/mobile-detection/) / [download](https://github.com/azurecurve/azrcrv-mobile-detection/releases/latest/)
 * Multisite Favicon - [details](https://development.azurecurve.co.uk/classicpress-plugins/multisite-favicon/) / [download](https://github.com/azurecurve/azrcrv-multisite-favicon/releases/latest/)
 * Nearby - [details](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) / [download](https://github.com/azurecurve/azrcrv-nearby/releases/latest/)
 * Page Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/page-index/) / [download](https://github.com/azurecurve/azrcrv-page-index/releases/latest/)
 * Post Archive - [details](https://development.azurecurve.co.uk/classicpress-plugins/post-archive/) / [download](https://github.com/azurecurve/azrcrv-post-archive/releases/latest/)
 * Redirect - [details](https://development.azurecurve.co.uk/classicpress-plugins/redirect/) / [download](https://github.com/azurecurve/azrcrv-redirect/releases/latest/)
 * Remove Revisions - [details](https://development.azurecurve.co.uk/classicpress-plugins/remove-revisions/) / [download](https://github.com/azurecurve/azrcrv-remove-revisions/releases/latest/)
 * RSS Feed - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-feed/) / [download](https://github.com/azurecurve/azrcrv-rss-feed/releases/latest/)
 * RSS Suffix - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-suffix/) / [download](https://github.com/azurecurve/azrcrv-rss-suffix/releases/latest/)
 * Series Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/series-index/) / [download](https://github.com/azurecurve/azrcrv-series-index/releases/latest/)
 * Shortcodes in Comments - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-comments/releases/latest/)
 * Shortcodes in Widgets - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-widgets/releases/latest/)
 * Sidebar Login - [details](https://development.azurecurve.co.uk/classicpress-plugins/sidebar-login/) / [download](https://github.com/azurecurve/azrcrv-sidebar-login/releases/latest/)
 * SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
 * Snippets - [details](https://development.azurecurve.co.uk/classicpress-plugins/snippets/) / [download](https://github.com/azurecurve/azrcrv-snippets/releases/latest/)
 * Strong Password Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/strong-password-generator/) / [download](https://github.com/azurecurve/azrcrv-strong-password-generator/releases/latest/)
 * Tag Cloud - [details](https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/) / [download](https://github.com/azurecurve/azrcrv-tag-cloud/releases/latest/)
 * Taxonomy Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-index/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-index/releases/latest/)
 * Taxonomy Order - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-order/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-order/releases/latest/)
 * Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
 * Timelines - [details](https://development.azurecurve.co.uk/classicpress-plugins/timelines/) / [download](https://github.com/azurecurve/azrcrv-timelines/releases/latest/)
 * To Twitter - [details](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/) / [download](https://github.com/azurecurve/azrcrv-to-twitter/releases/latest/)
 * Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
 * URL Shortener - [details](https://development.azurecurve.co.uk/classicpress-plugins/url-shortener/) / [download](https://github.com/azurecurve/azrcrv-url-shortener/releases/latest/)
 * Username Protection - [details](https://development.azurecurve.co.uk/classicpress-plugins/username-protection/) / [download](https://github.com/azurecurve/azrcrv-username-protection/releases/latest/)
 * Widget Announcements - [details](https://development.azurecurve.co.uk/classicpress-plugins/widget-announcements/) / [download](https://github.com/azurecurve/azrcrv-widget-announcements/releases/latest/)
 