<?php

// Display the content for the admin panel
function greatrealestate_admin_main() {
	echo '<div class="wrap">';
	echo "<h2>" . __('Great Real Estate','greatrealestate') . "</h2>";
	# Show the status of the plugin
	# Number of listings by status
	# Whether all required associated plugins are installed and ready
	# Whether there is a page slugged "listings"
	echo '<div id="gre-dashboard-widgets-wrap">';
	echo '<div id="gre-dashboard-main">';
	gre_do_dashboard_summary();
	echo '</div>';
	echo '<div id="gre-dashboard-sidebar">';
	gre_do_dashboard_docs();
	gre_do_dashboard_plugins();
	echo "</div></div></div>";
}

function gre_do_dashboard_summary() {
	global $wpdb;
?>
<div id="dashboard_greatrealestate_summary" class="gre-dashboard_widget_holder">
  <div class="gre-dashboard-widget">
  <h3 class="gre-dashboard-widget-title"><?php _e('Real Estate Summary','greatrealestate'); ?></h3>
    <div class="gre-dashboard-widget-content">
    <h4><?php _e('Listings main page: ','greatrealestate'); ?><?php gre_get_mainpage(); ?></h4>
<h5>
<?php
	$querystr = "
    SELECT status, COUNT(*) as number
    FROM $wpdb->gre_listings listings
    GROUP BY status
 ";

	$total = 0; $available = 0; $sold = 0; $contract = 0;

	$stats = $wpdb->get_results($querystr, OBJECT);

	if ($stats) {
		foreach ($stats as $row => $data) {
			if (in_array($data->status, array( RE_FORSALE, RE_FORRENT ))) $available += $data->number;
			if (in_array($data->status, array( RE_SOLD, RE_RENTED ))) $sold += $data->number;
			if (in_array($data->status, array( RE_PENDINGSALE, RE_PENDINGLEASE ))) $contract += $data->number;
			if (in_array($data->status, array( RE_FORSALE, RE_FORRENT, RE_SOLD, RE_RENTED, RE_PENDINGSALE, RE_PENDINGLEASE ))) $total += $data->number;

		}
	}

	printf(__('You have %d listings: %d available, %d under contract, and %d sold','greatrealestate'),$total,$available,$contract,$sold);

?>
</h5>

    </div>
  </div>
</div>
<?php
}

function gre_get_mainpage() {
	$mainpage = get_option('greatrealestate_pageforlistings');
	if ($mainpage) {
		$pagetitle = get_the_title($mainpage);
		$pagelink = get_permalink($mainpage);
		if ($pagetitle && $pagelink) {
			$message =  "<a href='$pagelink'>$pagetitle</a>";
		} else {
			$message =  __('Unknown - please check your settings','greatrealestate');
		}
	} else {
		$message =  __('WARNING - You must select a main Listings page','greatrealestate');
	}
	echo $message;
}

function gre_do_dashboard_plugins() {
?>
<div id="gre-dashboard_greatrealestate_plugins" class="gre-dashboard_widget_holder">
  <div class="gre-dashboard-sidebar-widget">
  <h3 class="gre-dashboard-widget-title"><span><?php _e('Plugin Status','greatrealestate'); ?></span><small><a href="<?php echo get_option('siteurl'); ?>/wp-admin/plugins.php">See All</a></small><br class="clear" /></h3>
    <div class="gre-dashboard-widget-content">

    <h4><?php _e('Built In Features','greatrealestate'); ?></h4>
    <h5><?php gre_status('featured-homes'); ?> <?php _e('Featured Homes Widget','greatrealestate'); ?></h5>
    <h5><?php gre_status('google-maps'); ?> <?php _e('Google Maps','greatrealestate'); ?></h5>

<h4><?php _e('Other Plugins','greatrealestate'); ?></h4>
<h5><?php gre_status('nextgen-gallery'); ?> <?php _e('NextGen Gallery','greatrealestate'); ?></h5>
<h5><?php gre_status('wordtube'); ?> <?php _e('wordTube (Videos)','greatrealestate'); ?></h5>
<h5><?php gre_status('fpp-pano'); ?> <?php _e('FPP-Pano (Panoramas)','greatrealestate'); ?></h5>
<h5><?php gre_status('wp-downloadmanager'); ?> <?php _e('WP-Downloadmanager','greatrealestate'); ?></h5>
<h5><?php gre_status('feed-wrangler'); ?> <?php _e('Feed Wrangler','greatrealestate'); ?></h5>
<p><?php _e('Note: The status icon indicates whether a plugin is activated; please make sure you have also made the appropriate settings','greatrealestate'); ?></p>

    </div>
  </div>
</div>
<?php
}

