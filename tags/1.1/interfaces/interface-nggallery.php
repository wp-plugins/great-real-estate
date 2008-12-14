<?php

/* Interface to NextGen-Gallery
 *
 * it is bad form for our plugin to access another plugin 
 * EXCEPT from this one file, where future changes we can't control can
 * be dealt with without having to mess with any other code
 */

if (!class_exists('nggallery')) return;

// USE THESE FUNCTIONS
function get_listing_gallerydropdown($currid = '') {
	// generates option list for edit dialogs
	get_nextgengallery_dropdown($currid);
}
function listings_showfirstpic($galleryid,$class = '') {
	// inserts IMG tag for thumbnail
	return nextgengallery_showfirstpic($galleryid,$class);
}
function listings_nggshowgallery($galleryid) {
	if (!$galleryid) return;

	echo nggShowGallery($galleryid);
}

// DONT CALL THESE PLEASE
function get_nextgengallery_dropdown($currid = '') {
	global $wpdb;
	$tables = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY 'name' ASC ");
	if($tables) {
		foreach($tables as $table) {
			echo '<option value="'.$table->gid.'" ';
			if ($table->gid == $currid) echo "selected='selected' ";
				echo '>'.$table->name.'</option>'."\n\t"; 
		}
	}
}

function nextgengallery_showfirstpic($galleryid, $class = '') {
	global $wpdb;
	global $ngg_options;

	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
	if ($class) $myclass = ' class="'.$class.'" ';
	if ($picturelist) { 
		$pid = $picturelist[0]->pid;
		$out = '<img alt="' . __('property photo') . '" src="' . nggallery::get_thumbnail_url($pid) . '" ' . $myclass . ' />';
		return $out;
	}
}

// for RSS feeds
function nextgengallery_picturelist($galleryid) {
	if (!$galleryid) return;

	global $wpdb;
	global $ngg_options;

	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
	if ($picturelist) { 
		return $picturelist;
	}
}

?>
