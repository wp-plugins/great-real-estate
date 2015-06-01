<ul class="gre-listings-widget-item-list gre-list-plain">
<?php
    foreach ( $listings as $listing ):
        // from setup_postdata docs:
        // You must pass a reference to the global $post variable, otherwise functions like the_title() don't work properly.
        setup_postdata( $GLOBALS['post'] =& $listing );
        setup_listingdata( $listing );

        $title = get_the_title();
        $link_title = str_replace( '<listing-title>', $title, __( 'More about <listing-title>', 'greatrealestate' ) );
?>
    <li class="gre-listings-widget-item gre-listings-widget-item-<?php echo esc_attr( $layout ); ?>-style gre-list-plain-item gre-clearfix">
    <?php if ( $layout == 'text' ): ?>
    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $link_title ); ?>"><?php echo esc_html( $title ); ?></a>
    <br />
    <?php echo get_listing_status(); ?> <?php echo get_listing_listprice(); ?>
    <?php elseif ( $layout == 'basic' ): ?>
    <div class="gre-listings-widget-item-thumbnail prop-thumb"><?php echo get_listing_thumbnail(); ?></div>

    <div class="gre-listings-widget-item-title">
        <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $link_title ); ?>"><?php echo esc_html( $title ); ?></a>
    </div>

    <div class="gre-listings-widget-item-blurb"><em><?php echo get_listing_blurb(); ?></em></div>
    <div class="gre-listings-widget-item-list-price"><em><?php echo get_listing_listprice(); ?></em></div>
    <?php endif; ?>
    </li>
<?php
    endforeach;
    wp_reset_postdata();
?>
</ul>
