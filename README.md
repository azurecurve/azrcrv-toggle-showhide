# [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/)
![Plugin Banner](/assets/pluginimages/banner-1544x500.png)

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

# Installation Instructions

* Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/).
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * Avatars - [details](https://development.azurecurve.co.uk/classicpress-plugins/avatars/) / [download](https://github.com/azurecurve/azrcrv-avatars/releases/latest/)
 * Check Plugin Status - [details](https://development.azurecurve.co.uk/classicpress-plugins/check-plugin-status/) / [download](https://github.com/azurecurve/azrcrv-check-plugin-status/releases/latest/)
 * Estimated Read Time - [details](https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/) / [download](https://github.com/azurecurve/azrcrv-estimated-read-time/releases/latest/)
 * Floating Featured Image - [details](https://development.azurecurve.co.uk/classicpress-plugins/floating-featured-image/) / [download](https://github.com/azurecurve/azrcrv-floating-featured-image/releases/latest/)
 * Icons - [details](https://development.azurecurve.co.uk/classicpress-plugins/icons/) / [download](https://github.com/azurecurve/azrcrv-icons/releases/latest/)
 * Loop Injection - [details](https://development.azurecurve.co.uk/classicpress-plugins/loop-injection/) / [download](https://github.com/azurecurve/azrcrv-loop-injection/releases/latest/)
 * Page Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/page-index/) / [download](https://github.com/azurecurve/azrcrv-page-index/releases/latest/)
 * SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
 * Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
 * Username Protection - [details](https://development.azurecurve.co.uk/classicpress-plugins/username-protection/) / [download](https://github.com/azurecurve/azrcrv-username-protection/releases/latest/)