<?php

function gre_listing_geolocation_service() {
    return new GRE_Listing_Geolocation_Service( gre_plugin()->settings );
}

class GRE_Listing_Geolocation_Service {

    private $meta_name = '_gre[google-maps][geolocation]';

    private $settings;

    public function __construct( $settings ) {
        $this->settings = $settings;
    }

    public function update_listing_geolocation( $listing_id ) {
        $address = $this->get_listing_address( $listing_id );

        if ( ! empty( $address ) ) {
            $this->update_listing_geolocation_from_address( $listing_id, $address );
        } else {
            $this->clear_listing_geolocation( $listing_id );
        }
    }

    private function get_listing_address( $listing_id ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->gre_listings} WHERE pageid = %d LIMIT 1";
        $listing_data = $wpdb->get_row( $wpdb->prepare( $sql, $listing_id ) );

        $address_fields = array_filter( array(
            'address' => $listing_data->address,
            'city' => $listing_data->city,
            'state' => $listing_data->state,
            'postal-code' => $listing_data->postcode,
        ) );

        if ( empty( $address_fields ) ) {
            return '';
        }

        $address = '<address>, <city>, <state>, <postal-code>';
        $address = str_replace( '<address>', $address_fields['address'], $address );
        $address = str_replace( '<city>', $address_fields['city'], $address );
        $address = str_replace( '<state>', $address_fields['state'], $address );
        $address = str_replace( '<postal-code>', $address_fields['postal-code'], $address );

        return $address;
    }

    private function update_listing_geolocation_from_address( $listing_id, $address ) {
        $location = get_post_meta( $listing_id, $this->meta_name, true );

        if ( is_object( $location ) && $location->hash == $this->get_address_hash( $address ) ) {
            return;
        }

        $location = $this->get_address_geolocation( $address );

        if ( ! is_object( $location ) ) {
            return;
        }

        update_post_meta( $listing_id, $this->meta_name, $location );
    }

    private function get_address_hash( $address ) {
        return wp_hash( $address );
    }

    private function get_address_geolocation( $address ) {
        $url_params = urlencode_deep( array(
            'key' => $this->settings->get( 'googleAPIkey' ),
            'sensor' => false,
            'address' => $address,
        ) );

        $google_maps_url = add_query_arg( $url_params, 'https://maps.googleapis.com/maps/api/geocode/json?' );
        $request_response = wp_remote_get( $google_maps_url, array( 'timeout' => 15 )  );

        if ( is_wp_error( $request_response ) ) {
            return false;
        }

        $response = json_decode( $request_response['body'] );

        if ( $response->status != 'OK' || ! isset( $response->results[0]->geometry->location ) ) {
            return false;
        }

        $location = $response->results[0]->geometry->location;
        $location->hash = $this->get_address_hash( $address );

        return $location;
    }

    private function clear_listing_geolocation( $listing_id ) {
        delete_post_meta( $listing_id, $this->meta_name );
    }
}
