<?php
/*
Plugin Name: Photopress Random Thumbs Widget
Description: Adds a sidebar widget to let users show random photopress thumbnails
Author: Richard Maxwell
Version: 1.0
Author URI:

*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_photopress_randomthumbs_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_photopress_randomthumbs($args) 
	{
		extract($args);
		$options = get_option('widget_photopress_randomthumbs');
		$title = $options['title'];
		$number_of_images = $options['numimages'];
		if ( empty($title) )
			$title = '&nbsp;';
		echo $before_widget . $before_title . $title . $after_title;
		echo '<div id="photopress_randomthumbs">';

		if ( function_exists('pp_random_image_bare') )
		{
			pp_random_image_bare($number_of_images);
		}	

		echo '</div>';
		echo $after_widget;
	}

	function widget_photopress_randomthumbs_control() 
	{
		$options = $newoptions = get_option('widget_photopress_randomthumbs');
		if ( $_POST["photopressrandomthumbs-submit"] ) 
		{
			$newoptions['title'] = strip_tags(stripslashes($_POST["photopressrandomthumbs-title"]));
			$newoptions['numimages'] = (int) $_POST["photopressrandomthumbs-items"];
		}

		if ( $options != $newoptions ) 
		{
			$options = $newoptions;
			update_option('widget_photopress_randomthumbs', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$items = htmlspecialchars($options['numimages'], ENT_QUOTES);
	?>
		<p><label for="photopressrandomthumbs-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="photopressrandomthumbs-title" name="photopressrandomthumbs-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p style="text-align:center; line-height: 30px;"><?php _e('How many tumbs would you like to display?'); ?> <select id="photopressrandomthumbs-items" name="photopressrandomthumbs-items"><?php for ( $i = 1; $i <= 10; ++$i ) echo "<option value='$i' ".($items==$i ? "selected='selected'" : '').">$i</option>"; ?></select></p>
		<input type="hidden" id="photopressrandomthumbs-submit" name="photopressrandomthumbs-submit" value="1" />
	<?php
	}


	/***** REGISTER *****/

	register_sidebar_widget('Photopress Thumbs', 'widget_photopress_randomthumbs');
	register_widget_control('Photopress Thumbs', 'widget_photopress_randomthumbs_control', 300, 110);

}

// Run our code later in case this loads prior to any required plugins.
add_action('plugins_loaded', 'widget_photopress_randomthumbs_init');

?>