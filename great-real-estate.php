<?php
/**
 * Plugin Name: Great Real Estate
 * Plugin URI: http://greatrealestateplugin.com
 * Description: Adds the ability to create and manage real estate listings in your WordPress site
 * Version: 1.5.1-dev-3
 * Author: Dave Rodenbaugh
 * Author URI: http://greatrealestateplugin.com
 * License: GPLv2 or any later version
 */

/*  Copyright 2008-2015, Skyline Consulting and D. Rodenbaugh

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2 or later, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    reCAPTCHA used with permission of Mike Crawford & Ben Maurer, http://recaptcha.net
*/
// STOP DIRECT CALLS
if( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) { die( 'You are not allowed to call this page directly.' ); }

#
global $listings;
global $wpdb, $wp_version;

# NOTE: THE FUNCTIONS YOU CAN/SHOULD USE IN YOUR THEME ARE ALL DESCRIBED IN 
#     templatefunctions.php
# some functions found here are not guaranteed to be upgrade compatible

// backward compatibility (pre- WP2.6)
if ( !defined('WP_CONTENT_URL') ) {
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if ( !defined('WP_CONTENT_DIR') ) {
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}

define( 'GRE_VERSION', '1.5' );
define( 'GRE_FOLDER', plugin_dir_path( __FILE__ ) );
define( 'GRE_URL', plugin_dir_url( __FILE__ ) );

define( 'GRE_URLPATH',  WP_CONTENT_URL .'/plugins/' .  dirname(plugin_basename(__FILE__) ) . '/');

# add database pointer
$wpdb->gre_listings = $wpdb->prefix . "greatrealestate_listings";

// Check for WP2.5 installation
$version_constants = array(
    'IS_WP25' => '2.4',
    'IS_WP26' => '2.6',
    'IS_WP27' => '2.7',
    'IS_WP28' => '2.8',
);

foreach ( $version_constants as $constant_name => $version_number ) {
    if ( ! defined( $constant_name ) ) {
        define( $constant_name, version_compare( $wp_version, $version_number, '>=' ) );
    }
}

//This works only in WP2.5 or higher
if ( IS_WP25 == FALSE ){
	add_action( 'admin_notices', create_function( '', 'echo \'<div id="message" class="error fade"><p><strong>' . __( 'Sorry, Great Real Estate works only under WordPress 2.5 or higher', "greatrealestate" ) . '</strong></p></div>\';' ) );
	return;
}

require_once( GRE_FOLDER . 'core/install.php' );
require_once( GRE_FOLDER . 'core/listings.php' );
require_once( GRE_FOLDER . 'core/routes.php' );
require_once( GRE_FOLDER . 'core/utils.php' );

require_once( GRE_FOLDER . 'core/functions/array.php' );
require_once( GRE_FOLDER . 'core/functions/html.php' );

require_once( GRE_FOLDER . 'core/class-query.php' );
require_once( GRE_FOLDER . 'core/class-sql-query-builder.php' );
require_once( GRE_FOLDER . 'core/class-listings-finder.php' );
require_once( GRE_FOLDER . 'core/class-listings-for-rent-shortcode-handler.php' );
require_once( GRE_FOLDER . 'core/class-listings-for-sale-shortcode-handler.php' );
require_once( GRE_FOLDER . 'core/class-listings-shortcode.php' );
require_once( GRE_FOLDER . 'core/class-listings-sql-query-builder.php' );
require_once( GRE_FOLDER . 'core/class-listings-widget.php' );
require_once( GRE_FOLDER . 'core/class-listing-geolocation-service.php' );
require_once( GRE_FOLDER . 'core/class-featured-listings-widget.php' );
require_once( GRE_FOLDER . 'core/class-regular-listings-widget.php' );
require_once( GRE_FOLDER . 'core/class-random-listings-widget.php' );
require_once( GRE_FOLDER . 'core/class-plugin-settings.php' );
require_once( GRE_FOLDER . 'core/class-search-listings-form.php' );
require_once( GRE_FOLDER . 'core/class-search-listings-shortcode-handler.php' );
require_once( GRE_FOLDER . 'core/class-search-listings-widget.php' );
require_once( GRE_FOLDER . 'core/class-settings.php' );
require_once( GRE_FOLDER . 'core/class-shortcodes-manager.php' );
/* HACKS (or Interfaces if you will...)
 * here go the interfaces to other plugins we access - which are likely to
 * break if those plugins change. Nice if plugin authors had more exposed
 * functions we could use, oh well...
 */
require_once( GRE_FOLDER . 'core/compat.php' );

require_once( GRE_FOLDER . 'admin/class-listings-table.php' );
require_once( GRE_FOLDER . 'admin/utils.php' );

require_once( GRE_FOLDER . 'debugging.php' );
require_once( GRE_FOLDER . 'feeds.php' );
require_once( GRE_FOLDER . 'interfaces/interface-nggallery.php' );
require_once( GRE_FOLDER . 'privatefunctions.php' );
require_once( GRE_FOLDER . 'templatefunctions.php' );

// TODO: isn't it too early to ask is_admin() before of plugins_loaded or init?
if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    require_once( dirname (__FILE__).'/admin/admin.php' );
}


