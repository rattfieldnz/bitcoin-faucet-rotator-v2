@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/css/datatables.net/datatables.min.css?{{ rand() }}" type="text/css">
@endsection

@section('content')
    <section class="content-header">
        <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
            <h1>Stats</h1>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        @include('layouts.partials.navigation._breadcrumbs')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="margin: 0 0 0 0;">
                    <!-- AREA CHART -->
                    <div class="box box-primary col-md-12">
                        <div class="box-header with-border">
                            <h3 class="box-title">Visitors and Page Views</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="areaChart" style="height:250px"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.row -->
                <div class="row" style="margin: 0 0 0 0;">
                    <!-- /.col (LEFT) -->
                    <!-- VISITORS TABLE -->
                    <div class="box box-info col-md-12">
                        <div class="box-header with-border">
                            <h3 class="box-title">Most Popular Pages</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <table id="visitorsTable" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>URL</th>
                                            <th>Page Title</th>
                                            <th>Unique Visitors</th>
                                            <th>Page Views</th>
                                            <th>Unique Page Views</th>
                                            <th>Ave. Session Duration</th>
                                            <th>Ave. Time on Page</th>
                                            <th>Bounces</th>
                                            <th>No. of Countries</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>URL</th>
                                            <th>Page Title</th>
                                            <th>Unique Visitors</th>
                                            <th>Page Views</th>
                                            <th>Unique Page Views</th>
                                            <th>Ave. Session Duration</th>
                                            <th>Ave. Time on Page</th>
                                            <th>Bounces</th>
                                            <th>No. of Countries</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="row" style="margin: 0 0 0 0;">
                    <!-- COUNTRIES MAP -->
                    <div class="box box-info col-md-12">
                        <div class="box-header with-border">
                            <h3 class="box-title">Visitor Countries</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="regions_div" style="width: 100%; height: auto;"></div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.col (RIGHT) -->
                </div>
                <div class="row" style="margin: 0 0 0 0;">
                    <!-- DONUT CHART -->
                    <div class="box box-danger col-md-12">
                        <div class="box-header with-border">
                            <h3 class="box-title">Browser Usage (by visits)</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <canvas id="pieChart" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.row -->

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="/assets/js/datatables.net/datatables.min.js?{{ rand() }}"></script>
<script src="/assets/js/chart.js/Chart.min.js?{{ rand() }}"></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(function () {

        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------

        let areaChartContext = document.getElementById("areaChart").getContext("2d");

        let visitorsRGB = getRandomRgb();
        let pageViewsRGB = getRandomRgb();

        let config = {
            type: 'line',
            data: {
                labels: {!! json_encode($dates->map(function($date) { return $date->format('d/m/Y'); })) !!},
                datasets: [
                    {
                        label: "Visitors",
                        borderColor: rgbaString(visitorsRGB.r, visitorsRGB.g, visitorsRGB.b),
                        backgroundColor: rgbaString(visitorsRGB.r, visitorsRGB.g, visitorsRGB.b, 0.5),
                        data: {!! json_encode($visitors) !!}
                    },
                    {
                        label: "Page views",
                        borderColor: rgbaString(pageViewsRGB.r, pageViewsRGB.g, pageViewsRGB.b),
                        backgroundColor: rgbaString(pageViewsRGB.r, pageViewsRGB.g, pageViewsRGB.b, 0.5),
                        data: {!! json_encode($pageViews) !!}
                    }
                ]
            },
            options: {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.8,
                //Boolean - Whether to show a dot for each point
                pointDot: false,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
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
        let areaChart = new Chart(areaChartContext, config);

        //-------------
        //- PIE CHART -
        //-------------
        let pieChartCanvas = document.getElementById('pieChart');
        let pieChartContext = pieChartCanvas.getContext("2d");
        let pieChartOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 2,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: true
        };

        let backgroundColors = [];
        let dataCount =
                {!! count($browserjson['datasets']['data']) !!}
        for (let i = 0; i < dataCount; i++) {
            backgroundColors.push(getRandomHexColor());
        }

        let pieChart = new Chart(
            pieChartContext, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($browserjson['labels']) !!},
                    datasets: [{
                        backgroundColor: backgroundColors,
                        data: {!! json_encode($browserjson['datasets']['data']) !!}
                    }],
                    options: pieChartOptions
                }
            }
        );

        //-----------------------
        //- COUNTRIES MAP -------
        //-----------------------

        var mapElement = $('#regions_div');
        google.charts.load('current', {
            'packages': ['geochart'],
            // Note: you will need to get a mapsApiKey for your project.
            // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
            'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
        });
        google.charts.setOnLoadCallback(drawRegionsMap);
        var countriesData = {!! json_encode($countries) !!};

        for (var i = 0; i < countriesData.length; i++) {
            if (!isNaN(countriesData[i][1])) {
                countriesData[i][1] = parseInt(countriesData[i][1]);
            }
        }

        function drawRegionsMap() {
            mapElement.empty();
            if (countriesData.length > 0) {
                var data = google.visualization.arrayToDataTable(countriesData);

                var options = {};
                options.width = '90%';
                options.showLegend = true;
                options.showZoomOut = true;
                options.zoomOutLabel = 'Zoom Out';

                var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

                chart.draw(data, options);
            } else {
                mapElement.append('<p style="font-size:2em;">Sorry, there is no data to show on this map.</p>');
            }
        }

        var windowResizeTimer;

        jQuery(window).on('resize', function () {
            clearTimeout(windowResizeTimer);
            windowResizeTimer = setTimeout(function () {
                drawRegionsMap();
            }, 250);
        });

        jQuery(window).on('reload', function () {
            clearTimeout(windowResizeTimer);
            windowResizeTimer = setTimeout(function () {
                drawRegionsMap();
            }, 250);
        });

        // DATATABLES EXAMPLE FOR SHOWING VISITORS //

        var dateFrom = '12-08-2017';
        var dateTo = '12-08-2017';
        var quantity = 1000;

        var jsonURL = '/api/v1/top-pages/from/' +
            dateFrom + '/to/' +
            dateTo + '/quantity/' +
            quantity;

        $.getJSON(jsonURL, function(response) {
            $('#visitorsTable').DataTable({
                data: response.data,
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
        });

        function getRandomRgb() {
            var num = Math.round(0xffffff * Math.random());
            return {
                r: num >> 16,
                g: num >> 8 & 255,
                b: num & 255
            }
        }

        function rgbaString(r, g, b, a = 1) {
            if (typeof r !== 'undefined' && typeof g !== 'undefined' && typeof b !== 'undefined') {
                return "rgba(" + r + "," + g + "," + b + "," + a + ")";
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

        function secondsToTime(seconds) {
            if (typeof seconds !== 'undefined' && !isNaN(seconds)){
                return Math.ceil(seconds);
            } else{
                return 'unknown';
            }

        }
    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush