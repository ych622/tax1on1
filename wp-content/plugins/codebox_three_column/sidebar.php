<div id="leftsidebar" class="column">
  <ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>
    <li>
      My sidebar goes here.
    </li>
<?php endif; ?>
  </ul>
</div>