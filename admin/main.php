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
    $plugins = array(
        'nextgen-gallery' => array(
            'name' => __( 'NextGen Gallery', 'greatrealestate' ),
            'description' => __( 'NextGen Gallery', 'greatrealestate' ),
            'function' => 'nggallery_install',
            'class' => 'nggGallery',
            'plugin_directory' => 'nextgen-gallery',
            'plugin_settings_url' => add_query_arg( 'page', 'nextgen-gallery', admin_url( 'admin.php' ) ),
        ),
        'panopress' => array(
            'name' => __( 'PanoPress', 'greatrealestate' ),
            'description' => __( 'PanoPress (Panoramas)', 'greatrealestate' ),
            'function' => 'pp_default_settings',
            'class' => null,
            'plugin_directory' => 'panopress',
            'plugin_settings_url' => add_query_arg( 'page', 'panopress', admin_url( 'options-general.php' ) ),
        ),
    );

    $params = array(
        'plugins' => gre_get_plugins_information( $plugins ),
    );

    $template = GRE_FOLDER . 'admin/templates/plugin-status-dashboard-widget.tpl.php';

    echo gre_render_template( $template, $params );
}

/**
 * @since 1.5
 */
function gre_get_plugins_information( $plugins ) {
    foreach ( $plugins as $plugin_slug => $plugin_info ) {
        $status = gre_get_plugin_status( $plugin_info );

        if ( $status == 'active' ) {
            $status_message = __( 'Plugin Installed and Active', 'greatrealestate' );
            $status_icon_url = GRE_URLPATH . '/images/greenlight.png';
            $status_link_url = $plugin_info['plugin_settings_url'];
        } else if ( $status == 'installed' ) {
            $status_message = __( 'Plugin Installed but NOT active. Please click the link to activate it.', 'greatrealestate' );
            $status_icon_url = GRE_URLPATH . '/images/yellowlight.png';
            $status_link_url = add_query_arg( 's', $plugin_info['name'], admin_url( 'plugins.php' ) );
        } else {
            $query_args = array(
                'tab' => 'search',
                'type' => 'term',
                's' => $plugin_info['name']
            );

            $status_message = __( 'Plugin not installed. Please click the link to start installing it.', 'greatrealestate' );
            $status_icon_url = GRE_URLPATH . '/images/redlight.png';
            $status_link_url = add_query_arg( $query_args, admin_url( 'plugin-install.php' ) );
        }

        $plugins[ $plugin_slug ]['status'] = $status;
        $plugins[ $plugin_slug ]['status_message'] = $status_message;
        $plugins[ $plugin_slug ]['status_icon_url'] = $status_icon_url;
        $plugins[ $plugin_slug ]['status_link_url'] = $status_link_url;
    }

    return $plugins;
}

/**
 * @since 1.5
 */
function gre_get_plugin_status( $plugin_info ) {
    $plugin_function_exists = $plugin_info['function'] && function_exists( $plugin_info['function'] );
    $plugin_class_exists = $plugin_info['class'] && class_exists( $plugin_info['class'] );

    if ( $plugin_function_exists || $plugin_class_exists ) {
        $status = 'active';
    } else if ( gre_is_plugin_there( "/{$plugin_info['plugin_directory']}" ) ) {
        $status = 'installed';
    } else {
        $status = 'missing';
    }

    return $status;
}

/**
 * @since 1.5
 */
function gre_is_plugin_there($plugin_dir) {
    if ( ! function_exists( 'get_plugins' ) )
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

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

<h4><a href="http://greatrealestateplugin.com/" target="_blank" title="Great Real Estate plugin home page">Plugin Home Page</a></h4>
<h4><a href="http://greatrealestateplugin.com/forums/" target="_blank" title="Great Real Estate User Help and Support">Support Forum</a></h4>
<h4><a href=" http://greatrealestateplugin.com/docs/" target="_blank" title="Documentation">Documentation</a></h4>

    </div>
  </div>
</div>
<?php
}

?>
