<?php
/*
	style 1 tab - fields controlling the "show/hide" toggle box. Field types
	and defaults are unchanged from the pre-2.0.0 plugin's General/Style 1
	tabs. Values are sanitized on save (functions-settings.php) and, since
	pre-upgrade saved values were never validated this strictly, re-validated
	again at shortcode render time (functions-shortcodes.php) before display.
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

/**
 * Render a labelled radio-button image picker for image_open/image_close.
 */
$render_image_picker = static function ( $field_name, $current_value ) {
	foreach ( get_available_toggle_images() as $file ) {
		printf(
			'<label style="display: inline-block; width: 90px; text-align: center; margin: 4px;"><input type="radio" name="%1$s" value="%2$s" %3$s /><br /><img src="%4$s" alt="%2$s" style="max-width: 100%%; height: auto;" /></label>',
			esc_attr( $field_name ),
			esc_attr( $file ),
			checked( $file, $current_value, false ),
			esc_url( plugins_url( 'assets/images/' . $file, PLUGIN_FILE ) )
		);
	}
};
?>

<p><?php esc_html_e( "If an option is left blank, the plugin's default CSS is used instead.", 'azrcrv-tsh' ); ?></p>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

	<input type="hidden" name="action" value="<?php echo esc_attr( PLUGIN_UNDERSCORE ); ?>_save_settings" />
	<input type="hidden" name="tsh-tab" value="style1" />
	<?php wp_nonce_field( PLUGIN_HYPHEN . '-save-settings', PLUGIN_HYPHEN . '-nonce' ); ?>

	<h2><?php esc_html_e( 'Title', 'azrcrv-tsh' ); ?></h2>
	<table class="form-table azrcrv-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Tag', 'azrcrv-tsh' ); ?></th>
				<td>
					<select name="title_tag">
						<?php foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p', 'strong' ) as $tag ) : ?>
							<option value="<?php echo esc_attr( $tag ); ?>" <?php selected( $tag, $settings['title_tag'] ?: 'h3' ); ?>><?php echo esc_html( $tag ); ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description"><?php esc_html_e( 'HTML tag used to wrap the toggle title.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="title" value="<?php echo esc_attr( $settings['title'] ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Default title text (e.g. Click here to toggle show/hide).', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Color', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="title_color" value="<?php echo esc_attr( $settings['title_color'] ); ?>" class="regular-text" placeholder="#000000" />
					<p class="description"><?php esc_html_e( 'Hex colour (e.g. #000000) or a basic colour name (e.g. black).', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Background Color', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="bg_title" value="<?php echo esc_attr( $settings['bg_title'] ); ?>" class="regular-text" placeholder="#FFFFFF" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Font Family', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="title_font" value="<?php echo esc_attr( $settings['title_font'] ); ?>" class="regular-text" placeholder="Arial, Calibri" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Font Size', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="title_font_size" value="<?php echo esc_attr( $settings['title_font_size'] ); ?>" class="regular-text" placeholder="14px" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Title Font Weight', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="title_font_weight" value="<?php echo esc_attr( $settings['title_font_weight'] ); ?>" class="regular-text" placeholder="bold" />
				</td>
			</tr>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Box', 'azrcrv-tsh' ); ?></h2>
	<table class="form-table azrcrv-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Width', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="width" value="<?php echo esc_attr( $settings['width'] ); ?>" class="regular-text" placeholder="500px" />
					<p class="description"><?php esc_html_e( 'e.g. 65% or 500px.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Border', 'azrcrv-tsh' ); ?></th>
				<td>
					<input type="text" name="border" value="<?php echo esc_attr( $settings['border'] ); ?>" class="regular-text" placeholder="1px solid #007FFF" />
					<p class="description"><?php esc_html_e( 'e.g. 1px solid #007FFF, or none.', 'azrcrv-tsh' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Disable Images?', 'azrcrv-tsh' ); ?></th>
				<td>
					<input name="disable_image" type="checkbox" id="disable_image" value="1" <?php checked( 1, (int) $settings['disable_image'] ); ?> />
					<label for="disable_image"><?php printf( esc_html__( 'Disable the open/close images in the toggle title? Can be overridden using the %s shortcode attribute.', 'azrcrv-tsh' ), '<code>disable_image</code>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup only. ?></label>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Image Location', 'azrcrv-tsh' ); ?></th>
				<td>
					<select name="image_location">
						<option value="left" <?php selected( 'left', $settings['image_location'] ); ?>><?php esc_html_e( 'Left', 'azrcrv-tsh' ); ?></option>
						<option value="right" <?php selected( 'right', $settings['image_location'] ); ?>><?php esc_html_e( 'Right', 'azrcrv-tsh' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Image Open', 'azrcrv-tsh' ); ?></th>
				<td>
					<p class="description"><?php esc_html_e( 'Icon shown while the toggle is closed (click to open).', 'azrcrv-tsh' ); ?></p>
					<?php $render_image_picker( 'image_open', $settings['image_open'] ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Image Close', 'azrcrv-tsh' ); ?></th>
				<td>
					<p class="description"><?php esc_html_e( 'Icon shown while the toggle is open (click to close).', 'azrcrv-tsh' ); ?></p>
					<?php $render_image_picker( 'image_close', $settings['image_close'] ); ?>
				</td>
			</tr>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Content', 'azrcrv-tsh' ); ?></h2>
	<table class="form-table azrcrv-settings" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="text_color" value="<?php echo esc_attr( $settings['text_color'] ); ?>" class="regular-text" placeholder="#000000" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Background Color', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="bg_text" value="<?php echo esc_attr( $settings['bg_text'] ); ?>" class="regular-text" placeholder="#FFFFFF" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Font Family', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="text_font" value="<?php echo esc_attr( $settings['text_font'] ); ?>" class="regular-text" placeholder="Arial, Calibri" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Font Size', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="text_font_size" value="<?php echo esc_attr( $settings['text_font_size'] ); ?>" class="regular-text" placeholder="14px" /></td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text Font Weight', 'azrcrv-tsh' ); ?></th>
				<td><input type="text" name="text_font_weight" value="<?php echo esc_attr( $settings['text_font_weight'] ); ?>" class="regular-text" placeholder="normal" /></td>
			</tr>
		</tbody>
	</table>

	<?php submit_button( __( 'Save Settings', 'azrcrv-tsh' ) ); ?>
</form>
