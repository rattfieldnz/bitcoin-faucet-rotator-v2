@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/css/datatables.net/datatables.min.css?{{ rand() }}" type="text/css">
@endsection

@section('content')
    <div class="zero-margin">
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
                    <div class="row" style="margin: 0 0 1em 0;">
                        <form id="stats-form" class="form-inline">
                            <div class="form-group">
                                <label for="from-date">From:</label>
                                <input type="text" class="form-control" id="from-date" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="to-date">To:</label>
                                <input type="text" class="form-control" id="to-date" placeholder="">
                            </div>
                            <button type="submit" class="btn btn-primary">Get Stats</button>
                        </form>
                    </div>
                    <div class="row zero-margin">
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
                                <div id="areaChart-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
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
                    <div class="row zero-margin">
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

                    <div class="row zero-margin">
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
                                <div id="countriesMap-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>

                                <div id="regions_div" style="width: 100%; height: auto;"></div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                        <!-- /.col (RIGHT) -->
                    </div>
                    <div class="row zero-margin">
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
    </div>
@endsection

@push('scripts')
<script src="/assets/js/datatables.net/datatables.min.js?{{ rand() }}"></script>
<script src="/assets/js/chart.js/Chart.min.js?{{ rand() }}"></script>
<script src="https://www.gstatic.com/charts/loader.js?{{ rand() }}"></script>
<script src="/assets/js/stats/stats.min.js?{{ rand() }}"></script>
<script>

    $(function () {
        $.ajaxSetup({
            timeout:3600000,

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

        var dateTo = currentDate().dateFormatted;
        var dateFrom = dateFormatted(alterDate(currentDate().date, -6)).dateFormatted;
        var quantity = 10000;

        var fromDateInput = $("#from-date");
        fromDateInput.val(dateFrom);
        var toDateInput = $("#to-date");
        toDateInput.val(dateTo);

        fromDateInput.prop("placeholder", dateFrom);
        toDateInput.prop("placeholder", dateTo);

        fromDateInput.datepicker({
            numberOfMonths: 2,
            dateFormat: 'dd-mm-yy',
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                toDateInput.datepicker("option", null, dateFormatted(dt).dateFormatted);
            }
        });
        toDateInput.datepicker({
            numberOfMonths: 2,
            dateFormat: 'dd-mm-yy',
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                fromDateInput.datepicker("option", null, dateFormatted(dt).dateFormatted);
            }
        });

        //--------------
        //- AREA CHART -
        //--------------
        var areaChartName = 'visitors area chart';
        var areaChartElement = "#areaChart";
        var visitorsAreaChartData = getVisitorsDataAjax('stats.visits-and-page-views', fromDateInput.val(), toDateInput.val(), quantity);
        var visitorsAreaChartProgressBar = generateProgressBar("#areaChart-progressbar", areaChartName);
        renderVisitorsAreaChart(visitorsAreaChartData, areaChartElement,visitorsAreaChartProgressBar);

        //--------------------------------
        //- DATATABLES SHOWING VISITORS -
        //--------------------------------
        var dataTablesName = 'visitors datatable';
        var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', fromDateInput.val(), toDateInput.val(), quantity);
        var visitorsTableProgressBar = generateProgressBar("#visitorsTable-progressbar",dataTablesName);

        if(visitorsData.status !== 'undefined' && visitorsData.status === 'error'){
            showElement("#visitorsTable-progressbar");
            progressError(
                visitorsData.message,
                visitorsTableProgressBar
            );
        } else {
            visitorsData.done(function(vd){
                if(typeof vd.data[0][0] !== 'undefined' && vd.data[0][0] === 'error'){
                    showElement("#visitorsTable-progressbar");
                    progressError(
                        vd.data[2][0],
                        visitorsTableProgressBar
                    );
                } else {
                    showElement("#visitorsTable-progressbar");
                    generateVisitorsTable(vd.data, '#visitorsTable');
                    visitorsTableProgressBar.progressTimer('complete');
                    hideElement("#visitorsTable-progressbar", 3000);
                }
            }).fail(function(vd){
                showElement("#visitorsTable-progressbar");
                progressError(vd,visitorsTableProgressBar,dataTablesName);
            }).progress(function(){
                console.log("Visitors datatable is loading...");
            });
        }

        //-----------------------
        //- COUNTRIES MAP -------
        //-----------------------
        var countriesMapName = 'visitors geo chart';
        var geoChartData = getCountriesAndVisitorsAjax(fromDateInput.val(), toDateInput.val());
        var geoChartProgressBar = generateProgressBar("#countriesMap-progressbar",countriesMapName);

        hideElement("#countriesMap-progressbar");
        if(geoChartData.status !== 'undefined' && geoChartData.status === 'error'){
            showElement("#countriesMap-progressbar");
            progressError(
                geoChartData.message,
                geoChartProgressBar
            );
        } else {
            geoChartData.done(function(gcd){

                if(typeof gcd.status !== 'undefined' && gcd.status === 'error'){
                    showElement("#countriesMap-progressbar");
                    progressError(
                        gcd.message,
                        geoChartProgressBar
                    );
                } else {
                    showElement("#countriesMap-progressbar");
                    generateGoogleGeoChart(gcd, '#regions_div');
                    geoChartProgressBar.progressTimer('complete');
                    hideElement("#countriesMap-progressbar", 3000);
                }
            }).fail(function(gcd){
                showElement("#countriesMap-progressbar");
                progressError(gcd,geoChartProgressBar);
            }).progress(function(){
                console.log("Visitors geo chart is loading...");
            });
        }

        //-------------
        //- PIE CHART -
        //-------------
        var pieChartName = "visitors' browsers chart";
        var browserStatsData = getBrowserStatsAjax(fromDateInput.val(), toDateInput.val(), 10);
        var browserStatsProgressBar = generateProgressBar("#pieChart-progressbar",pieChartName);

        hideElement("#pieChart-progressbar");
        if(browserStatsData.status !== 'undefined' && browserStatsData.status === 'error'){

            showElement("#pieChart-progressbar");
            progressError(
                browserStatsData.message,
                browserStatsProgressBar
            );
        } else {
            browserStatsData.done(function(bsd){

                if(typeof bsd.status !== 'undefined' && bsd.status === 'error'){
                    showElement("#pieChart-progressbar");
                    progressError(
                        bsd.message,
                        browserStatsProgressBar
                    );
                } else {
                    showElement("#pieChart-progressbar");
                    generatePieDonutChart(bsd,'#pieChart');
                    browserStatsProgressBar.progressTimer('complete');
                    hideElement("#pieChart-progressbar", 3000);
                }
            }).fail(function(bsd){
                showElement("#pieChart-progressbar");
                progressError(bsd,browserStatsProgressBar,pieChartName);
            }).progress(function(){
                console.log("Visitors' browsers chart is loading...");
            });
        }

        $("#stats-form").submit(function(event){
            event.preventDefault();

            var fromDate = $("#from-date").val();
            var toDate = $("#to-date").val();

            var data = getVisitorsDataAjax('stats.visits-and-page-views', fromDate, toDate, quantity);
            var progressBar = generateProgressBar("#areaChart-progressbar", areaChartName);

            $("#areaChart").empty();
            renderVisitorsAreaChart(data, "#areaChart",progressBar, true);

        });
    });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush