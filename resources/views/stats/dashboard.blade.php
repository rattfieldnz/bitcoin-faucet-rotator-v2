@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="/assets/css/datatables.net/datatables.min.css?{{ rand() }}" type="text/css">
@endsection

@section('content')
    <div style="margin: 0 0 0 1em;">
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
                                    <canvas id="areaChart" style="height:25em;"></canvas>
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
                                <div class="chart" id="pieChart-container">
                                    <canvas id="pieChart" style="height:35em;"></canvas>
                                </div>
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
@endpush