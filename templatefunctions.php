<?php 
/*
 * public functions provided by the Great Real Estate plugin
 * these functions abstract the interface so we can have the freedom
 * to make later changes without busting stuff
 *
 * #########################################################
 *  Use these functions in themes. 
 *  eg: <?php the_listing_listprice(); ?>
 *
 * IMPORTANT! before using these template tags,
 * you must set up the listing data.
 * In a regular loop, add this:
 * 	<?php getandsetup_listingdata(); ?>
 *
 */


function is_listing() {
	global $post;
	# user must set this; any subpage is considered a listing
	if (get_option('greatrealestate_pageforlistings') == $post->post_parent) {
		return TRUE;
	}
	return FALSE;
}
function is_listing_index() {
	global $post;
	# user must set this
	if (get_option('greatrealestate_pageforlistings') == $post->ID) {
		return TRUE;
	}
	return FALSE;
}

# short text description
function get_listing_blurb() {
	global $listing;
	return $listing->blurb;
}
function the_listing_blurb() {
	global $listing;
	echo $listing->blurb;
}

# list price of the home, eg: 250000, formatted
function get_listing_listprice() {
	global $listing;
	if ($listing->listprice <= 0) return "";
	return __("$","greatrealestate") . number_format($listing->listprice);
}
function the_listing_listprice() {   
	global $listing;
	if ($listing->listprice <= 0) return "";
	echo __("$","greatrealestate") . number_format($listing->listprice);
}

# what it sold for eg: 225000
function get_listing_saleprice() {
	global $listing;
	if ($listing->saleprice <= 0) return "";
	return __("$","greatrealestate") . number_format($listing->saleprice);
}
function the_listing_saleprice() {
	global $listing;
	if ($listing->saleprice <= 0) return "";
	echo __("$","greatrealestate") . number_format($listing->saleprice);
}

function get_listing_listdate() {
	// returns in mm/dd/yyyy format for use with input forms
	global $listing;
	return mdy_dateformat($listing->listdate);
}
function the_listing_listdate() {
	// returns in preferred format for end-user displays
	global $listing;
	echo mywp_dateformat($listing->listdate);
}
function get_listing_saledate() {
	// returns in mm/dd/yyyy format for use with input forms
	global $listing;
	return mdy_dateformat($listing->saledate);
}
function the_listing_saledate() {
	// returns in preferred format for end-user displays
	global $listing;
	echo mywp_dateformat($listing->saledate);
}


# current listing status eg: For Sale, For Rent, Pending Sale, etc
function get_listing_status() {
	global $listing;
	global $re_status;
	return $re_status[$listing->status];
}
function the_listing_status() {
	global $listing;
	global $re_status;
	echo $re_status[$listing->status];
}
function get_listing_hasclosed() {
	global $listing;
	global $re_status;

	$status = $listing->status;
	if (($status == RE_SOLD) || ($status == RE_RENTED)) return true;
	return false;
}

# TODO : listing status images

# Date listed (in blog date format)

# Thumbnail image - <img> tag format 
function get_listing_thumbnail() {
	$galleryid = get_listing_galleryid();
	return listings_showfirstpic($galleryid,'listing-thumb');
}
function the_listing_thumbnail() {
	$galleryid = get_listing_galleryid();
	echo listings_showfirstpic($galleryid);
}

# Geocode info - Latitude and Longitude - digital format
function get_listing_latitude() {
	global $listing;
	return $listing->latitude;
}
function get_listing_longitude() {
	global $listing;
	return $listing->longitude;
}

# Note: these interface functions can be utilized in your themes 
# if you decide to call a plugin function
# just give it the id from one of these functions

# Gallery - uses nggallery
function get_listing_galleryid() {
	global $listing;
	return $listing->galleryid;
}

# Video - uses wordtube
function get_listing_videoid() {
	global $listing;
	return $listing->videoid;
}

# Downloads - uses WP Download Manager
# returns a comma separated list
function get_listing_downloadid() {
	global $listing;
	return $listing->downloadid;
}

# Panoramas - Uses Media attachments from current post
# (Video/Quicktime - ie MOV files - should be QuicktimeVR)
# returns a comma separated list
function get_listing_panoid() {
	global $listing;
	return $listing->panoid;
}

