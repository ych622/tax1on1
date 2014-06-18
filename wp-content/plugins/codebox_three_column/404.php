<?php get_header(); ?>

<div id="centrecontent" class="column">
	<?php @include_once 'localtion.php'; ?>
	<h1>404'ed!</h1>
	<p>Sorry dude, doesn't exist. Not here. Nada. Zilch.</p>

	<?php if ( function_exists('related_posts_404') ) { ?>
	<p>
	Maybe the following could help?
	<ul>
	<?php related_posts_404(5, 10, '<li>', '</li>', '', '', false, true); ?>
	</ul></p><?php } ?>	
</div>

<?php get_sidebar(); ?>

<?php include(TEMPLATEPATH . '/rightsidebar.php'); ?>

<?php get_footer(); ?>
