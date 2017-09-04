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

                $('#rotator-iframe').attr('src', iframeUrl);
                $('#current').attr('href', '/faucets/' + currentFaucetSlug);
            }
        });
    }
});