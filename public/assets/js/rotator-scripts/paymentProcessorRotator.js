$(function(){
    var currentFaucetSlug;
    var paymentProcessorSlug;

    init();

    //Set iframe src to first faucet in array
    $('#first_faucet').click(function(event) {
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.first-faucet',
            { paymentProcessorSlug : paymentProcessorSlug }
        );

        generateFaucet(route);
    });

    $('#next_faucet').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.next-faucet',
            { paymentProcessorSlug : paymentProcessorSlug, faucetSlug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#previous_faucet').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.previous-faucet',
            { paymentProcessorSlug : paymentProcessorSlug, faucetSlug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#last_faucet').click(function(event) {
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.last-faucet',
            { paymentProcessorSlug : paymentProcessorSlug }
        );
        generateFaucet(route);
    });

    $('#reload_current').click(function(event) {
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.faucet',
            { paymentProcessorSlug : paymentProcessorSlug, faucetSlug : currentFaucetSlug }
        );
        generateFaucet(route);
    });

    $('#random').click(function(event){
        event.preventDefault();

        var route = laroute.route(
            'payment-processor.random-faucet',
            { paymentProcessorSlug : paymentProcessorSlug }
        );
        generateFaucet(route);
    });

    function init(){
        paymentProcessorSlug = $("#title").data("payment-processor-slug");
        var route = laroute.route(
            'payment-processor.first-faucet',
            { paymentProcessorSlug : paymentProcessorSlug }
        );
        generateFaucet(route);
    }

    function generateFaucet(apiUrl){
        var iframeUrl;
        //var currentFaucetSlug;
        var numberOfFaucets;

        paymentProcessorSlug = $("#title").data("payment-processor-slug");
        $.ajax(apiUrl, {
            success: function (data) {
                numberOfFaucets = data.data.length;
                iframeUrl = data.data.url;
                console.log(data.data.url);
                currentFaucetSlug = data.data.slug;
                $('#rotator-iframe').attr('src', iframeUrl);
                $('#current').attr('href', '/faucets/' + currentFaucetSlug);
            }
        });
    }
})
