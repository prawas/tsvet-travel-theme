<?php

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize
 */
function tsvet_customize_register($wp_customize) {

}
add_action('customize_register', 'tsvet_customize_register');

/**
 * Load dynamic logic for the customizer controls area.
 */
function tsvet_customize_control_js() {
    wp_enqueue_script('tsvet-customize-control', get_template_directory_uri() . '/js/customize-controls.js', ['jquery'], null, true);
}
add_action('customize_controls_enqueue_scripts', 'tsvet_customize_control_js');

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 */
function tsvet_customize_preview_js() {
    wp_enqueue_script('tsvet-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', ['customize-preview'], null, true);
}
add_action('customize_preview_init', 'tsvet_customize_preview_js');

function tsvet_tiny_mce_before_init($init) {
	$init['font_formats'] = "SFUIDisplay=SFUIDisplay;Andale Mono=andale mono,monospace;Arial=arial,helvetica,sans-serif;Arial Black=arial black,sans-serif;Book Antiqua=book antiqua,palatino,serif;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Helvetica=helvetica,arial,sans-serif;Impact=impact,sans-serif;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco,monospace;Times New Roman=times new roman,times,serif;Trebuchet MS=trebuchet ms,geneva,sans-serif;Verdana=verdana,geneva,sans-serif;Webdings=webdings;Wingdings=wingdings,zapf dingbats;BigNoodleTitlingCyr=BigNoodleTitlingCyr";
	$init['fontsize_formats'] = "8px";
	for($i = 9; $i < 151; $i++) {
		$init['fontsize_formats'] .= " {$i}px";
	}
	return $init;
}
add_filter('tiny_mce_before_init', 'tsvet_tiny_mce_before_init');

function tsvet_init() {
	add_editor_style('src/css/editor-style.css');
}
add_action('init', 'tsvet_init');