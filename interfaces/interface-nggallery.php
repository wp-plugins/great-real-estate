<?php

/* Interface to NextGen-Gallery
 *
 * it is bad form for our plugin to access another plugin 
 * EXCEPT from this one file, where future changes we can't control can
 * be dealt with without having to mess with any other code
 *
 *
 * [2008-12-16] added support for NextGen 1.0+ new classname
 * [2008-08-05] added function returns if db table not there
 */

if (!(class_exists('nggallery') || class_exists('nggGallery'))) return;

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
	if (!$wpdb->nggallery) return;

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
	if (!$galleryid) return;

	if (!$wpdb->nggallery) return;

	if (! $ngg_options) {
		$ngg_options = get_option('ngg_options');
	}

	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
	if ($class) $myclass = ' class="'.$class.'" ';
	if ($picturelist) { 
		$pid = $picturelist[0]->pid;
		if (method_exists('nggGallery','get_thumbnail_url')) {
			// new NextGen 1.0+
			$out = '<img alt="' . __('property photo') . '" src="' . nggGallery::get_thumbnail_url($pid) . '" ' . $myclass . ' />';
		} else {
			// backwards compatibility - NextGen below 1.0
			$out = '<img alt="' . __('property photo') . '" src="' . nggallery::get_thumbnail_url($pid) . '" ' . $myclass . ' />';
		}
		return $out;
	}
}

// for RSS feeds
function nextgengallery_picturelist($galleryid) {
	if (!$galleryid) return;

	global $wpdb;
	global $ngg_options;
	if (!$wpdb->nggallery) return;

	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
	if ($picturelist) { 
		return $picturelist;
	}
}

?>
