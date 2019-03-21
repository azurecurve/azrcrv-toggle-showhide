=== Toggle Show/Hide ===
Contributors: azurecurve
Tags: XXX
Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/
Donate link: https://development.azurecurve.co.uk/support-development/
Requires at least: 1.0.0
Tested up to: 1.0.0
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Toggle to show/hide content (allows custom title).

== Description ==
Toggle shortcode can be used to show/hide content.

[toggle]content[/toggle] to use toggle in basic format; parameters are read from settings.

The following parameters can be used:
<ul>
* <strong>title</strong> -  e.g. 
* <strong>expand</strong> - set to 1 to display toggle open; 0 to display toggle closed. e.g. [toggle expand=1]content[/toggle]
* <strong>width</strong> - override width from settings. e.g. [toggle width=75%]content[/toggle]
* <strong>border</strong> - override border from settings. e.g. [toggle border='none']content[/toggle] or [toggle border='1px dashed #FF0000']content[/toggle]
* <strong>title_color</strong> - override title color from settings. e.g. [toggle title_color='#000']content[/toggle]
* <strong>title_font</strong> - override title font family from settings. e.g. [toggle title_font='Arial, Calibri']content[/toggle]
* <strong>title_font_size</strong> - override title font size from settings. e.g. [toggle title_font_size='14px']content[/toggle]
* <strong>title_font_weight</strong> - override title font weight from settings. e.g. [toggle title_font_weight=600]content[/toggle]
* <strong>bgtitle</strong> - override text background colour from settings. e.g. [toggle bgtitle='#007FFF']content[/toggle]
* <strong>text_color</strong> - override text colour from settings. e.g. [toggle bgtext='#000']content[/toggle]
* <strong>text_font</strong> - override text font family from settings. e.g. [toggle text_font='Arial, Calibri']content[/toggle]
* <strong>text_font_size </strong> - override text font size from settings. e.g. [toggle text_font_size='14px']content[/toggle]
* <strong>text_font_weight </strong> - override text font weight from settings. e.g. [toggle text_font_weight=600]content[/toggle]
* <strong>bgtext</strong> - override text background colour from settings. e.g. [toggle bgtext='#000']content[/toggle]
* <strong>disable_image</strong> - set to 1 to disable toggle image. e.g. [toggle disable_image=1]content[/toggle]
</ul>

Shortcodes can now be used inside the content or title of the toggle (tested with Contact Form 7 and azurecurve BBCode).

Select toggle image in options or network options; allows different sites in a network to use different images. Extra images can be added by dropping them into the plugins /images folder

This plugin is multisite compatible; each site can be set to use network settings or locally defined ones.

== Installation ==
To install the Toggle Show/Hide plugin:
* Download the plugin from <a href='https://github.com/azurecurve/azrcrv-toggle-showhide/'>GitHub</a>.
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Changelog ==
Changes and feature additions for the Toggle Show/Hide plugin:
= 1.0.0 =
* First version for ClassicPress forked from azurecurve Toggle Show/Hide WordPress Plugin.

== Frequently Asked Questions ==
= Can I translate this plugin? =
* Yes, the .pot fie is in the plugin's languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk; if you do translate this plugin please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).
= Is this plugin compatible with both WordPress and ClassicPress? =
* This plugin is developed for ClassicPress, but will likely work on WordPress.