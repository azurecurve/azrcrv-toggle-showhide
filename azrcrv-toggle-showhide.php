<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Toggle Show/Hide
 * Description: Toggle shortcode can be used to show/hide content.
 * Version: 1.6.0
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

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname(__FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_tsh');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// add actions
add_action('admin_menu', 'azrcrv_tsh_create_admin_menu');
add_action('admin_enqueue_scripts', 'azrcrv_tsh_load_admin_jquery');
add_action('admin_enqueue_scripts', 'azrcrv_tsh_load_admin_style');
add_action('admin_post_azrcrv_tsh_save_options', 'azrcrv_tsh_save_options');
add_action('plugins_loaded', 'azrcrv_tsh_load_languages');
add_action('wp_enqueue_scripts', 'azrcrv_tsh_load_css');
add_action('wp_enqueue_scripts', 'azrcrv_tsh_load_jquery');

// add filters
add_filter('plugin_action_links', 'azrcrv_tsh_add_plugin_action_link', 10, 2);
add_filter('codepotent_update_manager_image_path', 'azrcrv_tsh_custom_image_path');
add_filter('codepotent_update_manager_image_url', 'azrcrv_tsh_custom_image_url');

// add shortcodes
add_shortcode('toggle', 'azrcrv_tsh_display_toggle');
add_shortcode('TOGGLE', 'azrcrv_tsh_display_toggle');

/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_load_languages(){
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('toggle-showhide', false, $plugin_rel_path);
}

/**
 * Load CSS.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_load_css(){
	wp_register_style('azrcrv-tsh-css', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
	wp_enqueue_style('azrcrv-tsh-css');
	$options = azrcrv_tsh_get_option('azrcrv-tsh');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	$custom_css = "";
	if (strlen($options['image_close']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle-active{
							background-image: url('.plugins_url('assets/images/'.$options['image_close'], __FILE__).') !important;
						}
						.azrcrv-tsh-toggle-open-active{
							background-image: url('.plugins_url('assets/images/'.$options['image_close'], __FILE__).');
						}
						';
	}
	if (strlen($options['image_open']) > 0){
		$custom_css .= '.azrcrv-tsh-toggle{
							background-image: url('.plugins_url('assets/images/'.$options['image_open'], __FILE__).');
						}
						.azrcrv-tsh-toggle-open{
							background-image: url('.plugins_url('assets/images/'.$options['image_open'], __FILE__).') !important;
						}
						';
	}
	if ($options['image_location'] == 'right'){
		$custom_css .= '.azrcrv-tsh-toggle, .azrcrv-tsh-toggle-open, .azrcrv-tsh-toggle-active, .azrcrv-tsh-toggle-open-active{
							background-position: '.$options['image_location'].';
						}';
	}
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
		wp_add_inline_style('azrcrv-tsh-css', $custom_css);
	}
}

/**
 * Load JQuery.
 *
 * @since 1.0.0
 *
 */
function azrcrv_tsh_load_jquery(){
	
	$options = azrcrv_tsh_get_option('azrcrv-tsh');
	
	wp_enqueue_script('azrcrv-tsh-jquery', plugins_url('assets/jquery/jquery.js', __FILE__), array('jquery'), '3.9.1');
	
	$variables = array(
							'readmore' => esc_attr($options['style2']['read-more'])
							,'readless' => esc_attr($options['style2']['read-less'])
						);
	wp_localize_script('azrcrv-tsh-jquery', 'azrcrvtshvariables', $variables);
}

/**
 * Load admin css.
 *
 * @since 1.4.0
 *
 */
function azrcrv_tsh_load_admin_style(){
	
	global $pagenow;
	
	if ($pagenow == 'admin.php' AND $_GET['page'] == 'azrcrv-tsh'){
		wp_register_style('azrcrv-tsh-css', plugins_url('assets/css/style.css', __FILE__), '', '1.0.0');
		wp_enqueue_style('azrcrv-tsh-css');
		
		$custom_css = "";
		$custom_css .= '.azrcrv-tsh-toggle-active{
							background-image: url('.plugins_url('assets/images/blue_control_play_up.png', __FILE__).') !important;
						}
						.azrcrv-tsh-toggle-open-active{
							background-image: url('.plugins_url('assets/images/blue_control_play_up.png', __FILE__).');
						}
						';
		$custom_css .= '.azrcrv-tsh-toggle{
							background-image: url('.plugins_url('assets/images/blue_control_play_down.png', __FILE__).');
						}
						.azrcrv-tsh-toggle-open{
							background-image: url('.plugins_url('assets/images/blue_control_play_down.png', __FILE__).') !important;
						}
						';
		wp_add_inline_style('azrcrv-tsh-css', $custom_css);
		
		wp_register_style('azrcrv-tsh-admin-css', plugins_url('assets/css/admin.css', __FILE__), false, '1.0.0');
		wp_enqueue_style('azrcrv-tsh-admin-css');
		
		wp_register_style('azrcrv-tsh-admin-css-jquery-ui', plugins_url('libraries/jquery-ui/jquery-ui.css', __FILE__), false, '1.0.0');
		wp_enqueue_style('azrcrv-tsh-admin-css-jquery-ui');
		
		wp_register_style('azrcrv-tsh-admin-css-jquery-ui-structure', plugins_url('libraries/jquery-ui/jquery-ui.structure.css', __FILE__), false, '1.0.0');
		wp_enqueue_style('azrcrv-tsh-admin-css-jquery-ui-structure');
	}
}

