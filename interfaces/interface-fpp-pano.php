<?php

// technically an interface to a non-existent plugin so far...
// the whole shebang is right here
// if (!function_exists('fpp_showpanolink')) return;

function greatrealestate_fpppanolink($panolist) {
	fpp_showpanolink($panolist);
}

function fpp_showpanolink($ids = '') {
	if (!$ids) return;
	$allids = explode(',',$ids);
	foreach ($allids as $id) {
		# use this in case JS shut off
		$link = wp_get_attachment_url($id);
		$rel_link = $link;
		#$rel_link = wp_make_link_relative($link);
		$rel_link = str_replace(".mov","",$rel_link);
		if(!$rel_link) next;
		$title = get_the_title($id);
		echo '<a class="vrchoice" id="pano_click_'.$id.'" href="' . $link . '" title="View '.$title.
			'">'.$title.  '</a>';
		if (!$starthere) {
			$starthere = 'set_pano("'.  $rel_link.'","'.$title.'"); ';
		}
		// TODO - CDATA
		$funcs .= <<< EOF
   var pano_evt_$id = document.getElementById("pano_click_$id");
   if (pano_evt_${id}.addEventListener) {
   	pano_evt_${id}.addEventListener("click", gopano_$id, false);
   } else if ( pano_evt_${id}.attachEvent ) {
	   // dang - make sure it is "onclick" or else
	   pano_evt_${id}.attachEvent('onclick', gopano_$id);
   } else {
	   pano_evt_${id}.onclick = gopano_$id;
   }
   function gopano_$id(evt) {
	set_pano("${rel_link}","${title}");
	if (evt.preventDefault) {
		evt.preventDefault();
	} else {
		evt.cancelBubble = true; // IE Quirk
		evt.returnValue = false; // IE Quirk
		window.event.returnValue = false;
		return false;
	}
   }
EOF;
	}

	$myfolder = dirname(plugin_basename(__FILE__));
	$siteurl = get_option('siteurl');
	#$siteurl = "";
	$fpp_dir = $siteurl . "/wp-content/plugins/" .
		$myfolder ."/FPP";
	$fpp_dir = wp_make_link_relative($fpp_dir);

	echo <<< EOF
<div class="vrwindow" id="panodiv">
<p>This content requires the Adobe Flash Player 9 plugin. Please, visit <a href="http://www.adobe.com/go/getflashplayer">adobe.com</a> and install it to view this content.
</p>
<p>If you have disabled JavaScript, please enable it for this site for a better experience.</p>
</div>
EOF;
}


?>
