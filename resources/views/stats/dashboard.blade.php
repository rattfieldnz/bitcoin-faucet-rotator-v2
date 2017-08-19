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
                            <div id="areaChart-progressbar" style="margin: 0 0 1em 0;"><span style="text-align: center;margin: 0.3em 0 0 45%;"></span></div>
                            <div></div>
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
                            <div id="visitorsTable-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>

                                <div id="visitorsTable-wrapper" class="chart">
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
                            <div id="countriesMap-progressbar" style="margin: 0 0 1em 0;"><span style="text-align: center;margin: 0.3em 0 0 45%;"></span></div>
                            <div></div>
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
                            <div id="pieChart-progressbar" style="margin: 0 0 1em 0;"><span style="text-align: center;margin: 0.3em 0 0 45%;"></span></div>
                            <div></div>
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

        var dateFrom = '13-08-2017';
        var dateTo = '19-08-2017';
        var quantity = 1000;

        //--------------
        //- AREA CHART -
        //--------------
        var visitorsAreaChartData = getVisitorsDataAjax('stats.visits-and-page-views', dateFrom, dateTo, quantity);

        visitorsAreaChartData.done(function(vacd){
            if(typeof vacd.message !== 'undefined'){
                console.log("There was an error for visitors area chart - " + vacd.message);
            } else {
                generateVisitorsLineChart(vacd, "areaChart");
                console.log("Area chart has loaded.");
            }
        });
        visitorsAreaChartData.fail(function(vacd){
            console.log("There was an error for visitors area chart - " + vacd.message);
        });
        visitorsAreaChartData.progress(function(){
            console.log("Visitors area chart is loading...");
        });

        //--------------------------------
        //- DATATABLES SHOWING VISITORS -
        //--------------------------------
        var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', dateFrom, dateTo, quantity);

        var progress = $("#visitorsTable-progressbar").progressTimer({
            timeLimit: 600,
            //bootstrap progress bar style at the beginning of the timer
            baseStyle: 'progress-bar-info',

            warningThreshold: 180,

            //bootstrap progress bar style in the warning phase
            warningStyle: 'progress-bar-warning',

            //bootstrap progress bar style at completion of timer
            completeStyle: 'progress-bar-success',
        });

        var progressError = function(dataObject, progressBar, item){
            if(typeof dataObject !== 'undefined' && typeof progressBar !== 'undefined'){
                if(dataObject.message !== 'undefined'){
                    progressBar.progressTimer('error', {
                        errorText:'ERROR! - ' + dataObject.message,
                        warningStyle: 'progress-bar-danger',
                        onFinish:function(){
                            typeof item !== 'undefined' ? item = ' for ' + item : '';
                            console.log("There was an error " + item + " - " + dataObject.message);
                        }
                    });
                }
            }
        };

        visitorsData.done(function(vd){
            if(typeof vd.status !== 'undefined' && vd.status === 'error'){
                progressError(vd,progress,'visitors datatable');
            } else {
                generateVisitorsTable(vd.data, '#visitorsTable');
                progress.progressTimer('complete');
                console.log("Visitors datatable has loaded.");
            }
        });
        visitorsData.fail(function(vd){
            progressError(vd,progress,'visitors datatable');
        });
        visitorsData.progress(function(vd){
            console.log("Visitors datatable is loading...");
        });

        //-----------------------
        //- COUNTRIES MAP -------
        //-----------------------
        var geoChartData = getCountriesAndVisitorsAjax(dateFrom, dateTo);

        geoChartData.done(function(gcd){
            if(typeof gcd.message !== 'undefined'){
                console.log("There was an error for visitors geo chart - " + gcd.message);
            } else{
                generateGoogleGeoChart(gcd, '#regions_div');
                console.log("Visitors geo chart has loaded.");
            }
        });
        geoChartData.fail(function(gcd){
            console.log("There was an error for visitors geo chart - " + gcd.message);
        });
        geoChartData.progress(function(){
            console.log("Visitors geo chart is loading...");
        });

        //-------------
        //- PIE CHART -
        //-------------
        var browserStatsData = getBrowserStatsAjax(dateFrom, dateTo, 10);

        browserStatsData.done(function(bsd){
            if(typeof bsd.message !== 'undefined'){
                console.log("There was an error for visitors' browsers chart - " + bsd.message);
            } else {
                generatePieDonutChart(bsd,'#pieChart');
                console.log("Visitors' browsers chart has loaded.");
            }
        });
        browserStatsData.fail(function(bsd){
            console.log("There was an error for visitors' browsers chart - " + bsd.message);
        });
        browserStatsData.progress(function(){
            console.log("Visitors' browsers chart is loading...");
        });

    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush