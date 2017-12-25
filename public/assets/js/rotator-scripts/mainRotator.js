
var currentFaucetSlug;

$(function () {
    //var currentFaucetSlug;
    init();

    //Set iframe src to first faucet in array
    $('#first_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.first-faucet'
        );
        generateFaucet(route);
    });

    $('#next_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.next-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#previous_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.previous-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#last_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.last-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#reload_current').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.show',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#random').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.random-faucet'
        );
        generateFaucet(route);
    });

    function init()
    {

        var route = laroute.route(
            'faucets.first-faucet'
        );
        generateFaucet(route);
    }

    function generateFaucet(apiUrl)
    {
        var iframeUrl;
        var iframe = $('#rotator-iframe');
        var ajaxErrorContent = $('#show-ajax-data-error-content');
        var noIframeContent = $('#show-no-iframe-content');

        var getFaucet = function() {
            $.ajax({
                url: apiUrl,
                timeout: 5000,
                success: function (data) {
                    iframe.attr('src', '');

                    noIframeContent.hide();
                    ajaxErrorContent.hide();
                    var editFaucetLink = $('#edit-faucet-link');

                    iframeUrl = data.data.url;
                    currentFaucetSlug = data.data.slug;

                    if (Boolean(data.data.can_show_in_iframes) === true) {
                        iframe.show();
                        noIframeContent.hide();
                        iframe.attr('src', iframeUrl);
                    } else {
                        iframe.hide();
                        noIframeContent.find('#faucet-link').attr('href', iframeUrl);
                        noIframeContent.find('#faucet-link').attr('title', 'View "' + data.data.name + '" faucet in a new window');

                        if (typeof editFaucetLink !== 'undefined') {
                            var editFaucetRoute = laroute.route(
                                'faucets.edit',
                                {slug: currentFaucetSlug}
                            );

                            editFaucetLink.attr('href', editFaucetRoute);
                        }

                        noIframeContent.show();
                    }

                    var currentFaucetRoute = laroute.route(
                        'faucet.show',
                        {slug: currentFaucetSlug}
                    );

                    $('#current').attr('href', currentFaucetRoute);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var message = typeof errorThrown !== 'undefined' ?
                        errorThrown : "Error: " + errorThrown;
                    if (
                        (textStatus === 'timeout' || errorThrown === 'timeout') ||
                        (jqXHR.responseJSON !== null && typeof jqXHR.responseJSON !== 'undefined') &&
                        (
                            (jqXHR.responseJSON.status !== null && typeof jqXHR.responseJSON.status !== 'undefined') &&
                            (
                                String(jqXHR.responseJSON.status).charAt(0) === '3' ||
                                String(jqXHR.responseJSON.status).charAt(0) === '4' ||
                                String(jqXHR.responseJSON.status).charAt(0) === '5'
                            )
                        )
                    ) {
                        iframe.hide();
                        noIframeContent.hide();
                        var errorCode = $('#error-code');
                        var errorMessage = $('#error-message');
                        if (
                            typeof errorCode !== 'undefined' && typeof errorMessage !== 'undefined') {
                            errorCode.text(textStatus);
                            errorMessage.text(message);
                            ajaxErrorContent.show();
                        }

                        getFaucet();
                    }
                }
            });
        };

        getFaucet();
    }
});