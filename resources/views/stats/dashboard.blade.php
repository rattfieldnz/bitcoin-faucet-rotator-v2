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
                                    <tbody></tbody>
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

        $.ajaxSetup({timeout:3600000});

        var loader = $('<div class="loader-wrapper"><div class="loader"></div><p>Please wait, there\'s a lot of data to crunch...</p></div>');

        var dateFrom = '12-08-2017';
        var dateTo = '18-08-2017';
        var quantity = 1000;

        function displayLoading(container, promise) {
            //create a spinner in container if not already created
            //show spinner
            //hide other content
            container.append(loader);
            loader.show();
            promise.done(function() {
                console.log("done");
                //hide spinner
                //show other content
                loader.hide();
            });
        }

        //--------------
        //- AREA CHART -
        //--------------
        var visitorsAreaChartData = getVisitorsDataAjax('stats.visits-and-page-views', dateFrom, dateTo, quantity);

        $.when(visitorsAreaChartData).then(function(vacd){
            generateVisitorsLineChart(vacd, "areaChart");
        }).catch(function(e){
            console.log("There was an error");
        });

        //--------------------------------
        //- DATATABLES SHOWING VISITORS -
        //--------------------------------
        var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', dateFrom, dateTo, quantity);
        displayLoading($("#visitorsTable").find("tbody"), visitorsData);

        $.when(visitorsData).then(function(vd){
            generateVisitorsTable(vd.data, '#visitorsTable');
        }).catch(function(e){
            console.log("There was an error");
        });

        //-----------------------
        //- COUNTRIES MAP -------
        //-----------------------
        var geoChartData = getCountriesAndVisitorsAjax(dateFrom, dateTo);

        $.when(geoChartData).then(function(gcd){
            generateGoogleGeoChart(gcd, '#regions_div');
        }).catch(function(e){
            console.log("There was an error");
        });

        //-------------
        //- PIE CHART -
        //-------------
        var browserStatsData = getBrowserStatsAjax(dateFrom, dateTo, 10);

        $.when(browserStatsData).then(function(bsd){
            generatePieDonutChart(bsd,'#pieChart');
        }).catch(function(e){
            console.log("There was an error");
        });

    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush