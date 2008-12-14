<?php
/**
 *  CUSTOM   ----   RSS2 Feed Template for Google Maps XML / Active Listings
 *
 * @package WordPress
 *
 * feel free to customize, but check the Gogle Maps API if
 * you intend to make any XML structure changes!
 * http://code.google.com/
 *
 * NOTE: this feed only contains AVAILABLE properties
 */

header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

$siteurl = get_option('siteurl');

// escape XML strings
function xmlescape($text) {
	// google's recommended parsing
	$xmlStr = str_replace('<','&lt;',$text); 
	$xmlStr = str_replace('>','&gt;',$xmlStr); 
	$xmlStr = str_replace('"','&quot;',$xmlStr); 
	$xmlStr = str_replace("'",'&#39;',$xmlStr); 
	$xmlStr = str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

$markertype = array (  	__("For Sale",'greatrealestate') => 'forsale',
	__("For Rent",'greatrealestate') => 'forrent',
	__("Sale Pending",'greatrealestate') => 'forsale',
	__("Lease Pending",'greatrealestate') => 'forrent',
	__("Sold",'greatrealestate') => 'sold',
	__("Rented",'greatrealestate') => 'sold',
);


if (!function_exists('get_listing_listprice')) {
	// Great Real Estate plugin is disabled
	return;
}
?>
<?php   

$pageposts = get_pages_with_listings('','listdate','all');


?>
<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<markers>
<?php
if ($pageposts) {
	foreach ($pageposts as $post) {
    		setup_postdata($post); 
		setup_listingdata($post);
		//$title = get_the_title();
		$title = get_listing_status();
	        if (get_listing_hasclosed()) {
			$title .= ' ' . get_listing_saleprice();
		} else {
			$title .= ' ' . get_listing_listprice();
		}
 		$address = get_listing_address();
 		$city = get_listing_city();
		$state = get_listing_state();
		$addr = $address . ", " . $city . ", " . $state;
		$status = get_listing_status();
		$type = $markertype[$status];
		$lat = get_listing_latitude();
		$long = get_listing_longitude();
		if ($lat && $long && $status) { 
			echo "<marker ";
			echo 'name="' . xmlescape($title) . '" ';
			echo 'address="' . xmlescape($addr) . '" ';
			echo 'lat="' . $lat . '" ';
			echo 'lng="' . $long . '" ';
			echo 'ref="' . xmlescape(get_permalink()) . '" ';
			echo 'type="' . $type . '">';
			echo '<info><![CDATA[<div class="prop-thumb">' . get_listing_thumbnail() . '</div>]]></info>';
			echo "</marker>\n";
		}
	}
}
?>
</markers>
