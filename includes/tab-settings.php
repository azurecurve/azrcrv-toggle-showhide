<?php
/*
	settings tab - general options.
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

$settings = get_settings();
?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

	<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_settings" />
	<input type="hidden" name="tsh-tab" value="settings" />
	<?php wp_nonce_field( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ); ?>

	<table class="form-table azrcrv-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Default Style', 'azrcrv-tsh' ); ?></th>
				<td>
					<select name="default-style">
						<option value="1" <?php selected( 1, (int) $settings['default-style'] ); ?>><?php esc_html_e( 'Style 1 - Show/Hide', 'azrcrv-tsh' ); ?></option>
						<option value="2" <?php selected( 2, (int) $settings['default-style'] ); ?>><?php esc_html_e( 'Style 2 - Read More', 'azrcrv-tsh' ); ?></option>
					</select>
					<p class="description"><?php esc_html_e( 'Style used when the [toggle] shortcode does not specify a style="1" or style="2" attribute.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Allow Shortcodes?', 'azrcrv-tsh' ); ?></th>
				<td>
					<input name="allow_shortcodes" type="checkbox" id="allow_shortcodes" value="1" <?php checked( 1, (int) $settings['allow_shortcodes'] ); ?> />
					<label for="allow_shortcodes"><?php esc_html_e( 'Allow shortcodes within the toggle title and content?', 'azrcrv-tsh' ); ?></label>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p>
						<?php
						printf(
							/* translators: %s: link to the Shortcodes in Widgets plugin. */
							esc_html__( 'To use the toggle shortcode in a widget, you will need a plugin (such as %s) which enables shortcodes in widgets.', 'azrcrv-tsh' ),
							'<a href="https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/">Shortcodes in Widgets</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static, developer-controlled URL and text.
						);
						?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Settings', 'azrcrv-tsh' ) ); ?>
</form>
