/********************************************************
 * Google map utility functions for Great Real Estate
 * by Roger Theriault, version 0.1.0 2008-06-15
 *
 * Requires a function gre_setupmap() defined to set the position
 * and marker overlay
 * and also requires the page needing this to invoke the google loader
 */

/*
 * Changelog:
 * [2008-06-27] added functions to handle XML population of featured map
 * [2008-06-27] changed id of map canvas from map_canvas to gre_map_canvas
 *              to reduce conflicts
 *
 */

	
var gre_map;
var gre_multi_map;
var gre_customIcons = [];

function gre_setup_custom_icons() {
    var iconForSale;
    var iconForRent;
    iconForSale = new GIcon();
    iconForSale.image = 'http://labs.google.com/ridefinder/images/mm_20_green.png';
    iconForSale.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconForSale.iconSize = new GSize(12, 20);
    iconForSale.shadowSize = new GSize(22, 20);
    iconForSale.iconAnchor = new GPoint(6, 20);
    iconForSale.infoWindowAnchor = new GPoint(5, 1);

    iconForRent = new GIcon(); 
    iconForRent.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
    iconForRent.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconForRent.iconSize = new GSize(12, 20);
    iconForRent.shadowSize = new GSize(22, 20);
    iconForRent.iconAnchor = new GPoint(6, 20);
    iconForRent.infoWindowAnchor = new GPoint(5, 1);

    iconSold = new GIcon(); 
    iconSold.image = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
    iconSold.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconSold.iconSize = new GSize(12, 20);
    iconSold.shadowSize = new GSize(22, 20);
    iconSold.iconAnchor = new GPoint(6, 20);
    iconSold.infoWindowAnchor = new GPoint(5, 1);

    gre_customIcons["sold"] = iconSold;
    gre_customIcons["forrent"] = iconForRent;
    gre_customIcons["forsale"] = iconForSale;
}

function gre_createMarker(point,html) {
       	var marker = new GMarker(point);
       	GEvent.addListener(marker, "click", function() {
       		marker.openInfoWindowHtml(html);
       	});
       	return marker;
}

function gre_createMarkerCustom(point, name, address, type, ref, img) {
      var marker = new GMarker(point, gre_customIcons[type]);
      var html = img + '<strong>' + name + "</a></strong> <br />" + address +
	'<br />' + '<a href="' + ref + '">listing info</a>' 
      GEvent.addListener(marker, 'click', function() {
        marker.openInfoWindowHtml(html);
      });
      return marker;
    }



function gre_mapinitialize() {
	jQuery(window).bind("unload", GUnload); // guard against leaks

	gre_map = new google.maps.Map2(document.getElementById("gre_map_canvas"));
	gre_map.addControl(new google.maps.LargeMapControl());
	gre_map.addControl(new google.maps.MapTypeControl());
	gre_setupmap();
}

// set up map of all properties in feed
function gre_initmultimap() {
	jQuery(window).bind("unload", GUnload); // guard against leaks

	gre_setup_custom_icons();

	gre_multi_map = new google.maps.Map2(document.getElementById("gre_map_multi"));
	gre_multi_map.addControl(new google.maps.LargeMapControl());
	gre_multi_map.addControl(new google.maps.MapTypeControl());
	var temp_point = new google.maps.LatLng(0,0);
	gre_multi_map.setCenter(temp_point, 5);
	GDownloadUrl("/feed/googlemaps/", gre_addmarkers );
}

function gre_addmarkers( data ) {
	var xml = GXml.parse(data);
	var markers = xml.documentElement.getElementsByTagName("marker");
	var maxLat = 0;
	var minLat = 91;
	var maxLng = -181;
	var minLng = 181;
	for (var i = 0; i < markers.length; i++) {
		var name = markers[i].getAttribute("name");
		var address = markers[i].getAttribute("address");
		address = address.replace(/,/,'<br />');
		var type = markers[i].getAttribute("type");
		var ref = markers[i].getAttribute("ref");
		var info = markers[i].getElementsByTagName("info")[0].childNodes[0].nodeValue;
		var lat = parseFloat(markers[i].getAttribute("lat"));
		var lng = parseFloat(markers[i].getAttribute("lng"));
		var point = new GLatLng(lat,lng);
		if (lat > maxLat) maxLat = lat;
		if (lat < minLat) minLat = lat;
		if (lng > maxLng) maxLng = lng;
		if (lng < minLng) minLng = lng;
		var marker = gre_createMarkerCustom(point, name, address, type, ref, info);
		gre_multi_map.addOverlay(marker);
	}
	var ctrLat = minLat + (maxLat - minLat)/2;
	var ctrLng = minLng + (maxLng - minLng)/2;
	var new_point = new google.maps.LatLng(ctrLat,ctrLng);
	gre_multi_map.setCenter(new_point, 11);
}


