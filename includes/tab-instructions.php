<?php
/*
	instructions tab
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
?>

<h2><?php esc_html_e( 'Instructions', 'azrcrv-tsh' ); ?></h2>

<p><?php esc_html_e( 'This plugin adds a [toggle] shortcode which either shows a clickable title that shows/hides a block of content (Style 1), or an expandable "read more" block (Style 2).', 'azrcrv-tsh' ); ?></p>

<h3><?php esc_html_e( 'Style 1 - Show/Hide', 'azrcrv-tsh' ); ?></h3>
<p><?php esc_html_e( 'Wrap content in a [toggle]...[/toggle] shortcode. A clickable title is shown; clicking it reveals the wrapped content.', 'azrcrv-tsh' ); ?></p>
<p><code>[toggle title="Click here to show/hide"]Content goes here.[/toggle]</code></p>
<p><?php esc_html_e( 'Any of the fields on the Style 1 tab can be overridden per-shortcode with a matching attribute, e.g.:', 'azrcrv-tsh' ); ?></p>
<p><code>[toggle title="More details" title_color="#000000" border="1px solid #007FFF" expand="1"]Content goes here.[/toggle]</code></p>
<p><?php esc_html_e( 'Set expand="1" to have the content shown by default, with the title acting as a collapse control instead.', 'azrcrv-tsh' ); ?></p>

<h3><?php esc_html_e( 'Style 2 - Read More', 'azrcrv-tsh' ); ?></h3>
<p>
	<?php
	printf(
		/* translators: %s: the <!--readmore--> marker, in a <code> tag. */
		esc_html__( 'Add style="2" and place a %s marker in the content at the point it should be cut off; a Read More button is shown, which expands to reveal the rest.', 'azrcrv-tsh' ),
		'<code>&lt;!--readmore--&gt;</code>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup only.
	);
	?>
</p>
<p><code>[toggle style="2"]Visible summary text.&lt;!--readmore--&gt;The rest of the content.[/toggle]</code></p>

<h3><?php esc_html_e( 'Shortcodes within the toggle', 'azrcrv-tsh' ); ?></h3>
<p><?php esc_html_e( 'Turn on "Allow Shortcodes?" on the Settings tab to have shortcodes within the toggle title and content expanded.', 'azrcrv-tsh' ); ?></p>

<h3><?php esc_html_e( 'Shortcode support in widgets', 'azrcrv-tsh' ); ?></h3>
<p>
	<?php
	printf(
		/* translators: %s: link to the Shortcodes in Widgets plugin. */
		esc_html__( 'This shortcode does not run in widgets by default - for that, see our sister plugin, %s.', 'azrcrv-tsh' ),
		'<a href="https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/">Shortcodes in Widgets</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, developer-controlled URL and text.
	);
	?>
</p>