function gre_status($component) {
	$ok = "/images/greenlight.png";
	$okmessage = __("Ready","greatrealestate");
	$warn = "/images/yellowlight.png";
	$warnmessage = __("Incomplete configuration","greatrealestate");
	$bad = "/images/redlight.png";
	$badmessage = __("Not activated","greatrealestate");
	$unknown = "/images/questionlight.png";
	$unknownmessage = __("details unavailable",'great_real_estate');
	// default status icons
	$icon = $unknown;
	$message = $unknownmessage;

	switch ($component) {
	case ('featured-homes') :
		if (function_exists('wp_widget_grefeatured_control')) {
			$icon = $ok;
			$message = __("widget activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/great-real-estate')) {
			$icon = $warn;
			$message = __('widget not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('file not installed','greatrealestate');
		}
		break;
	case ('google-maps') :
		if (get_option('greatrealestate_googleAPIkey')) {
			$icon = $ok;
			$message = __("Google API key present",'greatrealestate');
		} else {
			$icon = $bad;
			$message = __('Google API Key not present','greatrealestate');
		}
		break;
	case ('nextgen-gallery') :
		if (function_exists('nggallery_install')) {
			$icon = $ok;
			$message = __("plugin activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/nextgen-gallery')) {
			$icon = $warn;
			$message = __('plugin not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('plugin not installed','greatrealestate');
		}
		break;
	case ('wordtube') :
		if (class_exists('wordTubeClass')) {
			$icon = $ok;
			$message = __("plugin activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/wordtube')) {
			$icon = $warn;
			$message = __('plugin not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('plugin not installed','greatrealestate');
		}
		break;
	case ('fpp-pano') :
		if (function_exists('fpp_pano_admin_setup')) {
			$icon = $ok;
			$message = __("plugin activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/fpp-pano')) {
			$icon = $warn;
			$message = __('plugin not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('plugin not installed','greatrealestate');
		}
		break;
	case ('wp-downloadmanager') :
		if (function_exists('downloads_menu')) {
			$icon = $ok;
			$message = __("plugin activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/wp-downloadmanager')) {
			$icon = $warn;
			$message = __('plugin not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('plugin not installed','greatrealestate');
		}
		break;
	case ('feed-wrangler') :
		if (class_exists('FeedWrangler')) {
			$icon = $ok;
			$message = __("plugin activated",'greatrealestate');
		} elseif (gre_is_plugin_there('/feed-wrangler')) {
			$icon = $warn;
			$message = __('plugin not activated','greatrealestate');
		} else {
			$icon = $bad;
			$message = __('plugin not installed','greatrealestate');
		}
		break;
	default:
		$icon = $unknown;
		$message = "This item cannot be checked";
		break;
	}

	echo '<img src="'. GRE_URLPATH . $icon . '" alt="status icon" title="' . $message . '" />';
}

function gre_is_plugin_there($plugin_dir) {
	$plugins = get_plugins($plugin_dir);
	if ($plugins) return true;
	return false;
}

function gre_do_dashboard_docs() {
?>
<div id="dashboard_greatrealestate_plugins" class="gre-dashboard_widget_holder">
  <div class="gre-dashboard-sidebar-widget">
  <h3 class="gre-dashboard-widget-title"><?php _e('Docs &amp; Support','greatrealestate'); ?></h3>
    <div class="gre-dashboard-widget-content">

<h4><a href="http://www.rogertheriault.com/agents/plugins/great-real-estate-plugin/" title="Great Real Estate plugin home page">Plugin Home Page</a></h4>
<h4><a href="http://www.rogertheriault.com/forums/" title="Great Real Estate User Help and Support">Support Forum</a></h4>
<h4><a href="http://www.rogertheriault.com/agents/blog/" title="Great Real Estate Blog">Great Real Estate Blog</a></h4>

    </div>
  </div>
</div>
<?php
}

?>
