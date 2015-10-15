<?php

function gre_listings_shortcode() {
    return new GRE_Listings_Shortcode( gre_query() );
}

class GRE_Listings_Shortcode {

    private $query;

    public function __construct( $query ) {
        $this->query = $query;
    }

    public function do_shortcode( $options ) {
        $options = $this->get_options( $options );
        $shortcode_atts = $this->get_shortcode_atts( $options );

        $listings = get_pages_with_listings( $shortcode_atts['limit'], $options['orderby'], $options['filter'] );

        if ( $shortcode_atts['hide-title'] ) {
            $this->query->set_var( 'title', null );
        } else {
            $this->query->set_var( 'title', $options['title'] );
        }

        $this->query->set_queried_objects( $listings );

        return gre_render_template_part( 'great-real-estate/listings-list', $options['template_name'] );
    }

    private function get_options( $options ) {
        $default_options = array(
            'title' => __( 'Listings', 'greatrealtestate' ),
            'filter' => 'for-sale',
            'orderby' => 'listdate',
            'template_name' => 'for-sale',
            'shortcode_atts' => array(),
        );

        return wp_parse_args( $options, $default_options );
    }

    private function get_shortcode_atts( $options ) {
        $default_atts = array(
            'limit' => null,
            'hide-title' => false,
        );

        return shortcode_atts( $default_atts, $options['shortcode_atts'] );
    }
}
