<?php

function gre_search_listings_shortcode_handler() {
    return new GRE_Search_Listings_Shortcode_Handler(
        gre_search_listings_form(),
        gre_query()
    );
}

class GRE_Search_Listings_Shortcode_Handler {

    private $form;
    private $query;

    public function __construct( $form, $query ) {
        $this->form = $form;
        $this->query = $query;
    }

    public function do_shortcode( $atts ) {
        if ( $this->form->has_data() ) {
            return $this->search_listings();
        } else {
            return $this->render_search_listings_form( $this->get_form_options( $atts ) );
        }
    }

    private function get_form_options( $atts ) {
        $default_attrs = array(
            'hide_min_price_field' => false,
            'hide_max_price_field' => false,
            'hide_bedrooms_field' => false,
            'hide_bathrooms_field' => false,
            'hide_property_status_field' => false,
            'hide_property_type_field' => false,
        );

        $atts = shortcode_atts( $default_attrs, $atts );

        return array(
            'show_min_price_field' => ! $atts[ 'hide_min_price_field' ],
            'show_max_price_field' => ! $atts[ 'hide_max_price_field' ],
            'show_bedrooms_field' => ! $atts[ 'hide_bedrooms_field' ],
            'show_bathrooms_field' => ! $atts[ 'hide_bathrooms_field' ],
            'show_property_status_field' => ! $atts[ 'hide_property_status_field' ],
            'show_property_type_field' => ! $atts[ 'hide_property_type_field' ],
        );
    }

    private function search_listings() {
        $form_data = $this->form->get_posted_data();
        $params = $this->get_finder_params( $form_data );

        $this->query->set_queried_objects( gre_listings_finder()->find( $params ) );
        $this->query->set_var( 'return_url', $this->form->get_form_url( $this->get_current_url() ) );

        return gre_render_template_part( 'great-real-estate/search-listings-results' );
    }

    private function get_current_url() {
        return add_query_arg( array() );
    }

    private function get_finder_params( $posted_data ) {
        $params = array(
            'min_price' => $posted_data['gre_min_price'],
            'max_price' => $posted_data['gre_max_price'],
            'bedrooms' => $posted_data['gre_bedrooms'],
            'bathrooms' => $posted_data['gre_bathrooms'],
            'property_status' => $posted_data['gre_property_status'],
            'property_type' => $posted_data['gre_property_type'],
        );

        if ( $params['property_status'] == 'any' ) {
            unset( $params['property_status'] );
        }

        if ( $params['property_type'] == 'any' ) {
            unset( $params['property_type'] );
        }

        return array_filter( $params, 'strlen' );
    }

    private function render_search_listings_form( $form_options ) {
        $form = $this->form->render( $this->get_current_url(), $form_options, array() );

        $content = '<div class="gre-search-listings-shortcode"><search-form></div>';
        $content = str_replace( '<search-form>', $form, $content );

        return $content;
    }
}
