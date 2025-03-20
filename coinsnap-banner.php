<?php
/**
 * Plugin Name: Coinsnap Banner
 * Description: A simple banner plugin with WYSIWYG editor, dismissible with cookies, and WPML support.
 * Version: 1.0.0
 * Author: Alex @ Coinsnap
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Register settings
function wptb_register_settings() {
	add_option('wptb_banner_message', '');
	register_setting('wptb_options_group', 'wptb_banner_message');
}
add_action('admin_init', 'wptb_register_settings');

// Add settings page
function wptb_register_menu() {
	add_menu_page('Coinsnap Banner Settings', 'Coinsnap Banner', 'manage_options', 'wptb-settings', 'wptb_settings_page');
}
add_action('admin_menu', 'wptb_register_menu');

// Settings page content
function wptb_settings_page() {
	?>
  <div class="wrap">
    <h2>Coinsnap Banner Settings</h2>
    <form method="post" action="options.php">
		<?php settings_fields('wptb_options_group'); ?>
      <label for="wptb_banner_message">Banner Message:</label>
		<?php wp_editor(get_option('wptb_banner_message'), 'wptb_banner_message'); ?>
      <input type="submit" name="submit" value="Save Changes" class="button-primary" />
    </form>
  </div>
	<?php
}

// Display banner
function wptb_display_banner() {
	if (!isset($_COOKIE['wptb_dismissed']) && ($message = get_option('wptb_banner_message'))) {
		echo '<div id="wptb-banner">';
		echo '<div class="wptb-message">' . apply_filters('wpml_translate_single_string', $message, 'Coinsnap Banner', 'Banner Message') . '</div>';
		echo ' <a id="wptb-dismiss">✕</a>';
		echo '</div>';

		// Add dynamic body margin via inline style
		$admin_bar_offset = is_admin_bar_showing() ? 32 : 0;
		echo '<style>body { margin-top: ' . ($admin_bar_offset + 10) . 'px; }</style>';
	}
}
add_action('wp_footer', 'wptb_display_banner');

// Enqueue scripts and styles
function wptb_enqueue_scripts() {
	// Enqueue JavaScript
	wp_enqueue_script('wptb-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), '1.0.0', true);

	// Enqueue CSS
	wp_enqueue_style('wptb-styles', plugin_dir_url(__FILE__) . 'css/styles.css', array(), '1.0.0');

	// Pass admin bar offset to CSS
	$admin_bar_offset = is_admin_bar_showing() ? 32 : 0;
	$custom_css = "#wptb-banner { top: {$admin_bar_offset}px; }";
	wp_add_inline_style('wptb-styles', $custom_css);
}
add_action('wp_enqueue_scripts', 'wptb_enqueue_scripts');

// WPML String Registration
function wptb_register_wpml_strings() {
	do_action('wpml_register_single_string', 'Coinsnap Banner', 'Banner Message', get_option('wptb_banner_message'));
}
add_action('updated_option', 'wptb_register_wpml_strings', 10, 2);
