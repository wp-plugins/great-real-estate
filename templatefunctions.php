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

/*
 * Changelog:
 * [2008-08-02] added filters to get_pages_with_listings to retrieve only homes for sale (allsales) or for rent (allrentals)
 * [2008-08-02] added functions get_listing_acsf_noformat(), get_listing_totsf_noformat(), get_listing_acres_noformat which return just a number without formatting
 * [2008-07-27] added quote escaping in google map popup the_listing_js_mapinfo() to avoid JavaScript errors
 * [2008-06-27] added map option to show_listings_featured()
 * [2008-06-27] changed map_canvas id to gre_map_canvas to reduce conflicts
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
    
    if ( isset( $listing->listdate ) )
    	return mdy_dateformat($listing->listdate);

    return '';
}
function the_listing_listdate() {
	// returns in preferred format for end-user displays
	global $listing;
	echo mywp_dateformat($listing->listdate);
}
function get_listing_saledate() {
	// returns in mm/dd/yyyy format for use with input forms
	global $listing;

    if ( isset( $listing->saledate ) )
    	return mdy_dateformat($listing->saledate);

    return '';
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

	echo isset( $re_status[ $listing->status ] ) ? $re_status[ $listing->status ] : '';
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

/**
 * Return listing's latitude.
 *
 * If the user entered a latitude manually, the function returns
 * that value. If not, the plugin will use the geocode that Google
 * Maps API generated based on the information in the Address,
 * City, State and ZIP code fields.
 */
function get_listing_latitude() {
	global $listing;

	if ( isset( $listing->latitude ) && ! empty( $listing->latitude ) ) {
		return $listing->latitude;
	}

	$location = get_post_meta( $listing->pageid, '_gre[google-maps][geolocation]', true );

	if ( is_object( $location ) && isset( $location->lat ) ) {
		return $location->lat;
	}

	return false;
}

/**
 * Return listing's longitude.
 *
 * If the user entered a latitude manually, the function returns
 * that value. If not, the plugin will use the geocode that Google
 * Maps API generated based on the information in the Address,
 * City, State and ZIP code fields.
 */
function get_listing_longitude() {
	global $listing;

	if ( isset( $listing->longitude ) && ! empty( $listing->longitude ) ) {
		return $listing->longitude;
	}

	$location = get_post_meta( $listing->pageid, '_gre[google-maps][geolocation]', true );

	if ( is_object( $location ) && isset( $location->lng ) ) {
		return $location->lng;
	}

	return false;
}

# Note: these interface functions can be utilized in your themes 
# if you decide to call a plugin function
# just give it the id from one of these functions

# Gallery - uses nggallery
function get_listing_galleryid() {
	global $listing;
	return $listing->galleryid;
}

# Downloads - uses WP Download Manager
# returns a comma separated list
/**
 * @deprecated since 1.5. No alternative for now. This is a compatibility function.
 */
function get_listing_downloadid() {
    global $listing;

    $listing_page_id = $listing->pageid;

    if ( ! $listing_page_id )
        return '';

    $res = '';

    foreach ( gre_get_listing_downloads( $listing_page_id ) as $d ) {
        $res .= $listing_page_id . '|' . $d->index . ',';
    }

    if ( $res )
        $res = substr( $res, 0, -1 );

    return $res;
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
    return $listing->acsf ? number_format( doubleval( $listing->acsf ) ) : '';
}
function get_listing_acsf_noformat() {
	global $listing;
	return $listing->acsf;
}
function the_listing_acsf() {
	global $listing;
	echo empty( $listing->acsf ) ? '' : number_format( $listing->acsf );
}
function get_listing_totsf() {
	global $listing;
	return $listing->totsf ? number_format( doubleval( $listing->totsf ) ) : '';
}
function get_listing_totsf_noformat() {
	global $listing;
	return $listing->totsf;
}
function the_listing_totsf() {
	global $listing;
	echo number_format($listing->totsf);
}
function get_listing_acres() {
	global $listing;
	return $listing->acres ? number_format( doubleval( $listing->acres ), 2 ) : '';
}
function get_listing_acres_noformat() {
	global $listing;
	return $listing->acres;
}
function the_listing_acres() {
	global $listing;
	echo empty( $listing->acres ) ? '' : number_format( $listing->acres, 2 );
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
<script type="text/javascript">
jQuery(function($) {
    var $link = $( '#listing-container #photogallery .slideshowlink a' );
    $link.attr( 'href', $link.attr( 'href' ) + '#photogallery' );
});
</script>
<?php
}

