<?php if ( defined( 'GRE_VERSION' ) && version_compare( GRE_VERSION, '1.5-dev-3', '>=' ) ): ?>

<?php
    gre_set_view_title( esc_html( gre_get_option( 'active-listings-title' ) ) );
    gre_set_queried_objects( get_pages_with_active_listings( '', 'highprice' ) );

    gre_load_template_part( 'great-real-estate/listings-list' );
?>

<?php $pending_listings = get_pages_with_pending_listings( '','highprice' ); ?>

<?php if ( ! empty( $pending_listings ) ): ?>
<div id="pendingsales" class="gre-pending-listings-list gre-listings-list">
    <h2><?php echo esc_html( gre_get_option( 'pending-sale-listings-title', 'greatrealestate' ) ); ?></h2>

    <?php
        foreach( $pending_listings as $post ):
            setup_postdata( $GLOBALS['post'] = $post );
            setup_listingdata( $post );
    ?>

    <div class="prop-float-container prop-box gre-listings-list-item gre-clearfix">
        <div class="prop-thumb gre-listings-list-item-thumbnail"><?php the_listing_thumbnail(); ?></div>

        <div class="gre-listings-list-item-info">
            <h3 class="gre-listings-list-item-title">
                <a href="<?php the_permalink() ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a>
            </h3>

            <div class="gre-listings-list-item-status">
                <span class="propispending"><?php the_listing_status(); ?></span>
                <?php if ( get_listing_listprice() ) { _e( 'Last offered at ', 'greatrealestate' ); the_listing_listprice(); } ?>
            </div>
        </div>
    </div>

    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php $sold_listings = get_pages_with_sold_listings( '', 'saledate' ); ?>

<?php if ( ! empty( $sold_listings ) ): ?>
<div id="soldlistings" class="gre-sold-listings-list gre-listings-list">
    <h2><?php echo esc_html( gre_get_option( 'sold-listings-title', 'greatrealestate' ) ); ?></h2>

    <?php
        foreach ( $sold_listings as $post ):
            setup_postdata( $GLOBALS['post'] = $post );
            setup_listingdata( $post );
    ?>

    <div class="prop-float-container prop-box gre-listings-list-item gre-clearfix">
        <div class="prop-thumb gre-listings-list-item-thumbnail"><?php the_listing_thumbnail(); ?></div>

        <div class="gre-listings-list-item-info">
            <h3 class="gre-listings-list-title">
                <a href="<?php the_permalink() ?>" title="<?php _e('More about ','greatrealestate'); ?><?php the_title(); ?>"><?php the_title(); ?></a>
            </h3>

            <div>
                <span class="propwassold"><?php the_listing_status(); ?></span>
                <?php if ( get_listing_saleprice() ): ?>
                <?php the_listing_saleprice(); ?>
                <?php endif; ?>
                <?php if ( get_listing_saledate() ): ?>
                <em><?php the_listing_saledate(); ?></em>
                <?php endif ?>
            </div>
        </div>
    </div>

    <?php endforeach; ?>
    <?php wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php endif; # check whether GRE is installed and compatible ?>
