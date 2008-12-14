<?php

/*
 * Changelog:
 * [2008-08-02] removed plugin header and included from main code; widget will now automatically be enabled when the plugin is activated. This avoids having to keep the header in sync and also avoids any confusion.
 * [2008-06-27] updated version number to conform to main file
 *
 */

function wp_widget_grefeatured($args, $widget_args = 1) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('widget_grefeatured');
	if ( !isset($options[$number]) )
		return;

	$heading = $options[$number]['heading'];
	$type = $options[$number]['type'];
	$sort = $options[$number]['sort'];
	$numtoshow = $options[$number]['numtoshow'];

	if (function_exists(show_listings_featured)) {
?>
		<?php echo $before_widget; ?>
			<div class="widget featuredlistings-widget"><?php show_listings_featured($numtoshow,$sort,$type,$heading,$filter); ?></div>
		<?php echo $after_widget; ?>
<?php
	}
}

function wp_widget_grefeatured_control($widget_args) {
	global $wp_registered_widgets;
	static $updated = false;

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('widget_grefeatured');
	if ( !is_array($options) )
		$options = array();

	if ( !$updated && !empty($_POST['sidebar']) ) {
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( $this_sidebar as $_widget_id ) {
			if ( 'wp_widget_grefeatured' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "grefeatured-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
					unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-grefeatured'] as $widget_number => $widget_grefeatured ) {
			if ( !isset($widget_grefeatured['numtoshow']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$numtoshow = strip_tags(stripslashes($widget_grefeatured['numtoshow']));
			$heading = strip_tags(stripslashes($widget_grefeatured['heading']));
			$sort = strip_tags(stripslashes($widget_grefeatured['sort']));
			if ( ! in_array( $sort, array( 'random', 'highprice', 'lowprice', 'title', 'listdate' ) ) ) {
				// sanity check failed, not valid option - set default
				$sort = 'random';
			}

			$type = strip_tags(stripslashes($widget_grefeatured['type']));
			if ( ! in_array( $type, array( 'basic', 'text' ) ) ) {
				// sanity check failed, not valid option - set default
				$type = 'basic';
			}
			$options[$widget_number] = compact( array('numtoshow','heading','sort','type') );
		}

		update_option('widget_grefeatured', $options);
		$updated = true;
	}

	if ( -1 == $number ) {
		$numtoshow = '';
		$heading = '';
		$type = '';
		$sort = '';
		$number = '%i%';
	} else {
		$numtoshow = attribute_escape($options[$number]['numtoshow']);
		$heading   = attribute_escape($options[$number]['heading']);
		$type      = attribute_escape($options[$number]['type']);
		$sort      = attribute_escape($options[$number]['sort']);
	}
?>

		<p>
		<label for="grefeatured-heading-<?php echo $number; ?>">
		<?php _e( 'Title:' ); ?>
		<input class="widefat" id="grefeatured-heading-<?php echo $number; ?>" name="widget-grefeatured[<?php echo $number; ?>][heading]" type="text" value="<?php echo $heading; ?>" />
		</label>
		</p>
		<p>
		<label for="grefeatured-type-<?php echo $number; ?>">
		<?php _e('Display type: ','greatrealestate'); ?>
		<select name="widget-grefeatured[<?php echo $number; ?>][type]" id="grefeatured-type-<?php echo $number; ?>" class="widefat">
			<option value="basic"<?php selected( $type, 'basic' ); ?>><?php _e('Thumbnail list'); ?></option>
			<option value="text"<?php selected( $type, 'text' ); ?>><?php _e( 'Text links' ); ?></option>
		</select>
		</label>
		</p>
		<p>
		<label for="grefeatured-numtoshow-<?php echo $number; ?>">
		<?php _e('Number of homes: ','greatrealestate'); ?>
		<input class="widefat" id="grefeatured-numtoshow-<?php echo $number; ?>" name="widget-grefeatured[<?php echo $number; ?>][numtoshow]" type="text" value="<?php echo $numtoshow; ?>" />
		</label>
		</p>
		<p>
		<label for="grefeatured-sort-<?php echo $number; ?>">
		<?php _e( 'Sort by:' ); ?>
		<select name="widget-grefeatured[<?php echo $number; ?>][sort]" id="grefeatured-sort-<?php echo $number; ?>" class="widefat">
			<option value="random"<?php selected( $sort, 'random' ); ?>><?php _e('Random order'); ?></option>
			<option value="title"<?php selected( $sort, 'title' ); ?>><?php _e('Page title order'); ?></option>
			<option value="highprice"<?php selected( $sort, 'highprice' ); ?>><?php _e( 'Highest price' ); ?></option>
			<option value="lowprice"<?php selected( $sort, 'lowprice' ); ?>><?php _e( 'Lowest price' ); ?></option>
			<option value="listdate"<?php selected( $sort, 'listdate' ); ?>><?php _e( 'Recently listed' ); ?></option>
		</select>
		</label>
		<input type="hidden" name="widget-grefeatured[<?php echo $number; ?>][submit]" value="1" />
		</p>
<?php
}

function wp_widget_grefeatured_register() {
	if ( !$options = get_option('widget_grefeatured') )
		$options = array();
	$widget_ops = array('classname' => 'widget_grefeatured', 'description' => __('Featured real estate listings'));
	$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'grefeatured');
	$name = __('Featured Listings');

	$id = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['numtoshow']) )
			continue;
		$id = "grefeatured-$o"; // Never never never translate an id
		wp_register_sidebar_widget($id, $name, 'wp_widget_grefeatured', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'wp_widget_grefeatured_control', $control_ops, array( 'number' => $o ));
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$id ) {
		wp_register_sidebar_widget( 'grefeatured-1', $name, 'wp_widget_grefeatured', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'grefeatured-1', $name, 'wp_widget_grefeatured_control', $control_ops, array( 'number' => -1 ) );
	}
}

/* WIDGETS
 * Initialize our widgets
 */
function grefeatured_widgets_init() {
	if ( !is_blog_installed() ) return;

	wp_widget_grefeatured_register();
}
add_action('widgets_init', 'grefeatured_widgets_init');



?>
