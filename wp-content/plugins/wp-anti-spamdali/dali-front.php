<?php

/**
 * 检查评论是否有中文
 * 如果不包含任何中文，则判为垃圾评论
 *
 * @param array $incoming_comment 评论内容数组
 * @return null
 */
function dali_chinese_only($incoming_comment) {
	global $daliOptions;

	if($daliOptions['chinese_only'] == 0)
		return;

	$pattern = '/[一-龥]/u';
	if(!preg_match($pattern, $incoming_comment['comment_content'])) {
		if($daliOptions['delete_or_move'] == 'delete')
			wp_die('You should type some Chinese word (like \"你好\") in your comment to pass the spam-check, thanks for your patience! 您的评论中必须包含汉字!');
		else
			// 更改评论转态，待审 || 垃圾
			add_filter('pre_comment_approved', create_function('','return '.$daliOptions['delete_or_move'].';'));
	}
}

/**
 * 往评论表单中插入一个隐藏域，该隐藏域的值为根据微秒生成的一个难以
 * 伪造的时间令牌，该值将存放在SESSION中。
 * 每刷新一次页面都会生成一个新的时间令牌。
 *
 * @param int $id post ID
 * @return null
 */
function dali_insert_hidden_field($id) {
	global $daliOptions;

	if($daliOptions['anti_robots'] == 0)
		return;

	// 生成时间令牌，伪造的可能性极小
	$token = md5(uniqid(rand(), true));
	$_SESSION['robots_check_session'] = $token;
	echo '<input type="hidden" name="robots_check" value="'.$token.'" />';
}

/**
 * 检验评论表单中隐藏域的值，如果传递过来的值与SESSION中的值不同或未传递该隐藏域，
 * 则判定为机器人发送的评论
 *
 * @return null
 */
function dali_check_hidden_Field() {
	global $daliOptions;

	if($daliOptions['anti_robots'] == 0)
		return;

	if (!isset($_POST['robots_check']) || $_SESSION['robots_check_session'] != $_POST['robots_check']) {
		if($daliOptions['delete_or_move'] == 'delete')
			wp_die('本站禁止自动发布的评论');
		else
			add_filter('pre_comment_approved', create_function('','return '.$daliOptions['delete_or_move'].';'));
	}
}

/**
 * 检验评论内容长度是否在设定范围内容
 *
 * @param array $incoming_comment 评论内容数组
 * @return null
 */
function dali_length_check($incoming_comment) {
	global $daliOptions;

	if($daliOptions['min_words'] != 0
		&& (mb_strlen($incoming_comment['comment_content'], 'utf-8') < $daliOptions['min_words']))
	{
		if($daliOptions['delete_or_move'] == 'delete')
			wp_die('您输入的评论内容不得少于 '.$daliOptions['min_words'].' 字');
		else
			add_filter('pre_comment_approved', create_function('','return '.$daliOptions['delete_or_move'].';'));
	}
	if($daliOptions['max_words'] != 0
		&& (mb_strlen($incoming_comment['comment_content'], 'utf-8') > $daliOptions['max_words']))
	{
		if($daliOptions['delete_or_move'] == 'delete')
			wp_die('您输入的评论内容不得多于 '.$daliOptions['max_words'].' 字');
		else
			add_filter('pre_comment_approved', create_function('','return '.$daliOptions['delete_or_move'].';'));
	}
}

/**
 * 转义评论中的代码，避免代码直接执行
 *
 * @param array $incoming_comment 评论内容数组
 * @return array $incoming_comment
 */
function dali_code_escape($incoming_comment) {
 	global $daliOptions;

 	if($daliOptions['code_escape'] == 0)
		return $incoming_comment;

	$incoming_comment = htmlspecialchars($incoming_comment, ENT_QUOTES);
	return $incoming_comment;
}

/**
 * 检测是否为冒充博主评论
 *
 * @param array $incoming_comment 评论内容数组
 * @return array $incoming_comment
 */
function dali_use_check($incoming_comment) {
	global $daliOptions,$user_ID;

	//已登录
	if ( intval($user_ID) > 0 )
		return;

	$isSpam = 0;

	if ($daliOptions['admin_name'] != '' && $daliOptions['admin_name'] == strtolower(trim($incoming_comment['comment_author'])))
		$isSpam = 1;
	if ($daliOptions['admin_email'] != '' && $daliOptions['admin_email'] == strtolower(trim($incoming_comment['comment_author_email'])))
		$isSpam = 1;

	if(!$isSpam)
		return $incoming_comment;

	if($daliOptions['delete_or_move'] == 'delete')
		wp_die('请勿冒充博主发表评论');
	else
		add_filter('pre_comment_approved', create_function('','return '.$daliOptions['delete_or_move'].';'));

	return $incoming_comment;
}

/**
 * 禁止将评论的链接弄成可点击
 */
function make_unclickable() {
	global $daliOptions;

	if($daliOptions['delete_links'] == 0)
		return;

	remove_filter('comment_text', 'make_clickable', 9);
}

/**
 * 替换评论中的关键字
 *
 * @param array $incoming_comment 评论内容数组
 * @return array $incoming_comment
 */
function dali_conents_replace($incoming_comment) {
	global $daliOptions;

	if($daliOptions['words_replace'] == '')
		return $incoming_comment;

	$rules = explode('||', $daliOptions['words_replace']);

	foreach($rules as $rule) {
		$word = explode('->', trim($rule));

		if(isset($word[1]))
			$incoming_comment = str_replace(trim($word[0]), trim($word[1]), $incoming_comment);
	}

	return $incoming_comment;
}

/**
 * 评论插入数据库之前的操作
 *
 * @param array $incoming_comment 评论内容数组
 * @return array $incoming_comment
 */
function preprocess($incoming_comment) {
	//忽略 Trackbacks/Pingbacks
	if ( in_array( $incoming_comment['comment_type'], array('pingback', 'trackback') ) )
		return $incoming_comment;

	dali_check_hidden_Field();
	dali_chinese_only($incoming_comment);
	dali_length_check($incoming_comment);
	dali_use_check($incoming_comment);

	return $incoming_comment;
}

/**
 * 前台显示评论时的操作
 *
 * @param array $incoming_comment 评论内容数组
 * @return array $incoming_comment
 */
function display($incoming_comment) {
	make_unclickable();
	$incoming_comment = dali_conents_replace($incoming_comment);
	$incoming_comment = dali_code_escape($incoming_comment);

	return $incoming_comment;
}

?>