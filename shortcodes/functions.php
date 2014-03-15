<?php

//Courtesy Alix Axel, http://stackoverflow.com/questions/2762061/how-to-add-http-if-its-not-exists-in-the-url
function cur_add_http($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return esc_url( $url );
}

function cur_color_block( $atts, $content = null ) {
	extract( cur_shortcode_atts( array(
		'parent' => 'color-block',
		'child' => 'color-block'
	), $atts ) );
	$output = '<div class="color-block '. $class . ' ' . $size . '">';
	$output .= ( $title ) ? '<h2 class="headline ' . $color . ' bg ' . $headline_size . ' ' . $headline_class . '">' . $title . '</h2>' : '';
	$output .= ( $title ) ? '<div class="content">' : '';
	$output .= do_shortcode($content);
	$output .= ( $title ) ? '</div>': '';
	$output .= '</div>';
	return $output;
}

function cur_color_block_headline( $atts, $content = null ) {
	extract( cur_shortcode_atts( array(
		'parent' => 'color-block',
		'child' => 'headline'
	), $atts ) );

	$output = '<h2 class="headline ' . $color . ' bg ' . $size . ' ' . $class . '">';
	$output .= do_shortcode($content);
	$output .= '</h2>';

	return $output;
}

function cur_color_block_content( $atts, $content = null ) {
   return '<div class="content">' . do_shortcode($content) . '</div>';
}

function cur_color_block_footer( $atts, $content = null ) {
   return '<div class="footer">' . do_shortcode($content) . '</div>';
}

function cur_button( $atts, $content = null ) {
	extract( cur_shortcode_atts(
	'button'
	, $atts ) );
	return '<a href="' . cur_add_http( $link ) . '" class="button ' . $color . ' ' . $size . ' ' . $class . '">' . do_shortcode($content) . '</a>';
}

function cur_small( $atts, $content = null ) {
	return '<small>' . do_shortcode($content) . '</small>';
}
