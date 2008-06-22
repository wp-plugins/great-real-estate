<?php
/*
Plugin Name: Current Issue Widget
Plugin URI: 
Description: Sidebar widget to display an image with a link
Version: 0.1
Author: Roger Theriault
Author URI: http://www.rogertheriault.com/agents
*/


function wp_widget_currentissue($args, $widget_args = 1) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('widget_currentissue');
	if ( !isset($options[$number]) )
		return;

	$heading = $options[$number]['heading'];
	$description = $options[$number]['description'];
	$imgname = $options[$number]['imgname'];
	$pdfname = $options[$number]['pdfname'];
?>
	<?php echo $before_widget; ?>
<?php if ($heading) { ?>
	<h2><?php echo $heading; ?></h2>
<?php } ?>
<div class="currentissue"><?php echo $description; ?>
<?php if ($imgname && $pdfname) { ?>
	<a href="<?php echo bloginfo('url'); ?>/wp-content/issues/<?php echo $pdfname; ?>"><img src="<?php echo bloginfo('url'); ?>/wp-content/issues/<?php echo $imgname; ?>" alt="<?php echo $heading; ?>" /></a>
<?php } ?>
</div>
	<?php echo $after_widget; ?>
<?php
}

function wp_widget_currentissue_control($widget_args) {
	global $wp_registered_widgets;
	static $updated = false;

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	$options = get_option('widget_currentissue');
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
			if ( 'wp_widget_currentissue' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "currentissue-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
					unset($options[$widget_number]);
			}
		}

		foreach ( (array) $_POST['widget-currentissue'] as $widget_number => $widget_currentissue ) {
			if ( !isset($widget_currentissue['pdfname']) && isset($options[$widget_number]) ) // user clicked cancel
				continue;
			$heading = strip_tags(stripslashes($widget_currentissue['heading']));
			$description = strip_tags(stripslashes($widget_currentissue['description']));
			$imgname = strip_tags(stripslashes($widget_currentissue['imgname']));
			$pdfname = strip_tags(stripslashes($widget_currentissue['pdfname']));
			$options[$widget_number] = compact( array('heading','description','imgname','pdfname') );
		}

		update_option('widget_currentissue', $options);
		$updated = true;
	}

	if ( -1 == $number ) {
		$heading = '';
		$description = '';
		$imgname = '';
		$pdfname = '';
		$number = '%i%';
	} else {
		$heading = attribute_escape($options[$number]['heading']);
		$description = attribute_escape($options[$number]['description']);
		$imgname = attribute_escape($options[$number]['imgname']);
		$pdfname = attribute_escape($options[$number]['pdfname']);
	}
?>
		<p>
		<label for="currentissue-heading-<?php echo $number; ?>">
					<?php _e( 'Title:' ); ?>
			<input class="widefat" id="currentissue-heading-<?php echo $number; ?>" name="widget-currentissue[<?php echo $number; ?>][heading]" type="text" value="<?php echo $heading; ?>" />
		</label>
		</p>
		<p>
		<label for="currentissue-description-<?php echo $number; ?>">
					<?php _e( 'Text:' ); ?>
			<input class="widefat" id="currentissue-description-<?php echo $number; ?>" name="widget-currentissue[<?php echo $number; ?>][description]" type="text" value="<?php echo $description; ?>" />
		</label>
		</p>
		<p>
		<label for="currentissue-imgname-<?php echo $number; ?>">
					<?php _e( 'Thumbnail jpg:' ); ?>
			<input class="widefat" id="currentissue-imgname-<?php echo $number; ?>" name="widget-currentissue[<?php echo $number; ?>][imgname]" type="text" value="<?php echo $imgname; ?>" />
		</label>
		</p>
		<p>
		<label for="currentissue-pdfname-<?php echo $number; ?>">
					<?php _e( 'PDF Filename:' ); ?>
			<input class="widefat" id="currentissue-pdfname-<?php echo $number; ?>" name="widget-currentissue[<?php echo $number; ?>][pdfname]" type="text" value="<?php echo $pdfname; ?>" />
		</label>
		</p>
			<input type="hidden" name="widget-currentissue[<?php echo $number; ?>][submit]" value="1" />
		</p>
<?php
}

function wp_widget_currentissue_register() {
	if ( !$options = get_option('widget_currentissue') )
		$options = array();
	$widget_ops = array('classname' => 'widget_currentissue', 'description' => __('Current Issue - download link'));
	$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'currentissue');
	$name = __('CurrentIssue');

	$id = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['pdfname']) )
			continue;
		$id = "currentissue-$o"; // Never never never translate an id
		wp_register_sidebar_widget($id, $name, 'wp_widget_currentissue', $widget_ops, array( 'number' => $o ));
		wp_register_widget_control($id, $name, 'wp_widget_currentissue_control', $control_ops, array( 'number' => $o ));
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$id ) {
		wp_register_sidebar_widget( 'currentissue-1', $name, 'wp_widget_currentissue', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'currentissue-1', $name, 'wp_widget_currentissue_control', $control_ops, array( 'number' => -1 ) );
	}
}

/* WIDGETS
 * Initialize our widgets
 */
function currentissue_widgets_init() {
	if ( !is_blog_installed() ) return;

	wp_widget_currentissue_register();

	// do_action('widgets_init');
}
add_action('widgets_init', 'currentissue_widgets_init');

?>