function get_listing_bedrooms() {
	global $listing;
	return $listing->bedrooms;
}
function the_listing_bedrooms() {
	global $listing;
	echo $listing->bedrooms;
}
function get_listing_bathrooms() {
	global $listing;
	return $listing->bathrooms;
}
function the_listing_bathrooms() {
	global $listing;
	echo $listing->bathrooms;
}
function get_listing_halfbaths() {
	global $listing;
	return $listing->halfbaths;
}
function the_listing_halfbaths() {
	global $listing;
	echo $listing->halfbaths;
}
function get_listing_garage() {
	global $listing;
	return $listing->garage;
}
function the_listing_garage() {
	global $listing;
	echo $listing->garage;
}
function get_listing_acsf() {
	global $listing;
	return number_format($listing->acsf);
}
function the_listing_acsf() {
	global $listing;
	echo number_format($listing->acsf);
}
function get_listing_totsf() {
	global $listing;
	return number_format($listing->totsf);
}
function the_listing_totsf() {
	global $listing;
	echo number_format($listing->totsf);
}
function get_listing_acres() {
	global $listing;
	return number_format($listing->acres,2);
}
function the_listing_acres() {
	global $listing;
	echo number_format($listing->acres,2);
}
function get_listing_featureid() {
	global $listing;
	return $listing->featureid;
}


function get_listing_haspool() {
	return get_listing_hasfeature(RE_FEAT_POOL);
}
function get_listing_haswater() {
	return get_listing_hasfeature(RE_FEAT_WATER);
}
function get_listing_hasgolf() {
	return get_listing_hasfeature(RE_FEAT_GOLF);
}
function get_listing_hascondo() {
	return get_listing_hasfeature(RE_FEAT_CONDO);
}
function get_listing_hastownhome() {
	return get_listing_hasfeature(RE_FEAT_TH);
}

function get_listing_address() {
	global $listing;
	return $listing->address;
}
function the_listing_address() {
	global $listing;
	echo $listing->address;
}
function get_listing_city() {
	global $listing;
	return $listing->city;
}
function the_listing_city() {
	global $listing;
	echo $listing->city;
}
function get_listing_state() {
	global $listing;
	return $listing->state;
}
function the_listing_state() {
	global $listing;
	echo $listing->state;
}
function get_listing_postcode() {
	global $listing;
	return $listing->postcode;
}
function the_listing_postcode() {
	global $listing;
	echo $listing->postcode;
}
function get_listing_mlsid() {
	global $listing;
	return $listing->mlsid;
}
function the_listing_mlsid() {
	global $listing;
	echo $listing->mlsid;
}




// * these functions generate the various detail panel content and tabs
// NOTE: jQuery depends on the output structure to enable the 
// tabbed navigation

// the _tab functions create the tab itself only if there is content
// the _content functions output the actual content
function the_listing_description_tab($before='<li>',$after='</li>') {
	global $post;
	if (!$post->post_content) return;

	echo $before;
	echo '<a href="#text" title="'.__("Property description","greatrealestate") . '" ><span>';
	_e("Description","greatrealestate");
	echo '</span></a>';
	echo $after;
}
// following function should be used in the regular content pane,
// before the tabbed section begins
function the_listing_description_beforemore() {
	echo get_listing_description_beforemore();
}
// what goes outside the tabbed area
function get_listing_description_beforemore() {
	// outputs whatever is in front of the MORE tag
	// (not really an excerpt so we don't use that term)
	global $post;
	if (!$post) return;
	if (!$post->post_content) return;
	$splitpost = get_extended($post->post_content);
	if ($splitpost['extended']) {
		$output = '<div class="listing-summary">';
		$beforemore = $splitpost['main']; // just output the beginning
		// tell our own filter to skip
		$spctag = '<!--grenofilters-->';
		$beforemore .= $spctag;
		$beforemore = apply_filters('the_content', $beforemore);
		$output .= $beforemore;
		$output .= '</div>';
		return $output;
	}
}
// what goes inside the tabbed area - echoed
function the_listing_description_content() {
	// pardon the assumptions in formatting
	global $post;

	if (!$post) return;
	if (!$post->post_content) return;
?>
	<div id="text">  
	<h2>Property Details</h2>
	<div><?php
	$splitpost = get_extended($post->post_content);

	$spctag = '<!--grenofilters-->';
	// check if there's a more tag
	if ($extension = $splitpost['extended']) {
		// just continue after more tag
		$extension = apply_filters('the_content', $spctag . $extension );
		$extension = str_replace(']]>', ']]&gt;', $extension);
		echo $extension;
	} else {
		// the whole ball o wax, filtered as usual
		// nothing will be seen above the tabs since there was
		// no MORE tag
		$main = apply_filters('the_content', $spctag . $splitpost['main']);
		$main = str_replace(']]>', ']]&gt;', $main);
		echo $main;
	}
	?>
	</div>
	</div>
<?php
}

