<?php
/*basic hook */
add_filter('weixin_token',					'wpjam_basic_filter');
add_filter('weixin_default',				'wpjam_basic_filter');
add_filter('weixin_welcome',				'wpjam_basic_filter');
add_filter('weixin_voice',					'wpjam_basic_filter');
add_filter('weixin_keyword_allow_length',	'wpjam_basic_filter');
add_filter('weixin_keyword_too_long',		'wpjam_basic_filter');
add_filter('weixin_count',					'wpjam_basic_filter');
add_filter('weixin_not_found',				'wpjam_basic_filter');

function wpjam_basic_filter($original){
	$weixin_robot_basic = weixin_robot_get_basic_option();

	global $wp_current_filter;

	//最后一个才是当前的 filter
	$wpjam_current_filter = $wp_current_filter[count($wp_current_filter)-1];

	if(isset($weixin_robot_basic[$wpjam_current_filter])){
		if($weixin_robot_basic[$wpjam_current_filter ]){
			return $weixin_robot_basic[$wpjam_current_filter];
		}
	}else{
		return $original;
	}
}

/*advanced hook */

add_filter('weixin_custom_keywords','wpjam_advanced_weixin_custom_keywords');
function wpjam_advanced_weixin_custom_keywords($keywords){

	$wpjam_advanced_keywords = array();

	$weixin_robot_advanced = array_flip(weixin_robot_get_advanced_option());

	$wpjam_advanced_keywords = array_keys($weixin_robot_advanced);
	
	return array_merge($keywords,$wpjam_advanced_keywords);
}

add_action('weixin_robot','wpjam_advanced_weixin_robot');
function wpjam_advanced_weixin_robot($keyword){

	$weixin_robot_advanced = array_flip(weixin_robot_get_advanced_option());

	if(isset($weixin_robot_advanced[$keyword])){
		if($weixin_robot_advanced[$keyword] == 'new') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_new');
		}elseif($weixin_robot_advanced[$keyword] == 'rand') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_rand');
		}elseif($weixin_robot_advanced[$keyword] == 'hot') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_hot');
		}elseif($weixin_robot_advanced[$keyword] == 'comment') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_comment');
		}elseif($weixin_robot_advanced[$keyword] == 'hot-7') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_hot');
			add_filter('posts_where', 'wpjam_advanced_filter_where_7' );
		}elseif($weixin_robot_advanced[$keyword] == 'comment-7') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_comment');
			add_filter('posts_where', 'wpjam_advanced_filter_where_7' );
		}elseif($weixin_robot_advanced[$keyword] == 'hot-30') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_hot');
			add_filter('posts_where', 'wpjam_advanced_filter_where_30' );
		}elseif($weixin_robot_advanced[$keyword] == 'comment-30') {
			add_filter('weixin_query','wpjam_advanced_weixin_query_comment');
			add_filter('posts_where', 'wpjam_advanced_filter_where_30' );
		}
		global $wechatObj;
		$wechatObj->query();
	}
}

function wpjam_advanced_weixin_query_new($weixin_query_array){
	return array( 'posts_per_page' => $weixin_query_array['posts_per_page'] , 'post_status' => $weixin_query_array['post_status'],'post_type'=>'any','ignore_sticky_posts' => 1);
}

function wpjam_advanced_weixin_query_rand($weixin_query_array){
	return array( 'posts_per_page' => $weixin_query_array['posts_per_page'] , 'post_status' => $weixin_query_array['post_status'],'post_type'=>'any','ignore_sticky_posts' => 1, 'orderby' => 'rand');
}

function wpjam_advanced_weixin_query_hot($weixin_query_array){
	return array( 'posts_per_page' => $weixin_query_array['posts_per_page'] , 'post_status' => $weixin_query_array['post_status'],'post_type'=>'any','ignore_sticky_posts' => 1, 'meta_key' => 'views', 'orderby' => 'meta_value_num');
}

function wpjam_advanced_weixin_query_comment($weixin_query_array){
	return array( 'posts_per_page' => $weixin_query_array['posts_per_page'] , 'post_status' => $weixin_query_array['post_status'],'post_type'=>'any','ignore_sticky_posts' => 1, 'orderby' => 'comment_count');
}

function wpjam_advanced_filter_where_7( $where = '' ) {
	$where .= " AND post_date > '" . date('Y-m-d', strtotime('-7 days')) . "'";
	return $where;
}

