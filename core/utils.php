<?php

/**
 * @since 1.5
 */
function gre_render( $template, $vars = array(), $echo_output = false ) {
    if ($vars) {
        extract($vars);
    }

    ob_start();
    include($template);
    $html = ob_get_contents();
    ob_end_clean();

    if ($echo_output)
        echo $html;

    return $html;
}


/**
 * @since 1.5
 */
function gre_is_remote_file( $path ) {
    if( strpos($path, 'http://') === false && strpos($path, 'https://') === false  && strpos($path, 'ftp://') === false)
        return false;

    return true;
}


/**
 * @since 1.5
 */
function gre_render_template( $template, $params ) {
    if ( file_exists( $template ) ) {
        ob_start();
        extract( $params );
        include( $template );
        $output = ob_get_contents();
        ob_end_clean();
    } else {
        $output = sprintf( 'Template %s not found!', str_replace( GRE_FOLDER, '', $template ) );
    }

    return $output;
}

/**
 * @since 1.5
 */
function gre_render_links( $links ) {
    return implode( '&nbsp;|&nbsp;', array_filter( $links ) );
}

/**
 * @since 1.5
 */
function gre_return_to_listings_link() {
    if ( $listings_page = gre_get_option( 'pageforlistings' ) ) {
        $url = get_permalink( $listings_page );
        $link_template = '<a href="%s" class="return-to-listings">%s</a>';

        return sprintf( $link_template, $url, __( '← Return to Listings', 'greatrealestate' ) );
    } else {
        return '';
    }
}

/**
 * @since 1.5
 */
function gre_edit_post_link() {
    ob_start();
    edit_post_link( __( 'Edit this entry', 'greatrealestate' ), '', '' );
    $edit_post_link = ob_get_contents();
    ob_end_clean();

    return $edit_post_link;
}