class Great_Real_Estate_Plugin {

    public $settings;
    public $feeds;

    public function start() {
        $this->settings = new GRE_Settings();

        add_action( 'widgets_init', array( $this, 'register_widgets' ) );
        add_action( 'init', array( $this, 'init' ), 10 );
        add_action( 'init', array( $this, 'late_init' ), 10000 );
        add_action( 'init', 'gre_legacy_init', 20000 );
    }

    public function init() {
        // time to get any translations
        load_plugin_textdomain( 'greatrealestate', false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );

        $this->feeds = new GRE_RSS_Feeds();

        add_action( 'gre_register_settings', array( new GRE_Plugin_Settings(), 'register_settings' ) );
        add_action( 'gre_register_feeds', array( $this, 'register_feeds' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_resources' ) );
        // add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_resources' ) );

        add_action( 'save_post', array( $this, 'maybe_save_listing' ) );

        $listing_geolocation_service = gre_listing_geolocation_service();
        add_action( 'gre_save_listing', array( $listing_geolocation_service, 'update_listing_geolocation' ) );

        if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
        } else if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        } else if ( is_admin() ) {
            $this->admin_setup();
        } else {
            $this->frontend_setup();
        }
    }

    private function admin_setup() {
        add_action( 'admin_init', array( $this->settings, 'register_in_admin' ) );
    }

    private function frontend_setup() {
        $this->register_shortcodes();
    }

    private function register_shortcodes() {
        $shortcodes_manager = gre_shortcodes_manager();

        $shortcodes_manager->add_shortcode( 'gre-search-listings', 'gre_search_listings_shortcode_handler' );
        $shortcodes_manager->add_shortcode( 'gre-listings-for-sale', 'gre_listings_for_sale_shortcode_handler' );
        $shortcodes_manager->add_shortcode( 'gre-listings-for-rent', 'gre_listings_for_rent_shortcode_handler' );
    }

    public function late_init() {
        do_action_ref_array( 'gre_register_settings', array( &$this->settings ) );
        do_action_ref_array( 'gre_register_feeds', array( &$this->feeds ) );
    }

    public function register_feeds( $feeds ) {
        $feeds->register_feeds( gre_get_active_feeds() );
    }

    public function enqueue_frontend_resources() {
        // TODO - only add when we are displaying a page that needs this
        if ( gre_get_option( 'usecss' ) ) {
            wp_enqueue_style( 'gre-frontend-base', GRE_URLPATH . '/core/css/great-real-estate.css' );
            wp_enqueue_style( 'gre-frontend-default', GRE_URLPATH . '/css/listings.css' );
            $plugin_styles = array( 'gre-frontend-base', 'gre-frontend-default' );
        } else {
            $plugin_styles = array();
        }

        // load custom stylesheet if one exists in the active theme or wp-content/plugins directory:
        if ( file_exists( get_stylesheet_directory() . '/great-real-estate.css' ) ) {
            wp_register_style(
                'gre-custom-css',
                get_stylesheet_directory_uri() . '/great-real-estate.css',
                $plugin_styles,
                GRE_VERSION,
                'all'
            );
        } else if ( file_exists( WP_PLUGIN_DIR . '/great-real-estate-custom.css' ) ) {
            wp_register_style(
                'gre-custom-css',
                plugins_url( 'great-real-estate-custom.css' ),
                $plugin_styles,
                GRE_VERSION,
                'all'
            );
        }

        // TODO - only add when we are displaying a page that needs this
        $googlekey = gre_get_option( 'googleAPIkey' );

        // TODO - add &hl=XX for localization
        // (workaround: append to API key in Real Estate / Settings)
        if ( ! empty( $googlekey ) ) {
            $maps_api_url = "https://maps.googleapis.com/maps/api/js?v=3&key=" . $googlekey;
        } else {
            $maps_api_url = "https://maps.googleapis.com/maps/api/js?v=3";
        }

        /// TODO: if Maps scripts are not necessary, enqueue jquery-ui-tabs only.
        // wp_enqueue_script( 'jquery-ui-tabs' );
        wp_enqueue_script( 'gre-google-maps', $maps_api_url, false, false, false );
        wp_enqueue_script( 'gre-maps', GRE_URLPATH . 'js/google.gre.js', array( 'gre-google-maps', 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), '0.1.0', true );
    }

