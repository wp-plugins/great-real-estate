<div id="gmap-info" class="grep-listing-map-popup">
    <h3><?php echo esc_html( get_listing_blurb() ); ?></h3>

    <?php echo get_listing_thumbnail(); ?>

    <div>
        <?php the_listing_address(); ?><br />
        <?php the_listing_city(); ?>, <?php the_listing_state(); ?>, <?php the_listing_postcode(); ?><br />
        <?php echo get_listing_bedrooms(); ?>BR / <?php echo get_listing_bathrooms(); ?>.<?php echo get_listing_halfbaths(); ?>BA<br />
        <?php echo get_listing_status(); ?> <?php echo $listing_price; ?>
    </div>
</div>
