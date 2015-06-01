<?php

class GRE_Regular_Listings_Widget extends GRE_Listings_Widget {

    public function __construct() {
        parent::__construct(
            'gre-regular-listings-widget',
            __( 'Listings (Great Real Estate)', 'greatrealestate' ),
            array(
                'description' => __( 'Real Estate Listings', 'greatrealestate' ),
                'classname' => 'gre-regular-listings-widget',
            )
        );
    }

    protected function get_listings( $count, $orderby ) {
        if ( empty( $count ) || $count <= 0 ) {
            $count = 10;
        }

        if ( $orderby == 'random' ) {
            return gre_get_random_listings( $count, 'none', 'random' );
        } else {
            return get_pages_with_listings( $count, $orderby, 'none' );
        }
    }
}
