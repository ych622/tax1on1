<?php get_header(); ?>
    
    <div class="content-wrap">
	<div class="content">
		<div class="quicker">
        
			<!--<h3><span><a href="http://www.daqianduan.com/tags/">标签云</a></span>热门专题</h3>
            <ul>
                <li><a href="http://www.daqianduan.com/tag/css3-explain/">CSS3详解</a></li>
                <li><a href="http://www.daqianduan.com/tag/jobs/">名企招聘</a></li>
            </ul>
            -->
            <h3>分类目录</h3>
            <ul>
              
                
                <li><?php wp_list_categories('title_li='); ?></li>  
                
      
 
            </ul>

		</div>
		<!--
		<div class="tips">
			 公告:
        </div>
	    -->

		
		<ul class="excerpt">
		
		  <?php while (have_posts()) : the_post(); ?> 
            <!-- content -->    	
			<li>
				<a href="<?php the_permalink() ?>" class="pic"><img src="<?php  echo catch_that_image(); ?>" alt="<?php the_title(); ?>" /></a>
				<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="info">
					<span class="time"><?php the_time('m') ?>-<?php the_time('j') ?></span>
					<a class="comm" href="<?php the_permalink() ?>#comments" title="<?php the_title(); ?>"><?php comments_number('0', '1', '%'); ?>人评论</a>
					<span class="view"><?php if(function_exists('the_views')) {the_views();} ?>次浏览</span>
				</div>
				<div class="note"><?php echo mb_strimwidth(strip_tags(apply_filters('the_excerpt', $post->post_content)), 0, 220,"...");?></div>
              
			</li>
            <!-- content end-->    
          <?php endwhile; ?>
            
		</ul>
        
        
        
	   <div class="paging">
			         <!--page-->
                    <?php kriesi_pagination($query_string); ?>  
                     <!--page end--> 
       </div>
</div>
</div>
    <?php get_sidebar();  //右侧栏 ?>   
</div>
<style>
	.blue{
		background:blue;
	}
	
	.btntwo{border:1px solid #02598E;width:15px;background-color:#1E7BB3;color:#ffffff;border-radius: 8px 8px 8px 8px;}

</style>
<script>
$(function(){

   $("#wp-calendar td").mouseover(function(){
		var num = Number($(this).html());
		$(this).addClass('btntwo');
		$("#wp-calendar td").each(function(){
			if(Number($(this).html()) !== num){
				$(this).removeClass('btntwo');
			}
		});
   
   });
   
});

</script>


<?php get_footer(); ?>