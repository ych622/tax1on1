<?php
/*
Plugin Name: 微信机器人高级版
Plugin URI: http://blog.wpjam.com/project/weixin-robot-advanced/
Description: 微信机器人的主要功能就是能够将你的公众账号和你的 WordPress 博客联系起来，搜索和用户发送信息匹配的日志，并自动回复用户，让你使用微信进行营销事半功倍。
Version: 2.2
Author: Denis
Author URI: http://blog.wpjam.com/
*/

//定义微信 Token
define("WEIXIN_TOKEN", "weixin");

//定义默认缩略图
define("WEIXIN_DEFAULT", '');

add_action('parse_request', 'wpjam_weixin_robot_redirect', 4);
function wpjam_weixin_robot_redirect($wp){
	if(isset($_GET['weixin']) ){
		global $wechatObj;
		if(!isset($wechatObj)){
			//file_put_contents(WP_CONTENT_DIR.'/uploads/weixin.log',var_export($_SERVER,true));
			$wechatObj = new wechatCallback();
			$wechatObj->valid();
			exit;
		}
	}
}

class wechatCallback {
	private $keyword = '';
	private $textTpl = '';
	private $picTpl = '';
	private $response = '';

	public function valid()
	{
		if(isset($_GET['debug'])){
			$this->keyword = strtolower(trim($_GET['t']));
			$this->checkSignature();
			$this->responseMsg();
		}else{
			//valid signature , option
			if($this->checkSignature()){
				if(isset($_GET["echostr"])){
					$echoStr = $_GET["echostr"];
					echo $echoStr;					
				}
				$this->responseMsg();
				exit;
			}
		}
	}

	public function set_response($response){
		$this->response = $response;
	}

	public function get_textTpl(){
		return $this->textTpl;
	}

	public function set_textTpl($fromUsername, $toUsername){
		$time = time();
		$this->textTpl = "
			<xml>
				<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
				<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
				<CreateTime>".$time."</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
			</xml>
		";
	}

	public function get_picTpl(){
		return $this->picTpl;
	}

	public function set_picTpl($fromUsername, $toUsername){
		$time = time();
		$this->picTpl = "
			<xml>
				<ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
				<FromUserName><![CDATA[".$toUsername."]]></FromUserName>
				<CreateTime>".$time."</CreateTime>
				<MsgType><![CDATA[news]]></MsgType>
				<Content><![CDATA[]]></Content>
				<ArticleCount>%d</ArticleCount>
				<Articles>
				%s
				</Articles>
				<FuncFlag>1</FuncFlag>
			</xml>
		";
	}

	public function responseMsg()
	{
		//get post data, May be due to the different environments
		$postStr = (isset($GLOBALS["HTTP_RAW_POST_DATA"]))?$GLOBALS["HTTP_RAW_POST_DATA"]:'';

		//extract post data
		if (isset($_GET['debug']) || !empty($postStr)){	
			if(!isset($_GET['debug'])){
				$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				$fromUsername = $postObj->FromUserName;
				$toUsername = $postObj->ToUserName;
				$msgType = strtolower(trim($postObj->MsgType));
				if($msgType == 'event'){
					$event = strtolower(trim($postObj->Event));
					if($event == 'subscribe' || $event == 'unsubscribe'){
						$this->keyword = $event;					
					}elseif($event == 'click'){
						$this->keyword = strtolower(trim($postObj->EventKey));
					}elseif($event == 'view'){
						exit;
					}
				}elseif($msgType == 'text'){
					$this->keyword = strtolower(trim($postObj->Content));
				}elseif($msgType == 'voice'){
					$this->keyword = '[voice]';
				}
				$weixin_message_id = weixin_robot_insert_message($postObj);
			}else{
				$fromUsername = $toUsername = '';
			}

			if(empty( $this->keyword ) || strpos($this->keyword, '#') !== false ) {
				echo "";
				exit;
			}

			$this->set_textTpl($fromUsername, $toUsername);
			$this->set_picTpl($fromUsername, $toUsername);

			$textTpl	= $this->get_textTpl();  
			$picTpl		= $this->get_picTpl(); 

			$weixin_custom_keywords = apply_filters('weixin_custom_keywords',array());

			if($weixin_custom_keywords && in_array($this->keyword, $weixin_custom_keywords)){
				do_action('weixin_robot',$this->keyword);
			}elseif($this->keyword == 'hi' || $this->keyword == 'h' || $this->keyword == '您好' || $this->keyword == '你好' || $this->keyword == 'subscribe' ){
				$weixin_welcome = apply_filters('weixin_welcome',"请输入关键字开始搜索！");
				echo sprintf($textTpl, $weixin_welcome);
				$this->response = 'welcome';
			}elseif($this->keyword == 'unsubscribe' ){
				$weixin_unsubscribe = "你怎么忍心取消对我的订阅？";
				$this->response = 'byebye';
			}elseif($this->keyword == '[voice]' ){
				$weixin_voice = apply_filters('weixin_voice',"系统暂时还不支持语音回复，直接发送文本来搜索吧。\n获取更多帮助信息请输入：h。");
				if($weixin_voice){
					echo sprintf($textTpl, $weixin_voice);
				}
				$this->response = 'voice';
			}else {
				$keyword_length = mb_strwidth(preg_replace('/[\x00-\x7F]/','',$this->keyword),'utf-8')+str_word_count($this->keyword)*2;

				$weixin_keyword_allow_length = 16;
				$weixin_keyword_allow_length = apply_filters('weixin_keyword_allow_length',$weixin_keyword_allow_length);
		
				if($keyword_length > $weixin_keyword_allow_length){
					$weixin_keyword_too_long = apply_filters('weixin_keyword_too_long',"你输入的关键字太长了，系统没法处理了，请等待公众账号管理员到微信后台回复你吧。");
					if($weixin_keyword_too_long){
						echo sprintf($textTpl, $weixin_keyword_too_long);
					}
					$this->response = 'too-long';
				}elseif( !empty( $this->keyword )){
					$this->query();
				}
			}
			
			if(isset($this->response)){
				weixin_robot_update_message($weixin_message_id,$this->response);	
			}
			exit;
		}else {
			echo "";
			exit;
		}
	}

