<?php

/* Great Real Estate
 * general admin functions 
 *
 * needed only on admin screens
 *
 */

# note the following action needs a corresponding simple_edit_form action
# to store results
#
// Stop direct call
#if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

// Admin menu structure
add_action('admin_menu', 'greatrealestate_add_admin_menu');

function greatrealestate_add_admin_menu() {
	// Add a new top-level menu for all Real Estate stuff
	add_menu_page('Real Estate','Real Estate', 'edit_pages', GREFOLDER, 'gre_show_menu');
	// Add a sublevel page to that
	add_submenu_page(GREFOLDER, 'Listings','Listings', 'edit_pages', 'greatrealestate-listings', 'gre_show_menu');
	if (current_user_can('manage_options')) {
		// Add settings page
		add_submenu_page(GREFOLDER, 'Settings', 'Settings', 'manage_options', 'greatrealestate-options', 'gre_show_menu');
	}
	add_submenu_page(GREFOLDER, 'About', 'About', 'edit_pages', 'greatrealestate-about', 'gre_show_menu');
	# add admin menu pages
	# NEEDED: manage custom templates (options)
	# add property record
	# update / delete property record
}



// only include the absolutely necessary pieces for each admin page
function gre_show_menu() {
	global $wp_version;
	switch ($_GET["page"]) {
	case "greatrealestate-listings" :
		include_once (dirname (__FILE__) . '/listings.php');
		greatrealestate_admin_listings();
		break;
	case "greatrealestate-options" :
		include_once (dirname (__FILE__) . '/settings.php');
		greatrealestate_admin_settings();
		break;
	case "greatrealestate-about" :
		include_once (dirname (__FILE__) . '/about.php');
		greatrealestate_admin_about();
		break;
	case "greatrealestate" :
	default :
		include_once (dirname (__FILE__) . '/main.php');
		greatrealestate_admin_main();
		break;
	}
}

function greatrealestate_add_adminhead( ) {

	// add stylesheet
	echo "\n".'<style type="text/css" media="screen">@import "'. GRE_URLPATH .'/admin/admin.css";</style>'."\n";
}
add_action ('admin_head','greatrealestate_add_adminhead');

# additional custom fields on page edit form
function greatrealestate_add_edit() {
	global $post_ID;
	global $post;
	global $wpdb;
	global $listing;
	# only add this stuff if the Page is subpage of the Listings Main Page
	if (get_option('greatrealestate_pageforlistings') == $post->post_parent) {
		# get the listing data associated with the post/page
		$listing = $wpdb->get_row("SELECT * FROM $wpdb->gre_listings WHERE pageid = '$post_ID'");
		include (dirname (__FILE__).'/editpage.php');
	}
}
add_action('edit_page_form', 'greatrealestate_add_edit', 90);

# corresponding header content for page edit (page.php)
function greatrealestate_add_edit_js() {
	// We're trying not to toss this in every dang admin page
	global $post;
	if (get_option('greatrealestate_pageforlistings') == $post->post_parent) {
		    # wp_enqueue_script('jquery');
		    # for some reason enqueue doesn't work in admin screens
?>
<!-- added by great-real-estate -->
	<link rel="stylesheet" href="<?php echo GRE_URLPATH ?>css/ui.datepicker.css" type="text/css" />
<?php
	echo "\n<!-- form helper and validation -->\n";
?>
<script type="text/javascript" src="<?php echo GRE_URLPATH; ?>js/ui.datepicker.js"></script>
<script type="text/javascript" src="<?php echo GRE_URLPATH; ?>js/jquery.validate.js"></script>
<?php
		#wp_enqueue_script('ui-datepicker', GRE_URLPATH . 'js/ui.datepicker.js', array('jquery'));
		#wp_enqueue_script('jquery-validate', GRE_URLPATH . 'js/jquery.validate.js', array('jquery'), '1.4');
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function() {
   jQuery('.date-input').datepicker();
   jQuery("#post").validate();
} );
/* ]]> */
</script>
<!-- end added by great-real-estate -->
<?php
	}
}
add_action('admin_print_scripts', 'greatrealestate_add_edit_js', 90);

