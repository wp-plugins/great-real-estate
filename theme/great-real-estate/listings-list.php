<?php
    $listings = gre_get_queried_objects();
?>

<?php if ( ! empty( $listings ) ): ?>
<div id="activelistings" class="gre-active-listings-list gre-listings-list">
    <?php if ( strlen( gre_get_view_title( 'title' ) ) > 0 ): ?>
    <h2><?php echo esc_html( gre_get_view_title( 'title' ) ); ?></h2>
    <?php endif; ?>

    <?php
        foreach ( $listings as $post ) {
            setup_postdata( $GLOBALS['post'] = $post );
            setup_listingdata( $post );

            gre_load_template_part( 'great-real-estate/listing-excerpt' );
        }

        wp_reset_postdata();
    ?>
</div>
<?php endif; ?>
