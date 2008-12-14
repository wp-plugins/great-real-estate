<?php
/*
Plugin Name: Great Real Estate
Plugin URI: http://www.rogertheriault.com/agents/plugins/great-real-estate-plugin/
Description: The Real Estate plugin for Wordpress
Version: 1.1.1
Author: Roger Theriault
Author URI: http://RogerTheriault.com/agents/
*/

/*  Copyright 2008  Roger Theriault  (email : roger@rogertheriault.com)

    Great Real Estate - The Real Estate plugin for WordPress
    Copyright (C) 2008  Roger Theriault

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/*
 * changelog
 * Version 1.1
 * [2008-07-27] updated for WP2.6
 * 		added localization (correctly)
 * Version 1.01
 * [2008-06-27] added shortcode handler for featured listings block
 * Version 1.0 (original)
 *
 */


// STOP DIRECT CALLS
if( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) { die( 'You are not allowed to call this page directly.' ); }

#
global $listings;
global $wpdb, $wp_version;
global $greatrealestate_db_version;

# NOTE: THE FUNCTIONS YOU CAN/SHOULD USE IN YOUR THEME ARE ALL DESCRIBED IN 
#     templatefunctions.php
# some functions found here are not guaranteed to be upgrade compatible

$greatrealestate_db_version = "1.0";
// backward compatibility (pre- WP2.6)
if ( !defined('WP_CONTENT_URL') ) {
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if ( !defined('WP_CONTENT_DIR') ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
define( 'GREFOLDER', WP_CONTENT_DIR . '/plugins/' . dirname(plugin_basename(__FILE__) ));
# define( 'GREFOLDER', plugin_basename(dirname(__FILE__) ));
define( 'GRE_URLPATH',  WP_CONTENT_URL .'/plugins/' .  dirname(plugin_basename(__FILE__) ) . '/');

# add database pointer
$wpdb->gre_listings = $wpdb->prefix . "greatrealestate_listings";

// Check for WP2.5 installation
define( 'IS_WP25', version_compare( $wp_version, '2.4', '>=' ) );


//This works only in WP2.5 or higher
if ( IS_WP25 == FALSE ){
	add_action( 'admin_notices', create_function( '', 'echo \'<div id="message" class="error fade"><p><strong>' . __( 'Sorry, Great Real Estate works only under WordPress 2.5 or higher', "greatrealestate" ) . '</strong></p></div>\';' ) );
	return;
}




////
if ( is_admin() ) {
	# ensure we have jquery available on admin screens
	# wp_enqueue_script( 'jquery' );
	require_once( dirname (__FILE__).'/admin/admin.php' );

}

require_once( dirname (__FILE__).'/privatefunctions.php' );
require_once( dirname (__FILE__).'/templatefunctions.php' );

register_activation_hook( __FILE__, 'greatrealestate_activate' );
function greatrealestate_activate( ) {
	# add option defaults
	add_option( 'greatrealestate_maxfeatured', '5' );
	add_option( 'greatrealestate_usecss', 'true' );
	add_option( 'greatrealestate_nobrand', 'false' );
	# add database files if not there
	greatrealestate_install( );
}

register_deactivation_hook( __FILE__, 'greatrealestate_deactivate' );
function greatrealestate_deactivate( ) {
	# remove database files ??? no thats a separate user-initiated action
}

// load language / localization file
add_action('init','greatrealestate_init');
function greatrealestate_init() {
	// time to get any translations
	load_plugin_textdomain( 'greatrealestate', false, dirname(plugin_basename(__FILE__)) );
}


if ( !function_exists( 'the_slug' ) ) {
	function the_slug( $post_ID = '' ) {
		global $post;
		if ( ! $post_ID ) $post_ID = $post->ID;
		$post_data = get_post( $post_ID, ARRAY_A );
		$slug = $post_data['post_name'];
		return $slug;
	}
}

function greatrealestate_add_javascript( ) {

	// TODO - only add when we are displaying a page that needs this
	
	$googlekey = get_option( 'greatrealestate_googleAPIkey' );

	// TODO - add &hl=XX for localization
	// (workaround: append to API key in Real Estate / Settings)
	if ( $googlekey ) {
		$googlepath = "http://www.google.com/jsapi?key=" . $googlekey;
		wp_enqueue_script( 'google', $googlepath, FALSE );
		wp_enqueue_script( 'google-gre', GRE_URLPATH . 'js/google.gre.js', array( 'google' ), '0.1.0' );
	}
	wp_enqueue_script( 'jquery-ui', GRE_URLPATH . 'js/ui.core.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-ui-tabs', GRE_URLPATH . 'js/ui.tabs.js', array( 'jquery' ) );

}
add_action( 'wp_print_scripts', 'greatrealestate_add_javascript' );


function greatrealestate_add_headerincludes( ) {

	// TODO - only add when we are displaying a page that needs this
	
	$defaultcss = ( 'true' == get_option( 'greatrealestate_usecss' ) ) ? true : false;

	// TODO - nice comments
	echo "\n<!-- added by great-real-estate -->\n";
	if ( $defaultcss ) {
?>
	<style type="text/css" media="screen">@import "<?php echo GRE_URLPATH; ?>css/listings.css";</style>
<?php
	}
	// NOTE: GMaps requires a "kick" after being unhidden;
	// the timeout gives the browser a few seconds to start displaying
	// the map, otherwise GMaps will not receive its "kick"
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function() {
   jQuery('#listing-container > ul').tabs();
   jQuery('a[href=#map]').bind('click',fixMap);

   function fixMap(event) {
	   setTimeout("gre_map.checkResize()",500);
   }

} );
/* ]]> */
</script>
<?php
	echo "\n<!-- end great-real-estate -->\n";
}
add_action( 'wp_head', 'greatrealestate_add_headerincludes', 90 );

/*********************************************************************
 *
 * generate listing index page
 *
 * NOTE: this is an ALTERNATIVE to setting up a custom template
 *       for the main Listings Page. A custom template provides many
 *       more options!
 */
if ( 'true' == get_option('greatrealestate_genindex') ) {
	// add an action to output the listings summary right
	// after the content is displayed
	
	add_action('loop_end','greatrealestate_add_listindex');
	function greatrealestate_add_listindex() {
		global $post;
		if (get_option('greatrealestate_pageforlistings') == $post->ID) {
			greatrealestate_defaultlistingindex();
		}

	}
}


/*********************************************************************
 *
 * generate listing content
 *
 * NOTE: this is an ALTERNATIVE to setting up a custom template
 *       for pages with listing data. A custom template provides many
 *       more options!
 */
if ( 'true' == get_option('greatrealestate_genlistpage') ) {
	// add a filter to output the listings info instead of the content
	
	add_filter('the_content','greatrealestate_add_listcontent');
	function greatrealestate_add_listcontent($content) {
		global $post;
		if (get_option('greatrealestate_pageforlistings') == $post->post_parent) {
			$content = greatrealestate_defaultlistingcontent($content);
		}
		return $content;
	}

	add_action('loop_end','greatrealestate_add_listdetails');
	function greatrealestate_add_listdetails() {
		global $post;
		global $listing;
		if ((get_option('greatrealestate_pageforlistings') == $post->post_parent )
		    && !($listing->endloop) ) {
			greatrealestate_defaultlistingdetails();
		}

	}
}





/*************************************
 *
 * no-brand - restyle page to hide agent info and branding for MLS Vtour link
 *
 * note: PAGEs with /nobrand/foo/ will work, but /nobrand/ wont...
 *
 */

if ( 'true' == get_option( 'greatrealestate_nobrand' ) ) {

	function gre_nobrand_flush_rewrite_rules() {
		global $wp_rewrite;

		add_rewrite_endpoint('nobrand',EP_PAGES);

   		$wp_rewrite->flush_rules();
	}
	add_action('init', 'gre_nobrand_flush_rewrite_rules');

	function gre_nobrand_addvariables($public_query_vars) {
		$public_query_vars[] = 'nobrand';
		return $public_query_vars;
	}
	add_filter('query_vars', 'gre_nobrand_addvariables');


	function gre_nobrand_add_stylesheet() {
		if (get_query_var('nobrand')) {
			// add stylesheet to hide brand items and nav
			echo "\n".'<style type="text/css" media="screen">@import "'. GRE_URLPATH .'/css/nobrand.css";</style>'."\n";
			echo "\n".'<style type="text/css" media="screen">@import "'. get_bloginfo('stylesheet_directory') .'/nobrand.css";</style>'."\n";
		}
	}
	add_action('wp_head', 'gre_nobrand_add_stylesheet', 1);

	function gre_nobrand_hide_title($content,$show) {
		// filter the header title - name portion
		// replace it with the value eg /nobrand/newname
		// TODO - lookup table of strings to use,
		//        eg: rmls = "Regional MLS"
		if ($show == 'name') {
			if ($newname = get_query_var('nobrand')) {
				return $newname;
			}
		}
		return $content;
	}
	add_filter('bloginfo', 'gre_nobrand_hide_title', 1, 2);
}

/*
 * SHORTCODE HANDLERS
 *
 */

add_shortcode( 'featured-listings', 'greatrealestate_fl_sc_handler' );

function greatrealestate_fl_sc_handler( $attr ) {
	$attr = shortcode_atts( array ( 
		'max' => '',
		'sort' => 'random',
		'type' => 'basic',
		'head' => ''
		 ) , $attr );
	return get_listings_featured( $attr[max], $attr[sort], $attr[type], $attr[head] );
}


/* HACKS (or Interfaces if you will...)
 * here go the interfaces to other plugins we access - which are likely to
 * break if those plugins change. Nice if plugin authors had more exposed 
 * functions we could use, oh well...
 *
 */

require_once( dirname (__FILE__).'/interfaces/interface-nggallery.php' );
require_once( dirname (__FILE__).'/interfaces/interface-wordtube.php' );
require_once( dirname (__FILE__).'/interfaces/interface-wpdownloadmanager.php' );
require_once( dirname (__FILE__).'/interfaces/interface-fpp-pano.php' );

/* WIDGETS
 * Initialize our widgets
 */


/*
 * Database initialization 
 */

function greatrealestate_install( ) {
	global $wpdb;
	global $greatrealestate_db_version;

	$table_name = $wpdb->prefix . "greatrealestate_listings";

	$installed_ver = get_option( "greatrealestate_db_version" );

	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		$sql = "CREATE TABLE " . $table_name . " (
			id mediumint NOT NULL AUTO_INCREMENT,
			pageid bigint NOT NULL,
			address VARCHAR(100),
			city VARCHAR(50),
			state VARCHAR(40),
			postcode VARCHAR(10),
			mlsid VARCHAR(15),
			status tinyint NOT NULL,
			blurb VARCHAR(255),
			bedrooms VARCHAR(10),
			bathrooms VARCHAR(10),
			halfbaths VARCHAR(10),
			garage VARCHAR(10),
			acsf VARCHAR(10),
			totsf VARCHAR(10),
			acres VARCHAR(10),
			featureid VARCHAR(30),
			listprice int NOT NULL,
			saleprice int,
			listdate date,
			saledate date,
			galleryid VARCHAR(30),
			videoid VARCHAR(30),
			downloadid VARCHAR(30),
			panoid VARCHAR(30),
			latitude VARCHAR(20),
			longitude VARCHAR(20),
			featured VARCHAR(30),
			agentid VARCHAR(20),
			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		update_option( "greatrealestate_db_version", $greatrealestate_db_version );
	}
	// no upgrade needed yet
}


?>
