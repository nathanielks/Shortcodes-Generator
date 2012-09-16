<?php

/*
Plugin Name: Shortcodes Generator
Plugin URI: http://fightthecurrent.org/plugins/shortcodes-generator
Description: A plugin to generate shortcodes and a corresponding button in the WordPress visual editor. Wicked!
Version: 1.0
Author: Nathaniel Schweinberg
Author URI: http://fightthecurrent.org
Author Email: nathaniel@fightthecurrent.org

License:

  Copyright 2012 Nathaniel Schweinberg (nathaniel@fightthecurrent.org)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/


class Cur_Shortcodes_Generator{

	var $shortcodes;
	var $generate_output = false;
	var $plugin_path;
	var $theme_path;

	function __construct() {
	
		$theme = wp_get_theme();
		$this->theme_path = $theme->theme_root . '/' . $theme->template . '/';

		$shortcodes =& $this->get_shortcodes_array();
		if ( $shortcodes ) {

			if( !isset( $shortcodes['title'] ){
				$shortcodes['title'] = 'CurShortcodes';
			}
			if( !isset( $shortcodes['slug'] ){
				$shortcodes['slug'] = 'cur_shortcodes_button';
			}
			$this->shortcodes = $shortcodes;


			$this->plugin_path = dirname( __FILE__ );

			// Require related shortcodes functions, if they exist
			$this->get_shortcodes_functions();

			/**
			 * Shortcode buttons
			 *
			 * @see add_shortcode_button()
			 * @see refresh_mce()
			 */
			add_action( 'init', array( &$this, 'add_shortcode_button') );
			add_filter( 'tiny_mce_version', array( &$this, 'refresh_mce' ) );
			add_action( 'admin_print_styles', array( &$this, 'shortcodes_button_css' ) );

			add_action( 'init', array( &$this, 'generate_shortcodes' ) );
	
		}
		else {
			// Display a notice if options aren't present in the theme
			add_action('admin_notices', array( &$this, 'admin_notice') );
			add_action('admin_init', array(&$this, 'nag_ignore') );
		}

	}

	function get_shortcodes_array(){

		$theme = wp_get_theme();

		// Load shortcodes from shortcodes/array.php file (if it exists)
		$location = apply_filters( 'cur_shortcodes_array_location', 'shortcodes/array.php' );
		$location_path = $this->theme_path . $location;

		if ( file_exists( $location_path ) ) {

			$maybe_shortcodes = require_once $location_path;

			if ( !is_array( $maybe_shortcodes ) && function_exists('cur_shortcodes_generator_shortcodes') ) {
				$shortcodes = cur_shortcodes_generator_shortcodes();
			}

			return $shortcodes;

		}
		return false;
	}

	function get_shortcodes_functions(){

		$theme = wp_get_theme();

		// Load shortcodes from shortcodes/functions.php file (if it exists)
		$location = apply_filters( 'cur_shortcodes_functions_location', 'shortcodes/functions.php' );
		$location_path = $this->theme_path . $location;

		if ( file_exists( $location ) ) {

			require_once $location_path;

		}

	}

	function admin_notice() {

		global $pagenow;
		if ( $pagenow == 'plugins.php' ||  $pagenow == 'themes.php' ) {
			global $current_user;
			$user_id = $current_user->ID;
			if ( ! get_user_meta($user_id, 'cur_shortcodes_generator_ignore_notice') ) {
				echo '<div class="updated cur_shortcodes_generator_setup_nag"><p>';
				printf( __('Your current theme does not have support for the Shortcodes Generator plugin.  <a href="%1$s" target="_blank">Learn More</a> | <a href="%2$s">Hide Notice</a>', 'cur-shortcodes-generator'), 'http://fightthecurrent.org/plugins/shortcodes-generator', '?cur_shortcodes_generator_nag_ignore=0');
				echo "</p></div>";
			}
		}
	}

	function nag_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( isset($_GET['cur_shortcodes_generator_nag_ignore']) && '0' == $_GET['cur_shortcodes_generator_nag_ignore'] ) {
			add_user_meta($user_id, 'cur_shortcodes_generator_ignore_notice', 'true', true);
		}
	}

	/**
	 * Queue admin menu icons CSS.
	 *
	 * @access public
	 * @return void
	 */
	function shortcodes_button_css() {

		$css_path = apply_filters('cur_shortcodes_css_location', 'shortcodes/shortcodes.css');

		if ( file_exists( $this->theme_path . $css_path ) ){ 
			wp_enqueue_style( $this->slug . '_css', get_template_directory_uri() . '/' . $css_path );
		} else {
			wp_enqueue_style( 'cur_shortcodes_button_css', plugins_url('/assets/css/shortcodes.css', __FILE__ ) );
		}
	}

	/**
	 * Add a button for shortcodes to the WP editor.
	 *
	 * @access public
	 * @return void
	 */
	function add_shortcode_button() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
		if ( get_user_option('rich_editing') == 'true') :
			add_filter('mce_external_plugins', array( &$this, 'add_shortcode_tinymce_plugin' ) );
			add_filter('mce_buttons', array( &$this, 'register_shortcode_button') );
		endif;
	}


	/**
	 * Register the shortcode button.
	 *
	 * @access public
	 * @param mixed $buttons
	 * @return array
	 */
	function register_shortcode_button($buttons) {
		array_push($buttons, "|", $this->shortcodes['slug'] );
		return $buttons;
	}


	/**
	 * Add the shortcode button to TinyMCE
	 *
	 * @access public
	 * @param mixed $plugin_array
	 * @return array
	 */
	function add_shortcode_tinymce_plugin($plugin_array) {
		$plugin_array[ $this->shortcodes['title'] ] = plugins_url( '/assets/js/editor-plugin.js', __FILE__ );
		return $plugin_array;
	}


	/**
	 * Force TinyMCE to refresh.
	 *
	 * @access public
	 * @param mixed $ver
	 * @return int
	 */
	function refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}


	function generate_shortcodes( $out, $shortcodes ){

		$editor_plugin_path = $this->plugins_path . '/assets/js/editor-plugin.js';

		if (!is_file($editor_plugin_path) || filemtime($in) > filemtime($out)) {
			$this->generate_output = true;
		}

		$data = $this->parse_shortcodes( $this->shortcodes );

		if ( $data ){
			$this->compile( $data, $out );
		}

	}

	function parse_shortcodes( $shortcodes, $isChildren = 0, $isSelectable = 0, $tag = '' ){

		$output = '';

		foreach ( $shortcodes as $sc ){

			if ( !is_array( $sc ) && !$isChildren ){
				continue;
			} elseif ( $isChildren && !is_array( $sc ) ){
				$title = $sc;	
			} else{
				extract( $sc );
			}
			$tag = ( isset( $tag ) ) ? $tag : '';
			$function = ( isset( $function ) ) ? $function : '';
			$selectable = ( isset( $isSelectable ) ) ? 1 : ( isset( $selectable ) ) ? 1 : 0;
			$shortcode = ( !empty( $shortcode ) ) ? $shortcode : $sc;
			$title = ucwords( preg_replace( '[-_]', ' ', $shortcode ) );	

			if ( isset( $children ) && is_array( $children ) ){

				if( !$this->generate_output ){

					$this->parse_shortcodes( $children, 1, $selectable, $tag );

				} else {

					$output .= 'c=b.addMenu({title:"' . $title . '"});' . "\n";
					$output .= $this->parse_shortcodes( $children, 1, $selectable, $tag );
				}

				unset( $children );

			} else {

				$this->add_shortcode( $shortcode, $function, $tag );

				if( $this->generate_output ){

					$scope = 'b';
					$prefix = '';

					if( $isChildren == 1 ){
						$scope = 'c';
						$prefix = "\t";
					}
					$params = ( isset( $params ) && is_array( $params ) ) ? $this->parse_parameters( $params ) : '';

					if ( $selectable ){
						$output .= $prefix . 'a.addSelectable(' . $scope . ', \'' .$title. '\' , \'[' .$shortcode . $params. ']\', \'[/' .$shortcode. ']\');' . "\n";
					} else {
						$output .= $prefix . 'a.addImmediate(' . $scope . ', "' .$title. '" , "[' .$shortcode . $params. ']");' . "\n";
					}
			
					unset( $shortcode );

				}

			}
				
		}

		return $output;

	}
	
	function parse_parameters( $params ){

		$output = '';
		if ( $this->is_assoc( $params ) ){
			foreach( $params as $k => $v ){
				$output .= ' ' . $k . '="' .$v. '"';
			}
		} else {
			foreach( $params as $p ){
				$output .= ' ' . $p . '=""';
			}

		}

		return $output;
	}

	function is_assoc($array) {
	  return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	function add_shortcode( $shortcode = '', $function = '', $tag = '' ){

		if( empty( $function ) ){
			return $this->add_simple_shortcode( $shortcode, $tag );
		}	
		
		return add_shortcode( $shortcode, $function );
			
	}

	function add_simple_shortcode( $shortcode, $tag ){

		$tag = ( isset( $tag ) ) ? $tag : 'div';

		$sc = function( $atts, $content = null ) use ( $shortcode, $tag ){
			return '<' . $tag . ' class="' . $shortcode . '">' . do_shortcode($content) . '</' . $tag . '>';
		};

		return add_shortcode($shortcode, $sc);

	}

	function compile( $data, $output ){
		

		$file_start = '
(
	function(){
	
		tinymce.create(
			"tinymce.plugins.'. $this->shortcodes['title'] . '",
			{
				init: function(d,e) {},
				createControl:function(d,e)
				{
				
					if(d=="' . $this->shortcodes['slug'] . '"){
					
						d=e.createMenuButton( "' . $this->shortcodes['slug'] . '",{
							title:"Insert Shortcode",
							icons:false
							});
							
							var a=this;
                            d.onRenderMenu.add(function(c,b){
';

$file_end = '
							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
                addImmediate: function (d,e,a){
                    d.add({
                        title:e,
                        onclick:function(){ 
                            tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)
                        }
                    })
                },
                addSelectable: function (d,e,open,close,a){
                    d.add({
                        title: e,
                        onclick:function(){ 
                            //.execCommand( "mceInsertContent",false,a)
                            tinyMCE.activeEditor.selection.setContent(open + tinyMCE.activeEditor.selection.getContent() + close);
                        }
                    })
                }
				
			}
		);
		
		tinymce.PluginManager.add( "' . $this->shortcodes['title'] . '", tinymce.plugins.' . $this->shortcodes['title'] . ');
	}
)();
';

		$file_content = $file_start . $data . $file_end;

		return file_put_contents( $output, $file_content );


	}
}

new Cur_Shortcodes_Generator();
