<?php

if (!class_exists('wordtube')) return;

// CALL THIS
function get_listing_videodropdown($currid = '') {
	// generate option list for edit dialog
	get_wordtube_videodropdown($currid);
}

function the_listing_wtvideo($videoid) {
	// may seem simple but keeping this isolated will allow for 
	// unexpected changes in the WordTube plugin without having
	// to change any template tags
	global $WPwordTube;
	echo wt_GetVideo($videoid);
}

// DONT CALL THIS
function get_wordtube_videodropdown($currid = '') {
	global $wpdb;
	$tables = $wpdb->get_results("SELECT * FROM $wpdb->wordtube ORDER BY 'vid' ASC ");
	if($tables) {
		foreach($tables as $table) {
			echo '<option value="'.$table->vid.'" ';
			if ($table->vid == $currid) echo "selected='selected' ";
				echo '>'.$table->name.'</option>'."\n\t"; 
		}
	}
}

#TODO - playlist

?>