/**
 * Load media uploaded.
 *
 * @since 1.4.0
 *
 */
function azrcrv_tsh_load_admin_jquery(){
	
	global $pagenow;
	
	if ($pagenow == 'admin.php' AND $_GET['page'] == 'azrcrv-tsh'){
		wp_enqueue_script('azrcrv-tsh-jquery', plugins_url('assets/jquery/jquery.js', __FILE__), array('jquery'), '3.9.1');
	
		wp_enqueue_script('azrcrv-tsh-admin-jquery', plugins_url('assets/jquery/admin.js', __FILE__), array('jquery'));
		
		wp_enqueue_script('azrcrv-tsh-admin-jquery-ui', plugins_url('libraries/jquery-ui/jquery-ui.js', __FILE__), array('jquery'));
		wp_enqueue_script('azrcrv-tsh-admin-jquery-ui-external', plugins_url('libraries/jquery-ui/external/jquery/jquery.js', __FILE__), array('jquery'));
	}
}

/**
 * Custom plugin image path.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tsh_custom_image_path($path){
    if (strpos($path, 'azrcrv-toggle-showhide') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tsh_custom_image_url($url){
    if (strpos($url, 'azrcrv-toggle-showhide') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Get options including defaults.
 *
 * @since 1.2.0
 *
 */
