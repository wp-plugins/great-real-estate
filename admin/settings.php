<?php

function greatrealestate_admin_settings() {
    if ( ! current_user_can( 'manage_options' ) )
        return;

    $template = GRE_FOLDER . 'admin/templates/manage-settings-admin-page.tpl.php';

    echo gre_render_template( $template, array( 'settings' => gre_plugin()->settings ) );

    flush_rewrite_rules();
}
