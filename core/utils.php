<?php

/**
 * @since 1.5
 */
function gre_singleton( $constructor_function ) {
    static $instances = array();

    if ( ! isset( $instances[ $constructor_function ] ) && is_callable( $constructor_function ) ) {
        $instances[ $constructor_function ] = call_user_func( $constructor_function );
    } else if ( ! isset( $instances[ $constructor_function ] ) ) {
        $instances[ $constructor_function ] = null;
    }

    return $instances[ $constructor_function ];
}


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
 * @since next-release
 */
function gre_render_template_part( $slug, $name = null, $require_once = false ) {
    ob_start();
    gre_load_template_part( $slug, $name, $require_once );
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}


/**
 * @since next-release
 */
function gre_load_template_part( $slug, $name = null, $require_once = false ) {
    if ( $located_template = gre_locate_template_part( $slug, $name ) ) {
        load_template( $located_template, $require_once );
    }
}


/**
 * @since next-release
 */
function gre_locate_template_part( $slug, $name = null ) {
    $template_directories = gre_get_template_directories( $slug );

    if ( '' !== $name ) {
        $templates = array( "{$slug}-{$name}.php", "{$slug}.php" );
    } else {
        $templates = array( "{$slug}.php" );
    }

    return gre_locate_template( $template_directories, $templates, true, false );
}


/**
 * @since next-release
 */
function gre_get_template_directories( $slug ) {
    $template_directories = array( STYLESHEETPATH, TEMPLATEPATH );

    $listings_page_templates = array(
        'great-real-estate/listing-excerpt',
        'great-real-estate/listings-page-content',
        'great-real-estate/listings-page',
    );
    $single_listing_page_templates = array(
        'great-real-estate/listing-page-content',
        'great-real-estate/listing-page',
    );

    if ( gre_get_option( 'genindex' ) && in_array( $slug, $listings_page_templates ) ) {
        array_unshift( $template_directories, GRE_FOLDER . 'theme' );
    } else if ( gre_get_option( 'genlistpage' ) && in_array( $slug, $single_listing_page_templates ) ) {
        array_unshift( $template_directories, GRE_FOLDER . 'theme' );
    } else {
        array_push( $template_directories, GRE_FOLDER . 'theme' );
    }

    return $template_directories;
}


/**
 * @since next-release
 */
function gre_locate_template( $template_directories, $template_names, $load = false, $require_once = true ) {
    $located_template = '';

    foreach ( $template_names as $template_name ) {
        foreach ( $template_directories as $template_directory ) {
            if ( ! $template_name ) {
                continue;
            }

            if ( file_exists( $template_directory . '/' . $template_name ) ) {
                $located_template = $template_directory . '/' . $template_name;
                break 2;
            }
        }
    }

    return $located_template;
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
