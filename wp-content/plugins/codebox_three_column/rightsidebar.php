<div id="rightsidebar" class="column">
  <ul>
	<li id="search-3" class="widget widget_search" style="display:block;margin-top:25px;">
		<form role="search" method="get" id="searchform" action="http://xcodebox.com/" >
				<div>
				<input type="text" value="" x-webkit-speech name="s" id="s" />
				</div>
		</form>
	</li>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>
    <li>
      My right sidebar goes here.
    </li>
<?php endif; ?>
  </ul>
</div>