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

include_once (dirname (__FILE__) . '/settings.php');

// Admin menu structure
add_action('admin_menu', 'greatrealestate_add_admin_menu');

function greatrealestate_add_admin_menu() {
	// Add a new top-level menu for all Real Estate stuff
	add_menu_page(
        __( 'Great Real Estate', 'greatrealestate' ),
        __( 'Great Real Estate', 'greatrealestate' ),
        'edit_pages',
        GRE_FOLDER,
        'gre_show_menu',
        'dashicons-admin-home',
        30
    );

    if (current_user_can('manage_options')) {
        // Add settings page
        add_submenu_page(GRE_FOLDER, 'Settings', 'Settings', 'manage_options', 'greatrealestate-options', 'gre_show_menu');
    }

	// Add a sublevel page to that
	add_submenu_page(GRE_FOLDER, 'Listings','Listings', 'edit_pages', 'greatrealestate-listings', 'gre_show_menu');
	add_submenu_page(GRE_FOLDER, 'About', 'About', 'edit_pages', 'greatrealestate-about', 'gre_show_menu');
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
	# [2008-07-22] added logic to handle case where post has no parent
	#              and option has not been set
	if ( ( isset( $_GET['gre'] ) && '1' == $_GET['gre'] ) || ( $post->post_parent && (get_option('greatrealestate_pageforlistings') == $post->post_parent ) ) ) {
		# get the listing data associated with the post/page
		$listing = $wpdb->get_row("SELECT * FROM $wpdb->gre_listings WHERE pageid = '$post_ID'");

        if ( ! $listing ) {
            $listing = (object) array( 'pageid' => $post_ID );
        }

		include (dirname (__FILE__).'/editpage.php');
	}
}
add_action('edit_page_form', 'greatrealestate_add_edit', 90);

# corresponding header content for page edit (page.php)
function greatrealestate_add_edit_js() {
	global $post;

	// We're trying not to toss this in every dang admin page
	global $post;
	# [2008-07-22] added logic to handle case where post has no parent
	#              and option has not been set
	if ( ( isset( $_GET['gre'] ) && 1 == $_GET['gre'] ) || ( $post && $post->post_parent && ( gre_get_option( 'pageforlistings' ) == $post->post_parent ) ) ) {
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

    // Select the post parent by default.
    jQuery( '#parent_id' ).val( '<?php echo gre_get_option( 'pageforlistings' ); ?>' );
} );
/* ]]> */
</script>
<!-- end added by great-real-estate -->
<?php
	}
}
add_action('admin_print_scripts', 'greatrealestate_add_edit_js', 90);

