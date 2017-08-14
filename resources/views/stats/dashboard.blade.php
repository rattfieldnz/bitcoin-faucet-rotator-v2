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
<script src="https://www.gstatic.com/charts/loader.js?{{ rand() }}"></script>
<script src="/assets/js/stats/stats.min.js?{{ rand() }}"></script>
<script>
    $(function () {

        var dateFrom = '08-08-2017';
        var dateTo = '14-08-2017';
        var quantity = 1000;

        //--------------
        //- AREA CHART -
        //--------------

        var visitorsAreaChartData = getVisitorsDataAjax('stats.visits-and-page-views', dateFrom, dateTo, quantity);

        $.when(visitorsAreaChartData).then(function(response){
            generateVisitorsLineChart(response, "areaChart");
        });

        // DATATABLES EXAMPLE FOR SHOWING VISITORS //

        var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', dateFrom, dateTo, quantity);

        $.when(visitorsData).then(function(response){
            generateVisitorsTable(response.data, '#visitorsTable');
        });

        //-------------
        //- PIE CHART -
        //-------------

        var browserStatsData = getBrowserStatsAjax(dateFrom, dateTo, 10);

        $.when(browserStatsData).then(function(response){
            generatePieDonutChart(response,'#pieChart');
        });

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
    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush