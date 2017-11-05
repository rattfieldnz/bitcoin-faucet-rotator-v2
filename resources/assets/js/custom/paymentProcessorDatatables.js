function generatePaymentProcessorsTable(data, elementToRender)
{
    if (typeof data !== 'undefined' && typeof $(elementToRender) !== 'undefined') {
        var tableConfig = {
            processing: true,
            data: data,
            language: {
                processing: "Loading...",
            },
            scrollX: true,
            scrollCollapse: true,
            bJQueryUI: true,
            order: [],
            columns: [
                {
                    data: "name",
                    render: {
                        _: function(data){
                            var link = '<a href="' + data.display + '" title="' + data.original + '">';
                            link += data.original + '</a>';
                            return link;
                        },
                        sort: 'original'
                    },
                    order: 1
                },
                {
                    data: "faucets",
                    render: {
                        _: function(data){
                            var link = '<a href="' + data.display + '" title="' + data.original + '">';
                            link += data.original + '</a>';
                            return link;
                        },
                        sort: 'original'
                    },
                    order: 2
                },
                {
                    data: "rotator",
                    render: {
                        _: function(data){
                            var link = '<a href="' + data.display + '" title="' + data.original + '">';
                            link += data.original + '</a>';
                            return link;
                        },
                        sort: 'original'
                    },
                    order: 3
                },
                {
                    data: "no_of_faucets", type: 'num', order: 4
                },
                {
                    data: "min_claimable",
                    type: 'num',
                    render: {
                        _: function(data){
                            return data.display;
                        },
                        sort: 'original'
                    },
                    order: 5
                },
                {
                    data: "max_claimable",
                    type: 'num',
                    render: {
                        _: function(data){
                            return data.display;
                        },
                        sort: 'original'
                    },
                    order: 6
                },
            ],
            responsive: true,
            fnStateSave: function (settings, data) {
                localStorage.setItem( 'PaymentProcessorsDataTable', JSON.stringify(data) );
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
            tableConfig.order = [[4, 'desc'], [5, 'desc'], [6, 'desc'], [1, 'asc'], [0, 'asc']];
        } else {
            tableConfig.order = [[3, 'desc'], [4, 'desc'], [5, 'desc'], [0, 'asc']];
        }

        thead +=
            '<th>Name</th>' +
            '<th>Faucets</th>' +
            '<th>Rotators</th>' +
            '<th>No. of Faucets</th>' +
            '<th>Min. Claimable</th>' +
            '<th>Max. Claimable</th>';

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

        $(elementToRender).DataTable(tableConfig);
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

function renderPaymentProcessorsDataTable(data, dataTableElement, progressBar)
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

                generatePaymentProcessorsTable(d.data, dataTableElement);
            }
        }).fail(function (vd) {
            loadingProgressElement.attr('style', 'display:none !important');
            showElement(progressBar);
            progressError(vd.message,progressBar);
        }).progress(function () {
            console.log("Payment processors datatable is loading...");
        });
    }
}
