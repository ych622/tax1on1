<?php
/*
Plugin Name: WP anti spam dali
Plugin URI: http://www.ludou.org/wp-anti-spam-dali.html
Description: 过滤垃圾评论，处理评论内容
Author: 露兜
Version: 1.0
Author URI: http://www.ludou.org
*/

// 初始化插件选项
register_activation_hook(__FILE__, 'dali_set_options');

function dali_set_options() {
	// 插件选项初始化
	$options = array();
	$options['anti_robots'] 		= 	0;
	$options['min_words'] 			= 	0;
	$options['max_words'] 			= 	0;
	$options['chinese_only'] 		= 	0;
	$options['words_replace'] 	= 	'';
	$options['admin_name'] 			= 	'';
	$options['admin_email'] 		= 	'';
	$options['code_escape'] 		= 	0;
	$options['delete_links'] 		= 	0;
	$options['delete_or_move']	= 	'spam';

	update_option('wp_antispamdali_options', $options);
}

function dali_get_options() {
	return get_option('wp_antispamdali_options');
}

if(is_admin()) {
	// 添加管理后台菜单
	require_once('dali-admin.php');
	add_action('admin_menu', 'updateDaliOptions');
}
else {
	require_once('dali-front.php');
	$daliOptions = dali_get_options();

	if (!isset($_SESSION) && $daliOptions['anti_robots'] != 0) {
		session_start();
		session_regenerate_id();
	}

	add_action( 'comment_form', 'dali_insert_hidden_field' );
	add_filter( 'preprocess_comment', 'preprocess' );
	add_filter( 'comment_text', 'display' );
	add_filter( 'comment_text_rss', 'display' );
}

// 停用插件时，删除插件选项
register_deactivation_hook( __FILE__, create_function('','delete_option("wp_antispamdali_options");') );

?>