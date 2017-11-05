function generateUsersTable(data, elementToRender)
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
                    data: "user_name",
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
                        //sort: 'original'
                    },
                    order: 5
                },
                {
                    data: "no_of_faucets",
                    type: 'num',
                    order: 6
                },
                {
                    data: "payment_processors",
                    render: {
                        _: function(data){
                            var link = '<a href="' + data.display + '" target="_blank" title="' + data.original + '">';
                            link += data.original + '</a>';
                            return link;
                        },
                    },
                    order: 7
                }
            ],
            responsive: true,
            fnStateSave: function (settings, data) {
                localStorage.setItem( 'UsersDataTable', JSON.stringify(data) );
            },
            fnStateLoad: function (settings) {
                var stateSettings = JSON.parse( localStorage.getItem('UsersDataTable') );
                stateSettings.iStart = 0;  // resets to first page of results
                return settings
            }
        };

        var thead = '<tr>';

        if(
            !keyExists('id', data[0]) && !keyExists('role', data[0]) &&
            !keyExists('is_admin', data[0]) && !keyExists('is_deleted', data[0]))
        {
            tableConfig.order = [[2, "desc"], [0, "asc"]];
        }

        if(keyExists('id', data[0])){
            tableConfig.columns.push({data: 'id', type: 'num', order: 0});
            thead += '<th>Id</th>';
            tableConfig.order = [[2, 'desc'], [1, 'asc'], [0, 'asc']];
        }

        thead += '<th>User Name</th>';

        if(keyExists('role', data[0])){
            tableConfig.columns.push({data: 'role', order: 2});
            thead += '<th>Role</th>';
            tableConfig.order = [[3, 'desc'], [1, 'asc'], [2, 'asc'], [0, 'asc']];
        }

        if(keyExists('is_admin', data[0])){
            tableConfig.columns.push(
                {
                    data: 'is_admin',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    },
                    order: 3
                }
            );
            thead += '<th>Is an admin?</th>';
            tableConfig.order = [[4, 'desc'], [1, 'asc'], [3, 'asc'], [2, 'desc'], [0, 'asc']];
        }

        if(keyExists('is_deleted', data[0])){
            tableConfig.columns.push(
                {
                    data: 'is_deleted',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    },
                    order: 4
                }
            );
            thead += '<th>Deleted?</th>';
            tableConfig.order = [[6, 'desc'], [1, 'asc'], [3, 'asc'], [2, 'desc'], [4, 'desc'], [0, 'asc']];
        }

        thead += '<th>Faucets</th><th>No. of Faucets</th><th>Payment Processors</th>';

        if(keyExists('actions', data[0])){
            tableConfig.columns.push({
                data: 'actions',
                order: 8
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

function getUsersDataAjax(route)
{
    return $.get(route, function (response) {
        return response.data;
    }).promise();
}

function renderUsersDataTable(data, dataTableElement, progressBar)
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

                return generateUsersTable(d.data, dataTableElement);
            }
        }).fail(function (vd) {
            loadingProgressElement.attr('style', 'display:none !important');
            showElement(progressBar);
            progressError(vd.message,progressBar);
        }).progress(function () {
            console.log("Users datatable is loading...");
        });
    }
}
