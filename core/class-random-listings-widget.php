<?php

class GRE_Random_Listings_Widget extends GRE_Listings_Widget {

    public function __construct() {
        parent::__construct(
            'gre-random-listings-widget',
            __( 'Random Listings (Great Real Estate)', 'greatrealestate' ),
            array(
                'description' => __( 'Random Real Estate Listings', 'greatrealestate' ),
                'classname' => 'gre-random-listings-widget',
            )
        );
    }

    protected function get_listings( $count, $orderby ) {
        if ( empty( $count ) || $count <= 0 ) {
            $count = 10;
        }

        return gre_get_random_listings( $count, 'none', $orderby );
    }
}
