<?php

/**
 * @deprecated since 1.5. No alternative for now. This is a compatibility function.
 */
function downloadmanager_showdownloadlink( $download_ids = '' ) {
    $download_ids = trim( $download_ids );

    if ( ! $download_ids )
        return '';

    $download_ids = explode( ',', $download_ids );
    $downloads = array();

    foreach ( $download_ids as $d_id ) {
        $d_id = explode( '|', $d_id );
        $listing_id = $d_id[0];
        $index = $d_id[1];

        $download = gre_get_listing_download( $listing_id, $index );
        if ( $download )
            $downloads[] = $download;
    }

    if ( ! $downloads )
        return '';

    return gre_render( GRE_FOLDER . 'core/templates/listing-downloads.tpl.php', array( 'downloads' => $downloads ) );

/*
 * <p><img src="http://192.168.13.38/wp-content/plugins/wp-downloadmanager/images/ext/%FILE_ICON%" alt="" title="" style="vertical-align: middle;" />&nbsp;&nbsp;<strong><a href="%FILE_DOWNLOAD_URL%">%FILE_NAME%</a></strong><br /><strong>&raquo; %FILE_SIZE% - %FILE_HITS% hits - %FILE_DATE%</strong><br />%FILE_DESCRIPTION%</p>*/        
}

