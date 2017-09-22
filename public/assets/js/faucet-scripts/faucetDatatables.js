$(function () {
    $.fn.dataTable.ext.errMode = 'none';
    $.ajaxSetup({
        timeout: 3600000,

        // force ajax call on all browsers
        cache: false,

        // Enables cross domain requests
        crossDomain: true,

        // Helps in setting cookie
        xhrFields: {
            withCredentials: true
        },

        beforeSend: function (xhr, type) {
            // Set the CSRF Token in the header for security
            //if (type.type !== "GET") {
            var token = Cookies.get("XSRF-TOKEN");
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-XSRF-Token', token);
            //}
        }
    });

    //--------------------------------
    //- DATATABLES SHOWING FAUCETS -
    //--------------------------------
    var dataTablesName = 'faucets datatable';
    var route = laroute.route('faucets');
    var faucetsData = getFaucetsDataAjax(route);
    var faucetsTableProgressBar = generateProgressBar("#faucetsTable-progressbar",dataTablesName);
    renderFaucetsDataTable(faucetsData, '#faucetsTable', faucetsTableProgressBar);
});

function generateFaucetsTable(data, elementToRender)
{
    if (typeof data !== 'undefined' && typeof $(elementToRender) !== 'undefined') {
        var tableConfig = {
            processing: true,
            data: data,
            language: {
                processing: "Loading...",
            },
            scrollX: true,
            order: [],
            columns: [
                {
                    data: "name",
                    render: {
                        _: function(data){
                            var link = '<a href="' + data.display + '" target="_blank" title="' + data.original + '">';
                            link += data.original + '</a>';
                            return link;
                        },
                        sort: 'original'
                    },
                    order: 1
                },
                {data: "interval_minutes", order: 2},
                {
                    data: "min_payout",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    },
                    order: 3
                },
                {
                    data: "max_payout",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    },
                    order: 4
                },
                {
                    data: "payment_processors",
                    render: function (data){
                        var list = '<ul class="payment-processors-list">\n';
                        $.each(data, function(i){
                            var link = '<a href="' + data[i].url + '" target="_blank" title="' + data[i].name + '">';
                            link += data[i].name + '</a>';
                            list += '<li>' + link + '</li>';
                        });
                        list += '</ul>';
                        return list;
                    },
                    order: 5
                }
            ],
            responsive: true,
            fnStateSave: function (settings, data) {
                localStorage.setItem( 'FaucetsDataTable', JSON.stringify(data) );
            },
            fnStateLoad: function (settings) {
                var stateSettings = JSON.parse( localStorage.getItem('FaucetsDataTable') );
                stateSettings.iStart = 0;  // resets to first page of results
                return settings
            }
        };

        var thead = '<tr>';

        if(keyExists('id', data[0])){
            tableConfig.columns.push({data: 'id', order: 0});
            thead += '<th>Id</th>';
            tableConfig.order = [[2, 'asc'], [3, 'desc'], [4, 'desc'], [1, 'desc'], [0, 'desc']];
        } else {
            tableConfig.order = [[1, "asc"], [2, 'desc'], [3, 'desc'], [0, 'desc']];
        }

        thead += '<th>Name</th>' +
            '<th>Interval Minutes</th>' +
            '<th>Min. Payout*</th>' +
            '<th>Max. Payout*</th>' +
            '<th>Payment Processors</th>';

        if(keyExists('is_deleted', data[0])){
            tableConfig.columns.push({
                data: 'is_deleted',
                type: 'num',
                render: {
                    _: 'display',
                    sort: 'original'
                },
                order: 6
            });

            thead += '<th>Deleted?</th>';
        }

        if(keyExists('actions', data[0])){
            tableConfig.columns.push({
                data: 'actions',
                order: 7
            });

            thead += '<th>Action</th>';
        }

        thead += '</tr>';

        $(elementToRender + ' thead').empty();
        $(elementToRender + ' tfoot').empty();

        $(elementToRender + ' thead').append(thead);
        $(elementToRender + ' tfoot').append(thead);

        tableConfig.columns.sort(sortConfig);

        return $(elementToRender).DataTable(tableConfig);
    }
}

function sortConfig(a,b){
    return a.order - b.order;
}

function getFaucetsDataAjax(route)
{
    return $.get(route, function (response) {
        return response.data;
    }).promise();
}

function renderFaucetsDataTable(data, dataTableElement, progressBar)
{

    $(dataTableElement).DataTable().destroy();
    $(dataTableElement + ' tbody').empty();

    showElement(progressBar);

    var loadingProgressElement = $(dataTableElement + '_processing');
    loadingProgressElement.attr('style', 'display:initial !important');
    if (data.status !== 'undefined' && data.status === 'error') {
        progressError(
            data.message,
            progressBar
        );
    } else {
        data.done(function (d) {
            if (typeof d.status !== 'undefined' && d.status === 'error') {
                progressError(
                    d.message,
                    progressBar
                );
            } else {
                loadingProgressElement.attr('style', 'display:none !important');
                progressBar.progressTimer('complete');
                hideElement(progressBar, 3000);

                var table = generateFaucetsTable(d.data, dataTableElement);

                return table;
            }
        }).fail(function (vd) {
            loadingProgressElement.attr('style', 'display:none !important');
            showElement(progressBar);
            progressError(vd.message,progressBar);
        }).progress(function () {
            console.log("Faucets datatable is loading...");
        });
    }
}