function greatrealestate_add_savepage($postID) {
    global $wpdb;

    if ( empty( $postID ) ) {
        return;
    }

    if ( wp_is_post_revision( $postID ) ) {
        return;
    }

    $listings_parent_page_id = get_option( 'greatrealestate_pageforlistings' );

    if ( empty( $listings_parent_page_id ) ) {
        return;
    }

    $listing_parent = $wpdb->get_var( $wpdb->prepare( "SELECT post_parent from $wpdb->posts WHERE ID = %d LIMIT 1", $postID ) );

    if ( $listings_parent_page_id != $listing_parent ) {
        return;
    }

    $listing_data_id = $wpdb->get_var( $wpdb->prepare( "SELECT id from $wpdb->gre_listings WHERE pageid = %d  LIMIT 1", $postID ) );

    $listing = new stdClass();

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

    if ( isset( $_POST['listing_property_type'] ) ) {
        update_post_meta( $postID, '_gre[property-type]', sanitize_text_field( $_POST['listing_property_type'] ) );
    }

	if ($listing_data_id) {
		# update existing record
		# safer to reference the index on update
		$sql = "UPDATE $wpdb->gre_listings SET ";
		foreach ($listing as $lkey => $lvalue) {
			$sql .= "$lkey = '$lvalue', ";
		}
		$sql = substr($sql,0,-2); // remove last comma
		$sql .= " WHERE id = '$listing_data_id'";
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

function gre_create_main_page() {
    if ( ! current_user_can( 'administrator' ) || ! isset( $_GET['page'] ) || 'great-real-estate' != $_GET['page'] )
        return;

    if ( ! isset( $_GET['action'] ) || 'create-main-page' != $_GET['action'] )
        return;

    $page_id = gre_get_option( 'pageforlistings' );
    if ( $page_id && 'page' == get_post_type( $page_id ) && 'publish' == get_post_status( $page_id ) )
        return;

    $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';

    if ( ! wp_verify_nonce( $nonce, 'create main page' ) )
        return;

    $page_id = wp_insert_post( array( 'post_title' => __( 'Listings', 'greatrealestate' ),
                                      'post_content' => '',
                                      'post_type' => 'page',
                                      'post_status' => 'publish' ) );
    if ( ! $page_id )
        return;

    update_option( 'greatrealestate_pageforlistings', $page_id );
}
add_action( 'admin_init', 'gre_create_main_page' );

function gres_admin_notices() {
    if ( ! current_user_can( 'administrator' ) || ! isset( $_GET['page'] ) || 'great-real-estate' != $_GET['page'] )
        return;

    // Do not show this warning while we are creating the main page.
    if ( isset( $_GET['action'] ) && 'create-main-page' == $_GET['action'] )
        return;

    $page_id = gre_get_option( 'pageforlistings' );

    if ( $page_id && 'page' == get_post_type( $page_id ) && 'publish' == get_post_status( $page_id ) )
        return;

    echo '<div id="gre-main-page-warning" class="error"><p>';
    echo __( 'For <b>Great Real Estate</b> to work a main listings page needs to be configured.', 'greatrealestate' );
    echo '<br />';
    echo __( 'You can create and configure this page by yourself or let Great Real Estate do this for you automatically.', 'greatrealestate' );
    echo '<br /><br />';
    echo '<a href="' . admin_url( 'admin.php?page=greatrealestate-options' ) . '" class="button">' . __( 'Configure main page', 'greatrealestate' ) . '</a>';
    echo '<a href="' . add_query_arg( array( 'action' => 'create-main-page', '_wpnonce' => wp_create_nonce( 'create main page' ) ) ) . '" class="button button-primary">' . __( 'Create required pages for me', 'greatrealestate' ) . '</a>';
    echo '</p></div>';
}
add_action( 'admin_notices', 'gres_admin_notices' );


function gre_admin_enqueue_scripts() {
    wp_register_script( 'gre-admin-js', GRE_URL . 'admin/js/admin.js' );
    wp_enqueue_script( 'gre-admin-js' );

    wp_register_style( 'gre-admin-css', GRE_URL . 'admin/css/admin.css' );
    wp_enqueue_style( 'gre-admin-css' );

        wp_register_script( 'jquery-fileupload-iframe-transport',
                            GRE_URL . 'vendors/jQuery-File-Upload-9.5.7/js/jquery.iframe-transport.min.js' );
        wp_register_script( 'jquery-fileupload',
                            GRE_URL . 'vendors/jQuery-File-Upload-9.5.7/js/jquery.fileupload.min.js',
                            array( 'jquery',
                                   'jquery-ui-widget',
                                   'jquery-fileupload-iframe-transport' ) );

        wp_enqueue_script( 'jquery-fileupload' );
}
add_action( 'admin_enqueue_scripts', 'gre_admin_enqueue_scripts' );

function gre_admin_listing_file_upload() {
    $listing = absint( $_REQUEST['listing_id'] );
    $description = trim( $_REQUEST['description'] );
    $file = $_FILES['file'];

    // TODO: add validation.
    $index = gre_listing_download_add( $listing, $file, $description );

    if ( $index ) {
        $download = gre_get_listing_download( $listing, $index );

        $response = array();
        $response['ok'] = true;
        $response['download'] = $download;
        $response['html'] = gre_render( GRE_FOLDER . 'admin/templates/downloads-item.tpl.php', array( 'download' => $download ) );

        echo json_encode( $response );
    }

    exit();
}
add_action( 'wp_ajax_gre-listing-file-upload', 'gre_admin_listing_file_upload' );

function gre_admin_listing_file_delete() {
    $listing = absint( $_POST['listing'] );
    $index = absint( $_POST['index'] );

    if ( ! $listing || ! $index )
        return;

    gre_listing_download_remove( $listing, $index );

    $response = array();
    $response['ok'] = true;

    echo json_encode( $response );
    exit();
}
add_action( 'wp_ajax_gre-listing-file-delete', 'gre_admin_listing_file_delete' );
