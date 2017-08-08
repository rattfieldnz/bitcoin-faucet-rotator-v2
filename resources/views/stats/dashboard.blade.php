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
                    <div class="box box-primary col-md-6">
                        <div class="box-header with-border">
                            <h3 class="box-title">Visitor and Page View</h3>

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

                    <!-- DONUT CHART -->
                    <div class="box box-danger col-md-6">
                        <div class="box-header with-border">
                            <h3 class="box-title">Browser</h3>

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
                <div class="row" style="margin: 0 0 0 0;">
                    <!-- /.col (LEFT) -->
                    <!-- VISITORS TABLE -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Most Popular Pages</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <div id="visitorsTable"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="row" style="margin: 0 0 0 0;">
                    <!-- COUNTRIES DONUT CHART -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Countries</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <canvas id="countriesPieChart" style="height:250px"></canvas>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.col (RIGHT) -->
                </div>
                <!-- /.row -->

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="/assets/js/datatables.net/datatables.min.js?{{ rand() }}"></script>
<script src="/assets/js/chart.js/Chart.min.js"></script>
<script>
    $(function () {

        function getRandomRgb() {
            var num = Math.round(0xffffff * Math.random());
            return {
                r: num >> 16,
                g: num >> 8 & 255,
                b: num & 255
            }
        }

        function rgbaString(r, g, b, a = 1){
            if(typeof r !== 'undefined' && typeof g !== 'undefined' && typeof b !== 'undefined'){
                return "rgba(" + r + "," + g + "," + b + "," + a + ")";
            } else {
                return null;
            }
        }

        function getRandomHexColor() {
            var length = 6;
            var chars = '0123456789ABCDEF';
            var hex = '#';
            while(length--) hex += chars[(Math.random() * 16) | 0];
            return hex;
        }

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
		let dataCount = {!! count($browserjson['datasets']['data']) !!}
		for(let i = 0; i < dataCount; i++){
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
        //- COUNTRIES PIE CHART -
        //-----------------------
        let countriesPieChartContext = document.getElementById('countriesPieChart').getContext("2d");
        let countriesPieChartOptions = {
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

        let countriesPieChartBgColors = [];
        let countriesPieChartDataCount = {!! count($country_sessions) !!}
        for(let i = 0; i < countriesPieChartDataCount; i++){
            countriesPieChartBgColors.push(getRandomHexColor());
        }

        let countriesPieChart = new Chart(
            countriesPieChartContext, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($country) !!},
                    datasets: [{
                        backgroundColor: countriesPieChartBgColors,
                        data: {!! json_encode($country_sessions) !!}
                    }],
                    options: countriesPieChartOptions
                }
            }
        );



        // VISITORS TABLE BELOW
        let dataToTable = function (dataset) {

            let getItem = function(dataset, index){
                return dataset.data[index];
            };

            let html = '<table><thead><tr>';

            let columnCount = 0;
            jQuery.each(dataset.datasets, function (idx, item) {
                html += '<th style="background-color:' + item.fillColor + ';">' + item.label + '</th>';
                columnCount += 1;
            });

            html += '</tr></thead><tbody>';

            for(let i = 0; i < dataset.datasets[0].data.length; i++){
                html += '<tr>';
                html += '<td>' + getItem(dataset.datasets[0], i) + '</td>';
                html += '<td>' + getItem(dataset.datasets[1], i) + '</td>';
                html += '<td>' + getItem(dataset.datasets[2], i) + '</td>';
                html += '</tr>';
            }

            html += '</tbody></table>';

            return html;
        };

        let data = {
            labels: [],
            datasets: [
                {
                    label: "URL",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: {!! $three_url->toJson() !!}
                },
                {
                    label: "Page Title",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: {!! $three_pageTitle->toJson() !!}
                },
                {
                    label: "Page Views",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: {!! $three_pageViews->toJson() !!}
                }
            ]
        };

        jQuery('#visitorsTable').html(dataToTable(data));

  });
</script>
@endpush

@push('google-analytics')
@include('layouts.partials.tracking._google_analytics')
@endpush