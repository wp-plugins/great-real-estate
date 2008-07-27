<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

##################################################################
#  More obscure stuff you probably won't need in any templates

# note these are technically internal, the numbers are stored and 
# named functions returning booleans are the intended interface,
# display code should decide what to use in output and not use these
# THESE WILL ONLY BE DISPLAYED ON THE ADMIN SCREEN

$listing_cols = array ( "pageid",
			"address",
			"city",
			"state",
			"postcode",
			"mlsid",
			"status",
			"blurb",
			"bedrooms",
			"bathrooms",
			"halfbaths",
			"garage",
			"acsf",
			"totsf",
			"acres",
			"featureid",
			"listprice",
			"saleprice",
			"listdate",
			"saledate",
			"galleryid",
			"videoid",
			"downloadid",
			"panoid",
			"latitude",
			"longitude",
			"featured",
			"agentid"
	);

define ("RE_SOLD", 3);
define ("RE_RENTED", 6);
define ("RE_FORSALE", 1);
define ("RE_FORRENT", 4);
define ("RE_PENDINGSALE", 2);
define ("RE_PENDINGLEASE", 5);

$re_status = array (RE_FORSALE => __("For Sale","greatrealestate"), 
			RE_PENDINGSALE => __("Sale Pending","greatrealestate"), 
			RE_SOLD => __("Sold","greatrealestate"), 
			RE_FORRENT => __("For Rent", "greatrealestate"),
			RE_PENDINGLEASE => __("Lease Pending", "greatrealestate"),
			RE_RENTED => __("Rented", "greatrealestate")
		);
define ("RE_FEAT_POOL", 1);
define ("RE_FEAT_WATER", 2);
define ("RE_FEAT_GOLF", 3);
define ("RE_FEAT_CONDO", 4);
define ("RE_FEAT_TH", 5);
$re_features = array (1 => __('Pool', "greatrealestate"), 
			2 => __('Waterfront', "greatrealestate"), 
			3 => __('Golf', "greatrealestate"), 
			4 => __('Condo', "greatrealestate"), 
			5 => __('Townhome', "greatrealestate")
		);

function mywp_dateformat($mysqldate) {
	if (!$mysqldate) return "";
       	if ($mysqldate == "0000-00-00") return "";
	$format = get_option('date_format');
	$format = ($format) ? $format : "F j, Y";
	return date($format,strtotime($mysqldate));
}
function mdy_dateformat($mysqldate) {
	if (!$mysqldate) return "";
       	if ($mysqldate == "0000-00-00") return "";
	return strftime("%m/%d/%Y",strtotime($mysqldate));
}
function to_mysqldate($mdydate) {
	if (!$mdydate) return "";
	return strftime("%Y-%m-%d",strtotime($mdydate));
}

# returns true if a feature exists
function get_listing_hasfeature($featureid) {
	global $listing;
	$allfeatures = explode(',',$listing->featureid);
	return in_array($featureid,$allfeatures,false);
}


#####################################################################
#  DEFAULT TEMPLATES
#
#  THESE ARE NOT MEANT TO BE CALLED FROM A TEMPLATE
#  See the docs