	public function query(){

		$weixin_count = apply_filters('weixin_count',5);

		$weixin_query_array = array( 's' => $this->keyword, 'posts_per_page' => $weixin_count , 'post_status' => 'publish' );
		$weixin_query_array = apply_filters('weixin_query',$weixin_query_array); 

		if(!$this->response){
			if(isset($weixin_query_array['s'])){
				$this->response = 'query';
			}elseif(isset($weixin_query_array['cat'])){
				$this->response = 'cat';
			}elseif(isset($weixin_query_array['tag_id'])){
				$this->response = 'tag';
			}elseif(isset($weixin_query_array['post__in'])){
				$this->response = 'custom-img';
			}else{
				$this->response = 'advanced';
			}
		}

		$weixin_robot_query = new WP_Query($weixin_query_array);

		$items = '';

		$counter = 1;

		if($weixin_robot_query->have_posts()){
			while ($weixin_robot_query->have_posts()) {
				$weixin_robot_query->the_post();

				global $post;

				$title =get_the_title(); 
				$excerpt = get_post_excerpt($post,120);

				$thumbnail_id = get_post_thumbnail_id($post->ID);
				if($thumbnail_id ){
					if($counter == 1){
						$thumb = wp_get_attachment_image_src($thumbnail_id, array(640,320));
					}else{
						$thumb = wp_get_attachment_image_src($thumbnail_id, array(80,80));
					}
					$thumb = $thumb[0];
				}else{
					$thumb = get_post_first_image($post->post_content);
				}

				if(empty($thumb)){
					$thumb = apply_filters('weixin_default',WEIXIN_DEFAULT);
				}
				
				$thumb = apply_filters('weixin_thumb',$thumb,$counter,$post);

				$link = get_permalink();

				$items = $items . $this->get_item($title, $excerpt, $thumb, $link);

				$counter ++;

			}
		}

		$articleCount = count($weixin_robot_query->posts);
		if($articleCount > $weixin_count) $articleCount = $weixin_count;

		if($articleCount == 0){
			$weixin_not_found = apply_filters('weixin_not_found', "抱歉，没有找到与【{$this->keyword}】相关的文章，要不你更换一下关键字，可能就有结果了哦 :-) ", $this->keyword);
			$weixin_not_found = str_replace('[keyword]', '【'.$this->keyword.'】', $weixin_not_found);
			if($weixin_not_found){
				echo sprintf($this->textTpl, $weixin_not_found);
			}
			$this->response = 'not-found';
		}else{
			echo sprintf($this->picTpl,$articleCount,$items);
		}
	}