// GALLERY
function the_listing_gallery_tab($before='<li>',$after='</li>') {
	if (!function_exists('nextgengallery_picturelist')) return;
	if (!get_listing_galleryid()) return;

	echo $before;
	echo '<a href="#photogallery" title="'.__("Photo Gallery","greatrealestate") . '" ><span>';
	_e("Gallery","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_gallery_content() {
	if (!function_exists('nextgengallery_picturelist')) return;
	if (!($galleryid = get_listing_galleryid())) return;
?>
<div id="photogallery">
<h2><?php _e("Photo Gallery"); ?></h2>
   <div><?php listings_nggshowgallery($galleryid); ?></div>
</div>
<?php
}

// VIDEOS
function the_listing_video_tab($before='<li>',$after='</li>') {
	if (!function_exists('the_listing_wtvideo')) return;
	if (!get_listing_videoid()) return;

	echo $before;
	echo '<a href="#videos" title="'.__("Video Walkthrough","greatrealestate") . '" ><span>';
	_e("Videos","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_video_content() {
	if (!function_exists('the_listing_wtvideo')) return;
	if (!($videoid = get_listing_videoid())) return;
?>
<div id="videos">
<h2><?php _e("Video Walkthrough"); ?></h2>
   <div><?php the_listing_wtvideo($videoid); ?></div>
</div>
<?php
}

// PANORAMAS
function the_listing_panorama_tab($before='<li>',$after='</li>') {
	if (!function_exists('show_fpp_panos')) return;
	if (!get_listing_panoid()) return;

	echo $before;
	echo '<a href="#panoramas" title="'.__("360&deg; Panoramas","greatrealestate") . '" ><span>';
	_e("Panoramas","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_panorama_content() {
	if (!function_exists('show_fpp_panos')) return;
	if (!($panoids = get_listing_panoid())) return;
?>
<div id="panoramas">
<h2><?php _e("Panoramas",'greatrealestate'); ?><span id="vr_label"></span></h2>
<?php
	show_fpp_panos($panoids); // this is the documented template function
				// for the fpp-pano plugin
?>
</div>
<?php
}


// DOWNLOADS
function the_listing_downloads_tab($before='<li>',$after='</li>') {
	if (!function_exists('downloadmanager_showdownloadlink')) return;
	if (!get_listing_downloadid()) return;

	echo $before;
	echo '<a href="#downloads" title="'.__("Downloads","greatrealestate") . '" ><span>';
	_e("Downloads","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_downloads_content() {
	if (!function_exists('downloadmanager_showdownloadlink')) return;
        if (!($downloadids = get_listing_downloadid())) return;
?>
<div id="downloads">
   <h2>Downloads</h2>
   <div>
<div class="lfloat">
<?php echo downloadmanager_showdownloadlink($downloadids); ?>
</div>

<div class="adobe-reader"><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank" title="Download Adobe Reader software" ><img src="<?php echo GRE_URLPATH; ?>/images/get_adobe_reader.gif" alt="Download Adobe Reader" /></a></div>

	<div class="cfloat">&nbsp;</div>
    </div>
</div>
<?php 
}

// MAPS
// Note: due to jQuery Tabs weirdness, it is recommended that
// this be the first tab to display if it exists
// Otherwise, gMaps may not properly initialize and the map
// will be messed up
function the_listing_map_tab($before='<li>',$after='</li>') {
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;

	echo $before;
	echo '<a href="#map"  title="'.__("Location Map","greatrealestate") . '" ><span>';
	_e("Map","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_map_content() {
	if (!get_option('greatrealestate_googleAPIkey')) return;
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;
	// See the jQuery code in the main great-real-estate.php file
	// for how to make GMaps work nice with tabbed panels
	//
	// TODO - only one function call and a var definition, put the
	//        rest in the header for cleaner page
?>
<div id="map">
   <h2>Location Map</h2>
   <div><div id="map_canvas"></div></div>
<script type="text/javascript">
/* <![CDATA[ */
	function gre_setupmap() {
		var prop_point = new google.maps.LatLng(<?php echo get_listing_latitude(); ?>,<?php echo get_listing_longitude(); ?>);
		map.setCenter(prop_point, 13);
		var prop_marker = createMarker(prop_point, '<?php the_listing_js_mapinfo(); ?>');
		map.addOverlay(prop_marker);
	}
	google.load("maps", "2");
	google.setOnLoadCallback(mapinitialize);
	

/* ]]> */
</script>
</div>
<?php
}
// The HTML popup for our listing on gMaps
function the_listing_js_mapinfo() {
	// stick in JS, do not add linefeeds, no single quotes!
	// escape </ to <\/
	echo '<div id="gmap-info">' . 
		'<h3>' . get_listing_blurb() . '<\/h3>' .
		get_listing_thumbnail();
	echo "<div>";
	the_listing_address();
	echo "<br />";
	the_listing_city();
	echo ", ";
	the_listing_state();
	echo "  ";
	the_listing_postcode();
	echo "<br />";
	echo get_listing_bedrooms() . "BR / " .
		get_listing_bathrooms() . "." .
		get_listing_halfbaths() . "BA";
	echo "<br />";
        echo get_listing_status() . " ";
	if (get_listing_hasclosed()) {
		echo get_listing_saleprice(); 
	} else {
		echo get_listing_listprice(); 
	}
	echo "<\/div>";
	echo "<\/div>";
}


// NEIGHBORHOOD - not yet implemented
function the_listing_community_tab($before='<li>',$after='</li>') {
	return;

	echo $before;
	echo '<a href="#community"  title="'.__("Community Information","greatrealestate") . '" ><span>';
	_e("Community","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_community_content() {
	return;
?>
<div id="community">
   <h2>Community Information</h2>
   <div>This feature is not implemented.</div>
</div>
<?php
}

// display featured homes on sidebar or on home page
// uses different display types
function show_listings_featured($maxlistings = '',$sort = 'random',$type = 'basic',$heading = 'Featured Listings') {
	$maxtoshow = (int) $maxlistings;
	if ((!$maxlistings) || $maxlistings == '' || ($maxtoshow <= 0)) {
		// use default setting
		$maxtoshow = (int) get_option('greatrealestate_maxfeatured');
	}
	global $wpdb;

 	$featured = get_pages_with_featured_listings($maxtoshow,$sort);
	if (! $featured) {
		echo "<!-- no featured listings -->";
		return;
	}
	// definitely have something to show - spit it out
?>
<div id="featuredlistings">
<h2><?php echo $heading; ?></h2>
<?php
	global $post;
	if ($type == 'text') {
?>
<ul>
<?php	}
  	foreach ($featured as $post) {
		setup_postdata($post);
		setup_listingdata($post);
		switch ($type) {
		case 'text':
?>
<li><a href="<?php the_permalink(); ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a>
<br /><?php the_listing_status(); ?> <?php the_listing_listprice(); ?></li>
		
<?php
			break;
		case 'basic' :
		default :
?>
<div class="prop-box-featured">
<div class="prop-thumb">
<?php the_listing_thumbnail(); ?>
</div>
<h3><a href="<?php the_permalink(); ?>" title="<?php _e('More about','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a></h3>
<p><em><?php the_listing_blurb(); ?></em></p>
<p><?php the_listing_status(); ?> <?php the_listing_listprice(); ?></p>
</div>
<?php
			break;
		}
	} 
	if ($type == 'text') {
?>
</ul>
<?php	}
?>
</div>
<?php
}

/* Database related functions
 *
 * These query the listings database (and the posts database)
 * and set up the $listings global so it can be used by the
 * template tags.
 *
 * You must set up the listings before using the template tags
 *
 */


# Template tag - get_pages_with_active_listings()
# returns posts that have active (For Sale, For Rent) listings
function get_pages_with_active_listings($limit,$sort = "") {
	return get_pages_with_listings($limit,$sort,"active");
}
function get_pages_with_pending_listings($limit,$sort = "") {
	return get_pages_with_listings($limit,$sort,"pending");
}
function get_pages_with_sold_listings($limit,$sort) {
	return get_pages_with_listings($limit,$sort,"sold");
}

function get_pages_with_featured_listings($limit,$sort = "") {
	return get_pages_with_listings($limit,$sort,"featured");
}

// Generic function
function get_pages_with_listings($limit,$sort,$filter) {
	global $wpdb;

	// returns page data with listings; published pages only
	//
	// options:
	// limit = none (default), or numeric number of listings eg: 1, 4, etc
	// sort = title (default), saledate (most recent first), highprice, lowprice, random, listdate (most recent first)
	// filter = none (default), active, pending, sold, featured
	

	// determine how to filter
	// cant specify a table - TODO fix
	switch ($filter) {
	case 'active' :
		$filterclause = 
    			"AND (status = " . RE_FORSALE . 
       			" OR status = " . RE_FORRENT . " ) ";
		break;
	case 'pending' :
		$filterclause = 
    			"AND (status = " . RE_PENDINGSALE . 
       			" OR status = " . RE_PENDINGLEASE . " ) ";
		break;
	case 'sold' :
		$filterclause = 
    			"AND (status = " . RE_SOLD . 
       			" OR status = " . RE_RENTED . " ) ";
		break;
	case 'featured' :
		$filterclause = "AND featured = 'featured'";
		break;
	case 'none' :
	default:
		$filterclause = "";
		break;
	}

	$do_rand_query = false;
	// determine how to sort
	switch ($sort) {
	case 'listdate': 
		// most recent (newest) listing first
		$sortclause = "ORDER BY listings.listdate DESC";
		break;
	case 'saledate': 
		// most recent sale first
		$sortclause = "ORDER BY listings.saledate DESC";
		break;
	case 'highprice': 
		// most expensive first
		$sortclause = "ORDER BY listings.listprice DESC";
		break;
	case 'lowprice': 
		$sortclause = "ORDER BY listings.listprice ASC";
		break;
	case 'random': 
		$sortclause = "ORDER BY RAND()"; 
		$do_rand_query = true; // this triggers special handling
		break;
	case 'title': 
	default:
		$sortclause = "ORDER BY wposts.post_title ASC";
		break;
	}

	// determine how to limit
	$numlimit = (int) $limit;
	$limitclause = "";
	if ($numlimit > 0) {
		$limitclause = "LIMIT $numlimit";
	}
	
	// set up the query
	
	// special handling for a random query - main reason for scaling
	// inspired from http://www.paperplanes.de/archives/2008/4/24/mysql_nonos_order_by_rand/
	$randquerystr = "
		SELECT wposts.*, listings.* 
		FROM (
			SELECT ml.pageid pid
			FROM $wpdb->posts wp, $wpdb->gre_listings ml 
			WHERE wp.post_status = 'publish'
			$filterclause
			AND wp.post_type = 'page'
			AND wp.ID = ml.pageid
    			ORDER BY RAND() $limitclause )  
		AS RANDOM_LISTINGS 
		JOIN $wpdb->posts wposts ON wposts.ID = RANDOM_LISTINGS.pid
		JOIN $wpdb->gre_listings listings ON listings.pageid = RANDOM_LISTINGS.pid
	";
	// NOTE: on WINDOWS the RANDOM_LISTINGS result may be cached so
	// subsequent calls result in the same list; if you notice this,
	// and a preformance hit doesn't matter, set 
	//$do_rand_query = false; // all the time

	$querystr = "
    SELECT wposts.*, listings.*
    FROM $wpdb->posts wposts, $wpdb->gre_listings listings
    WHERE wposts.ID = listings.pageid 
    $filterclause
    AND wposts.post_status = 'publish' 
    AND wposts.post_type = 'page' 
    $sortclause $limitclause
 ";

	if ($do_rand_query) {
		$pageposts = $wpdb->get_results($randquerystr, OBJECT);
	} else {
		$pageposts = $wpdb->get_results($querystr, OBJECT);
	}
	return $pageposts;
}


// call this inside the loop to setup $listings with associated data
function getandsetup_listingdata() {
	global $wpdb;
	global $post;
	$postid = $post->ID;
	if (!$postid) return;
	// Note this only sets up the single listing

	$querystr = "
    SELECT *
    FROM $wpdb->gre_listings 
    WHERE pageid = '$postid'
    LIMIT 1
 ";

	$listingrow = $wpdb->get_row($querystr, OBJECT);
	setup_listingdata($listingrow);
}

// use this only for complex queries that return a resultset, 
// the resultset MUST include fields from the listing table
// it's not necessary to include all the fields, but advisable...
// if you only use a subset, you must include pageid
//
// pass it one row at a time
function setup_listingdata($row) {
	if (!$row) return;
	if (!$row->pageid) return; // not a listing row

	global $listing;
	global $listing_cols;
	// take a query row and save it as a global array we can reference
	foreach ($row as $key => $value) {
		// only use valid row names
		if (in_array($key,$listing_cols)) {
			$listing->$key = $value;
		}
	}
}



?>
