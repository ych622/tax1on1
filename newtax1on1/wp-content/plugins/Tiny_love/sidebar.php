<div class="sidebar">	

            <div class="widget widget_d_banner widget_d_banner_half">
                      <a href="http://www.php0.net/?page_id=1040"><img src="<?php bloginfo('template_directory'); ?>/img/ad.jpg" alt="点击获取您的广告位"></a>
            </div>
           
            <div class="widget widget_d_theme"><h3 class="widget_tit">博主资料</h3>
                <ul>		      
                    <li>
                        <span class="pic"><img src="http://tp2.sinaimg.cn/1958362225/180/5632798772/1" width="70" height="60" ></span>
                        <p><strong>简介：</strong>您好，我是北海情书,欢迎来到我的独立博客世界...</p>
                        <a class="btn btn-mini" href="http://weibo.com/jiankers">关注我微博去吧 »</a><a class="btn btn-mini btn-success" href="http://www.php0.net/?page_id=2">留言去吧</a>
                    </li>
               </ul>
			  
             </div>
			 
			 <div class="widget widget_d_theme"><h3 class="widget_tit">日历</h3>
              
			   <?php get_calendar(); ?>
             </div>
             
             <!-- 
             <div class="widget widget_d_theme"><!--<h3 class="widget_tit">收听博主</h3>
            <iframe src="http://follow.v.t.qq.com/index.php?c=follow&a=quick&name=hackshell&style=1&t=1343729958441&f=1" marginwidth="0" marginheight="0" allowtransparency="true" frameborder="0" scrolling="auto" width="227" height="75"></iframe>
             </div>
             -->

           
				<!-------最新评论文章--->
			     <div class="widget widget_d_comment"><h3 class="widget_tit">最新评论文章</h3>
					  <ul>
					  
					  <?php while (have_posts()) : the_post(); ?>  
						
	                  <?php endwhile;?>			

                           
				   <?php   
						global $wpdb;   
						$my_email = get_bloginfo ('admin_email');   
						$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, SUBSTRING(comment_content,1,14) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' AND comment_author_email != '$my_email' ORDER BY comment_date_gmt DESC LIMIT 8";   
						$comments = $wpdb->get_results($sql);   
						$output = $pre_HTML;   
						foreach ($comments as $comment) {
							  
							   //$output .= '<li><a href="'. get_permalink($comment->ID) ."#comment-" . $comment->comment_ID .'" title="'.$comment->post_title .'"><em>&gt;</em>'.get_avatar( $comment, 32 ).'<strong>'.strip_tags($comment->comment_author).'：</strong>'.strip_tags($comment->com_excerpt).'</a></li>';
							   $output .= '<li><a style="padding-left:0;" href="'.get_permalink($comment->ID).'" title="'.$comment->post_title.'"><em>&gt;</em><strong>'.$comment->post_title.'</strong></a></li>';
					    }   
						$output .= $post_HTML;   
						echo $output;   
                      ?>  


					  
					 </ul>
                 </div> 
				<!-------最新评论文章 end-->

				
				
				
				<div class="widget widget_d_comment"><h3 class="widget_tit">最新评论</h3>

                  <ul>
             
                  <?php   
						global $wpdb;   
						$my_email = get_bloginfo ('admin_email');   
						$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, SUBSTRING(comment_content,1,14) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' AND comment_author_email != '$my_email' ORDER BY comment_date_gmt DESC LIMIT 8";   
						$comments = $wpdb->get_results($sql);   
						$output = $pre_HTML;   
						foreach ($comments as $comment) {
							   //$output .= "\n<li>".get_avatar( $comment, 32 )." <a href=\"" . get_permalink($comment->ID) ."#comment-" . $comment->comment_ID . "\" title=\"发表在： " .$comment->post_title . "\">" .strip_tags($comment->comment_author).":<br/>". strip_tags($comment->com_excerpt)."</a><br /></li>";
							   $output .= '<li><a href="'. get_permalink($comment->ID) ."#comment-" . $comment->comment_ID .'" title="'.$comment->post_title .'"><em>&gt;</em>'.get_avatar( $comment, 32 ).'<strong>'.strip_tags($comment->comment_author).'：</strong>'.strip_tags($comment->com_excerpt).'</a></li>';
					    }   
						$output .= $post_HTML;   
						echo $output;   
                ?>   
                  
                  
                     
                  
                  
                
                  </ul>
                
                 </div>  
                 
                          
                 <!--link -->
                <div class="widget widget_links">
                <h3 class="widget_tit">友情链接</h3>
                    <ul class='xoxo blogroll'>
                            
                            <?php get_links('-1', '<li>', '</li>', '<br />', FALSE, 'id', FALSE, FALSE, -1, FALSE); ?>
    
                    </ul>
                </div>
                 <!--link  end-->
</div>



