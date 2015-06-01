/********************************************************
 * Google map utility functions for Great Real Estate
 * by Roger Theriault, version 0.1.0 2008-06-15
 */

/*
 * Changelog:
 * [2008-06-27] added functions to handle XML population of featured map
 * [2008-06-27] changed id of map canvas from map_canvas to gre_map_canvas
 *              to reduce conflicts
 *
 */

if ( typeof jQuery !== 'undefined' ) {
    (function( $ ) {
        if ( typeof google === 'undefined' ) {
            return;
        }

        $(function() {
            if ( typeof gre_listing_map_info === 'undefined' ) {
                return;
            }

            $( '#gre_map_canvas' ).each(function() {
                var map, center, marker, info;

                center = new google.maps.LatLng( gre_listing_map_info.latitude, gre_listing_map_info.longitude );
                options = {
                    zoom: 8,
                    center: center,
                    mapTypeControl: true,
                    panControl: true,
                    zoomControl: true
                };

                map = new google.maps.Map( this, options );

                marker = new google.maps.Marker({
                    position: center,
                    map: map
                });

                info = new google.maps.InfoWindow({
                    content: gre_listing_map_info.info_window_content
                });

                google.maps.event.addListener( marker, 'click', function() {
                    info.open(map, marker);
                } );
            });
        });

        $(function(){
            $( '#gre_map_multi' ).each(function() {
                var icons, map;

                icons = {
                    forsale: createGoogleMapsIcon( 'http://labs.google.com/ridefinder/images/mm_20_green.png' ),
                    forrent: createGoogleMapsIcon( 'http://labs.google.com/ridefinder/images/mm_20_blue.png' ),
                    sold: createGoogleMapsIcon( 'http://labs.google.com/ridefinder/images/mm_20_red.png' )
                }

                map = new google.maps.Map( this, {
                    zoom: 8,
                    center: new google.maps.LatLng( 0, 0 ),
                    mapTypeControl: true,
                    panControl: true,
                    zoomControl: true
                } );

                loadListingsFromXML( '/feed/googlemaps/' ).done(function( listings ) {
                    addListingsMarkers( map, listings, icons );
                });
            });

            function createGoogleMapsIcon( image ) {
                return {
                    url: image,
                    size: new google.maps.Size( 12, 20 ),
                    anchor: new google.maps.Point( 6, 20 )
                };
            }

            function loadListingsFromXML( url ) {
                var deferred = $.Deferred();

                $.get( url, function( data ) {
                    var listings = [], marker;

                    $( data ).find( 'marker' ).each(function() {
                        var $marker = $( this );

                        listings.push({
                            name: $marker.attr( 'name' ),
                            address: $marker.attr( 'address' ).replace( '/,/', '<br/>' ),
                            type: $marker.attr( 'type' ),
                            ref: $marker.attr( 'ref' ),
                            info: $marker.find( 'info' ).text(),
                            lat: parseFloat( $marker.attr( 'lat' ) ),
                            lng: parseFloat( $marker.attr( 'lng' ) ),
                        });
                    });

                    deferred.resolve( listings );
                } );

                return deferred;
            }

            function addListingsMarkers( map, listings, icons ) {
                var marker, info, mapLat, mapLng,
                    minLat = Infinity,
                    minLng = Infinity,
                    maxLat = -Infinity,
                    maxLng = -Infinity;

                $.each( listings, function( index, listing ) {
                    marker = new google.maps.Marker({
                        icon: icons[ listing.type ],
                        position: new google.maps.LatLng( listing.lat, listing.lng ),
                        map: map
                    });

                    info = new google.maps.InfoWindow({
                        content: listing.info + '<strong>' + listing.name + "</a></strong> <br />" + listing.address + '<br />' + '<a href="' + listing.ref + '">listing info</a>'
                    });

                    google.maps.event.addListener( marker, 'click', function() {
                        info.open(map, marker);
                    } );

                    minLat = Math.min( minLat, listing.lat );
                    minLng = Math.min( minLng, listing.lng );
                    maxLat = Math.max( maxLat, listing.lat );
                    maxLng = Math.max( maxLng, listing.lng );
                } );

                mapLat = minLat + ( maxLat - minLat ) * 0.5;
                mapLng = minLng + ( maxLng - minLng ) * 0.5;

                map.setCenter( new google.maps.LatLng( mapLat, mapLng ) );
                // TODO: calculate zoom level necessary to show most or all markers
                map.setZoom( 12 );
            }
        });
    })( jQuery );
}