function wpjam_advanced_filter_where_30( $where = '' ) {
	$where .= " AND post_date > '" . date('Y-m-d', strtotime('-60 days')) . "'";
	return $where;
}

//如果搜索关键字是分类名或者 tag 名，直接返回该分类或者tag下最新日志
add_filter('weixin_query','wpjam_advanced_weixin_query_catgory_tag', 11);
function wpjam_advanced_weixin_query_catgory_tag($weixin_query_array){
	if(isset($weixin_query_array['s'])){
		global $wpdb;
		$term = $wpdb->get_row("SELECT term_id, taxonomy FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms USING ( term_id ) WHERE lower({$wpdb->prefix}terms.name) = '{$weixin_query_array['s']}' OR {$wpdb->prefix}terms.slug = '{$weixin_query_array['s']}' LIMIT 0 , 1");

		if($term){
			if($term->taxonomy == 'category'){
				$weixin_query_array = array('cat' => $term->term_id, 'posts_per_page' => $weixin_query_array['posts_per_page'], 'post_status' => $weixin_query_array['post_status'], 'ignore_sticky_posts' => 1 );
			}elseif ($term->taxonomy == 'post_tag') {
				$weixin_query_array = array('tag_id' => $term->term_id, 'posts_per_page' => $weixin_query_array['posts_per_page'], 'post_status' => $weixin_query_array['post_status'], 'ignore_sticky_posts' => 1 );
			}
		}
	}
	return $weixin_query_array;
}

/* custom table hook */
function wpjam_get_weixin_custom_keywords(){
	global $wpdb;

	$weixin_custom_keywords = wp_cache_get('weixin_custom_keywords');

	if($weixin_custom_keywords === false){
		$weixin_custom_keywords_table = weixin_robot_get_custom_replies_table();
		$weixin_custom_original_keywords = $wpdb->get_results("SELECT keyword,reply,type FROM $weixin_custom_keywords_table WHERE status = 1",OBJECT_K);
		
		$weixin_custom_keywords = array(); 
		if($weixin_custom_original_keywords){
			foreach ($weixin_custom_original_keywords as $key => $value) {
				if(strpos($key,',')){
					foreach (explode(',', $key) as $new_key) {
						$new_key = strtolower(trim($new_key));
						if($new_key){
							$weixin_custom_keywords[$new_key] = $value;
						}
					}
				}else{
					$weixin_custom_keywords[strtolower($key)] = $value;
				}
			}
		}

		wp_cache_set('weixin_custom_keywords',$weixin_custom_keywords);
	}
	return $weixin_custom_keywords;
}

add_filter('weixin_custom_keywords','wpjam_weixin_custom_keywords');
function wpjam_weixin_custom_keywords($keywords){

	$weixin_custom_keywords = wpjam_get_weixin_custom_keywords();

	$weixin_custom_keywords = array_keys($weixin_custom_keywords);
	
	return array_merge($keywords,$weixin_custom_keywords);
}

add_action('weixin_robot','wpjam_custom_weixin_robot');
function wpjam_custom_weixin_robot($keyword){
	global $wpdb, $wechatObj;
	
	$weixin_custom_keywords = wpjam_get_weixin_custom_keywords();

	if(isset($weixin_custom_keywords[$keyword]) ) {
		if($weixin_custom_keywords[$keyword]->type == 'text'){
			$wechatObj->set_response('custom-text');
			echo sprintf($wechatObj->get_textTpl(), $weixin_custom_keywords[$keyword]->reply);
		}elseif($weixin_custom_keywords[$keyword]->type == 'img'){
			add_filter('weixin_query','wpjam_custom_weixin_query_img_repy');
			$wechatObj->set_response('custom-img');
			$wechatObj->query();
		}
	}
}

function wpjam_custom_weixin_query_img_repy($weixin_query_array){
	$weixin_custom_keywords = wpjam_get_weixin_custom_keywords();
	$post_ids = explode(',', $weixin_custom_keywords[$weixin_query_array['s']]->reply);
	return array( 'post_status' => $weixin_query_array['post_status'],	'posts_per_page' => $weixin_query_array['posts_per_page'], 'post__in'=>$post_ids,	'orderby'=>'post__in',	'post_type'=>'any');
}


