<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php seo_title(' | '); ?></title>
	<?php 
	if (is_home()){
		$keywords = "帝国cms二次开发,ecshop二次开发,dedecms二次开发,phpcms二次开发,帝国cms";
		$description = "分享dedecms,帝国cms,ecshop二次开发经验和技巧,专注于php开源系统的研究学习。同时本站提供基于帝国cms、dedecms、phpcms的企业网站，门户网站等建设服务。";
	} elseif (is_single()){
		if ($post->post_excerpt) {
			$description = $post->post_excerpt;
		} else {
			$description = mb_strimwidth(strip_tags($post->post_content),0,600,'');
			$description = str_replace(array("\r\n", "\r", "\n"," ",'"'), " ", $description);
		}
		$keywords = "";      
		$tags = wp_get_post_tags($post->ID);
		foreach ($tags as $tag ) {
			$keywords = $keywords . $tag->name . ", ";
		}
		$keywords = substr($keywords,0,-2);
	} elseif(is_category()){
		$description = category_description();
	}
	?>
<meta name="keywords" content="<?php echo $keywords;?>" />
<meta name="description" content="<?php echo $description;?>" />
<meta name="google-site-verification" content="7gzvf4JzPpD9-9eoll-LlJAS0qKw8JovRvswCH69Q2Y" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/print.css" type="text/css" media="print" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

<script type="text/javascript">
<?php if(is_single()):?>
document.onkeydown = chang_page;
function chang_page(e) {
    var e = e || event,
    keycode = e.which || e.keyCode;

    if (keycode == 37 || keycode == 33)
        location = "<?php echo get_permalink(get_adjacent_post(false, '42', false)); ?>";
    if (keycode == 39 || keycode == 34)
        location = "<?php echo get_permalink(get_adjacent_post(false, '42', true)); ?>";
}
<?php else: ?>
document.onkeydown = chang_page;
function chang_page(e) {
    var e = e || event,
    keycode = e.which || e.keyCode;
    if (keycode == 37 || keycode == 33)
        location = "<?php echo get_previous_posts_page_link(); ?>";
    if (keycode == 39 || keycode == 34)
        location = "<?php echo get_next_posts_page_link(); ?>";
}
<?php endif; ?>
</script>

</head>
<body>
<div id="header" class="headerlist">
	<div style="height:73px; width:100%;">
		<div style="float:left;">
			<h1><a href="<?php echo get_settings('home'); ?>"><?php bloginfo('name'); ?></a></h1>
			<p><?php bloginfo('description'); ?></p>
		</div>
		<div style="float:right;padding:16px 10px;">
			<script  type="text/javascript" charset="utf-8"  src="http://s.cnzz.net/cs.php?id=900010712"></script>
		</div>
	</div>
	<ul>
		<li><a href="<?php echo get_settings('home'); ?>">Home</a></li>
		<?php wp_list_pages('title_li='); ?>
	</ul>
</div>
<div id="container">