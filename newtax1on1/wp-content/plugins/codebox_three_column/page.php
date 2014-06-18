<?php get_header(); ?>

<div id="centrecontent" class="column">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
	<?php @include_once 'localtion.php'; ?>
		
		<div class="post" id="post-<?php the_ID(); ?>">		
	
			<h2 class="posttitle sinleposttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			
			<p class="postmeta">

			<?php if (!is_page()) { ?>
			<span class="postauthor"><?php _e('By '); ?><?php the_author(); ?></span><?php _e(' ('); ?>
				<?php the_time('F j, Y') ?> <?php _e('at'); ?> <?php the_time() ?><?php _e(')'); ?> 

			<?php if ( is_callable(array('GeoMashup','show_on_map_link')) ) {
				$linkString = GeoMashup::show_on_map_link('text=Map%20&show_icon=false');
				if ($linkString != "")
				{
					echo ' &#183; ';
					echo $linkString;
				}
			} ?>
			&#183; <?php _e('Filed under'); ?> <?php the_category(', ') ?><?php if ( function_exists('get_the_tags') ) {if (get_the_tags()) the_tags(', ',', ',''); }?>
			<?php } ?>

			<?php edit_post_link(__('Edit'), ' &#183; ', ''); ?>
			</p>	
		
			<div class="postentry">
			<?php the_content(__('Read the rest of this entry &raquo;')); ?>
			<?php wp_link_pages(); ?>
			<?php wp_related_posts(); ?>
			</div>

			<p class="postfeedback">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>" class="permalink"><?php _e('Permalink'); ?></a>
			</p>
			
		</div>
		
		<?php comments_template(); ?>
				
	<?php endwhile; else : ?>

		<h2><?php _e('Not Found'); ?></h2>

		<p><?php _e('Sorry, but the page you requested cannot be found.'); ?></p>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php include(TEMPLATEPATH . '/rightsidebar.php'); ?>

<?php get_footer(); ?>


