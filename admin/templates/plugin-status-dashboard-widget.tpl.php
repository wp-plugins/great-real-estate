<div id="gre-dashboard_greatrealestate_plugins" class="gre-dashboard_widget_holder">
    <div class="gre-dashboard-sidebar-widget">
        <h3 class="gre-dashboard-widget-title"><span><?php _e('Plugin Status','greatrealestate'); ?></span><small><a href="<?php echo get_option('siteurl'); ?>/wp-admin/plugins.php">See All</a></small><br class="clear" /></h3>

        <div class="gre-dashboard-widget-content">

            <h4><?php _e('Related Plugins','greatrealestate'); ?></h4>

            <?php foreach ( $plugins as $plugin ): ?>
            <h5>
                <a href="<?php echo esc_attr( $plugin['status_link_url'] ); ?>" title="<?php echo esc_attr( $plugin['status_message'] ); ?>">
                    <img src="<?php echo esc_attr( $plugin['status_icon_url'] ); ?>" title="<?php echo esc_attr( $plugin['status_message'] ); ?>" /> <?php echo esc_html( $plugin['description'] ); ?>
                </a>
            </h5>
            <?php endforeach; ?>

            <p><?php _e('Note: The status icon indicates whether a plugin is activated; please make sure you have also made the appropriate settings','greatrealestate'); ?></p>

        </div>
    </div>
</div>
