jQuery(function($) {
    var $startButton = $('.gre-admin-migrate-page .start-upgrade');
    var $pauseButton = $('.gre-admin-migrate-page a.pause-upgrade');
    var $progressArea = $('.gre-admin-migrate-page textarea#manual-upgrade-progress');
    var inProgress = false;

    var makeProgress = function() {
        if (!inProgress)
            return;

        var data = { action: 'gre-upgrade-migration' };
        $.get(ajaxurl, data, function(response) {
            var currentText = $progressArea.val();
            var newLine = (response.ok ? "*" : "!") + " " + ( response.message ? response.message : 'Working...' );

            $progressArea.val(currentText + newLine + "\n");
            $progressArea.scrollTop($progressArea[0].scrollHeight - $progressArea.height());

            if (response.done) {
                $( 'div.step-upgrade' ).fadeOut(function() { $('div.step-done').fadeIn() });
            } else {
                makeProgress();
            }
        }, 'json');
    };

    $startButton.click(function(e) {
        e.preventDefault();

        if (inProgress)
            return;

        inProgress = true;
        makeProgress();
    });

    $pauseButton.click(function(e) {
        e.preventDefault();
        inProgress = false;
    });

});
