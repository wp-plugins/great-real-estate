<?php
// Display the content for the listings sub-panel
	# show the listings in a table
	# indicate the type of content associated
	# link to the plugins to edit
	# link to the page editor
	# enable/disable "Featured"
	// include listings_admin_page.php



function greatrealestate_admin_listings() {
	$add_new_listing_url = admin_url( 'post-new.php?post_type=page&gre=1' );

	$table = gre_listings_table();
	$table->prepare_items();

	$params = array(
		'table' => $table,
		'add_new_listing_url' => $add_new_listing_url,
	);

	echo gre_render_template( GRE_FOLDER . 'admin/templates/manage-listings-admin-page.tpl.php', $params );
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
    ORDER BY wposts.post_date DESC, wposts.post_title ASC
 ";

	$pageposts = $wpdb->get_results($querystr, OBJECT);
	return $pageposts;
}


?>
