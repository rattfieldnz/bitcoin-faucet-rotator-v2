@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/css/datatables.net/datatables.min.css?{{ rand() }}" type="text/css">
@endsection

@section('content')
    <div class="zero-margin">
        <section class="content-header">
            <div class="row {{ empty(Auth::user()) ? 'guest-page-title' : 'auth-page-title' }}">
                <h1>Stats</h1>
                <h3>From <span id="date-from-display"></span> to <span id="date-to-display"></span></h3>
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
                                <div class="chart" id="areaChart-container">
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
                                <div></div>
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
                                <div></div>
                                <div id="regions_div"></div>
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
                                <div id="pieChart-progressbar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
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

        var dateTo = currentDate();
        var dateFrom = dateFormatted(alterDate(currentDate().date, -6));
        var quantity = 500;

        var dateFromDisplayElement = $('#date-from-display');
        var dateToDisplayElement = $('#date-to-display');

        dateFromDisplayElement.text(dateFrom.fullDisplay);
        dateToDisplayElement.text(dateTo.fullDisplay);

        var fromDateInput = $("#from-date");
        fromDateInput.val(dateFrom.dateFormatted);
        var toDateInput = $("#to-date");
        toDateInput.val(dateTo.dateFormatted);

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
        renderVisitorsAreaChart(visitorsAreaChartData, areaChartElement, "#areaChart-container", "15.625em", visitorsAreaChartProgressBar);

        //--------------------------------
        //- DATATABLES SHOWING VISITORS -
        //--------------------------------
        var dataTablesName = 'visitors datatable';
        var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', fromDateInput.val(), toDateInput.val(), quantity);
        var visitorsTableProgressBar = generateProgressBar("#visitorsTable-progressbar",dataTablesName);
        renderVisitorsDataTable(visitorsData,'#visitorsTable',visitorsTableProgressBar);
        //$('table#visitorsTable tbody').empty();

        //-----------------------
        //- COUNTRIES MAP -------
        //-----------------------
        var countriesMapName = 'visitors geo chart';
        var geoChartData = getCountriesAndVisitorsAjax(fromDateInput.val(), toDateInput.val());
        var geoChartProgressBar = generateProgressBar("#countriesMap-progressbar",countriesMapName);
        renderVisitorsGoogleMap(geoChartData, '#regions_div', geoChartProgressBar, '100%', 'auto');

        //-------------
        //- PIE CHART -
        //-------------
        var pieChartName = "visitors' browsers chart";
        var browserStatsData = getBrowserStatsAjax(fromDateInput.val(), toDateInput.val(), 10);
        var browserStatsProgressBar = generateProgressBar("#pieChart-progressbar",pieChartName);

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
                progressError(
                    bsd.message,
                    browserStatsProgressBar
                );
            }).progress(function(){
                console.log("Visitors' browsers chart is loading...");
            });
        }

        $("#stats-form").submit(function(event){
            event.preventDefault();

            var fromDate = $("#from-date").val();
            var toDate = $("#to-date").val();

            var dayFrom = Number.parseInt(fromDate.substr(0,2));
            var monthFrom = Number.parseInt(fromDate.substr(3,5));
            var yearFrom = Number.parseInt(fromDate.substr(6));

            var dayTo = Number.parseInt(toDate.substr(0,2));
            var monthTo = Number.parseInt(toDate.substr(3,5));
            var yearTo = Number.parseInt(toDate.substr(6));

            var newDateFrom = new Date(yearFrom,monthFrom-1,dayFrom);
            var newDateTo = new Date(yearTo,monthTo-1,dayTo);

            if(typeof newDateFrom !== 'undefined' && typeof newDateFrom !== 'undefined'){
                dateFromDisplayElement.text(dateFormatted(newDateFrom).fullDisplay);
                dateToDisplayElement.text(dateFormatted(newDateTo).fullDisplay);
            }

            var data = getVisitorsDataAjax('stats.visits-and-page-views', fromDate, toDate, quantity);
            var progressBar = generateProgressBar("#areaChart-progressbar", areaChartName);
            renderVisitorsAreaChart(data, "#areaChart", "#areaChart-container", "15.625em", progressBar, true);

            var visitorsData = getVisitorsDataAjax('stats.top-pages-between-dates', fromDate, toDate, quantity);
            var visitorsProgressBar = generateProgressBar("#visitorsTable-progressbar",dataTablesName);
            renderVisitorsDataTable(visitorsData,'table#visitorsTable',visitorsProgressBar, true);

            var geoChartData = getCountriesAndVisitorsAjax(fromDateInput.val(), toDateInput.val());
            var geoChartProgressBar = generateProgressBar("#countriesMap-progressbar",countriesMapName);
            renderVisitorsGoogleMap(geoChartData, '#regions_div', geoChartProgressBar, '100%', 'auto', true);

        });
    });
</script>
@endpush