<?php

namespace App\Http\Controllers;

use Analytics;
use App\Helpers\Functions\Dates;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;

class StatsController extends Controller
{
    private $data = [];
    public function index()
    {

        $analyticsData_one = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
        $this->data['dates'] = $analyticsData_one->pluck('date');
        $this->data['visitors'] = $analyticsData_one->pluck('visitors');
        $this->data['pageViews'] = $analyticsData_one->pluck('pageViews');

        $analyticsData_three = Analytics::fetchMostVisitedPages(Period::days(7));
        $this->data['three_url'] = $analyticsData_three->pluck('url');
        $this->data['three_pageTitle'] = $analyticsData_three->pluck('pageTitle');
        $this->data['three_pageViews'] = $analyticsData_three->pluck('pageViews');

        $this->data['browserjson'] = GoogleAnalytics::topbrowsers(7);

        $result = GoogleAnalytics::countries(7);
        $this->data['countries'] = $result;

        $this->data['title'] = "Stats"; // set the page title
        return view('stats.dashboard', $this->data);
    }
}
