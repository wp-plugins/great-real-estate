<a class="gre-search-listings-shortcode-form-url" href="<?php echo esc_url( gre_get_return_url() ); ?>"><?php echo esc_html( __( 'Return to Search Listings', 'greatrealestate' ) ); ?></a>

<?php $queried_objects = gre_get_queried_objects(); ?>

<?php if ( ! empty( $queried_objects ) ): ?>
<?php
        foreach ( $queried_objects as $post ) {
            setup_postdata( $GLOBALS['post'] = $post );
            setup_listingdata( $post );

            gre_load_template_part( 'great-real-estate/listing-excerpt' );
        }
?>
<?php else: ?>
<div class="gre-search-listings-shortcode-no-results">
    <?php echo esc_html( __( 'No listings found.', 'greatrealestate' ) ); ?>
</div>
<?php endif; ?>
