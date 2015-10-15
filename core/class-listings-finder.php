<?php

function gre_listings_finder() {
    return new GRE_Listings_Finder( gre_listings_sql_query_builder(), $GLOBALS['wpdb'] );
}

class GRE_Listings_Finder {

    private $query_builder;
    private $db;

    public function __construct( $query_builder, $db ) {
        $this->query_builder = $query_builder;
        $this->db = $db;
    }

    public function find( $params ) {
        return $this->db->get_results( $this->query_builder->get_sql( $params ) );
    }

    public function count( $params ) {
        $params['fields'] = 'count';

        return $this->db->get_var( $this->query_builder->get_sql( $params ) );
    }
}
