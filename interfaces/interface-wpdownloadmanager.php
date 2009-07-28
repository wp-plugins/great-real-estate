<?php

# check for plugin wp-downloadmanager 
if (!function_exists('download_shortcode')) return;

// CALL THESE
function get_listing_downloaddropdown($currid = '', $cat = '') {
	// generate option list for edit dialog
	get_downloadmanager_dropdown($currid, $cat);
}

// DONT CALL THESE
function get_downloadmanager_brochurecatid() {
	$dl_cats = get_option('download_categories');
	$dl_catids = array_flip($dl_cats);
	return $dl_catids['Brochure'];
}

function _dlcat($id = '') {
	$dl_cats = get_option('download_categories');
	return $dl_cats[intval($id)];
}

function get_downloadmanager_dropdown($currid = '',$cat = '') {
	global $wpdb;
	$currids = explode(',',$currid);
	$cat_sel = "";
	if ($cat) {
		$cat_sel = " AND file_category = '" . intval($cat) . "' ";
	}
	$files = $wpdb->get_results("SELECT * FROM $wpdb->downloads WHERE file_permission != -2 ${cat_sel} ORDER BY 'file_name' ASC ");
	if($files) {
		foreach($files as $file) {
			echo '<option value="'.$file->file_id.'" ';
			if (in_array($file->file_id, $currids)) echo "selected='selected' ";
				echo '>'.$file->file_name. ' -- ' . _dlcat($file->file_category) . '</option>'."\n\t"; 
		}
	}
}

function downloadmanager_showdownloadlink($id = '0') {
	if ($id == '0') return;
	return download_shortcode( array('id' => $id, 'display' => 'both') );
}

?>
