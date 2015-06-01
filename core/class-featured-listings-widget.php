<?php

class GRE_Featured_Listings_Widget extends GRE_Listings_Widget {

    public function __construct() {
        parent::__construct(
            'grefeatured',
            __( 'Featured Listings (Great Real Estate)', 'greatrealestate' ),
            array(
                'description' => __( 'Featured Real Estate Listings', 'greatrealestate' ),
                'classname' => 'widget_grefeatured gre-featured-listings-widget',
            )
        );
    }

    protected function get_listings( $count, $orderby ) {
        if ( empty( $count ) || $count <= 0 ) {
            $count = intval( gre_get_option( 'maxfeatured' ) );
        }

        if ( $orderby == 'random' ) {
            return gre_get_random_listings( $count, 'featured', 'random' );
        } else {
            return get_pages_with_featured_listings( $count, $orderby );
        }
    }
}
