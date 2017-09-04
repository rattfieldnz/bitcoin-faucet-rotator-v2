$(function(){
    var currentFaucetSlug;
    init();

    //Set iframe src to first faucet in array
    $('#first_faucet').click(function(event) {
        event.preventDefault();
        generateFaucet('/api/v1/first-faucet');

        var route = laroute.route(
            'faucets.first-faucet'
        );
        generateFaucet(route);
    });

    $('#next_faucet').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'faucets.next-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#previous_faucet').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'faucets.previous-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#last_faucet').click(function(event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.last-faucet',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#reload_current').click(function(event) {
        event.preventDefault();

        var route = laroute.route(
            'faucets.show',
            { slug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#random').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'faucets.random-faucet'
        );
        generateFaucet(route);
    });

    function init(){

        var route = laroute.route(
            'faucets.first-faucet'
        );
        generateFaucet(route);
    }

    function generateFaucet(apiUrl){
        var iframeUrl;

        $.ajax(apiUrl, {
            success: function (data) {
                iframeUrl = data.data.url;
                currentFaucetSlug = data.data.slug;
                var canShowInIframes = data.data.can_show_in_iframes;
                var iframe = $('#rotator-iframe');
                iframe.attr('src', '');
                var noIframeContent = $('#show-no-iframe-content');

                if(canShowInIframes === true){
                    iframe.show();
                    noIframeContent.hide();
                    iframe.attr('src', iframeUrl);
                } else {
                    iframe.hide();
                    noIframeContent.find('#faucet-link').attr('href', iframeUrl);
                    noIframeContent.find('#faucet-link').attr('title', 'View "' + data.data.name + '" faucet in a new window');
                    noIframeContent.show();
                }

                $('#current').attr('href', '/faucets/' + currentFaucetSlug);
            }
        });
    }
});