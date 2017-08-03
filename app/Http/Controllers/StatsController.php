<?php

namespace App\Http\Controllers;

use Analytics;
use App\Libraries\Google\Analytics\GoogleAnalytics;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;

class StatsController extends Controller
{
    private $data = [];
    public function index()
    {
        //$data = GoogleAnalytics::visitors_and_pageviews();
        //dd($data);exit;
        $analyticsData_one = Analytics::fetchTotalVisitorsAndPageViews(Period::days(14));
        $this->data['dates'] = $analyticsData_one->pluck('date');
        $this->data['visitors'] = $analyticsData_one->pluck('visitors');
        $this->data['pageViews'] = $analyticsData_one->pluck('pageViews');

        $analyticsData_two = Analytics::fetchVisitorsAndPageViews(Period::days(14));
        $this->data['two_dates'] = $analyticsData_two->pluck('date');
        $this->data['two_visitors'] = $analyticsData_two->pluck('visitors')->count();
        $this->data['two_pageTitle'] = $analyticsData_two->pluck('pageTitle')->count();

        $analyticsData_three = Analytics::fetchMostVisitedPages(Period::days(14));
        $this->data['three_url'] = $analyticsData_three->pluck('url');
        $this->data['three_pageTitle'] = $analyticsData_three->pluck('pageTitle');
        $this->data['three_pageViews'] = $analyticsData_three->pluck('pageViews');

        $this->data['browserjson'] = GoogleAnalytics::topbrowsers();

        $result = GoogleAnalytics::country();
        $this->data['country'] = $result->pluck('country');
        $this->data['country_sessions'] = $result->pluck('sessions');

        $this->data['title'] = "Stats"; // set the page title
        //dd($this->data);
        return view('stats.dashboard', $this->data);
    }
}
