/********************************************************
 * Google map utility functions for Great Real Estate
 * by Roger Theriault, version 0.1.0 2008-06-15
 *
 * Requires a function gre_setupmap() defined to set the position
 * and marker overlay
 * and also requires the page needing this to invoke the google loader
 */

	
var map;

function createMarker(point,html) {
       	var marker = new GMarker(point);
       	GEvent.addListener(marker, "click", function() {
       		marker.openInfoWindowHtml(html);
       	});
       	return marker;
}


function mapinitialize() {
	jQuery(window).bind("unload", GUnload); // guard against leaks

	map = new google.maps.Map2(document.getElementById("map_canvas"));
	map.addControl(new google.maps.LargeMapControl());
	map.addControl(new google.maps.MapTypeControl());
	gre_setupmap();
}

 

