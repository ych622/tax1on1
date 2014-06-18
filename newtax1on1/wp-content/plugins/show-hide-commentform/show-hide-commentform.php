<?php
/**
 * Plugin Name: Show/Hide Commentform
 * Plugin URI: http://blog.ppfeufer.de/wordpress-kommentarformular-per-jquery-einblenden-lassen/
 * Description: Toggles the visibilty of your commentform.
 * Version: 1.0.5
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */

/**
 * Sprachdatei wählen
 */
if(function_exists('load_plugin_textdomain')) {
	load_plugin_textdomain('show-hide-commentform', false, dirname(plugin_basename( __FILE__ )) . '/l10n/');
}

/**
 * Lade CSS in den Haderbereich
 */
if(!function_exists('shcf_load_css')) {
	function shcf_load_css() {
		$var_sSHCF_Css = "\n";
		$var_sSHCF_Css .= '<style type="text/css">' . "\n";
		$var_sSHCF_Css .= '#commentform-slide {display:block; width:20px; height:16px; cursor:pointer; background-image:url("data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAABQAAAAQCAYAAAAWGF8bAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQRJREFUeNpi/P//PwM1AQtW0XnqCkAyAYj9gdgATfYBEB8A4okMSTcvoGtlxHDhPPUGIJkPxAJEOGgDECcCDf6A3cB56vOhLiMFgFzpCDOUCcmwBDIMY4AGyXoYhwlJop6CuHAAOsgA3UAFNEUToN6BhdUGKBvktUYojQwC0A1Ej8lGaGyCwEIgLoQbnnSzAaoGb7L5gBSzINe+R5Jbj+SiBKD3ArCkgg/oLtxAIJwEcLBR9CMbWIglXIgFjcBgeIBqICQdLSDDsAXQMGXAFikfSTAI5KJAoGGJ+PKyPFIAg1x7EJpw9aHhBjLkITSmLxBTOCiA8yZEwwciI4tA4UAhAAgwAKW4TCsIsCVRAAAAAElFTkSuQmCC")}' . "\n";
		$var_sSHCF_Css .= '#respond {display:none}' . "\n";
		$var_sSHCF_Css .= '</style>' . "\n";

		echo $var_sSHCF_Css;
	}

	if(!is_admin()) {
		add_action('wp_head', 'shcf_load_css');
	}
}

/**
 * Lade das jQuery
 */
if(!function_exists('shcf_load_javascript')) {
	function shcf_load_javascript() {
		$var_sSHCF_Js = "\n";
		$var_sSHCF_Js .= '<script type="text/javascript">' . "\n";
		$var_sSHCF_Js .= 'jQuery(document).ready(function() {' . "\n";
		$var_sSHCF_Js .= "\t" . 'jQuery("#commentform-slide").click(function() {' . "\n";
		$var_sSHCF_Js .= "\t\t" . 'jQuery(this).next("div").slideToggle("slow");' . "\n";
		$var_sSHCF_Js .= "\t" . '});' . "\n";
		$var_sSHCF_Js .= '});' . "\n";
		$var_sSHCF_Js .= '</script>' . "\n";

		echo $var_sSHCF_Js;
	}

	if(!is_admin()) {
		wp_enqueue_script('jquery');
		add_action('wp_footer', 'shcf_load_javascript');
	}
}

/**
 * Läd das notwendige HTML vor das Kommentarformular
 */
if(!function_exists('hcf_slide_html_before')) {
	function shcf_slide_html_before() {
		$var_sHTML_before = '<span id="commentform-slide" title="' . __('Show/Hide Commentform', 'show-hide-commentform') . '">&nbsp;</span>' . "\n";

		echo $var_sHTML_before;
	}

	if(!is_admin()) {
		add_action('comment_form_before', 'shcf_slide_html_before');
	}
}
?>