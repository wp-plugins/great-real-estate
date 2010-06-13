<?php
// Display the content for the listings sub-panel
	# show the listings in a table
	# indicate the type of content associated
	# link to the plugins to edit
	# link to the page editor
	# enable/disable "Featured"
	// include listings_admin_page.php



function greatrealestate_admin_listings() {
?>
	<div class="wrap">
	<h2><?php _e('Manage Listings','greatrealestate'); ?></h2>
	<ul class="subsubsub"><li><?php _e('All Listings','greatrealestate'); ?></li></ul>
	<table class="widefat">
	<thead>
	<tr>
	<th scope="col"><?php _e('Featured','greatrealestate'); ?></th>
	<th scope="col"><?php _e('Thumbnail','greatrealestate'); ?></th>
	<th scope="col"><?php _e('Title','greatrealestate'); ?></th>
	<th scope="col"><?php _e('Status','greatrealestate'); ?></th>
	<th scope="col"><?php _e('Listed','greatrealestate'); ?></th>
	<th scope="col"><?php _e('Sold','greatrealestate'); ?></th>
	</tr>
	</thead>
	<tbody>
<?php
	$all = get_any_pages_with_listings();
	foreach ($all as $post) {
		setup_listingdata($post);
		echo "<tr><td>";
		echo ($post->featured == "featured") ? '<span class="is-featured">' . __('Featured','greatrealestate') . '</span><br />' : "";
		echo ($post->post_status == "publish") ? __("Published",'greatrealestate') : __("Unpublished",'greatrealestate') ;
		echo "<br />";
		echo '<a href="' . get_permalink($post) . '">' . __('View','greatrealestate') . '</a>';
		echo "<br />";
		echo '<a href="' . get_option('wpurl') . "post.php?action=edit&amp;post=" . $post->ID . '">' . __('Edit','greatrealestate') . '</a>';
	      	echo "</td>";
		echo "<td>";
		if ($post->galleryid) {
			the_listing_thumbnail();
		} else {
			# TODO - link to "No Picture" gif
			echo "<strong>" . __('Create a<br /> Gallery','greatrealestate') . "</strong>";
		}
		echo "</td>";
		echo "<td>";
		echo $post->post_title;
		echo "</td><td>";
		the_listing_status();
		echo "</td><td>";
		the_listing_listdate();
		echo "<br />";
		the_listing_listprice();
		echo "</td><td>";
		the_listing_saledate();
		echo "<br />";
		the_listing_saleprice();
		echo "</td>";
		# TODO - show icons for extras - map gallery video pano download
		echo "</tr>\n";
	}
?>
	</tbody>
	</table>

	<ul class="subsubsub">
	<li><a href="<?php get_option('wpurl'); ?>post-new.php?post_type=page"><?php _e('Add a listing','greatrealestate'); ?></a></li>
	</ul>
	</div>
<?php
}


function get_any_pages_with_listings() {
	global $wpdb;
	// NOTE: ADMIN USE! INCLUDES DRAFT PAGES
	// Default available, then pending, then sold
	// Includes DRAFT at the end

	$querystr = "
    SELECT wposts.*, listings.*
    FROM $wpdb->posts wposts, $wpdb->gre_listings listings
    WHERE wposts.ID = listings.pageid 
    AND wposts.post_type = 'page' 
    ORDER BY FIELD(wposts.post_status,'publish','draft'),FIELD(listings.status,1,4,2,5,3,6),wposts.post_title ASC
 ";

	$pageposts = $wpdb->get_results($querystr, OBJECT);
	return $pageposts;
}


?>
