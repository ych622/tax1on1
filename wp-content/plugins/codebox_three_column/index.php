<?php get_header(); ?>

<div id="centrecontent" class="column">

	<?php if (have_posts()) : ?>

		<?php $post = $posts[0]; ?>
		<?php @include_once 'localtion.php'; ?>

		<?php while (have_posts()) : the_post(); ?>
			
			<div class="post" id="post-<?php the_ID(); ?>">
				<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			</div>
				
		<?php endwhile; ?>

		<p>
		<?php if(function_exists('wp_pagenavi')):?>
			<?php wp_pagenavi(); ?>
		<?php else: ?>
			<?php posts_nav_link('', __(''), __('&laquo; Previous entries')); ?>
			<?php posts_nav_link(' &#183; ', __(''), __('')); ?>
		<?php endif;?>
		</p>
		
	<?php else : ?>

		<h2><?php _e('Not Found'); ?></h2>

		<p><?php _e('Sorry, but no posts matched your criteria.'); ?></p>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php include(TEMPLATEPATH . '/rightsidebar.php'); ?>

<?php get_footer(); ?>

