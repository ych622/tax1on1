    <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
        <a class="bds_tsina"></a>
        <a class="bds_tqq"></a>
        <a class="bds_qzone"></a>
        <a class="bds_baidu"></a>
        <a class="bds_qq"></a>
        <a class="bds_tsohu"></a>
        <a class="bds_t163"></a>
        <a class="bds_tfh"></a>
        <a class="bds_renren"></a>
        <a class="bds_douban"></a>
        <a class="bds_xg"></a>
		<a class="bds_copy"></a>
        <span class="bds_more">更多</span>
		<a class="shareCount"></a>
    </div>

	<div class="localtion" style="clear:both;">
		<?php if ( is_attachment() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; 附件下载 &raquo; <?php the_title();?></p>
		<?php }elseif ( is_home() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; 文章列表</p>
		<?php }elseif ( is_category() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; <?php the_category(','); ?> &raquo; 文章列表</p>
		<?php }elseif ( is_single() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; <?php the_category(','); ?> &raquo; <?php the_title(); ?></p>
		<?php }elseif ( is_page() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; <?php the_title(); ?></p>
		<?php }elseif ( is_search() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; <?php echo get_search_query();?> &raquo; Search Results</p>
		<?php }elseif ( is_tag() ) { ?>
		<p class="local">你的位置: <a href="http://xcodebox.com/">首页</a> &raquo; Tag  &raquo; <?php single_tag_title();?></p>
		<?php } ?>
	</div>