<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Toggle Show/Hide
 * Description: Toggle shortcode can be used to show/hide content.
 * Version: 1.0.0
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/
 * Text Domain: toggle-showhide
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// include plugin menu
require_once(dirname(__FILE__).'/pluginmenu/menu.php');

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
register_activation_hook(__FILE__, 'azrcrv_tsh_set_default_options');

// add actions
add_action('admin_menu', 'azrcrv_tsh_create_admin_menu');
add_action('admin_post_azrcrv_tsh_save_options', 'azrcrv_tsh_save_options');
add_action('wp_enqueue_scripts', 'azrcrv_tsh_load_css');
add_action('wp_enqueue_scripts', 'azrcrv_tsh_load_jquery');
//add_action('the_posts', 'azrcrv_tsh_check_for_shortcode');

// add filters
add_filter('plugin_action_links', 'azrcrv_tsh_add_plugin_action_link', 10, 2);

// add shortcodes
add_shortcode('toggle', 'azrcrv_tsh_display_toggle');
add_shortcode('TOGGLE', 'azrcrv_tsh_display_toggle');

/**
 * Check if shortcode on current page and then load css and jqeury.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_check_for_shortcode($posts){
    if (empty($posts)){
        return $posts;
	}
	
	
	// array of shortcodes to search for
	$shortcodes = array(
						'toggle','TOGGLE'
						);
	
    // loop through posts
    $found = false;
    foreach ($posts as $post){
		// loop through shortcodes
		foreach ($shortcodes as $shortcode){
			// check the post content for the shortcode
			if (has_shortcode($post->post_content, $shortcode)){
				$found = true;
				// break loop as shortcode found in page content
				break 2;
			}
		}
	}
 
    if ($found){
		// as shortcode found call functions to load css and jquery
        azrcrv_tsh_load_css();
		azrcrv_tsh_load_jquery();
    }
    return $posts;
}

/**
 * Load CSS.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_load_css(){
	wp_register_style('azrcrv-tsh', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	wp_enqueue_style('azrcrv-tsh', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	$options = get_option('azrcrv-tsh');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	$custom_css = "";
	if (strlen($options['image_close']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle-active {
							background-image: url('.plugins_url('images/'.$options['image_close'], __FILE__).') !important;
						}
						.azrcrv-tsh-toggle-open-active {
							background-image: url('.plugins_url('images/'.$options['image_close'], __FILE__).');
						}
						';
	}
	if (strlen($options['image_open']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle {
							background-image: url('.plugins_url('images/'.$options['image_open'], __FILE__).');
						}
						.azrcrv-tsh-toggle-open {
							background-image: url('.plugins_url('images/'.$options['image_open'], __FILE__).') !important;
						}
						';
	}
	/*if ($options['image_location'] == 'right'){
		$custom_css .= '.azrcrv-tsh-toggle, .azrcrv-tsh-toggle-open, .azrcrv-tsh-toggle-active, .azrcrv-tsh-toggle-open-active{
							background-position: '.$options['image_location'].';
						}';
	}*/
	if (strlen($options['title_font']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle, .azrcrv-tsh-toggle-open, .azrcrv-tsh-toggle-active, .azrcrv-tsh-toggle-open-active{
							font-family: '.$options['title_font'].';
						}';
	}
	if (strlen($options['title_font_size']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle, .azrcrv-tsh-toggle-open, .azrcrv-tsh-toggle-active, .azrcrv-tsh-toggle-open-active{
							font-size: '.$options['title_font_size'].';
						}';
	}
	if (strlen($options['text_font']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle-container, .azrcrv-tsh-toggle-container-open{
							font-family: '.$options['text_font'].';
						}';
	}
	if (strlen($options['text_font_size']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle-container, .azrcrv-tsh-toggle-container-open{
							font-size: '.$options['text_font_size'].';
						}';
	}
	if (strlen($custom_css) > 0){
		wp_add_inline_style('azrcrv-tsh', $custom_css);
	}
}

/**
 * Load JQuery.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_load_jquery(){
	wp_enqueue_script('azrcrv-tsh', plugins_url('assets/jquery/jquery.js', __FILE__), array('jquery'), '3.9.1');
}

/**
 * Set default options for plugin.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_set_default_options($networkwide){
	
	$option_name = 'azrcrv-tsh';
	$old_option_name = 'azc_tsh_options';
	
	$new_options = array(
						'use_multisite' => 0
						,'border' => ''
						,'title_tag' => ''
						,'title' => ''
						,'title_color' => ''
						,'title_font' => ''
						,'title_font_size' => ''
						,'title_font_weight' => ''
						,'allow_shortcodes' => 0
						,'bg_title' => ''
						,'bg_text' => ''
						,'text_color' => ''
						,'disable_image' => 0
						,'width' => ""
						,'image_open' => ""
						,'image_close' => ""
						,'image_location' => "left"
			);
	
	// set defaults for multi-site
	if (function_exists('is_multisite') && is_multisite()){
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide){
			global $wpdb;

			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			$original_blog_id = get_current_blog_id();

			foreach ($blog_ids as $blog_id){
				switch_to_blog($blog_id);

				if (get_option($option_name) === false){
					if (get_option($old_option_name) === false){
						add_option($option_name, $new_options);
					}else{
						add_option($option_name, get_option($old_option_name));
					}
				}
			}

			switch_to_blog($original_blog_id);
		}else{
			if (get_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
			}
		}
		if (get_site_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
		}
	}
	//set defaults for single site
	else{
		if (get_option($option_name) === false){
				if (get_option($old_option_name) === false){
					add_option($option_name, $new_options);
				}else{
					add_option($option_name, get_option($old_option_name));
				}
		}
	}
}

/**
 * Add Toggle Show/Hide action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-tsh">'.esc_html__('Settings' ,'toggle-showhide').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Toggle Show/Hide Settings", "toggle-showhide")
						,esc_html__("Toggle Show/Hide", "toggle-showhide")
						,'manage_options'
						,'azrcrv-tsh'
						,'azrcrv_tsh_display_options');
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'toggle-showhide'));
    }
	
	// Retrieve plugin configuration options from database
	$options = get_option('azrcrv-tsh');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	?>
	<div id="azrcrv-tsh-general" class="wrap">
		<fieldset>
			<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
			<?php if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Settings have been saved.', 'toggle-showhide') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_tsh_save_options" />
				<input name="page_options" type="hidden" value="tsh_suffix" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-tsh', 'azrcrv-tsh-nonce'); ?>
				
				<table class="form-table">
				
				<tr><td colspan=2>
					<p><?php printf(esc_html__('To use the toggle in a widget, you will need a plugin (such as %s) which enables shortcodes in widgets.', 'toggle-showhide'), '<a href="https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/">Shortcodes in Widgets</a>'); ?></p>
					<p><?php esc_html_e('If the options are blank then the defaults in the plugin\'s CSS will be used.', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<?php if (function_exists('is_multisite') && is_multisite()){ ?>
					<tr><th scope="row"><?php esc_html_e("Use multisite options instead of the site options below?", "toggle-showhide"); ?></th><td>
						<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Disable images in toggle title?', 'toggle-showhide'); ?></span></legend>
						<label for="use_multisite"><input name="use_multisite" type="checkbox" id="use_multisite" value="1" <?php checked('1', $options['use_multisite']); ?> /></label>
						</fieldset>
					</td></tr>
				<?php } ?>
				
				<tr><th scope="row"><label for="title_tag"><?php esc_html_e('Title Tag', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_tag" value="<?php echo esc_html(stripslashes($options['title_tag'])); ?>" class="small-text" />
					<p class="description"><?php printf(esc_html__('Set default title tag (e.g. h3); if not set, %s will be used.', 'toggle-showhide'), 'h3'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title"><?php esc_html_e('Title', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title" value="<?php echo esc_html(stripslashes($options['title'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title text (e.g. Click here to toggle show/hide)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Width', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="width" value="<?php echo esc_html(stripslashes($options['width'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default width (e.g. 65% or 500px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="border"><?php esc_html_e('Border', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="border" value="<?php echo esc_html(stripslashes($options['border'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default border (e.g. 1px solid #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_color"><?php esc_html_e('Title Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_color" value="<?php echo esc_html(stripslashes($options['title_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="bg_title"><?php esc_html_e('Title Background Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="bg_title" value="<?php echo esc_html(stripslashes($options['bg_title'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title background color (e.g. #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font"><?php esc_html_e('Title Font Family', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font" value="<?php echo esc_html(stripslashes($options['title_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font_size"><?php esc_html_e('Title Font Size', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font_size" value="<?php echo esc_html(stripslashes($options['title_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font_weight"><?php esc_html_e('Title Font Weight', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font_weight" value="<?php echo esc_html(stripslashes($options['title_font_weight'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font weight (e.g. 600)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_color"><?php esc_html_e('Text Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_color" value="<?php echo esc_html(stripslashes($options['text_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default text color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="bg_text"><?php esc_html_e('Text Background Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="bg_text" value="<?php echo esc_html(stripslashes($options['bg_text'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default text background color (e.g. #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font"><?php esc_html_e('Text Font Family', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font" value="<?php echo esc_html(stripslashes($options['text_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default text font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font_size"><?php esc_html_e('Text Font Size', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font_size" value="<?php echo esc_html(stripslashes($options['text_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default text font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font_weight"><?php esc_html_e('Text Font Weight', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font_weight" value="<?php echo esc_html(stripslashes($options['text_font_weight'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default text font weight (e.g. 600)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Image Open', 'toggle-showhide'); ?></th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__).'/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							$file = esc_html($file);
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_open' value='$file' "?><?php checked($file, $options['image_open'])?><?php echo ">&nbsp;<img src='".plugin_dir_url(__FILE__)."images/".$file."' name='$file' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Image Close', 'toggle-showhide'); ?></th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__).'/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							$file = esc_html($file);
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_close' value='$file' "?><?php checked($file, $options['image_close'])?><?php echo ">&nbsp;<img src='".plugin_dir_url(__FILE__)."images/".$file."' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Image Location', 'toggle-showhide'); ?></th><td>
					<select name="image_location">
						<option value="left" <?php if ($options['image_location'] == 'left'){ echo ' selected="selected"'; } ?>><?php esc_html_e("Left", "toggle-showhide"); ?></option>
						<option value="right" <?php if ($options['image_location'] == 'right'){ echo ' selected="selected"'; } ?>><?php esc_html_e("Right", "toggle-showhide"); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Select whether the toggle image should be displayed to the left or right.', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Disable Images?', 'toggle-showhide'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Disable images in toggle title?', 'toggle-showhide'); ?></span></legend>
					<label for="disable_image"><input name="disable_image" type="checkbox" id="disable_image" value="1" <?php checked('1', $options['disable_image']); ?> /><?php esc_html_e('Disable images in toggle title? Override setting by putting disable_image=0 in toggle.', 'toggle-showhide'); ?></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Allow Shortcodes?', 'toggle-showhide'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Allow shortcodes within toggle?', 'toggle-showhide'); ?></span></legend>
					<label for="allow_shortcodes"><input name="allow_shortcodes" type="checkbox" id="allow_shortcodes" value="1" <?php checked('1', $options['allow_shortcodes']); ?> /><?php esc_html_e('Allow shortcodes within toggle?', 'toggle-showhide'); ?></label>
					</fieldset>
				</td></tr>
				
				</table>
				<input type="submit" value="Submit" class="button-primary"/>
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'toggle-showhide'));
	}
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-tsh', 'azrcrv-tsh-nonce')){
		settings_fields('azrcrv-tsh');
		
		// Retrieve original plugin options array
		$options = get_option('azrcrv-tsh');
		
		$option_name = 'use_multisite';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'border';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_tag';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		$option_name = 'title';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_color';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'bg_title';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font_size';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font_weight';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'bg_text';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_color';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font_size';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font_weight';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'width';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_open';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_close';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_location';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'disable_image';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'allow_shortcodes';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		// Store updated options array to database
		update_option('azrcrv-tsh', $options);
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-tsh&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Add to Network menu.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_create_network_admin_menu(){
	if (function_exists('is_multisite') && is_multisite()){
		add_submenu_page(
						'settings.php'
						,esc_html__("Toggle Show/Hide Settings", "toggle-showhide")
						,esc_html__("Toggle Show/Hide", "toggle-showhide")
						,'manage_network_options'
						,'azrcrv-tsh'
						,'azrcrv_tsh_network_settings'
						);
	}
}

/**
 * Display network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_network_settings(){
	if(!current_user_can('manage_network_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'toggle-showhide'));
	}
	
	$options = get_site_option('azrcrv-tsh');
	
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }

	?>
	<div id="azrcrv-tsh-general" class="wrap">
		<fieldset>
			<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
			
			<?php if(isset($_GET['settings-updated'])){ ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e('Network Settings have been saved.', 'toggle-showhide') ?></strong></p>
				</div>
			<?php } ?>
			<form method="post" action="admin-post.php">
				<input type="hidden" name="action" value="azrcrv_tsh_save_network_options" />
				<input name="page_options" type="hidden" value="suffix" />
				
				<!-- Adding security through hidden referrer field -->
				<?php wp_nonce_field('azrcrv-tsh', 'azrcrv-tsh-nonce'); ?>
				<table class="form-table">
				
				<tr><td colspan=2>
					<p><?php printf(esc_html__('To use the toggle in a widget, you will need a plugin (such as %s) which enables shortcodes in widgets.', 'toggle-showhide'), '<a href="https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/">Shortcodes in Widgets</a>'); ?></p>
					<p><?php esc_html_e('If multisite is being used these options will be used when Use Multisite enabled in Site Options; if the options are blank the defaults in the plugin\'s CSS will be used.', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_tag"><?php esc_html_e('Title Tag', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_tag" value="<?php echo esc_html(stripslashes($options['title_tag'])); ?>" class="small-text" />
					<p class="description"><?php printf(esc_html__('Set default title tag (e.g. h3); if not set, %s will be used.', 'toggle-showhide'), 'h3'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title"><?php esc_html_e('Title', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title" value="<?php echo esc_html(stripslashes($options['title'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title text (e.g. Click here to toggle show/hide)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="width"><?php esc_html_e('Width', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="width" value="<?php echo esc_html(stripslashes($options['width'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default width (e.g. 65% or 500px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="border"><?php esc_html_e('Border', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="border" value="<?php echo esc_html(stripslashes($options['border'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default border (e.g. 1px solid #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_color"><?php esc_html_e('Title Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_color" value="<?php echo esc_html(stripslashes($options['title_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="bg_title"><?php esc_html_e('Title Background Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="bg_title" value="<?php echo esc_html(stripslashes($options['bg_title'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title background color (e.g. 1px solid #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font"><?php esc_html_e('Title Font Family', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font" value="<?php echo esc_html(stripslashes($options['title_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font_size"><?php esc_html_e('Title Font Size', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font_size" value="<?php echo esc_html(stripslashes($options['title_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="title_font_weight"><?php esc_html_e('Title Font Weight', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="title_font_weight" value="<?php echo esc_html(stripslashes($options['title_font_weight'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font weight (e.g. 600)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_color"><?php esc_html_e('Text Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_color" value="<?php echo esc_html(stripslashes($options['text_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default text color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="bg_text"><?php esc_html_e('Text Background Color', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="bg_text" value="<?php echo esc_html(stripslashes($options['bg_text'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default bg_text (e.g. 1px solid #00F000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font"><?php esc_html_e('Text Font Family', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font" value="<?php echo esc_html(stripslashes($options['text_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default text font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font_size"><?php esc_html_e('Text Font Size', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font_size" value="<?php echo esc_html(stripslashes($options['text_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default text font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><label for="text_font_weight"><?php esc_html_e('Text Font Weight', 'toggle-showhide'); ?></label></th><td>
					<input type="text" name="text_font_weight" value="<?php echo esc_html(stripslashes($options['text_font_weight'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default text font weight (e.g. 600)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Image Open', 'toggle-showhide'); ?></th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__).'/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_open' value='$file' "?><?php checked($file, $options['image_open'])?><?php echo ">&nbsp;<img src='".plugin_dir_url(__FILE__)."images/".$file."' name='$file' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				<tr><th scope="row"><?php esc_html_e('Image Close', 'toggle-showhide'); ?></th><td style='background: #D3D3D3;'>
				<?php
				$dir = plugin_dir_path(__FILE__).'/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								echo "<input type='radio' name='image_close' value='$file' "?><?php checked($file, $options['image_close'])?><?php echo ">&nbsp;<img src='".plugin_dir_url(__FILE__)."images/".$file."' alt='$file' />&nbsp;&nbsp;";
							}
						}
						closedir($directory);
					}
				}
				?>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Image Location', 'toggle-showhide'); ?></th><td>
					<select name="image_location">
						<option value="left" <?php if($options['image_location'] == 'left'){ echo ' selected="selected"'; } ?>><?php esc_html_e("Left", "toggle-showhide"); ?></option>
						<option value="right" <?php if($options['image_location'] == 'right'){ echo ' selected="selected"'; } ?>><?php esc_html_e("Right", "toggle-showhide"); ?></option>
					</select>
					<p class="description"><?php esc_html_e('Select whether the toggle image should be displayed to the left or right.', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Disable Images?', 'toggle-showhide'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Disable images in toggle title?', 'toggle-showhide'); ?></span></legend>
					<label for="disable_image"><input name="disable_image" type="checkbox" id="disable_image" value="1" <?php checked('1', $options['disable_image']); ?> /><?php esc_html_e('Disable images in toggle title? Override setting by putting disable_image=0 in toggle.', 'toggle-showhide'); ?></label>
					</fieldset>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Allow Shortcodes?', 'toggle-showhide'); ?></th><td>
					<fieldset><legend class="screen-reader-text"><span><?php esc_html_e('Allow shortcodes within toggle?', 'toggle-showhide'); ?></span></legend>
					<label for="allow_shortcodes"><input name="allow_shortcodes" type="checkbox" id="allow_shortcodes" value="1" <?php checked('1', $options['allow_shortcodes']); ?> /><?php esc_html_e('Allow shortcodes within toggle?', 'toggle-showhide'); ?></label>
					</fieldset>
				</td></tr>
				
				</table>
				<input type="submit" value="Save Changes" class="button-primary" />
			</form>
		</fieldset>
	</div>
	<?php
}

/**
 * Save network settings.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_save_network_options(){     
	if(!current_user_can('manage_network_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'toggle-showhide'));
	}
	
	if (! empty($_POST) && check_admin_referer('azrcrv-tsh', 'azrcrv-tsh-nonce')){
		// Retrieve original plugin options array
		$options = get_site_option('azrcrv-tsh');

		$option_name = 'border';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}

		$option_name = 'title_tag';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}

		$option_name = 'title';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}

		$option_name = 'title_color';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}

		$option_name = 'bg_title';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font_size';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'title_font_weight';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'bg_text';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_color';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font_size';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'text_font_weight';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'width';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_open';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_close';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'image_location';
		if (isset($_POST[$option_name])){
			$options[$option_name] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'disable_image';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		$option_name = 'allow_shortcodes';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
		}
		
		update_site_option('azrcrv-tsh', $options);

		wp_redirect(network_admin_url('settings.php?page=azrcrv-tsh&settings-updated'));
		exit;
	}
}

/**
 * Display Toggle via shortcode.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_display_toggle($atts, $content = null){
	
	$options = get_option('azrcrv-tsh');
	
	if ($options['use_multisite'] == 1){
		$options = get_site_option('azrcrv-tsh');
	}
	
	// extract attributes from shortcode
	$args = shortcode_atts(array(
		'title' => stripslashes($title),
		'title_color' => stripslashes($options['title_color']),
		'title_font' => stripslashes($options['title_font']),
		'title_font_size' => stripslashes($options['title_font_size']),
		'title_font_weight' => stripslashes($options['title_font_weight']),
		'expand' => 0,
		'border' => stripslashes($options['border']),
		'bgtitle' => stripslashes($options['bg_title']),
		'bgtext' => stripslashes($options['bg_text']),
		'text_color' => stripslashes($options['text_color']),
		'text_font' => stripslashes($options['text_font']),
		'text_font_size' => stripslashes($options['text_font_size']),
		'text_font_weight' => stripslashes($options['text_font_weight']),
		'disable_image' => stripslashes($options['disable_image']),
		'width' => stripslashes($options['width']),
		'image_location' => stripslashes($options['image_location']),
	), $atts);
	$title = $args['title'];
	if (strlen($title) == 0){
		$title = 'Click to show/hide';
	}else{
		$title = $title;
	}
	$title_color = $args['title_color'];
	$title_font = $args['title_font'];
	$title_font_size = $args['title_font_size'];
	$title_font_weight = $args['title_font_weight'];
	$expand = (int) $args['expand'];
	$border = $args['border'];
	$bgtitle = $args['bgtitle'];
	$bgtext = $args['bgtext'];
	$text_color = $args['text_color'];
	$text_font = $args['text_font'];
	$text_font_size = $args['text_font_size'];
	$text_font_weight = $args['text_font_weight'];
	$disable_image = $args['disable_image'];
	$width = $args['width'];
	$image_location = $args['image_location'];
	
	if($expand == 1){
		$expand = '-open';
		$expand_active = $expand.'-active';
	}else{
		$expand = '';
		$expand_active = '';
	}
	if (strlen($border) > 0){ $border = "border: ".$border."; "; }
	if (strlen($title_color) > 0){ $title_color = "color: ".$title_color."; "; }
	if (strlen($title_font) > 0){ $title_font = "font-family: ".$title_font."; "; }
	if (strlen($title_font_size) > 0){ $title_font_size = "font-size: ".$title_font_size."; "; }
	if (strlen($title_font_weight) > 0){ $title_font_weight = "font-weight: ".$title_font_weight."; "; }
	if (strlen($bgtitle) > 0){ $background_title = "background-color: ".$bgtitle."; "; }
	if (strlen($bgtext) > 0){ $background_text = "background-color: ".$bgtext."; "; }
	if (strlen($text_color) > 0){ $text_color = "color: ".$text_color."; "; }
	if (strlen($text_font) > 0){ $text_font = "font-family: ".$text_font."; "; }
	if (strlen($text_font_size) > 0){ $text_font_size = "font-size: ".$text_font_size."; "; }
	if (strlen($text_font_weight) > 0){ $text_font_weight = "font-weight: ".$text_font_weight."; "; }
	if ($disable_image == 1){
		$disable_image = 'background-image: none !important; padding-left: 10px; ';
	}else{
		$disable_image = '';
	}
	if (strlen($width) > 0){ $width = "margin: auto; width: ".esc_html($width)."; "; }
	
	if ($image_location == "right"){ $image_location = "background-position: right 10px center; padding-left: 10px; "; }
	if($options['allow_shortcodes'] == 1){
		$title = do_shortcode($title);
		$content = do_shortcode($content);
	}
	
	$title_tag = stripslashes($options['title_tag']);
	if (strlen ($title_tag) == 0){ $title_tag = 'h3'; }
	
	$output = "<".esc_html($title_tag)." class='azrcrv-tsh-toggle".esc_html($expand_active)."' style='".esc_html($border.$background_title.$disable_image.$image_location)."'><a href='#' style='".esc_html($title_color.$title_font.$title_font_size.$title_font_weight)."'>".esc_html($title)."</a></".esc_html($title_tag).">";
	
	$output .= "<div class='azrcrv-tsh-toggle-container".esc_html($expand)."' style='".esc_html($border.$background_text.$text_color.$text_font.$text_font_size.$text_font_weight)."'>".$content."</div>";
	
	if (strlen($width) > 0){
		$output = '<div style="'.esc_html($width).'">'.$output.'</div>';
	}
	return $output;
}

?>