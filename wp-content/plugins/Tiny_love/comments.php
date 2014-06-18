<?php


 function custom_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment;
	   $comorder =  get_option('comment_order');
	   if($comorder == 'asc'){
		 /* 论计数器   倒序*/
		 global $commentcount,$wpdb, $post;
		 if(!$commentcount) { //初始化楼层计数器
			  $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND comment_type = '' AND comment_approved = '1'");  //所有评论
			  $cnt = count($comments);//获取评论总数量
			  $page = get_query_var('cpage');//获取当前评论列表页码
			  $cpp=get_option('comments_per_page');//获取每页评论显示数量
			 if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
				 $commentcount = $cnt + 1;//如果评论只有1页或者是最后一页，初始值为主评论总数
			 } else {
				 $commentcount = $cpp * $page + 1;
			 }
		 }
	   }else{
		 //评论计数器初始化  正序
			global $commentcount;
			if(!$commentcount) { //初始化楼层计数器
				$page = get_query_var('cpage')-1;
				$cpp=get_option('comments_per_page');//获取每页评论数
				$commentcount = $cpp * $page;
			}
	   }
}
?>



<div id="postcomments">
	<h3 class="base-tit" id="comments">
		<span><a href="#"></a></span><strong><?php comments_number('0', '1', '%' );?></strong>个访客评论
			</h3>
	   <ol class="commentlist">
		
		<?php foreach (array_reverse($comments) as $comment) : ?>
		<li class="comment even thread-even depth-1" id="comment-<?php comment_ID(); ?>">
		       <div class="c-floor">
			       <a href="#comment-<?php comment_ID(); ?>">#
				   <?php 
					if($comorder == 'asc'){
						//倒叙
						printf('%1$s', --$commentcount);
					}else{
						//正序
						printf('%1$s', ++$commentcount);
					}
					?></a>
			   </div>
			   <div class="c-avatar">
				  <?php
				   echo  get_avatar($comment, 32 )
				  ?>
			   </div>
			   <div class="c-main" id="div-comment-<?php comment_ID(); ?>">
					   <p><?php comment_text(); //取得讨论内容?></p>
					<div class="c-meta">
					<span class="c-author">
						<?php comment_author_link(); //取得讨论名称?>
					</span>
					<?php comment_date(); //取得讨论日期?> 
					<a class='comment-reply-link' href='/internet-of-things/?replytocom=120#respond' onclick='return addComment.moveForm("div-comment-120", "120", "respond", "1380")'>回复</a>
					</div>
			   </div>
			   <!--回复代码
			   <ul class="children">
				      <li id="comment-12149" class="comment even depth-2">
						   <div class="c-avatar">头像</div>
						   <div id="div-comment-12149" class="c-main"><p>内容</p>
							   <div class="c-meta">
							   <span class="c-author">
									<a class="url" rel="external nofollow" target="_blank" href="网址">名字</a>
							   </span>时间<a onclick="return addComment.moveForm(&quot;div-comment-12149&quot;, &quot;12149&quot;, &quot;respond&quot;, &quot;451&quot;)" href="/contact-us/?replytocom=12149#respond" class="comment-reply-link">回复</a>
							   </div>
						   </div>
					   </li>
				</ul>
				-->
	    </li>
		<?php endforeach; ?>

	</ol>
	<div class="pagenav">
	<!--page_comments_start-->
			<?php
				if(get_option('page_comments')) {
					$comment_pages = paginate_comments_links('echo=0');
					if($comment_pages) {
			?>
					<div id="commentnavi">
						<span class="pages-text"><?php _e('Comment Pages :','YLife'); ?></span>
						<!--for_ajax-comment-pager_plugin-->
						<span id="cp_post_id" style="display:none;"><?php echo $post->ID; ?></span>
						<div id="commentpager">
							<?php echo $comment_pages; ?>
						</div>
						<div class="clearfix"></div>
					</div>
			<?php
					}
				}
			?>

    </div>
</div>


