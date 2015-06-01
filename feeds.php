<?php

function gre_get_active_feeds() {
    $feeds_slugs = array( 'trulia', 'yahoomedia', 'zillow', 'googlemaps', 'googlebase' );

    $active_feeds = array();
    foreach ( $feeds_slugs as $feed_slug ) {
        if ( gre_get_option( 'enable-' . $feed_slug . '-feed' ) ) {
            $active_feeds[] = $feed_slug;
        }
    }

    return $active_feeds;
}

class GRE_RSS_Feeds {

    function register_feeds( $feeds ) {
        foreach ( $feeds as $feed_slug ) {
            $wrapper = new GRE_RSS_Feed_Display_Wrapper( $feed_slug );
            add_feed( $feed_slug, array( $wrapper, 'display' ) );
        }
    }

}

class GRE_RSS_Feed_Display_Wrapper {

    private $feed_slug = '';

    function __construct( $feed_slug ) {
        $this->feed_slug = $feed_slug;
    }

    function display() {
        if ( $template = locate_template( 'feed-' . $this->feed_slug . '.php' ) ) {
            load_template( $template );
        } else {
            load_template( GRE_FOLDER . 'copytotemplatedir/feed-' . $this->feed_slug . '.php' );
        }
    }

}

