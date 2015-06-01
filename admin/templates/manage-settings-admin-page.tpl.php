<div class="wrap">
    <h2><?php echo esc_html( __( 'Manage Settings - Great Real Estate', 'greatrealestate' ) ); ?></h2>

    <h3 class="nav-tab-wrapper">
    <?php if (isset($_REQUEST['settings-updated'])): ?>
        <div class="updated fade">
            <p><?php _e('Settings updated.', 'WPBDM'); ?></p>
        </div>
    <?php endif; ?>

    <?php $group_slug = isset( $_REQUEST['group'] ) ? $_REQUEST['group'] : 'general'; ?>

    <?php foreach($settings->groups as $g): ?>
        <a class="nav-tab <?php echo $g->slug == $group_slug ? 'nav-tab-active': ''; ?>"
           href="<?php echo esc_url( add_query_arg( 'group', $g->slug, remove_query_arg( 'settings-updated' ) ) ); ?>">
           <?php echo $g->name; ?>
        </a>
    <?php endforeach; ?>
    </h3>

    <?php $group = $settings->groups[ $group_slug ]; ?>

    <form action="<?php echo admin_url( 'options.php' ); ?>" method="POST">
        <input type="hidden" name="group" value="<?php echo $group->slug; ?>" />
        <?php if ($group->help_text): ?>
            <p class="description"><?php echo $group->help_text; ?></p>
        <?php endif; ?>
        <?php settings_fields($group->wpslug); ?>
        <?php do_settings_sections($group->wpslug); ?>
        <?php echo submit_button(); ?>
    </form>

</div>
