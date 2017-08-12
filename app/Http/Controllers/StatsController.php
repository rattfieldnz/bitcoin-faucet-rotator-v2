<?php

namespace App\Http\Controllers;

use Analytics;
use App\Helpers\Functions\Dates;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Yajra\Datatables\Facades\Datatables;

class StatsController extends Controller
{
    private $data = [];
    public function index()
    {

        $analyticsData_one = Analytics::fetchTotalVisitorsAndPageViews(Period::days(1));
        $this->data['dates'] = $analyticsData_one->pluck('date');
        $this->data['visitors'] = $analyticsData_one->pluck('visitors');
        $this->data['pageViews'] = $analyticsData_one->pluck('pageViews');

        $analyticsData_three = Analytics::fetchMostVisitedPages(Period::days(1));
        $this->data['three_url'] = $analyticsData_three->pluck('url');
        $this->data['three_pageTitle'] = $analyticsData_three->pluck('pageTitle');
        $this->data['three_pageViews'] = $analyticsData_three->pluck('pageViews');

        $this->data['browserjson'] = GoogleAnalytics::topbrowsers(1);

        $result = GoogleAnalytics::countries(1);
        $this->data['countries'] = $result;

        $dataTablesData = GoogleAnalytics::topPagesBetweenTwoDates('12-08-2017', '12-08-2017');
        $this->data['dataTables'] = Datatables::collection($dataTablesData)->make(true);

        //dd(json_encode($this->data['dataTables']));

        return view('stats.dashboard', $this->data);
    }
}
