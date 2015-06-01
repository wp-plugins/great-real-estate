jQuery(function($) {

    // {{ Downloads.

    $( '#gre-listing-downloads ul.downloads' ).on( 'click', 'a.delete', function(e) {
        e.preventDefault();

        var $li = $(this).parents('li');

        $.ajax( {
            url: ajaxurl,
            data: { 'action': 'gre-listing-file-delete',
                    'listing': $('#gre-listing-downloads').attr('data-listing-id'),
                    'index': $li.attr('data-download-index') },
            dataType: 'json',
            type: 'POST',
            success: function( res ) {
                if ( ! res.ok )
                    return;

                $li.fadeOut('fast', function() {
                    $li.remove();

                    if ( 0 == $( '#gre-listing-downloads ul.downloads li' ).length )
                        $( '#gre-listing-downloads .no-downloads-msg' ).show();
                });
            }
        } );
    });

    var $input = $( '#gre-listing-downloads-file' );
    var $button = $( '.gre-listing-downloads-add .upload-button' );
    $input.fileupload({
        url: $input.attr( 'data-url' ),
        dataType: 'json',
        singleFileUploads: true,
        paramName: 'file',
        autoUpload: true,
        formData: function() {
            return [ {name: 'description', value: $( '#gre-listing-downloads-description' ).val() } ];
        },
        add: function( e, data ) {
            if ( data.files ) {
                $( '#gre-listing-downloads-filename' ).text( data.files[0].name );
            } else {
                $( '#gre-listing-downloads-filename' ).text('');
            }

            $button.unbind( 'click' );
            $button.click(function(e) {
                $button.val( $button.attr( 'data-i18n-working' ) );
                $button.attr( 'disabled', 'disabled' );
                data.submit();
            });
        },
        done: function( e, data ) {
            $button.val( $button.attr( 'data-i18n-def' ) );
            $button.removeAttr( 'disabled' );
            $( '#gre-listing-downloads-filename' ).text('');
            $( '#gre-listing-downloads-description' ).val('');

            var res = data.result;

            if ( ! res )
                return;

            if ( ! res.ok )
                return;

            tb_remove();

            var html = res.html;
            $( '#gre-listing-downloads .no-downloads-msg' ).hide();
            $( '#gre-listing-downloads ul.downloads' ).append( res.html );
        }
    });

    // }}

});
