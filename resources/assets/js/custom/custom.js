
$(window).ready(function () {
    footerReset();
});

$(window).resize(function () {
    footerReset();
});

window.addEventListener("load", function () {
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
    })
});

function footerReset() {
    var contentHeight = jQuery(window).height();
    var footer = jQuery('#footer-custom');
    if (typeof footer.position() !== 'undefined') {
        var footerHeight = footer.height();
        var footerTop = footer.position().top + footerHeight;
        if (footerTop < contentHeight) {
            footer.css('margin-top', (contentHeight - (footerTop) - 32) + 'px');
        }
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
    if (typeof a !== 'undefined') {
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

function progressError(dataObject, progressBar) {
    if (dataObject !== null && dataObject !== 'undefined' && typeof progressBar !== 'undefined') {

        var errorMessage;

        if(typeof dataObject === 'string'){
            errorMessage = dataObject;
        } else{
            errorMessage = dataObject.message;
        }

        progressBar.progressTimer('error', {
            errorText: '<strong>ERROR! - ' + errorMessage + '</strong>',
            warningStyle: 'progress-bar-danger'
        });
    }
}

function generateProgressBar(elementName, dataName) {
    if (typeof $(elementName) !== 'undefined' && dataName !== 'undefined') {
        return $(elementName).progressTimer({
            timeLimit: 600,
            baseStyle: 'progress-bar-info',
            warningThreshold: 180,
            warningStyle: 'progress-bar-warning',
            completeStyle: 'progress-bar-success',
            successText: '<strong>100% - ' + dataName + ' data loading complete!</strong>'
        });
    }
}

function hideElement(elementName, timeoutValue) {
    if (typeof $(elementName) !== 'undefined') {
        typeof timeoutValue === 'undefined' ? timeoutValue = 0 : timeoutValue;
        return setTimeout(function () {
            $(elementName).hide();
        }, timeoutValue);
    }
}

function showElement(elementName, timeoutValue){
    if (typeof $(elementName) !== 'undefined') {
        typeof timeoutValue === 'undefined' ? timeoutValue = 0 : timeoutValue;
        return setTimeout(function () {
            $(elementName).show();
        }, timeoutValue);
    }
}

function currentDate(separator){

    if(typeof separator === 'undefined'){
        separator = '-';
    }
    var d = new Date();
    var day = ('0' + (d.getDate())).slice(-2);
    var month = ('0' + (d.getMonth()+1)).slice(-2);
    var year = d.getFullYear();

    return {date: d, dateFormatted:day + separator + month + separator + year }
}

function dateFormatted(date, separator){
    if(typeof date !== 'undefined' && date.constructor === Date){

        if(typeof separator === 'undefined'){
            separator = '-';
        }
        var day = ("0" + (date.getDate())).slice(-2);
        var month = ("0" + (date.getMonth()+1)).slice(-2);
        var year = date.getFullYear();

        return {date: date, dateFormatted:day + separator + month + separator + year }
    } else{
        return null;
    }
}

function alterDate(date, amount) {
    var timezoneOffset = date.getTimezoneOffset() * 60 * 1000,
        t = date.getTime(),
        d = new Date();

    if(typeof amount !== 'undefined' && Number.isInteger(amount)){

        t += (1000 * 60 * 60 * 24 * amount);

        d.setTime(t);

        var timezoneOffset2 = d.getTimezoneOffset() * 60 * 1000;
        if (timezoneOffset !== timezoneOffset2) {
            t += timezoneOffset2 - timezoneOffset;
            d.setTime(t);
        }

        return d;
    } else{
        return null;
    }
}

function renderVisitorsAreaChart(data, chartElementName, progressBar, doUpdate){

    hideElement(progressBar);
    $(chartElementName).empty();
    if(typeof doUpdate === 'undefined' || doUpdate === null){
        doUpdate = false;
    }
    if(data.status !== 'undefined' && data.status === 'error'){
        showElement(progressBar);
        progressError(data.message, progressBar);
    } else {
        data.done(function(vacd){
            if(typeof vacd.status !== 'undefined' && vacd.status === 'error'){
                showElement(progressBar);
                progressError(vacd.message, progressBar);
            } else {
                showElement(progressBar);
                var chart = generateVisitorsLineChart(vacd, chartElementName);
                doUpdate === true ? chart.update() : '';
                progressBar.progressTimer('complete');
                hideElement(progressBar, 3000);
            }
        }).fail(function(vacd){
            showElement(progressBar);
            progressError(vacd,progressBar);
        }).progress(function(){
            console.log("Visitors area chart is loading...");
        });
    }
}