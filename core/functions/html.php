<?php

function gre_html_attributes( $attributes ) {
    $output = array();
    foreach ( $attributes as $name => $value ) {
        $output[] = sprintf( '%s="%s"', $name, $value );
    }

    return implode( ' ', $output );
}

function gre_html_label( $params ) {
    $params = wp_parse_args( $params, array(
        'attributes' => array(
            'for' => null,
        ),
        'text' => null,
    ) );

    $element = "<label<attributes>><text></label>\n";
    $element = str_replace( '<attributes>', rtrim( ' ' . gre_html_attributes( $params['attributes'] ) ), $element );
    $element = str_replace( '<text>', $params['text'], $element );

    return $element;
}

function gre_html_select( $params ) {
    $params = wp_parse_args( $params, array(
        'attributes' => array(
            'id' => null,
            'name' => null,
            'tabindex' => null,
        ),
        'options' => array(),
        'selected' => null,
    ) );

    $element = "<select<attributes>>\n\t<options>\n</select>\n";
    $element = str_replace( '<attributes>', rtrim( ' ' . gre_html_attributes( $params['attributes'] ) ), $element );
    $element = str_replace( '<options>', gre_html_options( gre_array_extract( $params, array( 'options', 'selected' ) ) ), $element );

    return $element;
}

function gre_html_options( $params ) {
    $params = wp_parse_args( $params, array(
        'options' => array(),
        'selected' => null,
    ) );

    $options = array();

    foreach ( $params['options'] as $value => $label ) {
        if ( $value == $params['selected'] ) {
            $options[] = sprintf( '<option value="%s" selected="selected">%s</option>', $value, $label );
        } else {
            $options[] = sprintf( '<option value="%s">%s</option>', $value, $label );
        }
    }

    return implode( "\n", $options );
}
