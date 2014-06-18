<?php
function bfa_comments($comment, $args, $depth) {

global $bfa_ata;

   $GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class($class='clearfix') ?> id="comment-<?php comment_ID(); ?>">
		<div id="div-comment-<?php comment_ID(); ?>" class="clearfix comment-container<?php 
		$comment = get_comment($comment_id);
		if ( $post = get_post($post_id) ) {
			if ( $comment->user_id === $post->post_author )
				echo ' bypostauthor';
		} ?>">
		<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<span class="authorname"><?php comment_author_link(); ?></span>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em><?php echo $bfa_ata['comment_moderation_text']; ?></em><br />
		<?php endif; ?>
		<div class="comment-meta commentmetadata">
		<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php printf(__('%1$s at %2$s','atahualpa'), get_comment_date(),  get_comment_time()) ?></a>
        <?php echo comment_reply_link(array('before' => '<span class="comment-reply-link">', 'after' => '</span>', 'reply_text' => $bfa_ata['comment_reply_link_text'], 'depth' => $depth, 'max_depth' => $args['max_depth'] ));  ?>
		<?php edit_comment_link($bfa_ata['comment_edit_link_text'],'<span class="comment-edit-link">','</span>') ?> 
		</div>
		<?php comment_text() ?>
		</div>
<?php } 


/* 邮件通知 by Qiqiboy */
function comment_mail_notify($comment_id) {
    $comment = get_comment($comment_id);//根据id获取这条评论相关数据
    $content=$comment->comment_content;
    //对评论内容进行匹配
    $match_count=preg_match_all('/<a href="#comment-([0-9]+)?" rel="nofollow">/si',$content,$matchs);
    if($match_count>0){//如果匹配到了
        foreach($matchs[1] as $parent_id){//对每个子匹配都进行邮件发送操作
            SimPaled_send_email($parent_id,$comment);
        }
    }elseif($comment->comment_parent!='0'){//以防万一，有人故意删了@回复，还可以通过查找父级评论id来确定邮件发送对象
        $parent_id=$comment->comment_parent;
        SimPaled_send_email($parent_id,$comment);
    }else return;
}
add_action('comment_post', 'comment_mail_notify');
function SimPaled_send_email($parent_id,$comment){//发送邮件的函数 by Qiqiboy.com
    $admin_email = get_bloginfo ('admin_email');//管理员邮箱
    $parent_comment=get_comment($parent_id);//获取被回复人（或叫父级评论）相关信息
    $author_email=$comment->comment_author_email;//评论人邮箱
    $to = trim($parent_comment->comment_author_email);//被回复人邮箱
    $spam_confirmed = $comment->comment_approved;
    if ($spam_confirmed != 'spam' && $to != $admin_email && $to != $author_email) {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 發出點, no-reply 可改為可用的 e-mail.
        $subject = '您在 [' . get_option("blogname") . '] 的留言有了回應';
        $message = '<div style="background-color:#eef2fa;border:1px solid #d8e3e8;color:#111;padding:0 15px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;">
        <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
        <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />'
        . trim(get_comment($parent_id)->comment_content) . '</p>
        <p>' . trim($comment->comment_author) . ' 给你的回复:<br />'
        . trim($comment->comment_content) . '<br /></p>
        <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id,array("type" => "all"))) . '">查看回复的完整內容</a></p>
        <p>欢迎再度光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
        <p>(此邮件有系统自动发出, 请勿回复.)</p></div>';
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail( $to, $subject, $message, $headers );
    }
}

?>