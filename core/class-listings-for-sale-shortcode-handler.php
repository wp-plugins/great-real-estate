<?php

function gre_listings_for_sale_shortcode_handler() {
    return new GRE_Listings_For_Sale_Shortcode_Handler( gre_listings_shortcode() );
}

class GRE_Listings_For_Sale_Shortcode_Handler {

    private $shortcode;

    public function __construct( $shortcode ) {
        $this->shortcode = $shortcode;
    }

    public function do_shortcode( $shortcode_atts ) {
        $options = array(
            'title' => __( 'Listings For Sale', 'greatrealtestate' ),
            'filter' => 'for-sale',
            'template_name' => 'for-sale',
            'shortcode_atts' => $shortcode_atts,
        );

        return $this->shortcode->do_shortcode( $options );
    }
}
