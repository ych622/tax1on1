<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if ( post_password_required() ) 
        {
		?>				
		<p><?php _e("请输入密码后查看评论!"); ?><p>				
		<?php
		return;
        }
?>

<!-- You can start editing here. -->

<?php 
	global $post;

	$thePostID = $post->ID;
	$commentsAtAll = comment_count_special($thePostID, 'comment');

  	$pingsAtAll = comment_count_special($thePostID, 'pings');

	if ($commentsAtAll) : ?>

	<h2 id="comments">
		<?php if ($commentsAtAll == 1)
		{
			echo "1 Comment";
		}
		else
		{
			echo "$commentsAtAll comments";
		}
		?>
		<?php if ( comments_open() ) : ?>
			<a href="#postcomment" title="<?php _e('跳转到评论框'); ?>">&raquo;</a>
		<?php endif; ?>
	</h2>

	<ol id="commentlist">

	<?php 
	$options = 'avatar_size=40&type=comment&callback=ns_comments';
	wp_list_comments($options);
	?>

	</ol>
	<div class="navigation-bottom">
	<p class="small alignleft">
	<?php comments_rss_link(__('<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post')); ?>
	<?php if ( pings_open() ) : ?>
	&#183; <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a>
	<?php endif; ?>
	</p>
	<p class="small alignright">
	<?php paginate_comments_links(); ?>
	</p>
	</div>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post-> comment_status) : ?> 
		<?php /* No comments yet */ ?>
		
	<?php else : // comments are closed ?>
		<?php /* Comments are closed */ ?>
		<p>评论已关闭.</p>
		
	<?php endif; ?>
	
<?php endif; ?>

<?php if ('open' == $post-> comment_status) : ?>
	<div id="respond">
	<h2 id="postcomment"><?php comment_form_title('评论', '回复 %s'); ?></h2>	
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	
		<p><?php _e('You must be'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('logged in'); ?></a> <?php _e('to post a comment.'); ?></p>
	
	<?php else : ?>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

		<p><?php comment_id_fields(); ?></p>
		
		<?php if ( $user_ID ) : ?>
		
			<p><?php _e('Logged in as'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e('Log out of this account') ?>"><?php _e('Logout'); ?> &raquo;</a></p>

		<?php else : ?>
	
			<p>
			<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="30" tabindex="1" />
			<label for="author">姓名 (必填)</label>
			</p>
			
			<p>
			<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="30" tabindex="2" />
			<label for="email">邮件 (必填)</label>
			</p>
			
			<p>
			<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="30" tabindex="3" />
			<label for="url">你的网站</label>
			</p>

		<?php endif; ?>

		<p>
		<textarea name="comment" id="comment" cols="70" rows="10" tabindex="4"></textarea>
		</p>
	
		<p>
		<input name="submit" type="submit" id="submit" tabindex="5" value="提交评论" />		
		<span id="cancel-comment-reply">&nbsp;<?php cancel_comment_reply_link('取消') ?></span>
		</p>
	
		<?php do_action('comment_form', $post->ID); ?>
	
		</form>

	<?php endif; // If registration required and not logged in ?>
	</div>

<?php endif; // if you delete this the sky will fall on your head ?>

<?php  

if ($pingsAtAll > 0)
{
?>
	<h2 id="trackbackpings">
		<?php 
		echo $pingsAtAll;
		if ($pingsAtAll == 1) 
		{ 
			echo ' Trackback &#92; Ping';
		}
		else
		{ 
			echo ' Trackbacks &#92; Pings';
		} 

		if ( comments_open() ) : ?>
			<a href="#postcomment" title="<?php _e('跳转到评论框'); ?>">&raquo;</a>
		<?php endif; ?>
	</h2>
	<ol id="commentlist">

	<?php 

	$options = 'type=pings&callback=ns_trackbacks';
	wp_list_comments($options);
	?>

	</ol>
<?php 
}
?> 
