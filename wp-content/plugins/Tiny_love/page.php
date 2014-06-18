<?php get_header(); ?>

	<div class="content-wrap">
	<div class="content">

          <?php while (have_posts()) : the_post(); ?>
		   <div class="meta">
				<h1 class="meta-tit">
				       <a href="<?php the_permalink() ?>" title="<?php printf(__('猛击查看 %s 的详细内容', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
				</h1>
				<div class="share">
					<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
					<span class="share-tit">分享到：</span>
						<!-- JiaThis Button BEGIN -->
						<div id="ckepop">
							<a class="jiathis_button_qzone"></a>
							<a class="jiathis_button_tsina"></a>
							<a class="jiathis_button_tqq"></a>
							<a class="jiathis_button_renren"></a>
							<a class="jiathis_button_kaixin001"></a>
							<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
							<a class="jiathis_counter_style"></a>
						</div>
						 <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1340387394593234" charset="utf-8"></script>
					   <!-- JiaThis Button END -->
					</div>
				</div>
				<p class="meta-info">
					<a class="comm" href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
					<?php if(function_exists('the_views')) { the_views(); } ?>人留言</a>
				
				</p>
		    </div>
		
		
		
		<div class="entry">
		 <?php the_content(); ?>
         <br><br>
    	</div>
      
      <?php endwhile; ?>
	
         

		<div class="share">
		<div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
		<span class="share-tit">分享到：</span>
	             	<!-- JiaThis Button BEGIN -->
						<div id="ckepop">
							<a class="jiathis_button_qzone"></a>
							<a class="jiathis_button_tsina"></a>
							<a class="jiathis_button_tqq"></a>
							<a class="jiathis_button_renren"></a>
							<a class="jiathis_button_kaixin001"></a>
							<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
							<a class="jiathis_counter_style"></a>
						</div>
						 <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1340387394593234" charset="utf-8"></script>
					   <!-- JiaThis Button END -->
		
		</div>
		</div>
		<ul class="post-related">
		
		         <?php
					global $post;
					$cats = wp_get_post_categories($post->ID);
					if ($cats) {
					$args = array(
							'category__in' => array( $cats[0] ),
							'post__not_in' => array( $post->ID ),
							'showposts' => 4,
							'caller_get_posts' => 1
						);
					query_posts($args);

					if (have_posts()) :
						while (have_posts()) : the_post(); update_post_caches($posts); ?>
				    <li><a href="<?php the_permalink(); ?>"><img src="<?php  echo catch_that_image(); ?>" alt="<?php the_title_attribute();?>" /><?php the_title(); ?></a></li>
				
				<?php endwhile; else : ?>
					<li>* 暂无相关文章</li>
				<?php endif; wp_reset_query(); } ?>	
        </ul>
		
		
             <?php comments_template(); ?> 
   </div>
			
</div>

<?php get_sidebar();  //右侧栏 ?>   

</div>
<script src="<?php bloginfo('template_directory'); ?>/js/post.js"></script>

<?php get_footer(); ?>