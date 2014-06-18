<?php

/**
 * 更新插件选项
 *
 */
function updateDaliOptions() {
	if(isset($_POST['wp_antispamdali_options'])) {
		// 更新插件选项
		$options = array();
		$options['anti_robots'] 	= 	$_POST['anti_robots'];
		$options['chinese_only'] 	= 	$_POST['chinese_only'];
		$options['code_escape'] 	= 	$_POST['code_escape'];
		$options['delete_links'] 	= 	$_POST['delete_links'];
		$options['delete_or_move'] 	= 	$_POST['delete_or_move'];
		$options['words_replace'] = 	trim($_POST['words_replace']);
		$options['admin_name'] 		= 	trim($_POST['admin_name']);
		$options['admin_email'] 	= 	trim($_POST['admin_email']);

		if(is_numeric(trim($_POST['min_words'])) && $_POST['min_words'] != 0)
			$options['min_words'] 	= 	trim($_POST['min_words']);
		else
			$options['min_words'] 	= 	0;

		if(is_numeric(trim($_POST['max_words'])) && $_POST['max_words'] != 0)
			$options['max_words'] 	= 	trim($_POST['max_words']);
		else
			$options['max_words'] 	= 	0;

		update_option('wp_antispamdali_options', $options);
		echo "<div class=\"updated fade\" id=\"message\"><p><strong>选项成功更新</strong></p></div>";
	}

	add_options_page('大篱评论过滤', '大篱评论过滤', 'manage_options', 'anti-spam-dali', 'addOptionsPage');
}

/**
 * 显示插件选项
 *
 */
function addOptionsPage() {
	$options = dali_get_options();
?>
<style type="text/css">
	div.clearing{border-top:1px solid #2580B2 !important;clear:both;}
	small{font-size: 11px;}
</style>

<div class="wrap">
<h2>大篱 - 评论过滤</h2>
<form action="#" method="post" name="wp_dali_form">
	<fieldset class="options" name="wp_basic_options">
		<p>
			<strong>对垃圾评论的处理操作</strong>
			<br><br>
			<label><input type="radio" value="delete" name="delete_or_move"<?php if($options['delete_or_move'] == 'delete') echo ' checked="checked"'; ?>>直接删除</label>
			<br>
			<label><input type="radio" value="0" name="delete_or_move"<?php if($options['delete_or_move'] == '0') echo ' checked="checked"'; ?>>移至待审评论</label>
			<br>
			<label><input type="radio" value="spam" name="delete_or_move"<?php if($options['delete_or_move'] == 'spam') echo ' checked="checked"'; ?>>移至垃圾评论</label>
			<br>
			<small>被本程序判为垃圾评论的评论将采取您选择的操作</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>阻止机器人的评论</strong>
			<br><br>
			<label><input type="radio" value="1" name="anti_robots"<?php if($options['anti_robots']) echo ' checked="checked"'; ?>>开启</label>
			<br>
			<label><input type="radio" value="0" name="anti_robots"<?php if(!$options['anti_robots']) echo ' checked="checked"'; ?>>关闭</label>
			<br>
			<small>开启此功能后，所有自动发布的评论都将被判为垃圾评论。如果开启此功能出现程序错误或者您开启了静态化插件，请关闭此功能。</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>只允许中文评论</strong>
			<br><br>
			<label><input type="radio" value="1" name="chinese_only"<?php if($options['chinese_only']) echo ' checked="checked"'; ?>>开启</label>
			<br>
			<label><input type="radio" value="0" name="chinese_only"<?php if(!$options['chinese_only']) echo ' checked="checked"'; ?>>关闭</label>
			<br>
			<small>开启此功能后，所有不包含中文的评论都会判为垃圾评论</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>评论内容字数限制</strong>
			<br><br>
			<label>至少: <input type="text" size="15" value="<?php echo $options['min_words'] ? $options['min_words'] : 0; ?>" name="min_words"> 字</label>
			<br>
			<label>至多: <input type="text" size="15" value="<?php echo $options['max_words'] ? $options['max_words'] : 0; ?>" name="max_words"> 字</label>
			<br>
			<small>不限制请填 0，此功能可阻止诸如纯&quot;顶&quot;、&quot;支持&quot;之类纯灌水评论，以及超大篇幅的评论内容。</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>仿评论冒充设置</strong>
			<br><br>
			<label>博主昵称: <input type="text" size="15" value="<?php echo $options['admin_name']; ?>" name="admin_name"></label>
			<br>
			<label>博主Email: <input type="text" size="15" value="<?php echo $options['admin_email']; ?>" name="admin_email"></label>
			<br>
			<small>不开启请留空。开启此功能可防止其他人使用博主信息进行评论，对博主名誉等造成损害。开启此功能，博主必须登录才能发表评论。</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>删除评论中的链接</strong>
			<br><br>
			<label><input type="radio" value="1" name="delete_links"<?php if($options['delete_links']) echo ' checked="checked"'; ?>>开启</label>
			<br>
			<label><input type="radio" value="0" name="delete_links"<?php if(!$options['delete_links']) echo ' checked="checked"'; ?>>关闭</label>
			<br>
			<small>开启此功能后，评论中的所有链接都不可点击，只保留文本形式的链接地址</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>转义评论中的所有代码</strong>
			<br><br>
			<label><input type="radio" value="1" name="code_escape"<?php if($options['code_escape']) echo ' checked="checked"'; ?>>开启</label>
			<br>
			<label><input type="radio" value="0" name="code_escape"<?php if(!$options['code_escape']) echo ' checked="checked"'; ?>>关闭</label>
			<br>
			<small>开启此功能后，评论中的所有代码都会被转义，不会执行，防止恶意代码和广告链接等。实际上转义只是采取以下字符替换操作:<br />&lt;替换成&amp;lt;，&gt;替换成&amp;gt;，&quot;替换成&amp;quot;，&#039;替换成&amp;#039;，&amp;替换成&amp;amp;</small>
		</p>
		<div class="clearing"></div>
		<p>
			<strong>评论关键字替换</strong>
			<br><br>
			<textarea rows="10" cols="100%" name="words_replace"><?php echo $options['words_replace']; ?></textarea>
			<br>
			<small>不开启请留空。前台显示评论时，替换评论中的关键字，防止评论中出现非法、恶意内容。请使用以下格式填写：<br />
				<strong>关键字A->替换A || 关键字B->替换B || 关键字C->替换C</strong><br />
				关键字A在实际显示时将被替换成替换A，依此类推，多个替换规则之间请用 || 隔开
			</small>
		</p>
		<div class="clearing"></div>
		<p class="submit">
			<input type="hidden" value="1" name="wp_antispamdali_options">
			<input type="submit" value="更新选项 &gt;&gt;" name="updateoptions">
		</p>
	</fieldset>
</form>
</div>
<?php } ?>