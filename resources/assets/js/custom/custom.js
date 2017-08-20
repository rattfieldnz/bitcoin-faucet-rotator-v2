
jQuery(document).ready(function() {
    footerReset();
});

jQuery(window).resize(function() {
    footerReset();
});

function footerReset(){
    var contentHeight = jQuery(window).height();
    var footer = jQuery('#footer-custom');
    var footerHeight = footer.height();
    var footerTop = footer.position().top + footerHeight;
    if (footerTop < contentHeight) {
        footer.css('margin-top', (contentHeight - (footerTop) - 32) + 'px');
    }
}

function getRandomRgb() {
    var num = Math.round(0xffffff * Math.random());
    return {
        r: num >> 16,
        g: num >> 8 & 255,
        b: num & 255
    }
}

function rgbaString(r, g, b, a) {
    var opacity = 1;
    if(typeof a !== 'undefined'){
        opacity = a;
    }
    if (typeof r !== 'undefined' && typeof g !== 'undefined' && typeof b !== 'undefined') {
        return "rgba(" + r + "," + g + "," + b + "," + opacity + ")";
    } else {
        return null;
    }
}

function getRandomHexColor() {
    var length = 6;
    var chars = '0123456789ABCDEF';
    var hex = '#';
    while (length--) hex += chars[(Math.random() * 16) | 0];
    return hex;
}

function progressError(dataObject, progressBar, item){
    if(typeof dataObject !== 'undefined' && typeof progressBar !== 'undefined'){
        if(dataObject.message !== 'undefined'){
            progressBar.progressTimer('error', {
                errorText:'ERROR! - ' + dataObject.message,
                warningStyle: 'progress-bar-danger',
                onFinish:function(){
                    typeof item !== 'undefined' ? item = ' for ' + item : '';
                    console.log("There was an error " + item + " - " + dataObject.message);
                }
            });
        }
    }
}

function generateProgressBar(element, dataName){
    if(typeof element !== 'undefined' && dataName !== 'undefined'){
        return $(element).progressTimer({
            timeLimit: 600,
            baseStyle: 'progress-bar-info',
            warningThreshold: 180,
            warningStyle: 'progress-bar-warning',
            completeStyle: 'progress-bar-success',
            successText: '<strong>100% - ' + dataName + ' data loading complete!</strong>'
        });
    }
}

window.addEventListener("load", function(){
    window.cookieconsent.initialise({
        "palette": {
            "popup": {
                "background": "#252e39"
            },
            "button": {
                "background": "#14a7d0"
            }
        },
        "theme": "classic"
    })});