function azrcrv_tsh_get_option($option_name){
 
	$defaults = array(
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
						,'text_font' => ''
						,'text_font_size' => ''
						,'text_font_weight' => ''
						,'disable_image' => 0
						,'width' => ""
						,'image_open' => 'blue_control_play_down.png'
						,'image_close' => 'blue_control_play_up.png'
						,'image_location' => 'left'
						,'default-style' => 1
						,'style2' => array(
											'read-more' => esc_html__('Read more...', 'toggle-showhide')
											,'read-less' => esc_html__('Read less...', 'toggle-showhide')
											,'button' => array(
																	'background-color' => '#FFF'
																	,'color' => '#007FFF'
																)
											,'button-hover' => array(
																	'background-color' => '#007FFF'
																	,'color' => '#FFF'
																)
										)
					);

	$options = get_option($option_name, $defaults);

	$options = azrcrv_tsh_recursive_parse_args($options, $defaults);

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 *
 * @since 1.4.0
 *
 */
function azrcrv_tsh_recursive_parse_args($args, $defaults){
	$new_args = (array) $defaults;

	foreach ($args as $key => $value){
		if (is_array($value) && isset($new_args[$key])){
			$new_args[$key] = azrcrv_tsh_recursive_parse_args($value, $new_args[$key]);
		}
		else{
			$new_args[$key] = $value;
		}
	}

	return $new_args;
	
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
		$settings_link = '<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=azrcrv-tsh"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'toggle-showhide').'</a>';
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
	$options = azrcrv_tsh_get_option('azrcrv-tsh');
	if (!isset($options['image_open'])){ $options['image_open'] = ''; }
	if (!isset($options['image_close'])){ $options['image_close'] = ''; }
	?>
	<div id="azrcrv-tsh-general" class="wrap">
		
			<h1>
				<?php
					echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
					esc_html_e(get_admin_page_title());
				?>
			</h1>
			
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
				
				<?php
				/*
					Tab 1 = General Settings
				*/
				$tab_1 = "
							<table class='form-table'>";
				
				// use multisite			
				if (function_exists('is_multisite') && is_multisite()){
					$use_multisite_th = esc_html__("Use multisite options?", "toggle-showhide");
					$use_multisite_name = 'use_multisite';
					$use_multisite_checked = checked('1', $options[$use_multisite_name]);
					$use_multisite_prompt = esc_html__('Use multisite options instead of the site options below?', 'toggle-showhide');
					$use_multisite_td = "<input name='$use_multisite_name' type='checkbox' id='$use_multisite_name' value='1' $use_multisite_checked />";
					$use_multisite_td .= "<label for='$use_multisite_name'>$use_multisite_prompt</label>";
					$tab_1 .=	"
								<tr>
									<th scope='row'>
										$use_multisite_th
									</th>
									
									<td>
										$use_multisite_td
									</td>
								</tr>";
				}
				// allow shortcodes
				$allow_shortcodes_th = esc_html__('Allow Shortcodes?', 'toggle-showhide');
				$allow_shortcodes_name = 'allow_shortcodes';
				$allow_shortcodes_checked = ($options[$allow_shortcodes_name] == 1) ? 'checked' : '';
				$allow_shortcodes_prompt = esc_html__('Allow shortcodes within toggle?', 'toggle-showhide');
				$allow_shortcodes_td = "<input name='$allow_shortcodes_name' type='checkbox' id='$allow_shortcodes_name' value='1' $allow_shortcodes_checked />";
				$allow_shortcodes_td .= "<label for='$allow_shortcodes_name'>$allow_shortcodes_prompt</label>";
				$tab_1 .= "
								<tr>
									<th scope='row'>
										$allow_shortcodes_th
									</th>
									
									<td>
										$allow_shortcodes_td
									</td>
								</tr>";
				// default style
				$default_style_th = esc_html__('Default Style', 'toggle-showhide');
				$default_style_name = 'default-style';
				$default_style_1_value = esc_html__("Style 1 - Toggle", "toggle-showhide");
				$default_style_1_checked = ($options[$default_style_name] == 1) ? 'selected' : '';
				$default_style_1_option = "<option value='1' $default_style_1_checked>$default_style_1_value</option>";
				$default_style_2_value = esc_html__("Style 2 - Read More", "toggle-showhide");
				$default_style_2_checked = ($options[$default_style_name] == 2) ? 'selected' : '';
				$default_style_2_option = "<option value='2' $default_style_2_checked>$default_style_2_value</option>";
				$default_style_td = "<select name='$default_style_name'>$default_style_1_option$default_style_2_option</select>";
				$tab_1 .= "
								<tr>
									<th scope='row'>
										$default_style_th
									</th>
									
									<td>
										$default_style_td
									</td>
								</tr>
							</table>";
				
				/*
					Tab 2 = Stype 1 - Toggle
				*/
				$tab_2 = "
							<table class='form-table'>";
				// instructions
				$tab_2_th = esc_html__('If the options are blank then the defaults in the plugin\'s CSS will be used.', 'toggle-showhide');
				$tab_2 .= "
								<tr>
									<th scope='row' colspan=2>
										$tab_2_th
									</th>
								</tr>";
				// width
				$width_th = esc_html__('Width', 'toggle-showhide');
				$width_name = 'width';
				$width_value = esc_html__(stripslashes($options[$width_name]));
				$width_input = "<input type='text' name='$width_name' value='$width_value' class='small-text' />";
				$width_description_text = sprintf(esc_html__('Set default width (e.g. %1$s65&#37;%2$s or %1$s500px%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$width_description = "<p class='description'>$width_description_text</p>";
				$width_td = $width_input.$width_description;
				$tab_2 .= "
								<tr>
									<th scope='row'>
										$width_th
									</th>
									
									<td>
										$width_td
									</td>
								</tr>";
				// border
				$border_th = esc_html__('Border', 'toggle-showhide');
				$border_name = 'border';
				$border_value = esc_html__(stripslashes($options[$border_name]));
				$border_input = "<input type='text' name='$border_name' value='$border_value' class='regular-text' />";
				$border_description_text = sprintf(esc_html__('Set default border (e.g. %1$s1px solid #007FFF%2$s or %1$snone%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$border_description = "<p class='description'>$border_description_text</p>";
				$border_td = $border_input.$border_description;
				$tab_2 .= "
								<tr>
									<th scope='row'>
										$border_th
									</th>
									
									<td>
										$border_td
									</td>
								</tr>
							</table>";
				/*
				toggle title section content
				*/
				// title tag
				$title_tag_th = esc_html__('Title Tag', 'toggle-showhide');
				$title_tag_name = 'title_tag';
				$title_tag_value = esc_html__(stripslashes($options[$title_tag_name]));
				$title_tag_input = "<input type='text' name='$title_tag_name' value='$title_tag_value' class='small-text' />";
				$title_tag_description_text = sprintf(esc_html__('Set default title tag (e.g. %sh3%s); if not set, %s will be used.', 'toggle-showhide'), '<strong>', '</strong>', 'h3');
				$title_tag_description = "<p class='description'>$title_tag_description_text</p>";
				$title_tag_td = $title_tag_input.$title_tag_description;
				$toggle_title_content = "
							<table class='form-table'>
								<tr>
									<th scope='row'>
										$title_tag_th
									</th>
									
									<td>
										$title_tag_td
									</td>
								</tr>";
				// title
				$title_th = esc_html__('Title', 'toggle-showhide');
				$title_name = 'title';
				$title_value = esc_html__(stripslashes($options[$title_name]));
				$title_input = "<input type='text' name='$title_name' value='$title_value' class='regular-text' />";
				$title_description_text = sprintf(esc_html__('Set default title text (e.g. %sClick here to toggle show/hide%s).', 'toggle-showhide'), '<strong>', '</strong>', 'h3');
				$title_description = "<p class='description'>$title_description_text</p>";
				$title_td = $title_input.$title_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_th
									</th>
									
									<td>
										$title_td
									</td>
								</tr>";
				// title colour
				$title_color_th = esc_html__('Title Color', 'toggle-showhide');
				$title_color_name = 'title_color';
				$title_color_value = esc_html__(stripslashes($options[$title_color_name]));
				$title_color_input = "<input type='text' name='$title_color_name' value='$title_color_value' class='regular-text' />";
				$title_color_description_text = sprintf(esc_html__('Set default title color (e.g. %1$s#000%2$s or %1$sblack%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$title_color_description = "<p class='description'>$title_color_description_text</p>";
				$title_color_td = $title_color_input.$title_color_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_color_th
									</th>
									
									<td>
										$title_color_td
									</td>
								</tr>";
				// title background colour
				$title_background_color_th = esc_html__('Title Background Color', 'toggle-showhide');
				$title_background_color_name = 'bg_title';
				$title_background_color_value = esc_html__(stripslashes($options[$title_background_color_name]));
				$title_background_color_input = "<input type='text' name='$title_background_color_name' value='$title_background_color_value' class='regular-text' />";
				$title_background_color_description_text = sprintf(esc_html__('Set default title background color (e.g. %1$s#FFF%2$s or %1$swhite%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$title_background_color_description = "<p class='description'>$title_background_color_description_text</p>";
				$title_background_color_td = $title_background_color_input.$title_background_color_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_background_color_th
									</th>
									
									<td>
										$title_background_color_td
									</td>
								</tr>";
				// title font family
				$title_font_th = esc_html__('Title Font Family', 'toggle-showhide');
				$title_font_name = 'title_font';
				$title_font_value = esc_html__(stripslashes($options[$title_font_name]));
				$title_font_input = "<input type='text' name='$title_font_name' value='$title_font_value' class='large-text' />";
				$title_font_description_text = sprintf(esc_html__('Set default title font family (e.g. %sArial, Calibri%s).', 'toggle-showhide'), '<strong>', '</strong>');
				$title_font_description = "<p class='description'>$title_font_description_text</p>";
				$title_font_td = $title_font_input.$title_font_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_font_th
									</th>
									
									<td>
										$title_font_td
									</td>
								</tr>";
				// title font size
				$title_font_size_th = esc_html__('Title Font Size', 'toggle-showhide');
				$title_font_size_name = 'title_font_size';
				$title_font_size_value = esc_html__(stripslashes($options[$title_font_size_name]));
				$title_font_size_input = "<input type='text' name='$title_font_size_name' value='$title_font_size_value' class='regular-text' />";
				$title_font_size_description_text = sprintf(esc_html__('Set default title font size (e.g. %1$s1.2em%2$s or %1$s14px%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$title_font_size_description = "<p class='description'>$title_font_size_description_text</p>";
				$title_font_size_td = $title_font_size_input.$title_font_size_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_font_size_th
									</th>
									
									<td>
										$title_font_size_td
									</td>
								</tr>";
				// title font weight
				$title_font_weight_th = esc_html__('Title Font Weight', 'toggle-showhide');
				$title_font_weight_name = 'title_font_weight';
				$title_font_weight_value = esc_html__(stripslashes($options[$title_font_weight_name]));
				$title_font_weight_input = "<input type='text' name='$title_font_weight_name' value='$title_font_weight_value' class='small-text' />";
				$title_font_weight_description_text = sprintf(esc_html__('Set default title font weight (e.g. %s900%s).', 'toggle-showhide'), '<strong>', '</strong>');
				$title_font_weight_description = "<p class='description'>$title_font_weight_description_text</p>";
				$title_font_weight_td = $title_font_weight_input.$title_font_weight_description;
				$toggle_title_content .= "
								<tr>
									<th scope='row'>
										$title_font_weight_th
									</th>
									
									<td>
										$title_font_weight_td
									</td>
								</tr>
							</table>";
								
				// toggle for toggle title section
				$toggle_title_atts = array(
												'expand' => 1,
												'width' => '100%',
												'border' => 'none',
												'image_location' => 'left',
												'title' => 'Toggle Title',
												'title_tag' => 'h2',
												'title_color' => 'black',
												'bgtitle' => 'none',
												'title_font' => '-',
												'title_font_size' => '1.3em; text-decoration: none',
												'title_font_weight' => '-',
												'text_colour' => 'black',
												'bgtext' => 'none',
												'text_font' => '-',
												'text_font_size' => '1em; text-decoration: none',
												'text_font_weight' => '-',
											);
				$tab_2 .= azrcrv_tsh_display_toggle_style1($toggle_title_atts, $toggle_title_content, $options);
				
				/*
				toggle image section content
				*/
				// allow shortcodes
				$disable_image_th = esc_html__('Disable Images?', 'toggle-showhide');
				$disable_image_name = 'disable_image';
				$disable_image_checked = ($options[$disable_image_name] == 1) ? 'checked' : '';
				$disable_image_prompt = sprintf(esc_html__('Disable images in toggle title? Can be overridden using the %s parameter in toggle', 'toggle-showhide'), "<strong>$disable_image_name</strong>");
				$disable_image_td = "<input name='$disable_image_name' type='checkbox' id='$disable_image_name' value='1' $disable_image_checked />";
				$disable_image_td .= "<label for='$disable_image_name'>$disable_image_prompt</label>";
				$toggle_image_content = "
							<table class='form-table'>
								<tr>
									<th scope='row'>
										$disable_image_th
									</th>
									
									<td>
										$disable_image_td
									</td>
								</tr>";
				// image location
				$image_location_th = esc_html__('Image Location', 'toggle-showhide');
				$image_location_name = 'image_location';
				$image_location_1_name = esc_html__("Left", "toggle-showhide");
				$image_location_1_value = 'left';
				$image_location_1_checked = ($options[$image_location_name] == 'left') ? 'selected' : '';
				$image_location_1_option = "<option value='$image_location_1_value' $image_location_1_checked>$image_location_1_name</option>";
				$image_location_2_name = esc_html__("Right", "toggle-showhide");
				$image_location_2_value = 'right';
				$image_location_2_checked = ($options[$image_location_name] == 'right') ? 'selected' : '';
				$image_location_2_option = "<option value='$image_location_2_value' $image_location_2_checked>$image_location_2_name</option>";
				$image_location_td = "<select name='$image_location_name'>$image_location_1_option$image_location_2_option</select>";
				$toggle_image_content .= "
								<tr>
									<th scope='row'>
										$image_location_th
									</th>
									
									<td>
										$image_location_td
									</td>
								</tr>";
				
				$image_open_th = esc_html__('Image Open', 'toggle-showhide');
				$image_open_td = '';
				$image_open_name = 'image_open';
				$dir = plugin_dir_path(__FILE__).'/assets/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							$file = esc_html($file);
							if ($file != '.' and $file != '..' and $file != 'index.php' and $file != 'Thumbs.db'){
								$checked = '';
								if ($file == $options[$image_open_name]){
									$checked = 'checked=checked';
								}
								$image_open_td .= "<div style='display: inline-block; width: 75px; '><input type='radio' name='$image_open_name' value='$file' $checked />&nbsp;<img src='".plugin_dir_url(__FILE__)."assets/images/$file' name='$file' alt='$file' /></div>";
							}
						}
						closedir($directory);
					}
				}
				$toggle_image_content .= "
								<tr>
									<th scope='row'>
										$image_open_th
									</th>
									
									<td>
										$image_open_td
									</td>
								</tr>";
				
				$image_close_th = esc_html__('Image Close', 'toggle-showhide');
				$image_close_td = '';
				$image_close_name = 'image_close';
				$dir = plugin_dir_path(__FILE__).'/assets/images';
				if (is_dir($dir)){
					if ($directory = opendir($dir)){
						while (($file = readdir($directory)) !== false){
							$file = esc_html($file);
							if ($file != '.' and $file != '..' and $file != 'index.php' and $file != 'Thumbs.db'){
								$checked = '';
								if ($file == $options[$image_close_name]){
									$checked = 'checked=checked';
								}
								$image_close_td .= "<div style='display: inline-block; width: 75px; '><input type='radio' name='$image_close_name' value='$file' $checked />&nbsp;<img src='".plugin_dir_url(__FILE__)."assets/images/$file' name='$file' alt='$file' /></div>";
							}
						}
						closedir($directory);
					}
				}
				$toggle_image_content .= "
								<tr>
									<th scope='row'>
										$image_close_th
									</th>
									
									<td>
										$image_close_td
									</td>
								</tr>
							</table>";
				
				// toggle for toggle image section
				$toggle_text_atts = array(
												'width' => '100%',
												'border' => 'none',
												'image_location' => 'left',
												'title' => 'Toggle Image',
												'title_tag' => 'h2',
												'title_color' => 'black',
												'bgtitle' => 'none',
												'title_font' => '-',
												'title_font_size' => '1.3em; text-decoration: none',
												'title_font_weight' => '-',
												'text_colour' => 'black',
												'bgtext' => 'none',
												'text_font' => '-',
												'text_font_size' => '1em; text-decoration: none',
												'text_font_weight' => '-',
											);
				$tab_2 .= azrcrv_tsh_display_toggle_style1($toggle_text_atts, $toggle_image_content, $options);
				
				/*
				toggle text section content
				*/
				// text colour
				$text_color_th = esc_html__('Text Color', 'toggle-showhide');
				$text_color_name = 'text_color';
				$text_color_value = esc_html__(stripslashes($options[$text_color_name]));
				$text_color_input = "<input type='text' name='$text_color_name' value='$text_color_value' class='regular-text' />";
				$text_color_description_text = sprintf(esc_html__('Set default text color (e.g. %1$s#000%2$s or %1$sblack%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$text_color_description = "<p class='description'>$text_color_description_text</p>";
				$text_color_td = $text_color_input.$text_color_description;
				$toggle_text_content = "
							<table class='form-table'>
								<tr>
									<th scope='row'>
										$text_color_th
									</th>
									
									<td>
										$text_color_td
									</td>
								</tr>";
				// text background colour
				$text_background_color_th = esc_html__('Text Background Color', 'toggle-showhide');
				$text_background_color_name = 'bg_text';
				$text_background_color_value = esc_html__(stripslashes($options[$text_background_color_name]));
				$text_background_color_input = "<input type='text' name='$text_background_color_name' value='$text_background_color_value' class='regular-text' />";
				$text_background_color_description_text = sprintf(esc_html__('Set default text background color (e.g. %1$s#FFF%2$s or %1$swhite%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$text_background_color_description = "<p class='description'>$text_background_color_description_text</p>";
				$text_background_color_td = $text_background_color_input.$text_background_color_description;
				$toggle_text_content .= "
								<tr>
									<th scope='row'>
										$text_background_color_th
									</th>
									
									<td>
										$text_background_color_td
									</td>
								</tr>";
				// text font family
				$text_font_th = esc_html__('Text Font Family', 'toggle-showhide');
				$text_font_name = 'text_font';
				$text_font_value = esc_html__(stripslashes($options[$text_font_name]));
				$text_font_input = "<input type='text' name='$text_font_name' value='$text_font_value' class='large-text' />";
				$text_font_description_text = sprintf(esc_html__('Set default text font family (e.g. %sArial, Calibri%s).', 'toggle-showhide'), '<strong>', '</strong>');
				$text_font_description = "<p class='description'>$text_font_description_text</p>";
				$text_font_td = $text_font_input.$text_font_description;
				$toggle_text_content .= "
								<tr>
									<th scope='row'>
										$text_font_th
									</th>
									
									<td>
										$text_font_td
									</td>
								</tr>";
				// text font size
				$text_font_size_th = esc_html__('Text Font Size', 'toggle-showhide');
				$text_font_size_name = 'text_font_size';
				$text_font_size_value = esc_html__(stripslashes($options[$text_font_size_name]));
				$text_font_size_input = "<input type='text' name='$text_font_size_name' value='$text_font_size_value' class='regular-text' />";
				$text_font_size_description_text = sprintf(esc_html__('Set default text font size (e.g. %1$s1.2em%2$s or %1$s14px%2$s).', 'toggle-showhide'), '<strong>', '</strong>');
				$text_font_size_description = "<p class='description'>$text_font_size_description_text</p>";
				$text_font_size_td = $text_font_size_input.$text_font_size_description;
				$toggle_text_content .= "
								<tr>
									<th scope='row'>
										$text_font_size_th
									</th>
									
									<td>
										$text_font_size_td
									</td>
								</tr>";
				// text font weight
				$text_font_weight_th = esc_html__('Text Font Weight', 'toggle-showhide');
				$text_font_weight_name = 'text_font_weight';
				$text_font_weight_value = esc_html__(stripslashes($options[$text_font_weight_name]));
				$text_font_weight_input = "<input type='text' name='$text_font_weight_name' value='$text_font_weight_value' class='small-text' />";
				$text_font_weight_description_text = sprintf(esc_html__('Set default text font weight (e.g. %s900%s).', 'toggle-showhide'), '<strong>', '</strong>');
				$text_font_weight_description = "<p class='description'>$text_font_weight_description_text</p>";
				$text_font_weight_td = $text_font_weight_input.$text_font_weight_description;
				$toggle_text_content .= "
								<tr>
									<th scope='row'>
										$text_font_weight_th
									</th>
									
									<td>
										$text_font_weight_td
									</td>
								</tr>
							</table>";
								
				// toggle for toggle text section
				$toggle_text_atts = array(
												'width' => '100%',
												'border' => 'none',
												'image_location' => 'left',
												'title' => 'Toggle Text',
												'title_tag' => 'h2',
												'title_color' => 'black',
												'bgtitle' => 'none',
												'title_font' => '-',
												'title_font_size' => '1.3em; text-decoration: none',
												'title_font_weight' => '-',
												'text_colour' => 'black',
												'bgtext' => 'none',
												'text_font' => '-',
												'text_font_size' => '1em; text-decoration: none',
												'text_font_weight' => '-',
											);
				$tab_2 .= azrcrv_tsh_display_toggle_style1($toggle_text_atts, $toggle_text_content, $options);
				
				/*
					Tab 3 = Stype 2 - Read More
				*/
				$tab_3 = "
							<table class='form-table'>";
				// instructions
				$tab_3_th = sprintf(esc_html__('Use the %s tag within the content of the toggle for the placement of the %sRead more%s button.', 'toggle-showhide'), '<strong>&lt;!--readmore--&gt;</strong>', '<em>', '</em>');
				$tab_3 .= "
								<tr>
									<th scope='row' colspan=2>
										$tab_3_th
									</th>
								</tr>";
				// read more
				$default_style_1_th = esc_html__('Read More', 'toggle-showhide');
				$default_style_1_name = 'style2-read-more';
				$default_style_1_value = esc_html__(stripslashes($options['style2']['read-more']));
				$default_style_1_input = "<input type='text' name='$default_style_1_name' value='$default_style_1_value' class='regular-text' />";
				$default_style_1_description_text = esc_html__('Text to use on the read more button.', 'toggle-showhide');
				$default_style_1_description = "<p class='description'>$default_style_1_description_text</p>";
				$default_style_1_td = $default_style_1_input.$default_style_1_description;
				$tab_3 .= "
								<tr>
									<th scope='row'>
										$default_style_1_th
									</th>
									
									<td>
										$default_style_1_td
									</td>
								</tr>";
				// read less
				$default_style_2_th = esc_html__('Read Less', 'toggle-showhide');
				$default_style_2_name = 'style2-read-less';
				$default_style_2_value = esc_html__(stripslashes($options['style2']['read-less']));
				$default_style_2_input = "<input type='text' name='$default_style_2_name' value='$default_style_2_value' class='regular-text' />";
				$default_style_2_description_text = esc_html__('Text to use on the read less button.', 'toggle-showhide');
				$default_style_2_description = "<p class='description'>$default_style_2_description_text</p>";
				$default_style_2_td = $default_style_2_input.$default_style_2_description;
				$tab_3 .= "
								<tr>
									<th scope='row'>
										$default_style_2_th
									</th>
									
									<td>
										$default_style_2_td
									</td>
								</tr>
							</table>";
				?>
				
				<?php
				$tab_1_label = esc_html__('General', 'toggle-showhide');
				$tab_2_label = esc_html__('Style 1 - Toggle', 'toggle-showhide');
				$tab_3_label = esc_html__('Style 2 - Read More', 'toggle-showhide');
				?>
				<div id="tabs" class="ui-tabs">
					<ul class="ui-tabs-nav ui-widget-header" role="tablist">
						<li class="ui-state-default ui-state-active" aria-controls="tab-panel-1" aria-labelledby="tab-1" aria-selected="true" aria-expanded="true" role="tab">
							<a id="tab-1" class="ui-tabs-anchor" href="#tab-panel-1"><?php echo $tab_1_label; ?></a>
						</li>
						<li class="ui-state-default" aria-controls="tab-panel-2" aria-labelledby="tab-2" aria-selected="false" aria-expanded="false" role="tab">
							<a id="tab-2" class="ui-tabs-anchor" href="#tab-panel-2"><?php echo $tab_2_label; ?></a>
						</li>
						<li class="ui-state-default" aria-controls="tab-panel-3" aria-labelledby="tab-3" aria-selected="false" aria-expanded="false" role="tab">
							<a id="tab-3" class="ui-tabs-anchor" href="#tab-panel-3"><?php echo $tab_3_label; ?></a>
						</li>
					</ul>
					<div id="tab-panel-1" class="ui-tabs-scroll" role="tabpanel" aria-hidden="false">
						<?php echo $tab_1; ?>
					</div>
					<div id="tab-panel-2" class="ui-tabs-scroll ui-tabs-hidden" role="tabpanel" aria-hidden="true">
						<?php echo $tab_2; ?>
					</div>
					<div id="tab-panel-3" class="ui-tabs-scroll ui-tabs-hidden" role="tabpanel" aria-hidden="true">
						<?php echo $tab_3; ?>
					</div>
				</div>
				
				<input type="submit" value="<?php esc_html_e('Submit', 'toggle-showhide'); ?>" class="button-primary"/>
			</form>
	</div>
		
	<div>
		<p>
			<label for="additional-plugins">
				azurecurve <?php esc_html_e('has the following plugin which allow toggles to be used in widgets:', 'flags'); ?>
			</label>
			<ul class='azrcrv-plugin-index'>
				<li>
					<?php
					if (azrcrv_tsh_is_plugin_active('azrcrv-shortcodes-in-widgets/azrcrv-shortcodes-in-widgets.php')){
						echo "<a href='admin.php?page=azrcrv-siw' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/' class='azrcrv-plugin-index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
		</p>
	</div>
	
	<?php
}

/**
 * Check if other plugin active.
 *
 * @since 1.6.0
 *
 */
function azrcrv_tsh_is_plugin_active($plugin){
    return in_array($plugin, (array) get_option('active_plugins', array()));
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
		
		// defaults tab
		$option_name = 'use_multisite';
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
		
		$option_name = 'default-style';
		if (isset($_POST[$option_name])){
			if (sanitize_text_field(intval($_POST[$option_name])) == 2){
				$options[$option_name] = 2;
			}else{
				$options[$option_name] = 1;
			}
		}
		
		// style 1 tab
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
		
		$option_name = 'disable_image';
		if (isset($_POST[$option_name])){
			$options[$option_name] = 1;
		}else{
			$options[$option_name] = 0;
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
		
		// style 2 (read more) tab
		$option_name = 'style2-read-more';
		if (isset($_POST[$option_name])){
			$options['style2']['read-more'] = sanitize_text_field($_POST[$option_name]);
		}
		
		$option_name = 'style2-read-less';
		if (isset($_POST[$option_name])){
			$options['style2']['read-less'] = sanitize_text_field($_POST[$option_name]);
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
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			
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
				
				<tr><th scope="row"><?php esc_html_e('Title Tag', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title_tag" value="<?php echo esc_html(stripslashes($options['title_tag'])); ?>" class="small-text" />
					<p class="description"><?php printf(esc_html__('Set default title tag (e.g. h3); if not set, %s will be used.', 'toggle-showhide'), 'h3'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title" value="<?php echo esc_html(stripslashes($options['title'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title text (e.g. Click here to toggle show/hide)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Width', 'toggle-showhide'); ?></th><td>
					<input type="text" name="width" value="<?php echo esc_html(stripslashes($options['width'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default width (e.g. 65% or 500px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Border', 'toggle-showhide'); ?></th><td>
					<input type="text" name="border" value="<?php echo esc_html(stripslashes($options['border'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default border (e.g. 1px solid #007FFF)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title Color', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title_color" value="<?php echo esc_html(stripslashes($options['title_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title Background Color', 'toggle-showhide'); ?></th><td>
					<input type="text" name="bg_title" value="<?php echo esc_html(stripslashes($options['bg_title'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default title background color (e.g. 1px solid #007FFF)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title Font Family', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title_font" value="<?php echo esc_html(stripslashes($options['title_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default title font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title Font Size', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title_font_size" value="<?php echo esc_html(stripslashes($options['title_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Title Font Weight', 'toggle-showhide'); ?></th><td>
					<input type="text" name="title_font_weight" value="<?php echo esc_html(stripslashes($options['title_font_weight'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default title font weight (e.g. 600)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Text Color', 'toggle-showhide'); ?></th><td>
					<input type="text" name="text_color" value="<?php echo esc_html(stripslashes($options['text_color'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default text color (e.g. #000)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Text Background Color', 'toggle-showhide'); ?></th><td>
					<input type="text" name="bg_text" value="<?php echo esc_html(stripslashes($options['bg_text'])); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e('Set default bg_text (e.g. 1px solid #007FFF)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Text Font Family', 'toggle-showhide'); ?></th><td>
					<input type="text" name="text_font" value="<?php echo esc_html(stripslashes($options['text_font'])); ?>" class="large-text" />
					<p class="description"><?php esc_html_e('Set default text font family (e.g. Arial, Calibri)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Text Font Size', 'toggle-showhide'); ?></th><td>
					<input type="text" name="text_font_size" value="<?php echo esc_html(stripslashes($options['text_font_size'])); ?>" class="small-text" />
					<p class="description"><?php esc_html_e('Set default text font size (e.g. 1.2em or 14px)', 'toggle-showhide'); ?></p>
				</td></tr>
				
				<tr><th scope="row"><?php esc_html_e('Text Font Weight', 'toggle-showhide'); ?></th><td>
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
				<input type="submit" value="<?php esc_html_e('Submit', 'toggle-showhide'); ?>" class="button-primary"/>
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
	
	$options = azrcrv_tsh_get_option('azrcrv-tsh');
	
	if ($options['use_multisite'] == 1){
		$options = get_site_option('azrcrv-tsh');
	}
	
	// extract attributes from shortcode
	$args = shortcode_atts(array(
		'style' => stripslashes($options['default-style']),
	), $atts);
	$style = $args['style'];
	
	if ($style == 1){
		$output = azrcrv_tsh_display_toggle_style1($atts, $content, $options);
	}else{
		$output = azrcrv_tsh_display_toggle_style2($atts, $content, $options);
	}
	
	return $output;
}

/**
 * Display Toggle via shortcode.
 *
 * @since 1.4.0
 *
 */
function azrcrv_tsh_display_toggle_style1($atts, $content, $options){
	
	// extract attributes from shortcode
	$args = shortcode_atts(array(
		'title' => stripslashes($options['title']),
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
	if (strlen($bgtitle) > 0){ $background_title = "background-color: ".$bgtitle."; "; }else{ $background_title = ''; }
	if (strlen($bgtext) > 0){ $background_text = "background-color: ".$bgtext."; "; }else{ $background_text = ''; }
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

/**
 * Display Toggle via shortcode.
 *
 * @since 1.4.0
 *
 */
function azrcrv_tsh_display_toggle_style2($atts, $content, $options){
	
	// extract attributes from shortcode
	$args = shortcode_atts(array(
		//'button-text' => stripslashes($options['style2']['button-text']),
		'test' => 'test'
	), $atts);
	
	$test = $args['test'];
	
	if($options['allow_shortcodes'] == 1){
		$content = do_shortcode($content);
	}
	
	$content_split = explode('<!--readmore-->', $content);
	
	$button_text = esc_attr($options['style2']['read-more']);
	if (isset($content_split[1])){
		$content_one = $content_split[0];
		$content_two = $content_split[1];
		$output = "<p class='azrcrv-tsh-container'>$content_one<div class='azrcrv-tsh-readmore'>$content_two</div><a class='azrcrv-tsh-readmore-button' href='#'>$button_text</a></p><p style='clear: both; ' />";
	}else{
		$output = $content;
	}
	return $output;
}

