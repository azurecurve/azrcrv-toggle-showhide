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

**azurecurve** was one of the first plugin developers to start developing for ClassicPress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

The plugins available from **azurecurve** are:

* Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
* Add Twitter Cards - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/) / [download](https://github.com/azurecurve/azrcrv-add-twitter-cards/releases/latest/)
* Avatars - [details](https://development.azurecurve.co.uk/classicpress-plugins/avatars/) / [download](https://github.com/azurecurve/azrcrv-avatars/releases/latest/)
* BBCode - [details](https://development.azurecurve.co.uk/classicpress-plugins/bbcode/) / [download](https://github.com/azurecurve/azrcrv-bbcode/releases/latest/)
* Breadcrumbs - [details](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/) / [download](https://github.com/azurecurve/azrcrv-breadcrumbs/releases/latest/)
* Broken Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/broken-links/) / [download](https://github.com/azurecurve/azrcrv-broken-links/releases/latest/)
* Call-out Boxes - [details](https://development.azurecurve.co.uk/classicpress-plugins/call-out-boxes/) / [download](https://github.com/azurecurve/azrcrv-call-out-boxes/releases/latest/)
* Chroma - [details](https://development.azurecurve.co.uk/classicpress-plugins/chroma/) / [download](https://github.com/azurecurve/azrcrv-chroma/releases/latest/)
* Code - [details](https://development.azurecurve.co.uk/classicpress-plugins/code/) / [download](https://github.com/azurecurve/azrcrv-code/releases/latest/)
* Comment Validator - [details](https://development.azurecurve.co.uk/classicpress-plugins/comment-validator/) / [download](https://github.com/azurecurve/azrcrv-comment-validator/releases/latest/)
* Conditional Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/conditional-links/) / [download](https://github.com/azurecurve/azrcrv-conditional-links/releases/latest/)
* Contact Forms - [details](https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/) / [download](https://github.com/azurecurve/azrcrv-contact-forms/releases/latest/)
* Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/) / [download](https://github.com/azurecurve/azrcrv-disable-floc/releases/latest/)
* Display After Post Content - [details](https://development.azurecurve.co.uk/classicpress-plugins/display-after-post-content/) / [download](https://github.com/azurecurve/azrcrv-display-after-post-content/releases/latest/)
* Estimated Read Time - [details](https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/) / [download](https://github.com/azurecurve/azrcrv-estimated-read-time/releases/latest/)
* Events - [details](https://development.azurecurve.co.uk/classicpress-plugins/events/) / [download](https://github.com/azurecurve/azrcrv-events/releases/latest/)
* Feed to Post - [details](https://development.azurecurve.co.uk/classicpress-plugins/feed-to-post/) / [download](https://github.com/azurecurve/azrcrv-feed-to-post/releases/latest/)
* Filtered Categories - [details](https://development.azurecurve.co.uk/classicpress-plugins/filtered-categories/) / [download](https://github.com/azurecurve/azrcrv-filtered-categories/releases/latest/)
* Flags - [details](https://development.azurecurve.co.uk/classicpress-plugins/flags/) / [download](https://github.com/azurecurve/azrcrv-flags/releases/latest/)
* Floating Featured Image - [details](https://development.azurecurve.co.uk/classicpress-plugins/floating-featured-image/) / [download](https://github.com/azurecurve/azrcrv-floating-featured-image/releases/latest/)
* Get GitHub File - [details](https://development.azurecurve.co.uk/classicpress-plugins/get-github-file/) / [download](https://github.com/azurecurve/azrcrv-get-github-file/releases/latest/)
* Icons - [details](https://development.azurecurve.co.uk/classicpress-plugins/icons/) / [download](https://github.com/azurecurve/azrcrv-icons/releases/latest/)
* Image Optimiser - [details](https://development.azurecurve.co.uk/classicpress-plugins/image-optimiser/) / [download](https://github.com/azurecurve/azrcrv-image-optimiser/releases/latest/)
* Images - [details](https://development.azurecurve.co.uk/classicpress-plugins/images/) / [download](https://github.com/azurecurve/azrcrv-images/releases/latest/)
* Insult Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/insult-generator/) / [download](https://github.com/azurecurve/azrcrv-insult-generator/releases/latest/)
* Load Admin CSS - [details](https://development.azurecurve.co.uk/classicpress-plugins/load-admin-css/) / [download](https://github.com/azurecurve/azrcrv-load-admin-css/releases/latest/)
* Loop Injection - [details](https://development.azurecurve.co.uk/classicpress-plugins/loop-injection/) / [download](https://github.com/azurecurve/azrcrv-loop-injection/releases/latest/)
* Lorem Ipsum Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/lorem-ipsum-generator/) / [download](https://github.com/azurecurve/azrcrv-lorem-ipsum-generator/releases/latest/)
* Maintenance Mode - [details](https://development.azurecurve.co.uk/classicpress-plugins/maintenance-mode/) / [download](https://github.com/azurecurve/azrcrv-maintenance-mode/releases/latest/)
* Markdown - [details](https://development.azurecurve.co.uk/classicpress-plugins/markdown/) / [download](https://github.com/azurecurve/azrcrv-markdown/releases/latest/)
* Nearby - [details](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) / [download](https://github.com/azurecurve/azrcrv-nearby/releases/latest/)
* Page Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/page-index/) / [download](https://github.com/azurecurve/azrcrv-page-index/releases/latest/)
* Post Archive - [details](https://development.azurecurve.co.uk/classicpress-plugins/post-archive/) / [download](https://github.com/azurecurve/azrcrv-post-archive/releases/latest/)
* Quiz Engine - [details](https://development.azurecurve.co.uk/classicpress-plugins/quiz-engine/) / [download](https://github.com/azurecurve/azrcrv-quiz-engine/releases/latest/)
* Read GitHub File - [details](https://development.azurecurve.co.uk/classicpress-plugins/read-github-file/) / [download](https://github.com/azurecurve/azrcrv-read-github-file/releases/latest/)
* Redirect - [details](https://development.azurecurve.co.uk/classicpress-plugins/redirect/) / [download](https://github.com/azurecurve/azrcrv-redirect/releases/latest/)
* Remove Revisions - [details](https://development.azurecurve.co.uk/classicpress-plugins/remove-revisions/) / [download](https://github.com/azurecurve/azrcrv-remove-revisions/releases/latest/)
* RSS Feed - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-feed/) / [download](https://github.com/azurecurve/azrcrv-rss-feed/releases/latest/)
* RSS Suffix - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-suffix/) / [download](https://github.com/azurecurve/azrcrv-rss-suffix/releases/latest/)
* Series Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/series-index/) / [download](https://github.com/azurecurve/azrcrv-series-index/releases/latest/)
* Shortcodes in Comments - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-comments/releases/latest/)
* Shortcodes in Widgets - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-widgets/releases/latest/)
* SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
* Snippets - [details](https://development.azurecurve.co.uk/classicpress-plugins/snippets/) / [download](https://github.com/azurecurve/azrcrv-snippets/releases/latest/)
* String Inspector - [details](https://development.azurecurve.co.uk/classicpress-plugins/string-inspector/) / [download](https://github.com/azurecurve/azrcrv-string-inspector/releases/latest/)
* Strong Password Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/strong-password-generator/) / [download](https://github.com/azurecurve/azrcrv-strong-password-generator/releases/latest/)
* Tag Cloud - [details](https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/) / [download](https://github.com/azurecurve/azrcrv-tag-cloud/releases/latest/)
* Taxonomy Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-index/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-index/releases/latest/)
* Taxonomy Order - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-order/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-order/releases/latest/)
* Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
* Timelines - [details](https://development.azurecurve.co.uk/classicpress-plugins/timelines) / [download](https://github.com/azurecurve/azrcrv-timelines/releases/latest/)
* Timelines - [details](https://development.azurecurve.co.uk/classicpress-plugins/timelines/) / [download](https://github.com/azurecurve/azrcrv-timelines/releases/latest/)
* Toggle Show/Hide - [details](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/) / [download](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/)
* Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
* URL Shortener - [details](https://development.azurecurve.co.uk/classicpress-plugins/url-shortener/) / [download](https://github.com/azurecurve/azrcrv-url-shortener/releases/latest/)
* Username Protection - [details](https://development.azurecurve.co.uk/classicpress-plugins/username-protection/) / [download](https://github.com/azurecurve/azrcrv-username-protection/releases/latest/)
* View Counter - [details](https://development.azurecurve.co.uk/classicpress-plugins/view-counter/) / [download](https://github.com/azurecurve/azrcrv-view-counter/releases/latest/)
* Widget Announcements - [details](https://development.azurecurve.co.uk/classicpress-plugins/widget-announcements/) / [download](https://github.com/azurecurve/azrcrv-widget-announcements/releases/latest/)