<?php

/**
 * @since next-release
 */
function gre_query() {
    return gre_singleton( 'gre_query_constructor' );
}

/**
 * @since next-release
 * @access private
 */
function gre_query_constructor() {
    return new GRE_Query();
}

/**
 * @since next-release
 */
class GRE_Query {

    private $queried_objects = array();
    private $vars = array();

    public function set_var( $name, $value ) {
        $this->vars[ $name ] = $value;
    }

    public function get_var( $name ) {
        if ( isset( $this->vars[ $name ] ) ) {
            return $this->vars[ $name ];
        } else {
            return false;
        }
    }

    public function set_queried_objects( $queried_objects ) {
        $this->queried_objects = $queried_objects;
    }

    public function get_queried_objects() {
        return $this->queried_objects;
    }
}
