<?php

function gre_array_extract( $source, $keys ) {
    $extracted = array();

    foreach ( $keys as $key ) {
        if ( isset( $source[ $key ] ) ) {
            $extracted[ $key ] = $source[ $key ];
        }
    }

    return $extracted;
}
