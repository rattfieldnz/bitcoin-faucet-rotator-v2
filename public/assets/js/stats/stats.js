
Date.prototype.formatDDMMYYYY = function() {
    return (this.getDate()) +
        "/" +  (this.getMonth() + 1) +
        "/" +  this.getFullYear();
};

function generateVisitorsTable(data, elementToRender){
    if(typeof data !== 'undefined' && typeof elementToRender !== 'undefined'){
        $(elementToRender).DataTable({
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

function generateVisitorsLineChart(data, chartElement){

    var visitorsRGB = getRandomRgb();
    var pageViewsRGB = getRandomRgb();

    if(typeof data !== 'undefined' && typeof chartElement !== 'undefined'){

        var areaChartContext = document.getElementById(chartElement).getContext("2d");

        var labels = [], visitorsData = [], pageViewsData = [];

        // Separate data and labels into their own arrays.
        data.forEach(function(d) {
            labels.push(new Date(d.date).formatDDMMYYYY());
            visitorsData.push(parseInt(d.visitors));
            pageViewsData.push(parseInt(d.pageViews));
        });

        var config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Visitors",
                        borderColor: rgbaString(visitorsRGB.r, visitorsRGB.g, visitorsRGB.b),
                        backgroundColor: rgbaString(visitorsRGB.r, visitorsRGB.g, visitorsRGB.b, 0.5),
                        data: visitorsData
                    },
                    {
                        label: "Page views",
                        borderColor: rgbaString(pageViewsRGB.r, pageViewsRGB.g, pageViewsRGB.b),
                        backgroundColor: rgbaString(pageViewsRGB.r, pageViewsRGB.g, pageViewsRGB.b, 0.5),
                        data: pageViewsData
                    }
                ]
            },
            options: {
                showScale: true,
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                scaleShowHorizontalLines: true,
                scaleShowVerticalLines: true,
                bezierCurve: true,
                bezierCurveTension: 0.8,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                maintainAspectRatio: true,
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function (t, d) {
                            if (t.datasetIndex === 0) {
                                return "Visitors: " + t.yLabel.toString();
                            } else if (t.datasetIndex === 1) {
                                return "Page Views: " + t.yLabel.toString();
                            }
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Number of Visitors / Page Views'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }]
                }
            }
        };
        return new Chart(areaChartContext, config);
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