	private function get_item($title, $description, $picUrl, $url){
		if(!$description) $description = $title;

		return
		'
		<item>
			<Title><![CDATA['.html_entity_decode($title, ENT_QUOTES, "utf-8" ).']]></Title>
			<Description><![CDATA['.html_entity_decode($description, ENT_QUOTES, "utf-8" ).']]></Description>
			<PicUrl><![CDATA['.$picUrl.']]></PicUrl>
			<Url><![CDATA['.$url.']]></Url>
		</item>
		';
	}

	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];	
				
		$weixin_token = apply_filters('weixin_token',WEIXIN_TOKEN);
		if(isset($_GET['debug'])){
			echo "\n".'WEIXIN_TOKEN：'.$weixin_token;
		}
		$tmpArr = array($weixin_token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

if(!function_exists('get_post_excerpt')){

	function get_post_excerpt($post,$width=120){
		$post_excerpt = strip_tags($post->post_excerpt); 
		if(!$post_excerpt){
			$post_excerpt = mb_strimwidth(strip_tags(do_shortcode($post->post_content)),0,$width,'...','utf-8');
		}
		return $post_excerpt;
	}
}

if(!function_exists('get_post_first_image')){

	function get_post_first_image($post_content){
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches);
		if($matches){	 
			return $matches[1][0];
		}else{
			return false;
		}
	}
}
//加强搜索相关性
if(!function_exists('wpjam_search_orderby')){

	add_filter('posts_orderby_request', 'wpjam_search_orderby',10,2);
	function wpjam_search_orderby($orderby,$wp_query){
		global $wpdb;

		$keyword = stripslashes($wp_query->query_vars['s']);

		if($keyword){ 

			$n = !empty($q['exact']) ? '' : '%';

			preg_match_all('/".*?("|$)|((?<=[\r\n\t ",+])|^)[^\r\n\t ",+]+/', $keyword, $matches);
			$search_terms = array_map('_search_terms_tidy', $matches[0]);

			$case_when = "0";

			foreach( (array) $search_terms as $term ){
				$term = esc_sql( like_escape( $term ) );

				$case_when .=" + (CASE WHEN {$wpdb->posts}.post_title LIKE '{$term}' THEN 3 ELSE 0 END) + (CASE WHEN {$wpdb->posts}.post_title LIKE '{$n}{$term}{$n}' THEN 2 ELSE 0 END) + (CASE WHEN {$wpdb->posts}.post_content LIKE '{$n}{$term}{$n}' THEN 1 ELSE 0 END)";
			}

			return "({$case_when}) DESC, {$wpdb->posts}.post_modified DESC";
		}else{
			return $orderby;
		}
	}
}

add_action('admin_head','weixin_robot_admin_head');
function weixin_robot_admin_head(){
	global $plugin_page;
	if(in_array($plugin_page, array('weixin-robot', 'weixin-robot-advanced', 'weixin-robot-custom-reply', 'weixin-robot-custom-menu', 'weixin-robot-stats', 'weixin-robot-summary', 'weixin-robot-messages', 'weixin-robot-about'))){
?>
	<style>
	#icon-weixin-robot{background-image: url("<?php echo WP_CONTENT_URL?>/plugins/weixin-robot-advanced/weixin-32.png");background-repeat: no-repeat;}
	</style>
<?php
	}
}

function weixin_robot_get_plugin_file(){
	return __FILE__;
}

add_action( 'admin_menu', 'weixin_robot_admin_menu' );
function weixin_robot_admin_menu() {
	add_menu_page(						'微信机器人',						'微信机器人',	'manage_options',	'weixin-robot',				'weixin_robot_basic_setting_page',	plugins_url('/weixin-robot-advanced/weixin-16.ico'));
	add_submenu_page( 'weixin-robot',	'基本设置 &lsaquo; 微信机器人',	'基本设置',	'manage_options',	'weixin-robot',				'weixin_robot_basic_setting_page');
	add_submenu_page( 'weixin-robot',	'高级回复 &lsaquo; 微信机器人',	'高级回复',	'manage_options',	'weixin-robot-advanced',	'weixin_robot_advanced_setting_page');
	add_submenu_page( 'weixin-robot',	'自定义回复 &lsaquo; 微信机器人',	'自定义回复',	'manage_options',	'weixin-robot-custom-reply','weixin_robot_custom_reply_page');
	
	$weixin_robot_basic = weixin_robot_get_option('weixin-robot-basic');
	if($weixin_robot_basic['weixin_app_id'] && $weixin_robot_basic['weixin_app_secret']){
		add_submenu_page( 'weixin-robot','自定义菜单 &lsaquo; 微信机器人',	'自定义菜单',	'manage_options',	'weixin-robot-custom-menu',	'weixin_robot_custom_menu_page');
	}
	add_submenu_page( 'weixin-robot',	'消息统计分析 &lsaquo; 微信机器人',	'消息统计分析','manage_options',	'weixin-robot-stats',		'weixin_robot_stats_page');
	add_submenu_page( 'weixin-robot',	'回复统计分析 &lsaquo; 微信机器人',	'回复统计分析','manage_options',	'weixin-robot-summary',		'weixin_robot_summary_page');
	add_submenu_page( 'weixin-robot',	'最新消息 &lsaquo; 微信机器人',	'最新消息',	'manage_options',	'weixin-robot-messages',	'weixin_robot_messsages_page');
	add_submenu_page( 'weixin-robot',	'关于和更新 &lsaquo; 微信机器人',	'关于和更新',	'manage_options',	'weixin-robot-about',		'weixin_robot_about_page');
}

include(WP_CONTENT_DIR.'/plugins/weixin-robot-advanced/weixin-robot-options.php');
include(WP_CONTENT_DIR.'/plugins/weixin-robot-advanced/weixin-robot-custom.php');
include(WP_CONTENT_DIR.'/plugins/weixin-robot-advanced/weixin-robot-custom-menu.php');
include(WP_CONTENT_DIR.'/plugins/weixin-robot-advanced/weixin-robot-hook.php');
include(WP_CONTENT_DIR.'/plugins/weixin-robot-advanced/weixin-robot-stats.php');
