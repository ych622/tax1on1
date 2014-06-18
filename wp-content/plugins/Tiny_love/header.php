<!DOCTYPE HTML>
<html <?php language_attributes(); ?> >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
<title><?php global $page, $paged; wp_title( '|', true, 'right' ); bloginfo( 'name' ); $site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?>
</title>
		<?php if (is_home()){ 
			$description     = '您将进入的是一个技术宅的个人博客,Welcome!';
			$keywords = '技术博客,PHP博客,软件编程,PHP教程,PHP代码,PHP插件,JS教程,javascruipt实例,linux教程,C语言编程,黑客技术,jquery实例';
		} elseif (is_single() || is_page()){    
			$description1 =  $post->post_excerpt ;
			$description2 = mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 200, "…");
			$description = $description1 ? $description1 : $description2;
		
			$keywords = "";        
			$tags = wp_get_post_tags($post->ID);
			foreach ($tags as $tag ) {
				$keywords = $keywords . $tag->name . ", ";
			}
		} elseif(is_category()){
			$description     = category_description();
		}
		?>
<meta name="keywords" content="<?php echo $keywords ?>" />
<meta name="description" content="<?php echo $description ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
</head>
<body<?php if(is_home()):?> class="home blog"<?php endif;?>  class="single single-post postid-4165 single-format-standard" >
<div class="header">
	<div class="header-inner">
		<h1 class="logo"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
		
      
        
		<ul class="nav">
			<li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-3685" id="menu-item-3685"><a href="http://www.php0.net">首页</a></li>
            <li class="page_item page-item-440"><a href="http://www.php0.net/?page_id=440">关于博主</a></li>
            <li class="page_item page-item-2"><a href="http://www.php0.net/?page_id=2">留言板</a></li>
		</ul>
		
		
		<form method="get" class="search-form" action="<?php bloginfo('url');?>" >
			<input class="search-input" name="s" type="text" placeholder="输入 回车搜索" autofocus="" x-webkit-speech="">
		</form>
		<ul class="quick-menu">
		
             <?php wp_list_pages('title_li='); ?>

		</ul>
	</div>
</div>

<div class="wrapper">
	
    <ul class="follow">
		<li><a target="_blank" href="http://t.qq.com/hackshell"><span class="ico ico-tqq"></span><span class="tit">关注腾讯微博</span><span class="note">t.qq.com/hackshell</span></a></li>
		<li><a target="_blank" href="http://weibo.com/jiankers"><span class="ico ico-weibo"></span><span class="tit">关注新浪微博</span><span class="note">weibo.com/jiankers</span></a></li>
		<li><a target="_blank" href="http://www.php0.net/feed/"><span class="ico ico-feed"></span><span class="tit">订阅北海情书</span><span class="note">www.php0.net/feed/</span></a></li>
		<li><form class="mailfeed" action="http://list.qq.com/cgi-bin/qf_compose_send" target="_blank" method="post">
			<span class="ico ico-mailfeed"></span>
			<input type="hidden" name="t" value="qf_booked_feedback">
			<input type="hidden" name="id" value="f799bf4d11933840e24314cabe1526cd3ccb9705a8ec7fb8">
			<input id="to" onfocus="if (this.value == '输入邮箱 邮件订阅') {this.value = '';}" onblur="if (this.value == '') {this.value = '输入邮箱 邮件订阅';}" value="输入邮箱 邮件订阅" name="to" type="text" class="ipt">
		</form></li>
	</ul>
    
    <!--Header end -->
	
    
