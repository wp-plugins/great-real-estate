<?php

/**
 * @since 1.5
 */
function gre_listing_download_add( $listing_id = 0, $file, $description = '' ) {
    $downloads = get_post_meta( $listing_id, '_gre[downloads]', true );

    $res = wp_handle_upload( $file, array( 'test_form' => false ) );
    if ( ! empty( $res['error'] ) )
        return false;

    $item = array( 'file' => _wp_relative_upload_path( $res['file'] ),
                   'type' => $res['type'],
                   'date' => current_time( 'timestamp' ),
                   'description' => stripslashes( $description ),
                   'hits' => 0 );
    $downloads[] = $item;
    update_post_meta( $listing_id, '_gre[downloads]', $downloads );

    $downloads_ = gre_get_listing_downloads( $listing_id );
    $last = array_pop( $downloads_ );
    return $last->index;
}

/**
 * @since 1.5
 */
function gre_get_listing_downloads( $listing_id = null ) {
    if ( ! $listing_id )
        $listing_id = get_the_ID();

    $upload_dir = wp_upload_dir();

    $downloads = array();
    $downloads_ = get_post_meta( $listing_id, '_gre[downloads]', true );
    $downloads_ = is_array( $downloads_ ) ? $downloads_ : array();

    foreach ( $downloads_ as $i => $d ) {
        $item = (object) $d;
        $item->index = ( $i + 1 );
        $item->filename = basename( $item->file );

        if ( gre_is_remote_file( $item->file ) ) {
            $item->path = '';
            $item->url = $item->file;
            $item->size = 0;
            $item->remote = true;
        } else {
            $item->path = trailingslashit( $upload_dir['basedir'] ) . $item->file;
            $item->url = trailingslashit( $upload_dir['baseurl'] ) . $item->file;
            $item->size = filesize( $item->path );
            $item->remote = false;
        }

        $downloads[ $i ] = $item;
    }

    return $downloads;
}

/**
 * @since 1.5
 */
function gre_get_listing_download( $listing_id, $index ) {
    $downloads = gre_get_listing_downloads( $listing_id );

    if ( ! isset( $downloads[ $index - 1 ] ) )
        return false;

    return $downloads[ $index - 1 ];
}

/**
 * @since 1.5
 */
function gre_listing_download_remove( $listing_id, $index ) {
    $downloads = get_post_meta( $listing_id, '_gre[downloads]', true );

    if ( ! isset( $downloads[ $index - 1 ] ) )
        return false;

    $upload_dir = wp_upload_dir();
    $item = $downloads[ $index - 1 ];

    if ( ! gre_is_remote_file( $item['file'] ) )
        @unlink( trailingslashit( $upload_dir['basedir'] ) . $item['file'] );

    unset( $downloads[ $index - 1 ] );

    update_post_meta( $listing_id, '_gre[downloads]', $downloads );

    return true;
}
