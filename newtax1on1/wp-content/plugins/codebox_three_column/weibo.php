<?php
/*
Template Name: 微博模板
*/
?>
<?php get_header(); ?>

<div id="centrecontent" class="column">
	<?php @include_once 'localtion.php'; ?>
		<div class="post">			
			<div class="postentry">
			<iframe width="100%" height="1130" class="share_self"  frameborder="0" scrolling="no" src="http://widget.weibo.com/weiboshow/index.php?language=&width=0&height=1130&fansRow=2&ptype=1&speed=0&skin=3&isTitle=1&noborder=1&isWeibo=1&isFans=1&uid=2805850270&verifier=9dc69088&dpc=1"></iframe>
			</div>
		</div>
</div>

<?php get_sidebar(); ?>

<?php include(TEMPLATEPATH . '/rightsidebar.php'); ?>

<?php get_footer(); ?>


