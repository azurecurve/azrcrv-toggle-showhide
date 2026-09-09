<?php
/*
	style 2 tab - "read more" text and button colours.

	Fixes a bug in the pre-2.0.0 plugin: the button/button-hover colour
	fields below were saved but never actually applied to the rendered
	button anywhere - the settings existed but silently did nothing. They
	are now applied via a small scoped <style> block in
	display_toggle_style2() (functions-shortcodes.php), built only from
	values that have passed sanitize_css_colour().
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

<p><?php esc_html_e( 'These settings apply when a [toggle] shortcode uses style="2", or when Style 2 is the default style and the content contains a <!--readmore--> marker.', 'azrcrv-tsh' ); ?></p>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

	<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_settings" />
	<input type="hidden" name="tsh-tab" value="style2" />
	<?php wp_nonce_field( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ); ?>

	<table class="form-table azrcrv-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Read More', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="style2_read_more" value="<?php echo esc_attr( $settings['style2']['read-more'] ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Text to use on the read more button.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Read Less', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="style2_read_less" value="<?php echo esc_attr( $settings['style2']['read-less'] ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Text to use on the read less button.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Button Background Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="style2_button_background_color" value="<?php echo esc_attr( $settings['style2']['button']['background-color'] ); ?>" class="regular-text" placeholder="#FFFFFF" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Button Text Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="style2_button_color" value="<?php echo esc_attr( $settings['style2']['button']['color'] ); ?>" class="regular-text" placeholder="#007FFF" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Button Hover Background Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="style2_button_hover_background_color" value="<?php echo esc_attr( $settings['style2']['button-hover']['background-color'] ); ?>" class="regular-text" placeholder="#007FFF" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Button Hover Text Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="style2_button_hover_color" value="<?php echo esc_attr( $settings['style2']['button-hover']['color'] ); ?>" class="regular-text" placeholder="#FFFFFF" /></td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Settings', 'azrcrv-tsh' ) ); ?>
</form>
