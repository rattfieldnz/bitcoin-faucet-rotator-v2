(function ($) {

    var table = $('#faucets-table')
        .DataTable(
            {
                "fnInitComplete": function () {
                    // Disable TBODY scoll bars
                    $('.dataTables_scrollBody').css({
                        'overflow': 'hidden',
                        'border': '0'
                    });

                    // Enable TFOOT scoll bars
                    $('.dataTables_scrollFoot').css('overflow', 'auto');

                    $('.dataTables_scrollHead').css('overflow', 'auto');

                    // Sync TFOOT scrolling with TBODY
                    $('.dataTables_scrollFoot').on('scroll', function () {
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });

                    $('.dataTables_scrollHead').on('scroll', function () {
                        $('.dataTables_scrollBody').scrollLeft($(this).scrollLeft());
                    });
                },
                "scrollX": true,
                "scrollCollapse": true,
                "dom": 'Zlrtip',
                "colResize": {
                    "tableWidthFixed": false,
                    //"handleWidth": 10,
                    "resizeCallback": function (column) {

                    }
                },
                "searching": false,
                "paging": false,
                "info": false,
                "deferRender": true,
                "sScrollX": "190%"
            });

});


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