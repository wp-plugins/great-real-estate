<?php

function gre_listings_for_rent_shortcode_handler() {
    return new GRE_Listings_For_Rent_Shortcode_Handler( gre_listings_shortcode() );
}

class GRE_Listings_For_Rent_Shortcode_Handler {

    private $shortcode;

    public function __construct( $shortcode ) {
        $this->shortcode = $shortcode;
    }

    public function do_shortcode( $shortcode_atts ) {
        $options = array(
            'title' => __( 'Listings For Rent', 'greatrealtestate' ),
            'filter' => 'for-rent',
            'template_name' => 'for-rent',
            'shortcode_atts' => $shortcode_atts,
        );

        return $this->shortcode->do_shortcode( $options );
    }
}
