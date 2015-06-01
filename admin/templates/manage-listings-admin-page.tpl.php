<div class="wrap">
    <h2>
        <?php _e( 'Manage Listings', 'greatrealestate' ); ?><a href="<?php echo $add_new_listing_url; ?>" class="add-new-h2"><?php _e( 'Add a listing', 'greatrealestate' ); ?></a>
    </h2>

    <?php echo $table->views(); ?>
    <?php echo $table->display(); ?>
</div>
