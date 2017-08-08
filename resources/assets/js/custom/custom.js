
jQuery(document).ready(function() {
    footerReset();
});

jQuery(window).resize(function() {
    footerReset();
});

function footerReset(){
    var contentHeight = jQuery(window).height();
    var footerHeight = jQuery('#footer-custom').height();
    var footerTop = jQuery('#footer-custom').position().top + footerHeight;
    if (footerTop < contentHeight) {
        jQuery('#footer-custom').css('margin-top', (contentHeight - (footerTop) - 32) + 'px');
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