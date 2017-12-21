$(function () {
    var currentFaucetSlug;
    var paymentProcessorSlug;
    var userSlug;

    init();

    //Set iframe src to first faucet in array
    $('#first_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-first-faucet',
            {
                paymentProcessorSlug : paymentProcessorSlug,
                faucetSlug : currentFaucetSlug
            }
        );

        generateFaucet(route);
    });

    $('#next_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-next-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug,
                faucetSlug : currentFaucetSlug
            }
        );
        generateFaucet(route);
    });

    $('#previous_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-previous-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug,
                faucetSlug : currentFaucetSlug
            }
        );
        generateFaucet(route);
    });

    $('#last_faucet').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-last-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug
            }
        );
        generateFaucet(route);
    });

    $('#reload_current').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug,
                faucetSlug : currentFaucetSlug
            }
        );
        generateFaucet(route);
    });

    $('#random').click(function (event) {
        event.preventDefault();

        var route = laroute.route(
            'user.payment-processor-random-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug
            }
        );
        generateFaucet(route);
    });

    function init()
    {
        paymentProcessorSlug = $("#title").data("payment-processor-slug");
        userSlug = $("#title").data("user-slug");
        var route = laroute.route(
            'user.payment-processor-first-faucet',
            {
                userSlug: userSlug,
                paymentProcessorSlug : paymentProcessorSlug
            }
        );
        generateFaucet(route);
    }

    function generateFaucet(apiUrl)
    {
        var iframeUrl;
        var iframe = $('#rotator-iframe');
        var ajaxErrorContent = $('#show-ajax-data-error-content');
        var noIframeContent = $('#show-no-iframe-content');

        $.ajax(apiUrl, {
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
                            'users.faucets',
                            { userSlug : userSlug }
                        );

                        editFaucetLink.attr('href', editFaucetRoute);
                    }

                    noIframeContent.show();
                }

                var currentFaucetRoute = laroute.route(
                    'users.faucets.show',
                    {
                        userSlug: userSlug,
                        faucetSlug : currentFaucetSlug
                    }
                );

                $('#current').attr('href', currentFaucetRoute);

                var faucetListsRoute = laroute.route(
                    'users.payment-processors.faucets',
                    {
                        userSlug: userSlug,
                        paymentProcessorSlug : paymentProcessorSlug
                    }
                );

                $('#list_of_faucets').attr('href', faucetListsRoute);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (
                    jqXHR.responseJSON.status !== null &&
                    typeof jqXHR.responseJSON.status !== 'undefined' &&
                    jqXHR.responseJSON.status === 'error'
                ) {
                    iframe.hide();
                    noIframeContent.hide();
                    var errorCode = $('#error-code');
                    var errorMessage = $('#error-message');
                    var sentryId = $('#sentry-id');
                    if (
                        typeof errorCode !== 'undefined' &&
                        typeof errorMessage !== 'undefined' &&
                        typeof sentryId !== 'undefined') {
                        errorCode.text(jqXHR.responseJSON.code);
                        errorMessage.text(jqXHR.responseJSON.message);
                        sentryId.text(jqXHR.responseJSON.sentryID);
                        ajaxErrorContent.show();
                    }
                }
            }
        });
    }
});

