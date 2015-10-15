<?php

function gre_listings_sql_query_builder() {
    return new GRE_Listings_SQL_Query_Builder( $GLOBALS['wpdb'] );
}

class GRE_Listings_SQL_Query_Builder extends GRE_SQL_Query_Builder {

    public function get_sql( $params ) {
        return parent::get_sql( apply_filters( 'gre-listings-query-params', $this->prepare_params( $params ) ) );
    }

    protected function prepare_params( $params ) {
        return wp_parse_args( $params, array(
            'fields' => '*',

            'raw' => null,

            'price' => null,
            'min_price' => null,
            'max_price' => null,

            'bathrooms' => null,
            'bedrooms' => null,

            'property_status' => null,
            'property_type' => null,
        ) );
    }

    protected function build_select_clause( $params ) {
        if ( $params['fields'] == 'count' ) {
            $fields = 'COUNT( DISTINCT posts.ID )';
        } else {
            $fields = $params['fields'] . ', posts.`post_content` as content';
        }

        $sql = "SELECT $fields
                FROM <listings-table> AS listings
                INNER JOIN <posts-table> AS posts
                ON ( listings.pageid = posts.ID )
                <join>";

        return $sql;
    }

    protected function build_where_clause( $params ) {
        $conditions = array(
            "posts.`post_type` = 'page'",
            $this->build_price_condition( $params ),
            $this->build_bedrooms_condition( $params ),
            $this->build_bathrooms_condition( $params ),
            $this->build_property_status_condition( $params ),
            $this->build_property_type_condition( $params ),
        );

        return sprintf( 'WHERE %s', implode( ' AND ', array_filter( $conditions ) ) );
    }

    private function build_price_condition( $params ) {
        $conditions = array();

        if ( strlen( $params['price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`listprice` = %d', $params['price'] );
        }

        if ( strlen( $params['min_price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`listprice` >= %d', $params['min_price'] );
        }

        if ( strlen( $params['max_price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`listprice` <= %d', $params['max_price'] );
        }

        return $this->group_conditions( $conditions, 'AND' );
    }

    protected function build_bedrooms_condition( $params ) {
        return $this->build_simple_condition( $params, 'bedrooms', 'listings.`bedrooms` = %d' );
    }

    protected function build_bathrooms_condition( $params ) {
        return $this->build_simple_condition( $params, 'bathrooms', 'listings.`bathrooms` = %d' );
    }

    protected function build_property_status_condition( $params ) {
        if ( isset( $params['property_status'] ) ) {
            $search_values = (array) $params['property_status'];
        } else {
            $search_values = array();
        }

        $property_status = array();

        foreach ( $search_values as $search_value ) {
            switch ( $search_value ) {
                case 'for-sale':
                    $property_status[] = RE_FORSALE;
                    break;
                case 'pending-sale':
                    $property_status[] = RE_PENDINGSALE;
                    break;
                case 'sold':
                    $property_status[] = RE_SOLD;
                    break;
                case 'for-rent':
                    $property_status[] = RE_FORRENT;
                    break;
                case 'pending-lease':
                    $property_status[] = RE_PENDINGLEASE;
                    break;
                case 'rented':
                    $property_status[] = RE_RENTED;
                    break;
            }
        }

        return $this->build_condition_with_in_clause( 'listings.`status`', $property_status );
    }

    protected function build_property_type_condition( $params ) {
        $query = $this->get_meta_sql(
            'property_type_meta',
            array(
                'meta_query' => array(
                    'key' => '_gre[property-type]',
                    'value' => $params['property_type'],
                    'compare' => '=',
                    'type' => 'CHAR',
                )
            ),
            'post',
            'posts',
            'ID'
        );

        $this->add_join_clause( $query['join'] );

        return $query['where'];
    }

    protected function build_limit_clause( $params ) {

    }

    protected function build_order_clause( $params ) {

    }

    protected function prepare_sql( $sql ) {
        $sql = parent::prepare_sql( $sql );

        $sql = str_replace( '<listings-table>', $this->db->gre_listings, $sql );
        $sql = str_replace( '<posts-table>', $this->db->posts, $sql );

        return $sql;
    }
}
