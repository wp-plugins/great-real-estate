<?php

function gre_search_listings_form() {
    return new GRE_Search_Listings_Form();
}

class GRE_Search_Listings_Form {

    public function render( $url, $options, $form ) {
        $params = array(
            'url' => $url,
            'options' => wp_parse_args( $options, $this->get_default_options() ),
            'form' => array_merge( $form, $this->get_posted_data() ),
        );

        $template = GRE_FOLDER . 'core/templates/search-listings-form.tpl.php';

        return gre_render_template( $template, $params );
    }

    private function get_default_options() {
        return array(
            'show_min_price_field' => true,
            'show_max_price_field' => true,
            'show_bedrooms_field' => true,
            'show_bathrooms_field' => true,
            'show_property_status_field' => true,
            'show_property_type_field' => true,
        );
    }

    public function get_posted_data() {
        return array(
            'gre_min_price' => sanitize_text_field( isset( $_REQUEST['gre_min_price'] ) ? $_REQUEST['gre_min_price'] : '' ),
            'gre_max_price' => sanitize_text_field( isset( $_REQUEST['gre_max_price'] ) ? $_REQUEST['gre_max_price'] : '' ),
            'gre_bedrooms' => sanitize_text_field( isset( $_REQUEST['gre_bedrooms'] ) ? $_REQUEST['gre_bedrooms'] : '' ),
            'gre_bathrooms' => sanitize_text_field( isset( $_REQUEST['gre_bathrooms'] ) ? $_REQUEST['gre_bathrooms'] : '' ),
            'gre_property_status' => sanitize_text_field( isset( $_REQUEST['gre_property_status'] ) ? $_REQUEST['gre_property_status'] : '' ),
            'gre_property_type' => sanitize_text_field( isset( $_REQUEST['gre_property_type'] ) ? $_REQUEST['gre_property_type'] : '' ),
        );
    }

    public function get_form_url( $base_url = null ) {
        $query_args = array_merge( $this->get_posted_data(), array( 'gre_search_listings' => null ) );
        return add_query_arg( $query_args, $base_url );
    }

    public function has_data() {
        return isset( $_REQUEST['gre_search_listings'] ) && (boolean) intval( $_REQUEST['gre_search_listings'] );
    }
}
