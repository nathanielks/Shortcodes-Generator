<?php

require_once 'class-cur-shortcode-generator.php';
require_once 'shortcodes.php';

//move wpautop filter to AFTER shortcode is processed
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );


/**
 * Queue admin menu icons CSS.
 *
 * @access public
 * @return void
 */
function cur_shortcodes_button_css() {
	wp_enqueue_style( 'cur_shortcodes_button_css', get_template_directory_uri() . '/inc/shortcodes/assets/css/shortcodes.css' );
}

add_action( 'admin_print_styles', 'cur_shortcodes_button_css' );

/**
 * Add a button for shortcodes to the WP editor.
 *
 * @access public
 * @return void
 */
function cur_add_shortcode_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'cur_add_shortcode_tinymce_plugin');
		add_filter('mce_buttons', 'cur_register_shortcode_button');
	endif;
}


/**
 * Register the shortcode button.
 *
 * @access public
 * @param mixed $buttons
 * @return array
 */
function cur_register_shortcode_button($buttons) {
	array_push($buttons, "|", "cur_shortcodes_button");
	return $buttons;
}


/**
 * Add the shortcode button to TinyMCE
 *
 * @access public
 * @param mixed $plugin_array
 * @return array
 */
function cur_add_shortcode_tinymce_plugin($plugin_array) {
	$plugin_array['CurShortcodes'] = get_template_directory_uri() . '/inc/shortcodes/assets/js/editor-plugin.js';
	return $plugin_array;
}


/**
 * Force TinyMCE to refresh.
 *
 * @access public
 * @param mixed $ver
 * @return int
 */
function cur_refresh_mce( $ver ) {
	$ver += 3;
	return $ver;
}

/**
 * Shortcode buttons
 *
 * @see cur_add_shortcode_button()
 * @see cur_refresh_mce()
 */
add_action( 'init', 'cur_add_shortcode_button' );
add_filter( 'tiny_mce_version', 'cur_refresh_mce' );


/**
 *  Intialize the shortcodes!!
 */

$path = dirname( __FILE__ );
$shortcodes_array_path = $path . '/shortcodes-array.php';
$editor_plugin_path = $path . '/assets/js/editor-plugin.js';

new Cur_Shortcodes_Generator( $shortcodes_array_path, $editor_plugin_path );
