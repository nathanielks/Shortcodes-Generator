<?php

function cur_color_block( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'class' => '',
		'size' => '',
		'title' => '',
		'color' => '',
		'headline_size' => '',
		'headline_class' => ''
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
	extract( shortcode_atts( array(
		'color' => '',
		'size' => '',
		'class' => '',
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
	extract( shortcode_atts( array(
		'link' => '',
		'color' => '',
		'size' => '',
		'class' => '',
	), $atts ) );
	return '<a href="' . addhttp( $link ) . '" class="button ' . $color . ' ' . $size . ' ' . $class . '">' . do_shortcode($content) . '</a>';
}

function cur_small( $atts, $content = null ) {
	return '<small>' . do_shortcode($content) . '</small>';
}