// This is called by an action hook just after the contents of the
// main Listings Page have been displayed
// It can be turned off via the Admin screens
function greatrealestate_defaultlistingindex() {
	global $post;
	global $listing;

	$listing->endloop = true;
?>
<!-- list of listings added by default -->
<?php
	$pageposts = get_pages_with_active_listings('','highprice');
?>

<?php 	if ($pageposts): ?>
<div id="activelistings">
<h2><?php _e('My Active Listings','greatrealestate'); ?></h2>
	<?php foreach ($pageposts as $post): ?>
		<?php setup_postdata($post); ?>
		<?php setup_listingdata($post); ?>
		<?php $line1 = ''; $line2 = ''; $line3 = ''; ?>

	<div class="prop-box-avail">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
	<h2><a href="<?php the_permalink() ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a></h2>
	<h3><?php the_listing_status(); ?>
		<?php if (get_listing_listprice()) { ?>
		<?php _e('- Offered at ','greatrealestate'); ?><?php the_listing_listprice(); } ?> 
	</h3>
      	<div>
 	<?php the_listing_blurb(); ?>
      	</div>
	<?php if ($bedrooms = get_listing_bedrooms()) 
		$line1 .= "<div>$bedrooms ". 
			__('Bedrooms','greatrealestate') . "</div>"; ?>
	<?php if ($bathrooms = get_listing_bathrooms()) {
		$line1 .= "<div>$bathrooms " . __('Full ','greatrealestate');
		if ($halfbaths = get_listing_halfbaths()) 
			$line1 .= "&amp; $halfbaths " . __('Half ','greatrealestate');
		$line1 .= __(' Baths','greatrealestate') . "</div>"; 
              }	?>
	<?php if (get_listing_garage()) 
		$line1 .= "<div>" . get_listing_garage() . __(' Garage Spaces','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_acsf()) 
		$line2 .= "<div>" . get_listing_acsf() . 
			__(' Sq/Ft Under Air','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_totsf()) $line2 .= "<div>" .get_listing_totsf(). 
		__(' Sq/Ft Total','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_acres()) $line2 .= "<div>" .get_listing_acres().
		__(' Acres','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_haspool()) $line3 .= "<div>" . __('Private Pool','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_haswater()) $line3 .= "<div>" . __('Waterfront','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_hasgolf()) $line3 .= "<div>" . __('On Golf Course','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_hascondo()) $line3 .= "<div>" . __('Condominium','greatrealestate') . "</div>"; ?>
	<?php if (get_listing_hastownhome()) $line3 .= "<div>" . __('Townhome','greatrealestate') . "</div>"; ?>
 	<?php if ($line1 || $line2 || $line3) { ?>
      <div class='propdata'>
	<?php if ($line1) echo "<div class='propdata-line'>$line1</div>"; ?>
	<?php if ($line2) echo "<div class='propdata-line'>$line2</div>"; ?>
	<?php if ($line3) echo "<div class='propdata-line propfeatures'>$line3</div>"; ?>
      </div>
	<?php } ?>
  
    	</div>
  <?php endforeach; ?>
  
  </div>
 <?php endif; ?>

<!-- list of pending sales -->
<?php   

 $pageposts = get_pages_with_pending_listings('','highprice');

?>

<?php if ($pageposts): ?>
  <div id="pendingsales">
  <h2><?php _e('Pending Sale','greatrealestate'); ?></h2>
  <?php foreach ($pageposts as $post): ?>
    <?php setup_postdata($post); ?>
    <?php setup_listingdata($post); ?>

    <div class="prop-float-container prop-box">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
	<h3><a href="<?php the_permalink() ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<p><span class="propispending"><?php the_listing_status(); ?></span>
		<?php if (get_listing_listprice()) { ?>
		Last offered at <?php the_listing_listprice(); ?> 
		<?php } ?>
	</p>
    </div> 
  <?php endforeach; ?>
  </div> 
 <?php endif; ?>


<!-- list of sold -->
<?php   

 $pageposts = get_pages_with_sold_listings('','saledate');

?>

<?php if ($pageposts): ?>
  <div id="soldlistings">
  <h2><?php _e('I Sold or Leased','greatrealestate'); ?></h2>
  <?php foreach ($pageposts as $post): ?>
    <?php setup_postdata($post); ?>
    <?php setup_listingdata($post); ?>

    <div class="prop-float-container prop-box">
	<div class="prop-thumb">
	<?php the_listing_thumbnail(); ?>
	</div>
	<h3><a href="<?php the_permalink() ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<p><span class="propwassold"><?php the_listing_status(); ?></span>
		<?php if (get_listing_saleprice()) the_listing_saleprice(); ?> 
		<?php if (get_listing_saledate()) { ?>
		<em><?php the_listing_saledate(); ?></em>
		<?php } ?>
	</p>
    </div>
  <?php endforeach; ?>
  </div>

 <?php endif; ?>
 <br clear="all" />

<?php

}


// THIS IS A FILTER! 
// It is called by the_content filter 
// when a listing page's content is being displayed
// It can be turned off via the Admin screens
function greatrealestate_defaultlistingcontent($content) {
	global $post;
	global $listing;
	
	// only filter if this is a single page
	if (! is_page()) return $content;

	// if do not filter flag set, just pass it on
	// IMPORTANT otherwise it loops foreeeeeeevvvvvveeeeeerrrrrrr
	if ( ! (strpos($content, "grenofilters") === FALSE) ) 
		return $content;

	// this is the "top of page" case
	// combine the box of data and the filtered "beforemore"
	$content = greatrealestate_listingdatabox() . 
			get_listing_description_beforemore(); 

	return $content;

}

// added to the end of a listing page, after the content... tabbed interface
function greatrealestate_defaultlistingdetails() {
	global $post;
	global $listing;
	getandsetup_listingdata(); 
	// this all echos
?>
<div id="listing-container">
	<ul id="tabnav">
	<?php the_listing_map_tab(); // recommend this be first ?>
	<?php the_listing_description_tab(); ?>
	<?php the_listing_gallery_tab(); ?>
	<?php the_listing_video_tab(); ?>
	<?php the_listing_panorama_tab(); ?>
	<?php the_listing_downloads_tab(); ?>
	<?php the_listing_community_tab(); ?>
	</ul>
	<?php the_listing_map_content(); // recommend this be first ?>
	<?php the_listing_description_content(); ?>
	<?php the_listing_gallery_content(); ?>
	<?php the_listing_video_content(); ?>
	<?php the_listing_panorama_content(); ?>
	<?php the_listing_downloads_content(); ?>
	<?php the_listing_community_content(); ?>
</div>
<?php

}

function greatrealestate_listingdatabox() {
	global $post;
	global $listing;
	getandsetup_listingdata(); 

	$output = '<div class="page-propdata-box">';

 	$line1 = ''; $line2 = ''; $line3 = ''; 

	$output .= '<div class="page-blurb">' . get_listing_blurb() . '</div>';
	if ($bedrooms = get_listing_bedrooms()) 
		$line1 .= "<div>$bedrooms " . __('Bedrooms','greatrealestate') . "</div>"; 
	if ($bathrooms = get_listing_bathrooms()) {
		$line1 .= "<div>$bathrooms " . __('Full','greatrealestate') . " ";
		if ($halfbaths = get_listing_halfbaths()) 
			$line1 .= "&amp; $halfbaths " . __('Half','greatrealestate') . " ";
		$line1 .= " " . __('Baths','greatrealestate') . "</div>"; 
	}
	if ($garage = get_listing_garage()) 
		$line1 .= "<div>$garage " . __('Garage Spaces','greatrealestate') . "</div>"; 
	if ($acsf = get_listing_acsf()) 
		$line2 .= "<div>$acsf " . __('Sq/Ft Under Air','greatrealestate') . "</div>"; 
	if ($totsf = get_listing_totsf()) 
		$line2 .= "<div>$totsf " . __('Sq/Ft Total','greatrealestate') . "</div>"; 
	$acres = get_listing_acres();
	if ($acres > 0) 
		$line2 .= "<div>$acres " . __('Acres','greatrealestate') . "</div>"; 
	if (get_listing_haspool()) $line3 .= "<div>" . __('Private Pool','greatrealestate') . "</div>"; 
	if (get_listing_haswater()) $line3 .= "<div>" . __('Waterfront','greatrealestate') . "</div>"; 
	if (get_listing_hasgolf()) $line3 .= "<div>" . __('On Golf Course','greatrealestate') . "</div>"; 
	if (get_listing_hascondo()) $line3 .= "<div>" . __('Condominium','greatrealestate') . "</div>"; 
	if (get_listing_hastownhome()) $line3 .= "<div>" . __('Townhome','greatrealestate') . "</div>"; 

	# sanity check time
 	if ( ! $line1 && ! $line2 && ! $line3 && ! $propstatus) return "";

      	$output .= "<div class='propdata'>";
	if ($line1) $output .= "<div class='propdata-line'>$line1</div>";
	if ($line2) $output .= "<div class='propdata-line'>$line2</div>";
	if ($line3) $output .= "<div class='propdata-line propfeatures'>$line3</div>";

	$output .= '<h3>' . get_listing_status() . ' ';
	if (get_listing_hasclosed()) { 
		$output .= mywp_dateformat($listing->saledate) . 
			__(' for ','greatrealestate') . 
			get_listing_saleprice(). 
			__(' - last offered','greatrealestate');
	} else { 
		$output .= __('- Offered','greatrealestate');
	}
	$output .= __(' at ','greatrealestate') . 
		get_listing_listprice() . '</h3>';
	$output .= "</div></div>";

	return $output;

}

?>