<div id="respond" class="no_webshot">
	<h3 class="base-tit"> <a name='footer'></a>
		我来说说	</h3>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				<div id="comment-author-info" >
			<p><label for="author">签名</label><input type="text" name="author" id="author" value="" size="14" tabindex="1" /><em>*</em></p>
			<p><label for="email">邮箱</label><input type="text" name="email" id="email" value="" size="25" tabindex="2" /><em>*</em></p>
			<p class="comment-author-url"><label for="url">网址</label><input type="text" name="url" id="url" value="" size="36" tabindex="3" /></p>
		</div>
				<div class="post-area">
			<div class="comment-editor">
			   <a id="comment-smiley" href="javascript:;">表情</a><a href="javascript:SIMPALED.Editor.code()">插代码</a><a href="javascript:SIMPALED.Editor.strong()">粗体</a><a href="javascript:SIMPALED.Editor.em()">斜体</a><a href="javascript:SIMPALED.Editor.del()">删除线</a><a href="javascript:SIMPALED.Editor.underline()">下划线</a><a href="javascript:SIMPALED.Editor.quote()">引用</a><a href="javascript:SIMPALED.Editor.ahref()">链接</a><a href="javascript:SIMPALED.Editor.img()">插图</a>
			</div>
			<div id="smileys">
			    <a title="mrgreen" href="javascript:grin(':mrgreen:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_mrgreen.gif" /></a><a title="razz" href="javascript:grin(':razz:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_razz.gif" /></a><a title="sad" href="javascript:grin(':sad:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_sad.gif" /></a><a title="smile" href="javascript:grin(':smile:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_smile.gif" /></a><a title="oops" href="javascript:grin(':oops:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_redface.gif" /></a><a title="grin" href="javascript:grin(':grin:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_biggrin.gif" /></a><a title="eek" href="javascript:grin(':eek:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_surprised.gif" /></a><a title="???" href="javascript:grin(':???:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_confused.gif" /></a><a title="cool" href="javascript:grin(':cool:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_cool.gif" /></a><a title="lol" href="javascript:grin(':lol:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_lol.gif" /></a><a title="mad" href="javascript:grin(':mad:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_mad.gif" /></a><a title="twisted" href="javascript:grin(':twisted:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_twisted.gif" /></a><a title="roll" href="javascript:grin(':roll:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_rolleyes.gif" /></a><a title="wink" href="javascript:grin(':wink:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_wink.gif" /></a><a title="idea" href="javascript:grin(':idea:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_idea.gif" /></a><a title="arrow" href="javascript:grin(':arrow:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_arrow.gif" /></a><a title="neutral" href="javascript:grin(':neutral:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_neutral.gif" /></a><a title="cry" href="javascript:grin(':cry:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_cry.gif" /></a><a title="?" href="javascript:grin(':?:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_question.gif" /></a><a title="evil" href="javascript:grin(':evil:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_evil.gif" /></a><a title="shock" href="javascript:grin(':shock:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_eek.gif" /></a><a title="!" href="javascript:grin(':!:')">
				<img src="http://www.daqianduan.com/wp-content/themes/d7/img/smilies/icon_exclaim.gif" /></a>
			</div>
			<div class="post-area-txt" id="post-area-txt-none"><?php echo stripslashes(get_option('d_commentarea')); ?></div>
			<textarea name="comment"  id="comment" cols="100%" rows="7" tabindex="4" onkeydown="if(event.ctrlKey&amp;&amp;event.keyCode==13){document.getElementById('submit').click();return false};"></textarea>
			
			
		</div>
		
		<div class="subcon">
			<input class="btn primary" type="submit" name="submit" id="submit" tabindex="5" value="提交评论（Ctrl+Enter）" />
			<a rel="nofollow" id="cancel-comment-reply-link" href="javascript:;">取消</a>
			<input type='hidden' name='comment_post_ID' value='1380' id='comment_post_ID' />
            <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
            <label for="comment_mail_notify" class="comment_mail"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>有人回复时邮件通知我</label>		
	    </div>
		
			<!--特殊PHP代码-->
			<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>
			<!--特殊PHP代码-->
	    </form>
	</div>
	<?php wp_list_comments('type=pings&per_page=0&callback=zbench_custom_pings'); ?>

