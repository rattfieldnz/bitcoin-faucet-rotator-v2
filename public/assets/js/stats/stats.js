

function generateVisitorsTable(data){
    if(typeof data !== 'undefined'){
        $('#visitorsTable').DataTable({
            data: data,
            order: [[2, "desc"], [3, "desc"], [4, "desc"], [5,"desc"], [6, "desc"], [7, "asc"], [8, "desc"]],
            columns: [
                {data: "url"},
                {data: "pageTitle"},
                {
                    data: "uniqueVisitors",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {
                    data: "pageViews",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {
                    data: "uniquePageViews",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {
                    data: 'aveSessionDuration',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {
                    data: 'aveTimeOnPage',
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {
                    data: "noOfBounces",
                    type: 'num',
                    render: {
                        _: 'display',
                        sort: 'original'
                    }
                },
                {data: "noOfCountries"}
            ],
            responsive: true
        });
    }
}

function getVisitorsDataAjax(routeName, dateFrom, dateTo, quantity){

    if(typeof quantity === 'undefined'){
        quantity = 10;
    }

    if(typeof routeName !== 'undefined' && typeof dateFrom !== 'undefined' && typeof dateTo !== 'undefined'){

        var visitorsRoute = laroute.route(
            routeName,
            { dateFrom : dateFrom, dateTo: dateTo, quantity: quantity }
        );

        if(typeof visitorsRoute === 'undefined'){
            return null;
        }

        return $.get(visitorsRoute, function(response){
            return response.data;
        }).promise();
    } else {
        return null;
    }
}