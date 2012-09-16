<?php

class Cur_Shortcodes_Generator{

	//check if array is newer that js file
	//if so, continue, else, do nothing
	//get shortcodes array
	//compile

	var $shortcodes;
	var $generate_output = false;

	function __construct( $in, $out ) {

		$this->in = $in;
		$this->out = $out;

		require_once $in;

		$this->shortcodes = $shortcodes;

		if (!is_file($out) || filemtime($in) > filemtime($out)) {
			$this->generate_output = true;
		}

		$this->generate_shortcodes( $out, $shortcodes );

	}
	
	function generate_shortcodes( $out, $shortcodes ){

		$data = $this->parse_shortcodes( $shortcodes );

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