function greatrealestate_add_savepage($postID) {
	# handle input from page being saved (Write Page)
	# Data associated with a particular listing
	#
	global $wpdb;

	# quit if this is not a "listing" as we define it
	// need valid post
	if (! $postID) return;
	// need our main listing page defined
	if (! $listparent = get_option('greatrealestate_pageforlistings')) return;
	// need this page to be a child of that page
	$postparent = $wpdb->get_var("SELECT post_parent from $wpdb->posts WHERE ID = '$postID'  LIMIT 1");
	if (! ($listparent == $postparent )) return;

	# first, do we already have a listing record associated with
	# this particular post?
	# if so, get the id
	# NOTE: we are being passed an id $postID in the function call
	if ($postID > 0) {
		$listdata_id = $wpdb->get_var("SELECT id from $wpdb->gre_listings WHERE pageid = '$postID'  LIMIT 1");
	}
	
	# filter input, store it (new or update)
	
	# not much checking, yet... some JS validation on edit form
	# to clear out an entry we update even if blank
	#
	$listing->pageid = $postID;

	$listing->mlsid = $_POST['listings_mlsid'];
	$listing->address = $_POST['listings_address'];
	$listing->city = $_POST['listings_city'];
	$listing->state = $_POST['listings_state'];
	$listing->postcode = $_POST['listings_postcode'];

	$listing->featured = $_POST['listings_featured'];
	$listing->status = $_POST['listings_status'];
	$listing->blurb = $_POST['listings_blurb'];
	$listing->listdate = to_mysqldate($_POST['listings_listdate']);
	$listing->listprice = $_POST['listings_listprice'];
	$listing->saledate = to_mysqldate($_POST['listings_saledate']);
	$listing->saleprice = $_POST['listings_saleprice'];
	$listing->latitude = $_POST['listings_latitude'];
	$listing->longitude = $_POST['listings_longitude']; 

	$listing->galleryid = $_POST['listings_galleryid'];
	$listing->videoid = $_POST['listings_videoid'];
	$listing->downloadid = $_POST['listings_downloadid']; # multi-select
	$listing->panoid = $_POST['listings_panoid']; # multi-select

	$listing->featureid = $_POST['listings_featureid']; # multi-select

	$listing->bedrooms = $_POST['listings_bedrooms'];
	$listing->bathrooms = $_POST['listings_bathrooms'];
	$listing->halfbaths = $_POST['listings_halfbaths'];
	$listing->garage = $_POST['listings_garage'];
	$listing->acsf = $_POST['listings_acsf'];
	$listing->totsf = $_POST['listings_totsf'];
	$listing->acres = $_POST['listings_acres'];

	# rearrange any multi-selects, ie arrays, into comma delimited
	foreach ($listing as $lkey => $lvalue) {
		if (is_array($lvalue)) {
			$listing->$lkey = implode(',',$lvalue);
		}
	}

	if ($listdata_id) {
		# update existing record
		# safer to reference the index on update
		$sql = "UPDATE $wpdb->gre_listings SET ";
		foreach ($listing as $lkey => $lvalue) {
			$sql .= "$lkey = '$lvalue', ";
		}
		$sql = substr($sql,0,-2); // remove last comma
		$sql .= " WHERE id = '$listdata_id'";
		$wpdb->query($sql);
	} else {
		# new association - link to current post id
		if ($postID) {
			$sql = "INSERT INTO $wpdb->gre_listings (";
			$values = ' VALUES (';
			foreach ($listing as $lkey => $lvalue) {
				$sql .= "$lkey,";
				$values .= "'$lvalue',";
			}
			$sql = substr($sql,0,-1); // remove last comma
			$values = substr($values,0,-1); // remove last comma
			$sql .= ') ' . $values . ') ';
			$wpdb->query($sql);
		} else {
			// Whoops - can't save, no post id
			echo "WHOA! we dont know the page id (given $postID)";
			exit;
		}
	}
}
add_action('save_post', 'greatrealestate_add_savepage');







# utility functions for edit page
# return list of options, with current option selected
function re_status_dropdown($currstatus) {
	global $re_status; // array with all valid statuses
	$ddlist = "";
	foreach ($re_status as $id => $status) {
		$mysel = ($currstatus == $id) ? ' selected="selected"' : "";
		$ddlist .= "<option$mysel value='$id'>$status</option>";
	}
	echo $ddlist;
	return;
}

function get_listing_panodropdown($currid = '') {
	global $wpdb;
	global $post;
	$id = $post->ID;
	$currids = explode(',',$currid);
	$files = get_children("post_parent=$id&post_type=attachment&post_mime_type=video/quicktime");
	if($files) {
		foreach($files as $fid => $attachment) {
			echo '<option value="'.$fid.'" ';
			if (in_array($fid, $currids)) echo "selected='selected' ";
				echo '>'.$attachment->post_title.'</option>'."\n\t"; 
		}
	}
}

function get_listing_featuredropdown($currid = '') {
	global $wpdb;
	global $post;
	global $re_features;
	$id = $post->ID;
	$currids = explode(',',$currid);
	$features = $re_features;
	if($features) {
		foreach($features as $fid => $label) {
			echo '<option value="'.$fid.'" ';
			if (in_array($fid, $currids)) echo "selected='selected' ";
			echo '>'.$label.'</option>'."\n\t"; 
		}
	}
}




?>