// VIDEOS
function the_listing_video_tab($before='<li>',$after='</li>') {
	return '';
}
function the_listing_video_content() {
	return '';
}

// PANORAMAS
function the_listing_panorama_tab($before='<li>',$after='</li>') {
	if ( ! defined( 'PP_APP_NAME' ) ) {
		return;
	}

	if (!get_listing_panoid()) return;

	echo $before;
	echo '<a href="#panoramas" title="'.__("360&deg; Panoramas","greatrealestate") . '" ><span>';
	_e("Panoramas","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_panorama_content() {
	if ( ! defined( 'PP_APP_NAME' ) ) {
		return;
	}

	if (!($panoids = get_listing_panoid())) return;

	$panorma_attachments_ids = explode( ',', $panoids );

	$output = '<div id="panoramas"><h2><title><span id="vr_label"></span></h2><panoramas></div>';
	$output = str_replace( '<title>', __( 'Panoramas', 'greatrealestate' ), $output );
	$output = str_replace( '<panoramas>', gre_render_qtvr_panoramas( $panorma_attachments_ids ), $output );

	echo $output;
}

function gre_render_qtvr_panoramas( $attachments_ids ) {
	$shortcodes = array();

	foreach ( $attachments_ids as $attachment_id ) {
		$attachment_url = wp_get_attachment_url( $attachment_id );
		$shortcodes[] = sprintf( '[pano file="%s"]', $attachment_url );
	}

	return do_shortcode( implode( '', $shortcodes ) );
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
	if (!get_listing_longitude()) return;
	if (!get_listing_latitude()) return;

	echo $before;
	echo '<a href="#map"  title="'.__("Location Map","greatrealestate") . '" ><span>';
	_e("Map","greatrealestate");
	echo '</span></a>';
	echo $after;
}
function the_listing_map_content() {
	if ( ! get_listing_longitude() || ! get_listing_latitude() ) {
		return;
	}

	// See the jQuery code in the main great-real-estate.php file
	// for how to make GMaps work nice with tabbed panels
	//
	// TODO - only one function call and a var definition, put the
	//        rest in the header for cleaner page
	wp_localize_script( 'gre-maps', 'gre_listing_map_info', array(
		'latitude' => floatval( get_listing_latitude() ),
		'longitude' => floatval( get_listing_longitude() ),
		'info_window_content' => gre_render_listing_map_popup(),
	) );

	$output = '<div id="map"><h2><tab-title></h2><div><div id="gre_map_canvas" class="gre-google-map"></div></div></div>';
	$output = str_replace( '<tab-title>', __( 'Location Map', 'greatrealestate' ), $output );

	echo $output;
}

/**
 * @since 1.5
 */
function gre_render_listing_map_popup() {
    if ( get_listing_hasclosed() ) {
        $listing_price = get_listing_saleprice();
    } else {
        $listing_price = get_listing_listprice();
    }

    $params = array(
        'listing_price' => $listing_price,
    );

    $template = GRE_FOLDER . 'core/templates/listing-map-popup.tpl.php';

    return gre_render_template( $template, $params );
}

// The HTML popup for our listing on gMaps
function the_listing_js_mapinfo() {
	// stick in JS, do not add linefeeds, no single quotes!
	// escape </ to <\/
	echo '<div id="gmap-info">' . 
		'<h3>' . addslashes(get_listing_blurb()) . '<\/h3>' .
		addslashes(get_listing_thumbnail());
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
// v1.1 - added map type, rewrote to support shortcodes (added get_)
function show_listings_featured($maxlistings = '',$sort = 'random',$type = 'basic',$heading = 'Featured Listings') {
	echo get_listings_featured($maxlistings,$sort,$type,$heading);
}

function get_listings_featured($maxlistings = '',$sort = 'random',$type = 'basic',$heading = 'Featured Listings') {
	$maxtoshow = (int) $maxlistings;
	if ((!$maxlistings) || $maxlistings == '' || ($maxtoshow <= 0)) {
		// use default setting
		$maxtoshow = (int) get_option('greatrealestate_maxfeatured');
	}
	global $wpdb;

	// handle map type - does not use db call, uses XML feed
	if ( 'map' == $type ) {
		    $output = <<<ENDOFHTMLBLOCK
<div id="featuredlistings-map" class="featuredlistings-map">
<h2>$heading</h2>
<div id="gre_map_multi" class="gre-google-map"></div>
</div>
ENDOFHTMLBLOCK;
		return $output;
	}

 	$featured = get_pages_with_featured_listings($maxtoshow,$sort);
	if (! $featured) {
		return "<!-- no featured listings -->";
	}
	// definitely have something to show - spit it out
	$output = <<<ENDOFHTMLBLOCK
<div id="featuredlistings" class="featuredlistings-${type}">
<h2>$heading</h2>
ENDOFHTMLBLOCK;

	global $post;
	$oldpost = $post; // save for after the loop - IMPORTANT

	if ( 'text' == $type ) {
		$output .= "<ul>";
	}
  	foreach ($featured as $post) {
		setup_postdata($post);
		setup_listingdata($post);
		switch ($type) {
		case 'text':
			$output .= '<li><a href="' . get_permalink() . '" ' .
			 'title="' . __('More about ','greatrealestate') .
			 get_the_title() . '">';
			$output .= get_the_title() . '</a>';
			$output .= "<br />";
			$output .= get_listing_status() . " " .
			 get_listing_listprice() . '</li>';
			break;
		case 'basic' :
		default :
			$output .= '<div class="prop-box-featured">';
			$output .= '<div class="prop-thumb">';
			$output .= get_listing_thumbnail();
			$output .= "</div>";
			$output .= '<h3><a href="' . get_permalink() .
			 '" title="' . __('More about','greatrealestate') .
			 get_the_title() . '">' . get_the_title() .
			 '</a></h3>';
			$output .= '<p><em>' . get_listing_blurb() .
				'</em></p>';
			$output .= '<p>' . get_listing_status() .
			       ' ' . get_listing_listprice() . '</p>';
			$output .= '</div>';
			break;
		}
	} 
	if ( 'text' == $type ) {
		$output .= "</ul>";
	}
	$output .= "</div>";

	$post = $oldpost; // restore saved post - IMPORTANT!
	return $output;
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
	$filterclause = gre_build_listings_query_conditions( $filter );

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
		SELECT wposts.*, listings.*, wposts.post_content AS content
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
    SELECT wposts.*, listings.*, wposts.post_content AS content
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

/**
 * @since 1.5
 */
function gre_get_random_listings( $count, $filter, $orderby ) {
    global $wpdb;

    $conditions = gre_build_listings_query_conditions( $filter );
    $orderby = gre_build_listings_query_order_clause( $orderby );

    $sql = "SELECT posts.*, listings.*, posts.post_content AS content
            FROM (
                SELECT l.*
                FROM {$wpdb->posts} AS p
                INNER JOIN {$wpdb->gre_listings} AS l
                ON ( p.ID = l.pageid )
                WHERE p.post_status = 'publish' AND p.post_type = 'page'
                $conditions
                ORDER BY RAND() LIMIT $count
            ) AS listings
            INNER JOIN {$wpdb->posts} AS posts
            ON ( listings.pageid = posts.ID )
            $orderby";

    return $wpdb->get_results( $sql );
}

/**
 * @since 1.5
 * @access private
 */
function gre_build_listings_query_conditions( $filter ) {
    switch ( $filter ) {
    	case 'for-sale':
    		$clause = 'AND status = ' . RE_FORSALE . ' ';
    		break;
    	case 'for-rent':
    		$clause = 'AND status = ' . RE_FORRENT . ' ';
    		break;
        case 'allrentals' :
            $clause =
                    "AND (status = " . RE_FORRENT .
                    " OR status = " . RE_PENDINGLEASE .
                    " OR status = " . RE_RENTED . " ) ";
            break;
        case 'allsales' :
            $clause =
                    "AND (status = " . RE_FORSALE .
                    " OR status = " . RE_PENDINGSALE .
                    " OR status = " . RE_SOLD . " ) ";
            break;
        case 'active' :
            $clause =
                    "AND (status = " . RE_FORSALE .
                    " OR status = " . RE_FORRENT . " ) ";
            break;
        case 'pending' :
            $clause =
                    "AND (status = " . RE_PENDINGSALE .
                    " OR status = " . RE_PENDINGLEASE . " ) ";
            break;
        case 'sold' :
            $clause =
                    "AND (status = " . RE_SOLD .
                    " OR status = " . RE_RENTED . " ) ";
            break;
        case 'featured' :
            $clause = "AND featured = 'featured'";
            break;
        case 'none' :
        default:
            $clause = "";
            break;
    }

    return $clause;
}

/**
 * @since 1.5
 * @access private
 */
function gre_build_listings_query_order_clause( $orderby ) {
    switch ( $orderby ) {
        case 'listdate':
            // most recent (newest) listing first
            $clause = "ORDER BY listdate DESC";
            break;
        case 'saledate':
            // most recent sale first
            $clause = "ORDER BY saledate DESC";
            break;
        case 'highprice':
            // most expensive first
            $clause = "ORDER BY listprice DESC";
            break;
        case 'lowprice':
            $clause = "ORDER BY listprice ASC";
            break;
        case 'random':
            $clause = "ORDER BY RAND()";
            break;
        case 'title':
        default:
            $clause = "ORDER BY post_title ASC";
            break;
    }

    return $clause;
}

/**
 * @since 1.5
 * @access public
 */
function gre_count_listings( $params ) {
    global $wpdb;

    $params = gre_parse_listings_query_params( $params );
    $conditions = gre_build_listings_query_conditions( $params['filter'] );
    $post_status = gre_build_listings_query_post_status_condition( $params['post_status'] );

    $sql = "SELECT COUNT( DISTINCT posts.ID )
            FROM {$wpdb->posts} AS posts
            INNER JOIN {$wpdb->gre_listings} AS listings
            ON ( listings.pageid = posts.ID )
            WHERE $post_status AND posts.post_type = 'page'
            $conditions";

    return intval( $wpdb->get_var( $sql ) );
}

/**
 * @since 1.5
 * @access private
 */
function gre_parse_listings_query_params( $params ) {
    return wp_parse_args( $params, array(
        'post_status' => array( 'publish' ),
        'filter' => null,
        'orderby' => null,
        'limit' => null,
        'offset' => null,
    ) );
}

/**
 * @since 1.5
 * @access private
 */
function gre_build_listings_query_post_status_condition( $post_status ) {
    if ( empty( $post_status ) ) {
        $condition = '1 = 1';
    } else {
        $condition = "posts.post_status IN ('" . implode( "','", $post_status ) . "')";
    }

    return $condition;
}

/**
 * @since 1.5
 * @access public
 */
function gre_get_listings( $params ) {
    global $wpdb;

    $params = gre_parse_listings_query_params( $params );
    $conditions = gre_build_listings_query_conditions( $params['filter'] );
    $orderby = gre_build_listings_query_order_clause( $params['orderby'] );
    $post_status = gre_build_listings_query_post_status_condition( $params['post_status'] );

    if ( ! empty( $params['limit'] ) && ! empty( $params['offset'] ) ) {
        $limit = "LIMIT {$params['offset']}, {$params['limit']}";
    } else if ( ! empty( $params['limit'] ) ) {
        $limit = "LIMIT {$params['limit']}";
    } else {
        $limit = '';
    }

    $sql = "SELECT posts.*, listings.*
            FROM {$wpdb->posts} AS posts
            INNER JOIN {$wpdb->gre_listings} AS listings
            ON ( listings.pageid = posts.ID )
            WHERE $post_status AND posts.post_type = 'page'
            $conditions
            $orderby
            $limit";

    return $wpdb->get_results( $sql );
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

    if ( ! is_object( $listing ) )
        $listing = new StdClass();

	// take a query row and save it as a global array we can reference
	foreach ($row as $key => $value) {
		// only use valid row names
		if (in_array($key,$listing_cols)) {
			$listing->$key = $value;
		}
	}
}

function gre_get_listing_field( $listing_id, $name, $default = null ) {
    $value = get_post_meta( $listing_id, "_gre[$name]", true );
    return empty( $value ) ? $default : $value;
}

/**
 * @since next-release
 */
function gre_get_queried_objects() {
    return gre_query()->get_queried_objects();
}

/**
 * @since next-release
 */
function gre_set_queried_objects( $objects ) {
	return gre_query()->set_queried_objects( $objects );
}

/**
 * @since next-release
 */
function gre_get_return_url() {
    return gre_query()->get_var( 'return_url' );
}

/**
 * @since next-release
 */
function gre_set_view_title( $title ) {
	return gre_query()->set_var( 'return_url', $title );
}

/**
 * @since next-release
 */
function gre_get_view_title() {
	return gre_query()->get_var( 'title' );
}
