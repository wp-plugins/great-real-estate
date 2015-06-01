jQuery(function($) {

    var $gres_debugging = $('#gres-debugging');
    var $tab_selector = $('.tab-selector', $gres_debugging);

	$('#wpbody .wrap').before('<div id="gres-debugging-placeholder"></div>');
	$('#gres-debugging-placeholder').replaceWith($('#gres-debugging'));

    $tab_selector.find('li a').click(function(e) {
        e.preventDefault();

        var dest = '#gres-debugging-tab-' + $(this).attr('href').replace('#', '');

        $tab_selector.find('li').removeClass('active');
        $(this).parent('li').addClass('active');
        $gres_debugging.find('.tab').hide();
        $(dest).show();
    }).first().click();

    $gres_debugging.find('table tr').click(function(e) {
        var $extradata = $(this).find('.extradata');

        if ( $extradata.length > 0 )
            $extradata.toggle();
    });

});