    public function register_widgets() {
        register_widget( 'GRE_Regular_Listings_Widget' );
        register_widget( 'GRE_Featured_Listings_Widget' );
        register_widget( 'GRE_Random_Listings_Widget' );

        register_widget( 'GRE_Search_Listings_Widget' );
    }

    public function maybe_save_listing( $listing_id ) {
        global $wpdb;

        if ( ! $listing_id || wp_is_post_revision( $listing_id ) ) {
            return;
        }

        if ( get_post_type( $listing_id ) != 'page' ) {
            return;
        }

        $listings_page_id = gre_get_option( 'pageforlistings' );

        if ( ! $listings_page_id ) {
            return;
        }

        $sql = "SELECT post_parent FROM {$wpdb->posts} WHERE ID = %d LIMIT 1";
        $listing_parent_id = $wpdb->get_var( $wpdb->prepare( $sql, $listing_id ) );

        if ( $listing_parent_id != $listings_page_id ) {
            return;
        }

        do_action( 'gre_save_listing', $listing_id );
    }
}

function gre_plugin() {
    static $instance;

    if ( is_null( $instance ) ) {
        $instance = new Great_Real_Estate_Plugin();
    }

    return $instance;
}

function gre_load_plugin() {
    gre_plugin()->start();
}
add_action( 'plugins_loaded', 'gre_load_plugin' );

function gre_enable_debug() {
    // Enable debugging if needed.
    if ( defined( 'GRES_DEBUG' ) && true == GRES_DEBUG )
        GRES_Debugging::debug_on();
}
add_action( 'plugins_loaded', 'gre_enable_debug' );

function gre_get_option( $name, $default = null ) {
    return gre_plugin()->settings->get( $name, $default );
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

function greatrealestate_add_footerjs() {
	echo "\n<!-- great-real-estate -->\n";
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function() {
   jQuery('#listing-container').tabs();
   jQuery('a[href=#map]').bind('click',fixMap);

    // NOTE: GMaps requires a "kick" after being unhidden;
    // the timeout gives the browser a few seconds to start displaying
    // the map, otherwise GMaps will not receive its "kick"
   function fixMap(event) {
	   setTimeout("gre_map.checkResize()",500);
   }

} );
/* ]]> */
</script>
<?php
}
add_action( 'wp_footer', 'greatrealestate_add_footerjs', 90 );

function gre_legacy_init() {

/*********************************************************************
 *
 * generate listing index page
 *
 * NOTE: this is an ALTERNATIVE to setting up a custom template
 *       for the main Listings Page. A custom template provides many
 *       more options!
 */
if ( gre_get_option( 'genindex' ) ) {
	// add an action to output the listings summary right
	// after the content is displayed

//	add_action('loop_end','greatrealestate_add_listindex');
	add_filter( 'the_content', 'greatrealestate_add_listindex_filter' );

	function greatrealestate_add_listindex_filter( $content ) {
        global $listing;
        global $post;

        if ( ! is_object( $listing ) ) {
            $listing = new StdClass();
        }

        $listing->endloop = true;

		if (get_option('greatrealestate_pageforlistings') != $post->ID)
            return $content;

        return gre_render_default_listings_page();
    }

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
if ( gre_get_option( 'genlistpage' ) ) {
	// add a filter to output the listings info instead of the content
	
	add_filter('the_content','greatrealestate_add_listcontent');
	function greatrealestate_add_listcontent($content) {
		global $post;

        // if do not filter flag set, just pass it on
        // IMPORTANT otherwise it loops foreeeeeeevvvvvveeeeeerrrrrrr
        if ( ! ( strpos( $content, 'grenofilters' ) === FALSE ) ) {
            return $content;
        }

        if ( ! is_page() ) {
            return $content;
        }

		if (get_option('greatrealestate_pageforlistings') == $post->post_parent) {
            $content = gre_render_default_listing_page();
		}
		return $content;
	}

	//add_action('loop_end','greatrealestate_add_listdetails');

	function greatrealestate_add_listdetails() {
		global $post;
		global $listing;

		if ((get_option('greatrealestate_pageforlistings') == $post->post_parent )
		    && ( ! isset( $listing->endloop ) || ! $listing->endloop ) ) {
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

if ( gre_get_option( 'nobrand' ) ) {

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
