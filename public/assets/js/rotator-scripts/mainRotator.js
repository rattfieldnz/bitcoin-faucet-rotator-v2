$(function(){
    var currentFaucetSlug;
    init();

    //Set iframe src to first faucet in array
    $('#first_faucet').click(function(event) {
        event.preventDefault();
        generateFaucet('/api/v1/first_faucet', false);
    });

    $('#next_faucet').click(function(event){
        event.preventDefault();
        generateFaucet('/api/v1/faucets/'+currentFaucetSlug+'/next', false);
    });

    $('#previous_faucet').click(function(event){
        event.preventDefault();
        generateFaucet('/api/v1/faucets/'+currentFaucetSlug+'/previous',false);
    });

    $('#last_faucet').click(function(event) {
        event.preventDefault();
        generateFaucet('/api/v1/faucets/'+currentFaucetSlug+'/previous',false);
    });

    $('#reload_current').click(function(event) {
        event.preventDefault();
        generateFaucet('/api/v1/faucets/' + currentFaucetSlug, false);
    });

    $('#random').click(function(event){
        event.preventDefault();
        generateFaucet('/api/v1/faucets', true);
    });

    function init(){
        generateFaucet('/api/v1/first_faucet', false);
    }

    function generateFaucet(apiUrl, isRandom){
        var iframeUrl;
        //var currentFaucetSlug;
        var numberOfFaucets;

        if(isRandom === true && apiUrl === '/api/v1/faucets'){
            $.ajax(apiUrl, {
                success: function (data) {
                    numberOfFaucets = data.data.length;
                    var randomFaucetIndex = randomInt(0,numberOfFaucets - 1);
                    iframeUrl = data.data[randomFaucetIndex - 1].url;
                    currentFaucetSlug = data.data[randomFaucetIndex - 1].slug;
                    $('#rotator-iframe').attr('src', iframeUrl);
                    $('#current').attr('href', '/faucets/' + currentFaucetSlug);
                }
            });
        }else{
            $.ajax(apiUrl, {
                success: function (data) {
                    iframeUrl = data.data.url;
                    currentFaucetSlug = data.data.slug;
                    $('#rotator-iframe').attr('src', iframeUrl);
                    $('#current').attr('href', '/faucets/' + currentFaucetSlug);
                }
            });
        }
    }
});

function randomInt(min, max)
{
    return Math.floor(Math.random() * (max - min + 1) + min);
}